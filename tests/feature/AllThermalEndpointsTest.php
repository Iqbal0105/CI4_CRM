<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AllThermalEndpointsTest extends CIUnitTestCase
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
                'id'                => 1,
                'username'          => 'admin',
                'nama'              => 'Super Administrator',
                'role_id'           => 1,
                'role_name'         => 'superadmin',
                'role_display_name' => 'Super Admin',
            ],
        ];
    }

    public function testPosStrukJsonReturnsValidTransactionData(): void
    {
        $db = \Config\Database::connect();
        $so = $db->table('sales_order so')
            ->select('so.*')
            ->join('sales_order_item soi', 'soi.so_id = so.id')
            ->where('so.deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$so) {
            $so = $db->table('sales_order')->where('deleted_at IS NULL')->orderBy('id', 'DESC')->get()->getRowArray();
            if ($so) {
                $db->table('sales_order_item')->insert([
                    'so_id'        => $so['id'],
                    'produk_id'    => 1,
                    'qty'          => 1,
                    'harga_satuan' => (float)$so['total'],
                    'diskon'       => 0,
                    'subtotal'     => (float)$so['total'],
                ]);
            }
        }

        if (!$so) {
            $this->markTestSkipped('No sales_order in test DB');
        }

        $result = $this->withSession($this->getSession())
            ->get("pos/struk-json/{$so['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals($so['nomor_so'], $json['order']['nomor_so']);
        $this->assertNotEmpty($json['items']);
        $this->assertArrayHasKey('store', $json);
    }

    public function testPenjualanThermalDataReturnsMatchingOrder(): void
    {
        $db = \Config\Database::connect();
        $so = $db->table('sales_order so')
            ->select('so.*')
            ->join('sales_order_item soi', 'soi.so_id = so.id')
            ->where('so.deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$so) {
            $this->markTestSkipped('No sales_order with items in test DB');
        }

        $result = $this->withSession($this->getSession())
            ->get("penjualan/thermal-data/{$so['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals($so['nomor_so'], $json['order']['nomor_so']);
        $this->assertNotEmpty($json['items']);
    }

    public function testMarketplaceResiThermalDataReturnsCourierAndItems(): void
    {
        $db = \Config\Database::connect();
        $mp = $db->table('penjualan_marketplace')->where('deleted_at IS NULL')->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$mp) {
            $this->markTestSkipped('No penjualan_marketplace in test DB');
        }

        $result = $this->withSession($this->getSession())
            ->get("marketplace/resi-thermal-data/{$mp['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals($mp['nomor_resi'], $json['order']['nomor_resi']);
        $this->assertNotEmpty($json['items']);
    }

    public function testKeuanganBkmThermalDataReturnsVoucher(): void
    {
        $db = \Config\Database::connect();
        $bkm = $db->table('pembayaran_piutang')->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$bkm) {
            $this->markTestSkipped('No pembayaran_piutang in test DB');
        }

        $result = $this->withSession($this->getSession())
            ->get("keuangan/bkm-thermal-data/{$bkm['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals('BKM', $json['type']);
        $this->assertEquals($bkm['nomor_bkm'], $json['data']['nomor_bkm']);
    }

    public function testKeuanganBkkThermalDataReturnsVoucher(): void
    {
        $db = \Config\Database::connect();
        $bkk = $db->table('pembayaran_utang')->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$bkk) {
            $this->markTestSkipped('No pembayaran_utang in test DB');
        }

        $result = $this->withSession($this->getSession())
            ->get("keuangan/bkk-thermal-data/{$bkk['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals('BKK', $json['type']);
        $this->assertEquals($bkk['nomor_bkk'], $json['data']['nomor_bkk']);
    }

    public function testReturPenjualanDetailReturnsItemsAndStore(): void
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_penjualan')->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$ret) {
            $db->table('retur_penjualan')->insert([
                'nomor_retur'    => 'RTJ-TEST-001',
                'so_id'          => 1,
                'pelanggan_id'   => 1,
                'tanggal'        => date('Y-m-d'),
                'tindak_lanjut'  => 'ganti_barang',
                'kondisi_barang' => 'rusak',
                'total'          => 100000,
                'status'         => 'completed',
                'alasan'         => 'Test retur',
                'user_id'        => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $retId = $db->insertID();
            $db->table('retur_penjualan_item')->insert([
                'retur_id'     => $retId,
                'produk_id'    => 1,
                'qty'          => 1,
                'harga_satuan' => 100000,
                'subtotal'     => 100000,
            ]);
            $ret = $db->table('retur_penjualan')->where('id', $retId)->get()->getRowArray();
        }

        $result = $this->withSession($this->getSession())
            ->get("retur/penjualan/detail/{$ret['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals($ret['nomor_retur'], $json['retur']['nomor_retur']);
        $this->assertArrayHasKey('store', $json);
    }

    public function testReturPembelianDetailReturnsItemsAndStore(): void
    {
        $db = \Config\Database::connect();
        $ret = $db->table('retur_pembelian')->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$ret) {
            $db->table('retur_pembelian')->insert([
                'nomor_retur'    => 'RTB-TEST-001',
                'po_id'          => 1,
                'supplier_id'    => 1,
                'tanggal'        => date('Y-m-d'),
                'tindak_lanjut'  => 'potong_utang',
                'total'          => 150000,
                'status'         => 'completed',
                'alasan'         => 'Test retur supplier',
                'user_id'        => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $retId = $db->insertID();
            $db->table('retur_pembelian_item')->insert([
                'retur_id'     => $retId,
                'produk_id'    => 1,
                'qty'          => 1,
                'harga_satuan' => 150000,
                'subtotal'     => 150000,
            ]);
            $ret = $db->table('retur_pembelian')->where('id', $retId)->get()->getRowArray();
        }

        $result = $this->withSession($this->getSession())
            ->get("retur/pembelian/detail/{$ret['id']}");

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals($ret['nomor_retur'], $json['retur']['nomor_retur']);
        $this->assertArrayHasKey('store', $json);
    }
}
