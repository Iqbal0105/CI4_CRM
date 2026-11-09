<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class NotificationTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper(['metafora', 'security']);

        $security = config('Security');
        $hash = csrf_hash();
        $_COOKIE[$security->cookieName] = $hash;
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

    private function postWithCsrf(string $route, array $params = []): \CodeIgniter\Test\TestResponse
    {
        $security = config('Security');
        $hash = csrf_hash();
        $_COOKIE[$security->cookieName] = $hash;
        $params[$security->tokenName] = $hash;

        return $this->withSession($this->getSession())
            ->withHeaders([$security->headerName => $hash])
            ->post($route, $params);
    }

    public function testNotificationCenterRendersSuccessfully(): void
    {
        $result = $this->withSession($this->getSession())->get('notifications');

        $result->assertStatus(200);
        $result->assertSee('Pusat Notifikasi');
        $result->assertSee('Total Notifikasi');
    }

    public function testNotificationFeedEndpointReturnsJson(): void
    {
        $result = $this->withSession($this->getSession())->get('notifications/feed');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertIsArray($json);
        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('unread_count', $json);
        $this->assertArrayHasKey('notifications', $json);
    }

    public function testNotificationMarkAllRead(): void
    {
        $result = $this->postWithCsrf('notifications/mark-all-read');
        $this->assertTrue(in_array($result->response()->getStatusCode(), [200, 302], true));
    }

    public function testMarketplaceResiNotificationAppearsInFeed(): void
    {
        $db = \Config\Database::connect();
        // Insert a marketplace order
        $db->table('penjualan_marketplace')->insert([
            'source'          => 'shopee',
            'no_pesanan'      => 'NOTIF-ORDER-' . time(),
            'nomor_resi'      => 'SPXNOTIF' . time(),
            'kurir'           => 'SPX Express',
            'nama_penerima'   => 'Customer Notif Test',
            'no_telepon'      => '081233445566',
            'alamat_penerima' => 'Jl. Notifikasi No. 1',
            'total'           => 150000.00,
            'status'          => 'processed',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession($this->getSession())->get('notifications/feed?category=penjualan');
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertNotEmpty($json['notifications']);
        $found = false;
        foreach ($json['notifications'] as $notif) {
            if (str_contains($notif['title'], 'Pesanan') || str_contains($notif['title'], 'Resi')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Should find marketplace or resi notification in feed');
    }

    public function testNotificationDeleteAndClearAll(): void
    {
        $db = \Config\Database::connect();
        $db->table('notifications')->insert([
            'user_id'    => 1,
            'title'      => 'Notifikasi Uji Hapus',
            'message'    => 'Pesan uji coba hapus satu item',
            'category'   => 'system',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $notifId = (int) $db->insertID();

        // 1. Hapus 1 Notifikasi
        $resDel = $this->postWithCsrf('notifications/delete/' . $notifId);
        $this->assertSame(302, $resDel->response()->getStatusCode());

        $deleted = $db->table('notifications')->where('id', $notifId)->get()->getRowArray();
        $this->assertNull($deleted, 'Notifikasi harus terhapus');

        // 2. Clear All Notifikasi
        $resClear = $this->postWithCsrf('notifications/clear-all');
        $this->assertSame(302, $resClear->response()->getStatusCode());
    }
}
