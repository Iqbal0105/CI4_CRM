<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\JurnalTidakSeimbangException;
use App\Services\JurnalService;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class JurnalServiceTest extends CIUnitTestCase
{
    private JurnalService $jurnalService;
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->jurnalService = new JurnalService($this->db);
    }

    public function testBalancedJournalSucceeds(): void
    {
        // 1110 = Kas, 4110 = Pendapatan Penjualan
        $details = [
            [
                'coa_kode' => '1110',
                'debit'    => '150000.00',
                'kredit'   => '0.00',
                'memo'     => 'Penerimaan Kas Kasir',
            ],
            [
                'coa_kode' => '4110',
                'debit'    => '0.00',
                'kredit'   => '150000.00',
                'memo'     => 'Pendapatan Kasir',
            ],
        ];

        $jurnalId = $this->jurnalService->catat(
            date('Y-m-d'),
            'TRX-TEST-001',
            'Penjualan Kasir POS',
            $details
        );

        $this->assertGreaterThan(0, $jurnalId);

        $jurnalRow = $this->db->table('jurnal')->where('id', $jurnalId)->get()->getRow();
        $this->assertNotNull($jurnalRow);
        $this->assertSame('TRX-TEST-001', $jurnalRow->referensi);
        $this->assertEquals('150000.00', (string) $jurnalRow->total_debit);
        $this->assertEquals('150000.00', (string) $jurnalRow->total_kredit);

        $detailRows = $this->db->table('jurnal_detail')->where('jurnal_id', $jurnalId)->get()->getResult();
        $this->assertCount(2, $detailRows);
    }

    public function testUnbalancedJournalThrowsException(): void
    {
        $this->expectException(JurnalTidakSeimbangException::class);

        // Unbalanced: Debit 100000, Kredit 90000
        $details = [
            [
                'coa_kode' => '1110',
                'debit'    => '100000.00',
                'kredit'   => '0.00',
            ],
            [
                'coa_kode' => '4110',
                'debit'    => '0.00',
                'kredit'   => '90000.00',
            ],
        ];

        $this->jurnalService->catat(
            date('Y-m-d'),
            'TRX-TEST-UNBALANCED',
            'Testing Unbalanced Journal',
            $details
        );
    }
}

