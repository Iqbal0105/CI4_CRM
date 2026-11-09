<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'title',
        'message',
        'category',
        'type',
        'icon',
        'url',
        'is_read',
        'read_at',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get unread notification count
     */
    public function getUnreadCount(?int $userId = null): int
    {
        $builder = $this->builder()->where('is_read', 0);
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }
        return $builder->countAllResults();
    }

    /**
     * Get notification feed
     */
    public function getFeed(?int $userId = null, int $limit = 15, ?string $category = null, ?int $isRead = null): array
    {
        $builder = $this->builder();
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }

        if ($category !== null && $category !== 'all' && $category !== '') {
            $builder->where('category', $category);
        }

        if ($isRead !== null) {
            $builder->where('is_read', $isRead);
        }

        return $builder->orderBy('id', 'DESC')->limit($limit)->get()->getResultArray();
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(int $id, ?int $userId = null): bool
    {
        $data = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
        ];

        $builder = $this->builder()->where('id', $id);
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }

        return $builder->update($data);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(?int $userId = null): bool
    {
        $data = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
        ];

        $builder = $this->builder()->where('is_read', 0);
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }

        return $builder->update($data);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(?int $userId = null): bool
    {
        $builder = $this->builder();
        if ($userId !== null) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }

        return $builder->delete();
    }
}

