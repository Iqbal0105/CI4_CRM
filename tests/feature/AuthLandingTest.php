<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Database;

final class AuthLandingTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper(['metafora', 'url', 'form']);
        $_SESSION = [];
        try {
            session()->remove(['is_logged_in', 'user']);
            session()->destroy();
        } catch (\Throwable $e) {
        }
    }

    private function postWithCsrf(string $url, array $data = [], array $session = [])
    {
        $security = config('Security');
        $hash = csrf_hash();
        $_COOKIE[$security->cookieName] = $hash;
        $data[$security->tokenName] = $hash;

        $caller = empty($session) ? $this : $this->withSession($session);

        return $caller
            ->withHeaders([$security->headerName => $hash])
            ->post($url, $data);
    }

    public function testLandingPageRendersSuccessfully(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('MetaforaCRM');
        $result->assertSee('Cara Termudah Bikin Pembeli Balik Belanja Lagi');
        $result->assertSee(base_url('login'));
        $result->assertSee(base_url('register'));
    }

    public function testLoginPageRendersSuccessfully(): void
    {
        $result = $this->get('login');
        $result->assertStatus(200);
        $result->assertSee('Selamat Datang Kembali');
        $result->assertSee('Username atau Email');
        $result->assertSee('Kata Sandi');
        $result->assertSee('Masuk ke Dashboard');
    }

    public function testRegisterPageRendersSuccessfully(): void
    {
        $result = $this->get('register');
        $result->assertStatus(200);
        $result->assertSee('Mulai Akun Toko Anda');
        $result->assertSee('Nama Pemilik / Pengelola');
        $result->assertSee('Nama Bisnis / Toko');
        $result->assertSee('Nomor WhatsApp Aktif');
        $result->assertSee('Username Login Pilihan');
    }

    public function testRegisterNewCustomerSavesToDatabaseAndNotifiesAdmin(): void
    {
        $uniqueSuffix = time();
        $username = 'toko_' . $uniqueSuffix;
        $email = "toko_{$uniqueSuffix}@example.com";

        $postData = [
            'nama'             => 'Budi Test Sukses',
            'bisnis'           => 'Toko Sukses ' . $uniqueSuffix,
            'no_wa'            => '081299998888',
            'email'            => $email,
            'username'         => $username,
            'password'         => 'secret123',
            'password_confirm' => 'secret123',
            'catatan'          => 'Paket Lengkap ERP, POS & CRM Enterprise',
        ];

        $result = $this->postWithCsrf('register', $postData);
        $result->assertRedirectTo('/login');

        // Verify record in calon_customer
        $db = Database::connect();
        $applicant = $db->table('calon_customer')->where('username', $username)->get()->getRowArray();
        $this->assertNotNull($applicant, 'Applicant must be saved in calon_customer');
        $this->assertSame('pending', $applicant['status']);
        $this->assertTrue(password_verify('secret123', $applicant['password_hash']));

        // Verify notification was logged
        $notif = $db->table('notifications')
            ->like('title', 'Toko Sukses ' . $uniqueSuffix)
            ->get()->getRowArray();
        $this->assertNotNull($notif, 'Notification for new registration must be logged');
    }

    public function testPendingApplicantLoginFailsWithClearMessage(): void
    {
        $uniqueSuffix = time() + 1;
        $username = 'pending_' . $uniqueSuffix;
        $email = "pending_{$uniqueSuffix}@example.com";

        $db = Database::connect();
        $db->table('calon_customer')->insert([
            'nama'          => 'Pending User',
            'bisnis'        => 'Pending Store',
            'no_wa'         => '081122334455',
            'email'         => $email,
            'username'      => $username,
            'password_hash' => password_hash('pass1234', PASSWORD_DEFAULT),
            'waktu_daftar'  => date('Y-m-d H:i:s'),
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->postWithCsrf('login', [
            'login'    => $username,
            'password' => 'pass1234',
        ]);

        $result->assertRedirect();
        $this->assertStringContainsString('MENUNGGU APPROVAL', session()->getFlashdata('error') ?? '');
    }

    public function testAdminCanApproveCustomerAndCustomerCanLogin(): void
    {
        $uniqueSuffix = time() + 2;
        $username = 'approved_' . $uniqueSuffix;
        $email = "approved_{$uniqueSuffix}@example.com";

        $db = Database::connect();
        $db->table('calon_customer')->insert([
            'nama'          => 'Approved Customer',
            'bisnis'        => 'Approved Store',
            'no_wa'         => '081987654321',
            'email'         => $email,
            'username'      => $username,
            'password_hash' => password_hash('pass12345', PASSWORD_DEFAULT),
            'waktu_daftar'  => date('Y-m-d H:i:s'),
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        $applicantId = (int) $db->insertID();

        // 1. Admin approves
        $adminSession = [
            'is_logged_in' => true,
            'user'         => [
                'id'        => 1,
                'username'  => 'superadmin',
                'role_id'   => 1,
                'role_name' => 'superadmin',
            ],
        ];

        $approveResult = $this->postWithCsrf("pengaturan/approval/approve/{$applicantId}", [], $adminSession);
        $approveResult->assertRedirectTo('pengaturan/approval');

        // Check status updated to approved
        $applicant = $db->table('calon_customer')->where('id', $applicantId)->get()->getRowArray();
        $this->assertSame('approved', $applicant['status']);

        // Check user record was provisioned
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();
        $this->assertNotNull($user, 'User record must be provisioned upon approval');
        $this->assertSame('active', $user['status']);

        // 2. Customer logs in
        $loginResult = $this->postWithCsrf('login', [
            'login'    => $username,
            'password' => 'pass12345',
        ]);

        $loginResult->assertRedirectTo('/dashboard');
        $this->assertTrue(session()->get('is_logged_in'));
        $this->assertSame($username, session()->get('user')['username']);
    }

    public function testDefaultAdminLoginSucceeds(): void
    {
        // Ensure admin user has a known password for this test
        $testPassword = 'testAdminPass_' . time();
        $db = Database::connect();
        $db->table('users')
            ->where('username', 'admin')
            ->update(['password_hash' => password_hash($testPassword, PASSWORD_BCRYPT)]);

        $result = $this->postWithCsrf('login', [
            'login'    => 'admin',
            'password' => $testPassword,
        ]);

        $result->assertRedirectTo('/dashboard');
        $this->assertTrue(session()->get('is_logged_in'));
        $this->assertSame('admin', session()->get('user')['username']);
    }
}
