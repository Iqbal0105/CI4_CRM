<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToCalonCustomer extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('calon_customer') && !$this->db->fieldExists('password_hash', 'calon_customer')) {
            $this->forge->addColumn('calon_customer', [
                'password_hash' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'username',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->tableExists('calon_customer') && $this->db->fieldExists('password_hash', 'calon_customer')) {
            $this->forge->dropColumn('calon_customer', 'password_hash');
        }
    }
}

