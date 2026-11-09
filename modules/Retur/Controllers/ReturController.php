<?php

declare(strict_types=1);

namespace Modules\Retur\Controllers;

use App\Controllers\BaseController;
use Modules\Retur\Services\ReturService;

class ReturController extends BaseController
{
    protected ReturService $returService;

    public function __construct()
    {
        $this->returService = new ReturService();
    }

    public function penjualan()
    {
        $db = \Config\Database::connect();
        $returns = $db->table('retur_penjualan rp')
            ->select('rp.*, so.nomor_so, p.nama_pelanggan')
            ->join('sales_order so', 'so.id = rp.so_id', 'left')
            ->join('pelanggan p', 'p.id = rp.pelanggan_id', 'left')
            ->orderBy('rp.tanggal', 'DESC')
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Retur Penjualan (Customer Returns)',
            'returns' => $returns,
        ];

        return view('Modules\Retur\Views\penjualan', $data);
    }

    public function createPenjualan()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('sales_order so')
            ->select('so.id, so.nomor_so, so.tanggal_so, so.total, p.nama_pelanggan, inv.nomor_invoice')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.status !=', 'cancelled')
            ->orderBy('so.id', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

        $data = [
            'title'  => 'Buat Retur Penjualan Baru',
            'orders' => $orders,
        ];

        return view('Modules\Retur\Views\create_penjualan', $data);
    }

    public function orderItems(int $soId)
    {
        $db = \Config\Database::connect();

        $so = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.no_telp, p.alamat, inv.nomor_invoice')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.id', $soId)
            ->get()
            ->getRowArray();

        if (!$so) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sales Order tidak ditemukan.']);
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.id as item_id, soi.produk_id, soi.qty, soi.harga_satuan, soi.diskon, soi.subtotal, pr.kode_produk, pr.nama_produk, s.nama_satuan')
            ->join('produk pr', 'pr.id = soi.produk_id', 'left')
            ->join('satuan s', 's.id = pr.satuan_id', 'left')
            ->where('soi.so_id', $soId)
            ->get()
            ->getResultArray();

        // Fallback jika item tercatat di penjualan_marketplace_item
        if (empty($items)) {
            $mpItems = $db->table('penjualan_marketplace pm')
                ->select('pmi.id as item_id, COALESCE(pmi.produk_id, pr.id) as produk_id, pmi.qty, pmi.harga_satuan, pmi.subtotal, COALESCE(pr.kode_produk, pmi.sku) as kode_produk, COALESCE(pr.nama_produk, pmi.nama_produk_marketplace) as nama_produk, s.nama_satuan')
                ->join('penjualan_marketplace_item pmi', 'pmi.penjualan_marketplace_id = pm.id', 'inner')
                ->join('produk pr', 'pr.id = pmi.produk_id OR (pmi.produk_id IS NULL AND pr.kode_produk = pmi.sku)', 'left')
                ->join('satuan s', 's.id = pr.satuan_id', 'left')
                ->where('pm.so_id', $soId)
                ->get()
                ->getResultArray();
            if (!empty($mpItems)) {
                $items = $mpItems;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'order'  => $so,
            'items'  => $items,
        ]);
    }

    public function storePenjualan()
    {
        $rules = [
            'so_id'          => 'required|is_natural_no_zero',
            'tindak_lanjut'  => 'required|in_list[ganti_barang,potong_piutang,refund]',
            'kondisi_barang' => 'required|in_list[baik,rusak]',
            'alasan'         => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $soId = (int) $this->request->getPost('so_id');
        $tindakLanjut = (string) $this->request->getPost('tindak_lanjut');
        $kondisi = (string) $this->request->getPost('kondisi_barang');
        $alasan = trim((string) $this->request->getPost('alasan'));

        $produkIds = $this->request->getPost('produk_id');
        $qtys = $this->request->getPost('qty');
        $hargas = $this->request->getPost('harga_satuan');

        if (empty($produkIds) || !is_array($produkIds)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu item harus dipilih untuk retur.');
        }

        $items = [];
        for ($i = 0; $i < count($produkIds); $i++) {
            $pId = (int) $produkIds[$i];
            $qty = (float) ($qtys[$i] ?? 0);
            $harga = (float) ($hargas[$i] ?? 0);

            if ($pId > 0 && $qty > 0) {
                $items[] = [
                    'produk_id'    => $pId,
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                ];
            }
        }

        try {
            $nomorRetur = $this->returService->prosesReturPenjualan($soId, $tindakLanjut, $kondisi, $alasan, $items);
            return redirect()->to('retur/penjualan')->with('success', "Retur Penjualan #{$nomorRetur} berhasil diproses!");
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }

    public function pembelian()
    {
        $db = \Config\Database::connect();
        $returns = $db->table('retur_pembelian rp')
            ->select('rp.*, po.nomor_po, s.nama_supplier')
            ->join('purchase_order po', 'po.id = rp.po_id', 'left')
            ->join('supplier s', 's.id = rp.supplier_id', 'left')
            ->orderBy('rp.tanggal', 'DESC')
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Retur Pembelian (Vendor Returns)',
            'returns' => $returns,
        ];

        return view('Modules\Retur\Views\pembelian', $data);
    }

    public function createPembelian()
    {
        $db = \Config\Database::connect();
        $pos = $db->table('purchase_order po')
            ->select('po.id, po.nomor_po, po.tanggal_po, s.nama_supplier')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->whereIn('po.status', ['received', 'partial'])
            ->orderBy('po.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Buat Retur Pembelian ke Supplier',
            'pos'   => $pos,
        ];

        return view('Modules\Retur\Views\create_pembelian', $data);
    }

    public function poItems(int $poId)
    {
        $db = \Config\Database::connect();
        
        $po = $db->table('purchase_order po')
            ->select('po.*, s.nama_supplier, s.no_telp, s.alamat')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->where('po.id', $poId)
            ->get()
            ->getRowArray();

        if (!$po) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase Order tidak ditemukan.']);
        }

        $items = $db->table('purchase_order_item poi')
            ->select('poi.id as item_id, poi.produk_id, poi.qty, poi.qty_diterima, poi.harga_satuan, poi.subtotal, pr.kode_produk, pr.nama_produk, s.nama_satuan')
            ->join('produk pr', 'pr.id = poi.produk_id', 'left')
            ->join('satuan s', 's.id = pr.satuan_id', 'left')
            ->where('poi.po_id', $poId)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'po'     => $po,
            'items'  => $items,
        ]);
    }

    public function storePembelian()
    {
        $rules = [
            'po_id'         => 'required|is_natural_no_zero',
            'tindak_lanjut' => 'required|in_list[potong_utang,refund_kas]',
            'alasan'        => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $poId = (int) $this->request->getPost('po_id');
        $tindakLanjut = (string) $this->request->getPost('tindak_lanjut');
        $alasan = trim((string) $this->request->getPost('alasan'));

        $produkIds = $this->request->getPost('produk_id');
        $qtys = $this->request->getPost('qty');
        $hargas = $this->request->getPost('harga_satuan');

        if (empty($produkIds) || !is_array($produkIds)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu item harus dipilih untuk retur pembelian.');
        }

        $items = [];
        for ($i = 0; $i < count($produkIds); $i++) {
            $pId = (int) $produkIds[$i];
            $qty = (float) ($qtys[$i] ?? 0);
            $harga = (float) ($hargas[$i] ?? 0);

            if ($pId > 0 && $qty > 0) {
                $items[] = [
                    'produk_id'    => $pId,
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                ];
            }
        }

        try {
            $nomorRetur = $this->returService->prosesReturPembelian($poId, $tindakLanjut, $alasan, $items);
            return redirect()->to('retur/pembelian')->with('success', "Retur Pembelian #{$nomorRetur} berhasil diproses!");
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses retur pembelian: ' . $e->getMessage());
        }
    }

    public function detailPenjualan(int $id)
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_penjualan rp')
            ->select('rp.*, so.nomor_so, so.tanggal_so, p.nama_pelanggan, p.no_telp, p.alamat')
            ->join('sales_order so', 'so.id = rp.so_id', 'left')
            ->join('pelanggan p', 'p.id = rp.pelanggan_id', 'left')
            ->where('rp.id', $id)
            ->get()
            ->getRowArray();

        if (!$ret) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data retur tidak ditemukan']);
        }

        $items = $db->table('retur_penjualan_item rpi')
            ->select('rpi.*, pr.kode_produk, pr.nama_produk, sat.nama_satuan')
            ->join('produk pr', 'pr.id = rpi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = pr.satuan_id', 'left')
            ->where('rpi.retur_id', $id)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'retur'   => $ret,
            'items'   => $items,
            'store'   => [
                'name'    => 'METAFORA ERP STORE',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
            ]
        ]);
    }

    public function deletePenjualan(int $id)
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_penjualan')->where('id', $id)->get()->getRowArray();
        if (!$ret) {
            return redirect()->to('retur/penjualan')->with('error', 'Data retur tidak ditemukan.');
        }

        $db->table('retur_penjualan_item')->where('retur_id', $id)->delete();
        $db->table('retur_penjualan')->where('id', $id)->delete();

        return redirect()->to('retur/penjualan')->with('success', 'Retur #' . $ret['nomor_retur'] . ' berhasil dihapus.');
    }

    public function exportPenjualan(string $format = 'csv')
    {
        $db = \Config\Database::connect();
        $rows = $db->table('retur_penjualan rp')
            ->select('rp.nomor_retur, so.nomor_so, p.nama_pelanggan, rp.tanggal, rp.total, rp.tindak_lanjut, rp.status')
            ->join('sales_order so', 'so.id = rp.so_id', 'left')
            ->join('pelanggan p', 'p.id = rp.pelanggan_id', 'left')
            ->orderBy('rp.tanggal', 'DESC')
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        $filename = 'Export_Retur_Penjualan_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');
        fputcsv($out, ['No Retur', 'No Transaksi SO', 'Pelanggan', 'Tanggal', 'Total (Rp)', 'Tindak Lanjut', 'Status']);
        foreach ($rows as $r) {
            fputcsv($out, [
                $r['nomor_retur'],
                $r['nomor_so'] ?? '-',
                $r['nama_pelanggan'] ?? 'Pelanggan Walk-In',
                $r['tanggal'],
                $r['total'],
                $r['tindak_lanjut'],
                $r['status'],
            ]);
        }
        fclose($out);
        exit;
    }

    public function detailPembelian(int $id)
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_pembelian rp')
            ->select('rp.*, po.nomor_po, po.tanggal_po, s.nama_supplier, s.no_telp, s.alamat')
            ->join('purchase_order po', 'po.id = rp.po_id', 'left')
            ->join('supplier s', 's.id = rp.supplier_id', 'left')
            ->where('rp.id', $id)
            ->get()
            ->getRowArray();

        if (!$ret) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data retur pembelian tidak ditemukan']);
        }

        $items = $db->table('retur_pembelian_item rpi')
            ->select('rpi.*, pr.kode_produk, pr.nama_produk, sat.nama_satuan')
            ->join('produk pr', 'pr.id = rpi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = pr.satuan_id', 'left')
            ->where('rpi.retur_id', $id)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'retur'   => $ret,
            'items'   => $items,
            'store'   => [
                'name'    => 'METAFORA ERP STORE',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
            ]
        ]);
    }

    public function deletePembelian(int $id)
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_pembelian')->where('id', $id)->get()->getRowArray();
        if (!$ret) {
            return redirect()->to('retur/pembelian')->with('error', 'Data retur pembelian tidak ditemukan.');
        }

        $db->table('retur_pembelian_item')->where('retur_id', $id)->delete();
        $db->table('retur_pembelian')->where('id', $id)->delete();

        return redirect()->to('retur/pembelian')->with('success', 'Retur Pembelian #' . $ret['nomor_retur'] . ' berhasil dihapus.');
    }

    public function exportPembelian(string $format = 'csv')
    {
        $db = \Config\Database::connect();
        $rows = $db->table('retur_pembelian rp')
            ->select('rp.nomor_retur, po.nomor_po, s.nama_supplier, rp.tanggal, rp.total, rp.tindak_lanjut')
            ->join('purchase_order po', 'po.id = rp.po_id', 'left')
            ->join('supplier s', 's.id = rp.supplier_id', 'left')
            ->orderBy('rp.tanggal', 'DESC')
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        $filename = 'Export_Retur_Pembelian_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');
        fputcsv($out, ['No Retur', 'No Purchase Order', 'Supplier', 'Tanggal', 'Total (Rp)', 'Tindak Lanjut']);
        foreach ($rows as $r) {
            fputcsv($out, [
                $r['nomor_retur'],
                $r['nomor_po'] ?? '-',
                $r['nama_supplier'] ?? 'Supplier',
                $r['tanggal'],
                $r['total'],
                $r['tindak_lanjut'],
            ]);
        }
        fclose($out);
        exit;
    }
}

