<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class AllPagesSmokeTest extends CIUnitTestCase
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

    public function testAllGetRoutesRenderWithFullLayoutAndStyles(): void
    {
        $htmlRoutes = [
            'landing',
            'dashboard',
            'master/produk',
            'master/pelanggan',
            'master/supplier',
            'master/kategori',
            'master/satuan',
            'stok',
            'stok/opname',
            'pembelian',
            'pembelian/create',
            'pos',
            'penjualan',
            'penjualan/create',
            'penjualan/invoice',
            'invoice',
            'marketplace',
            'marketplace/import',
            'marketplace/scan-resi',
            'retur/penjualan',
            'retur/penjualan/create',
            'retur/pembelian',
            'retur/pembelian/create',
            'keuangan/piutang',
            'keuangan/bkm',
            'keuangan/utang',
            'keuangan/bkk',
            'keuangan/jurnal',
            'keuangan/kas-bank',
            'crm',
            'crm/dashboard',
            'crm/broadcast',
            'crm/directory',
            'crm/kinerja',
            'crm/kelompok',
            'crm/chat',
            'rfm',
            'rfm/customer',
            'rfm/tracking',
            'rfm/kohort',
            'rfm/tren-produk',
            'rfm/matriks',
            'pengaturan/settings',
            'pengaturan/user',
            'pengaturan/groups',
            'pengaturan/approval',
            'pengaturan/access',
            'pengaturan/permission',
            'pengaturan/api-keys',
            'dev/crud',
            'dev/menu',
            'dev/extension',
            'dev/api',
            'dev/page',
            'dev/form',
            'dev/docs/web',
            'dev/docs/api',
            'notifications',
        ];

        foreach ($htmlRoutes as $route) {
            ob_start();
            $result = $this->withSession($this->getSession())->get($route);
            $rawOutput = ob_get_clean();

            $status = $result->response()->getStatusCode();
            $body = $result->response()->getBody() ?? '';

            $this->assertSame(
                200,
                $status,
                "Route '{$route}' failed to render. Status: {$status} - Body: " . substr($body, 0, 200)
            );

            // Must be valid HTML5 document
            $this->assertStringContainsString(
                '<!DOCTYPE html>',
                $body,
                "Route '{$route}' missing <!DOCTYPE html> declaration."
            );

            // All module pages (except standalone landing) must render the main layout sidebar
            if ($route !== 'landing') {
                $this->assertStringContainsString(
                    'id="sidebar"',
                    $body,
                    "Route '{$route}' does not inherit layouts/main. Missing sidebar navigation element."
                );
                $this->assertStringContainsString(
                    'class="top-navbar"',
                    $body,
                    "Route '{$route}' does not inherit layouts/main. Missing topbar element."
                );
                $this->assertStringContainsString(
                    'bootstrap',
                    $body,
                    "Route '{$route}' missing Bootstrap CSS/JS links."
                );
            }
        }
    }

    public function testExportAndReceiptRoutesReturnSuccess(): void
    {
        $exportRoutes = [
            'pos/struk/1',
            'pengaturan/user/export',
            'pengaturan/groups/export',
            'pengaturan/permission/export',
            'dev/crud/export',
        ];

        foreach ($exportRoutes as $route) {
            ob_start();
            $result = $this->withSession($this->getSession())->get($route);
            ob_end_clean();

            $this->assertSame(
                200,
                $result->response()->getStatusCode(),
                "Export route '{$route}' failed with status: " . $result->response()->getStatusCode()
            );
        }
    }
}
