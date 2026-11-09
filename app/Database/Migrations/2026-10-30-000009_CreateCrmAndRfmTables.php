<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCrmAndRfmTables extends Migration
{
    public function up(): void
    {
        // 1. CRM Transaction Followup Labels
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'order_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // pos, manual, shopee, tiktok, tokopedia
            ],
            'customer_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'label_code' => [
                'type'       => 'ENUM',
                'constraint' => ['H+0', 'H+3', 'H+7'],
            ],
            'target_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'sent', 'failed', 'cancelled'],
                'default'    => 'pending',
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'message_log' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'retry_count' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
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
        $this->forge->addKey('id', true);
        $this->forge->addKey(['target_date', 'status']);
        $this->forge->addKey(['order_source', 'order_id']);
        $this->forge->createTable('crm_transaction_followup_labels', true);

        // 2. CRM Followup Notes / Chat Log
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pelanggan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'label' => [
                'type'       => 'ENUM',
                'constraint' => ['New', 'Follow-up', 'Closed', 'Repeat Order'],
                'default'    => 'New',
            ],
            'catatan' => [
                'type' => 'TEXT',
            ],
            'direction' => [
                'type'       => 'ENUM',
                'constraint' => ['inbound', 'outbound'],
                'default'    => 'outbound',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('customer_phone');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('crm_followup_notes', true);

        // 3. RFM Segments Matrix & Strategy
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'segment_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'r_min' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'r_max' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'f_min' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'f_max' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'm_min' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'm_max' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'recommended_action' => [
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
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('segment_name');
        $this->forge->createTable('rfm_segments', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('rfm_segments', true);
        $this->forge->dropTable('crm_followup_notes', true);
        $this->forge->dropTable('crm_transaction_followup_labels', true);
    }
}

