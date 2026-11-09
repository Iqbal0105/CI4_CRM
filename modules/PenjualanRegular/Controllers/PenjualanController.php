<?php

declare(strict_types=1);

namespace Modules\PenjualanRegular\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Master\Models\PelangganModel;
use Modules\Master\Models\ProdukModel;
use Modules\PenjualanRegular\Services\SalesOrderService;

class PenjualanController extends BaseController
{
    protected SalesOrderService $soService;
    protected PelangganModel $pelangganModel;
    protected ProdukModel $produkModel;

    public function __construct()
    {
        $this->soService = new SalesOrderService();
        $this->pelangganModel = new PelangganModel();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, inv.nomor_invoice, inv.status as invoice_status')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.deleted_at IS NULL')
            ->orderBy('so.tanggal_so', 'DESC')
            ->orderBy('so.id', 'DESC')
            ->get()
            ->getResultArray();

        $pelangganList = $db->table('pelanggan')->select('id, nama_pelanggan')->orderBy('nama_pelanggan', 'ASC')->get()->getResultArray();

        $data = [
            'title'         => 'Daftar Pesanan & Transaksi Penjualan',
            'orders'        => $orders,
            'pelangganList' => $pelangganList,
        ];

        return view('Modules\PenjualanRegular\Views\index', $data);
    }

    public function invoices()
    {
        $db = \Config\Database::connect();
        $invoices = $db->table('invoice inv')
            ->select('inv.*, so.nomor_so, so.source, so.tanggal_so, so.status as so_status, p.nama_pelanggan')
            ->join('sales_order so', 'so.id = inv.so_id', 'left')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->orderBy('inv.tanggal_invoice', 'DESC')
            ->orderBy('so.tanggal_so', 'DESC')
            ->orderBy('inv.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Daftar Invoice',
            'invoices' => $invoices,
        ];

        return view('Modules\PenjualanRegular\Views\invoice', $data);
    }

    public function create()
    {
        $data = [
            'title'     => 'Buat Sales Order Baru',
            'pelanggan' => $this->pelangganModel->findAll(),
            'products'  => $this->produkModel->where('status', 'active')->findAll(),
        ];

        return view('Modules\PenjualanRegular\Views\create', $data);
    }

    public function store()
    {
        $rules = [
            'pelanggan_id' => 'required|is_natural_no_zero',
            'tanggal_so'   => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $produkIds = $this->request->getPost('produk_id');
        $qtys = $this->request->getPost('qty');
        $hargas = $this->request->getPost('harga_satuan');
        $diskons = $this->request->getPost('diskon_item');

        if (empty($produkIds) || !is_array($produkIds)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu produk harus dipesan.');
        }

        $items = [];
        for ($i = 0; $i < count($produkIds); $i++) {
            $pId = (int) $produkIds[$i];
            $qty = (float) ($qtys[$i] ?? 0);
            $hargaRaw = str_replace('.', '', (string)($hargas[$i] ?? 0));
            $harga = (float) $hargaRaw;
            $diskonRaw = str_replace('.', '', (string)($diskons[$i] ?? 0));
            $diskon = (float) $diskonRaw;

            if ($pId > 0 && $qty > 0) {
                $items[] = [
                    'produk_id'    => $pId,
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                    'diskon'       => $diskon,
                ];
            }
        }

        $header = [
            'pelanggan_id' => (int) $this->request->getPost('pelanggan_id'),
            'tanggal_so'   => $this->request->getPost('tanggal_so'),
            'tempo_hari'   => (int) ($this->request->getPost('tempo_hari') ?: 30),
            'diskon_total' => (float) (str_replace('.', '', (string)$this->request->getPost('diskon_total')) ?: 0),
            'ppn_persen'   => (float) ($this->request->getPost('ppn_persen') ?: 0),
            'ongkir'       => (float) (str_replace('.', '', (string)$this->request->getPost('ongkir')) ?: 0),
            'catatan'      => trim((string) $this->request->getPost('catatan')),
            'metode_bayar' => $this->request->getPost('metode_bayar') ?: 'tempo',
        ];

        try {
            $soId = $this->soService->createSalesOrder($header, $items);
            return redirect()->to('penjualan/detail/' . $soId)->with('success', 'Sales Order & Invoice berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat SO: ' . $e->getMessage());
        }
    }

    public function detail(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, p.kota, inv.nomor_invoice, inv.status as invoice_status, inv.jatuh_tempo')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('penjualan')->with('error', 'Sales Order tidak ditemukan.');
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Sales Order #' . $order['nomor_so'],
            'order' => $order,
            'items' => $items,
        ];

        return view('Modules\PenjualanRegular\Views\detail', $data);
    }

    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');
        try {
            $this->soService->updateStatus($id, $status);
            return redirect()->to('penjualan/detail/' . $id)->with('success', "Status pesanan berhasil diperbarui menjadi {$status}.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    public function invoicePdf(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, p.kota, inv.nomor_invoice, inv.tanggal_invoice, inv.jatuh_tempo, inv.status as invoice_status')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        $html = view('Modules\PenjualanRegular\Views\invoice_pdf', [
            'order' => $order,
            'items' => $items,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Invoice-' . $order['nomor_invoice'] . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function suratJalanPdf(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, p.kota')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        $html = view('Modules\PenjualanRegular\Views\surat_jalan_pdf', [
            'order' => $order,
            'items' => $items,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('SuratJalan-' . $order['nomor_so'] . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function thermalData(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order so')
            ->select('so.*, p.nama_pelanggan, p.kode_pelanggan, p.no_telp, p.alamat, p.kota, inv.nomor_invoice, inv.status as invoice_status, u.username as kasir_nama')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->join('users u', 'u.id = so.user_id', 'left')
            ->where('so.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Transaksi penjualan tidak ditemukan.'
            ]);
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('soi.so_id', $id)
            ->get()
            ->getResultArray();

        // Calculate fallbacks if bayar / kembalian null
        $total = (float)($order['total'] ?? 0);
        $bayar = (float)($order['bayar'] ?? 0);
        if ($bayar <= 0) {
            $bayar = $total;
            $order['bayar'] = $bayar;
        }
        if (!isset($order['kembalian'])) {
            $order['kembalian'] = max(0, $bayar - $total);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'order'  => $order,
            'items'  => $items,
            'store'  => [
                'name'    => 'METAFORA ERP STORE',
                'address' => 'Jl. Raya Bisnis Terpadu No. 12',
                'phone'   => '0812-3456-7890',
                'footer'  => 'Terima kasih atas pesanan Anda!',
                'note'    => 'Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian.',
            ]
        ]);
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('sales_order so')
            ->select('so.nomor_so, so.tanggal_so, so.source, p.nama_pelanggan, p.kode_pelanggan, inv.nomor_invoice, so.subtotal, so.diskon_total, so.ppn, so.ongkir, so.total, so.metode_bayar, so.status')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('so.deleted_at IS NULL')
            ->orderBy('so.tanggal_so', 'DESC')
            ->orderBy('so.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor Transaksi SO', 'No. Invoice', 'Tanggal SO', 'Sumber', 
            'Pelanggan', 'Subtotal (Rp)', 'Diskon (Rp)', 'PPN (Rp)', 'Ongkir (Rp)', 
            'Grand Total (Rp)', 'Metode Bayar', 'Status'
        ];

        $rows = [];
        foreach ($orders as $idx => $o) {
            $rows[] = [
                $idx + 1,
                $o['nomor_so'],
                $o['nomor_invoice'] ?? '-',
                $o['tanggal_so'],
                strtoupper($o['source'] ?? 'offline'),
                $o['nama_pelanggan'] ?? 'Pelanggan Umum',
                $o['subtotal'],
                $o['diskon_total'],
                $o['ppn'],
                $o['ongkir'],
                $o['total'],
                strtoupper($o['metode_bayar'] ?? 'tunai'),
                strtoupper(str_replace('_', ' ', $o['status'])),
            ];
        }

        export_csv_file('Export_Transaksi_Penjualan_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    public function exportInvoices()
    {
        $db = \Config\Database::connect();
        $invoices = $db->table('invoice inv')
            ->select('inv.nomor_invoice, inv.tanggal_invoice, inv.jatuh_tempo, so.nomor_so, so.source, p.nama_pelanggan, inv.total, inv.status')
            ->join('sales_order so', 'so.id = inv.so_id', 'left')
            ->join('pelanggan p', 'p.id = inv.pelanggan_id', 'left')
            ->orderBy('inv.tanggal_invoice', 'DESC')
            ->orderBy('so.tanggal_so', 'DESC')
            ->orderBy('inv.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor Invoice', 'No. Transaksi SO', 'Tanggal Invoice', 'Jatuh Tempo', 
            'Sumber Transaksi', 'Pelanggan', 'Total Tagihan (Rp)', 'Status Bayar'
        ];

        $rows = [];
        foreach ($invoices as $idx => $inv) {
            $rows[] = [
                $idx + 1,
                $inv['nomor_invoice'],
                $inv['nomor_so'] ?? '-',
                $inv['tanggal_invoice'],
                $inv['jatuh_tempo'] ?? '-',
                strtoupper($inv['source'] ?? 'regular'),
                $inv['nama_pelanggan'] ?? 'Umum',
                $inv['total'],
                strtoupper($inv['status']),
            ];
        }

        export_csv_file('Export_Daftar_Invoice_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}

