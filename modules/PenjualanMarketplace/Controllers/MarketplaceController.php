<?php

declare(strict_types=1);

namespace Modules\PenjualanMarketplace\Controllers;

use App\Controllers\BaseController;
use Modules\PenjualanMarketplace\Services\MarketplaceImportService;
use Modules\PenjualanMarketplace\Services\ResiParserService;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MarketplaceController extends BaseController
{
    protected MarketplaceImportService $importService;
    protected ResiParserService $resiParserService;

    public function __construct()
    {
        $this->importService = new MarketplaceImportService();
        $this->resiParserService = new ResiParserService();
    }

    public function index()
    {
        // Auto-sinkronisasi data marketplace lama ke Master Data (produk & pelanggan)
        try {
            $this->importService->syncExistingUnmappedData();
        } catch (\Throwable) {}

        $channel = $this->request->getGet('channel');
        $db = \Config\Database::connect();
        $builder = $db->table('penjualan_marketplace pm')
            ->select('pm.*, COUNT(pmi.id) as total_items, COALESCE(ROUND(SUM(pmi.qty)), 1) as total_qty, GROUP_CONCAT(DISTINCT CONCAT(pmi.sku, " (", ROUND(pmi.qty), ")") SEPARATOR ", ") as items_sku_summary, so.nomor_so, so.metode_bayar, inv.nomor_invoice')
            ->join('penjualan_marketplace_item pmi', 'pmi.penjualan_marketplace_id = pm.id', 'left')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('pm.deleted_at IS NULL');

        if (!empty($channel)) {
            $builder->where('pm.source', $channel);
        }

        $orders = $builder->groupBy('pm.id, so.nomor_so, so.metode_bayar, inv.nomor_invoice')
            ->orderBy('pm.tanggal_pesanan', 'DESC')
            ->orderBy('pm.id', 'DESC')
            ->get()
            ->getResultArray();

        $totalOrders = count($orders);
        $totalOmset = (float) array_sum(array_column($orders, 'total'));
        $perluDikirim = 0;
        $selesai = 0;
        foreach ($orders as &$o) {
            $o['is_cod'] = !empty($o['is_cod']) || strtolower((string)($o['metode_bayar'] ?? '')) === 'cod';
            $st = strtolower((string) ($o['status'] ?? ''));
            if (in_array($st, ['completed', 'selesai'], true)) {
                $selesai++;
            } else {
                $perluDikirim++;
            }
        }
        unset($o);

        $data = [
            'title'        => 'Omnichannel Marketplace (Shopee, TikTok, Tokopedia)',
            'orders'       => $orders,
            'channel'      => $channel,
            'totalOrders'  => $totalOrders,
            'totalOmset'   => $totalOmset,
            'perluDikirim' => $perluDikirim,
            'selesai'      => $selesai,
        ];

        return view('Modules\PenjualanMarketplace\Views\index', $data);
    }

    public function import()
    {
        // Auto-sinkronisasi data marketplace lama ke Master Data jika ada yang belum terpetakan
        try {
            $this->importService->syncExistingUnmappedData();
        } catch (\Throwable) {}

        $data = [
            'title' => 'Impor Laporan Pesanan Marketplace (CSV / Excel)',
        ];

        return view('Modules\PenjualanMarketplace\Views\import', $data);
    }

    /**
     * Parse raw rows from CSV or Excel into grouped order items by order number.
     */
    protected function parseOrderRows(array $rows, string &$source): array
    {
        if (count($rows) < 2) {
            return [];
        }

        $header = array_map(function ($h) {
            return trim(str_replace("\xEF\xBB\xBF", '', (string) $h));
        }, $rows[0]);
        $headerMap = array_flip($header);

        // Auto-detect marketplace source if headers match known signatures
        if (isset($headerMap['Order ID']) || isset($headerMap['Seller SKU']) || isset($headerMap['Tracking ID'])) {
            $source = 'tiktok';
        } elseif (isset($headerMap['SKU Induk']) || isset($headerMap['Total Harga Produk']) || isset($headerMap['Catatan Pembeli'])) {
            $source = 'tokopedia';
        } elseif (isset($headerMap['orderNumber']) || isset($headerMap['trackingCode']) || isset($headerMap['sellerSku'])) {
            $source = 'lazada';
        } elseif (isset($headerMap['No. Pesanan']) && (isset($headerMap['Nomor Referensi SKU']) || isset($headerMap['Opsi Pengiriman']))) {
            $source = 'shopee';
        } elseif (isset($headerMap['No. Pesanan']) || isset($headerMap['Nomor Pesanan'])) {
            $source = 'shopee';
        }

        $getVal = function (array $row, array $candidates, $default = '') use ($headerMap) {
            foreach ($candidates as $candidate) {
                if (isset($headerMap[$candidate])) {
                    $idx = $headerMap[$candidate];
                    if (isset($row[$idx]) && trim((string) $row[$idx]) !== '') {
                        return trim((string) $row[$idx]);
                    }
                }
            }
            return $default;
        };

        $grouped = [];
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (count($row) < 3) continue;

            $noPesanan = $getVal($row, ['Order ID', 'No. Pesanan', 'Nomor Pesanan', 'orderNumber', 'No Pesanan']);
            if (empty($noPesanan)) continue;

            if (!isset($grouped[$noPesanan])) {
                $resi = $getVal($row, ['Tracking ID', 'No. Resi', 'Nomor Resi', 'trackingCode', 'No Resi'], null);
                $kurir = $getVal($row, ['Shipping Provider Name', 'Opsi Pengiriman', 'Kurir', 'Ekspedisi', 'shippingProvider', 'Delivery Option'], 'Ekspedisi Standard');
                $namaPenerima = $getVal($row, ['Recipient', 'Nama Penerima', 'customerName', 'Nama Pembeli'], 'Pembeli');
                $noTelepon = $getVal($row, ['Phone #', 'No. Telepon', 'Nomor Telepon', 'No Telp', 'customerPhone', 'Telepon'], '');
                
                $alamatDetail = $getVal($row, ['Detail Address', 'Alamat Pengiriman', 'Alamat', 'shippingAddress'], '');
                $kota = $getVal($row, ['Regency and City', 'Kota/Kabupaten', 'Kota', 'City'], '');
                $provinsi = $getVal($row, ['Province', 'Provinsi'], '');
                $fullAlamat = $alamatDetail;
                if (!empty($kota) && !str_contains($fullAlamat, $kota)) {
                    $fullAlamat .= ($fullAlamat !== '' ? ', ' : '') . $kota;
                }
                if (!empty($provinsi) && !str_contains($fullAlamat, $provinsi)) {
                    $fullAlamat .= ($fullAlamat !== '' ? ', ' : '') . $provinsi;
                }

                $rawTotal = $getVal($row, ['Order Amount', 'Total Pembayaran', 'Total Bayar', 'paidPrice'], '0');
                $total = (float) str_replace(['Rp', '.', ' '], '', $rawTotal);

                $rawOngkir = $getVal($row, ['Shipping Fee After Discount', 'Original Shipping Fee', 'Ongkos Kirim Dibayar oleh Pembeli', 'Ongkir', 'shippingFee'], '0');
                $ongkir = (float) str_replace(['Rp', '.', ' '], '', $rawOngkir);

                $rawVoucher = $getVal($row, ['SKU Seller Discount', 'SKU Platform Discount', 'Voucher Ditanggung Penjual', 'Voucher', 'sellerDiscount'], '0');
                $voucher = (float) str_replace(['Rp', '.', ' '], '', $rawVoucher);

                $rawTanggal = $getVal($row, ['Created Time', 'Paid Time', 'Waktu Pesanan Dibuat', 'Waktu Pembayaran', 'Waktu Pembayaran Dilakukan', 'createTime', 'Tanggal Pesanan'], date('Y-m-d H:i:s'));

                $grouped[$noPesanan] = [
                    'no_pesanan'     => $noPesanan,
                    'resi'           => $resi,
                    'kurir'          => $kurir,
                    'nama_penerima'  => $namaPenerima,
                    'buyer_username' => $getVal($row, ['Buyer Username', 'Username (Pembeli)', 'Username', 'buyerUsername'], ''),
                    'no_telepon'     => $noTelepon,
                    'alamat'         => $fullAlamat,
                    'kota'           => $kota,
                    'total'          => $total,
                    'ongkir'         => $ongkir,
                    'voucher'        => $voucher,
                    'tanggal'        => $rawTanggal,
                    'items'          => [],
                ];
            }

            $sku = $getVal($row, ['Seller SKU', 'Nomor Referensi SKU', 'SKU Induk', 'SKU', 'sellerSku', 'SKU ID']);
            $nama = $getVal($row, ['Product Name', 'Nama Produk', 'itemName'], $sku);
            $qty = (float) $getVal($row, ['Quantity', 'Jumlah', 'Qty', 'quantity'], '1');
            $rawHarga = $getVal($row, ['SKU Unit Original Price', 'SKU Subtotal After Discount', 'Harga Setelah Diskon', 'Harga Satuan', 'Harga Awal', 'unitPrice'], '0');
            $harga = (float) str_replace(['Rp', '.', ' '], '', $rawHarga);
            if ($harga <= 0 && $qty > 0 && $total > 0) {
                $harga = round($total / $qty, 2);
            }

            $grouped[$noPesanan]['items'][] = [
                'sku'      => $sku,
                'nama'     => $nama,
                'qty'      => $qty,
                'harga'    => $harga,
                'subtotal' => $qty * $harga,
            ];
        }

        return $grouped;
    }

    /**
     * AJAX Preview data from uploaded report file before committing import.
     */
    public function previewImport()
    {
        $source = (string) $this->request->getPost('source');
        $file = $this->request->getFile('file_laporan');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Berkas laporan tidak valid atau belum dipilih.']);
        }

        $ext = strtolower($file->getExtension());
        if (empty($ext)) {
            $ext = strtolower(pathinfo($file->getClientName(), PATHINFO_EXTENSION));
        }
        if (!in_array($ext, ['csv', 'xlsx', 'xls'], true)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus berupa CSV atau Excel (.xlsx/.xls).']);
        }

        $filePath = $file->getTempName();

        try {
            $ordersGrouped = [];
            if ($ext === 'csv') {
                $handle = fopen($filePath, 'r');
                $csvRows = [];
                $firstLine = fgets($handle);
                $delimiter = (substr_count((string)$firstLine, ';') > substr_count((string)$firstLine, ',')) ? ';' : ',';
                rewind($handle);
                while (($line = fgetcsv($handle, 4096, $delimiter)) !== false) {
                    $csvRows[] = $line;
                }
                fclose($handle);

                if (count($csvRows) < 2) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'File CSV kosong atau tidak memiliki baris data.']);
                }

                $ordersGrouped = $this->parseOrderRows($csvRows, $source);
            } else {
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $excelRows = $sheet->toArray();
                if (count($excelRows) < 2) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'File Excel kosong atau tidak memiliki baris data.']);
                }

                $ordersGrouped = $this->parseOrderRows($excelRows, $source);
            }

            if (empty($ordersGrouped)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data pesanan yang berhasil dibaca. Pastikan format kolom file sesuai dengan platform terpilih.']);
            }

            // Check existing orders in DB to flag duplicates
            $db = \Config\Database::connect();
            $orderIds = array_keys($ordersGrouped);
            $existing = $db->table('penjualan_marketplace')
                ->select('no_pesanan, source')
                ->whereIn('no_pesanan', $orderIds)
                ->where('deleted_at IS NULL')
                ->get()
                ->getResultArray();

            $existingMap = [];
            foreach ($existing as $ex) {
                $existingMap[$ex['source'] . '_' . $ex['no_pesanan']] = true;
            }

            $orderList = [];
            $newCount = 0;
            $dupCount = 0;

            foreach ($ordersGrouped as $noPesanan => $ord) {
                $isDup = isset($existingMap[$source . '_' . $noPesanan]);
                if ($isDup) {
                    $dupCount++;
                } else {
                    $newCount++;
                }
                $ord['is_duplicate'] = $isDup;
                $orderList[] = $ord;
            }

            return $this->response->setJSON([
                'status'           => 'success',
                'source'           => $source,
                'file_name'        => $file->getClientName(),
                'file_size'        => round($file->getSize() / 1024, 1) . ' KB',
                'total_orders'     => count($orderList),
                'new_orders'       => $newCount,
                'duplicate_orders' => $dupCount,
                'orders'           => array_values($orderList),
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca berkas: ' . $e->getMessage()]);
        }
    }

    public function processImport()
    {
        $source = $this->request->getPost('source'); // shopee, tokopedia, tiktok, lazada
        $file = $this->request->getFile('file_laporan');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File unggahan tidak valid.');
        }

        $ext = strtolower($file->getExtension());
        if (empty($ext)) {
            $ext = strtolower(pathinfo($file->getClientName(), PATHINFO_EXTENSION));
        }
        if (!in_array($ext, ['csv', 'xlsx', 'xls'], true)) {
            return redirect()->back()->with('error', 'Format file harus berupa CSV atau Excel (.xlsx/.xls).');
        }

        $filePath = $file->getTempName();

        try {
            // Bersihkan data pesanan yang sebelumnya berstatus soft-deleted agar bisa diimpor ulang tanpa bentrok index unik
            $db = \Config\Database::connect();
            $db->table('penjualan_marketplace')->where('deleted_at IS NOT NULL')->delete();

            $ordersGrouped = [];

            if ($ext === 'csv') {
                $handle = fopen($filePath, 'r');
                $csvRows = [];
                $firstLine = fgets($handle);
                $delimiter = (substr_count((string)$firstLine, ';') > substr_count((string)$firstLine, ',')) ? ';' : ',';
                rewind($handle);
                while (($line = fgetcsv($handle, 4096, $delimiter)) !== false) {
                    $csvRows[] = $line;
                }
                fclose($handle);

                if (count($csvRows) < 2) {
                    return redirect()->back()->with('error', 'File CSV kosong atau tidak memiliki baris data.');
                }

                $ordersGrouped = $this->parseOrderRows($csvRows, $source);
            } else {
                // Read via PhpSpreadsheet
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $excelRows = $sheet->toArray();
                if (count($excelRows) < 2) {
                    return redirect()->back()->with('error', 'File Excel kosong atau tidak memiliki baris data.');
                }

                $ordersGrouped = $this->parseOrderRows($excelRows, $source);
            }

            $result = $this->importService->importOrders($source, array_values($ordersGrouped));

            // Otomatis sinkronkan seluruh data ke Master Data (produk & pelanggan) saat impor selesai
            try {
                $this->importService->syncExistingUnmappedData();
            } catch (\Throwable) {}

            $msg = "Impor selesai: <strong>{$result['imported']}</strong> pesanan baru berhasil diimpor, <strong>{$result['skipped']}</strong> pesanan dilewati (duplikat).";
            if (!empty($result['new_products'])) {
                $msg .= "<br><i class=\"fa-solid fa-cube text-success me-1\"></i> <strong>{$result['new_products']}</strong> produk otomatis didaftarkan ke Master Data Produk.";
            }
            if (!empty($result['new_customers'])) {
                $msg .= "<br><i class=\"fa-solid fa-user text-primary me-1\"></i> <strong>{$result['new_customers']}</strong> pelanggan otomatis didaftarkan ke Master Data Pelanggan.";
            }
            if (!empty($result['unmapped_skus'])) {
                $msg .= "<br>Perhatian: Ditemukan SKU yang belum terpetakan di master produk: <code>" . implode(', ', $result['unmapped_skus']) . "</code>";
            }

            return redirect()->to('marketplace/import')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    public function scanResi()
    {
        $db = \Config\Database::connect();
        $products = $db->table('produk')
            ->select('id, kode_produk, nama_produk, harga_jual, barcode')
            ->where('deleted_at IS NULL')
            ->orderBy('nama_produk', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Import & Scraper Resi Otomatis (Gambar & PDF Thermal)',
            'products' => $products,
            'parsed'   => session()->getFlashdata('parsedResi') ?? null,
        ];

        return view('Modules\PenjualanMarketplace\Views\scan_resi', $data);
    }

    /**
     * Debug endpoint: test whether exec/python/node work in the web server context.
     * Access via GET /marketplace/debug-ocr
     * REMOVE THIS METHOD after confirming OCR works in production.
     */
    public function debugOcr()
    {
        $pythonBin = 'C:\\Users\\Lenovo\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
        $nodeBin   = 'D:\\nodejs\\node.exe';

        $result = [
            'php_version'    => PHP_VERSION,
            'python_exists'  => file_exists($pythonBin),
            'node_exists'    => file_exists($nodeBin),
            'exec_disabled'  => in_array('exec', array_map('trim', explode(',', ini_get('disable_functions')))),
            'writepath'      => WRITEPATH,
        ];

        // Test python
        $pyOut = [];
        @exec(escapeshellarg($pythonBin) . ' --version 2>&1', $pyOut);
        $result['python_version'] = implode(' ', $pyOut);

        // Test node
        $ndOut = [];
        @exec(escapeshellarg($nodeBin) . ' --version 2>&1', $ndOut);
        $result['node_version'] = implode(' ', $ndOut);

        // Test pymupdf render of 1 page
        $tempDir = WRITEPATH . 'temp_debug_' . bin2hex(random_bytes(4));
        @mkdir($tempDir, 0777, true);
        $testPdf = WRITEPATH . 'uploads/resi/' . (glob(WRITEPATH . 'uploads/resi/*.pdf')[0] ?? '');
        $pdfFiles = glob(WRITEPATH . 'uploads/resi/*.pdf');
        $result['pdf_found'] = !empty($pdfFiles) ? basename($pdfFiles[0]) : 'none';

        if (!empty($pdfFiles)) {
            $escapedPdf = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, $pdfFiles[0]));
            $escapedDir = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, $tempDir));
            $pyScript   = "import pymupdf, sys; doc=pymupdf.open(sys.argv[1]); doc[0].get_pixmap(dpi=72).save(f'{sys.argv[2]}/page_1.png')";
            $pyOut2     = [];
            @exec(escapeshellarg($pythonBin) . " -c \"{$pyScript}\" {$escapedPdf} {$escapedDir} 2>&1", $pyOut2);
            $result['pymupdf_output']  = implode(' | ', $pyOut2);
            $result['pymupdf_success'] = file_exists($tempDir . '/page_1.png');
            @unlink($tempDir . '/page_1.png');
        }
        @rmdir($tempDir);

        return $this->response->setJSON($result);
    }

    public function processScanResi()
    {
        // Allow up to 5 minutes for multi-page batch OCR processing
        @set_time_limit(300);

        $file = $this->request->getFile('resi_file') ?? $this->request->getFile('pdf_resi');
        $rawText = trim((string) $this->request->getPost('scraped_text'));
        $imagePath = null;

        if ($file && $file->isValid()) {
            $ext = strtolower($file->getExtension());
            if (empty($ext)) {
                $ext = strtolower(pathinfo($file->getClientName(), PATHINFO_EXTENSION));
            }
            if (!in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'webp'], true)) {
                return redirect()->back()->with('error', 'Format berkas harus berupa Gambar (PNG/JPG/WEBP) atau PDF label thermal.');
            }

            // Save file into writable/uploads/resi
            $uploadDir = WRITEPATH . 'uploads/resi/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $imagePath = $newName;

            if ($ext === 'pdf') {
                $pdfText = $this->resiParserService->extractTextFromPdf($uploadDir . $newName);
                $rawText .= "\n" . $pdfText;
            } else {
                // Image OCR extraction (PNG, JPG, JPEG, WEBP)
                $imgText = $this->resiParserService->extractTextFromImage($uploadDir . $newName);
                if (!empty($imgText)) {
                    $rawText .= "\n" . $imgText;
                }
            }
        }

        // Parse extracted/scraped text (supporting single and batch multi-label)
        $batch = $this->resiParserService->parseBatch($rawText);
        if (!empty($batch['items'])) {
            foreach ($batch['items'] as &$it) {
                $it['file_name'] = $imagePath;
            }
            unset($it);
        }
        $firstItem = $batch['items'][0] ?? null;

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'success',
                'is_batch' => $batch['is_batch'],
                'count'    => $batch['count'],
                'items'    => $batch['items'],
                'data'     => $firstItem,
            ]);
        }

        $db = \Config\Database::connect();
        $products = $db->table('produk')
            ->select('id, kode_produk, nama_produk, harga_jual, barcode')
            ->where('deleted_at IS NULL')
            ->orderBy('nama_produk', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'     => 'Hasil Scraping & Verifikasi Resi',
            'parsed'    => $firstItem,
            'batch'     => $batch,
            'products'  => $products,
            'imagePath' => $imagePath,
        ];

        return view('Modules\PenjualanMarketplace\Views\scan_resi', $data);
    }

    public function parseResiTextAjax()
    {
        $rawText = (string) ($this->request->getPost('raw_text') ?? $this->request->getVar('raw_text'));
        $batch = $this->resiParserService->parseBatch($rawText);

        return $this->response->setJSON([
            'status'   => 'success',
            'is_batch' => $batch['is_batch'],
            'count'    => $batch['count'],
            'items'    => $batch['items'],
            'data'     => $batch['items'][0] ?? null,
        ]);
    }

    public function saveBatchResi()
    {
        $user = session()->get('user');
        $userId = $user['id'] ?? null;

        $rawItems = $this->request->getPost('items');
        if (is_string($rawItems)) {
            $items = json_decode($rawItems, true) ?? [];
        } elseif (is_array($rawItems)) {
            $items = $rawItems;
        } else {
            $itemsJson = (string) $this->request->getPost('batch_json');
            $items = json_decode($itemsJson, true) ?? [];
        }

        if (empty($items)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data resi untuk diimpor.']);
            }
            return redirect()->back()->with('error', 'Tidak ada data resi yang dipilih untuk diimpor.');
        }

        $successCount = 0;
        $errors = [];
        $savedResi = [];

        foreach ($items as $item) {
            $nomorResi = trim($item['nomor_resi'] ?? $item['tracking_number'] ?? '');
            if (empty($nomorResi)) {
                continue;
            }

            $postData = [
                'no_pesanan'      => $item['no_pesanan'] ?? ('RESI-' . $nomorResi),
                'nomor_resi'      => $nomorResi,
                'kurir'           => !empty($item['kurir']) && $item['kurir'] !== 'UNKNOWN' ? $item['kurir'] : ($item['courier'] ?? 'SPX Express'),
                'source'          => in_array($item['source'] ?? '', ['shopee', 'tiktok', 'tokopedia', 'lazada'], true) ? $item['source'] : 'shopee',
                'nama_penerima'   => !empty($item['nama_penerima']) ? $item['nama_penerima'] : (!empty($item['recipient_name']) ? $item['recipient_name'] : 'Pelanggan Resi'),
                'no_telepon'      => !empty($item['no_telepon']) ? $item['no_telepon'] : ($item['recipient_phone'] ?? ''),
                'alamat_penerima' => !empty($item['alamat_penerima']) ? $item['alamat_penerima'] : (!empty($item['recipient_address']) ? $item['recipient_address'] : 'Indonesia'),
                'kota'            => $item['kota'] ?? $item['city'] ?? '',
                'sku'             => $item['sku'] ?? '',
                'product_name'    => $item['product_name'] ?? $item['nama_produk'] ?? '',
                'variasi'         => $item['variasi'] ?? '',
                'qty'             => max(1, (int) ($item['qty'] ?? 1)),
                'total'           => (float) ($item['total'] ?? 0),
                'is_cod'          => !empty($item['is_cod']),
                'tanggal'         => !empty($item['tanggal']) ? $item['tanggal'] : date('Y-m-d'),
                'items'           => !empty($item['items']) && is_array($item['items']) ? $item['items'] : [],
            ];

            try {
                $this->importService->saveScrapedResiOrder($postData, $userId);
                $successCount++;
                $savedResi[] = $nomorResi;
            } catch (\Throwable $e) {
                $err = $e->getMessage();
                if (str_contains($err, 'Duplicate entry') || str_contains($err, 'uq_marketplace_source_pesanan')) {
                    $err = "Pesanan #{$postData['no_pesanan']} sudah pernah diimpor sebelumnya.";
                }
                $errors[] = "Resi {$nomorResi}: " . $err;
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'        => $successCount > 0 ? 'success' : 'error',
                'success_count' => $successCount,
                'saved_resi'    => $savedResi,
                'errors'        => $errors,
                'message'       => "Berhasil mengimpor & mengintegrasikan {$successCount} resi pesanan ke database (ERP SO, Invoice, Jurnal, stok fisik, CRM & RFM).",
            ]);
        }

        $msg = "Berhasil mengimpor & merekap <strong>{$successCount} resi pesanan</strong> ke seluruh sistem!<br>"
             . "&bull; <strong>ERP Penjualan:</strong> {$successCount} Sales Order & Faktur Penjualan (Invoice Lunas) diterbitkan.<br>"
             . "&bull; <strong>Keuangan:</strong> {$successCount} Buku Jurnal Umum (Debit Piutang Usaha 1140 & Kredit Pendapatan 4110) tercatat.<br>"
             . "&bull; <strong>Stok Fisik:</strong> Saldo stok produk otomatis terpotong di modul Manajemen Stok.<br>"
             . "&bull; <strong>CRM WhatsApp:</strong> " . ($successCount * 3) . " jadwal pesan follow-up after-sales (H+0, H+3, H+7) aktif.<br>"
             . "&bull; <strong>Pusat Notifikasi:</strong> Alert telah dicatat di lonceng notifikasi sistem.";
        if (!empty($errors)) {
            $msg .= "<br><small class='text-warning'>" . implode('<br>', array_slice($errors, 0, 3)) . "</small>";
        }

        return redirect()->to('marketplace')->with('success', $msg);
    }

    public function saveResiOrder()
    {
        $user = session()->get('user');
        $userId = $user['id'] ?? null;

        $itemsJson = (string) $this->request->getPost('items_json');
        $items = !empty($itemsJson) ? json_decode($itemsJson, true) : null;
        if (empty($items)) {
            $itemsPost = $this->request->getPost('items');
            if (is_array($itemsPost)) {
                $items = $itemsPost;
            }
        }

        $postData = [
            'no_pesanan'      => $this->request->getPost('no_pesanan'),
            'nomor_resi'      => $this->request->getPost('nomor_resi'),
            'kurir'           => $this->request->getPost('kurir'),
            'source'          => $this->request->getPost('source'),
            'nama_penerima'   => $this->request->getPost('nama_penerima'),
            'no_telepon'      => (string) $this->request->getPost('no_telepon'),
            'alamat_penerima' => $this->request->getPost('alamat_penerima'),
            'kota'            => $this->request->getPost('kota'),
            'sku'             => $this->request->getPost('sku'),
            'product_name'    => $this->request->getPost('product_name'),
            'variasi'         => $this->request->getPost('variasi'),
            'qty'             => $this->request->getPost('qty'),
            'total'           => $this->request->getPost('total'),
            'is_cod'          => $this->request->getPost('is_cod') ? 1 : 0,
            'tanggal'         => $this->request->getPost('tanggal') ?: date('Y-m-d'),
            'items'           => $items,
        ];

        if (empty($postData['nomor_resi'])) {
            return redirect()->back()->with('error', 'Nomor resi wajib terisi.');
        }

        try {
            $result = $this->importService->saveScrapedResiOrder($postData, $userId);

            $msg = "Resi <strong>{$postData['nomor_resi']}</strong> ({$postData['kurir']}) berhasil diimpor";

            return redirect()->to('marketplace')->with('success', $msg);
        } catch (\Throwable $e) {
            $errMsg = $e->getMessage();
            if (str_contains($errMsg, 'Duplicate entry') || str_contains($errMsg, 'uq_marketplace_source_pesanan')) {
                $errMsg = "Pesanan #{$postData['no_pesanan']} (" . ucfirst($postData['source'] ?? 'Marketplace') . ") sudah pernah diimpor sebelumnya.";
            }
            return redirect()->back()->with('error', $errMsg)->withInput();
        }
    }

    public function detail(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('penjualan_marketplace pm')
            ->select('pm.*, so.nomor_so, so.status as so_status, so.tanggal_so, so.metode_bayar, p.nama_pelanggan, p.kode_pelanggan, p.tipe_pelanggan, p.kota as pelanggan_kota, inv.nomor_invoice, inv.status as invoice_status, inv.jatuh_tempo')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->join('invoice inv', 'inv.so_id = so.id', 'left')
            ->where('pm.id', $id)
            ->where('pm.deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('marketplace')->with('error', 'Pesanan marketplace tidak ditemukan.');
        }

        $order['is_cod'] = !empty($order['is_cod']) || strtolower((string)($order['metode_bayar'] ?? '')) === 'cod';

        $items = $db->table('penjualan_marketplace_item pmi')
            ->select('pmi.*, pr.kode_produk, pr.nama_produk, sat.nama_satuan')
            ->join('produk pr', 'pr.id = pmi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = pr.satuan_id', 'left')
            ->where('pmi.penjualan_marketplace_id', $id)
            ->get()
            ->getResultArray();

        if (empty($items)) {
            $items = [
                [
                    'sku'                     => 'SKU-MP-' . $order['id'],
                    'nama_produk_marketplace' => 'Produk Marketplace (' . ucfirst((string) $order['source']) . ')',
                    'qty'                     => 1,
                    'harga_satuan'            => (float) $order['total'],
                    'subtotal'                => (float) $order['total'],
                    'nama_produk'             => null,
                    'kode_produk'             => null,
                    'nama_satuan'             => 'PCS',
                ]
            ];
        }

        // CRM follow-up schedules if any
        $crmFollowups = [];
        if (!empty($order['so_id']) && $db->tableExists('crm_transaction_followup_labels')) {
            $crmFollowups = $db->table('crm_transaction_followup_labels')
                ->where('order_id', $order['so_id'])
                ->where('order_source', $order['source'])
                ->orderBy('target_date', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'        => 'Detail Pesanan Marketplace #' . ($order['no_pesanan'] ?: $order['nomor_resi']),
            'order'        => $order,
            'items'        => $items,
            'crmFollowups' => $crmFollowups,
        ];

        return view('Modules\PenjualanMarketplace\Views\detail', $data);
    }

    public function edit(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('penjualan_marketplace pm')
            ->select('pm.*, so.nomor_so, so.status as so_status, so.tanggal_so, so.metode_bayar, p.nama_pelanggan, p.kode_pelanggan, p.tipe_pelanggan, p.kota as pelanggan_kota')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->where('pm.id', $id)
            ->where('pm.deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('marketplace')->with('error', 'Pesanan marketplace tidak ditemukan.');
        }

        $order['is_cod'] = !empty($order['is_cod']) || strtolower((string)($order['metode_bayar'] ?? '')) === 'cod';

        $items = $db->table('penjualan_marketplace_item pmi')
            ->select('pmi.*, pr.kode_produk, pr.nama_produk, sat.nama_satuan')
            ->join('produk pr', 'pr.id = pmi.produk_id', 'left')
            ->join('satuan sat', 'sat.id = pr.satuan_id', 'left')
            ->where('pmi.penjualan_marketplace_id', $id)
            ->get()
            ->getResultArray();

        if (empty($items)) {
            $items = [
                [
                    'sku'                     => 'SKU-MP-' . $order['id'],
                    'nama_produk_marketplace' => 'Produk Marketplace (' . ucfirst((string) $order['source']) . ')',
                    'qty'                     => 1,
                    'harga_satuan'            => (float) $order['total'],
                    'subtotal'                => (float) $order['total'],
                    'produk_id'               => null,
                ]
            ];
        }

        $products = $db->table('produk')
            ->select('id, kode_produk, nama_produk, harga_jual, barcode')
            ->where('deleted_at IS NULL')
            ->orderBy('nama_produk', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Edit Pesanan Marketplace #' . ($order['no_pesanan'] ?: $order['nomor_resi']),
            'order'    => $order,
            'items'    => $items,
            'products' => $products,
        ];

        return view('Modules\PenjualanMarketplace\Views\edit', $data);
    }

    public function update(int $id)
    {
        $user = session()->get('user');
        $userId = $user['id'] ?? null;

        $itemsPost = $this->request->getPost('items');
        if (!is_array($itemsPost) || empty($itemsPost)) {
            $itemsJson = (string) $this->request->getPost('items_json');
            if (!empty($itemsJson)) {
                $itemsPost = json_decode($itemsJson, true);
            }
        }

        $postData = [
            'no_pesanan'      => $this->request->getPost('no_pesanan'),
            'nomor_resi'      => $this->request->getPost('nomor_resi'),
            'kurir'           => $this->request->getPost('kurir'),
            'source'          => $this->request->getPost('source'),
            'nama_penerima'   => $this->request->getPost('nama_penerima'),
            'no_telepon'      => (string) $this->request->getPost('no_telepon'),
            'alamat_penerima' => $this->request->getPost('alamat_penerima'),
            'kota'            => $this->request->getPost('kota'),
            'total'           => $this->request->getPost('total'),
            'ongkir'          => $this->request->getPost('ongkir'),
            'voucher'         => $this->request->getPost('voucher'),
            'biaya_layanan'   => $this->request->getPost('biaya_layanan'),
            'is_cod'          => $this->request->getPost('is_cod') ? 1 : 0,
            'status'          => $this->request->getPost('status') ?: 'ready_to_ship',
            'tanggal_pesanan' => $this->request->getPost('tanggal_pesanan'),
            'items'           => $itemsPost,
        ];

        try {
            $res = $this->importService->updateOrder($id, $postData, $userId);

            $msg = "Pesanan #<strong>{$res['no_pesanan']}</strong> berhasil diperbarui dan disinkronkan ke seluruh modul ERP & Stok.";
            return redirect()->to('marketplace/detail/' . $id)->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');
        $allowed = ['unpaid', 'ready_to_ship', 'shipped', 'completed', 'cancelled', 'returned'];
        if (!in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid.');
        }

        $db = \Config\Database::connect();
        $order = $db->table('penjualan_marketplace')->where('id', $id)->get()->getRowArray();
        if (!$order) {
            return redirect()->to('marketplace')->with('error', 'Pesanan marketplace tidak ditemukan.');
        }

        $db->table('penjualan_marketplace')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($order['so_id'])) {
            $soStatusMap = [
                'unpaid'        => 'draft',
                'ready_to_ship' => 'ready_to_ship',
                'shipped'       => 'shipped',
                'completed'     => 'completed',
                'cancelled'     => 'cancelled',
                'returned'      => 'cancelled',
            ];
            if (isset($soStatusMap[$status])) {
                $db->table('sales_order')->where('id', $order['so_id'])->update([
                    'status'     => $soStatusMap[$status],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to('marketplace/detail/' . $id)->with('success', 'Status pesanan marketplace berhasil diperbarui.');
    }

    /**
     * Update customer / recipient data for a marketplace order
     * and optionally sync changes back to ERP master data pelanggan and CRM schedules.
     */
    public function updatePelanggan(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('penjualan_marketplace pm')
            ->select('pm.*, so.pelanggan_id')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->where('pm.id', $id)
            ->where('pm.deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('marketplace')->with('error', 'Pesanan marketplace tidak ditemukan.');
        }

        $namaPenerima = trim((string) $this->request->getPost('nama_penerima'));
        $rawPhone = trim((string) $this->request->getPost('no_telepon'));
        $alamat = trim((string) $this->request->getPost('alamat_penerima'));
        $kota = trim((string) $this->request->getPost('kota'));
        $syncMaster = !empty($this->request->getPost('sync_master'));

        if (empty($namaPenerima)) {
            return redirect()->back()->with('error', 'Nama penerima / pelanggan wajib diisi.');
        }

        // Normalisasi format telepon (08xx)
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '62')) {
            $localPhone = '0' . substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '8')) {
            $localPhone = '0' . $cleanPhone;
        } else {
            $localPhone = $cleanPhone;
        }

        $db->transStart();
        try {
            // 1. Update penjualan_marketplace
            $db->table('penjualan_marketplace')->where('id', $id)->update([
                'nama_penerima'   => $namaPenerima,
                'no_telepon'      => $localPhone,
                'alamat_penerima' => $alamat,
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            // 2. Update master data pelanggan jika terhubung & opsi sinkronisasi aktif
            if ($syncMaster && !empty($order['pelanggan_id'])) {
                $custUpdate = [
                    'nama_pelanggan' => $namaPenerima,
                    'no_telp'        => $localPhone,
                    'alamat'         => $alamat,
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];
                if (!empty($kota)) {
                    $custUpdate['kota'] = $kota;
                }
                $db->table('pelanggan')->where('id', $order['pelanggan_id'])->update($custUpdate);
            }

            // 3. Update jadwal follow-up CRM jika ada
            if (!empty($order['so_id']) && $db->tableExists('crm_transaction_followup_labels')) {
                $crmUpdate = ['customer_nama' => $namaPenerima];
                if (!empty($localPhone)) {
                    $cleanCrmPhone = preg_replace('/[^0-9]/', '', $localPhone);
                    if (str_starts_with($cleanCrmPhone, '0')) {
                        $cleanCrmPhone = '62' . substr($cleanCrmPhone, 1);
                    }
                    $crmUpdate['customer_phone'] = $cleanCrmPhone;
                }
                $db->table('crm_transaction_followup_labels')
                    ->where('order_id', $order['so_id'])
                    ->update($crmUpdate);
            }

            $db->transComplete();

            return redirect()->to('marketplace/detail/' . $id)->with('success', "Data pelanggan & penerima untuk pesanan #{$order['no_pesanan']} berhasil diperbarui.");
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui data pelanggan: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $this->importService->cascadeDeleteOrder($id);
            return redirect()->to('marketplace')->with('success', 'Pesanan marketplace dan seluruh relasi terkait (item, stok, jadwal CRM) berhasil dibersihkan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }

    public function resiThermalData(int $id)
    {
        $db = \Config\Database::connect();
        $order = $db->table('penjualan_marketplace pm')
            ->select('pm.*, so.nomor_so, so.metode_bayar')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->where('pm.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Pesanan marketplace tidak ditemukan.'
            ]);
        }

        $order['is_cod'] = !empty($order['is_cod']) || strtolower((string)($order['metode_bayar'] ?? '')) === 'cod';

        $items = $db->table('penjualan_marketplace_item pmi')
            ->select('pmi.*, pr.kode_produk, pr.nama_produk')
            ->join('produk pr', 'pr.id = pmi.produk_id', 'left')
            ->where('pmi.penjualan_marketplace_id', $id)
            ->get()
            ->getResultArray();

        // If items empty, create fallback from summary
        if (empty($items)) {
            $items = [
                [
                    'sku'                      => 'SKU-MP-' . $order['id'],
                    'nama_produk_marketplace' => 'Produk Marketplace (' . ucfirst($order['source']) . ')',
                    'qty'                      => 1,
                    'harga_satuan'             => (float)$order['total'],
                    'subtotal'                 => (float)$order['total'],
                ]
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'order'  => $order,
            'items'  => $items,
            'store'  => [
                'name'    => 'OFFICIAL STORE ERP',
                'address' => 'Pusat Distribusi ERP, Jakarta Barat',
                'phone'   => '0812-3456-7890',
            ]
        ]);
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $orders = $db->table('penjualan_marketplace pm')
            ->select('pm.*, so.nomor_so')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->where('pm.deleted_at IS NULL')
            ->orderBy('pm.tanggal_pesanan', 'DESC')
            ->orderBy('pm.id', 'DESC')
            ->get()
            ->getResultArray();

        $headers = [
            'No', 'No. Pesanan', 'Channel Marketplace', 'Nomor Resi / AWB', 'Kurir / Ekspedisi',
            'No. Transaksi SO', 'Nama Penerima', 'No. Telepon', 'Alamat Pengiriman', 
            'Total Belanja (Rp)', 'Ongkos Kirim (Rp)', 'Status Pesanan', 'Tanggal Pesanan'
        ];

        $rows = [];
        foreach ($orders as $idx => $o) {
            $rows[] = [
                $idx + 1,
                $o['no_pesanan'],
                strtoupper($o['source'] ?? 'marketplace'),
                $o['nomor_resi'] ?? '-',
                $o['kurir'] ?? 'Reguler',
                $o['nomor_so'] ?? '-',
                $o['nama_penerima'] ?? '-',
                $o['no_telepon'] ?? '-',
                $o['alamat_penerima'] ?? '-',
                $o['total'],
                $o['ongkir'] ?? 0,
                $o['status'] ?? 'Perlu Dikirim',
                $o['tanggal_pesanan'],
            ];
        }

        export_csv_file('Export_Penjualan_Marketplace_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    public function syncMaster()
    {
        try {
            $res = $this->importService->syncExistingUnmappedData();
            $msg = "Sinkronisasi Master Data selesai: <strong>{$res['synced_products']}</strong> item produk diperbarui & <strong>{$res['synced_customers']}</strong> data pelanggan berhasil disinkronkan ke Master Data.";
            return redirect()->to('marketplace')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->to('marketplace')->with('error', 'Gagal sinkronisasi ke Master Data: ' . $e->getMessage());
        }
    }
}

