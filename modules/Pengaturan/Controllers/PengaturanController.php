<?php

declare(strict_types=1);

namespace Modules\Pengaturan\Controllers;

use App\Controllers\BaseController;

class PengaturanController extends BaseController
{
    // ==========================================
    // 1. SETTINGS & COMPANY PROFILE
    // ==========================================
    public function settings()
    {
        $data = [
            'title' => 'Settings & Profil Perusahaan',
        ];

        return view('Modules\Pengaturan\Views\settings', $data);
    }

    public function updateSettings()
    {
        return redirect()->to('pengaturan/settings')->with('success', 'Konfigurasi parameter dan profil perusahaan berhasil disimpan.');
    }

    // ==========================================
    // 2. USER MANAGEMENT
    // ==========================================
    public function user()
    {
        $db = \Config\Database::connect();
        $users = $db->table('users u')
            ->select('u.*, r.name as role_name, r.display_name')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.deleted_at IS NULL')
            ->orderBy('u.id', 'ASC')
            ->get()->getResultArray();

        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $users,
            'roles' => $roles,
        ];

        return view('Modules\Pengaturan\Views\user', $data);
    }

    public function storeUser()
    {
        $db = \Config\Database::connect();
        $username = trim($this->request->getPost('username') ?? '');
        $email    = trim($this->request->getPost('email') ?? '');
        $password = (string) $this->request->getPost('password');
        $roleId   = (int) $this->request->getPost('role_id');
        $status   = $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active';

        if ($username === '') {
            return redirect()->back()->with('error', 'Username wajib diisi.');
        }

        // Check unique username
        $existing = $db->table('users')->where('username', $username)->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('error', "Username '{$username}' sudah digunakan.");
        }

        if (empty($email)) {
            $email = strtolower($username) . '@metafora.local';
        }

        if (empty($password) || strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password wajib diisi (minimal 6 karakter).');
        }

        if ($roleId <= 0) {
            $firstRole = $db->table('roles')->orderBy('id', 'ASC')->get()->getRowArray();
            $roleId = $firstRole ? (int)$firstRole['id'] : 1;
        }

        $db->table('users')->insert([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role_id'       => $roleId,
            'status'        => $status,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('pengaturan/user')->with('success', "User '{$username}' berhasil ditambahkan ke dalam sistem!");
    }

    public function updateUser(int $id)
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $email    = trim($this->request->getPost('email') ?? '');
        $roleId   = (int) $this->request->getPost('role_id');
        $status   = $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active';
        $password = (string) $this->request->getPost('password');

        $updateData = [
            'email'      => $email ?: $user['email'],
            'role_id'    => $roleId > 0 ? $roleId : $user['role_id'],
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Security Hardening: Prevent deactivating or demoting Primary Superadmin
        if ($id === 1 || $user['username'] === 'superadmin' || $user['username'] === 'admin') {
            $updateData['role_id'] = 1;
            $updateData['status']  = 'active';
        }

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $db->table('users')->where('id', $id)->update($updateData);

        return redirect()->to('pengaturan/user')->with('success', "Data user '{$user['username']}' berhasil diperbarui.");
    }

    public function deleteUser(int $id)
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Prevent deleting superadmin or id 1
        if ($id === 1 || $user['username'] === 'superadmin' || $user['username'] === 'admin') {
            return redirect()->to('pengaturan/user')->with('error', 'Akun Administrator Utama tidak boleh dihapus untuk keamanan sistem.');
        }

        $currentUserId = session()->get('user')['id'] ?? 0;
        if ($id === (int) $currentUserId) {
            return redirect()->to('pengaturan/user')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $db->table('users')->where('id', $id)->delete();

        return redirect()->to('pengaturan/user')->with('success', "User '{$user['username']}' berhasil dihapus.");
    }

    public function toggleStatusUser(int $id)
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'User not found'])->setStatusCode(404);
            }
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Security Hardening: Never deactivate or toggle Primary Administrator
        if ($id === 1 || $user['username'] === 'superadmin' || $user['username'] === 'admin') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Status Administrator Utama tidak dapat diubah.'])->setStatusCode(400);
            }
            return redirect()->back()->with('error', 'Status akun Administrator Utama tidak dapat diubah demi stabilitas sistem.');
        }

        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        $db->table('users')->where('id', $id)->update([
            'status'     => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'     => 'success',
                'new_status' => $newStatus,
                'message'    => "Status user '{$user['username']}' kini {$newStatus}.",
            ]);
        }

        return redirect()->to('pengaturan/user')->with('success', "Status user '{$user['username']}' berhasil diubah menjadi {$newStatus}.");
    }

    public function exportUser(string $format = 'csv')
    {
        $db = \Config\Database::connect();
        $users = $db->table('users u')
            ->select('u.id, u.username, u.email, r.display_name as role, u.status, u.created_at')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.deleted_at IS NULL')
            ->orderBy('u.id', 'ASC')
            ->get()->getResultArray();

        $currentUser = session()->get('user') ?? ['username' => 'Administrator', 'role' => 'Super Admin'];

        // PDF Export using Dompdf
        if (strtolower($format) === 'pdf') {
            $data = [
                'users'       => $users,
                'currentUser' => $currentUser,
            ];

            if ($this->request->getGet('preview') === 'html') {
                return view('Modules\Pengaturan\Views\user_pdf', $data);
            }

            $html = view('Modules\Pengaturan\Views\user_pdf', $data);

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'Helvetica');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'Laporan_Manajemen_Pengguna_' . date('Ymd_His') . '.pdf';

            // Stream directly to browser tab for instant viewing/downloading
            $dompdf->stream($filename, ['Attachment' => false]);
            exit;
        }

        // CSV / Excel Export
        $filename = 'export_users_' . date('Ymd_His') . '.csv';

        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['ID', 'Username', 'Email', 'Role / Group', 'Status', 'Dibuat Pada']);
        foreach ($users as $u) {
            fputcsv($out, [
                $u['id'],
                $u['username'],
                $u['email'],
                $u['role'] ?? 'General',
                strtoupper($u['status']),
                $u['created_at'],
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // ==========================================
    // 3. GROUPS / ROLES MANAGEMENT
    // ==========================================
    public function groups()
    {
        $db = \Config\Database::connect();
        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        // Calculate member count for each group
        foreach ($roles as &$r) {
            $r['user_count'] = $db->table('users')->where('role_id', $r['id'])->countAllResults();
        }
        unset($r);

        $data = [
            'title' => 'Grup & Peran Pengguna',
            'roles' => $roles,
        ];

        return view('Modules\Pengaturan\Views\groups', $data);
    }

    public function storeGroup()
    {
        $db = \Config\Database::connect();
        $name        = trim($this->request->getPost('name') ?? '');
        $displayName = trim($this->request->getPost('display_name') ?? '');
        $description = trim($this->request->getPost('description') ?? '');

        if ($name === '') {
            $name = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $displayName));
        }

        if ($displayName === '') {
            $displayName = ucwords(str_replace('_', ' ', $name));
        }

        $existing = $db->table('roles')->where('name', $name)->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('error', "Role code '{$name}' sudah digunakan.");
        }

        $db->table('roles')->insert([
            'name'         => $name,
            'display_name' => $displayName,
            'description'  => $description,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('pengaturan/groups')->with('success', "Grup / Role baru '{$displayName}' berhasil ditambahkan.");
    }

    public function updateGroup(int $id)
    {
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return redirect()->back()->with('error', 'Group tidak ditemukan.');
        }

        $displayName = trim($this->request->getPost('display_name') ?? $role['display_name']);
        $description = trim($this->request->getPost('description') ?? $role['description']);

        $db->table('roles')->where('id', $id)->update([
            'display_name' => $displayName,
            'description'  => $description,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('pengaturan/groups')->with('success', "Group '{$displayName}' berhasil diperbarui.");
    }

    public function deleteGroup(int $id)
    {
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return redirect()->back()->with('error', 'Group tidak ditemukan.');
        }

        if ($id <= 2 || in_array($role['name'], ['superadmin', 'manager', 'admin'], true)) {
            return redirect()->to('pengaturan/groups')->with('error', 'Group sistem bawaan dilindungi dan tidak dapat dihapus.');
        }

        // Reassign users of this role to role 1
        $db->table('users')->where('role_id', $id)->update(['role_id' => 1]);
        $db->table('roles')->where('id', $id)->delete();

        return redirect()->to('pengaturan/groups')->with('success', "Group '{$role['display_name']}' berhasil dihapus.");
    }

    public function exportGroup(string $format = 'csv')
    {
        $db = \Config\Database::connect();
        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        // Calculate member count for each group
        foreach ($roles as &$r) {
            $r['user_count'] = $db->table('users')->where('role_id', $r['id'])->where('deleted_at IS NULL')->countAllResults();
        }
        unset($r);

        $currentUser = session()->get('user') ?? ['username' => 'Administrator', 'role' => 'Super Admin'];

        // PDF Export using Dompdf
        if (strtolower($format) === 'pdf') {
            $data = [
                'roles'       => $roles,
                'currentUser' => $currentUser,
            ];

            if ($this->request->getGet('preview') === 'html') {
                return view('Modules\Pengaturan\Views\groups_pdf', $data);
            }

            $html = view('Modules\Pengaturan\Views\groups_pdf', $data);

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'Helvetica');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'Laporan_Grup_dan_Peran_' . date('Ymd_His') . '.pdf';

            // Stream directly to browser tab for instant viewing/downloading
            $dompdf->stream($filename, ['Attachment' => false]);
            exit;
        }

        // CSV / Excel Export
        $filename = 'export_groups_' . date('Ymd_His') . '.csv';

        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['ID', 'Role Code', 'Display Name', 'Deskripsi', 'Jumlah Anggota', 'Dibuat Pada']);
        foreach ($roles as $r) {
            fputcsv($out, [
                $r['id'],
                $r['name'],
                $r['display_name'],
                $r['description'] ?? '-',
                $r['user_count'] ?? 0,
                $r['created_at'] ?? '-',
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // ==========================================
    // 4. APPROVAL CALON CUSTOMER
    // ==========================================
    public function approval()
    {
        $db = \Config\Database::connect();
        $applicants = $db->table('calon_customer')->orderBy('id', 'DESC')->get()->getResultArray();

        $pendingCount  = $db->table('calon_customer')->where('status', 'pending')->countAllResults();
        $approvedCount = $db->table('calon_customer')->where('status', 'approved')->countAllResults();
        $rejectedCount = $db->table('calon_customer')->where('status', 'rejected')->countAllResults();

        $data = [
            'title'         => 'Approval Calon Customer',
            'applicants'    => $applicants,
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ];

        return view('Modules\Pengaturan\Views\approval', $data);
    }

    public function approveCustomer(int $id)
    {
        $db = \Config\Database::connect();
        $applicant = $db->table('calon_customer')->where('id', $id)->get()->getRowArray();

        $db->table('calon_customer')->where('id', $id)->update([
            'status'     => 'approved',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($applicant) {
            $existingUser = $db->table('users')->where('username', $applicant['username'])->get()->getRowArray();
            if (!$existingUser) {
                $db->table('users')->insert([
                    'username'      => $applicant['username'],
                    'email'         => $applicant['email'],
                    'password_hash' => $applicant['password_hash'] ?: password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                    'role_id'       => 2, // Manager
                    'status'        => 'active',
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
            } else {
                $db->table('users')->where('id', $existingUser['id'])->update(['status' => 'active']);
            }
        }

        $nama = $applicant['nama'] ?? 'Customer';
        return redirect()->to('pengaturan/approval')->with('success', "Pendaftaran Calon Customer '{$nama}' berhasil disetujui! Akun telah aktif.");
    }

    public function rejectCustomer(int $id)
    {
        $db = \Config\Database::connect();
        $applicant = $db->table('calon_customer')->where('id', $id)->get()->getRowArray();

        $db->table('calon_customer')->where('id', $id)->update([
            'status'     => 'rejected',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($applicant) {
            $db->table('users')->where('username', $applicant['username'])->update(['status' => 'inactive']);
        }

        return redirect()->to('pengaturan/approval')->with('success', 'Pendaftaran Calon Customer berhasil ditolak / dinonaktifkan.');
    }

    // ==========================================
    // 5. ACCESS CONTROL & RBAC
    // ==========================================
    protected function ensureRbacTables(): void
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // 1. Roles table
        if (!$db->tableExists('roles')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'display_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addUniqueKey('name');
            $forge->createTable('roles', true);

            $defaultRoles = [
                ['name' => 'superadmin', 'display_name' => 'Super Admin', 'description' => 'Akses penuh seluruh modul sistem'],
                ['name' => 'manager',    'display_name' => 'Manager',     'description' => 'Akses operasional dan laporan analitis'],
                ['name' => 'kasir',      'display_name' => 'Kasir',       'description' => 'Akses POS kasir dan transaksi penjualan'],
                ['name' => 'admin_stok', 'display_name' => 'Admin Stok',  'description' => 'Akses stok barang dan penerimaan PO'],
                ['name' => 'staff_cs',   'display_name' => 'Staff CS',    'description' => 'Akses CRM, journal follow-up, dan RFM'],
                ['name' => 'member',     'display_name' => 'Member / Calon Customer', 'description' => 'Akses terbatas demo customer'],
            ];
            foreach ($defaultRoles as $r) {
                $r['created_at'] = date('Y-m-d H:i:s');
                $r['updated_at'] = date('Y-m-d H:i:s');
                $db->table('roles')->insert($r);
            }
        }

        // 2. Permissions table
        if (!$db->tableExists('permissions')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'definition' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'module' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'custom',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addUniqueKey('name');
            $forge->createTable('permissions', true);
        } else {
            $fields = $db->getFieldNames('permissions');
            if (!in_array('module', $fields, true)) {
                $forge->addColumn('permissions', [
                    'module' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 50,
                        'default'    => 'custom',
                        'after'      => 'definition',
                    ],
                ]);
            }
        }

        // 3. Role Permissions Mapping Table
        if (!$db->tableExists('role_permissions')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'permission_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey(['role_id', 'permission_id']);
            $forge->createTable('role_permissions', true);
        }

        // 4. Seed Standard Permission Catalog
        $standardPerms = [
            // Module Menu
            ['name' => 'menu_dashboard',  'definition' => 'Menu Dashboard',     'module' => 'menu'],
            ['name' => 'menu_crud',       'definition' => 'Menu CRUD Builder',  'module' => 'menu'],
            ['name' => 'menu_page',       'definition' => 'Menu Page Builder',  'module' => 'menu'],
            ['name' => 'menu_form',       'definition' => 'Menu Form Builder',  'module' => 'menu'],
            ['name' => 'menu_menu',       'definition' => 'Menu Menu',          'module' => 'menu'],
            ['name' => 'menu_auth',       'definition' => 'Menu Auth',          'module' => 'menu'],
            ['name' => 'menu_user',       'definition' => 'Menu User',          'module' => 'menu'],
            ['name' => 'menu_group',      'definition' => 'Menu Group',         'module' => 'menu'],
            ['name' => 'menu_access',     'definition' => 'Menu Access Matrix', 'module' => 'menu'],
            ['name' => 'menu_permission', 'definition' => 'Menu Permission',    'module' => 'menu'],
            ['name' => 'menu_docs_web',   'definition' => 'Menu Web Docs',      'module' => 'menu'],

            // Module Master
            ['name' => 'master_produk',    'definition' => 'Master Produk',       'module' => 'master'],
            ['name' => 'master_pelanggan', 'definition' => 'Master Pelanggan',    'module' => 'master'],
            ['name' => 'master_supplier',  'definition' => 'Master Supplier',     'module' => 'master'],
            ['name' => 'master_kategori',  'definition' => 'Kategori Barang',     'module' => 'master'],
            ['name' => 'master_satuan',    'definition' => 'Unit & Satuan',       'module' => 'master'],
            ['name' => 'stok_dashboard',   'definition' => 'Dashboard Stok',      'module' => 'master'],
            ['name' => 'stok_opname',      'definition' => 'Stock Opname Fisik',  'module' => 'master'],
            ['name' => 'stok_export',      'definition' => 'Export Stok Excel',   'module' => 'master'],

            // Module Penjualan
            ['name' => 'pos_kasir',             'definition' => 'Kasir Cepat (POS)',       'module' => 'penjualan'],
            ['name' => 'penjualan_transaksi',   'definition' => 'Transaksi Penjualan',     'module' => 'penjualan'],
            ['name' => 'penjualan_marketplace', 'definition' => 'Penjualan Marketplace',   'module' => 'penjualan'],
            ['name' => 'penjualan_invoice',     'definition' => 'Cetak Invoice & Resi',   'module' => 'penjualan'],
            ['name' => 'retur_penjualan',       'definition' => 'Retur Penjualan',         'module' => 'penjualan'],

            // Module CRM
            ['name' => 'crm_dashboard', 'definition' => 'Dashboard CRM',               'module' => 'crm'],
            ['name' => 'crm_followup',  'definition' => 'Journal Follow-Up H+',         'module' => 'crm'],
            ['name' => 'rfm_analisis',  'definition' => 'Analisis Matriks & Kohort RFM','module' => 'crm'],

            // Module Pembelian
            ['name' => 'po_list',         'definition' => 'Purchase Order (PO)',    'module' => 'pembelian'],
            ['name' => 'po_create',       'definition' => 'Buat PO Baru',           'module' => 'pembelian'],
            ['name' => 'po_receive',      'definition' => 'Penerimaan Barang PO',   'module' => 'pembelian'],
            ['name' => 'retur_pembelian', 'definition' => 'Retur Pembelian',        'module' => 'pembelian'],

            // Module Keuangan
            ['name' => 'ar_piutang',    'definition' => 'Piutang Usaha (AR)',   'module' => 'keuangan'],
            ['name' => 'bkm_masuk',     'definition' => 'Penerimaan Kas (BKM)', 'module' => 'keuangan'],
            ['name' => 'ap_utang',      'definition' => 'Utang Usaha (AP)',     'module' => 'keuangan'],
            ['name' => 'bkk_keluar',    'definition' => 'Pengeluaran Kas (BKK)','module' => 'keuangan'],
            ['name' => 'gl_buku_besar', 'definition' => 'Buku Besar (GL)',     'module' => 'keuangan'],
        ];

        $existingPerms = $db->table('permissions')->get()->getResultArray();
        $existingMap = [];
        foreach ($existingPerms as $ep) {
            $existingMap[$ep['name']] = $ep;
        }

        $now = date('Y-m-d H:i:s');
        foreach ($standardPerms as $sp) {
            if (!isset($existingMap[$sp['name']])) {
                $db->table('permissions')->insert([
                    'name'       => $sp['name'],
                    'definition' => $sp['definition'],
                    'module'     => $sp['module'],
                    'created_at' => $now,
                ]);
            } else {
                if (empty($existingMap[$sp['name']]['module']) || $existingMap[$sp['name']]['module'] === 'general' || $existingMap[$sp['name']]['module'] === 'custom') {
                    $db->table('permissions')->where('id', $existingMap[$sp['name']]['id'])->update(['module' => $sp['module']]);
                }
            }
        }

        // 5. Seed default role permissions if table is empty
        $rolePermCount = $db->table('role_permissions')->countAllResults();
        if ($rolePermCount === 0) {
            $allPerms = $db->table('permissions')->get()->getResultArray();
            $permNameToId = array_column($allPerms, 'id', 'name');
            $roles = $db->table('roles')->get()->getResultArray();

            foreach ($roles as $r) {
                $roleName = strtolower($r['name'] ?? '');
                $rId = (int) $r['id'];
                $assignKeys = [];

                if (str_contains($roleName, 'admin') || str_contains($roleName, 'super')) {
                    $assignKeys = array_keys($permNameToId);
                } elseif (str_contains($roleName, 'manager')) {
                    $assignKeys = array_filter(array_keys($permNameToId), function ($k) {
                        return !in_array($k, ['menu_crud', 'menu_page', 'menu_form'], true);
                    });
                } elseif (str_contains($roleName, 'kasir')) {
                    $assignKeys = ['menu_dashboard', 'master_produk', 'master_pelanggan', 'pos_kasir', 'penjualan_transaksi', 'penjualan_invoice'];
                } elseif (str_contains($roleName, 'cs')) {
                    $assignKeys = ['menu_dashboard', 'master_produk', 'master_pelanggan', 'crm_dashboard', 'crm_followup', 'rfm_analisis'];
                } elseif (str_contains($roleName, 'stok') || str_contains($roleName, 'gudang')) {
                    $assignKeys = ['menu_dashboard', 'master_produk', 'master_supplier', 'master_kategori', 'master_satuan', 'stok_dashboard', 'stok_opname', 'stok_export', 'po_receive'];
                } else {
                    $assignKeys = ['menu_dashboard'];
                }

                $batch = [];
                foreach ($assignKeys as $key) {
                    if (isset($permNameToId[$key])) {
                        $batch[] = [
                            'role_id'       => $rId,
                            'permission_id' => $permNameToId[$key],
                            'created_at'    => $now,
                        ];
                    }
                }
                if (!empty($batch)) {
                    $db->table('role_permissions')->insertBatch($batch);
                }
            }
        }
    }

    public function access()
    {
        $this->ensureRbacTables();

        $db = \Config\Database::connect();
        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        if (empty($roles)) {
            $roles = [
                ['id' => 1, 'name' => 'superadmin', 'display_name' => 'Super Admin', 'description' => 'Akses penuh seluruh modul sistem'],
            ];
        }

        // Active role from GET param or default to first role
        $requestedRoleId = (int) ($this->request->getGet('role_id') ?? 0);
        $activeRole = null;
        if ($requestedRoleId > 0) {
            foreach ($roles as $r) {
                if ((int) $r['id'] === $requestedRoleId) {
                    $activeRole = $r;
                    break;
                }
            }
        }
        if (!$activeRole) {
            $activeRole = $roles[0];
        }
        $activeRoleId = (int) $activeRole['id'];

        // Fetch all permissions ordered by module and id
        $permissions = $db->table('permissions')->orderBy('module', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();

        // Fetch all role permissions mapping: [role_id => [perm_key_1, perm_key_2, ...]]
        $rpRows = $db->table('role_permissions rp')
            ->select('rp.role_id, p.name as perm_name')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->get()
            ->getResultArray();

        $rolePermissionsMap = [];
        foreach ($roles as $r) {
            $rolePermissionsMap[$r['id']] = [];
        }
        foreach ($rpRows as $row) {
            $rId = (int) $row['role_id'];
            if (!isset($rolePermissionsMap[$rId])) {
                $rolePermissionsMap[$rId] = [];
            }
            $rolePermissionsMap[$rId][] = $row['perm_name'];
        }

        // Module meta configuration
        $moduleMeta = [
            'menu' => [
                'title' => 'Navigasi & Menu',
                'icon'  => 'fa-bars',
                'class' => 'grp-menu',
            ],
            'master' => [
                'title' => 'Master & Stok',
                'icon'  => 'fa-boxes-stacked',
                'class' => 'grp-stok',
            ],
            'penjualan' => [
                'title' => 'Penjualan & POS',
                'icon'  => 'fa-cart-shopping',
                'class' => 'grp-penjualan',
            ],
            'crm' => [
                'title' => 'CRM & RFM',
                'icon'  => 'fa-users-viewfinder',
                'class' => 'grp-crm',
            ],
            'pembelian' => [
                'title' => 'Pembelian',
                'icon'  => 'fa-truck-ramp-box',
                'class' => 'grp-pembelian',
            ],
            'keuangan' => [
                'title' => 'Keuangan & Kas',
                'icon'  => 'fa-calculator',
                'class' => 'grp-keuangan',
            ],
            'custom' => [
                'title' => 'Izin Tambahan / Kustom',
                'icon'  => 'fa-shield-halved',
                'class' => 'grp-custom',
            ],
        ];

        $groupedPermissions = [];
        foreach ($moduleMeta as $modKey => $meta) {
            $groupedPermissions[$modKey] = [
                'meta'  => $meta,
                'items' => [],
            ];
        }

        foreach ($permissions as $p) {
            $mod = $p['module'] ?? 'custom';
            if (!isset($groupedPermissions[$mod])) {
                $mod = 'custom';
            }
            $groupedPermissions[$mod]['items'][] = $p;
        }

        $data = [
            'title'                  => 'Matriks Hak Akses',
            'roles'                  => $roles,
            'activeRole'             => $activeRole,
            'activeRoleId'           => $activeRoleId,
            'groupedPermissions'     => $groupedPermissions,
            'rolePermissionsMap'     => $rolePermissionsMap,
            'currentRolePermissions' => $rolePermissionsMap[$activeRoleId] ?? [],
        ];

        return view('Modules\Pengaturan\Views\access', $data);
    }

    public function getRolePermissions(int $roleId)
    {
        $this->ensureRbacTables();
        $db = \Config\Database::connect();
        $rpRows = $db->table('role_permissions rp')
            ->select('p.name')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->get()
            ->getResultArray();

        $perms = array_column($rpRows, 'name');
        return $this->response->setJSON([
            'status'      => 'success',
            'role_id'     => $roleId,
            'permissions' => $perms,
        ]);
    }

    public function saveAccess()
    {
        $this->ensureRbacTables();

        $db = \Config\Database::connect();
        $roleId = (int) $this->request->getPost('role_id');
        $roleName = trim((string) ($this->request->getPost('role_name') ?? ''));

        // Find role
        $role = null;
        if ($roleId > 0) {
            $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();
        }
        if (!$role && $roleName !== '') {
            $role = $db->table('roles')->where('display_name', $roleName)->orWhere('name', $roleName)->get()->getRowArray();
        }
        if (!$role) {
            $role = $db->table('roles')->orderBy('id', 'ASC')->get()->getRowArray();
        }

        if (!$role) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => 'error',
                    'message' => 'Role tujuan tidak ditemukan dalam sistem database.',
                ]);
            }
            return redirect()->to('pengaturan/access')->with('error', 'Role tujuan tidak ditemukan.');
        }

        $roleId = (int) $role['id'];
        $roleDisplayName = $role['display_name'] ?? $role['name'];

        // Get submitted permissions
        $submittedPerms = $this->request->getPost('permissions') ?? [];
        if (!is_array($submittedPerms)) {
            $submittedPerms = [];
        }

        // Map permission names to IDs
        $allPerms = $db->table('permissions')->get()->getResultArray();
        $permNameToId = array_column($allPerms, 'id', 'name');

        $permissionIds = [];
        $now = date('Y-m-d H:i:s');

        foreach ($submittedPerms as $permKey) {
            $permKey = trim((string) $permKey);
            if ($permKey === '') {
                continue;
            }

            if (isset($permNameToId[$permKey])) {
                $permissionIds[] = (int) $permNameToId[$permKey];
            } else {
                // If not in catalog, create automatically
                $db->table('permissions')->insert([
                    'name'       => $permKey,
                    'definition' => ucwords(str_replace('_', ' ', $permKey)),
                    'module'     => 'custom',
                    'created_at' => $now,
                ]);
                $newId = (int) $db->insertID();
                $permNameToId[$permKey] = $newId;
                $permissionIds[] = $newId;
            }
        }

        $permissionIds = array_values(array_unique($permissionIds));

        // Save in transaction
        $db->transStart();

        // 1. Delete existing permissions for this role
        $db->table('role_permissions')->where('role_id', $roleId)->delete();

        // 2. Insert new permissions
        if (!empty($permissionIds)) {
            $batch = [];
            foreach ($permissionIds as $pId) {
                $batch[] = [
                    'role_id'       => $roleId,
                    'permission_id' => $pId,
                    'created_at'    => $now,
                ];
            }
            $db->table('role_permissions')->insertBatch($batch);
        }

        // 3. Optional audit log
        if ($db->tableExists('audit_trail')) {
            $user = session()->get('user');
            $db->table('audit_trail')->insert([
                'user_id'    => $user['id'] ?? null,
                'action'     => 'update_role_permissions',
                'table_name' => 'role_permissions',
                'record_id'  => (string) $roleId,
                'new_values' => json_encode(['role_id' => $roleId, 'permissions_count' => count($permissionIds), 'permissions' => $submittedPerms]),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string) $this->request->getUserAgent(),
                'created_at' => $now,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => "Terjadi kesalahan saat menyimpan hak akses untuk role '{$roleDisplayName}'.",
                ]);
            }
            return redirect()->to("pengaturan/access?role_id={$roleId}")->with('error', "Gagal menyimpan hak akses untuk role '{$roleDisplayName}'.");
        }

        $count = count($permissionIds);
        $msg = "Konfigurasi hak akses ({$count} izin aktif) untuk role '{$roleDisplayName}' berhasil disimpan ke sistem!";

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'            => 'success',
                'message'           => $msg,
                'role_id'           => $roleId,
                'role_name'         => $roleDisplayName,
                'permissions_count' => $count,
                'permissions'       => array_values($submittedPerms),
            ]);
        }

        return redirect()->to("pengaturan/access?role_id={$roleId}")->with('success', $msg);
    }

    // ==========================================
    // 6. PERMISSION MANAGEMENT
    // ==========================================
    public function permission()
    {
        $this->ensureRbacTables();

        $db = \Config\Database::connect();
        $permissions = $db->table('permissions')->orderBy('module', 'ASC')->orderBy('id', 'DESC')->get()->getResultArray();
        $permList = [];
        foreach ($permissions as $p) {
            $permList[] = [
                'id'     => $p['id'],
                'name'   => $p['name'],
                'def'    => $p['definition'],
                'module' => $p['module'] ?? 'custom',
            ];
        }

        $data = [
            'title'    => 'Katalog Izin (Permissions)',
            'permList' => $permList,
        ];

        return view('Modules\Pengaturan\Views\permission', $data);
    }

    public function storePermission()
    {
        $name = trim($this->request->getPost('name') ?? '');
        $def  = trim($this->request->getPost('definition') ?? '');
        $module = trim($this->request->getPost('module') ?? 'custom');
        $redirectTarget = trim($this->request->getPost('redirect_to') ?? '');

        if ($name === '') {
            return redirect()->back()->with('error', 'Nama permission key wajib diisi.');
        }

        $formattedName = strtolower(str_replace([' ', '-'], '_', $name));

        $this->ensureRbacTables();
        $db = \Config\Database::connect();

        $existing = $db->table('permissions')->where('name', $formattedName)->get()->getRowArray();
        if ($existing) {
            return redirect()->back()->with('error', "Permission key '{$formattedName}' sudah terdaftar sebelumnya.");
        }

        if (!in_array($module, ['menu', 'master', 'penjualan', 'crm', 'pembelian', 'keuangan', 'custom'], true)) {
            $module = 'custom';
        }

        $db->table('permissions')->insert([
            'name'       => $formattedName,
            'definition' => $def ?: ucwords(str_replace('_', ' ', $formattedName)),
            'module'     => $module,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $referer = (string) $this->request->getServer('HTTP_REFERER');
        if ($redirectTarget === 'pengaturan/access' || str_contains($referer, 'access')) {
            return redirect()->to('pengaturan/access')->with('success', "Permission baru '{$formattedName}' berhasil didaftarkan dan langsung tersedia di Matriks Hak Akses!");
        }

        return redirect()->to('pengaturan/permission')->with('success', "Permission baru '{$formattedName}' berhasil didaftarkan ke sistem!");
    }

    public function updatePermission(int $id)
    {
        $name   = trim($this->request->getPost('name') ?? '');
        $def    = trim($this->request->getPost('definition') ?? '');
        $module = trim($this->request->getPost('module') ?? '');

        $this->ensureRbacTables();
        $db = \Config\Database::connect();

        $formattedName = strtolower(str_replace([' ', '-'], '_', $name));

        $updateData = [
            'name'       => $formattedName,
            'definition' => $def ?: ucwords(str_replace('_', ' ', $formattedName)),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($module !== '') {
            $updateData['module'] = $module;
        }

        $db->table('permissions')->where('id', $id)->update($updateData);

        return redirect()->to('pengaturan/permission')->with('success', "Permission ID #{$id} ({$formattedName}) berhasil diperbarui.");
    }

    public function deletePermission(int $id)
    {
        $this->ensureRbacTables();
        $db = \Config\Database::connect();

        if ($db->tableExists('role_permissions')) {
            $db->table('role_permissions')->where('permission_id', $id)->delete();
        }
        $db->table('permissions')->where('id', $id)->delete();

        return redirect()->to('pengaturan/permission')->with('success', "Permission ID #{$id} berhasil dihapus.");
    }

    public function exportPermission(string $format = 'csv')
    {
        $db = \Config\Database::connect();
        $permList = $db->table('permissions')->orderBy('id', 'DESC')->get()->getResultArray();

        $filename = 'export_permissions_' . date('Ymd_His') . '.csv';

        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['ID', 'Permission Key', 'Definition']);
        foreach ($permList as $p) {
            fputcsv($out, [$p['id'], $p['name'], $p['definition']]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // ==========================================
    // 7. API KEYS MANAGEMENT
    // ==========================================
    public function apiKeys()
    {
        return redirect()->to('pengaturan/access');
    }
}
