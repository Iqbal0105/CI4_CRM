<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReturTables extends Migration
{
    public function up(): void
    {
        // 1. Retur Penjualan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_retur' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'so_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pelanggan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'tindak_lanjut' => [
                'type'       => 'ENUM',
                'constraint' => ['ganti_barang', 'potong_piutang', 'refund'],
            ],
            'kondisi_barang' => [
                'type'       => 'ENUM',
                'constraint' => ['baik', 'rusak'],
                'default'    => 'baik',
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'approved', 'completed'],
                'default'    => 'draft',
            ],
            'alasan' => [
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
        $this->forge->addUniqueKey('nomor_retur');
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('retur_penjualan', true);

        // 2. Retur Penjualan Item
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'retur_id' => [
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
        $this->forge->addForeignKey('retur_id', 'retur_penjualan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('retur_penjualan_item', true);

        // 3. Retur Pembelian
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_retur' => [
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
            'tindak_lanjut' => [
                'type'       => 'ENUM',
                'constraint' => ['potong_utang', 'refund_kas'],
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'approved', 'completed'],
                'default'    => 'draft',
            ],
            'alasan' => [
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
        $this->forge->addUniqueKey('nomor_retur');
        $this->forge->addForeignKey('po_id', 'purchase_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('retur_pembelian', true);

        // 4. Retur Pembelian Item
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'retur_id' => [
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
        $this->forge->addForeignKey('retur_id', 'retur_pembelian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('retur_pembelian_item', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('retur_pembelian_item', true);
        $this->forge->dropTable('retur_pembelian', true);
        $this->forge->dropTable('retur_penjualan_item', true);
        $this->forge->dropTable('retur_penjualan', true);
    }
}

