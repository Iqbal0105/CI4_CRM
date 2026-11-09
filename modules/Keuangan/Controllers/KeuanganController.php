<?php

declare(strict_types=1);

namespace Modules\Keuangan\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Keuangan\Services\KeuanganService;

class KeuanganController extends BaseController
{
    protected KeuanganService $keuanganService;

    public function __construct()
    {
        $this->keuanganService = new KeuanganService();
    }

    public function piutang()
    {
        $db = \Config\Database::connect();
        $piutangList = $db->table('piutang_usaha pu')
            ->select('pu.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, so.nomor_so')
            ->join('pelanggan p', 'p.id = pu.pelanggan_id', 'left')
            ->join('sales_order so', 'so.id = pu.so_id', 'left')
            ->orderBy('pu.id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($piutangList as &$pu) {
            $pu['telah_dibayar'] = (float)$pu['total'] - (float)$pu['sisa'];
        }
        unset($pu);

        $totalHakPiutang = array_sum(array_column($piutangList, 'total'));
        $totalDiterima = array_sum(array_column($piutangList, 'telah_dibayar'));
        $sisaBelumLunas = array_sum(array_column($piutangList, 'sisa'));
        $overdueCount = 0;
        foreach ($piutangList as $pu) {
            if ($pu['status'] !== 'lunas' && !empty($pu['jatuh_tempo']) && $pu['jatuh_tempo'] < date('Y-m-d')) {
                $overdueCount++;
            }
        }

        $bkmList = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, akb.nama_akun as nama_kas_bank')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->orderBy('pp.tanggal_bayar', 'DESC')
            ->orderBy('pp.id', 'DESC')
            ->limit(25)
            ->get()
            ->getResultArray();

        $kasBankList = $db->table('akun_kas_bank')->where('status', 'active')->get()->getResultArray();

        $data = [
            'title'           => 'Piutang Usaha (Accounts Receivable)',
            'piutangList'     => $piutangList,
            'bkmList'         => $bkmList,
            'kasBankList'     => $kasBankList,
            'totalHakPiutang' => $totalHakPiutang,
            'totalDiterima'   => $totalDiterima,
            'sisaBelumLunas'  => $sisaBelumLunas,
            'overdueCount'    => $overdueCount,
        ];

        return view('Modules\Keuangan\Views\piutang', $data);
    }

    public function bkm()
    {
        $db = \Config\Database::connect();

        $bkmList = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, p.kode_pelanggan, pu.nomor_piutang, akb.nama_akun as nama_kas_bank, u.username as petugas')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('piutang_usaha pu', 'pu.id = pp.piutang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pp.user_id', 'left')
            ->orderBy('pp.tanggal_bayar', 'DESC')
            ->orderBy('pp.id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Penerimaan Kas Masuk (Bukti Kas Masuk - BKM)',
            'bkmList' => $bkmList,
        ];

        return view('Modules\Keuangan\Views\bkm', $data);
    }

    public function storeBayarPiutang()
    {
        $rules = [
            'piutang_id'       => 'required|is_natural_no_zero',
            'akun_kas_bank_id' => 'required|is_natural_no_zero',
            'nominal'          => 'required|numeric',
            'tanggal_bayar'    => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $piutangId = (int) $this->request->getPost('piutang_id');
        $kasBankId = (int) $this->request->getPost('akun_kas_bank_id');
        $nominal = (float) $this->request->getPost('nominal');
        $tanggal = (string) $this->request->getPost('tanggal_bayar');
        $metode = (string) ($this->request->getPost('metode_pembayaran') ?: 'transfer');
        $catatan = trim((string) $this->request->getPost('catatan'));

        try {
            $nomorBKM = $this->keuanganService->bayarPiutang($piutangId, $kasBankId, $nominal, $tanggal, $metode, $catatan);
            return redirect()->to('keuangan/piutang')->with('success', "Pembayaran piutang berhasil dicatat! Terbit Bukti Kas Masuk (BKM): #{$nomorBKM}");
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function utang()
    {
        $this->keuanganService->syncMissingUtangFromPO();

        $db = \Config\Database::connect();
        $utangList = $db->table('utang_usaha uu')
            ->select('uu.*, s.nama_supplier, s.kode_supplier, po.nomor_po')
            ->join('supplier s', 's.id = uu.supplier_id', 'left')
            ->join('purchase_order po', 'po.id = uu.po_id', 'left')
            ->orderBy('uu.tanggal', 'DESC')
            ->orderBy('uu.id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($utangList as &$uu) {
            $uu['telah_dibayar'] = (float)$uu['total'] - (float)$uu['sisa'];
        }
        unset($uu);

        $totalKewajibanUtang = array_sum(array_column($utangList, 'total'));
        $totalTelahDibayar = array_sum(array_column($utangList, 'telah_dibayar'));
        $sisaBelumLunas = array_sum(array_column($utangList, 'sisa'));
        $overdueCount = 0;
        foreach ($utangList as $uu) {
            if ($uu['status'] !== 'lunas' && !empty($uu['jatuh_tempo']) && $uu['jatuh_tempo'] < date('Y-m-d')) {
                $overdueCount++;
            }
        }

        $bkkList = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, akb.nama_akun as nama_kas_bank')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->orderBy('pu.tanggal_bayar', 'DESC')
            ->orderBy('pu.id', 'DESC')
            ->limit(25)
            ->get()
            ->getResultArray();

        $kasBankList = $db->table('akun_kas_bank')->where('status', 'active')->get()->getResultArray();

        $data = [
            'title'               => 'Utang Usaha (Accounts Payable)',
            'utangList'           => $utangList,
            'bkkList'             => $bkkList,
            'kasBankList'         => $kasBankList,
            'totalKewajibanUtang' => $totalKewajibanUtang,
            'totalTelahDibayar'   => $totalTelahDibayar,
            'sisaBelumLunas'      => $sisaBelumLunas,
            'overdueCount'        => $overdueCount,
        ];

        return view('Modules\Keuangan\Views\utang', $data);
    }

    public function bkk()
    {
        $db = \Config\Database::connect();

        $bkkList = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, s.kode_supplier, uu.nomor_utang, akb.nama_akun as nama_kas_bank, u.username as dicatat_oleh')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('utang_usaha uu', 'uu.id = pu.utang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pu.user_id', 'left')
            ->orderBy('pu.tanggal_bayar', 'DESC')
            ->orderBy('pu.id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Pembayaran Kas Keluar (Disbursement - BKK)',
            'bkkList' => $bkkList,
        ];

        return view('Modules\Keuangan\Views\bkk', $data);
    }

    public function storeBayarUtang()
    {
        $rules = [
            'utang_id'         => 'required|is_natural_no_zero',
            'akun_kas_bank_id' => 'required|is_natural_no_zero',
            'nominal'          => 'required|numeric',
            'tanggal_bayar'    => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $utangId = (int) $this->request->getPost('utang_id');
        $kasBankId = (int) $this->request->getPost('akun_kas_bank_id');
        $nominal = (float) $this->request->getPost('nominal');
        $tanggal = (string) $this->request->getPost('tanggal_bayar');
        $metode = (string) ($this->request->getPost('metode_pembayaran') ?: 'transfer');
        $catatan = trim((string) $this->request->getPost('catatan'));

        try {
            $nomorBKK = $this->keuanganService->bayarUtang($utangId, $kasBankId, $nominal, $tanggal, $metode, $catatan);
            return redirect()->to('keuangan/utang')->with('success', "Pembayaran utang berhasil dicatat! Terbit Bukti Kas Keluar (BKK): #{$nomorBKK}");
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function jurnal()
    {
        $db = \Config\Database::connect();

        $tglDari = $this->request->getGet('tgl_dari');
        $tglSampai = $this->request->getGet('tgl_sampai');

        $builder = $db->table('jurnal j')
            ->select('j.*, u.username')
            ->join('users u', 'u.id = j.user_id', 'left');

        if (!empty($tglDari)) {
            $builder->where('j.tanggal >=', $tglDari);
        }
        if (!empty($tglSampai)) {
            $builder->where('j.tanggal <=', $tglSampai);
        }

        $jurnalList = $builder->orderBy('j.tanggal', 'DESC')
            ->orderBy('j.id', 'DESC')
            ->limit(250)
            ->get()
            ->getResultArray();

        // Get details for all listed journals
        $jurnalIds = array_column($jurnalList, 'id');
        $details = [];
        if (!empty($jurnalIds)) {
            $rawDetails = $db->table('jurnal_detail jd')
                ->select('jd.*, c.kode_akun, c.nama_akun')
                ->join('coa c', 'c.id = jd.coa_id')
                ->whereIn('jd.jurnal_id', $jurnalIds)
                ->orderBy('jd.id', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($rawDetails as $rd) {
                $details[$rd['jurnal_id']][] = $rd;
            }
        }

        // Preload entity maps for Pihak Terkait lookup
        $allRefs = array_filter(array_column($jurnalList, 'referensi'));
        $soMap = [];
        $poMap = [];
        $bkkMap = [];
        $bkmMap = [];
        $piuMap = [];
        $utangMap = [];

        if (!empty($allRefs)) {
            // 1. Sales Orders
            $soRows = $db->table('sales_order so')
                ->select('so.nomor_so, p.nama_pelanggan')
                ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
                ->whereIn('so.nomor_so', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($soRows as $r) {
                $soMap[$r['nomor_so']] = $r['nama_pelanggan'];
            }

            // 2. Purchase Orders
            $poRows = $db->table('purchase_order po')
                ->select('po.nomor_po, s.nama_supplier')
                ->join('supplier s', 's.id = po.supplier_id', 'left')
                ->whereIn('po.nomor_po', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($poRows as $r) {
                $poMap[$r['nomor_po']] = $r['nama_supplier'];
            }

            // 3. BKK (Pembayaran Utang)
            $bkkRows = $db->table('pembayaran_utang pu')
                ->select('pu.nomor_bkk, s.nama_supplier')
                ->join('supplier s', 's.id = pu.supplier_id', 'left')
                ->whereIn('pu.nomor_bkk', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($bkkRows as $r) {
                $bkkMap[$r['nomor_bkk']] = $r['nama_supplier'];
            }

            // 4. BKM (Pembayaran Piutang)
            $bkmRows = $db->table('pembayaran_piutang pp')
                ->select('pp.nomor_bkm, p.nama_pelanggan')
                ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
                ->whereIn('pp.nomor_bkm', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($bkmRows as $r) {
                $bkmMap[$r['nomor_bkm']] = $r['nama_pelanggan'];
            }

            // 5. Piutang Usaha
            $piuRows = $db->table('piutang_usaha pu')
                ->select('pu.nomor_piutang, p.nama_pelanggan')
                ->join('pelanggan p', 'p.id = pu.pelanggan_id', 'left')
                ->whereIn('pu.nomor_piutang', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($piuRows as $r) {
                $piuMap[$r['nomor_piutang']] = $r['nama_pelanggan'];
            }

            // 6. Utang Usaha
            $utangRows = $db->table('utang_usaha uu')
                ->select('uu.nomor_utang, s.nama_supplier')
                ->join('supplier s', 's.id = uu.supplier_id', 'left')
                ->whereIn('uu.nomor_utang', $allRefs)
                ->get()
                ->getResultArray();
            foreach ($utangRows as $r) {
                $utangMap[$r['nomor_utang']] = $r['nama_supplier'];
            }
        }

        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($jurnalList as &$j) {
            $ref = trim((string)($j['referensi'] ?? ''));
            $ket = trim((string)($j['keterangan'] ?? ''));
            $nominal = (float)($j['total_debit'] ?? 0);
            $refUpper = strtoupper($ref);
            $ketUpper = strtoupper($ket);

            $modulBadge = 'Penjualan (Invoice)';
            $modulKey = 'penjualan';
            $modulStyle = 'background-color: #e0f2fe; color: #0284c7;';
            $pihakTerkait = 'Pelanggan Umum';
            $isDebit = true;

            if (str_starts_with($refUpper, 'PO') || str_contains($ketUpper, 'PEMBELIAN') || str_contains($ketUpper, 'PENERIMAAN PO')) {
                $modulBadge = 'Purchase Order (PO)';
                $modulKey = 'pembelian';
                $modulStyle = 'background-color: #fef3c7; color: #b45309;';
                $pihakTerkait = $poMap[$ref] ?? 'Supplier';
                $isDebit = false;
            } elseif (str_starts_with($refUpper, 'BKK') || str_contains($ketUpper, 'KAS KELUAR') || str_contains($ketUpper, 'PELUNASAN UTANG')) {
                $modulBadge = 'Pengeluaran Kas (BKK)';
                $modulKey = 'pengeluaran';
                $modulStyle = 'background-color: #ffedd5; color: #c2410c;';
                $pihakTerkait = $bkkMap[$ref] ?? 'Supplier';
                $isDebit = false;
            } elseif (str_starts_with($refUpper, 'AP') || str_starts_with($refUpper, 'UTG') || str_contains($ketUpper, 'UTANG USAHA')) {
                $modulBadge = 'Utang Usaha (AP)';
                $modulKey = 'utang';
                $modulStyle = 'background-color: #fee2e2; color: #b91c1c;';
                $pihakTerkait = $utangMap[$ref] ?? 'Supplier';
                $isDebit = false;
            } elseif (str_starts_with($refUpper, 'BKM') || str_contains($ketUpper, 'KAS MASUK') || str_contains($ketUpper, 'PEMBAYARAN PIUTANG')) {
                $modulBadge = 'Kas Masuk (BKM)';
                $modulKey = 'penerimaan';
                $modulStyle = 'background-color: #dcfce7; color: #15803d;';
                $pihakTerkait = $bkmMap[$ref] ?? 'Pelanggan Umum';
                $isDebit = true;
            } elseif (str_starts_with($refUpper, 'PIU') || str_contains($ketUpper, 'PIUTANG USAHA') || str_contains($ketUpper, 'HAK TAGIH')) {
                $modulBadge = 'Piutang Usaha (AR)';
                $modulKey = 'piutang';
                $modulStyle = 'background-color: #ede9fe; color: #6d28d9;';
                $pihakTerkait = $piuMap[$ref] ?? 'Pelanggan';
                $isDebit = true;
            } elseif (str_starts_with($refUpper, 'RET') || str_contains($ketUpper, 'RETUR')) {
                if (str_contains($ketUpper, 'RETUR PEMBELIAN')) {
                    $modulBadge = 'Retur Pembelian';
                    $modulKey = 'pembelian';
                    $modulStyle = 'background-color: #cffafe; color: #0891b2;';
                    $pihakTerkait = 'Supplier';
                    $isDebit = true;
                } else {
                    $modulBadge = 'Retur Penjualan';
                    $modulKey = 'penjualan';
                    $modulStyle = 'background-color: #fee2e2; color: #dc2626;';
                    $pihakTerkait = 'Pelanggan';
                    $isDebit = false;
                }
            } else {
                $modulBadge = 'Penjualan (Invoice)';
                $modulKey = 'penjualan';
                $modulStyle = 'background-color: #e0f2fe; color: #0284c7;';
                $pihakTerkait = $soMap[$ref] ?? ($j['username'] ?? 'Pelanggan Umum');
                $isDebit = true;
            }

            $j['modul_badge'] = $modulBadge;
            $j['modul_key'] = $modulKey;
            $j['modul_style'] = $modulStyle;
            $j['pihak_terkait'] = $pihakTerkait;
            $j['is_debit'] = $isDebit;
            $j['nominal'] = $nominal;

            if ($isDebit) {
                $totalMasuk += $nominal;
            } else {
                $totalKeluar += $nominal;
            }
        }
        unset($j);

        $arusKas = $totalMasuk - $totalKeluar;
        $totalCount = count($jurnalList);
        $coaList = $db->table('coa')->orderBy('kode_akun', 'ASC')->get()->getResultArray();

        $data = [
            'title'       => 'Buku Besar (General Ledger)',
            'jurnalList'  => $jurnalList,
            'details'     => $details,
            'coaList'     => $coaList,
            'totalDebit'  => (float) $totalMasuk,
            'totalKredit' => (float) $totalKeluar,
            'arusKas'     => (float) $arusKas,
            'totalCount'  => $totalCount,
            'tglDari'     => $tglDari ?? date('Y-m-01'),
            'tglSampai'   => $tglSampai ?? date('Y-m-d'),
        ];

        return view('Modules\Keuangan\Views\jurnal', $data);
    }

    public function kasBank()
    {
        $db = \Config\Database::connect();
        $accounts = $db->table('akun_kas_bank akb')
            ->select('akb.*, c.kode_akun as coa_kode, c.nama_akun as coa_nama')
            ->join('coa c', 'c.id = akb.coa_id', 'left')
            ->get()
            ->getResultArray();

        $coaList = $db->table('coa')->where('tipe', 'aset')->get()->getResultArray();

        $data = [
            'title'    => 'Manajemen Akun Kas & Bank',
            'accounts' => $accounts,
            'coaList'  => $coaList,
        ];

        return view('Modules\Keuangan\Views\kas_bank', $data);
    }

    public function storeKasBank()
    {
        $rules = [
            'kode_akun' => 'required|max_length[50]|is_unique[akun_kas_bank.kode_akun]',
            'nama_akun' => 'required|max_length[150]',
            'tipe'      => 'required|in_list[kas,bank,qris,petty_cash]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->table('akun_kas_bank')->insert([
            'kode_akun'      => strtoupper(trim((string) $this->request->getPost('kode_akun'))),
            'nama_akun'      => trim((string) $this->request->getPost('nama_akun')),
            'nomor_rekening' => trim((string) $this->request->getPost('nomor_rekening')) ?: null,
            'atas_nama'      => trim((string) $this->request->getPost('atas_nama')) ?: null,
            'coa_id'         => $this->request->getPost('coa_id') ?: null,
            'saldo'          => money_round($this->request->getPost('saldo') ?: 0),
            'tipe'           => $this->request->getPost('tipe'),
            'status'         => 'active',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('keuangan/kas-bank')->with('success', 'Akun kas/bank berhasil ditambahkan.');
    }

    public function bkmThermalData(int $id)
    {
        $db = \Config\Database::connect();
        $bkm = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, pu.nomor_piutang, akb.nama_akun as nama_kas_bank, u.username as petugas')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('piutang_usaha pu', 'pu.id = pp.piutang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pp.user_id', 'left')
            ->where('pp.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkm) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Bukti Kas Masuk tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'type'   => 'BKM',
            'title'  => 'BUKTI KAS MASUK (BKM)',
            'data'   => $bkm,
            'store'  => [
                'name'    => 'METAFORA ERP KEUANGAN',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
            ]
        ]);
    }

    public function bkkThermalData(int $id)
    {
        $db = \Config\Database::connect();
        $bkk = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, s.kode_supplier, s.no_telp, uu.nomor_utang, akb.nama_akun as nama_kas_bank, u.username as dicatat_oleh')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('utang_usaha uu', 'uu.id = pu.utang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pu.user_id', 'left')
            ->where('pu.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkk) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Bukti Kas Keluar tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'type'   => 'BKK',
            'title'  => 'BUKTI KAS KELUAR (BKK)',
            'data'   => $bkk,
            'store'  => [
                'name'    => 'METAFORA ERP KEUANGAN',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
            ]
        ]);
    }

    public function bkmPrint(int $id)
    {
        $db = \Config\Database::connect();
        $bkm = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, pu.nomor_piutang, so.nomor_so, akb.nama_akun as nama_kas_bank, u.username as petugas')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('piutang_usaha pu', 'pu.id = pp.piutang_id', 'left')
            ->join('sales_order so', 'so.id = pu.so_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pp.user_id', 'left')
            ->where('pp.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkm) {
            return redirect()->to('keuangan/bkm')->with('error', 'Bukti Kas Masuk tidak ditemukan.');
        }

        $data = [
            'title' => 'Bukti Kas Masuk #' . ($bkm['nomor_bkm'] ?? $bkm['id']),
            'bkm'   => $bkm,
            'isPdf' => false,
        ];

        return view('Modules\Keuangan\Views\bkm_voucher', $data);
    }

    public function bkmPdf(int $id)
    {
        $db = \Config\Database::connect();
        $bkm = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, pu.nomor_piutang, so.nomor_so, akb.nama_akun as nama_kas_bank, u.username as petugas')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('piutang_usaha pu', 'pu.id = pp.piutang_id', 'left')
            ->join('sales_order so', 'so.id = pu.so_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pp.user_id', 'left')
            ->where('pp.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkm) {
            return redirect()->to('keuangan/bkm')->with('error', 'Bukti Kas Masuk tidak ditemukan.');
        }

        $html = view('Modules\Keuangan\Views\bkm_voucher', [
            'title' => 'Bukti Kas Masuk #' . ($bkm['nomor_bkm'] ?? $bkm['id']),
            'bkm'   => $bkm,
            'isPdf' => true,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape');
        $dompdf->render();

        $dompdf->stream('BKM-' . ($bkm['nomor_bkm'] ?? $id) . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function bkkPrint(int $id)
    {
        $db = \Config\Database::connect();
        $bkk = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, s.kode_supplier, s.no_telp, s.alamat, uu.nomor_utang, po.nomor_po, akb.nama_akun as nama_kas_bank, u.username as dicatat_oleh')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('utang_usaha uu', 'uu.id = pu.utang_id', 'left')
            ->join('purchase_order po', 'po.id = uu.po_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pu.user_id', 'left')
            ->where('pu.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkk) {
            return redirect()->to('keuangan/bkk')->with('error', 'Bukti Kas Keluar tidak ditemukan.');
        }

        $data = [
            'title' => 'Bukti Kas Keluar #' . ($bkk['nomor_bkk'] ?? $bkk['id']),
            'bkk'   => $bkk,
            'isPdf' => false,
        ];

        return view('Modules\Keuangan\Views\bkk_voucher', $data);
    }

    public function bkkPdf(int $id)
    {
        $db = \Config\Database::connect();
        $bkk = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, s.kode_supplier, s.no_telp, s.alamat, uu.nomor_utang, po.nomor_po, akb.nama_akun as nama_kas_bank, u.username as dicatat_oleh')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('utang_usaha uu', 'uu.id = pu.utang_id', 'left')
            ->join('purchase_order po', 'po.id = uu.po_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pu.user_id', 'left')
            ->where('pu.id', $id)
            ->get()
            ->getRowArray();

        if (!$bkk) {
            return redirect()->to('keuangan/bkk')->with('error', 'Bukti Kas Keluar tidak ditemukan.');
        }

        $html = view('Modules\Keuangan\Views\bkk_voucher', [
            'title' => 'Bukti Kas Keluar #' . ($bkk['nomor_bkk'] ?? $bkk['id']),
            'bkk'   => $bkk,
            'isPdf' => true,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape');
        $dompdf->render();

        $dompdf->stream('BKK-' . ($bkk['nomor_bkk'] ?? $id) . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function exportPiutang()
    {
        $db = \Config\Database::connect();
        $rows = $db->table('piutang_usaha pu')
            ->select('pu.nomor_piutang, so.nomor_so, p.nama_pelanggan, p.kode_pelanggan, pu.total, pu.sisa, pu.jatuh_tempo, pu.status, pu.created_at')
            ->join('pelanggan p', 'p.id = pu.pelanggan_id', 'left')
            ->join('sales_order so', 'so.id = pu.so_id', 'left')
            ->orderBy('pu.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor Piutang', 'No. SO', 'Pelanggan', 'Kode Pelanggan',
            'Total Tagihan (Rp)', 'Telah Dibayar (Rp)', 'Sisa Piutang (Rp)', 'Jatuh Tempo', 'Status'
        ];

        $dataRows = [];
        foreach ($rows as $idx => $r) {
            $dibayar = (float)$r['total'] - (float)$r['sisa'];
            $dataRows[] = [
                $idx + 1,
                $r['nomor_piutang'],
                $r['nomor_so'] ?? '-',
                $r['nama_pelanggan'] ?? 'Umum',
                $r['kode_pelanggan'] ?? '-',
                $r['total'],
                $dibayar,
                $r['sisa'],
                $r['jatuh_tempo'] ?? '-',
                strtoupper($r['status']),
            ];
        }

        export_csv_file('Export_Piutang_Usaha_' . date('Ymd_His') . '.csv', $headers, $dataRows);
    }

    public function exportUtang()
    {
        $this->keuanganService->syncMissingUtangFromPO();

        $db = \Config\Database::connect();
        $rows = $db->table('utang_usaha uu')
            ->select('uu.nomor_utang, po.nomor_po, s.nama_supplier, s.kode_supplier, uu.total, uu.sisa, uu.jatuh_tempo, uu.status, uu.created_at')
            ->join('supplier s', 's.id = uu.supplier_id', 'left')
            ->join('purchase_order po', 'po.id = uu.po_id', 'left')
            ->orderBy('uu.tanggal', 'DESC')
            ->orderBy('uu.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor Utang', 'No. Purchase Order', 'Supplier', 'Kode Supplier',
            'Total Utang (Rp)', 'Telah Dibayar (Rp)', 'Sisa Utang (Rp)', 'Jatuh Tempo', 'Status'
        ];

        $dataRows = [];
        foreach ($rows as $idx => $r) {
            $dibayar = (float)$r['total'] - (float)$r['sisa'];
            $dataRows[] = [
                $idx + 1,
                $r['nomor_utang'],
                $r['nomor_po'] ?? '-',
                $r['nama_supplier'] ?? 'Supplier',
                $r['kode_supplier'] ?? '-',
                $r['total'],
                $dibayar,
                $r['sisa'],
                $r['jatuh_tempo'] ?? '-',
                strtoupper($r['status']),
            ];
        }

        export_csv_file('Export_Utang_Usaha_' . date('Ymd_His') . '.csv', $headers, $dataRows);
    }

    public function exportJurnal()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal_detail jd')
            ->select('j.nomor_jurnal, j.tanggal, j.referensi, j.keterangan, u.username, c.kode_akun, c.nama_akun, c.tipe as tipe_akun, jd.memo, jd.debit, jd.kredit')
            ->join('jurnal j', 'j.id = jd.jurnal_id', 'left')
            ->join('coa c', 'c.id = jd.coa_id', 'left')
            ->join('users u', 'u.id = j.user_id', 'left');

        $tglDari = $this->request->getGet('tgl_dari');
        $tglSampai = $this->request->getGet('tgl_sampai');

        if (!empty($tglDari)) {
            $builder->where('j.tanggal >=', $tglDari);
        }
        if (!empty($tglSampai)) {
            $builder->where('j.tanggal <=', $tglSampai);
        }

        $rows = $builder->orderBy('j.tanggal', 'DESC')
            ->orderBy('j.id', 'DESC')
            ->orderBy('jd.id', 'ASC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor Jurnal', 'Tanggal', 'No. Dokumen Ref', 'Kategori Modul',
            'Kode Akun (COA)', 'Nama Akun', 'Tipe Akun', 'Uraian / Keterangan', 'Memo Baris', 'Debit (Rp)', 'Kredit (Rp)', 'Petugas'
        ];

        $dataRows = [];
        foreach ($rows as $idx => $r) {
            $ref = $r['referensi'] ?? '';
            $ket = $r['keterangan'] ?? '';
            $kategori = 'Jurnal Umum';
            $refUpper = strtoupper(trim((string)$ref));
            $ketUpper = strtoupper(trim((string)$ket));

            if (str_starts_with($refUpper, 'TRX') || str_starts_with($refUpper, 'SO') || str_starts_with($refUpper, 'INV') || str_contains($ketUpper, 'PENJUALAN') || str_contains($ketUpper, 'POS')) {
                $kategori = 'Penjualan';
            } elseif (str_starts_with($refUpper, 'PO') || str_contains($ketUpper, 'PEMBELIAN')) {
                $kategori = 'Pembelian';
            } elseif (str_starts_with($refUpper, 'BKM') || str_starts_with($refUpper, 'PIU') || str_contains($ketUpper, 'PIUTANG') || str_contains($ketUpper, 'KAS MASUK')) {
                $kategori = 'Piutang / Kas Masuk';
            } elseif (str_starts_with($refUpper, 'BKK') || str_starts_with($refUpper, 'UTG') || str_contains($ketUpper, 'UTANG') || str_contains($ketUpper, 'KAS KELUAR')) {
                $kategori = 'Utang / Kas Keluar';
            } elseif (str_starts_with($refUpper, 'RET') || str_contains($ketUpper, 'RETUR')) {
                $kategori = 'Retur';
            }

            $dataRows[] = [
                $idx + 1,
                $r['nomor_jurnal'] ?? '-',
                $r['tanggal'] ?? '-',
                $ref !== '' ? $ref : '-',
                $kategori,
                $r['kode_akun'] ?? '-',
                $r['nama_akun'] ?? '-',
                ucfirst($r['tipe_akun'] ?? '-'),
                $ket !== '' ? $ket : '-',
                !empty($r['memo']) ? $r['memo'] : '-',
                (float) ($r['debit'] ?? 0),
                (float) ($r['kredit'] ?? 0),
                $r['username'] ?? 'Admin',
            ];
        }

        export_csv_file('Export_Buku_Besar_GL_' . date('Ymd_His') . '.csv', $headers, $dataRows);
    }

    public function exportBkm()
    {
        $db = \Config\Database::connect();
        $bkmList = $db->table('pembayaran_piutang pp')
            ->select('pp.*, p.nama_pelanggan, p.kode_pelanggan, pu.nomor_piutang, akb.nama_akun as nama_kas_bank, u.username as petugas')
            ->join('pelanggan p', 'p.id = pp.pelanggan_id', 'left')
            ->join('piutang_usaha pu', 'pu.id = pp.piutang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pp.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pp.user_id', 'left')
            ->orderBy('pp.tanggal_bayar', 'DESC')
            ->orderBy('pp.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'No. BKM', 'Tanggal Terima', 'Pelanggan', 'Kode Pelanggan',
            'No. Tagihan Piutang', 'Akun Kas / Bank', 'Metode Pembayaran', 
            'Nominal Diterima (Rp)', 'Petugas', 'Catatan'
        ];

        $dataRows = [];
        foreach ($bkmList as $idx => $bkm) {
            $dataRows[] = [
                $idx + 1,
                $bkm['nomor_bkm'] ?? '-',
                $bkm['tanggal_bayar'] ?? '-',
                $bkm['nama_pelanggan'] ?? 'Pelanggan Umum',
                $bkm['kode_pelanggan'] ?? '-',
                $bkm['nomor_piutang'] ?? '-',
                $bkm['nama_kas_bank'] ?? 'Kas Utama',
                strtoupper($bkm['metode_pembayaran'] ?? 'TUNAI'),
                (float) ($bkm['nominal'] ?? 0),
                $bkm['petugas'] ?? 'Finance',
                $bkm['catatan'] ?? '-',
            ];
        }

        export_csv_file('Export_Bukti_Kas_Masuk_BKM_' . date('Ymd_His') . '.csv', $headers, $dataRows);
    }

    public function exportBkk()
    {
        $db = \Config\Database::connect();
        $bkkList = $db->table('pembayaran_utang pu')
            ->select('pu.*, s.nama_supplier, s.kode_supplier, uu.nomor_utang, akb.nama_akun as nama_kas_bank, u.username as dicatat_oleh')
            ->join('supplier s', 's.id = pu.supplier_id', 'left')
            ->join('utang_usaha uu', 'uu.id = pu.utang_id', 'left')
            ->join('akun_kas_bank akb', 'akb.id = pu.akun_kas_bank_id', 'left')
            ->join('users u', 'u.id = pu.user_id', 'left')
            ->orderBy('pu.tanggal_bayar', 'DESC')
            ->orderBy('pu.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'No. BKK', 'Tanggal Bayar', 'Supplier', 'Kode Supplier',
            'No. Tagihan Utang', 'Akun Kas / Bank', 'Metode Pembayaran', 
            'Nominal Pengeluaran (Rp)', 'Dicatat Oleh', 'Catatan'
        ];

        $dataRows = [];
        foreach ($bkkList as $idx => $bkk) {
            $dataRows[] = [
                $idx + 1,
                $bkk['nomor_bkk'] ?? '-',
                $bkk['tanggal_bayar'] ?? '-',
                $bkk['nama_supplier'] ?? 'Supplier Umum',
                $bkk['kode_supplier'] ?? '-',
                $bkk['nomor_utang'] ?? '-',
                $bkk['nama_kas_bank'] ?? 'Kas Utama',
                strtoupper($bkk['metode_pembayaran'] ?? 'TRANSFER'),
                (float) ($bkk['nominal'] ?? 0),
                $bkk['dicatat_oleh'] ?? 'Finance',
                $bkk['catatan'] ?? '-',
            ];
        }

        export_csv_file('Export_Bukti_Kas_Keluar_BKK_' . date('Ymd_His') . '.csv', $headers, $dataRows);
    }
}

