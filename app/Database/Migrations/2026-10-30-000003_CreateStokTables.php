<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStokTables extends Migration
{
    public function up(): void
    {
        // 1. Stok
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
                'default'    => '0.000',
            ],
            'reserved_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
                'default'    => '0.000',
            ],
            'lokasi_rak' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'RAK-UTAMA',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('produk_id');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('stok', true);

        // 2. Stok Log (Append-Only ledger!)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['in', 'out', 'adj'],
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
            ],
            'saldo_akhir' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
            ],
            'referensi_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'operator_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['produk_id', 'created_at']);
        $this->forge->addKey('referensi_dokumen');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('stok_log', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('stok_log', true);
        $this->forge->dropTable('stok', true);
    }
}

