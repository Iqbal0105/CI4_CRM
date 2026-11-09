<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class PengaturanFeatureTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper(['metafora', 'security']);

        // Set matching CSRF cookie and token for test requests
        $security = config('Security');
        $hash = csrf_hash();
        $_COOKIE[$security->cookieName] = $hash;
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

    public function testPengaturanUserCrudAndToggle(): void
    {
        // 1. Get User List
        $res = $this->withSession($this->getSession())->get('pengaturan/user');
        $this->assertSame(200, $res->response()->getStatusCode());

        // 2. Toggle Status User 3 (kasir)
        $resToggle = $this->postWithCsrf('pengaturan/user/toggle-status/3');
        $this->assertTrue(in_array($resToggle->response()->getStatusCode(), [200, 302], true));
        // Revert user 3 back to active
        $this->postWithCsrf('pengaturan/user/toggle-status/3');

        // 3. User Export CSV
        $resExp = $this->withSession($this->getSession())->get('pengaturan/user/export');
        $this->assertSame(200, $resExp->response()->getStatusCode());
    }

    public function testPengaturanApprovalAndGroups(): void
    {
        // 1. Approval List
        $res = $this->withSession($this->getSession())->get('pengaturan/approval');
        $this->assertSame(200, $res->response()->getStatusCode());

        // 2. Approve Customer
        $resApprove = $this->postWithCsrf('pengaturan/approval/approve/1');
        $this->assertSame(302, $resApprove->response()->getStatusCode());

        // 3. Reject Customer
        $resReject = $this->postWithCsrf('pengaturan/approval/reject/2');
        $this->assertSame(302, $resReject->response()->getStatusCode());

        // 4. Groups List & Export
        $resGrp = $this->withSession($this->getSession())->get('pengaturan/groups');
        $this->assertSame(200, $resGrp->response()->getStatusCode());

        $resGrpExp = $this->withSession($this->getSession())->get('pengaturan/groups/export');
        $this->assertSame(200, $resGrpExp->response()->getStatusCode());
    }

    public function testPengaturanAccessAndPermission(): void
    {
        // 1. Access Page
        $resAcc = $this->withSession($this->getSession())->get('pengaturan/access');
        $this->assertSame(200, $resAcc->response()->getStatusCode());

        // 2. Save Access
        $resSaveAcc = $this->postWithCsrf('pengaturan/access/save', [
            'role_name'   => 'Admin',
            'permissions' => ['menu_dashboard', 'master_produk'],
        ]);
        $this->assertSame(302, $resSaveAcc->response()->getStatusCode());

        // 3. Permission List & Export
        $resPerm = $this->withSession($this->getSession())->get('pengaturan/permission');
        $this->assertSame(200, $resPerm->response()->getStatusCode());

        $resPermExp = $this->withSession($this->getSession())->get('pengaturan/permission/export');
        $this->assertSame(200, $resPermExp->response()->getStatusCode());
    }

    public function testUserCrudFullLifecycle(): void
    {
        $db = \Config\Database::connect();
        $username = 'operator_' . mt_rand(10000, 99999);

        // 1. TAMBAH (CREATE USER)
        $createRes = $this->postWithCsrf('pengaturan/user/store', [
            'username' => $username,
            'email'    => $username . '@metafora.local',
            'password' => 'secret123456',
            'role_id'  => 3, // Kasir
            'status'   => 'active',
        ]);
        $this->assertSame(302, $createRes->response()->getStatusCode());

        $user = $db->table('users')->where('username', $username)->get()->getRowArray();
        $this->assertNotNull($user, 'User baru harus berhasil dibuat di database');
        $this->assertSame($username . '@metafora.local', $user['email']);
        $userId = (int) $user['id'];

        // 2. EDIT (UPDATE USER)
        $updateRes = $this->postWithCsrf('pengaturan/user/update/' . $userId, [
            'email'    => $username . '_updated@metafora.local',
            'role_id'  => 2, // Manager
            'status'   => 'active',
            'password' => '', // Tanpa ubah password
        ]);
        $this->assertSame(302, $updateRes->response()->getStatusCode());

        $updated = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $this->assertSame($username . '_updated@metafora.local', $updated['email']);
        $this->assertSame(2, (int)$updated['role_id']);

        // 3. HAPUS (DELETE USER)
        $delRes = $this->postWithCsrf('pengaturan/user/delete/' . $userId, []);
        $this->assertSame(302, $delRes->response()->getStatusCode());

        $deleted = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $this->assertNull($deleted, 'User harus terhapus dari database');
    }
}
