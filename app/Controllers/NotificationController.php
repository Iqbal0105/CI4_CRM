<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Services\NotificationService;
use CodeIgniter\HTTP\ResponseInterface;

class NotificationController extends BaseController
{
    protected NotificationModel $notifModel;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->notifModel   = new NotificationModel();
        $this->notifService = new NotificationService();
    }

    /**
     * Notification Center Page (/notifications)
     */
    public function index()
    {
        $userId   = session()->get('user')['id'] ?? null;
        $category = $this->request->getGet('category') ?: 'all';
        $status   = $this->request->getGet('status') ?: 'all';
        $search   = trim((string) $this->request->getGet('q'));

        // Sync fresh alerts
        $this->notifService->syncSystemAlerts($userId);

        $builder = $this->notifModel->builder();
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }

        if ($category !== 'all' && !empty($category)) {
            $builder->where('category', $category);
        }

        if ($status === 'unread') {
            $builder->where('is_read', 0);
        } elseif ($status === 'read') {
            $builder->where('is_read', 1);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        $notifications = $builder->orderBy('id', 'DESC')->get()->getResultArray();

        // Calculate stats with user scoping
        $baseBuilder = function () use ($userId) {
            $b = $this->notifModel->builder();
            if ($userId !== null) {
                $b->groupStart()
                    ->where('user_id', $userId)
                    ->orWhere('user_id', null)
                    ->groupEnd();
            }
            return $b;
        };

        $totalAll      = $baseBuilder()->countAllResults();
        $unreadCount   = $baseBuilder()->where('is_read', 0)->countAllResults();
        $criticalCount = $baseBuilder()->whereIn('type', ['danger', 'warning'])->where('is_read', 0)->countAllResults();
        $readCount     = $baseBuilder()->where('is_read', 1)->countAllResults();

        foreach ($notifications as &$item) {
            $item['time_ago'] = $this->notifService->timeAgo($item['created_at']);
        }
        unset($item);

        $data = [
            'title'          => 'Pusat Notifikasi & Alert Sistem',
            'notifications'  => $notifications,
            'category'       => $category,
            'status'         => $status,
            'search'         => $search,
            'totalAll'       => $totalAll,
            'unreadCount'    => $unreadCount,
            'criticalCount'  => $criticalCount,
            'readCount'      => $readCount,
        ];

        return view('notifications/index', $data);
    }

    /**
     * AJAX Feed for Topbar Dropdown
     */
    public function getFeed(): ResponseInterface
    {
        $userId   = session()->get('user')['id'] ?? null;
        $category = $this->request->getGet('category');
        $status   = $this->request->getGet('status');

        // Dropdown feed strictly displays only unread notifications (is_read = 0)
        // unless status is explicitly requested otherwise
        $isRead = 0;
        if ($status === 'all') {
            $isRead = null;
        } elseif ($status === 'read') {
            $isRead = 1;
        }

        $notifications = $this->notifService->getFeed($userId, 15, $category, $isRead);
        $unreadCount   = $this->notifService->getUnreadCount($userId);

        $badgeLabel = $unreadCount > 99 ? '99+' : (string) $unreadCount;

        return $this->response->setJSON([
            'success'       => true,
            'unread_count'  => $unreadCount,
            'unread_label'  => $badgeLabel,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markRead(int $id): ResponseInterface
    {
        $userId = session()->get('user')['id'] ?? null;
        $this->notifModel->markAsRead($id, $userId);
        $unreadCount = $this->notifService->getUnreadCount($userId);

        return $this->response->setJSON([
            'success'      => true,
            'message'      => 'Notifikasi berhasil ditandai telah dibaca.',
            'unread_count' => $unreadCount,
            'unread_label' => $unreadCount > 99 ? '99+' : (string) $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(): ResponseInterface
    {
        $userId = session()->get('user')['id'] ?? null;
        $this->notifModel->markAllAsRead($userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Semua notifikasi telah ditandai dibaca.',
                'unread_count' => 0,
                'unread_label' => '0',
            ]);
        }

        return redirect()->back()->with('success', 'Seluruh notifikasi berhasil ditandai telah dibaca.');
    }

    /**
     * Clear / Delete all notifications
     */
    public function clearAll(): ResponseInterface
    {
        $userId = session()->get('user')['id'] ?? null;
        $this->notifModel->clearAll($userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Semua riwayat notifikasi telah dibersihkan.',
                'unread_count' => 0,
                'unread_label' => '0',
            ]);
        }

        return redirect()->back()->with('success', 'Semua riwayat notifikasi telah berhasil dibersihkan.');
    }

    /**
     * Delete single notification
     */
    public function delete(int $id)
    {
        $userId = session()->get('user')['id'] ?? null;
        $builder = $this->notifModel->builder()->where('id', $id);
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }
        $builder->delete();

        if ($this->request->isAJAX()) {
            $unreadCount = $this->notifService->getUnreadCount($userId);
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Notifikasi berhasil dihapus.',
                'unread_count' => $unreadCount,
                'unread_label' => $unreadCount > 99 ? '99+' : (string) $unreadCount,
            ]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}

