<?php

declare(strict_types=1);

namespace Modules\Stok\Controllers;

use App\Controllers\BaseController;
use Modules\Master\Models\ProdukModel;
use Modules\Stok\Services\StokService;

class StokController extends BaseController
{
    protected StokService $stokService;
    protected ProdukModel $produkModel;

    public function __construct()
    {
        $this->stokService = new StokService();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $items = $db->table('produk p')
            ->select('p.id, p.kode_produk, p.nama_produk, p.stok_minimum, s.id as stok_id, COALESCE(s.qty, 0) as fisik, COALESCE(s.reserved_qty, 0) as reserved, COALESCE(s.lokasi_rak, "RAK-UTAMA") as lokasi_rak, sat.nama_satuan, kat.nama_kategori')
            ->join('stok s', 's.produk_id = p.id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->join('kategori_produk kat', 'kat.id = p.kategori_id', 'left')
            ->where('p.status', 'active')
            ->where('p.deleted_at IS NULL')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        $totalQty = 0;
        $menipisItems = [];
        $habisItems = [];
        $normalItems = [];

        foreach ($items as $item) {
            $stokTersedia = (float) $item['fisik'] - (float) $item['reserved'];
            $totalQty += (float) $item['fisik'];
            $minStok = (int) $item['stok_minimum'];

            if ($stokTersedia <= 0) {
                $habisItems[] = $item;
            } elseif ($stokTersedia <= $minStok) {
                $menipisItems[] = $item;
            } else {
                $normalItems[] = $item;
            }
        }

        $data = [
            'title'        => 'Dashboard Stok Real-Time',
            'items'        => $items,
            'totalQty'     => $totalQty,
            'menipisItems' => $menipisItems,
            'habisItems'   => $habisItems,
            'normalItems'  => $normalItems,
        ];

        return view('Modules\Stok\Views\index', $data);
    }

    public function kartu(int $produkId)
    {
        $produk = $this->produkModel->find($produkId);
        if (!$produk) {
            return redirect()->to('stok')->with('error', 'Produk tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $logs = $db->table('stok_log sl')
            ->select('sl.*, u.username as operator_nama')
            ->join('users u', 'u.id = sl.operator_id', 'left')
            ->where('sl.produk_id', $produkId)
            ->orderBy('sl.created_at', 'DESC')
            ->orderBy('sl.id', 'DESC')
            ->get()
            ->getResultArray();

        $currentStok = $this->stokService->getStok($produkId);

        $data = [
            'title'       => 'Kartu Stok - ' . $produk['nama_produk'],
            'produk'      => $produk,
            'logs'        => $logs,
            'currentStok' => $currentStok,
        ];

        return view('Modules\Stok\Views\kartu', $data);
    }

    public function opname()
    {
        $db = \Config\Database::connect();
        $items = $db->table('produk p')
            ->select('p.id, p.kode_produk, p.nama_produk, p.stok_minimum, s.id as stok_id, COALESCE(s.qty, 0) as fisik, COALESCE(s.reserved_qty, 0) as reserved, sat.nama_satuan, kat.nama_kategori')
            ->join('stok s', 's.produk_id = p.id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->join('kategori_produk kat', 'kat.id = p.kategori_id', 'left')
            ->where('p.status', 'active')
            ->where('p.deleted_at IS NULL')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        $menipisItems = [];
        $habisItems = [];

        foreach ($items as $item) {
            $stokTersedia = (float) $item['fisik'] - (float) $item['reserved'];
            $minStok = (int) $item['stok_minimum'];
            if ($stokTersedia <= 0) {
                $habisItems[] = $item;
            } elseif ($stokTersedia <= 5 || $stokTersedia <= $minStok) {
                $menipisItems[] = $item;
            }
        }

        $data = [
            'title'        => 'Edit Semua Stok Produk - Pembaruan Massal',
            'items'        => $items,
            'menipisItems' => $menipisItems,
            'habisItems'   => $habisItems,
        ];

        return view('Modules\Stok\Views\opname', $data);
    }

    public function storeOpname()
    {
        $bulkStok = $this->request->getPost('stok');
        if (is_array($bulkStok) && !empty($bulkStok)) {
            $db = \Config\Database::connect();
            $db->transStart();
            $countUpdated = 0;
            $refDoc = 'OPNAME-BULK-' . date('Ymd-His');
            foreach ($bulkStok as $pId => $qtyBaru) {
                if ($qtyBaru === '' || $qtyBaru === null) {
                    continue;
                }
                $currentQty = (float) ($db->table('stok')->where('produk_id', (int) $pId)->get()->getRow()->qty ?? 0);
                if (abs((float) $qtyBaru - $currentQty) > 0.0001) {
                    $this->stokService->penyesuaian((int) $pId, (float) $qtyBaru, $refDoc, 'Pembaruan massal / Bulk Stock Editor');
                    $countUpdated++;
                }
            }
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses pembaruan stok massal.');
            }

            return redirect()->to('stok')->with('success', "Berhasil memperbarui {$countUpdated} stok produk secara massal.");
        }

        // Single stock adjustment
        $rules = [
            'produk_id'  => 'required|is_natural_no_zero',
            'qty_fisik'  => 'required|numeric',
            'keterangan' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $produkId = (int) $this->request->getPost('produk_id');
        $qtyFisik = (float) $this->request->getPost('qty_fisik');
        $keterangan = trim((string) $this->request->getPost('keterangan'));
        if (empty($keterangan)) {
            $keterangan = 'Penyesuaian stok manual / Stock Opname';
        }

        $refDoc = 'OPNAME-' . date('Ymd-His');

        $db = \Config\Database::connect();
        $db->transStart();
        $this->stokService->penyesuaian($produkId, $qtyFisik, $refDoc, $keterangan);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses stock opname.');
        }

        return redirect()->to('stok')->with('success', 'Penyesuaian stok berhasil disimpan.');
    }

    public function updateRak(int $stokId)
    {
        $lokasi = trim((string) $this->request->getPost('lokasi_rak'));
        $db = \Config\Database::connect();
        $db->table('stok')->where('id', $stokId)->update([
            'lokasi_rak' => strtoupper($lokasi),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Lokasi rak berhasil diperbarui.');
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $items = $db->table('produk p')
            ->select('p.kode_produk, p.barcode, p.nama_produk, kat.nama_kategori, sat.nama_satuan, s.lokasi_rak, COALESCE(s.qty, 0) as fisik, COALESCE(s.reserved_qty, 0) as reserved, p.stok_minimum, p.harga_beli, p.harga_jual')
            ->join('stok s', 's.produk_id = p.id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->join('kategori_produk kat', 'kat.id = p.kategori_id', 'left')
            ->where('p.status', 'active')
            ->where('p.deleted_at IS NULL')
            ->orderBy('p.nama_produk', 'ASC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Kode Produk', 'Barcode', 'Nama Produk', 'Kategori', 'Satuan', 
            'Lokasi Rak', 'Stok Fisik', 'Stok Reserved', 'Stok Tersedia', 
            'Stok Minimum', 'Status Stok', 'Nilai Aset Stok (Rp)'
        ];

        $rows = [];
        foreach ($items as $idx => $it) {
            $tersedia = (float)$it['fisik'] - (float)$it['reserved'];
            $statusStok = 'Aman';
            if ($tersedia <= 0) {
                $statusStok = 'Habis';
            } elseif ($tersedia <= (int)$it['stok_minimum']) {
                $statusStok = 'Menipis';
            }
            $nilaiAset = (float)$it['fisik'] * (float)$it['harga_beli'];

            $rows[] = [
                $idx + 1,
                $it['kode_produk'],
                $it['barcode'] ?? '-',
                $it['nama_produk'],
                $it['nama_kategori'] ?? 'Tanpa Kategori',
                $it['nama_satuan'] ?? 'PCS',
                $it['lokasi_rak'] ?? 'RAK-A1',
                $it['fisik'],
                $it['reserved'],
                $tersedia,
                $it['stok_minimum'],
                $statusStok,
                $nilaiAset,
            ];
        }

        export_csv_file('Export_Monitoring_Stok_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}
