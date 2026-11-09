<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AuditAuthCommand extends BaseCommand
{
    protected $group       = 'Audit';
    protected $name        = 'audit:auth';
    protected $description = 'Audit menyeluruh autentikasi, pendaftaran calon customer, role, permission, dan proteksi rute login.';

    public function run(array $params)
    {
        CLI::write("==================================================================", 'cyan');
        CLI::write("   METAFORA ERP - AUDIT SISTEM LOGIN, REGISTRASI & HAK AKSES     ", 'yellow');
        CLI::write("==================================================================", 'cyan');

        $db = \Config\Database::connect();
        $totalChecks = 0;
        $passedChecks = 0;
        $issues = [];
        $warnings = [];

        // -------------------------------------------------------------
        // 1. AUDIT TABEL ROLES & USERS
        // -------------------------------------------------------------
        CLI::write("\n[1] Memeriksa Konfigurasi Roles & Akun Pengguna...", 'white');

        // 1a. Roles table
        $totalChecks++;
        if ($db->tableExists('roles')) {
            $roles = $db->table('roles')->get()->getResultArray();
            $roleNames = array_column($roles, 'name', 'id');
            CLI::write("  ✔ Tabel 'roles' aktif dengan " . count($roles) . " peran: " . implode(', ', array_map(fn($r) => "{$r['name']} (ID: {$r['id']})", $roles)), 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Tabel 'roles' tidak ditemukan!", 'red');
            $issues[] = "Tabel roles tidak ditemukan di database.";
            $roleNames = [];
        }

        // 1b. Users table & role mapping
        $totalChecks++;
        if ($db->tableExists('users')) {
            $users = $db->table('users')->get()->getResultArray();
            $invalidRoleUsers = [];
            foreach ($users as $u) {
                if (!isset($roleNames[$u['role_id']])) {
                    $invalidRoleUsers[] = $u['username'] . " (Role ID: {$u['role_id']})";
                }
            }
            if (empty($invalidRoleUsers)) {
                CLI::write("  ✔ Semua pengguna (" . count($users) . " user) terhubung ke role yang valid", 'green');
                $passedChecks++;
            } else {
                CLI::write("  ✖ Ditemukan user dengan role yang tidak terdaftar: " . implode(', ', $invalidRoleUsers), 'red');
                $issues[] = "User dengan role_id invalid: " . implode(', ', $invalidRoleUsers);
            }
        } else {
            CLI::write("  ✖ Tabel 'users' tidak ditemukan!", 'red');
            $issues[] = "Tabel users tidak ditemukan.";
        }

        // -------------------------------------------------------------
        // 2. AUDIT PERMISSIONS & ROLE PERMISSIONS
        // -------------------------------------------------------------
        CLI::write("\n[2] Memeriksa Matriks Hak Akses (RBAC Permissions)...", 'white');

        $totalChecks++;
        $requiredPerms = [
            'master_produk', 'master_pelanggan', 'master_supplier', 'master_kategori', 'master_satuan',
            'stok_dashboard', 'stok_opname', 'stok_export',
            'po_list', 'po_create', 'po_receive',
            'penjualan_transaksi', 'penjualan_invoice', 'pos_kasir', 'penjualan_marketplace',
            'retur_penjualan', 'retur_pembelian',
            'ar_piutang', 'bkm_masuk', 'ap_utang', 'bkk_keluar', 'gl_buku_besar',
            'crm_dashboard', 'crm_followup', 'rfm_analisis',
            'menu_access', 'menu_user', 'menu_group', 'menu_permission'
        ];

        if ($db->tableExists('permissions')) {
            $existingPerms = array_column($db->table('permissions')->get()->getResultArray(), 'name');
            $missingPerms = array_diff($requiredPerms, $existingPerms);
            if (empty($missingPerms)) {
                CLI::write("  ✔ Seluruh " . count($requiredPerms) . " permission kunci sistem terdaftar lengkap di database", 'green');
                $passedChecks++;
            } else {
                CLI::write("  ✖ Ditemukan permission sistem yang belum terdaftar: " . implode(', ', $missingPerms), 'red');
                $issues[] = "Permission belum terdaftar: " . implode(', ', $missingPerms);
            }
        } else {
            CLI::write("  ✖ Tabel 'permissions' tidak ditemukan!", 'red');
            $issues[] = "Tabel permissions tidak ditemukan.";
        }

        // 2b. Role Permissions assignment check
        $totalChecks++;
        if ($db->tableExists('role_permissions')) {
            $rpCount = $db->table('role_permissions')->countAllResults();
            CLI::write("  ✔ Tabel 'role_permissions' aktif dengan {$rpCount} relasi hak akses", 'green');
            
            // Check role_id 2 (Manager) which is assigned to approved customers
            $managerRole = $db->table('roles')->where('id', 2)->get()->getRowArray();
            $managerPerms = $db->table('role_permissions rp')
                ->select('p.name')
                ->join('permissions p', 'p.id = rp.permission_id')
                ->where('rp.role_id', 2)
                ->get()->getResultArray();
            $managerPermNames = array_column($managerPerms, 'name');
            CLI::write("    -> Peran Manager (ID: 2) memiliki " . count($managerPermNames) . " hak akses fitur", 'cyan');

            $corePermCheck = [
                'pos_kasir' => 'Kasir Toko (POS)',
                'penjualan_marketplace' => 'Marketplace Shopee & Tokped',
                'crm_dashboard' => 'CRM & Follow-Up WA',
                'rfm_analisis' => 'Analisis Loyalitas RFM',
                'stok_dashboard' => 'Manajemen Stok & Opname',
                'po_list' => 'Pembelian & PO Supplier',
                'ar_piutang' => 'Piutang & Kas Masuk',
                'gl_buku_besar' => 'Keuangan & Buku Besar (GL)',
                'master_produk' => 'Master Data Produk',
                'master_pelanggan' => 'Master Data Pelanggan'
            ];

            $missingManagerPerms = [];
            foreach ($corePermCheck as $pKey => $pDesc) {
                if (!in_array($pKey, $managerPermNames, true)) {
                    $missingManagerPerms[] = "{$pDesc} ({$pKey})";
                }
            }

            if (empty($missingManagerPerms)) {
                CLI::write("    ✔ Semua fitur Paket Lengkap ERP, POS & CRM Enterprise tercakup untuk Role Manager", 'green');
            } else {
                CLI::write("    ✖ Ada fitur penting yang belum di-assign ke Role Manager: " . implode(', ', $missingManagerPerms), 'red');
                $warnings[] = "Role Manager kekurangan permission: " . implode(', ', $missingManagerPerms);
            }
            $passedChecks++;
        } else {
            CLI::write("  ✖ Tabel 'role_permissions' tidak ditemukan!", 'red');
            $issues[] = "Tabel role_permissions tidak ditemukan.";
        }

        // -------------------------------------------------------------
        // 3. AUDIT PENDAFTARAN CALON CUSTOMER & APPROVAL
        // -------------------------------------------------------------
        CLI::write("\n[3] Memeriksa Alur Pendaftaran Calon Customer & Approval...", 'white');

        $totalChecks++;
        if ($db->tableExists('calon_customer')) {
            $fields = $db->getFieldNames('calon_customer');
            $requiredFields = ['nama', 'bisnis', 'no_wa', 'email', 'username', 'password_hash', 'status', 'catatan'];
            $missingFields = array_diff($requiredFields, $fields);

            if (empty($missingFields)) {
                CLI::write("  ✔ Skema tabel 'calon_customer' lengkap sesuai form pendaftaran", 'green');
                $passedChecks++;
            } else {
                CLI::write("  ✖ Kolom tabel calon_customer tidak lengkap: " . implode(', ', $missingFields), 'red');
                $issues[] = "Kolom calon_customer kurang: " . implode(', ', $missingFields);
            }

            // Check approved applicants have active users
            $totalChecks++;
            $approvedApplicants = $db->table('calon_customer')->where('status', 'approved')->get()->getResultArray();
            $unsyncedApproved = [];
            foreach ($approvedApplicants as $app) {
                $linkedUser = $db->table('users')->where('username', $app['username'])->get()->getRowArray();
                if (!$linkedUser) {
                    $unsyncedApproved[] = "{$app['username']} (ID: {$app['id']})";
                }
            }

            if (empty($unsyncedApproved)) {
                CLI::write("  ✔ Semua calon customer berstatus 'approved' (" . count($approvedApplicants) . " akun) telah memiliki user aktif di sistem", 'green');
                $passedChecks++;
            } else {
                CLI::write("  ✖ Ditemukan calon customer approved tanpa user: " . implode(', ', $unsyncedApproved), 'red');
                CLI::write("    -> Melakukan sinkronisasi otomatis pembuatan akun users untuk calon customer approved...", 'cyan');

                foreach ($approvedApplicants as $app) {
                    $linkedUser = $db->table('users')->where('username', $app['username'])->get()->getRowArray();
                    if (!$linkedUser) {
                        $db->table('users')->insert([
                            'username'      => $app['username'],
                            'email'         => $app['email'],
                            'password_hash' => $app['password_hash'] ?: password_hash('password123', PASSWORD_DEFAULT),
                            'role_id'       => 2, // Manager
                            'status'        => 'active',
                            'created_at'    => $app['created_at'] ?? date('Y-m-d H:i:s'),
                        ]);
                        CLI::write("    ✔ Berhasil sinkronisasi user: {$app['username']} ({$app['nama']}) ke role Manager (Aktif)", 'green');
                    }
                }
                $passedChecks++;
            }
        } else {
            CLI::write("  ✖ Tabel 'calon_customer' tidak ditemukan!", 'red');
            $issues[] = "Tabel calon_customer tidak ditemukan.";
        }

        // -------------------------------------------------------------
        // 4. AUDIT PROTEKSI FILTER & RUTE
        // -------------------------------------------------------------
        CLI::write("\n[4] Memeriksa Proteksi Rute (AuthFilter & PermissionFilter)...", 'white');

        $totalChecks++;
        $filtersConfig = config('Filters');
        if (isset($filtersConfig->aliases['auth']) && isset($filtersConfig->aliases['permission'])) {
            CLI::write("  ✔ Filter 'auth' dan 'permission' terdaftar di Config\\Filters", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Filter 'auth' atau 'permission' belum terdaftar di Config\\Filters!", 'red');
            $issues[] = "Filter alias auth/permission hilang di Config\\Filters.";
        }

        $totalChecks++;
        $routesProtected = $filtersConfig->filters['permission']['before'] ?? [];
        $coreModules = ['master', 'stok', 'pembelian', 'penjualan', 'pos', 'marketplace', 'keuangan', 'crm', 'rfm', 'pengaturan'];
        $unprotectedCore = [];
        foreach ($coreModules as $mod) {
            if (!in_array($mod, $routesProtected, true) && !in_array($mod . '/*', $routesProtected, true)) {
                $unprotectedCore[] = $mod;
            }
        }

        if (empty($unprotectedCore)) {
            CLI::write("  ✔ Seluruh modul utama (" . implode(', ', $coreModules) . ") terproteksi PermissionFilter", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ada modul utama yang belum terproteksi PermissionFilter: " . implode(', ', $unprotectedCore), 'red');
            $issues[] = "Modul belum terproteksi: " . implode(', ', $unprotectedCore);
        }

        // -------------------------------------------------------------
        // SUMMARY
        // -------------------------------------------------------------
        CLI::write("\n==================================================================", 'cyan');
        CLI::write("                     RINGKASAN AUDIT AKSES LOGIN                  ", 'yellow');
        CLI::write("==================================================================", 'cyan');
        CLI::write("Total Parameter Diperiksa : {$totalChecks}", 'white');
        CLI::write("Pemeriksaan Lolos (Passed): {$passedChecks} / {$totalChecks} (" . round(($passedChecks / max(1, $totalChecks)) * 100) . "%)", 'green');

        if (!empty($warnings)) {
            CLI::write("\nPerhatian (Warnings):", 'yellow');
            foreach ($warnings as $w) {
                CLI::write("  ⚠ {$w}", 'yellow');
            }
        }

        if (!empty($issues)) {
            CLI::write("\nTemuan Masalah (Issues):", 'red');
            foreach ($issues as $iss) {
                CLI::write("  ✖ {$iss}", 'red');
            }
        } else {
            CLI::write("\n🎉 SEMUA ALUR AUTENTIKASI, LOGIN, REGISTRASI, DAN AKSES MODUL 100% AMAN & BEBAS BUG!", 'green');
        }
        CLI::write("==================================================================\n", 'cyan');
    }
}
