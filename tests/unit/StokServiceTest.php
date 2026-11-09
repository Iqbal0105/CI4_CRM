<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\StokTidakCukupException;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Modules\Stok\Services\StokService;

final class StokServiceTest extends CIUnitTestCase
{
    private StokService $stokService;
    protected $db;
    private int $testProdukId;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->stokService = new StokService($this->db);

        // Create a test product
        $kategori = $this->db->table('kategori_produk')->get()->getRow();
        $kategoriId = $kategori ? $kategori->id : 1;
        $satuan = $this->db->table('satuan')->get()->getRow();
        $satuanId = $satuan ? $satuan->id : 1;

        $sku = 'TEST-SKU-' . time() . '-' . mt_rand(100, 999);
        $this->db->table('produk')->insert([
            'kode_produk'     => $sku,
            'barcode'         => 'BC' . mt_rand(100000, 999999),
            'nama_produk'     => 'Test Unit Product ' . $sku,
            'kategori_id'     => $kategoriId,
            'satuan_id'       => $satuanId,
            'harga_beli'      => '50000.00',
            'harga_jual'      => '75000.00',
            'stok_minimum'    => 5,
            'status'          => 'active',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $this->testProdukId = (int) $this->db->insertID();
    }

    public function testStokMasukIncreasesStockAndCreatesLog(): void
    {
        $newSaldo = $this->stokService->masuk($this->testProdukId, 10.0, 'REF-TEST-001', 'Uji stok masuk');
        $this->assertEquals('10.000', $newSaldo);

        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertNotNull($stokRow);
        $this->assertEquals('10.000', (string) $stokRow->qty);

        $logRow = $this->db->table('stok_log')
            ->where('produk_id', $this->testProdukId)
            ->where('referensi_dokumen', 'REF-TEST-001')
            ->get()->getRow();
        $this->assertNotNull($logRow);
        $this->assertSame('in', $logRow->tipe);
        $this->assertEquals('10.000', (string) $logRow->qty);
        $this->assertEquals('10.000', (string) $logRow->saldo_akhir);
    }

    public function testStokKeluarDecreasesStockAndEnforcesNoNegative(): void
    {
        // Add 20 first
        $this->stokService->masuk($this->testProdukId, 20.0, 'REF-IN-001');

        // Deduct 5
        $saldoAfterKeluar = $this->stokService->keluar($this->testProdukId, 5.0, 'REF-OUT-001');
        $this->assertEquals('15.000', $saldoAfterKeluar);

        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertEquals('15.000', (string) $stokRow->qty);

        // Attempt to deduct 50 (exceeds available 15) -> must throw StokTidakCukupException
        $this->expectException(StokTidakCukupException::class);
        $this->stokService->keluar($this->testProdukId, 50.0, 'REF-OUT-FAIL');
    }

    public function testReserveAndReleaseReserve(): void
    {
        $this->stokService->masuk($this->testProdukId, 30.0, 'REF-IN-002');

        $this->stokService->reserve($this->testProdukId, 8.0);
        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertEquals('8.000', (string) $stokRow->reserved_qty);

        $this->stokService->releaseReserve($this->testProdukId, 3.0);
        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertEquals('5.000', (string) $stokRow->reserved_qty);
    }

    public function testPenyesuaianOpname(): void
    {
        $this->stokService->masuk($this->testProdukId, 25.0, 'REF-IN-003');

        // Physical count shows 22 (selisih -3)
        $this->stokService->penyesuaian($this->testProdukId, 22.0, 'OPNAME-2026-001', 'Hasil stock opname rak A');

        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertEquals('22.000', (string) $stokRow->qty);

        $logRow = $this->db->table('stok_log')
            ->where('produk_id', $this->testProdukId)
            ->where('tipe', 'adj')
            ->get()->getRow();
        $this->assertNotNull($logRow);
        $this->assertEquals('22.000', (string) $logRow->saldo_akhir);
    }
}
