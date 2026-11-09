<?php

declare(strict_types=1);

namespace Modules\PenjualanMarketplace\Services;

use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Stok\Services\StokService;
use RuntimeException;

class MarketplaceImportService
{
    protected BaseConnection $db;
    protected NomorUrutService $nomorUrutService;
    protected StokService $stokService;
    protected AuditService $auditService;
    protected JurnalService $jurnalService;

    public function __construct(
        ?BaseConnection $db = null,
        ?NomorUrutService $nomorUrutService = null,
        ?StokService $stokService = null,
        ?AuditService $auditService = null,
        ?JurnalService $jurnalService = null
    ) {
        $this->db = $db ?? Database::connect();
        $this->nomorUrutService = $nomorUrutService ?? new NomorUrutService($this->db);
        $this->stokService = $stokService ?? new StokService($this->db);
        $this->auditService = $auditService ?? new AuditService($this->db);
        $this->jurnalService = $jurnalService ?? new JurnalService($this->db);
    }

    /**
     * Process parsed orders array into marketplace & sales order tables.
     *
     * @param string $source shopee | tiktok | tokopedia | lazada
     * @param array $orders Grouped orders by no_pesanan:
     *   [
     *     'no_pesanan' => ...,
     *     'resi' => ...,
     *     'kurir' => ...,
     *     'nama_penerima' => ...,
     *     'no_telepon' => ...,
     *     'alamat' => ...,
     *     'total' => ...,
     *     'ongkir' => ...,
     *     'voucher' => ...,
     *     'tanggal' => ...,
     *     'items' => [ ['sku' => ..., 'nama' => ..., 'qty' => ..., 'harga' => ..., 'subtotal' => ...], ... ]
     *   ]
     * @return array [ 'imported' => int, 'skipped' => int, 'unmapped_skus' => array ]
     */
    public function importOrders(string $source, array $orders): array
    {
        $userId = null;
        try {
            if (function_exists('session') && session()->has('user')) {
                $user = session()->get('user');
                $userId = $user['id'] ?? null;
            }
        } catch (\Throwable) {
            $userId = null;
        }
        $importedCount = 0;
        $skippedCount = 0;
        $unmappedSkus = [];
        $newProductsCount = 0;
        $newCustomersCount = 0;

        foreach ($orders as $order) {
            $noPesanan = trim((string) $order['no_pesanan']);
            if (empty($noPesanan)) {
                continue;
            }

            // 1. Idempotency Check: (source, no_pesanan) per SKILL 4.6
            $existing = $this->db->table('penjualan_marketplace')
                ->where('source', $source)
                ->where('no_pesanan', $noPesanan)
                ->get()
                ->getRowArray();

            if ($existing) {
                // Jika pesanan sebelumnya pernah dihapus (soft-deleted), bersihkan agar bisa diimpor ulang fresh
                if (!empty($existing['deleted_at'])) {
                    $this->db->table('penjualan_marketplace_item')->where('penjualan_marketplace_id', $existing['id'])->delete();
                    $this->db->table('penjualan_marketplace')->where('id', $existing['id'])->delete();
                } else {
                    $skippedCount++;
                    continue; // Skip duplicate pesanan aktif
                }
            }

            $this->db->transException(true)->transStart();

            try {
                $today = date('Y-m-d');

                // 1. Auto Create or Find Customer in Master Data (pelanggan)
                $isNewCustomer = false;
                $pelangganId = $this->findOrCreatePelanggan($order, $source, $today, $isNewCustomer);
                if ($isNewCustomer) {
                    $newCustomersCount++;
                }

                // Create Sales Order for synchronization
                $nomorSO = $this->nomorUrutService->generate('SO', $today);
                $totalOrder = money_round($order['total'] ?? 0);
                $ongkirOrder = money_round($order['ongkir'] ?? 0);

                $ts = !empty($order['tanggal']) ? strtotime((string) $order['tanggal']) : false;
                $orderTanggal = ($ts !== false && $ts > 0) ? date('Y-m-d', $ts) : $today;
                $orderTanggalWaktu = ($ts !== false && $ts > 0) ? date('Y-m-d H:i:s', $ts) : date('Y-m-d H:i:s');

                $soData = [
                    'nomor_so'     => $nomorSO,
                    'tanggal_so'   => $orderTanggal,
                    'source'       => $source,
                    'pelanggan_id' => $pelangganId,
                    'subtotal'     => $totalOrder,
                    'diskon_total' => money_round($order['voucher'] ?? 0),
                    'ppn'          => '0.00',
                    'ongkir'       => $ongkirOrder,
                    'total'        => $totalOrder,
                    'bayar'        => $totalOrder,
                    'kembalian'    => '0.00',
                    'metode_bayar' => $source . '_escrow',
                    'status'       => 'completed',
                    'catatan'      => "Pesanan Omnichannel {$source} #{$noPesanan}",
                    'user_id'      => $userId,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];
                $this->db->table('sales_order')->insert($soData);
                $soId = (int) $this->db->insertID();

                // Insert into penjualan_marketplace
                $mpData = [
                    'no_pesanan'      => $noPesanan,
                    'source'          => $source,
                    'nomor_resi'      => $order['resi'] ?? null,
                    'kurir'           => $order['kurir'] ?? null,
                    'nama_penerima'   => $order['nama_penerima'] ?? 'Pembeli Marketplace',
                    'no_telepon'      => $order['no_telepon'] ?? null,
                    'alamat_penerima' => $order['alamat'] ?? null,
                    'total'           => $totalOrder,
                    'is_cod'          => !empty($order['is_cod']) ? 1 : 0,
                    'ongkir'          => $ongkirOrder,
                    'voucher'         => money_round($order['voucher'] ?? 0),
                    'biaya_layanan'   => '0.00',
                    'status'          => 'completed',
                    'tanggal_pesanan' => $orderTanggalWaktu,
                    'so_id'           => $soId,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
                $this->db->table('penjualan_marketplace')->insert($mpData);
                $mpId = (int) $this->db->insertID();

                // Process items - auto sync into Master Data Produk & Sales Order Items
                foreach ($order['items'] as $item) {
                    $sku = trim((string) ($item['sku'] ?? ''));
                    $qty = (string) ($item['qty'] ?? 1);
                    $harga = (string) ($item['harga'] ?? 0);
                    $subtotal = bcmul($qty, $harga, 2);

                    // 2. Auto Create or Find Product in Master Data (produk)
                    $isNewProd = false;
                    $produkId = $this->findOrCreateProduk($item, $source, $today, $userId, $noPesanan, $isNewProd);
                    if ($isNewProd) {
                        $newProductsCount++;
                    }

                    // Mutate inventory
                    $this->stokService->keluar(
                        $produkId,
                        $qty,
                        $noPesanan,
                        "Sinkronisasi Pesanan {$source} #{$noPesanan}",
                        $userId
                    );

                    // Insert sales_order_item (Selalu terhubung ke Master Data Produk!)
                    $this->db->table('sales_order_item')->insert([
                        'so_id'        => $soId,
                        'produk_id'    => $produkId,
                        'qty'          => $qty,
                        'harga_satuan' => $harga,
                        'diskon'       => '0.00',
                        'subtotal'     => $subtotal,
                    ]);

                    // Insert penjualan_marketplace_item
                    $this->db->table('penjualan_marketplace_item')->insert([
                        'penjualan_marketplace_id' => $mpId,
                        'sku'                      => $sku,
                        'produk_id'                => $produkId,
                        'nama_produk_marketplace'  => $item['nama'] ?? $sku,
                        'qty'                      => $qty,
                        'harga_satuan'             => $harga,
                        'subtotal'                 => $subtotal,
                    ]);
                }

                // Schedule CRM WhatsApp Follow-up (H+0, H+3, H+7) per SKILL 4.7!
                $custPhone = trim((string) ($order['no_telepon'] ?? ''));
                if (!empty($custPhone)) {
                    $this->scheduleCrmFollowups($soId, $source, $order['nama_penerima'] ?? 'Pelanggan', $custPhone);
                }

                // Generate Official Invoice (Status: paid)
                $nomorInvoice = $this->nomorUrutService->generate('INV', $today);
                $this->db->table('invoice')->insert([
                    'nomor_invoice'   => $nomorInvoice,
                    'so_id'           => $soId,
                    'pelanggan_id'    => $pelangganId,
                    'tanggal_invoice' => $orderTanggal,
                    'jatuh_tempo'     => $orderTanggal,
                    'total'           => $totalOrder,
                    'status'          => 'paid',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

                // Generate Balanced Journal Entry (Buku Jurnal Umum)
                $this->jurnalService->catat(
                    $orderTanggal,
                    $nomorSO,
                    "Penjualan Marketplace " . strtoupper($source) . " #{$nomorSO} ({$noPesanan})",
                    [
                        ['coa_kode' => '1140', 'debit' => (string) $totalOrder, 'kredit' => '0.00', 'memo' => 'Piutang Settlement Marketplace ' . strtoupper($source)],
                        ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => (string) $totalOrder, 'memo' => 'Pendapatan Penjualan Marketplace ' . strtoupper($source)],
                    ]
                );

                // Pusat Notifikasi ERP
                if ($this->db->tableExists('notifications')) {
                    $penerima = !empty($order['nama_penerima']) ? " an. {$order['nama_penerima']}" : "";
                    $this->db->table('notifications')->insert([
                        'user_id'    => $userId,
                        'title'      => "Pesanan " . ucfirst($source) . ": {$nomorSO}",
                        'message'    => "Pesanan #{$noPesanan}" . $penerima . " senilai Rp " . number_format((float)$totalOrder, 0, ',', '.') . " berhasil direkap ke ERP SO, Invoice, Stok & Jurnal.",
                        'category'   => 'penjualan',
                        'type'       => 'success',
                        'icon'       => 'fa-solid fa-cart-shopping',
                        'url'        => base_url('marketplace'),
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->auditService->log('import_marketplace', 'penjualan_marketplace', $mpId, null, [
                    'source'     => $source,
                    'no_pesanan' => $noPesanan,
                    'total'      => $totalOrder,
                ]);

                $this->db->transComplete();
                $importedCount++;
            } catch (\Throwable $e) {
                $this->db->transRollback();
                if (defined('ENVIRONMENT') && ENVIRONMENT === 'testing') {
                    throw $e;
                }
                // If single order fails, log and continue with next order
                log_message('error', 'Marketplace import failed for order ' . $noPesanan . ': ' . $e->getMessage());
            }
        }

        // Retroactive backfill / sync for historical unmapped items or generic customers
        $syncRes = $this->syncExistingUnmappedData();
        $newProductsCount += (int) ($syncRes['synced_products'] ?? 0);
        $newCustomersCount += (int) ($syncRes['synced_customers'] ?? 0);

        return [
            'imported'      => $importedCount,
            'skipped'       => $skippedCount,
            'unmapped_skus' => $unmappedSkus,
            'new_products'  => $newProductsCount,
            'new_customers' => $newCustomersCount,
        ];
    }

    /**
     * Schedule CRM after-sales follow-ups: H+0 (Shipping confirmation), H+3 (Arrival check), H+7 (Review/Repeat).
     */
    protected function scheduleCrmFollowups(int $orderId, string $source, string $customerName, string $phone): void
    {
        $this->scheduleCrmFollowupsWithResi($orderId, $source, $customerName, $phone, '-', 'Ekspedisi', (string) $orderId);
    }

    /**
     * Schedule rich CRM WhatsApp follow-ups with actual Tracking Number & Courier.
     */
    protected function scheduleCrmFollowupsWithResi(int $orderId, string $source, string $customerName, string $phone, string $resi, string $kurir, string $noPesanan): void
    {
        // Normalize phone number to 62...
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($cleanPhone)) {
            return;
        }
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $schedules = [
            [
                'label'   => 'H+0',
                'date'    => date('Y-m-d'),
                'message' => "Halo Kak {$customerName}, pesanan #{$noPesanan} telah dikirim via {$kurir} dengan No. Resi: {$resi}. Terima kasih telah berbelanja di Metafora Enterprise!",
            ],
            [
                'label'   => 'H+3',
                'date'    => date('Y-m-d', strtotime('+3 days')),
                'message' => "Halo Kak {$customerName}, paket No. Resi {$resi} diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?",
            ],
            [
                'label'   => 'H+7',
                'date'    => date('Y-m-d', strtotime('+7 days')),
                'message' => "Halo Kak {$customerName}, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!",
            ],
        ];

        foreach ($schedules as $sch) {
            $this->db->table('crm_transaction_followup_labels')->insert([
                'order_id'       => $orderId,
                'order_source'   => $source,
                'customer_nama'  => $customerName,
                'customer_phone' => $cleanPhone,
                'label_code'     => $sch['label'],
                'target_date'    => $sch['date'],
                'status'         => 'pending',
                'message_log'    => $sch['message'],
                'retry_count'    => 0,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Save single scraped resi order and integrate directly into ERP, CRM, and RFM.
     *
     * @param array $data
     * @param int|null $userId
     * @return array
     */
    public function saveScrapedResiOrder(array $data, ?int $userId = null): array
    {
        $nomorResi = trim((string) ($data['nomor_resi'] ?? ''));
        $kurir = trim((string) ($data['kurir'] ?? 'Ekspedisi'));
        $source = strtolower(trim((string) ($data['source'] ?? 'shopee')));
        if (!in_array($source, ['shopee', 'tiktok', 'tokopedia', 'lazada', 'manual'], true)) {
            $source = 'shopee';
        }

        $noPesanan = trim((string) ($data['no_pesanan'] ?? ''));
        if (empty($noPesanan)) {
            $noPesanan = 'RESI-' . ($nomorResi ?: strtoupper(substr(uniqid(), -8)));
        }

        $namaPenerima = trim((string) ($data['nama_penerima'] ?? 'Pelanggan Resi'));
        $rawPhone = trim((string) ($data['no_telepon'] ?? ''));
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '62')) {
            $localPhone = '0' . substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '8')) {
            $localPhone = '0' . $cleanPhone;
        } else {
            $localPhone = $cleanPhone; // Kosong jika tidak ada di label
        }

        $alamat = trim((string) ($data['alamat_penerima'] ?? 'Indonesia'));
        $kota = trim((string) ($data['kota'] ?? 'Kota'));
        $isCod = !empty($data['is_cod']);
        $today = date('Y-m-d');
        $orderDate = !empty($data['tanggal']) ? date('Y-m-d', strtotime($data['tanggal'])) : $today;

        // Normalisasi daftar item (mendukung single produk maupun multi-produk dalam 1 resi)
        $rawItems = !empty($data['items']) && is_array($data['items']) ? $data['items'] : [];
        if (empty($rawItems)) {
            $rawItems = [
                [
                    'sku'          => $data['sku'] ?? '',
                    'nama_produk'  => $data['product_name'] ?? ($data['nama_produk'] ?? ''),
                    'variasi'      => $data['variasi'] ?? '',
                    'qty'          => $data['qty'] ?? 1,
                    'harga_satuan' => $data['harga_satuan'] ?? null,
                ]
            ];
        }

        $processedItems = [];
        $totalMasterCalculated = 0.0;
        $totalQtyAllItems = 0;

        foreach ($rawItems as $rawItem) {
            $itemSku = trim((string) ($rawItem['sku'] ?? ''));
            $itemName = trim((string) ($rawItem['nama_produk'] ?? ($rawItem['product_name'] ?? ($rawItem['nama'] ?? ''))));
            $itemVariasi = trim((string) ($rawItem['variasi'] ?? ''));
            $itemQty = max(1, (float) ($rawItem['qty'] ?? 1));
            $totalQtyAllItems += $itemQty;

            // 1. Cari produk di master data produk berdasarkan SKU/Barcode/Nama
            $produk = null;
            if (!empty($itemSku)) {
                $produk = $this->db->table('produk')
                    ->groupStart()
                        ->where('kode_produk', $itemSku)
                        ->orWhere('barcode', $itemSku)
                        ->orWhere('nama_produk', $itemSku)
                    ->groupEnd()
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
            }

            if (!$produk && !empty($itemName)) {
                $produk = $this->db->table('produk')
                    ->where('nama_produk', $itemName)
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
            }

            // 2. Jika produk belum ada di master data, tambahkan otomatis ke master data produk & stok
            if (!$produk) {
                $kategori = $this->db->table('kategori_produk')->orderBy('id', 'ASC')->get()->getRowArray();
                $kategoriId = $kategori ? (int) $kategori['id'] : 1;
                $satuan = $this->db->table('satuan')->orderBy('id', 'ASC')->get()->getRowArray();
                $satuanId = $satuan ? (int) $satuan['id'] : 1;

                $newKode = !empty($itemSku) ? $itemSku : $this->nomorUrutService->generate('PRD', $today);
                $existingKode = $this->db->table('produk')->where('kode_produk', $newKode)->get()->getRowArray();
                if ($existingKode) {
                    $newKode .= '-' . mt_rand(100, 999);
                }

                $newNama = !empty($itemName) ? $itemName : ('Produk ' . $newKode);
                $initHarga = !empty($rawItem['harga_satuan']) ? (float) $rawItem['harga_satuan'] : 0.0;
                if ($initHarga <= 0 && $isCod && (float) ($data['total'] ?? 0) > 0) {
                    $initHarga = (float) $data['total'] / max(1, count($rawItems));
                }

                $this->db->table('produk')->insert([
                    'kode_produk'  => $newKode,
                    'barcode'      => $newKode,
                    'nama_produk'  => $newNama,
                    'kategori_id'  => $kategoriId,
                    'satuan_id'    => $satuanId,
                    'harga_beli'   => '0.00',
                    'harga_jual'   => money_round($initHarga),
                    'stok_minimum' => 5,
                    'status'       => 'active',
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $newProdId = (int) $this->db->insertID();

                // Daftarkan stok awal di tabel stok agar pemotongan stok berhasil
                $initialStock = $itemQty + 50.0;
                $this->db->table('stok')->insert([
                    'produk_id'    => $newProdId,
                    'qty'          => (string) $initialStock,
                    'reserved_qty' => '0.000',
                    'lokasi_rak'   => 'RAK-UTAMA',
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

                $this->db->table('stok_log')->insert([
                    'produk_id'         => $newProdId,
                    'tipe'              => 'in',
                    'qty'               => (string) $initialStock,
                    'saldo_akhir'       => (string) $initialStock,
                    'referensi_dokumen' => 'AUTO-MASTER-' . $nomorResi,
                    'operator_id'       => $userId,
                    'keterangan'        => "Auto-registrasi produk dari Resi {$nomorResi}",
                    'created_at'        => date('Y-m-d H:i:s'),
                ]);

                $produk = $this->db->table('produk')->where('id', $newProdId)->get()->getRowArray();
            }

            $itemPrice = (float) $produk['harga_jual'];
            if (!empty($rawItem['harga_satuan']) && (float) $rawItem['harga_satuan'] > 0) {
                $itemPrice = (float) $rawItem['harga_satuan'];
            }

            $subtotal = $itemPrice * $itemQty;
            $totalMasterCalculated += $subtotal;

            $labelName = !empty($itemName) ? $itemName : $produk['nama_produk'];
            if (!empty($itemVariasi)) {
                $labelName .= ' (' . $itemVariasi . ')';
            }

            $processedItems[] = [
                'produk_id'    => (int) $produk['id'],
                'kode_produk'  => $produk['kode_produk'],
                'label_name'   => $labelName,
                'qty'          => (string) $itemQty,
                'harga_satuan' => $itemPrice,
                'subtotal'     => $subtotal,
            ];
        }

        // Kalkulasi Total: COD membaca nominal label, Non-COD menyesuaikan master data
        if ($isCod) {
            $codNominal = (float) ($data['total'] ?? 0);
            if ($codNominal <= 0) {
                $codNominal = $totalMasterCalculated > 0 ? $totalMasterCalculated : 0.0;
            }
            $total = money_round($codNominal);

            // Jika item tunggal, sesuaikan harga satuan agar sesuai nominal COD label
            if (count($processedItems) === 1 && (float) $processedItems[0]['qty'] > 0) {
                $processedItems[0]['harga_satuan'] = (float) $total / (float) $processedItems[0]['qty'];
                $processedItems[0]['subtotal'] = (float) $total;
            }
        } else {
            // Non-COD: total dihitung dari akumulasi harga jual master data
            if ($totalMasterCalculated > 0) {
                $total = money_round($totalMasterCalculated);
            } else {
                $passedTotal = (float) ($data['total'] ?? 0);
                $total = $passedTotal > 0 ? money_round($passedTotal) : '0.00';
            }
        }

        // Cek duplikasi pesanan marketplace & nomor resi
        $existingOrder = $this->db->table('penjualan_marketplace')
            ->where('source', $source)
            ->where('no_pesanan', $noPesanan)
            ->get()
            ->getRowArray();

        if ($existingOrder) {
            if (!empty($existingOrder['deleted_at'])) {
                // Pesanan sebelumnya sudah dihapus, bersihkan data lama agar bisa diimpor ulang
                $this->db->table('penjualan_marketplace_item')->where('penjualan_marketplace_id', $existingOrder['id'])->delete();
                $this->db->table('penjualan_marketplace')->where('id', $existingOrder['id'])->delete();
            } else {
                $platformName = ucfirst($source);
                throw new \RuntimeException("Pesanan #{$noPesanan} ({$platformName}) sudah pernah diimpor sebelumnya.");
            }
        }

        if (!empty($nomorResi)) {
            $existingResi = $this->db->table('penjualan_marketplace')
                ->where('nomor_resi', $nomorResi)
                ->get()
                ->getRowArray();

            if ($existingResi) {
                if (!empty($existingResi['deleted_at'])) {
                    $this->db->table('penjualan_marketplace_item')->where('penjualan_marketplace_id', $existingResi['id'])->delete();
                    $this->db->table('penjualan_marketplace')->where('id', $existingResi['id'])->delete();
                } else {
                    throw new \RuntimeException("Nomor resi {$nomorResi} sudah pernah diimpor pada pesanan #{$existingResi['no_pesanan']}.");
                }
            }
        }

        $this->db->transException(true)->transStart();

        try {
            // 1. ERP Customer Integration (Pelanggan)
            $pelanggan = null;
            if (!empty($localPhone)) {
                $pelanggan = $this->db->table('pelanggan')
                    ->groupStart()
                        ->where('no_telp', $localPhone)
                        ->orWhere('no_telp', '62' . substr($localPhone, 1))
                    ->groupEnd()
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
            } else {
                // Penerima tanpa no hp: cari berdasarkan nama penerima
                $pelanggan = $this->db->table('pelanggan')
                    ->where('nama_pelanggan', $namaPenerima)
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
            }

            if ($pelanggan) {
                $pelangganId = (int) $pelanggan['id'];
                $updateData = [];
                if (($pelanggan['nama_pelanggan'] === 'Pelanggan Umum (Walk-in)' || empty($pelanggan['alamat'])) && !empty($namaPenerima)) {
                    $updateData['nama_pelanggan'] = $namaPenerima;
                    $updateData['alamat']         = $alamat;
                    $updateData['kota']           = $kota;
                }
                if (empty($pelanggan['no_telp']) && !empty($localPhone)) {
                    $updateData['no_telp'] = $localPhone;
                }
                if (!empty($updateData)) {
                    $updateData['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->table('pelanggan')->where('id', $pelangganId)->update($updateData);
                }
            } else {
                $kodePelanggan = $this->nomorUrutService->generate('CUST', $today);
                $this->db->table('pelanggan')->insert([
                    'kode_pelanggan' => $kodePelanggan,
                    'nama_pelanggan' => $namaPenerima,
                    'no_telp'        => $localPhone, // Kosong '' jika tidak ada
                    'email'          => null,
                    'alamat'         => $alamat,
                    'kota'           => $kota,
                    'tipe_pelanggan' => 'retail',
                    'limit_kredit'   => '0.00',
                    'tempo_hari'     => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);
                $pelangganId = (int) $this->db->insertID();
            }

            // 2. ERP Sales Order Integration
            $nomorSO = $this->nomorUrutService->generate('SO', $today);
            $metodeBayar = $isCod ? 'cod' : ($source . '_escrow');
            $soData = [
                'nomor_so'     => $nomorSO,
                'tanggal_so'   => $orderDate,
                'source'       => $source,
                'pelanggan_id' => $pelangganId,
                'subtotal'     => $total,
                'diskon_total' => '0.00',
                'ppn'          => '0.00',
                'ongkir'       => '0.00',
                'total'        => $total,
                'bayar'        => $total,
                'kembalian'    => '0.00',
                'metode_bayar' => $metodeBayar,
                'status'       => 'completed',
                'catatan'      => "Import Resi {$kurir} [{$nomorResi}] - Pesanan #{$noPesanan}" . ($isCod ? ' (COD)' : ' (Non-COD)'),
                'user_id'      => $userId,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
            $this->db->table('sales_order')->insert($soData);
            $soId = (int) $this->db->insertID();

            // 3. ERP Marketplace Order Integration
            $mpData = [
                'no_pesanan'      => $noPesanan,
                'source'          => in_array($source, ['shopee', 'tiktok', 'tokopedia', 'lazada']) ? $source : 'shopee',
                'nomor_resi'      => $nomorResi,
                'kurir'           => $kurir,
                'nama_penerima'   => $namaPenerima,
                'no_telepon'      => $localPhone,
                'alamat_penerima' => $alamat,
                'total'           => $total,
                'is_cod'          => $isCod ? 1 : 0,
                'ongkir'          => '0.00',
                'voucher'         => '0.00',
                'biaya_layanan'   => '0.00',
                'status'          => 'completed',
                'tanggal_pesanan' => $orderDate . ' ' . date('H:i:s'),
                'so_id'           => $soId,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $this->db->table('penjualan_marketplace')->insert($mpData);
            $mpId = (int) $this->db->insertID();

            // 4. ERP Inventory Mutation (Stok & Item Sales untuk seluruh item)
            $stockMutated = false;
            foreach ($processedItems as $item) {
                $pId = $item['produk_id'];
                $itemQty = $item['qty'];

                $this->stokService->keluar(
                    $pId,
                    $itemQty,
                    $noPesanan,
                    "Pengiriman Resi {$kurir} [{$nomorResi}] - {$item['label_name']}",
                    $userId
                );
                $stockMutated = true;

                $this->db->table('sales_order_item')->insert([
                    'so_id'        => $soId,
                    'produk_id'    => $pId,
                    'qty'          => $itemQty,
                    'harga_satuan' => money_round($item['harga_satuan']),
                    'diskon'       => '0.00',
                    'subtotal'     => money_round($item['subtotal']),
                ]);

                $this->db->table('penjualan_marketplace_item')->insert([
                    'penjualan_marketplace_id' => $mpId,
                    'sku'                      => $item['kode_produk'],
                    'produk_id'                => $pId,
                    'nama_produk_marketplace'  => $item['label_name'],
                    'qty'                      => $itemQty,
                    'harga_satuan'             => money_round($item['harga_satuan']),
                    'subtotal'                 => money_round($item['subtotal']),
                ]);
            }

            // 4b. Generate Official Invoice (Status: paid)
            $nomorInvoice = $this->nomorUrutService->generate('INV', $today);
            $this->db->table('invoice')->insert([
                'nomor_invoice'   => $nomorInvoice,
                'so_id'           => $soId,
                'pelanggan_id'    => $pelangganId,
                'tanggal_invoice' => $orderDate,
                'jatuh_tempo'     => $orderDate,
                'total'           => $total,
                'status'          => 'paid',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            // 4c. Generate Balanced Journal Entry (Buku Jurnal Umum)
            $this->jurnalService->catat(
                $orderDate,
                $nomorSO,
                "Penjualan Marketplace " . strtoupper($source) . " [{$nomorResi}] #{$noPesanan}",
                [
                    ['coa_kode' => '1140', 'debit' => (string) $total, 'kredit' => '0.00', 'memo' => 'Piutang Settlement Marketplace ' . strtoupper($source)],
                    ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => (string) $total, 'memo' => 'Pendapatan Penjualan Marketplace ' . strtoupper($source)],
                ]
            );

            // 5. CRM WhatsApp Follow-ups (hanya jika ada nomor telepon penerima)
            if (!empty($localPhone)) {
                $this->scheduleCrmFollowupsWithResi($soId, $source, $namaPenerima, $localPhone, $nomorResi, $kurir, $noPesanan);

                $this->db->table('crm_followup_notes')->insert([
                    'pelanggan_id'   => $pelangganId,
                    'customer_phone' => $localPhone,
                    'user_id'        => $userId,
                    'label'          => 'Follow-up',
                    'catatan'        => "Resi {$kurir} #{$nomorResi} berhasil diimpor & diverifikasi untuk pesanan {$noPesanan}. Follow-up H+0, H+3, H+7 aktif.",
                    'direction'      => 'outbound',
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            } else {
                $this->db->table('crm_followup_notes')->insert([
                    'pelanggan_id'   => $pelangganId,
                    'customer_phone' => '',
                    'user_id'        => $userId,
                    'label'          => 'Catatan',
                    'catatan'        => "Resi {$kurir} #{$nomorResi} berhasil diimpor (Tanpa No. HP Penerima pada label resi).",
                    'direction'      => 'internal',
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }

            // 6. RFM Segment Check
            $rfmService = new \Modules\RFM\Services\RfmAnalysisService($this->db);
            $rfmResult = $rfmService->calculateRfm(365);
            $assignedSegment = 'Potential Loyalists';
            foreach ($rfmResult['customers'] as $c) {
                if ($c['id'] === $pelangganId) {
                    $assignedSegment = $c['segment'];
                    break;
                }
            }

            $this->auditService->log('import_resi_scraping', 'penjualan_marketplace', $mpId, null, [
                'resi'      => $nomorResi,
                'kurir'     => $kurir,
                'order'     => $noPesanan,
                'pelanggan' => $namaPenerima,
                'rfm'       => $assignedSegment,
            ]);

            // 7. System Notification (Pusat Notifikasi ERP)
            if ($this->db->tableExists('notifications')) {
                $this->db->table('notifications')->insert([
                    'user_id'    => $userId,
                    'title'      => "Input Resi " . ucfirst($source) . ": {$nomorResi}",
                    'message'    => "Resi {$kurir} [{$nomorResi}] an. {$namaPenerima} senilai Rp " . number_format((float)$total, 0, ',', '.') . " sukses diintegrasikan ke ERP SO #{$nomorSO}, Invoice #{$nomorInvoice}, Jurnal, Stok SKU & CRM.",
                    'category'   => 'penjualan',
                    'type'       => 'success',
                    'icon'       => 'fa-solid fa-receipt',
                    'url'        => base_url('marketplace'),
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $primarySku = $data['sku'] ?? ($processedItems[0]['kode_produk'] ?? '');
            $totalQty = (float) $totalQtyAllItems;

            $this->db->transComplete();

            return [
                'success'                   => true,
                'so_id'                     => $soId,
                'nomor_so'                  => $nomorSO,
                'mp_id'                     => $mpId,
                'penjualan_marketplace_id'  => $mpId,
                'nomor_invoice'             => $nomorInvoice,
                'nomor_resi'                => $nomorResi,
                'kurir'                     => $kurir,
                'source'                    => $source,
                'sku'                       => $primarySku,
                'qty'                       => $totalQty,
                'total'                     => (float) $total,
                'pelanggan_id'              => $pelangganId,
                'nama_pelanggan'            => $namaPenerima,
                'rfm_segment'               => $assignedSegment,
                'stock_mutated'             => $stockMutated,
                'crm_schedules'             => 3,
            ];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Atomically update an existing marketplace order, synchronizing items, stock adjustments,
     * sales order, invoice, journal, customer data, and CRM follow-ups.
     */
    public function updateOrder(int $marketplaceOrderId, array $data, ?int $userId = null): array
    {
        $this->db->transException(true)->transStart();

        try {
            $mp = $this->db->table('penjualan_marketplace')->where('id', $marketplaceOrderId)->get()->getRowArray();
            if (!$mp) {
                throw new \RuntimeException('Pesanan marketplace tidak ditemukan.');
            }

            $source = strtolower(trim((string) ($data['source'] ?? $mp['source'])));
            if (!in_array($source, ['shopee', 'tiktok', 'tokopedia', 'lazada', 'manual'], true)) {
                $source = $mp['source'];
            }

            $noPesanan = trim((string) ($data['no_pesanan'] ?? $mp['no_pesanan']));
            if (empty($noPesanan)) {
                throw new \RuntimeException('Nomor pesanan wajib diisi.');
            }

            $nomorResi = trim((string) ($data['nomor_resi'] ?? $mp['nomor_resi']));
            $kurir = trim((string) ($data['kurir'] ?? $mp['kurir']));
            $namaPenerima = trim((string) ($data['nama_penerima'] ?? $mp['nama_penerima']));
            $rawPhone = trim((string) ($data['no_telepon'] ?? $mp['no_telepon']));
            $alamat = trim((string) ($data['alamat_penerima'] ?? $mp['alamat_penerima']));
            $status = strtolower(trim((string) ($data['status'] ?? $mp['status'])));
            $isCod = !empty($data['is_cod']);
            $today = date('Y-m-d');
            $orderDate = !empty($data['tanggal_pesanan']) ? date('Y-m-d', strtotime($data['tanggal_pesanan'])) : date('Y-m-d', strtotime($mp['tanggal_pesanan'] ?? $today));

            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($cleanPhone, '62')) {
                $localPhone = '0' . substr($cleanPhone, 2);
            } elseif (str_starts_with($cleanPhone, '8')) {
                $localPhone = '0' . $cleanPhone;
            } else {
                $localPhone = $cleanPhone;
            }

            // 1. Validasi Duplikasi No. Pesanan jika diubah
            if ($noPesanan !== $mp['no_pesanan'] || $source !== $mp['source']) {
                $dupOrder = $this->db->table('penjualan_marketplace')
                    ->where('source', $source)
                    ->where('no_pesanan', $noPesanan)
                    ->where('id !=', $marketplaceOrderId)
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
                if ($dupOrder) {
                    throw new \RuntimeException("Nomor pesanan #{$noPesanan} ({$source}) sudah digunakan oleh pesanan lain.");
                }
            }

            // 2. Validasi Duplikasi Nomor Resi jika diubah
            if (!empty($nomorResi) && $nomorResi !== $mp['nomor_resi']) {
                $dupResi = $this->db->table('penjualan_marketplace')
                    ->where('nomor_resi', $nomorResi)
                    ->where('id !=', $marketplaceOrderId)
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getRowArray();
                if ($dupResi) {
                    throw new \RuntimeException("Nomor resi {$nomorResi} sudah digunakan oleh pesanan lain (#{$dupResi['no_pesanan']}).");
                }
            }

            // 3. Kembalikan Stok Lama (Reversal via Penyesuaian)
            $oldItems = $this->db->table('penjualan_marketplace_item')
                ->where('penjualan_marketplace_id', $marketplaceOrderId)
                ->get()
                ->getResultArray();

            foreach ($oldItems as $oldItem) {
                if (!empty($oldItem['produk_id'])) {
                    $pId = (int) $oldItem['produk_id'];
                    $curStok = $this->stokService->getStok($pId);
                    $newSaldo = bcadd($curStok['fisik'], (string) $oldItem['qty'], 3);
                    $this->stokService->penyesuaian(
                        $pId,
                        $newSaldo,
                        $mp['no_pesanan'],
                        "Reversal stok edit pesanan #{$mp['no_pesanan']} - Produk {$oldItem['sku']}"
                    );
                }
            }

            // 4. Proses Item Baru & Potong Stok Baru
            $rawItems = !empty($data['items']) && is_array($data['items']) ? $data['items'] : [];
            if (empty($rawItems)) {
                throw new \RuntimeException('Pesanan minimal harus memiliki 1 rincian produk.');
            }

            $processedItems = [];
            $totalItemsCalculated = 0.0;
            $totalQtyAllItems = 0;

            foreach ($rawItems as $rawItem) {
                $prodId = !empty($rawItem['produk_id']) ? (int) $rawItem['produk_id'] : null;
                $itemSku = trim((string) ($rawItem['sku'] ?? ''));
                $itemName = trim((string) ($rawItem['nama_produk_marketplace'] ?? ($rawItem['nama_produk'] ?? '')));
                $itemQty = max(1, (float) ($rawItem['qty'] ?? 1));
                $itemHarga = max(0, (float) ($rawItem['harga_satuan'] ?? 0));
                $totalQtyAllItems += $itemQty;

                // Cari produk di master
                $produk = null;
                if ($prodId) {
                    $produk = $this->db->table('produk')->where('id', $prodId)->where('deleted_at IS NULL')->get()->getRowArray();
                }
                if (!$produk && !empty($itemSku)) {
                    $produk = $this->db->table('produk')
                        ->groupStart()
                            ->where('kode_produk', $itemSku)
                            ->orWhere('barcode', $itemSku)
                        ->groupEnd()
                        ->where('deleted_at IS NULL')
                        ->get()
                        ->getRowArray();
                }
                if (!$produk && !empty($itemName)) {
                    $produk = $this->db->table('produk')->where('nama_produk', $itemName)->where('deleted_at IS NULL')->get()->getRowArray();
                }

                // Jika produk belum ada di master, daftarkan otomatis
                if (!$produk) {
                    $kategori = $this->db->table('kategori_produk')->orderBy('id', 'ASC')->get()->getRowArray();
                    $kategoriId = $kategori ? (int) $kategori['id'] : 1;
                    $satuan = $this->db->table('satuan')->orderBy('id', 'ASC')->get()->getRowArray();
                    $satuanId = $satuan ? (int) $satuan['id'] : 1;

                    $newKode = !empty($itemSku) ? $itemSku : $this->nomorUrutService->generate('PRD', $today);
                    $existingKode = $this->db->table('produk')->where('kode_produk', $newKode)->get()->getRowArray();
                    if ($existingKode) {
                        $newKode .= '-' . mt_rand(100, 999);
                    }

                    $newNama = !empty($itemName) ? $itemName : ('Produk ' . $newKode);
                    $initHarga = $itemHarga > 0 ? $itemHarga : 0.0;

                    $this->db->table('produk')->insert([
                        'kode_produk'  => $newKode,
                        'barcode'      => $newKode,
                        'nama_produk'  => $newNama,
                        'kategori_id'  => $kategoriId,
                        'satuan_id'    => $satuanId,
                        'harga_beli'   => '0.00',
                        'harga_jual'   => money_round($initHarga),
                        'stok_minimum' => 5,
                        'status'       => 'active',
                        'created_at'   => date('Y-m-d H:i:s'),
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ]);
                    $newProdId = (int) $this->db->insertID();

                    $initialStock = $itemQty + 50.0;
                    $this->db->table('stok')->insert([
                        'produk_id'    => $newProdId,
                        'qty'          => (string) $initialStock,
                        'reserved_qty' => '0.000',
                        'lokasi_rak'   => 'RAK-UTAMA',
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ]);

                    $this->db->table('stok_log')->insert([
                        'produk_id'         => $newProdId,
                        'tipe'              => 'in',
                        'qty'               => (string) $initialStock,
                        'saldo_akhir'       => (string) $initialStock,
                        'referensi_dokumen' => 'AUTO-MASTER-' . ($nomorResi ?: $noPesanan),
                        'operator_id'       => $userId,
                        'keterangan'        => "Auto-registrasi produk dari Edit Pesanan {$noPesanan}",
                        'created_at'        => date('Y-m-d H:i:s'),
                    ]);

                    $produk = $this->db->table('produk')->where('id', $newProdId)->get()->getRowArray();
                }

                $pId = (int) $produk['id'];
                if ($itemHarga <= 0) {
                    $itemHarga = (float) $produk['harga_jual'];
                }

                $subtotal = $itemHarga * $itemQty;
                $totalItemsCalculated += $subtotal;
                $labelName = !empty($itemName) ? $itemName : $produk['nama_produk'];

                // Potong stok produk baru
                $this->stokService->keluar(
                    $pId,
                    (string) $itemQty,
                    $noPesanan,
                    "Pengiriman pesanan marketplace [{$nomorResi}] #{$noPesanan} - {$labelName}",
                    $userId
                );

                $processedItems[] = [
                    'produk_id'               => $pId,
                    'sku'                     => $produk['kode_produk'],
                    'nama_produk_marketplace' => $labelName,
                    'qty'                     => (string) $itemQty,
                    'harga_satuan'            => $itemHarga,
                    'subtotal'                => $subtotal,
                ];
            }

            // 5. Hitung Biaya & Total Pesanan
            $ongkir = max(0, (float) ($data['ongkir'] ?? $mp['ongkir']));
            $voucher = max(0, (float) ($data['voucher'] ?? $mp['voucher']));
            $biayaLayanan = max(0, (float) ($data['biaya_layanan'] ?? $mp['biaya_layanan']));

            if ($isCod) {
                $totalPassed = (float) ($data['total'] ?? $mp['total']);
                $finalTotal = $totalPassed > 0 ? $totalPassed : ($totalItemsCalculated + $ongkir + $biayaLayanan - $voucher);
            } else {
                $finalTotal = $totalItemsCalculated + $ongkir + $biayaLayanan - $voucher;
                if ($finalTotal <= 0 && !empty($data['total'])) {
                    $finalTotal = (float) $data['total'];
                }
            }
            $finalTotal = money_round(max(0, $finalTotal));

            // 6. Ganti Rincian Item di penjualan_marketplace_item
            $this->db->table('penjualan_marketplace_item')
                ->where('penjualan_marketplace_id', $marketplaceOrderId)
                ->delete();

            foreach ($processedItems as $item) {
                $this->db->table('penjualan_marketplace_item')->insert([
                    'penjualan_marketplace_id' => $marketplaceOrderId,
                    'sku'                      => $item['sku'],
                    'produk_id'                => $item['produk_id'],
                    'nama_produk_marketplace'  => $item['nama_produk_marketplace'],
                    'qty'                      => $item['qty'],
                    'harga_satuan'             => money_round($item['harga_satuan']),
                    'subtotal'                 => money_round($item['subtotal']),
                ]);
            }

            // 7. Update penjualan_marketplace header
            $this->db->table('penjualan_marketplace')
                ->where('id', $marketplaceOrderId)
                ->update([
                    'no_pesanan'      => $noPesanan,
                    'source'          => in_array($source, ['shopee', 'tiktok', 'tokopedia', 'lazada']) ? $source : 'shopee',
                    'nomor_resi'      => $nomorResi,
                    'kurir'           => $kurir,
                    'nama_penerima'   => $namaPenerima,
                    'no_telepon'      => $localPhone,
                    'alamat_penerima' => $alamat,
                    'total'           => $finalTotal,
                    'is_cod'          => $isCod ? 1 : 0,
                    'ongkir'          => money_round($ongkir),
                    'voucher'         => money_round($voucher),
                    'biaya_layanan'   => money_round($biayaLayanan),
                    'status'          => $status,
                    'tanggal_pesanan' => $orderDate . ' ' . date('H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

            // 8. Sinkronisasi Sales Order & Sales Order Items
            if (!empty($mp['so_id'])) {
                $soId = (int) $mp['so_id'];

                $this->db->table('sales_order_item')->where('so_id', $soId)->delete();
                foreach ($processedItems as $item) {
                    $this->db->table('sales_order_item')->insert([
                        'so_id'        => $soId,
                        'produk_id'    => $item['produk_id'],
                        'qty'          => $item['qty'],
                        'harga_satuan' => money_round($item['harga_satuan']),
                        'diskon'       => '0.00',
                        'subtotal'     => money_round($item['subtotal']),
                    ]);
                }

                $soStatus = in_array($status, ['completed', 'selesai'], true) ? 'completed' : ($status === 'cancelled' ? 'cancelled' : 'ready_to_ship');
                $metodeBayar = $isCod ? 'cod' : ($source . '_escrow');

                $this->db->table('sales_order')->where('id', $soId)->update([
                    'tanggal_so'   => $orderDate,
                    'source'       => $source,
                    'subtotal'     => $finalTotal,
                    'diskon_total' => money_round($voucher),
                    'ongkir'       => money_round($ongkir),
                    'total'        => $finalTotal,
                    'bayar'        => $finalTotal,
                    'metode_bayar' => $metodeBayar,
                    'status'       => $soStatus,
                    'catatan'      => "Pesanan Marketplace {$kurir} [{$nomorResi}] - Pesanan #{$noPesanan}" . ($isCod ? ' (COD)' : ' (Non-COD)'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

                // 8b. Sinkronisasi Faktur / Invoice
                $this->db->table('invoice')->where('so_id', $soId)->update([
                    'total'           => $finalTotal,
                    'tanggal_invoice' => $orderDate,
                    'jatuh_tempo'     => $orderDate,
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

                // 8c. Sinkronisasi Jurnal Akuntansi
                $so = $this->db->table('sales_order')->where('id', $soId)->get()->getRowArray();
                if ($so && !empty($so['nomor_so'])) {
                    $jurnals = $this->db->table('jurnal')->where('referensi', $so['nomor_so'])->get()->getResultArray();
                    foreach ($jurnals as $j) {
                        $this->db->table('jurnal_detail')->where('jurnal_id', $j['id'])->delete();
                    }
                    $this->db->table('jurnal')->where('referensi', $so['nomor_so'])->delete();

                    $this->jurnalService->catat(
                        $orderDate,
                        $so['nomor_so'],
                        "Penjualan Marketplace " . strtoupper($source) . " [{$nomorResi}] #{$noPesanan}",
                        [
                            ['coa_kode' => '1140', 'debit' => (string) $finalTotal, 'kredit' => '0.00', 'memo' => 'Piutang Settlement Marketplace ' . strtoupper($source)],
                            ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => (string) $finalTotal, 'memo' => 'Pendapatan Penjualan Marketplace ' . strtoupper($source)],
                        ]
                    );
                }

                // 8d. Sinkronisasi Kontak Pelanggan ERP & CRM
                $kota = trim((string) ($data['kota'] ?? ''));
                if (!empty($so['pelanggan_id'])) {
                    $custUpdate = [
                        'nama_pelanggan' => $namaPenerima,
                        'no_telp'        => $localPhone,
                        'alamat'         => $alamat,
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ];
                    if (!empty($kota)) {
                        $custUpdate['kota'] = $kota;
                    }
                    $this->db->table('pelanggan')->where('id', $so['pelanggan_id'])->update($custUpdate);
                }

                if ($this->db->tableExists('crm_transaction_followup_labels')) {
                    $crmUpdate = ['customer_nama' => $namaPenerima];
                    if (!empty($localPhone)) {
                        $cleanCrmPhone = preg_replace('/[^0-9]/', '', $localPhone);
                        if (str_starts_with($cleanCrmPhone, '0')) {
                            $cleanCrmPhone = '62' . substr($cleanCrmPhone, 1);
                        }
                        $crmUpdate['customer_phone'] = $cleanCrmPhone;
                    }
                    $this->db->table('crm_transaction_followup_labels')
                        ->where('order_id', $soId)
                        ->update($crmUpdate);
                }
            } else {
                // Pesanan marketplace belum memiliki Sales Order (misal data impor lama) -> buatkan SO baru & integrasikan CRM
                $isNewCust = false;
                $pelangganId = $this->findOrCreatePelanggan([
                    'nama_penerima' => $namaPenerima,
                    'no_telepon'    => $localPhone,
                    'alamat'        => $alamat,
                    'kota'          => $data['kota'] ?? '',
                    'no_pesanan'    => $noPesanan,
                ], $source, $orderDate, $isNewCust);

                $nomorSO = $this->nomorUrutService->generate('SO', $orderDate);
                $soStatus = in_array($status, ['completed', 'selesai'], true) ? 'completed' : ($status === 'cancelled' ? 'cancelled' : 'ready_to_ship');
                $metodeBayar = $isCod ? 'cod' : ($source . '_escrow');

                $this->db->table('sales_order')->insert([
                    'nomor_so'     => $nomorSO,
                    'tanggal_so'   => $orderDate,
                    'source'       => $source,
                    'pelanggan_id' => $pelangganId,
                    'subtotal'     => $finalTotal,
                    'diskon_total' => money_round($voucher),
                    'ppn'          => '0.00',
                    'ongkir'       => money_round($ongkir),
                    'total'        => $finalTotal,
                    'bayar'        => $finalTotal,
                    'kembalian'    => '0.00',
                    'metode_bayar' => $metodeBayar,
                    'status'       => $soStatus,
                    'catatan'      => "Pesanan Marketplace {$kurir} [{$nomorResi}] - Pesanan #{$noPesanan}" . ($isCod ? ' (COD)' : ' (Non-COD)'),
                    'user_id'      => $userId,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $soId = (int) $this->db->insertID();

                $this->db->table('penjualan_marketplace')->where('id', $marketplaceOrderId)->update(['so_id' => $soId]);

                foreach ($processedItems as $item) {
                    $this->db->table('sales_order_item')->insert([
                        'so_id'        => $soId,
                        'produk_id'    => $item['produk_id'],
                        'qty'          => $item['qty'],
                        'harga_satuan' => money_round($item['harga_satuan']),
                        'diskon'       => '0.00',
                        'subtotal'     => money_round($item['subtotal']),
                    ]);
                }

                $nomorInvoice = $this->nomorUrutService->generate('INV', $orderDate);
                $this->db->table('invoice')->insert([
                    'nomor_invoice'   => $nomorInvoice,
                    'so_id'           => $soId,
                    'pelanggan_id'    => $pelangganId,
                    'tanggal_invoice' => $orderDate,
                    'jatuh_tempo'     => $orderDate,
                    'total'           => $finalTotal,
                    'status'          => 'paid',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

                $this->jurnalService->catat(
                    $orderDate,
                    $nomorSO,
                    "Penjualan Marketplace " . strtoupper($source) . " [{$nomorResi}] #{$noPesanan}",
                    [
                        ['coa_kode' => '1140', 'debit' => (string) $finalTotal, 'kredit' => '0.00', 'memo' => 'Piutang Settlement Marketplace ' . strtoupper($source)],
                        ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => (string) $finalTotal, 'memo' => 'Pendapatan Penjualan Marketplace ' . strtoupper($source)],
                    ]
                );

                if (!empty($localPhone)) {
                    $this->scheduleCrmFollowupsWithResi($soId, $source, $namaPenerima, $localPhone, $nomorResi, $kurir, $noPesanan);

                    if ($this->db->tableExists('crm_followup_notes')) {
                        $this->db->table('crm_followup_notes')->insert([
                            'pelanggan_id'   => $pelangganId,
                            'customer_phone' => $localPhone,
                            'user_id'        => $userId,
                            'label'          => 'Follow-up',
                            'catatan'        => "Resi {$kurir} #{$nomorResi} berhasil disinkronkan ke CRM untuk pesanan {$noPesanan}. Follow-up aktif.",
                            'direction'      => 'outbound',
                            'created_at'     => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            // 9. Audit Trail Log
            $this->auditService->log('edit_pesanan_marketplace', 'penjualan_marketplace', $marketplaceOrderId, $mp, [
                'no_pesanan' => $noPesanan,
                'resi'       => $nomorResi,
                'kurir'      => $kurir,
                'total'      => $finalTotal,
                'pelanggan'  => $namaPenerima,
            ]);

            $this->db->transComplete();

            return [
                'success'    => true,
                'mp_id'      => $marketplaceOrderId,
                'no_pesanan' => $noPesanan,
                'nomor_resi' => $nomorResi,
                'total'      => $finalTotal,
            ];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Cascade delete marketplace order per SKILL 4.6:
     * Cleans items, reverses inventory mutasi via 'adj', removes invoice, CRM schedule in 1 transaction.
     */
    public function cascadeDeleteOrder(int $marketplaceOrderId): void
    {
        $this->db->transException(true)->transStart();

        try {
            $mp = $this->db->table('penjualan_marketplace')->where('id', $marketplaceOrderId)->get()->getRowArray();
            if (!$mp) {
                throw new RuntimeException('Pesanan marketplace tidak ditemukan.');
            }

            $items = $this->db->table('penjualan_marketplace_item')
                ->where('penjualan_marketplace_id', $marketplaceOrderId)
                ->get()
                ->getResultArray();

            // 1. Reverse stock mutations via 'adj' entry (never hard delete stok_log!)
            foreach ($items as $item) {
                if (!empty($item['produk_id'])) {
                    $pId = (int) $item['produk_id'];
                    $curStok = $this->stokService->getStok($pId);
                    $newSaldo = bcadd($curStok['fisik'], (string) $item['qty'], 3);
                    $this->stokService->penyesuaian(
                        $pId,
                        $newSaldo,
                        $mp['no_pesanan'],
                        "Reversal / Pembatalan Pesanan Marketplace #{$mp['no_pesanan']}"
                    );
                }
            }

            // 2. Remove CRM follow-up schedules
            if (!empty($mp['so_id'])) {
                $this->db->table('crm_transaction_followup_labels')
                    ->where('order_id', $mp['so_id'])
                    ->where('order_source', $mp['source'])
                    ->delete();

                // 2b. Remove invoice
                $this->db->table('invoice')->where('so_id', $mp['so_id'])->delete();

                // 2c. Remove journal entries
                $so = $this->db->table('sales_order')->where('id', $mp['so_id'])->get()->getRowArray();
                if ($so && !empty($so['nomor_so'])) {
                    $jurnals = $this->db->table('jurnal')->where('referensi', $so['nomor_so'])->get()->getResultArray();
                    foreach ($jurnals as $j) {
                        $this->db->table('jurnal_detail')->where('jurnal_id', $j['id'])->delete();
                    }
                    $this->db->table('jurnal')->where('referensi', $so['nomor_so'])->delete();
                }

                // 3. Remove sales_order items and sales_order
                $this->db->table('sales_order_item')->where('so_id', $mp['so_id'])->delete();
                $this->db->table('sales_order')->where('id', $mp['so_id'])->delete();
            }

            // 4. Soft delete penjualan_marketplace
            $this->db->table('penjualan_marketplace_item')->where('penjualan_marketplace_id', $marketplaceOrderId)->delete();
            $this->db->table('penjualan_marketplace')->where('id', $marketplaceOrderId)->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'status'     => 'cancelled',
            ]);

            $this->auditService->log('cascade_delete', 'penjualan_marketplace', $marketplaceOrderId, $mp, null);

            $this->db->transComplete();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Cari atau otomatis daftarkan Pelanggan baru ke Master Data (pelanggan).
     */
    public function findOrCreatePelanggan(array $orderData, string $source, string $today, bool &$isNewCustomer = false): int
    {
        $isNewCustomer = false;
        $namaPenerima = trim((string)($orderData['nama_penerima'] ?? ''));
        if (empty($namaPenerima) && !empty($orderData['buyer_username'])) {
            $namaPenerima = trim((string)$orderData['buyer_username']);
        }
        if (empty($namaPenerima) || $namaPenerima === 'Pembeli') {
            $namaPenerima = 'Pembeli ' . ucfirst($source);
        }

        $rawPhone = trim((string)($orderData['no_telepon'] ?? ''));
        $normPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (empty($normPhone)) {
            $normPhone = '08' . str_pad((string)abs(crc32($namaPenerima . ($orderData['no_pesanan'] ?? ''))) % 10000000000, 10, '0', STR_PAD_LEFT);
        }

        // Cek apakah pelanggan sudah terdaftar:
        // Prioritas 1: Cocokkan nomor telepon DAN nama yang sama (atau nama default Walk-in)
        $cust = $this->db->table('pelanggan')
            ->groupStart()
                ->where('no_telp', $normPhone)
                ->orWhere('no_telp', '62' . ltrim($normPhone, '0'))
                ->orWhere('no_telp', '0' . ltrim(preg_replace('/^62/', '', $normPhone), '0'))
            ->groupEnd()
            ->groupStart()
                ->where('LOWER(nama_pelanggan)', strtolower($namaPenerima))
                ->orWhere('nama_pelanggan', 'Pelanggan Umum (Walk-in)')
            ->groupEnd()
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        // Prioritas 2: Jika tidak cocok nama+telp (misal resi batch memakai nomor telepon template yang sama untuk banyak penerima berbeda),
        // cari pelanggan berdasarkan NAMA yang sama
        if (!$cust) {
            $cust = $this->db->table('pelanggan')
                ->where('LOWER(nama_pelanggan)', strtolower($namaPenerima))
                ->where('deleted_at IS NULL')
                ->get()
                ->getRowArray();
        }

        if ($cust) {
            $pelangganId = (int) $cust['id'];
            $updateData = [];
            if (!empty($orderData['alamat']) && (empty($cust['alamat']) || $cust['alamat'] === '-')) {
                $updateData['alamat'] = $orderData['alamat'];
            }
            if (!empty($orderData['kota']) && (empty($cust['kota']) || $cust['kota'] === '-')) {
                $updateData['kota'] = $orderData['kota'];
            }
            if ($cust['nama_pelanggan'] === 'Pelanggan Umum (Walk-in)' && $namaPenerima !== 'Pembeli ' . ucfirst($source)) {
                $updateData['nama_pelanggan'] = $namaPenerima;
            }
            if (!empty($updateData)) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('pelanggan')->where('id', $pelangganId)->update($updateData);
            }
            return $pelangganId;
        }

        // Otomatis daftarkan ke Master Data Pelanggan
        $kodePelanggan = $this->nomorUrutService->generate('CUST', $today);
        $this->db->table('pelanggan')->insert([
            'kode_pelanggan' => $kodePelanggan,
            'nama_pelanggan' => $namaPenerima,
            'no_telp'        => $normPhone,
            'email'          => null,
            'alamat'         => !empty($orderData['alamat']) ? $orderData['alamat'] : null,
            'kota'           => !empty($orderData['kota']) ? $orderData['kota'] : null,
            'tipe_pelanggan' => 'retail',
            'limit_kredit'   => '0.00',
            'tempo_hari'     => 0,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $isNewCustomer = true;
        return (int) $this->db->insertID();
    }

    /**
     * Cari atau otomatis daftarkan Produk baru ke Master Data (produk).
     */
    public function findOrCreateProduk(array $itemData, string $source, string $today, ?int $userId, string $noPesanan, bool &$isNewProduct = false): int
    {
        $isNewProduct = false;
        $sku = trim((string)($itemData['sku'] ?? ''));
        $namaProduk = trim((string)($itemData['nama'] ?? ''));
        $harga = (string)($itemData['harga'] ?? 0);
        $qty = (string)($itemData['qty'] ?? 1);

        $produk = null;
        if (!empty($sku)) {
            $produk = $this->db->table('produk')
                ->groupStart()
                    ->where('kode_produk', $sku)
                    ->orWhere('barcode', $sku)
                ->groupEnd()
                ->where('deleted_at IS NULL')
                ->get()
                ->getRowArray();
        }

        if (!$produk && !empty($namaProduk)) {
            $produk = $this->db->table('produk')
                ->where('nama_produk', $namaProduk)
                ->where('deleted_at IS NULL')
                ->get()
                ->getRowArray();
        }

        if (!$produk) {
            $kodeProduk = !empty($sku) ? $sku : $this->nomorUrutService->generate('PRD', $today);
            $namaProdukFinal = !empty($namaProduk) ? $namaProduk : (!empty($sku) ? $sku : 'Produk Marketplace ' . ucfirst($source));

            // Klasifikasi kategori otomatis
            $namaLower = strtolower($namaProdukFinal);
            $kategoriId = 5; // Default UMUM
            if (preg_match('/(kopi|coffee|gayo|robusta|arabika|sirup|syrup|cocoa|cokelat|teh|tea|susu|milk|snack|makan|minum|food|beverage)/i', $namaLower)) {
                $kategoriId = 3; // FNB
            } elseif (preg_match('/(baju|kaos|tshirt|shirt|celana|pants|dress|jaket|jacket|sepatu|shoes|sandal|tas|bag|fashion|pakaian)/i', $namaLower)) {
                $kategoriId = 2; // FASHION
            } elseif (preg_match('/(laptop|komputer|printer|mouse|keyboard|gadget|elektronik|electronic|hp|phone|ram|ssd|usb|kabel|cable)/i', $namaLower)) {
                $kategoriId = 1; // ELEKTRONIK
            } elseif (preg_match('/(skincare|sabun|soap|lotion|serum|cream|shampoo|vitamin|obat|beauty|masker|parfum)/i', $namaLower)) {
                $kategoriId = 4; // KESEHATAN
            } else {
                $katMatch = $this->db->table('kategori_produk')
                    ->groupStart()
                        ->like('nama_kategori', 'Umum')
                        ->orLike('kode_kategori', 'UMUM')
                    ->groupEnd()
                    ->get()
                    ->getRowArray();
                if ($katMatch) {
                    $kategoriId = (int) $katMatch['id'];
                } else {
                    $firstKat = $this->db->table('kategori_produk')->orderBy('id', 'ASC')->get()->getRowArray();
                    if ($firstKat) $kategoriId = (int) $firstKat['id'];
                }
            }

            // Satuan default
            $satuanId = 1; // PCS
            if (preg_match('/\b(box|dus)\b/i', $namaLower)) {
                $satuanId = 2; // BOX
            } elseif (preg_match('/\b(pack|bungkus|isi)\b/i', $namaLower)) {
                $satuanId = 3; // PACK
            } elseif (preg_match('/\b(kg|kilogram)\b/i', $namaLower)) {
                $satuanId = 5; // KG
            }

            // Estimasi harga beli / HPP (default 70% dari harga jual atau 0)
            $hargaBeli = bccomp($harga, '0', 2) > 0 ? bcmul($harga, '0.70', 2) : '0.00';

            $newProdukData = [
                'kode_produk'       => $kodeProduk,
                'barcode'           => !empty($sku) ? $sku : null,
                'nama_produk'       => $namaProdukFinal,
                'kategori_id'       => $kategoriId,
                'satuan_id'         => $satuanId,
                'harga_beli'        => $hargaBeli,
                'harga_jual'        => $harga,
                'harga_grosir'      => $harga,
                'harga_distributor' => $harga,
                'stok_minimum'      => 5,
                'status'            => 'active',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
            $this->db->table('produk')->insert($newProdukData);
            $produkId = (int) $this->db->insertID();
            $isNewProduct = true;
        } else {
            $produkId = (int) $produk['id'];
        }

        // Pastikan stok fisik tersedia di tabel stok agar sinkronisasi aman
        $stokRow = $this->db->table('stok')->where('produk_id', $produkId)->get()->getRow();
        if (!$stokRow) {
            $initialStock = bcadd((string)$qty, '50.000', 3);
            $this->db->table('stok')->insert([
                'produk_id'    => $produkId,
                'qty'          => $initialStock,
                'reserved_qty' => '0.000',
                'lokasi_rak'   => 'RAK-UTAMA',
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
            $this->db->table('stok_log')->insert([
                'produk_id'         => $produkId,
                'tipe'              => 'in',
                'qty'               => $initialStock,
                'saldo_akhir'       => $initialStock,
                'referensi_dokumen' => "INIT-{$source}",
                'operator_id'       => $userId,
                'keterangan'        => "Stok Awal Produk Baru dari Marketplace ({$sku})",
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
        } else if (bccomp((string)$stokRow->qty, (string)$qty, 3) < 0) {
            $tambahStok = bcadd(bcsub((string)$qty, (string)$stokRow->qty, 3), '20.000', 3);
            $this->stokService->masuk(
                $produkId,
                $tambahStok,
                "ADJ-{$source}-" . date('Ymd'),
                "Penyesuaian Stok Masuk Impor Pesanan #{$noPesanan}",
                $userId
            );
        }

        return $produkId;
    }

    /**
     * Sinkronisasi retroaktif data marketplace lama ke Master Data (produk & pelanggan).
     */
    public function syncExistingUnmappedData(): array
    {
        $today = date('Y-m-d');
        $syncedProducts = 0;
        $syncedCustomers = 0;

        // 1. Sinkronisasi item produk yang belum terhubung ke master data
        $unmappedItems = $this->db->table('penjualan_marketplace_item pmi')
            ->select('pmi.*, pm.no_pesanan, pm.source, pm.so_id')
            ->join('penjualan_marketplace pm', 'pm.id = pmi.penjualan_marketplace_id', 'inner')
            ->where('pmi.produk_id IS NULL')
            ->get()
            ->getResultArray();

        foreach ($unmappedItems as $item) {
            $isNewProd = false;
            $pId = $this->findOrCreateProduk([
                'sku'   => $item['sku'],
                'nama'  => $item['nama_produk_marketplace'],
                'harga' => $item['harga_satuan'],
                'qty'   => $item['qty'],
            ], $item['source'] ?? 'marketplace', $today, null, $item['no_pesanan'] ?? '-', $isNewProd);

            if ($pId > 0) {
                $this->db->table('penjualan_marketplace_item')
                    ->where('id', $item['id'])
                    ->update(['produk_id' => $pId]);

                if (!empty($item['so_id'])) {
                    $existsSoi = $this->db->table('sales_order_item')
                        ->where('so_id', $item['so_id'])
                        ->where('produk_id', $pId)
                        ->get()
                        ->getRowArray();
                    if (!$existsSoi) {
                        $this->db->table('sales_order_item')->insert([
                            'so_id'        => $item['so_id'],
                            'produk_id'    => $pId,
                            'qty'          => $item['qty'],
                            'harga_satuan' => $item['harga_satuan'],
                            'diskon'       => '0.00',
                            'subtotal'     => $item['subtotal'],
                        ]);
                    }
                }
                $syncedProducts++;
            }
        }

        // 2. Sinkronisasi data pelanggan dari pesanan yang masih bertaut ke Pelanggan Walk-In ID 1 atau belum memiliki Sales Order
        $unmappedOrders = $this->db->table('penjualan_marketplace pm')
            ->select('pm.*, so.pelanggan_id as so_pelanggan_id')
            ->join('sales_order so', 'so.id = pm.so_id', 'left')
            ->where('pm.deleted_at IS NULL')
            ->groupStart()
                ->where('pm.so_id IS NULL')
                ->orWhere('pm.so_id', 0)
                ->orWhere('so.pelanggan_id', 1)
                ->orWhere('so.pelanggan_id IS NULL')
            ->groupEnd()
            ->where('pm.nama_penerima IS NOT NULL')
            ->where('pm.nama_penerima !=', '')
            ->where('pm.nama_penerima !=', 'Pembeli Marketplace')
            ->where('pm.nama_penerima !=', 'Pembeli')
            ->get()
            ->getResultArray();

        foreach ($unmappedOrders as $ord) {
            $isNewCust = false;
            $custId = $this->findOrCreatePelanggan([
                'nama_penerima'  => $ord['nama_penerima'],
                'no_telepon'     => $ord['no_telepon'],
                'alamat'         => $ord['alamat_penerima'],
                'no_pesanan'     => $ord['no_pesanan'],
            ], $ord['source'] ?? 'marketplace', $today, $isNewCust);

            if ($custId > 0 && $custId !== 1) {
                if (!empty($ord['so_id'])) {
                    $this->db->table('sales_order')->where('id', $ord['so_id'])->update(['pelanggan_id' => $custId]);
                    $this->db->table('invoice')->where('so_id', $ord['so_id'])->update(['pelanggan_id' => $custId]);
                    $syncedCustomers++;
                } else {
                    // Pesanan belum memiliki sales_order sama sekali (misal impor legacy) -> generate SO, items, invoice & CRM
                    $orderDate = !empty($ord['tanggal_pesanan']) ? date('Y-m-d', strtotime($ord['tanggal_pesanan'])) : $today;
                    $nomorSO = $this->nomorUrutService->generate('SO', $orderDate);
                    $soStatus = in_array($ord['status'], ['completed', 'selesai'], true) ? 'completed' : ($ord['status'] === 'cancelled' ? 'cancelled' : 'ready_to_ship');
                    $metodeBayar = !empty($ord['is_cod']) ? 'cod' : (($ord['source'] ?? 'marketplace') . '_escrow');

                    $this->db->table('sales_order')->insert([
                        'nomor_so'     => $nomorSO,
                        'tanggal_so'   => $orderDate,
                        'source'       => $ord['source'] ?? 'marketplace',
                        'pelanggan_id' => $custId,
                        'subtotal'     => $ord['total'],
                        'diskon_total' => money_round($ord['voucher'] ?? 0),
                        'ppn'          => '0.00',
                        'ongkir'       => money_round($ord['ongkir'] ?? 0),
                        'total'        => $ord['total'],
                        'bayar'        => $ord['total'],
                        'kembalian'    => '0.00',
                        'metode_bayar' => $metodeBayar,
                        'status'       => $soStatus,
                        'catatan'      => "Pesanan Marketplace {$ord['kurir']} [{$ord['nomor_resi']}] - Pesanan #{$ord['no_pesanan']}",
                        'created_at'   => $ord['created_at'] ?? date('Y-m-d H:i:s'),
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ]);
                    $newSoId = (int) $this->db->insertID();

                    $this->db->table('penjualan_marketplace')->where('id', $ord['id'])->update(['so_id' => $newSoId]);

                    // sales_order_item
                    $mpItems = $this->db->table('penjualan_marketplace_item')->where('penjualan_marketplace_id', $ord['id'])->get()->getResultArray();
                    foreach ($mpItems as $mpi) {
                        $this->db->table('sales_order_item')->insert([
                            'so_id'        => $newSoId,
                            'produk_id'    => $mpi['produk_id'] ?: 1,
                            'qty'          => $mpi['qty'],
                            'harga_satuan' => $mpi['harga_satuan'],
                            'diskon'       => '0.00',
                            'subtotal'     => $mpi['subtotal'],
                        ]);
                    }

                    // invoice
                    $nomorInvoice = $this->nomorUrutService->generate('INV', $orderDate);
                    $this->db->table('invoice')->insert([
                        'nomor_invoice'   => $nomorInvoice,
                        'so_id'           => $newSoId,
                        'pelanggan_id'    => $custId,
                        'tanggal_invoice' => $orderDate,
                        'jatuh_tempo'     => $orderDate,
                        'total'           => $ord['total'],
                        'status'          => 'paid',
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);

                    // Sinkronisasi CRM Followup
                    $rawPhone = trim((string)($ord['no_telepon'] ?? ''));
                    if (!empty($rawPhone)) {
                        $this->scheduleCrmFollowupsWithResi($newSoId, $ord['source'] ?? 'marketplace', $ord['nama_penerima'], $rawPhone, $ord['nomor_resi'] ?? '-', $ord['kurir'] ?? 'Ekspedisi', $ord['no_pesanan']);

                        if ($this->db->tableExists('crm_followup_notes')) {
                            $this->db->table('crm_followup_notes')->insert([
                                'pelanggan_id'   => $custId,
                                'customer_phone' => $rawPhone,
                                'user_id'        => 1,
                                'label'          => 'Follow-up',
                                'catatan'        => "Sinkronisasi pesanan marketplace #{$ord['no_pesanan']} ({$ord['source']}) ke CRM. Follow-up H+0, H+3, H+7 aktif.",
                                'direction'      => 'outbound',
                                'created_at'     => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                    $syncedCustomers++;
                }
            }
        }

        return [
            'synced_products'  => $syncedProducts,
            'synced_customers' => $syncedCustomers,
        ];
    }
}
