<?php

declare(strict_types=1);

namespace Modules\Master\Controllers;

use App\Controllers\BaseController;
use App\Services\AuditService;
use Modules\Master\Models\KategoriModel;
use Modules\Master\Models\ProdukModel;
use Modules\Master\Models\SatuanModel;

class MasterProdukController extends BaseController
{
    protected ProdukModel $produkModel;
    protected KategoriModel $kategoriModel;
    protected SatuanModel $satuanModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->satuanModel = new SatuanModel();
        $this->auditService = new AuditService();
    }

    public function index()
    {
        $data = [
            'title'    => 'Master Data Produk',
            'produk'   => $this->produkModel->getWithRelations(),
            'kategori' => $this->kategoriModel->findAll(),
            'satuan'   => $this->satuanModel->findAll(),
        ];

        return view('Modules\Master\Views\produk\index', $data);
    }

    public function create()
    {
        $data = [
            'title'        => 'Tambah Produk Baru',
            'autoKode'     => $this->generateKodeProduk(),
            'autoBarcode'  => $this->generateBarcode(),
            'kategori'     => $this->kategoriModel->findAll(),
            'satuan'       => $this->satuanModel->findAll(),
        ];

        return view('Modules\Master\Views\produk\form', $data);
    }

    public function store()
    {
        // Normalisasi format ribuan (hapus titik) dari field harga sebelum validasi
        $rawPost = $this->request->getPost();
        foreach (['harga_beli', 'harga_jual', 'harga_grosir', 'harga_distributor'] as $f) {
            if (isset($rawPost[$f])) {
                $rawPost[$f] = str_replace('.', '', (string) $rawPost[$f]);
            }
        }
        $this->request->setGlobal('post', $rawPost);
        $_POST = $rawPost;

        $kode = strtoupper(trim((string) $this->request->getPost('kode_produk')));
        if ($kode === '') {
            $kode = $this->generateKodeProduk();
            $_POST['kode_produk'] = $kode;
        }

        $barcode = trim((string) $this->request->getPost('barcode'));
        if ($barcode === '') {
            $barcode = $this->generateBarcode();
            $_POST['barcode'] = $barcode;
        }

        $rules = [
            'kode_produk'  => 'required|max_length[100]|is_unique[produk.kode_produk]',
            'nama_produk'  => 'required|max_length[255]',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
            'stok_minimum' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode_produk'       => strtoupper(trim((string) $this->request->getPost('kode_produk'))),
            'barcode'           => trim((string) $this->request->getPost('barcode')) ?: null,
            'nama_produk'       => trim((string) $this->request->getPost('nama_produk')),
            'kategori_id'       => $this->request->getPost('kategori_id') ?: null,
            'satuan_id'         => $this->request->getPost('satuan_id') ?: null,
            'harga_beli'        => money_round($this->request->getPost('harga_beli')),
            'harga_jual'        => money_round($this->request->getPost('harga_jual')),
            'harga_grosir'      => money_round($this->request->getPost('harga_grosir') ?: $this->request->getPost('harga_jual')),
            'harga_distributor' => money_round($this->request->getPost('harga_distributor') ?: $this->request->getPost('harga_jual')),
            'stok_minimum'      => (int) ($this->request->getPost('stok_minimum') ?: 5),
            'status'            => $this->request->getPost('status') ?? 'active',
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $this->produkModel->insert($data);
        $produkId = (int) $this->produkModel->getInsertID();

        // Initialize stock record
        $db->table('stok')->insert([
            'produk_id'    => $produkId,
            'qty'          => '0.000',
            'reserved_qty' => '0.000',
            'lokasi_rak'   => 'RAK-UTAMA',
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->auditService->log('create', 'produk', $produkId, null, $data);

        $db->transComplete();

        return redirect()->to('master/produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('master/produk')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Produk - ' . $produk['nama_produk'],
            'produk'   => $produk,
            'kategori' => $this->kategoriModel->findAll(),
            'satuan'   => $this->satuanModel->findAll(),
        ];

        return view('Modules\Master\Views\produk\form', $data);
    }

    public function update(int $id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('master/produk')->with('error', 'Produk tidak ditemukan.');
        }

        // Normalisasi format ribuan (hapus titik) dari field harga sebelum validasi
        $rawPost = $this->request->getPost();
        foreach (['harga_beli', 'harga_jual', 'harga_grosir', 'harga_distributor'] as $f) {
            if (isset($rawPost[$f])) {
                $rawPost[$f] = str_replace('.', '', (string) $rawPost[$f]);
            }
        }
        $this->request->setGlobal('post', $rawPost);
        $_POST = $rawPost;

        $rules = [
            'kode_produk'  => "required|max_length[100]|is_unique[produk.kode_produk,id,{$id}]",
            'nama_produk'  => 'required|max_length[255]',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
            'stok_minimum' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldPrices = [
            'harga_beli'   => $produk['harga_beli'],
            'harga_jual'   => $produk['harga_jual'],
            'harga_grosir' => $produk['harga_grosir'],
        ];

        $newPrices = [
            'harga_beli'   => money_round($this->request->getPost('harga_beli')),
            'harga_jual'   => money_round($this->request->getPost('harga_jual')),
            'harga_grosir' => money_round($this->request->getPost('harga_grosir') ?: $this->request->getPost('harga_jual')),
        ];

        $updateData = [
            'kode_produk'       => strtoupper(trim((string) $this->request->getPost('kode_produk'))),
            'barcode'           => trim((string) $this->request->getPost('barcode')) ?: null,
            'nama_produk'       => trim((string) $this->request->getPost('nama_produk')),
            'kategori_id'       => $this->request->getPost('kategori_id') ?: null,
            'satuan_id'         => $this->request->getPost('satuan_id') ?: null,
            'harga_beli'        => $newPrices['harga_beli'],
            'harga_jual'        => $newPrices['harga_jual'],
            'harga_grosir'      => $newPrices['harga_grosir'],
            'harga_distributor' => money_round($this->request->getPost('harga_distributor') ?: $this->request->getPost('harga_jual')),
            'stok_minimum'      => (int) ($this->request->getPost('stok_minimum') ?: 5),
            'status'            => $this->request->getPost('status') ?? 'active',
        ];

        $this->produkModel->update($id, $updateData);

        // Audit trail for price changes
        if ($oldPrices != $newPrices) {
            $this->auditService->log('price_change', 'produk', $id, $oldPrices, $newPrices);
        } else {
            $this->auditService->log('update', 'produk', $id, $produk, $updateData);
        }

        return redirect()->to('master/produk')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('master/produk')->with('error', 'Produk tidak ditemukan.');
        }

        $this->produkModel->delete($id);
        $this->auditService->log('delete', 'produk', $id, $produk, null);

        return redirect()->to('master/produk')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * AJAX search endpoint for POS and transaction selection.
     */
    public function searchAjax()
    {
        $q = trim((string) $this->request->getGet('q'));
        if (strlen($q) < 1) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();
        $results = $db->table('produk p')
            ->select('p.id, p.kode_produk, p.barcode, p.nama_produk, p.harga_jual, p.harga_grosir, p.harga_distributor, p.harga_beli, s.nama_satuan, COALESCE(st.qty, 0) as stok_fisik, COALESCE(st.reserved_qty, 0) as stok_reserved')
            ->join('satuan s', 's.id = p.satuan_id', 'left')
            ->join('stok st', 'st.produk_id = p.id', 'left')
            ->where('p.status', 'active')
            ->where('p.deleted_at IS NULL')
            ->groupStart()
                ->like('p.kode_produk', $q)
                ->orLike('p.barcode', $q)
                ->orLike('p.nama_produk', $q)
            ->groupEnd()
            ->limit(20)
            ->get()
            ->getResultArray();

        $formatted = array_map(function ($row) {
            $tersedia = (float) bcsub((string) $row['stok_fisik'], (string) $row['stok_reserved'], 3);
            return [
                'id'                => (int) $row['id'],
                'kode_produk'       => $row['kode_produk'],
                'barcode'           => $row['barcode'] ?? '',
                'nama_produk'       => $row['nama_produk'],
                'harga_jual'        => (float) $row['harga_jual'],
                'harga_grosir'      => (float) $row['harga_grosir'],
                'harga_distributor' => (float) $row['harga_distributor'],
                'harga_beli'        => (float) $row['harga_beli'],
                'satuan'            => $row['nama_satuan'] ?? 'PCS',
                'stok_tersedia'     => $tersedia,
            ];
        }, $results);

        return $this->response->setJSON($formatted);
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $products = $db->table('produk p')
            ->select('p.kode_produk, p.barcode, p.nama_produk, k.nama_kategori, s.nama_satuan, p.harga_beli, p.harga_jual, p.harga_grosir, p.stok_minimum, p.status, COALESCE(SUM(st.qty), 0) as total_stok')
            ->join('kategori_produk k', 'k.id = p.kategori_id', 'left')
            ->join('satuan s', 's.id = p.satuan_id', 'left')
            ->join('stok st', 'st.produk_id = p.id', 'left')
            ->where('p.deleted_at IS NULL')
            ->groupBy('p.id')
            ->orderBy('p.nama_produk', 'ASC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Kode Produk', 'Barcode', 'Nama Produk', 'Kategori', 'Satuan', 
            'Harga Beli (Rp)', 'Harga Jual (Rp)', 'Harga Grosir (Rp)', 'Stok Saat Ini', 'Stok Minimum', 'Status'
        ];

        $rows = [];
        foreach ($products as $idx => $p) {
            $rows[] = [
                $idx + 1,
                $p['kode_produk'],
                $p['barcode'] ?? '-',
                $p['nama_produk'],
                $p['nama_kategori'] ?? 'Tanpa Kategori',
                $p['nama_satuan'] ?? 'PCS',
                $p['harga_beli'],
                $p['harga_jual'],
                $p['harga_grosir'],
                $p['total_stok'],
                $p['stok_minimum'],
                ucfirst($p['status']),
            ];
        }

        export_csv_file('Export_Master_Produk_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    /**
     * Auto generate sequence kode SKU produk (PRD-0001, PRD-0002, dst)
     */
    protected function generateKodeProduk(): string
    {
        $all = $this->produkModel
            ->withDeleted()
            ->select('kode_produk')
            ->like('kode_produk', 'PRD-', 'after')
            ->findAll();

        $maxNum = 0;
        foreach ($all as $item) {
            $code = (string) ($item['kode_produk'] ?? '');
            if (preg_match('/PRD-(\d+)/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        return sprintf('PRD-%04d', $nextNum);
    }

    /**
     * Auto generate sequence barcode produk (89910010001, dst)
     */
    protected function generateBarcode(): string
    {
        $all = $this->produkModel
            ->withDeleted()
            ->select('barcode')
            ->like('barcode', '8991001', 'after')
            ->findAll();

        $maxNum = 0;
        foreach ($all as $item) {
            $code = (string) ($item['barcode'] ?? '');
            if (preg_match('/8991001(\d+)/', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        // Selaraskan dengan sequence tertinggi dari SKU jika ada
        $skuMax = 0;
        $allSkus = $this->produkModel
            ->withDeleted()
            ->select('kode_produk')
            ->like('kode_produk', 'PRD-', 'after')
            ->findAll();
        foreach ($allSkus as $item) {
            $code = (string) ($item['kode_produk'] ?? '');
            if (preg_match('/PRD-(\d+)/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $skuMax) {
                    $skuMax = $num;
                }
            }
        }

        $nextNum = max($maxNum, $skuMax) + 1;
        return sprintf('8991001%04d', $nextNum);
    }
}

