<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

final class MasterDataCrudTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
    }

    private function getSession(): array
    {
        return [
            'is_logged_in' => true,
            'user'         => [
                'id'          => 1,
                'username'    => 'superadmin',
                'nama'        => 'Super Administrator',
                'role_id'     => 1,
                'role_name'   => 'superadmin',
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
            ->withHeaders([$security->headerName => $hash])
            ->post($url, $data);
    }

    public function testStoreKursValasSavesToDatabase(): void
    {
        $payload = [
            'mata_uang'  => 'USD',
            'nilai_kurs' => '16250.00',
            'tanggal'    => date('Y-m-d'),
        ];

        $result = $this->postWithCsrf('master/kurs/store', $payload);
        $result->assertRedirectTo('master/supplier');

        $db = Database::connect();
        $saved = $db->table('kurs')
            ->where('mata_uang', 'USD')
            ->where('tanggal', $payload['tanggal'])
            ->get()
            ->getRowArray();

        $this->assertNotNull($saved);
        $this->assertEquals(16250.00, (float)$saved['nilai_kurs']);
    }

    public function testStoreSupplierSavesToDatabase(): void
    {
        $sku = 'SUP-TEST-' . mt_rand(1000, 9999);
        $payload = [
            'kode_supplier' => $sku,
            'nama_supplier' => 'PT Test Supplier Indonesia',
            'no_telp'       => '081299887766',
            'email'         => 'supplier.test@metafora.local',
            'alamat'        => 'Kawasan Industri Cikarang Blok B2',
            'mata_uang'     => 'IDR',
        ];

        $result = $this->postWithCsrf('master/supplier/store', $payload);
        $result->assertRedirectTo('master/supplier');

        $db = Database::connect();
        $saved = $db->table('supplier')
            ->where('kode_supplier', $sku)
            ->get()
            ->getRowArray();

        $this->assertNotNull($saved);
        $this->assertSame('PT Test Supplier Indonesia', $saved['nama_supplier']);
        $this->assertSame('081299887766', $saved['no_telp']);
    }

    public function testStoreKategoriSavesToDatabase(): void
    {
        $kode = 'KAT-TEST-' . mt_rand(100, 999);
        $payload = [
            'kode_kategori' => $kode,
            'nama_kategori' => 'Kategori Pengujian Otomatis',
        ];

        $result = $this->postWithCsrf('master/kategori/store', $payload);
        $result->assertRedirectTo('master/kategori');

        $db = Database::connect();
        $saved = $db->table('kategori_produk')
            ->where('kode_kategori', $kode)
            ->get()
            ->getRowArray();

        $this->assertNotNull($saved);
        $this->assertSame('Kategori Pengujian Otomatis', $saved['nama_kategori']);
    }

    public function testStoreSatuanSavesToDatabase(): void
    {
        $kode = 'STN-' . strtoupper(bin2hex(random_bytes(4)));
        $payload = [
            'kode_satuan' => $kode,
            'nama_satuan' => 'Unit Satuan Otomatis',
        ];

        $result = $this->postWithCsrf('master/satuan/store', $payload);
        $result->assertRedirectTo('master/satuan');

        $db = Database::connect();
        $saved = $db->table('satuan')
            ->where('kode_satuan', $kode)
            ->get()
            ->getRowArray();

        $this->assertNotNull($saved);
        $this->assertSame('Unit Satuan Otomatis', $saved['nama_satuan']);
    }

    public function testSyncKursApiAndGetLatest(): void
    {
        $result = $this->postWithCsrf('master/kurs/sync-api', []);
        $result->assertRedirectTo('master/supplier');

        $db = Database::connect();
        $savedKurs = $db->table('kurs')
            ->whereIn('mata_uang', ['USD', 'SGD', 'CNY', 'EUR'])
            ->get()
            ->getResultArray();

        $this->assertNotEmpty($savedKurs, 'Kurs table must contain exchange rates pulled from API.');

        $ajaxResult = $this->withSession($this->getSession())->get('master/kurs/latest');
        $ajaxResult->assertStatus(200);
        $ajaxData = json_decode($ajaxResult->getJSON(), true);
        $this->assertSame('success', $ajaxData['status']);
        $this->assertArrayHasKey('USD', $ajaxData['rates']);
        $this->assertGreaterThan(0, (float)$ajaxData['rates']['USD']['nilai_kurs']);
    }

    public function testPurchaseOrderForeignCurrencyConversion(): void
    {
        $db = Database::connect();

        // 1. Ensure supplier with USD exists
        $supplierSku = 'SUP-USD-' . mt_rand(1000, 9999);
        $db->table('supplier')->insert([
            'kode_supplier' => $supplierSku,
            'nama_supplier' => 'Global Machinery Corp (USA)',
            'no_telp'       => '+1-555-0199',
            'mata_uang'     => 'USD',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $supplierId = (int)$db->insertID();

        // 2. Ensure product exists
        $productSku = 'PRD-IMP-' . mt_rand(1000, 9999);
        $db->table('produk')->insert([
            'kode_produk'  => $productSku,
            'nama_produk'  => 'Imported Precision Sensor',
            'harga_beli'   => '150000.00',
            'harga_jual'   => '350000.00',
            'stok_minimum' => 5,
            'status'       => 'active',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        $productId = (int)$db->insertID();

        // 3. Create PO with USD
        $rate = '18132.70';
        $qty = '10.000';
        $hargaSatuan = '25.50'; // $25.50
        $expectedValas = '255.00'; // 10 * 25.50
        $expectedIdr = bcmul($expectedValas, $rate, 2); // 4623838.50

        $payload = [
            'supplier_id'  => (string)$supplierId,
            'tanggal_po'   => date('Y-m-d'),
            'termin'       => 'tempo',
            'tempo_hari'   => '30',
            'mata_uang'    => 'USD',
            'nilai_kurs'   => $rate,
            'produk_id'    => [(string)$productId],
            'qty'          => [$qty],
            'harga_satuan' => [$hargaSatuan],
            'diskon'       => '0',
            'pajak'        => '0',
            'catatan'      => 'Test PO Foreign Currency Conversion',
        ];

        $res = $this->postWithCsrf('pembelian/store', $payload);
        $res->assertStatus(302);

        $po = $db->table('purchase_order')
            ->where('supplier_id', $supplierId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        $this->assertNotNull($po);
        $this->assertSame('USD', $po['mata_uang']);
        $this->assertEquals((float)$rate, (float)$po['nilai_kurs']);
        $this->assertEquals((float)$expectedValas, (float)$po['total']);
        $this->assertEquals((float)$expectedIdr, (float)$po['total_idr']);
    }

    public function testProdukCrudFullLifecycle(): void
    {
        $db = Database::connect();
        $code = 'PRD-TEST-' . mt_rand(10000, 99999);

        // 1. TAMBAH (CREATE)
        $createPayload = [
            'kode_produk'  => $code,
            'nama_produk'  => 'Produk Uji Tambah Edit Hapus',
            'harga_beli'   => '45000',
            'harga_jual'   => '85000',
            'stok_minimum' => '10',
            'status'       => 'active',
        ];

        $resCreate = $this->postWithCsrf('master/produk/store', $createPayload);
        $resCreate->assertStatus(302);
        $resCreate->assertRedirectTo('master/produk');

        $saved = $db->table('produk')->where('kode_produk', $code)->get()->getRowArray();
        $this->assertNotNull($saved, 'Produk harus berhasil tersimpan di DB');
        $this->assertSame('Produk Uji Tambah Edit Hapus', $saved['nama_produk']);
        $productId = (int) $saved['id'];

        // Cek record stok awal terbuat otomatis
        $stok = $db->table('stok')->where('produk_id', $productId)->get()->getRowArray();
        $this->assertNotNull($stok, 'Record stok awal produk harus terinisialisasi otomatis');

        // 2. EDIT (UPDATE)
        $updatePayload = [
            'kode_produk'  => $code,
            'nama_produk'  => 'Produk Uji TELAH DIUBAH',
            'harga_beli'   => '50000',
            'harga_jual'   => '99000',
            'stok_minimum' => '15',
            'status'       => 'active',
        ];

        $resUpdate = $this->postWithCsrf('master/produk/update/' . $productId, $updatePayload);
        $resUpdate->assertStatus(302);
        $resUpdate->assertRedirectTo('master/produk');

        $updated = $db->table('produk')->where('id', $productId)->get()->getRowArray();
        $this->assertSame('Produk Uji TELAH DIUBAH', $updated['nama_produk']);
        $this->assertEquals(99000.00, (float) $updated['harga_jual']);
        $this->assertSame(15, (int) $updated['stok_minimum']);

        // 3. HAPUS (DELETE via GET)
        $resDelete = $this->withSession($this->getSession())->get('master/produk/delete/' . $productId);
        $resDelete->assertStatus(302);
        $resDelete->assertRedirectTo('master/produk');

        $deleted = $db->table('produk')->where('id', $productId)->get()->getRowArray();
        $this->assertNotNull($deleted['deleted_at'], 'Produk harus memiliki flag deleted_at setelah dihapus (Soft Delete)');
    }

    public function testPelangganCrudFullLifecycle(): void
    {
        $db = Database::connect();
        $kode = 'CUST-TEST-' . mt_rand(10000, 99999);

        // 1. TAMBAH (CREATE)
        $createPayload = [
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => 'Toko Mitra Berkah Test',
            'no_telp'        => '081234567890',
            'email'          => 'mitra@berkah.local',
            'alamat'         => 'Jl. Merdeka No. 45',
            'kota'           => 'Surabaya',
            'tipe_pelanggan' => 'grosir',
            'limit_kredit'   => '5000000',
            'tempo_hari'     => '14',
        ];

        $resCreate = $this->postWithCsrf('master/pelanggan/store', $createPayload);
        $resCreate->assertStatus(302);
        $resCreate->assertRedirectTo('master/pelanggan');

        $saved = $db->table('pelanggan')->where('kode_pelanggan', $kode)->get()->getRowArray();
        $this->assertNotNull($saved);
        $this->assertSame('Toko Mitra Berkah Test', $saved['nama_pelanggan']);
        $customerId = (int) $saved['id'];

        // 2. EDIT (UPDATE)
        $updatePayload = [
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => 'Toko Mitra Berkah REVISI',
            'no_telp'        => '081299988877',
            'email'          => 'revisi@berkah.local',
            'alamat'         => 'Jl. Pahlawan No. 10',
            'kota'           => 'Surabaya',
            'tipe_pelanggan' => 'distributor',
            'limit_kredit'   => '10000000',
            'tempo_hari'     => '30',
        ];

        $resUpdate = $this->postWithCsrf('master/pelanggan/update/' . $customerId, $updatePayload);
        $resUpdate->assertStatus(302);
        $resUpdate->assertRedirectTo('master/pelanggan');

        $updated = $db->table('pelanggan')->where('id', $customerId)->get()->getRowArray();
        $this->assertSame('Toko Mitra Berkah REVISI', $updated['nama_pelanggan']);
        $this->assertSame('081299988877', $updated['no_telp']);

        // 3. HAPUS (DELETE via POST / GET)
        $resDelete = $this->postWithCsrf('master/pelanggan/delete/' . $customerId, []);
        $resDelete->assertStatus(302);
        $resDelete->assertRedirectTo('master/pelanggan');

        $deleted = $db->table('pelanggan')->where('id', $customerId)->get()->getRowArray();
        $this->assertNotNull($deleted['deleted_at']);
    }

    public function testSupplierCrudFullLifecycle(): void
    {
        $db = Database::connect();
        $kode = 'VND-TEST-' . mt_rand(10000, 99999);

        // 1. TAMBAH (CREATE)
        $createPayload = [
            'kode_supplier' => $kode,
            'nama_supplier' => 'PT Sumber Komponen Global',
            'no_telp'       => '021-5554321',
            'email'         => 'info@sumberkomponen.local',
            'alamat'        => 'Kawasan Jababeka Blok C1',
            'mata_uang'     => 'IDR',
        ];

        $resCreate = $this->postWithCsrf('master/supplier/store', $createPayload);
        $resCreate->assertStatus(302);
        $resCreate->assertRedirectTo('master/supplier');

        $saved = $db->table('supplier')->where('kode_supplier', $kode)->get()->getRowArray();
        $this->assertNotNull($saved);
        $supplierId = (int) $saved['id'];

        // 2. EDIT (UPDATE)
        $updatePayload = [
            'kode_supplier' => $kode,
            'nama_supplier' => 'PT Sumber Komponen Global Tbk',
            'no_telp'       => '021-9998877',
            'email'         => 'sales@sumberkomponen.local',
            'alamat'        => 'Kawasan Jababeka Blok C1 Gedung B',
            'mata_uang'     => 'USD',
        ];

        $resUpdate = $this->postWithCsrf('master/supplier/update/' . $supplierId, $updatePayload);
        $resUpdate->assertStatus(302);
        $resUpdate->assertRedirectTo('master/supplier');

        $updated = $db->table('supplier')->where('id', $supplierId)->get()->getRowArray();
        $this->assertSame('PT Sumber Komponen Global Tbk', $updated['nama_supplier']);
        $this->assertSame('USD', $updated['mata_uang']);

        // 3. HAPUS (DELETE)
        $resDelete = $this->withSession($this->getSession())->get('master/supplier/delete/' . $supplierId);
        $resDelete->assertStatus(302);
        $resDelete->assertRedirectTo('master/supplier');

        $deleted = $db->table('supplier')->where('id', $supplierId)->get()->getRowArray();
        $this->assertNotNull($deleted['deleted_at']);
    }

    public function testKategoriAndSatuanCrudFullLifecycle(): void
    {
        $db = Database::connect();

        // KATEGORI
        $kodeKat = 'KAT-CRUD-' . mt_rand(1000, 9999);
        $resKatCreate = $this->postWithCsrf('master/kategori/store', [
            'kode_kategori' => $kodeKat,
            'nama_kategori' => 'Kategori Aksesoris Komputer',
        ]);
        $resKatCreate->assertStatus(302);

        $savedKat = $db->table('kategori_produk')->where('kode_kategori', $kodeKat)->get()->getRowArray();
        $this->assertNotNull($savedKat);
        $katId = (int) $savedKat['id'];

        // Update Kategori
        $resKatUpdate = $this->postWithCsrf('master/kategori/update/' . $katId, [
            'kode_kategori' => $kodeKat,
            'nama_kategori' => 'Kategori Aksesoris Gaming',
        ]);
        $resKatUpdate->assertStatus(302);

        $updatedKat = $db->table('kategori_produk')->where('id', $katId)->get()->getRowArray();
        $this->assertSame('Kategori Aksesoris Gaming', $updatedKat['nama_kategori']);

        // Delete Kategori
        $resKatDel = $this->postWithCsrf('master/kategori/delete/' . $katId, []);
        $resKatDel->assertStatus(302);
        $this->assertNull($db->table('kategori_produk')->where('id', $katId)->get()->getRowArray());

        // SATUAN
        $kodeSat = 'STN-' . mt_rand(1000, 9999);
        $resSatCreate = $this->postWithCsrf('master/satuan/store', [
            'kode_satuan' => $kodeSat,
            'nama_satuan' => 'Karton Besar',
        ]);
        $resSatCreate->assertStatus(302);

        $savedSat = $db->table('satuan')->where('kode_satuan', $kodeSat)->get()->getRowArray();
        $this->assertNotNull($savedSat);
        $satId = (int) $savedSat['id'];

        // Update Satuan
        $resSatUpdate = $this->postWithCsrf('master/satuan/update/' . $satId, [
            'kode_satuan' => $kodeSat,
            'nama_satuan' => 'Karton Ekspor 24 Pcs',
        ]);
        $resSatUpdate->assertStatus(302);

        $updatedSat = $db->table('satuan')->where('id', $satId)->get()->getRowArray();
        $this->assertSame('Karton Ekspor 24 Pcs', $updatedSat['nama_satuan']);

        // Delete Satuan
        $resSatDel = $this->postWithCsrf('master/satuan/delete/' . $satId, []);
        $resSatDel->assertStatus(302);
        $this->assertNull($db->table('satuan')->where('id', $satId)->get()->getRowArray());
    }
}
