<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelianAndUtangTables extends Migration
{
    public function up(): void
    {
        // 1. Purchase Order
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_po' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_po' => [
                'type' => 'DATE',
            ],
            'termin' => [
                'type'       => 'ENUM',
                'constraint' => ['tunai', 'tempo'],
                'default'    => 'tunai',
            ],
            'tempo_hari' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'jatuh_tempo' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'mata_uang' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'default'    => 'IDR',
            ],
            'nilai_kurs' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,6',
                'default'    => '1.000000',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'diskon' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'pajak' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'total_idr' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'approved', 'received', 'partial', 'cancelled'],
                'default'    => 'draft',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addUniqueKey('nomor_po');
        $this->forge->addKey(['supplier_id', 'status']);
        $this->forge->addKey('tanggal_po');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('purchase_order', true);

        // 2. Purchase Order Items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'po_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
            ],
            'qty_diterima' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,3',
                'default'    => '0.000',
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('po_id', 'purchase_order', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('purchase_order_item', true);

        // 3. Utang Usaha
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_utang' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'po_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jatuh_tempo' => [
                'type' => 'DATE',
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
            'sisa' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['belum', 'sebagian', 'lunas'],
                'default'    => 'belum',
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
        $this->forge->addUniqueKey('nomor_utang');
        $this->forge->addForeignKey('po_id', 'purchase_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('utang_usaha', true);

        // 4. Pembayaran Utang (BKK)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_bkk' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'utang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'akun_kas_bank_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_bayar' => [
                'type' => 'DATE',
            ],
            'metode_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nomor_bkk');
        $this->forge->addForeignKey('utang_id', 'utang_usaha', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('akun_kas_bank_id', 'akun_kas_bank', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('pembayaran_utang', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('pembayaran_utang', true);
        $this->forge->dropTable('utang_usaha', true);
        $this->forge->dropTable('purchase_order_item', true);
        $this->forge->dropTable('purchase_order', true);
    }
}

