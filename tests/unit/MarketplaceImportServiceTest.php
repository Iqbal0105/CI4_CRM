<?php

declare(strict_types=1);

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Modules\PenjualanMarketplace\Services\MarketplaceImportService;
use Modules\Stok\Services\StokService;

final class MarketplaceImportServiceTest extends CIUnitTestCase
{
    private MarketplaceImportService $importService;
    private StokService $stokService;
    protected $db;
    private int $testProdukId;
    private string $testSku;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->stokService = new StokService($this->db);
        $this->importService = new MarketplaceImportService($this->db, null, $this->stokService);

        // Create test product
        $kategori = $this->db->table('kategori_produk')->get()->getRow();
        $kategoriId = $kategori ? $kategori->id : 1;
        $satuan = $this->db->table('satuan')->get()->getRow();
        $satuanId = $satuan ? $satuan->id : 1;

        $this->testSku = 'MP-SKU-' . time() . '-' . mt_rand(100, 999);
        $this->db->table('produk')->insert([
            'kode_produk'     => $this->testSku,
            'barcode'         => 'MPBC' . mt_rand(100000, 999999),
            'nama_produk'     => 'Marketplace Product ' . $this->testSku,
            'kategori_id'     => $kategoriId,
            'satuan_id'       => $satuanId,
            'harga_beli'      => '50000.00',
            'harga_jual'      => '85000.00',
            'stok_minimum'    => 5,
            'status'          => 'active',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $this->testProdukId = (int) $this->db->insertID();

        // Seed 30 units of stock
        $this->stokService->masuk($this->testProdukId, 30.0, 'SEED-MP-001', 'Initial stock for MP import test');
    }

    public function testMarketplaceImportAndIdempotencyAndCascadeDelete(): void
    {
        $orderNumber = 'SHOPEE-' . time() . '-' . mt_rand(1000, 9999);
        $testPhone = '0812' . mt_rand(10000000, 99999999);
        $cleanPhone = '62' . substr($testPhone, 1);
        $orders = [
            [
                'no_pesanan'    => $orderNumber,
                'resi'          => 'SPX9988776655',
                'kurir'         => 'SPX Express Standard',
                'nama_penerima' => 'Dewi Sartika',
                'no_telepon'    => $testPhone,
                'alamat'        => 'Jl. Pahlawan No. 45',
                'total'         => 170000.00,
                'ongkir'        => 10000.00,
                'voucher'       => 0.00,
                'tanggal'       => date('Y-m-d H:i:s'),
                'items'         => [
                    [
                        'sku'      => $this->testSku,
                        'nama'     => 'Marketplace Product ' . $this->testSku,
                        'qty'      => 2,
                        'harga'    => 85000.00,
                        'subtotal' => 170000.00,
                    ],
                ],
            ],
        ];

        // 1. First import
        $res1 = $this->importService->importOrders('shopee', $orders);
        $this->assertSame(1, $res1['imported']);
        $this->assertSame(0, $res1['skipped']);

        // Check stock reduced from 30 to 28
        $stok1 = $this->stokService->getStok($this->testProdukId);
        $this->assertEquals('28.000', $stok1['fisik']);

        // Verify CRM Follow-up scheduled (H+0, H+3, H+7)
        $crmRows = $this->db->table('crm_transaction_followup_labels')
            ->where('customer_phone', $cleanPhone)
            ->get()
            ->getResultArray();
        $this->assertCount(3, $crmRows);

        // 2. Second import with identical order -> must be skipped idempotently!
        $res2 = $this->importService->importOrders('shopee', $orders);
        $this->assertSame(0, $res2['imported']);
        $this->assertSame(1, $res2['skipped']);

        // Stock must remain unchanged at 28
        $stok2 = $this->stokService->getStok($this->testProdukId);
        $this->assertEquals('28.000', $stok2['fisik']);

        // 3. Cascade Delete Order
        $mpOrder = $this->db->table('penjualan_marketplace')
            ->where('source', 'shopee')
            ->where('no_pesanan', $orderNumber)
            ->get()
            ->getRowArray();
        $this->assertNotNull($mpOrder);

        $this->importService->cascadeDeleteOrder((int) $mpOrder['id']);

        // Stock reversed back to 30 via 'adj'
        $stok3 = $this->stokService->getStok($this->testProdukId);
        $this->assertEquals('30.000', $stok3['fisik']);

        $adjLog = $this->db->table('stok_log')
            ->where('produk_id', $this->testProdukId)
            ->where('tipe', 'adj')
            ->where('referensi_dokumen', $orderNumber)
            ->get()
            ->getRowArray();
        $this->assertNotNull($adjLog);

        // CRM follow-ups cleaned up
        $crmRemaining = $this->db->table('crm_transaction_followup_labels')
            ->where('order_id', $mpOrder['so_id'])
            ->get()
            ->getResultArray();
        $this->assertCount(0, $crmRemaining);

        // MP order soft deleted / cancelled
        $mpDeleted = $this->db->table('penjualan_marketplace')
            ->where('id', $mpOrder['id'])
            ->get()
            ->getRowArray();
        $this->assertSame('cancelled', $mpDeleted['status']);
        $this->assertNotNull($mpDeleted['deleted_at']);
    }

    public function testSaveScrapedResiOrderIntegratesErpCrmRfm(): void
    {
        $uniquePhone = '0813' . mt_rand(10000000, 99999999);
        $uniqueResi = 'SPXID' . mt_rand(1000000000, 9999999999);
        $orderNo = '241030RESI' . mt_rand(1000, 9999);

        $resiData = [
            'nomor_resi'      => $uniqueResi,
            'kurir'           => 'SPX Express',
            'source'          => 'shopee',
            'no_pesanan'      => $orderNo,
            'nama_penerima'   => 'Bambang Sudarsono',
            'no_telepon'      => $uniquePhone,
            'alamat_penerima' => 'Jl. Malioboro No. 10, Yogyakarta',
            'kota'            => 'Yogyakarta',
            'sku'             => $this->testSku,
            'qty'             => 3,
            'total'           => 255000.00,
            'tanggal'         => date('Y-m-d'),
        ];

        $res = $this->importService->saveScrapedResiOrder($resiData, 1);

        $this->assertTrue($res['success']);
        $this->assertGreaterThan(0, $res['so_id']);
        $this->assertGreaterThan(0, $res['pelanggan_id']);
        $this->assertSame('Bambang Sudarsono', $res['nama_pelanggan']);
        $this->assertTrue($res['stock_mutated']);
        $this->assertSame(3, $res['crm_schedules']);
        $this->assertNotEmpty($res['rfm_segment']);

        // 1. Verify ERP: Pelanggan
        $cust = $this->db->table('pelanggan')->where('id', $res['pelanggan_id'])->get()->getRowArray();
        $this->assertNotNull($cust);
        $this->assertSame('Bambang Sudarsono', $cust['nama_pelanggan']);
        $this->assertSame($uniquePhone, $cust['no_telp']);

        // 2. Verify ERP: Sales Order & Marketplace
        $so = $this->db->table('sales_order')->where('id', $res['so_id'])->get()->getRowArray();
        $this->assertNotNull($so);
        $this->assertSame($res['pelanggan_id'], (int) $so['pelanggan_id']);
        $this->assertEquals('255000.00', $so['total']);

        $mp = $this->db->table('penjualan_marketplace')->where('id', $res['mp_id'])->get()->getRowArray();
        $this->assertNotNull($mp);
        $this->assertSame($uniqueResi, $mp['nomor_resi']);
        $this->assertSame('SPX Express', $mp['kurir']);

        // 3. Verify Stock deduction: 30 - 3 = 27
        $stok = $this->stokService->getStok($this->testProdukId);
        $this->assertEquals('27.000', $stok['fisik']);

        // 4. Verify CRM: 3 WhatsApp Follow-ups
        $crmRows = $this->db->table('crm_transaction_followup_labels')
            ->where('order_id', $res['so_id'])
            ->orderBy('label_code', 'ASC')
            ->get()
            ->getResultArray();
        $this->assertCount(3, $crmRows);
        $this->assertStringContainsString($uniqueResi, $crmRows[0]['message_log']);

        // 5. Verify CRM Chat Note created in crm_followup_notes
        $chat = $this->db->table('crm_followup_notes')
            ->where('pelanggan_id', $res['pelanggan_id'])
            ->get()
            ->getRowArray();
        $this->assertNotNull($chat);
        $this->assertStringContainsString($uniqueResi, $chat['catatan']);
    }
}

