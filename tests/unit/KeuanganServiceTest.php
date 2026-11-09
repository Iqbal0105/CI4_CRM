<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\PembayaranMelebihiSisaException;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Modules\Keuangan\Services\KeuanganService;

final class KeuanganServiceTest extends CIUnitTestCase
{
    private KeuanganService $keuanganService;
    protected $db;
    private int $testKasBankId;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->keuanganService = new KeuanganService($this->db);

        // Fetch or create a test cash bank account
        $kas = $this->db->table('akun_kas_bank')->where('kode_akun', 'KAS-KASIR')->get()->getRow();
        if (!$kas) {
            $this->db->table('akun_kas_bank')->insert([
                'kode_akun'       => 'KAS-TEST',
                'nama_akun'       => 'Kas Operasional Test',
                'nomor_rekening'  => '-',
                'saldo'           => '5000000.00',
                'coa_id'          => 1, // Kas
                'status'          => 'active',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $this->testKasBankId = (int) $this->db->insertID();
        } else {
            $this->testKasBankId = (int) $kas->id;
        }
    }

    public function testBayarPiutangSuccessAndBalancedJournal(): void
    {
        // 0. Create a dummy sales order
        $this->db->table('sales_order')->insert([
            'nomor_so'       => 'SO-PAY-TEST-' . time(),
            'tanggal_so'     => date('Y-m-d'),
            'pelanggan_id'   => 1,
            'source'         => 'manual',
            'subtotal'       => '500000.00',
            'diskon_total'   => '0.00',
            'ppn'            => '0.00',
            'ongkir'         => '0.00',
            'total'          => '500000.00',
            'status'         => 'confirmed',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $soId = (int) $this->db->insertID();

        // 1. Create a dummy piutang
        $this->db->table('piutang_usaha')->insert([
            'nomor_piutang'  => 'PIUT-TEST-' . time(),
            'so_id'          => $soId,
            'pelanggan_id'   => 1,
            'tanggal'        => date('Y-m-d'),
            'total'          => '500000.00',
            'sisa'           => '500000.00',
            'status'         => 'belum',
            'jatuh_tempo'    => date('Y-m-d', strtotime('+30 days')),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $piutangId = (int) $this->db->insertID();

        // 2. Pay 200,000
        $bkmNo = $this->keuanganService->bayarPiutang(
            $piutangId,
            $this->testKasBankId,
            '200000.00',
            date('Y-m-d'),
            'tunai',
            'Cicilan piutang pertama'
        );

        $this->assertNotEmpty($bkmNo);
        $this->assertStringStartsWith('BKM-', $bkmNo);

        // Verify remaining piutang is 300,000 and status 'sebagian'
        $piutangRow = $this->db->table('piutang_usaha')->where('id', $piutangId)->get()->getRow();
        $this->assertEquals('300000.00', (string) $piutangRow->sisa);
        $this->assertSame('sebagian', $piutangRow->status);

        // Verify journal entry created and balanced
        $jurnalRow = $this->db->table('jurnal')->where('referensi', $bkmNo)->get()->getRow();
        $this->assertNotNull($jurnalRow);
        $this->assertEquals('200000.00', (string) $jurnalRow->total_debit);
        $this->assertEquals('200000.00', (string) $jurnalRow->total_kredit);
    }

    public function testBayarPiutangExceedingBalanceThrowsException(): void
    {
        $this->expectException(PembayaranMelebihiSisaException::class);

        $this->db->table('sales_order')->insert([
            'nomor_so'       => 'SO-EXC-TEST-' . time(),
            'tanggal_so'     => date('Y-m-d'),
            'pelanggan_id'   => 1,
            'source'         => 'manual',
            'subtotal'       => '100000.00',
            'diskon_total'   => '0.00',
            'ppn'            => '0.00',
            'ongkir'         => '0.00',
            'total'          => '100000.00',
            'status'         => 'confirmed',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $soId = (int) $this->db->insertID();

        $this->db->table('piutang_usaha')->insert([
            'nomor_piutang'  => 'PIUT-EXCEED-' . time(),
            'so_id'          => $soId,
            'pelanggan_id'   => 1,
            'tanggal'        => date('Y-m-d'),
            'total'          => '100000.00',
            'sisa'           => '100000.00',
            'status'         => 'belum',
            'jatuh_tempo'    => date('Y-m-d', strtotime('+30 days')),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $piutangId = (int) $this->db->insertID();

        // Attempt to pay 150,000 on a 100,000 debt
        $this->keuanganService->bayarPiutang(
            $piutangId,
            $this->testKasBankId,
            '150000.00',
            date('Y-m-d'),
            'tunai'
        );
    }

    public function testBayarUtangSuccessAndBalancedJournal(): void
    {
        $supplier = $this->db->table('supplier')->get()->getRow();
        if (!$supplier) {
            $this->db->table('supplier')->insert([
                'kode_supplier' => 'SUP-TEST-01',
                'nama_supplier' => 'PT Test Supplier Indonesia',
                'no_telp'       => '0219876543',
                'alamat'        => 'Jl. Industri No. 10',
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            $supplierId = (int) $this->db->insertID();
        } else {
            $supplierId = (int) $supplier->id;
        }

        // 0. Create dummy purchase order
        $this->db->table('purchase_order')->insert([
            'nomor_po'       => 'PO-PAY-TEST-' . time(),
            'tanggal_po'     => date('Y-m-d'),
            'supplier_id'    => $supplierId,
            'subtotal'       => '1000000.00',
            'diskon'         => '0.00',
            'pajak'          => '0.00',
            'total'          => '1000000.00',
            'total_idr'      => '1000000.00',
            'status'         => 'received',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $poId = (int) $this->db->insertID();

        // 1. Create a dummy utang
        $this->db->table('utang_usaha')->insert([
            'nomor_utang'    => 'UTANG-TEST-' . time(),
            'po_id'          => $poId,
            'supplier_id'    => $supplierId,
            'tanggal'        => date('Y-m-d'),
            'total'          => '1000000.00',
            'sisa'           => '1000000.00',
            'status'         => 'belum',
            'jatuh_tempo'    => date('Y-m-d', strtotime('+14 days')),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $utangId = (int) $this->db->insertID();

        // 2. Pay in full 1,000,000
        $bkkNo = $this->keuanganService->bayarUtang(
            $utangId,
            $this->testKasBankId,
            '1000000.00',
            date('Y-m-d'),
            'transfer',
            'Pelunasan utang supplier'
        );

        $this->assertNotEmpty($bkkNo);
        $this->assertStringStartsWith('BKK-', $bkkNo);

        // Verify remaining utang is 0.00 and status 'lunas'
        $utangRow = $this->db->table('utang_usaha')->where('id', $utangId)->get()->getRow();
        $this->assertEquals('0.00', (string) $utangRow->sisa);
        $this->assertSame('lunas', $utangRow->status);

        // Verify journal entry created and balanced
        $jurnalRow = $this->db->table('jurnal')->where('referensi', $bkkNo)->get()->getRow();
        $this->assertNotNull($jurnalRow);
        $this->assertEquals('1000000.00', (string) $jurnalRow->total_debit);
        $this->assertEquals('1000000.00', (string) $jurnalRow->total_kredit);
    }
}
