<?php

declare(strict_types=1);

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Services\AuditService;
use App\Services\NotificationService;
use Modules\Auth\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected AuditService $auditService;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->auditService = new AuditService();
        $this->notifService = new NotificationService();
    }

    /**
     * Display Login Page
     */
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('Modules\Auth\Views\login');
    }

    /**
     * Display Register Page
     */
    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('Modules\Auth\Views\register');
    }

    /**
     * Handle Public Registration (Calon Customer)
     */
    public function attemptRegister()
    {
        $throttler = service('throttler');
        $ipAddress = $this->request->getIPAddress();

        // Security Hardening: Anti-bot & flood rate limiting (10 attempts / min per IP)
        if (ENVIRONMENT !== 'testing' && $throttler->check(md5('reg_' . $ipAddress), 10, MINUTE) === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terlalu banyak permintaan pendaftaran. Silakan tunggu 1 menit sebelum mencoba kembali.');
        }

        $rules = [
            'nama'             => 'required|min_length[3]|max_length[150]',
            'bisnis'           => 'required|min_length[2]|max_length[200]',
            'no_wa'            => 'required|min_length[8]|max_length[30]',
            'email'            => 'required|valid_email|max_length[150]',
            'username'         => 'required|min_length[3]|max_length[50]|alpha_numeric_punct',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'nama'             => ['required' => 'Nama Pemilik / Pengelola wajib diisi.'],
            'bisnis'           => ['required' => 'Nama Bisnis / Toko wajib diisi.'],
            'no_wa'            => ['required' => 'Nomor WhatsApp aktif wajib diisi.'],
            'email'            => [
                'required'    => 'Alamat Email wajib diisi.',
                'valid_email' => 'Format Alamat Email tidak valid.',
            ],
            'username'         => ['required' => 'Username login pilihan wajib diisi.'],
            'password'         => ['min_length' => 'Kata sandi minimal 6 karakter.'],
            'password_confirm' => ['matches' => 'Konfirmasi kata sandi tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $nama     = trim((string) $this->request->getPost('nama'));
        $bisnis   = trim((string) $this->request->getPost('bisnis'));
        $noWa     = trim((string) $this->request->getPost('no_wa'));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $username = strtolower(trim((string) $this->request->getPost('username')));
        $password = (string) $this->request->getPost('password');
        $catatan  = trim((string) $this->request->getPost('catatan'));

        $db = \Config\Database::connect();

        // 1. Check uniqueness across users and calon_customer
        $userExists = $db->table('users')
            ->where('username', $username)
            ->orWhere('email', $email)
            ->countAllResults();

        if ($userExists > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Username '{$username}' atau Email '{$email}' sudah terdaftar dalam sistem. Silakan gunakan yang lain atau masuk.");
        }

        $applicantExists = $db->table('calon_customer')
            ->where('username', $username)
            ->orWhere('email', $email)
            ->countAllResults();

        if ($applicantExists > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Permohonan akun dengan username '{$username}' atau email '{$email}' sudah pernah diajukan. Silakan tunggu proses approval admin atau hubungi tim support.");
        }

        // 2. Insert into calon_customer
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertData = [
            'nama'          => $nama,
            'bisnis'        => $bisnis,
            'no_wa'         => $noWa,
            'email'         => $email,
            'username'      => $username,
            'password_hash' => $hashedPassword,
            'waktu_daftar'  => date('Y-m-d H:i:s'),
            'status'        => 'pending',
            'catatan'       => $catatan ?: 'Pendaftaran Akun Baru dari Landing Page',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $db->table('calon_customer')->insert($insertData);
        $applicantId = $db->insertID();

        // 3. Automatically notify admin in the topbar notification bell
        try {
            $this->notifService->logActivity(
                "Pendaftaran Calon Customer Baru: {$bisnis}",
                "{$nama} ({$bisnis}) mendaftar akun baru. No WA: {$noWa}. Menunggu verifikasi di menu Approval.",
                'approval',
                'warning',
                base_url('pengaturan/approval')
            );
        } catch (\Throwable $e) {
            // Ignore notification errors
        }

        return redirect()->to('/login')->with('success', "Pendaftaran akun '{$bisnis}' berhasil diajukan! Akun Anda sedang diverifikasi oleh Administrator. Tim kami juga akan mengirimkan notifikasi via WhatsApp {$noWa}.");
    }

    /**
     * Handle Login Submission
     */
    public function attemptLogin()
    {
        $throttler = service('throttler');
        $ipAddress = $this->request->getIPAddress();

        // Security: Brute-force protection (5 login attempts per minute per IP)
        if (ENVIRONMENT !== 'testing' && $throttler->check(md5($ipAddress), 5, MINUTE) === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terlalu banyak percobaan login gagal. Silakan tunggu 1 menit sebelum mencoba kembali.');
        }

        $rules = [
            'login'    => 'required|min_length[3]',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $loginInput = trim((string) $this->request->getPost('login'));
        $password   = (string) $this->request->getPost('password');

        $db = \Config\Database::connect();

        // 1. Try finding in primary users table
        $user = $this->userModel->findWithRole($loginInput);

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.');
            }

            // Regenerate session
            session()->regenerate();

            session()->set([
                'is_logged_in' => true,
                'user'         => [
                    'id'                => (int) $user['id'],
                    'username'          => $user['username'],
                    'email'             => $user['email'],
                    'phone'             => $user['phone'] ?? null,
                    'avatar'            => $user['avatar'] ?? null,
                    'role_id'           => (int) $user['role_id'],
                    'role_name'         => $user['role_name'],
                    'role_display_name' => $user['role_display_name'],
                ],
            ]);

            $this->auditService->log('login', 'users', $user['id'], null, ['ip' => $ipAddress]);

            return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . esc($user['username']) . '!');
        }

        // 2. If not found or failed in users, check calon_customer table for approval workflow
        $applicant = $db->table('calon_customer')
            ->where('username', strtolower($loginInput))
            ->orWhere('email', strtolower($loginInput))
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if ($applicant) {
            if ($applicant['status'] === 'pending') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Pendaftaran akun Anda ('{$applicant['username']}') masih berstatus MENUNGGU APPROVAL dari Administrator. Tim admin akan segera meninjau dan mengaktifkannya.");
            }

            if ($applicant['status'] === 'rejected') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Mohon maaf, permohonan akun Anda ditolak atau dinonaktifkan oleh Administrator.');
            }

            if ($applicant['status'] === 'approved') {
                // Verify applicant password
                if (!empty($applicant['password_hash']) && password_verify($password, $applicant['password_hash'])) {
                    // Provision user into users table if not exists
                    $existingUser = $db->table('users')->where('username', $applicant['username'])->get()->getRowArray();
                    if (!$existingUser) {
                        $db->table('users')->insert([
                            'username'      => $applicant['username'],
                            'email'         => $applicant['email'],
                            'password_hash' => $applicant['password_hash'],
                            'role_id'       => 2, // Manager role
                            'status'        => 'active',
                            'created_at'    => date('Y-m-d H:i:s'),
                        ]);
                        $newUserId = (int)$db->insertID();
                        $userObj = $this->userModel->findWithRole($applicant['username']);
                    } else {
                        $userObj = $existingUser;
                        $userObj['role_name'] = 'manager';
                        $userObj['role_display_name'] = 'Manager';
                    }

                    session()->regenerate();
                    session()->set([
                        'is_logged_in' => true,
                        'user'         => [
                            'id'                => (int) ($userObj['id'] ?? $applicant['id']),
                            'username'          => $applicant['username'],
                            'email'             => $applicant['email'],
                            'role_id'           => 2,
                            'role_name'         => 'manager',
                            'role_display_name' => 'Manager',
                        ],
                    ]);

                    $this->auditService->log('login', 'users', $userObj['id'] ?? null, null, ['ip' => $ipAddress, 'source' => 'approved_applicant']);

                    return redirect()->to('/dashboard')->with('success', 'Selamat datang di Portal MetaforaCRM, ' . esc($applicant['nama']) . ' (' . esc($applicant['bisnis']) . ')!');
                }
            }
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Username/Email atau Password yang Anda masukkan salah.');
    }

    /**
     * Handle Logout
     */
    public function logout()
    {
        $user = session()->get('user');
        if ($user) {
            $this->auditService->log('logout', 'users', $user['id']);
        }

        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
