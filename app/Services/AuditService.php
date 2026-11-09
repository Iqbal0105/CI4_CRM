<?php

declare(strict_types=1);

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class AuditService
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Record an audit trail log.
     */
    public function log(
        string $action,
        string $tableName,
        string|int $recordId,
        array|object|string|null $oldValues = null,
        array|object|string|null $newValues = null
    ): void {
        $user = session()->get('user');
        $userId = $user['id'] ?? null;

        $request = service('request');
        $ip = $request ? $request->getIPAddress() : '127.0.0.1';
        $agent = $request ? (string) $request->getUserAgent() : 'CLI';

        $this->db->table('audit_trail')->insert([
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => (string) $recordId,
            'old_values' => is_array($oldValues) || is_object($oldValues) ? json_encode($oldValues) : (string) $oldValues,
            'new_values' => is_array($newValues) || is_object($newValues) ? json_encode($newValues) : (string) $newValues,
            'ip_address' => $ip,
            'user_agent' => substr($agent, 0, 255),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

