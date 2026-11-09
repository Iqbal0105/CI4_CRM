<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterDataTables extends Migration
{
    public function up(): void
    {
        // 1. Kategori Produk
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
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
        $this->forge->addUniqueKey('kode_kategori');
        $this->forge->createTable('kategori_produk', true);

        // 2. Satuan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nama_satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addUniqueKey('kode_satuan');
        $this->forge->createTable('satuan', true);

        // 3. Produk
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'barcode' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nama_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'satuan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'harga_beli' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'harga_jual' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'harga_grosir' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'harga_distributor' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'stok_minimum' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
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
        $this->forge->addUniqueKey('kode_produk');
        $this->forge->addKey('barcode');
        $this->forge->addForeignKey('kategori_id', 'kategori_produk', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('satuan_id', 'satuan', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('produk', true);

        // 4. Pelanggan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_pelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_pelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'no_telp' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'tipe_pelanggan' => [
                'type'       => 'ENUM',
                'constraint' => ['retail', 'grosir', 'distributor'],
                'default'    => 'retail',
            ],
            'limit_kredit' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'tempo_hari' => [
                'type'       => 'INT',
                'constraint' => 11,
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_pelanggan');
        $this->forge->addKey('no_telp');
        $this->forge->createTable('pelanggan', true);

        // 5. Supplier
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_supplier' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_supplier' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'no_telp' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'mata_uang' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'default'    => 'IDR',
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
        $this->forge->addUniqueKey('kode_supplier');
        $this->forge->createTable('supplier', true);

        // 6. Kurs
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mata_uang' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
            ],
            'nilai_kurs' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,6',
                'default'    => '1.000000',
            ],
            'tanggal' => [
                'type' => 'DATE',
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
        $this->forge->addKey(['mata_uang', 'tanggal']);
        $this->forge->createTable('kurs', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('kurs', true);
        $this->forge->dropTable('supplier', true);
        $this->forge->dropTable('pelanggan', true);
        $this->forge->dropTable('produk', true);
        $this->forge->dropTable('satuan', true);
        $this->forge->dropTable('kategori_produk', true);
    }
}

