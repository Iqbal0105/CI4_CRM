<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeuanganAndCoaTables extends Migration
{
    public function up(): void
    {
        // 1. Chart of Accounts (COA)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['aset', 'kewajiban', 'ekuitas', 'pendapatan', 'beban'],
            ],
            'posisi_normal' => [
                'type'       => 'ENUM',
                'constraint' => ['debit', 'kredit'],
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
        $this->forge->addUniqueKey('kode_akun');
        $this->forge->createTable('coa', true);

        // 2. Akun Kas & Bank
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'nomor_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'atas_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'coa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'saldo' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['kas', 'bank', 'qris', 'petty_cash'],
                'default'    => 'kas',
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_akun');
        $this->forge->addForeignKey('coa_id', 'coa', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('akun_kas_bank', true);

        // 3. Jurnal Umum
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_jurnal' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'referensi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total_debit' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'total_kredit' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
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
        $this->forge->addUniqueKey('nomor_jurnal');
        $this->forge->addKey('tanggal');
        $this->forge->addKey('referensi');
        $this->forge->createTable('jurnal', true);

        // 4. Jurnal Detail
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jurnal_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'coa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'debit' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'kredit' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => '0.00',
            ],
            'memo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jurnal_id', 'jurnal', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('coa_id', 'coa', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('jurnal_detail', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('jurnal_detail', true);
        $this->forge->dropTable('jurnal', true);
        $this->forge->dropTable('akun_kas_bank', true);
        $this->forge->dropTable('coa', true);
    }
}

