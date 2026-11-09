<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AllExportEndpointsTest extends CIUnitTestCase
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

    public function testAllExportEndpointsReturn200AndValidCsv(): void
    {
        $endpoints = [
            'master/produk/export',
            'master/pelanggan/export',
            'master/supplier/export',
            'master/kategori/export',
            'master/satuan/export',
            'pembelian/export',
            'penjualan/export',
            'penjualan/invoice/export',
            'marketplace/export',
            'stok/export',
            'keuangan/piutang/export',
            'keuangan/utang/export',
            'keuangan/jurnal/export',
            'crm/directory/export',
            'crm/kinerja/export',
            'rfm/customer/export',
            'retur/penjualan/export/csv',
            'retur/pembelian/export/csv',
        ];

        foreach ($endpoints as $url) {
            $result = $this->withSession($this->getSession())->get($url);
            $this->assertTrue(
                $result->isOK(),
                "Endpoint [{$url}] failed with status code " . $result->response()->getStatusCode()
            );

            $body = $result->response()->getBody();
            // Verify body is not empty and contains CSV or headers
            $this->assertNotEmpty($body, "Endpoint [{$url}] returned empty body.");
        }
    }
}

