<?php

declare(strict_types=1);

namespace Modules\Pembelian\Controllers;

use App\Controllers\BaseController;
use Modules\Master\Models\KursModel;
use Modules\Master\Models\ProdukModel;
use Modules\Master\Models\SupplierModel;
use Modules\Pembelian\Services\PurchaseOrderService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PembelianController extends BaseController
{
    protected PurchaseOrderService $poService;
    protected SupplierModel $supplierModel;
    protected ProdukModel $produkModel;
    protected KursModel $kursModel;

    public function __construct()
    {
        $this->poService = new PurchaseOrderService();
        $this->supplierModel = new SupplierModel();
        $this->produkModel = new ProdukModel();
        $this->kursModel = new KursModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('purchase_order po')
            ->select('po.*, s.nama_supplier, s.mata_uang as supplier_mata_uang, COUNT(poi.id) as total_items')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->join('purchase_order_item poi', 'poi.po_id = po.id', 'left')
            ->groupBy('po.id, s.nama_supplier, s.mata_uang')
            ->orderBy('po.tanggal_po', 'DESC')
            ->orderBy('po.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'  => 'Daftar Purchase Order (PO)',
            'orders' => $orders,
        ];

        return view('Modules\Pembelian\Views\index', $data);
    }

    public function create()
    {
        $supplierId = $this->request->getGet('supplier_id');
        $produkId = $this->request->getGet('produk_id');

        $currencies = ['USD', 'SGD', 'CNY', 'EUR'];
        $latestRates = ['IDR' => 1.0];
        foreach ($currencies as $curr) {
            $latestRates[$curr] = (float) $this->kursModel->getLatestRate($curr);
        }

        $data = [
            'title'       => 'Buat Purchase Order Baru',
            'suppliers'   => $this->supplierModel->findAll(),
            'products'    => $this->produkModel->where('status', 'active')->findAll(),
            'supplierId'  => $supplierId,
            'produkId'    => $produkId,
            'latestRates' => $latestRates,
        ];

        return view('Modules\Pembelian\Views\create', $data);
    }

    public function store()
    {
        $rules = [
            'supplier_id' => 'required|is_natural_no_zero',
            'tanggal_po'  => 'required|valid_date',
            'termin'      => 'required|in_list[tunai,tempo]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $produkIds = $this->request->getPost('produk_id');
        $qtys = $this->request->getPost('qty');
        $hargas = $this->request->getPost('harga_satuan');

        if (empty($produkIds) || !is_array($produkIds)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu item produk harus ditambahkan dalam PO.');
        }

        $items = [];
        for ($i = 0; $i < count($produkIds); $i++) {
            $pId = (int) $produkIds[$i];
            $qty = (int) ($qtys[$i] ?? 0);
            $harga = (float) ($hargas[$i] ?? 0);

            if ($pId > 0 && $qty > 0) {
                $items[] = [
                    'produk_id'    => $pId,
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                ];
            }
        }

        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah kuantitas item PO tidak boleh kosong atau nol.');
        }

        $mataUang = strtoupper(trim((string) ($this->request->getPost('mata_uang') ?: 'IDR')));
        $inputKurs = (float) $this->request->getPost('nilai_kurs');
        if ($mataUang === 'IDR') {
            $nilaiKurs = 1.0;
        } elseif ($inputKurs > 0) {
            $nilaiKurs = $inputKurs;
        } else {
            $nilaiKurs = (float) $this->kursModel->getLatestRate($mataUang);
        }

        $header = [
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'tanggal_po'  => $this->request->getPost('tanggal_po'),
            'termin'      => $this->request->getPost('termin'),
            'tempo_hari'  => (int) ($this->request->getPost('tempo_hari') ?: 0),
            'mata_uang'   => $mataUang,
            'nilai_kurs'  => $nilaiKurs,
            'diskon'      => (float) ($this->request->getPost('diskon') ?: 0),
            'pajak'       => (float) ($this->request->getPost('pajak') ?: 0),
            'catatan'     => trim((string) $this->request->getPost('catatan')),
        ];

        try {
            $poId = $this->poService->createPO($header, $items);
            return redirect()->to('pembelian/detail/' . $poId)->with('success', 'Purchase Order berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }

    public function detail(int $id)
    {
        $db = \Config\Database::connect();
        $po = $db->table('purchase_order po')
            ->select('po.*, s.nama_supplier, s.kode_supplier, s.no_telp as supplier_telp, s.alamat as supplier_alamat')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->where('po.id', $id)
            ->get()
            ->getRowArray();

        if (!$po) {
            return redirect()->to('pembelian')->with('error', 'PO tidak ditemukan.');
        }

        $items = $db->table('purchase_order_item poi')
            ->select('poi.*, p.kode_produk, p.nama_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = poi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('poi.po_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Purchase Order #' . $po['nomor_po'],
            'po'    => $po,
            'items' => $items,
        ];

        return view('Modules\Pembelian\Views\detail', $data);
    }

    public function penerimaan(int $id)
    {
        $db = \Config\Database::connect();
        $po = $db->table('purchase_order po')
            ->select('po.*, s.nama_supplier')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->where('po.id', $id)
            ->get()
            ->getRowArray();

        if (!$po || in_array($po['status'], ['received', 'cancelled'], true)) {
            return redirect()->to('pembelian/detail/' . $id)->with('error', 'PO ini sudah selesai atau dibatalkan.');
        }

        $items = $db->table('purchase_order_item poi')
            ->select('poi.*, p.kode_produk, p.nama_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = poi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('poi.po_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Penerimaan Barang PO #' . $po['nomor_po'],
            'po'    => $po,
            'items' => $items,
        ];

        return view('Modules\Pembelian\Views\penerimaan', $data);
    }

    public function processPenerimaan(int $id)
    {
        $receivedItems = $this->request->getPost('qty_terima'); // [itemId => qty]
        if (empty($receivedItems) || !is_array($receivedItems)) {
            return redirect()->back()->with('error', 'Data kuantitas penerimaan tidak valid.');
        }

        try {
            $this->poService->receivePO($id, $receivedItems);
            return redirect()->to('pembelian/detail/' . $id)->with('success', 'Penerimaan barang berhasil diproses. Stok fisik dan kartu stok telah dimutasi.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memproses penerimaan: ' . $e->getMessage());
        }
    }

    public function pdf(int $id)
    {
        $db = \Config\Database::connect();
        $po = $db->table('purchase_order po')
            ->select('po.*, s.nama_supplier, s.kode_supplier, s.no_telp as supplier_telp, s.alamat as supplier_alamat')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->where('po.id', $id)
            ->get()
            ->getRowArray();

        if (!$po) {
            return redirect()->to('pembelian')->with('error', 'PO tidak ditemukan.');
        }

        $items = $db->table('purchase_order_item poi')
            ->select('poi.*, p.kode_produk, p.nama_produk, sat.nama_satuan')
            ->join('produk p', 'p.id = poi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = p.satuan_id', 'left')
            ->where('poi.po_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Purchase Order #' . $po['nomor_po'],
            'po'    => $po,
            'items' => $items,
        ];

        return view('Modules\Pembelian\Views\pdf', $data);
    }

    public function cancel(int $id)
    {
        $db = \Config\Database::connect();
        $po = $db->table('purchase_order')->where('id', $id)->get()->getRowArray();
        if (!$po) {
            return redirect()->to('pembelian')->with('error', 'PO tidak ditemukan.');
        }

        if ($po['status'] === 'received') {
            return redirect()->to('pembelian')->with('error', 'PO yang sudah diterima tidak dapat dibatalkan.');
        }

        $utang = $db->table('utang_usaha')->where('po_id', $id)->get()->getRowArray();
        if ($utang) {
            $hasPayment = $db->table('pembayaran_utang')->where('utang_id', $utang['id'])->countAllResults() > 0;
            if ($hasPayment || $utang['status'] !== 'belum') {
                return redirect()->to('pembelian')->with('error', 'PO tidak dapat dibatalkan karena sudah ada pembayaran utang.');
            }
            $db->table('utang_usaha')->where('id', $utang['id'])->delete();
        }

        $db->table('purchase_order')->where('id', $id)->update(['status' => 'cancelled']);
        return redirect()->to('pembelian')->with('success', 'Purchase Order #' . $po['nomor_po'] . ' berhasil dibatalkan.');
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('purchase_order po')
            ->select('po.nomor_po, po.tanggal_po, s.nama_supplier, s.kode_supplier, po.mata_uang, po.nilai_kurs, po.total, po.total_idr, po.status, po.catatan')
            ->join('supplier s', 's.id = po.supplier_id', 'left')
            ->orderBy('po.tanggal_po', 'DESC')
            ->orderBy('po.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'Nomor PO', 'Tanggal PO', 'Supplier', 'Kode Supplier', 
            'Mata Uang', 'Kurs (Rp)', 'Total Valas', 'Total (IDR)', 'Status', 'Catatan'
        ];

        $rows = [];
        foreach ($orders as $idx => $o) {
            $rows[] = [
                $idx + 1,
                $o['nomor_po'],
                date('d/m/Y', strtotime($o['tanggal_po'])),
                $o['nama_supplier'] ?? 'Supplier Umum',
                $o['kode_supplier'] ?? '-',
                $o['mata_uang'] ?? 'IDR',
                (float) ($o['nilai_kurs'] ?? 1),
                (float) ($o['total'] ?? 0),
                (float) ($o['total_idr'] ?? 0),
                strtoupper($o['status']),
                $o['catatan'] ?? '-',
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Purchase Orders');

            $sheet->fromArray($headers, null, 'A1');

            if (!empty($rows)) {
                $sheet->fromArray($rows, null, 'A2');
            }

            $sheet->getStyle('A1:K1')->getFont()->setBold(true);
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'Export_Purchase_Orders_' . date('Ymd_His') . '.xlsx';

            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Throwable $e) {
            export_csv_file('Export_Purchase_Orders_' . date('Ymd_His') . '.csv', $headers, $rows);
        }
    }
}

