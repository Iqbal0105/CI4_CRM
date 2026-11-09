<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMarketplaceTables extends Migration
{
    public function up(): void
    {
        // 1. Penjualan Marketplace
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_pesanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'source' => [
                'type'       => 'ENUM',
                'constraint' => ['shopee', 'tiktok', 'tokopedia', 'lazada'],
            ],
            'nomor_resi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'kurir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nama_penerima' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'no_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'alamat_penerima' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'is_cod' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'ongkir' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'voucher' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'biaya_layanan' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['unpaid', 'ready_to_ship', 'shipped', 'completed', 'cancelled', 'returned'],
                'default'    => 'ready_to_ship',
            ],
            'tanggal_pesanan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'so_id' => [
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
        // Idempotency constraint per SKILL 4.6
        $this->forge->addUniqueKey(['source', 'no_pesanan'], 'uq_marketplace_source_pesanan');
        $this->forge->addKey('nomor_resi');
        $this->forge->addForeignKey('so_id', 'sales_order', 'id', 'RESTRICT', 'SET NULL');
        $this->forge->createTable('penjualan_marketplace', true);

        // 2. Penjualan Marketplace Items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'penjualan_marketplace_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nama_produk_marketplace' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('sku');
        $this->forge->addForeignKey('penjualan_marketplace_id', 'penjualan_marketplace', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'RESTRICT', 'SET NULL');
        $this->forge->createTable('penjualan_marketplace_item', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('penjualan_marketplace_item', true);
        $this->forge->dropTable('penjualan_marketplace', true);
    }
}

