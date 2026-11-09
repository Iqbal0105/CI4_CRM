<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

final class DashboardTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
    }

    public function testDashboardRendersSuccessfully(): void
    {
        // Simulate authenticated user session
        $sessionData = [
            'is_logged_in' => true,
            'user'         => [
                'id'          => 1,
                'username'    => 'superadmin',
                'nama'        => 'Super Administrator',
                'role_id'     => 1,
                'role_name'   => 'superadmin',
            ],
        ];

        $result = $this->withSession($sessionData)->get('dashboard');

        $result->assertStatus(200);
        $result->assertSee('Dashboard Utama');
        $result->assertSee('Peringatan Stok Minimum');
    }
}
