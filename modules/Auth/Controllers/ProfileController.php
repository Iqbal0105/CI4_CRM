<?php

declare(strict_types=1);

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Services\AuditService;
use App\Services\NotificationService;
use Modules\Auth\Models\UserModel;
use Config\Database;

class ProfileController extends BaseController
{
    protected UserModel $userModel;
    protected AuditService $auditService;
    protected NotificationService $notifService;
    protected $db;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->auditService = new AuditService();
        $this->notifService = new NotificationService();
        $this->db           = Database::connect();
    }

    /**
     * Display User Profile Page
     */
    public function index()
    {
        $sessionUser = session()->get('user');
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/login')->with('error', 'Sesi Anda telah kedaluwarsa. Silakan masuk kembali.');
        }

        $user = $this->userModel->findByIdWithRole($userId);
        if (!$user) {
            return redirect()->to('/logout')->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Fetch recent activities for this user
        $activities = $this->db->table('audit_trail')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        $data = [
            'title'      => 'Profil Saya',
            'user'       => $user,
            'activities' => $activities,
        ];

        return view('Modules\Auth\Views\profile', $data);
    }

    /**
     * Update Basic Profile Information
     */
    public function updateProfile()
    {
        $sessionUser = session()->get('user');
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/logout');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|alpha_numeric_punct|is_unique[users.username,id,{$userId}]",
            'email'    => "required|valid_email|max_length[150]|is_unique[users.email,id,{$userId}]",
            'phone'    => 'permit_empty|min_length[8]|max_length[30]',
        ];

        $messages = [
            'username' => [
                'required'            => 'Username wajib diisi.',
                'min_length'          => 'Username minimal 3 karakter.',
                'alpha_numeric_punct' => 'Username hanya boleh berisi huruf, angka, titik, atau garis bawah.',
                'is_unique'           => 'Username sudah digunakan oleh akun lain.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah digunakan oleh akun lain.',
            ],
            'phone' => [
                'min_length' => 'Nomor WhatsApp minimal 8 digit.',
                'max_length' => 'Nomor WhatsApp maksimal 30 digit.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('active_tab', 'profile');
        }

        $username = strtolower(trim((string) $this->request->getPost('username')));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $phone    = trim((string) $this->request->getPost('phone'));

        $oldData = [
            'username' => $user['username'] ?? '',
            'email'    => $user['email'] ?? '',
            'phone'    => $user['phone'] ?? '',
        ];

        $updateData = [
            'username' => $username,
            'email'    => $email,
            'phone'    => $phone ?: null,
        ];

        $this->userModel->update($userId, $updateData);

        // Update session data
        $currSession = session()->get('user') ?? [];
        $currSession['username'] = $username;
        $currSession['email']    = $email;
        $currSession['phone']    = $phone;
        session()->set('user', $currSession);

        $this->auditService->log('update_profile', 'users', $userId, $oldData, $updateData);

        return redirect()->to('/profile')->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Change Account Password
     */
    public function changePassword()
    {
        $sessionUser = session()->get('user');
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/logout');
        }

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        $messages = [
            'current_password' => [
                'required' => 'Kata sandi saat ini wajib diisi.',
            ],
            'new_password' => [
                'required'   => 'Kata sandi baru wajib diisi.',
                'min_length' => 'Kata sandi baru minimal 6 karakter.',
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi kata sandi baru wajib diisi.',
                'matches'  => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('password_errors', $this->validator->getErrors())
                ->with('active_tab', 'security');
        }

        $currentPassword = (string) $this->request->getPost('current_password');
        $newPassword     = (string) $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()
                ->with('error', 'Kata sandi saat ini tidak sesuai.')
                ->with('active_tab', 'security');
        }

        // Update with new hashed password
        $this->userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        $this->auditService->log('change_password', 'users', $userId, null, ['status' => 'password_updated']);

        return redirect()->to('/profile')->with('success', 'Kata sandi Anda berhasil diperbarui! Gunakan kata sandi baru saat login berikutnya.');
    }

    /**
     * Upload Avatar Picture
     */
    public function uploadAvatar()
    {
        $sessionUser = session()->get('user');
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Tidak ada berkas foto yang diunggah atau berkas rusak.');
        }

        $validMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $validMimes, true)) {
            return redirect()->back()->with('error', 'Format foto harus berupa JPG, PNG, atau WEBP.');
        }

        if ($file->getSizeByUnit('mb') > 2) {
            return redirect()->back()->with('error', 'Ukuran foto maksimal adalah 2 MB.');
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Remove old avatar if exists
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!empty($user['avatar'])) {
            $oldFilePath = $uploadDir . DIRECTORY_SEPARATOR . $user['avatar'];
            if (is_file($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);

        $this->userModel->update($userId, ['avatar' => $newName]);

        // Update session
        $currSession = session()->get('user') ?? [];
        $currSession['avatar'] = $newName;
        session()->set('user', $currSession);

        $this->auditService->log('upload_avatar', 'users', $userId, null, ['avatar' => $newName]);

        return redirect()->to('/profile')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Delete Avatar Picture
     */
    public function deleteAvatar()
    {
        $sessionUser = session()->get('user');
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';

        if (!empty($user['avatar'])) {
            $oldFilePath = $uploadDir . DIRECTORY_SEPARATOR . $user['avatar'];
            if (is_file($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        $this->userModel->update($userId, ['avatar' => null]);

        // Update session
        $currSession = session()->get('user') ?? [];
        $currSession['avatar'] = null;
        session()->set('user', $currSession);

        $this->auditService->log('delete_avatar', 'users', $userId, null, ['avatar' => null]);

        return redirect()->to('/profile')->with('success', 'Foto profil berhasil dihapus.');
    }
}

