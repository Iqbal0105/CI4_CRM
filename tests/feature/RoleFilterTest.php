<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class RoleFilterTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
    }

    public function testKasirCannotAccessDevCrud(): void
    {
        $session = [
            'is_logged_in' => true,
            'user'         => [
                'id'        => 3,
                'username'  => 'kasir',
                'role_id'   => 3,
                'role_name' => 'kasir',
            ],
        ];

        $res = $this->withSession($session)->get('dev/crud');
        $res->assertRedirectTo('/dashboard');
    }

    public function testKasirCannotAccessPengaturanUser(): void
    {
        $session = [
            'is_logged_in' => true,
            'user'         => [
                'id'        => 3,
                'username'  => 'kasir',
                'role_id'   => 3,
                'role_name' => 'kasir',
            ],
        ];

        $res = $this->withSession($session)->get('pengaturan/user');
        $res->assertRedirectTo('/dashboard');
    }

    public function testSuperadminCanAccessPengaturanUser(): void
    {
        $session = [
            'is_logged_in' => true,
            'user'         => [
                'id'        => 1,
                'username'  => 'superadmin',
                'role_id'   => 1,
                'role_name' => 'superadmin',
            ],
        ];

        $res = $this->withSession($session)->get('pengaturan/user');
        $res->assertStatus(200);
    }

    public function testDeactivatedUserSessionIsImmediatelyRevoked(): void
    {
        $db = \Config\Database::connect();
        // Create temporary inactive user
        $tempUsername = 'inactive_' . time();
        $db->table('users')->insert([
            'username'      => $tempUsername,
            'email'         => "{$tempUsername}@test.com",
            'password_hash' => password_hash('pass123', PASSWORD_BCRYPT),
            'role_id'       => 3,
            'status'        => 'inactive',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        $inactiveUserId = (int)$db->insertID();

        $session = [
            'is_logged_in' => true,
            'user'         => [
                'id'        => $inactiveUserId,
                'username'  => $tempUsername,
                'role_id'   => 3,
                'role_name' => 'kasir',
            ],
        ];

        $res = $this->withSession($session)->get('dashboard');
        $res->assertRedirectTo('/login');

        // Cleanup
        $db->table('users')->where('id', $inactiveUserId)->delete();
    }
}
