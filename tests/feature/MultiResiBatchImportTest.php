<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;
use Modules\PenjualanMarketplace\Services\ResiParserService;

class MultiResiBatchImportTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = Database::connect();
    }

    public function testBatchParsingMultipleLabelsFromRawPdfString()
    {
        $parser = new ResiParserService();

        $multiPageText = "--- PAGE 1 ---
Shopee STD SPX Express
No. Resi: ID25838628439GK
Penerima: Ujang Sound Pengirim: Elen Purnama
Pengkolan Opak, RT 01 RW 01
KAB. CIREBON   KALIWEDI
No. Pesanan: 2505220HM1MBOGK
6282180944685

--- PAGE 2 ---
J&T EXPRESS
No. Resi: JP9876543210
Penerima: Siti Aminah
Jl. Melati No. 12, RT.02/RW.05
Kota Bandung, Jawa Barat
081298765432
Order ID: JT-241030-9988

--- PAGE 3 ---
SICEPAT EKSPRES
AWB: 002938475612
Penerima: Budi Santoso
Jl. Sudirman No. 45
Kota Surabaya, Jawa Timur
085712345678
No. Pesanan: SCP-99112233
Total: 150000";

        $batch = $parser->parseBatch($multiPageText);

        $this->assertTrue($batch['is_batch']);
        $this->assertSame(3, $batch['count']);
        $this->assertCount(3, $batch['items']);

        // Item 1: SPX
        $this->assertSame('SPX Express', $batch['items'][0]['courier']);
        $this->assertSame('ID25838628439GK', $batch['items'][0]['tracking_number']);
        $this->assertSame('Ujang Sound', $batch['items'][0]['recipient_name']);
        $this->assertSame('Cirebon', $batch['items'][0]['city']);

        // Item 2: J&T
        $this->assertSame('J&T Express', $batch['items'][1]['courier']);
        $this->assertSame('JP9876543210', $batch['items'][1]['tracking_number']);
        $this->assertSame('Siti Aminah', $batch['items'][1]['recipient_name']);
        $this->assertSame('Bandung', $batch['items'][1]['city']);

        // Item 3: SiCepat
        $this->assertSame('SiCepat', $batch['items'][2]['courier']);
        $this->assertSame('002938475612', $batch['items'][2]['tracking_number']);
        $this->assertSame('Budi Santoso', $batch['items'][2]['recipient_name']);
        $this->assertSame('Surabaya', $batch['items'][2]['city']);
    }

    private function getSession(): array
    {
        return [
            'is_logged_in' => true,
            'user'         => [
                'id'        => 1,
                'username'  => 'superadmin',
                'nama'      => 'Super Administrator',
                'role_id'   => 1,
                'role_name' => 'superadmin',
            ],
        ];
    }

    private function postWithCsrf(string $url, array $data = [])
    {
        $security = config('Security');
        $hash = csrf_hash();
        $_COOKIE[$security->cookieName] = $hash;
        $data[$security->tokenName] = $hash;

        return $this->withSession($this->getSession())
            ->withHeaders([
                $security->headerName => $hash,
                'X-Requested-With'    => 'XMLHttpRequest',
            ])
            ->post($url, $data);
    }

    public function testSaveBatchResiEndpointIntegratesToDatabaseErpCrmRfm()
    {
        $uniqueSuffix = time() . rand(100, 999);

        // Prepare a test product in DB
        $testSku = 'BATCH-SKU-' . $uniqueSuffix;
        $this->db->table('produk')->insert([
            'kode_produk'  => $testSku,
            'nama_produk'  => 'Batch Test Item ' . $uniqueSuffix,
            'harga_beli'   => 40000,
            'harga_jual'   => 75000,
            'stok_minimum' => 5,
            'status'       => 'Active',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
        $prodId = $this->db->insertID();

        // Give initial stock via StokService
        $stokService = new \Modules\Stok\Services\StokService($this->db);
        $stokService->masuk($prodId, 100.0, 'REF-BATCH-INIT', 'Initial batch test stock');

        $resi1 = 'ID-TEST-' . $uniqueSuffix . '-1';
        $resi2 = 'JT-TEST-' . $uniqueSuffix . '-2';

        $batchItems = [
            [
                'no_pesanan'        => 'ORD-BATCH-' . $uniqueSuffix . '-1',
                'nomor_resi'        => $resi1,
                'courier'           => 'SPX Express',
                'source'            => 'shopee',
                'recipient_name'    => 'Penerima Batch Satu',
                'recipient_phone'   => '0812340001' . rand(10, 99),
                'recipient_address' => 'Jl. Pengujian No. 1, Kota Cirebon',
                'city'              => 'Cirebon',
                'sku'               => $testSku,
                'qty'               => 1,
                'total'             => 75000,
                'tanggal'           => date('Y-m-d'),
            ],
            [
                'no_pesanan'        => 'ORD-BATCH-' . $uniqueSuffix . '-2',
                'nomor_resi'        => $resi2,
                'courier'           => 'J&T Express',
                'source'            => 'tiktok',
                'recipient_name'    => 'Penerima Batch Dua',
                'recipient_phone'   => '0812340002' . rand(10, 99),
                'recipient_address' => 'Jl. Pengujian No. 2, Kota Bandung',
                'city'              => 'Bandung',
                'sku'               => $testSku,
                'qty'               => 2,
                'total'             => 150000,
                'tanggal'           => date('Y-m-d'),
            ],
        ];

        // Call the save-batch-resi endpoint
        $result = $this->postWithCsrf('marketplace/save-batch-resi', [
            'items' => json_encode($batchItems),
        ]);

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);

        $this->assertSame('success', $json['status']);
        $this->assertSame(2, $json['success_count']);

        // Verify penjualan_marketplace records exist
        $saved1 = $this->db->table('penjualan_marketplace')->where('nomor_resi', $resi1)->get()->getRowArray();
        $saved2 = $this->db->table('penjualan_marketplace')->where('nomor_resi', $resi2)->get()->getRowArray();

        $this->assertNotNull($saved1, 'Order 1 should be saved in penjualan_marketplace');
        $this->assertNotNull($saved2, 'Order 2 should be saved in penjualan_marketplace');
        $this->assertSame('Penerima Batch Satu', $saved1['nama_penerima']);
        $this->assertSame('Penerima Batch Dua', $saved2['nama_penerima']);

        // Verify Sales Orders in ERP exist
        $this->assertNotEmpty($saved1['so_id']);
        $this->assertNotEmpty($saved2['so_id']);
        $so1 = $this->db->table('sales_order')->where('id', $saved1['so_id'])->get()->getRowArray();
        $this->assertNotNull($so1);

        // Verify CRM follow-up records exist
        $this->assertNotEmpty($so1['pelanggan_id']);
        $notes1 = $this->db->table('crm_followup_notes')->where('pelanggan_id', $so1['pelanggan_id'])->get()->getResultArray();
        $this->assertNotEmpty($notes1, 'Should have CRM follow-up note');

        // Verify Invoice record exists in ERP
        $inv1 = $this->db->table('invoice')->where('so_id', $so1['id'])->get()->getRowArray();
        $this->assertNotNull($inv1, 'Should have official invoice created in invoice table');
        $this->assertSame('paid', $inv1['status']);

        // Verify Journal Entry exists in Buku Jurnal Umum (Double-Entry)
        $jurnal1 = $this->db->table('jurnal')->where('referensi', $so1['nomor_so'])->get()->getRowArray();
        $this->assertNotNull($jurnal1, 'Should have journal entry in Buku Jurnal Umum');
        $jurnalDetails = $this->db->table('jurnal_detail')->where('jurnal_id', $jurnal1['id'])->get()->getResultArray();
        $this->assertCount(2, $jurnalDetails, 'Journal entry must have 2 lines (debit & credit)');

        // Test Delete Marketplace Order
        $delRes = $this->withSession([
            'is_logged_in' => true,
            'user'         => ['id' => 1, 'username' => 'superadmin', 'role_id' => 1],
        ])->get('marketplace/delete/' . $saved1['id']);
        $delRes->assertStatus(302);

        $deletedOrder = $this->db->table('penjualan_marketplace')->where('id', $saved1['id'])->get()->getRowArray();
        $this->assertNotNull($deletedOrder['deleted_at'], 'Pesanan marketplace harus ditandai deleted_at (Soft Delete)');
        $this->assertSame('cancelled', $deletedOrder['status']);
    }
}
