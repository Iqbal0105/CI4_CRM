<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\StokTidakCukupException;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Modules\PenjualanPOS\Services\CheckoutPosService;
use Modules\Stok\Services\StokService;

final class CheckoutPosServiceTest extends CIUnitTestCase
{
    private CheckoutPosService $checkoutPosService;
    private StokService $stokService;
    protected $db;
    private int $testProdukId;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->stokService = new StokService($this->db);
        $this->checkoutPosService = new CheckoutPosService($this->db);

        // Create a test product
        $kategori = $this->db->table('kategori_produk')->get()->getRow();
        $kategoriId = $kategori ? $kategori->id : 1;
        $satuan = $this->db->table('satuan')->get()->getRow();
        $satuanId = $satuan ? $satuan->id : 1;

        $sku = 'POS-TEST-' . time() . '-' . mt_rand(100, 999);
        $this->db->table('produk')->insert([
            'kode_produk'     => $sku,
            'barcode'         => 'POSBC' . mt_rand(100000, 999999),
            'nama_produk'     => 'POS Test Item ' . $sku,
            'kategori_id'     => $kategoriId,
            'satuan_id'       => $satuanId,
            'harga_beli'      => '20000.00',
            'harga_jual'      => '35000.00',
            'stok_minimum'    => 5,
            'status'          => 'active',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $this->testProdukId = (int) $this->db->insertID();

        // Seed stock of 20 units
        $this->stokService->masuk($this->testProdukId, 20.0, 'SEED-POS-001', 'Initial stock for POS test');
    }

    public function testPosCheckoutSuccess(): void
    {
        $payload = [
            'pelanggan_id' => 1,
            'metode_bayar' => 'tunai',
            'bayar'        => '100000.00',
            'diskon_total' => '0.00',
            'ppn_persen'   => '0.00',
            'catatan'      => 'POS unit test checkout',
            'items'        => [
                [
                    'produk_id' => $this->testProdukId,
                    'qty'       => 2,
                    'diskon'    => 0,
                ],
            ],
        ];

        $result = $this->checkoutPosService->checkout($payload);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['nomor_so']);
        $this->assertNotEmpty($result['nomor_invoice']);
        $this->assertSame('70000.00', $result['total']); // 2 * 35000
        $this->assertSame('30000.00', $result['kembalian']); // 100000 - 70000

        // Verify stock reduced from 20 to 18
        $stokRow = $this->db->table('stok')->where('produk_id', $this->testProdukId)->get()->getRow();
        $this->assertEquals('18.000', (string) $stokRow->qty);

        // Verify invoice is paid
        $invRow = $this->db->table('invoice')->where('id', $result['invoice_id'])->get()->getRow();
        $this->assertNotNull($invRow);
        $this->assertSame('paid', $invRow->status);
        $this->assertEquals('70000.00', (string) $invRow->total);

        // Verify balanced journal created for this transaction
        $jurnalRow = $this->db->table('jurnal')->where('referensi', $result['nomor_so'])->get()->getRow();
        $this->assertNotNull($jurnalRow);
        $this->assertEquals('70000.00', (string) $jurnalRow->total_debit);
        $this->assertEquals('70000.00', (string) $jurnalRow->total_kredit);
    }

    public function testPosCheckoutFailsWhenStockInsufficient(): void
    {
        $this->expectException(StokTidakCukupException::class);

        $payload = [
            'pelanggan_id' => 1,
            'metode_bayar' => 'tunai',
            'bayar'        => '20000000.00',
            'diskon_total' => '0.00',
            'ppn_persen'   => '0.00',
            'items'        => [
                [
                    'produk_id' => $this->testProdukId,
                    'qty'       => 500, // available is only 20
                    'diskon'    => 0,
                ],
            ],
        ];

        $this->checkoutPosService->checkout($payload);
    }
}
