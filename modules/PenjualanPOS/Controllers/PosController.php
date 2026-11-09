<?php

declare(strict_types=1);

namespace Modules\PenjualanPOS\Controllers;

use App\Controllers\BaseController;
use Modules\Master\Models\KategoriModel;
use Modules\Master\Models\PelangganModel;
use Modules\Master\Models\ProdukModel;
use Modules\PenjualanPOS\Services\CheckoutPosService;

class PosController extends BaseController
{
    protected CheckoutPosService $checkoutService;
    protected PelangganModel $pelangganModel;
    protected ProdukModel $produkModel;
    protected KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->checkoutService = new CheckoutPosService();
        $this->pelangganModel = new PelangganModel();
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $nextId = (int)($db->table('sales_order')->selectMax('id')->get()->getRow()->id ?? 0) + 1;
        $nextTrxNumber = 'TRX-' . date('Ym') . '-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

        $produk = $this->produkModel->getWithRelations();
        $kategori = $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        $data = [
            'title'         => 'Kasir POS Cepat',
            'pelanggan'     => $this->pelangganModel->orderBy('nama_pelanggan', 'ASC')->findAll(),
            'produk'        => $produk,
            'kategori'      => $kategori,
            'nextTrxNumber' => $nextTrxNumber,
        ];

        return view('Modules\PenjualanPOS\Views\index', $data);
    }

    public function checkout()
    {
        $json = $this->request->getJSON(true);
        if (!$json) {
            $json = $this->request->getPost();
        }

        if (empty($json['items'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'success' => false,
                'message' => 'Daftar item belanja masih kosong.',
            ]);
        }

        try {
            $result = $this->checkoutService->checkout($json);
            return $this->response->setJSON([
                'status'  => 'success',
                'success' => true,
                'so_id'   => $result['so_id'],
                'message' => 'Transaksi POS berhasil diselesaikan!',
                'data'    => $result,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function struk(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.no_telp, u.username as kasir_nama, inv.nomor_invoice')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('users u', 'u.id = so.user_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('pos')->with('error', 'Transaksi tidak ditemukan.');
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Struk Penjualan #' . $order['nomor_so'],
            'order' => $order,
            'items' => $items,
        ];

        return view('Modules\PenjualanPOS\Views\struk', $data);
    }

    public function strukJson(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.no_telp, u.username as kasir_nama, inv.nomor_invoice')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('users u', 'u.id = so.user_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Transaksi tidak ditemukan.',
            ]);
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'order'   => $order,
            'items'   => $items,
            'store'   => [
                'name'    => 'METAFORA STORE',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
                'footer'  => 'Terima kasih atas kunjungan Anda!',
                'note'    => 'Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian.',
            ],
        ]);
    }
}

