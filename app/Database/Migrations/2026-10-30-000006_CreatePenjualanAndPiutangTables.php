<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanAndPiutangTables extends Migration
{
    public function up(): void
    {
        // 1. Sales Order
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_so' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tanggal_so' => [
                'type' => 'DATE',
            ],
            'source' => [
                'type'       => 'ENUM',
                'constraint' => ['pos', 'manual', 'shopee', 'tiktok', 'tokopedia', 'lazada'],
                'default'    => 'manual',
            ],
            'pelanggan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'diskon_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'ppn' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'ongkir' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'bayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'kembalian' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'metode_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'tunai',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled'],
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nomor_so');
        // Composite Index for frequent searches as specified in PRD
        $this->forge->addKey(['source', 'status', 'tanggal_so'], false, false, 'idx_so_source_status_tanggal');
        $this->forge->addKey('pelanggan_id');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('sales_order', true);

        // 2. Sales Order Items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'so_id' => [
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
            'diskon' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('sales_order_item', true);

        // 3. Invoice
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_invoice' => [
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
            'tanggal_invoice' => [
                'type' => 'DATE',
            ],
            'jatuh_tempo' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['paid', 'unpaid', 'cancelled'],
                'default'    => 'unpaid',
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
        $this->forge->addUniqueKey('nomor_invoice');
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('invoice', true);

        // 4. Piutang Usaha
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_piutang' => [
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
        $this->forge->addUniqueKey('nomor_piutang');
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('piutang_usaha', true);

        // 5. Pembayaran Piutang (BKM)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_bkm' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'piutang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'so_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pelanggan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addUniqueKey('nomor_bkm');
        $this->forge->addForeignKey('piutang_id', 'piutang_usaha', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('akun_kas_bank_id', 'akun_kas_bank', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('pembayaran_piutang', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('pembayaran_piutang', true);
        $this->forge->dropTable('piutang_usaha', true);
        $this->forge->dropTable('invoice', true);
        $this->forge->dropTable('sales_order_item', true);
        $this->forge->dropTable('sales_order', true);
    }
}

