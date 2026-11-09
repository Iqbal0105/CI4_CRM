<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\NotificationModel;
use Config\Database;

class NotificationService
{
    protected NotificationModel $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotificationModel();
    }

    /**
     * Synchronize live ERP alerts directly from database tables
     */
    public function syncSystemAlerts(?int $userId = null): void
    {
        $db = Database::connect();

        // 1. Sync Low Stock Alerts from Database (produk & stok)
        if ($db->tableExists('produk') && $db->tableExists('stok')) {
            $lowStockProducts = $db->table('produk p')
                ->select('p.id, p.kode_produk, p.nama_produk, p.stok_minimum, COALESCE(s.qty, 0) as fisik, (COALESCE(s.qty, 0) - COALESCE(s.reserved_qty, 0)) as sisa_stok')
                ->join('stok s', 's.produk_id = p.id', 'left')
                ->where('p.status', 'active')
                ->where('(COALESCE(s.qty, 0) - COALESCE(s.reserved_qty, 0)) <= p.stok_minimum')
                ->limit(5)
                ->get()->getResultArray();

            foreach ($lowStockProducts as $prod) {
                $title = "Peringatan Stok Kritis: {$prod['nama_produk']}";
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Stok produk tersisa " . number_format((float)$prod['sisa_stok'], 0) . " unit (batas minimum: {$prod['stok_minimum']}). Segera buat Purchase Order (PO).",
                        'category'   => 'stok',
                        'type'       => 'danger',
                        'icon'       => 'fa-solid fa-triangle-exclamation',
                        'url'        => base_url('stok'),
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 2. Sync Pending Approval from Database (calon_customer)
        if ($db->tableExists('calon_customer')) {
            $pendingApplicants = $db->table('calon_customer')
                ->where('status', 'pending')
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            foreach ($pendingApplicants as $app) {
                $title = "Approval Calon Customer: {$app['nama']}";
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Pendaftaran Calon Customer baru dari {$app['bisnis']} ({$app['no_wa']}) menunggu persetujuan admin.",
                        'category'   => 'approval',
                        'type'       => 'warning',
                        'icon'       => 'fa-solid fa-user-check',
                        'url'        => base_url('pengaturan/approval'),
                        'is_read'    => 0,
                        'created_at' => $app['waktu_daftar'] ?? date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 3. Sync Overdue Piutang (AR) from Database (piutang_usaha)
        if ($db->tableExists('piutang_usaha')) {
            $overdueAR = $db->table('piutang_usaha')
                ->where('status !=', 'lunas')
                ->where('jatuh_tempo <=', date('Y-m-d'))
                ->where('sisa >', 0)
                ->orderBy('jatuh_tempo', 'ASC')
                ->limit(5)
                ->get()->getResultArray();

            foreach ($overdueAR as $ar) {
                $title = "Jatuh Tempo Piutang: {$ar['nomor_piutang']}";
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $tempoText = !empty($ar['jatuh_tempo']) ? " sejak " . date('d/m/Y', strtotime($ar['jatuh_tempo'])) : "";
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Tagihan piutang usaha ({$ar['nomor_piutang']}) senilai Rp " . number_format((float)$ar['sisa'], 0, ',', '.') . " telah jatuh tempo{$tempoText}. Segera hubungi pelanggan untuk penagihan.",
                        'category'   => 'keuangan',
                        'type'       => 'danger',
                        'icon'       => 'fa-solid fa-file-invoice-dollar',
                        'url'        => base_url('keuangan/piutang'),
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 4. Sync CRM Follow-Up Schedules from Database (crm_transaction_followup_labels)
        if ($db->tableExists('crm_transaction_followup_labels')) {
            $pendingCrm = $db->table('crm_transaction_followup_labels')
                ->where('status', 'pending')
                ->orderBy('id', 'DESC')
                ->limit(3)
                ->get()->getResultArray();

            foreach ($pendingCrm as $crm) {
                $title = "Jadwal Follow-Up CRM: {$crm['customer_nama']}";
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Jadwal follow-up after sales ({$crm['label_code']}) untuk pelanggan {$crm['customer_nama']} siap diproses.",
                        'category'   => 'crm',
                        'type'       => 'primary',
                        'icon'       => 'fa-solid fa-calendar-check',
                        'url'        => base_url('crm'),
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 5. Sync Sales Orders from Database (sales_order)
        if ($db->tableExists('sales_order')) {
            $recentOrders = $db->table('sales_order')
                ->orderBy('id', 'DESC')
                ->limit(2)
                ->get()->getResultArray();

            foreach ($recentOrders as $order) {
                $title = "Transaksi Penjualan: {$order['nomor_so']}";
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Pesanan penjualan baru dicatat senilai Rp " . number_format((float)$order['total'], 0, ',', '.') . " (Status: " . strtoupper($order['status']) . ").",
                        'category'   => 'penjualan',
                        'type'       => 'success',
                        'icon'       => 'fa-solid fa-cart-shopping',
                        'url'        => base_url('penjualan'),
                        'is_read'    => 0,
                        'created_at' => $order['created_at'] ?? date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 6. Sync Marketplace & Resi Orders from Database (penjualan_marketplace)
        if ($db->tableExists('penjualan_marketplace')) {
            $recentMp = $db->table('penjualan_marketplace pm')
                ->select('pm.*, so.nomor_so')
                ->join('sales_order so', 'so.id = pm.so_id', 'left')
                ->where('pm.deleted_at IS NULL')
                ->orderBy('pm.id', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            foreach ($recentMp as $mp) {
                $refNo = !empty($mp['nomor_so']) ? $mp['nomor_so'] : $mp['no_pesanan'];
                $title = "Pesanan " . ucfirst($mp['source']) . ": " . $refNo;
                $existing = $this->notifModel->where('title', $title)->first();
                if (!$existing) {
                    $resiText = !empty($mp['nomor_resi']) ? " [Resi: {$mp['nomor_resi']}]" : "";
                    $kurirText = !empty($mp['kurir']) ? " via {$mp['kurir']}" : "";
                    $penerimaText = !empty($mp['nama_penerima']) ? " an. {$mp['nama_penerima']}" : "";
                    $this->notifModel->insert([
                        'user_id'    => $userId,
                        'title'      => $title,
                        'message'    => "Pesanan marketplace" . $resiText . $kurirText . $penerimaText . " senilai Rp " . number_format((float)$mp['total'], 0, ',', '.') . " telah tercatat di ERP SO, Stok, Invoice & Jurnal.",
                        'category'   => 'penjualan',
                        'type'       => 'success',
                        'icon'       => 'fa-solid fa-truck-fast',
                        'url'        => base_url('marketplace'),
                        'is_read'    => 0,
                        'created_at' => $mp['created_at'] ?? date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(?int $userId = null): int
    {
        return $this->notifModel->getUnreadCount($userId);
    }

    /**
     * Get feed with formatted Indonesian time ago
     */
    public function getFeed(?int $userId = null, int $limit = 15, ?string $category = null, ?int $isRead = null): array
    {
        $this->syncSystemAlerts($userId);
        $notifs = $this->notifModel->getFeed($userId, $limit, $category, $isRead);

        foreach ($notifs as &$item) {
            $item['time_ago'] = $this->timeAgo($item['created_at']);
        }
        unset($item);

        return $notifs;
    }

    /**
     * Log user data manipulation activity (create, update, delete)
     */
    public function logActivity(string $title, string $message, string $category = 'sistem', string $type = 'success', string $url = '#', ?int $userId = null): void
    {
        $icon = 'fa-solid fa-circle-check';
        $lower = strtolower($title . ' ' . $message);

        if (str_contains($lower, 'hapus') || str_contains($lower, 'delete') || str_contains($lower, 'batal')) {
            $icon = 'fa-solid fa-trash-can';
            $type = 'danger';
        } elseif (str_contains($lower, 'ubah') || str_contains($lower, 'edit') || str_contains($lower, 'perbarui') || str_contains($lower, 'update')) {
            $icon = 'fa-solid fa-pen-to-square';
            $type = 'warning';
        } elseif (str_contains($lower, 'tambah') || str_contains($lower, 'buat') || str_contains($lower, 'create') || str_contains($lower, 'baru')) {
            $icon = 'fa-solid fa-circle-plus';
            $type = 'success';
        }

        // Avoid logging exact same message within 5 seconds (duplicate refresh prevention)
        $recent = $this->notifModel
            ->where('title', $title)
            ->where('created_at >=', date('Y-m-d H:i:s', time() - 5))
            ->first();

        if (!$recent) {
            $this->notifModel->insert([
                'user_id'    => $userId,
                'title'      => $title,
                'message'    => $message,
                'category'   => $category,
                'type'       => $type,
                'icon'       => $icon,
                'url'        => $url,
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Format time relative in Indonesian
     */
    public function timeAgo(?string $datetime): string
    {
        if (empty($datetime)) {
            return 'Baru saja';
        }

        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        }
        if ($diff < 3600) {
            $mins = max(1, (int) floor($diff / 60));
            return "{$mins} menit lalu";
        }
        if ($diff < 86400) {
            $hours = (int) floor($diff / 3600);
            return "{$hours} jam lalu";
        }
        if ($diff < 172800) {
            return "Kemarin, " . date('H:i', $timestamp);
        }

        return date('d M Y, H:i', $timestamp);
    }
}

