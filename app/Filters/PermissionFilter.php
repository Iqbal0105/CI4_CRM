<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    /**
     * Mapping of URI prefixes to required permission keys.
     * If user has ANY of the mapped permissions, access is granted.
     *
     * @var array<string, list<string>>
     */
    private array $uriPermissionMap = [
        // Master Data
        'master/produk'    => ['master_produk'],
        'master/pelanggan' => ['master_pelanggan'],
        'master/supplier'  => ['master_supplier'],
        'master/kurs'      => ['master_supplier'],
        'master/kategori'  => ['master_kategori'],
        'master/satuan'    => ['master_satuan'],
        'master'           => ['master_produk', 'master_pelanggan', 'master_supplier', 'master_kategori', 'master_satuan'],

        // Stok
        'stok/opname'      => ['stok_opname', 'stok_dashboard'],
        'stok/export'      => ['stok_export', 'stok_dashboard'],
        'stok'             => ['stok_dashboard', 'stok_opname', 'stok_export'],

        // Pembelian
        'pembelian/create'             => ['po_create', 'po_list'],
        'pembelian/store'              => ['po_create', 'po_list'],
        'pembelian/penerimaan'         => ['po_receive', 'po_list'],
        'pembelian/process-penerimaan' => ['po_receive', 'po_list'],
        'pembelian'                    => ['po_list', 'po_create', 'po_receive', 'retur_pembelian'],

        // Penjualan & Kasir
        'pos'                  => ['pos_kasir'],
        'penjualan/invoice'    => ['penjualan_invoice', 'penjualan_transaksi'],
        'invoice'              => ['penjualan_invoice', 'penjualan_transaksi'],
        'penjualan'            => ['penjualan_transaksi', 'penjualan_invoice', 'pos_kasir'],
        'marketplace'          => ['penjualan_marketplace', 'penjualan_transaksi'],

        // Retur
        'retur/penjualan'      => ['retur_penjualan', 'penjualan_transaksi'],
        'retur/pembelian'      => ['retur_pembelian', 'po_list'],
        'retur'                => ['retur_penjualan', 'retur_pembelian'],

        // Keuangan & Kas
        'keuangan/piutang'       => ['ar_piutang'],
        'keuangan/bayar-piutang' => ['ar_piutang'],
        'keuangan/bkm'           => ['bkm_masuk'],
        'keuangan/bkm-thermal'   => ['bkm_masuk'],
        'keuangan/utang'         => ['ap_utang'],
        'keuangan/bayar-utang'   => ['ap_utang'],
        'keuangan/bkk'           => ['bkk_keluar'],
        'keuangan/bkk-thermal'   => ['bkk_keluar'],
        'keuangan/jurnal'        => ['gl_buku_besar'],
        'keuangan/kas-bank'      => ['bkm_masuk', 'bkk_keluar', 'gl_buku_besar'],
        'keuangan'               => ['ar_piutang', 'bkm_masuk', 'ap_utang', 'bkk_keluar', 'gl_buku_besar'],

        // CRM & RFM
        'crm'                  => ['crm_dashboard', 'crm_followup'],
        'rfm'                  => ['rfm_analisis'],

        // Pengaturan Sistem
        'pengaturan/access'    => ['menu_access'],
        'pengaturan/user'      => ['menu_user'],
        'pengaturan/groups'    => ['menu_group'],
        'pengaturan/permission'=> ['menu_permission'],
        'pengaturan/approval'  => ['menu_user', 'menu_access'],
        'pengaturan/settings'  => ['menu_access', 'menu_user', 'menu_group'],
        'pengaturan'           => ['menu_access', 'menu_user', 'menu_group', 'menu_permission'],

        // Developer Tools
        'dev'                  => ['menu_crud', 'menu_page', 'menu_form', 'menu_menu', 'menu_docs_web'],
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('user');

        if (!$user) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                    ]);
            }
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        }

        $roleName = strtolower($user['role_name'] ?? '');
        $roleDisplayName = $user['role_display_name'] ?? ($user['role_name'] ?? 'User');

        // Super Admin has unrestricted bypass to all features
        if ($roleName === 'superadmin' || str_contains($roleName, 'super admin')) {
            return null;
        }

        // 1. Explicit argument permissions passed from route definition
        if (!empty($arguments)) {
            $requiredPerms = is_array($arguments) ? $arguments : explode(',', (string) $arguments);
            if (!has_permission($requiredPerms)) {
                return $this->denyAccess($request, $roleDisplayName);
            }
            return null;
        }

        // 2. Automatic URI inspection against permission mapping
        $uriPath = trim($request->getUri()->getPath(), '/');
        // Strip index.php if present
        $uriPath = preg_replace('#^index\.php/#i', '', $uriPath);

        $matchedPerms = null;
        // Check exact match or prefix match (longest prefix first)
        foreach ($this->uriPermissionMap as $prefix => $perms) {
            if ($uriPath === $prefix || str_starts_with($uriPath, $prefix . '/')) {
                $matchedPerms = $perms;
                break;
            }
        }

        if ($matchedPerms !== null) {
            if (!has_permission($matchedPerms)) {
                return $this->denyAccess($request, $roleDisplayName, $this->resolveModuleName($uriPath));
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }

    private function denyAccess(RequestInterface $request, string $roleDisplayName, ?string $moduleName = null)
    {
        $targetDesc = $moduleName ? "modul {$moduleName}" : 'halaman ini';
        $message = "Akses Ditolak: Peran '{$roleDisplayName}' tidak memiliki hak akses untuk {$targetDesc}. Silakan hubungi Administrator jika membutuhkan akses.";

        if ($request->isAJAX()) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => $message,
                ]);
        }

        return redirect()->to('/dashboard')->with('error', $message);
    }

    private function resolveModuleName(string $uriPath): string
    {
        $segments = explode('/', $uriPath);
        $first = strtolower($segments[0] ?? '');
        $second = strtolower($segments[1] ?? '');

        return match ($first) {
            'master' => 'Master Data (' . ucfirst($second ?: 'Produk') . ')',
            'pembelian' => 'Pembelian (PO)',
            'stok' => 'Manajemen Stok',
            'penjualan', 'pos', 'invoice' => 'Penjualan & Kasir',
            'marketplace' => 'Penjualan Marketplace',
            'retur' => 'Retur Barang',
            'keuangan' => 'Keuangan & Kas (' . strtoupper($second ?: 'Buku Besar') . ')',
            'crm' => 'CRM & Follow-Up',
            'rfm' => 'Analisis RFM',
            'pengaturan' => 'Pengaturan Sistem',
            'dev' => 'Developer Tools',
            default => ucfirst($first),
        };
    }
}

