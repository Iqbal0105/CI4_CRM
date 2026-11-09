<?php

declare(strict_types=1);

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Modules\RFM\Services\RfmAnalysisService;

final class RfmAnalysisServiceTest extends CIUnitTestCase
{
    private RfmAnalysisService $rfmService;
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
        $this->db = Database::connect();
        $this->rfmService = new RfmAnalysisService($this->db);

        // Ensure we have customers with sales orders
        $pelanggan = $this->db->table('pelanggan')->where('kode_pelanggan', 'CUST-0001')->get()->getRow();
        if (!$pelanggan) {
            $this->db->table('pelanggan')->insert([
                'kode_pelanggan' => 'CUST-0001',
                'nama_pelanggan' => 'Pelanggan Champion Test',
                'no_telp'        => '081234567890',
                'alamat'         => 'Jl. Testing No. 1',
                'kota'           => 'Surabaya',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $pelangganId = (int) $this->db->insertID();
        } else {
            $pelangganId = (int) $pelanggan->id;
        }

        // Add 5 completed sales orders for this customer
        for ($i = 1; $i <= 5; $i++) {
            $this->db->table('sales_order')->insert([
                'nomor_so'       => 'SO-RFM-TEST-' . time() . '-' . $i . '-' . mt_rand(10, 99),
                'tanggal_so'     => date('Y-m-d', strtotime("-{$i} days")),
                'pelanggan_id'   => $pelangganId,
                'source'         => 'pos',
                'subtotal'       => '100000.00',
                'diskon_total'   => '0.00',
                'ppn'            => '0.00',
                'ongkir'         => '0.00',
                'total'          => '100000.00',
                'status'         => 'completed',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function testRfmCalculationProducesSegmentsAndMetrics(): void
    {
        $report = $this->rfmService->calculateRfm(365);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('customers', $report);
        $this->assertArrayHasKey('distribution', $report);
        $this->assertArrayHasKey('strategies', $report);

        $this->assertNotEmpty($report['customers']);

        $championFound = false;
        foreach ($report['customers'] as $cust) {
            $this->assertArrayHasKey('r_score', $cust);
            $this->assertArrayHasKey('f_score', $cust);
            $this->assertArrayHasKey('m_score', $cust);
            $this->assertArrayHasKey('segment', $cust);

            if ($cust['kode_pelanggan'] === 'CUST-0001') {
                $championFound = true;
                $this->assertGreaterThanOrEqual(5, $cust['frequency_count']);
                $this->assertGreaterThanOrEqual(500000.0, (float) $cust['monetary_total']);
                $this->assertSame(5, $cust['r_score']); // purchased within 1-5 days
            }
        }

        $this->assertTrue($championFound, 'Expected customer CUST-0001 to be analyzed.');
        $this->assertArrayHasKey('Champions', $report['distribution']);
        $this->assertArrayHasKey('Champions', $report['strategies']);
    }
}
