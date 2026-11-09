<?php

declare(strict_types=1);

namespace Modules\Auth\Models;

use CodeIgniter\Model;
use Modules\Auth\Entities\User;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = User::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'phone',
        'avatar',
        'password_hash',
        'role_id',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [];

    /**
     * Find user with joined role data.
     */
    public function findWithRole(string $identifier): ?array
    {
        return $this->builder()
            ->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.deleted_at IS NULL')
            ->groupStart()
                ->where('users.username', $identifier)
                ->orWhere('users.email', $identifier)
            ->groupEnd()
            ->get()
            ->getRowArray();
    }

    /**
     * Find user by ID with joined role data.
     */
    public function findByIdWithRole(int $id): ?array
    {
        return $this->builder()
            ->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->where('users.deleted_at IS NULL')
            ->where('users.id', $id)
            ->get()
            ->getRowArray();
    }
}

