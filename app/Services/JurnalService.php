<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\JurnalTidakSeimbangException;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

class JurnalService
{
    protected BaseConnection $db;
    protected NomorUrutService $nomorUrutService;

    public function __construct(?BaseConnection $db = null, ?NomorUrutService $nomorUrutService = null)
    {
        $this->db = $db ?? Database::connect();
        $this->nomorUrutService = $nomorUrutService ?? new NomorUrutService($this->db);
    }

    /**
     * Create double-entry bookkeeping journal entries.
     * Must be called within an active database transaction.
     *
     * @param string $tanggal Y-m-d
     * @param string $referensi Document number (e.g., TRX-..., PO-..., BKM-..., BKK-...)
     * @param string $keterangan Transaction description
     * @param array $details List of items: [ ['coa_kode' => '1110', 'debit' => 50000, 'kredit' => 0, 'memo' => '...'], ... ]
     * @return int Created Jurnal ID
     * @throws JurnalTidakSeimbangException
     */
    public function catat(string $tanggal, string $referensi, string $keterangan, array $details): int
    {
        $totalDebit = '0.00';
        $totalKredit = '0.00';

        // Prepare details and calculate totals using bcmath
        $formattedDetails = [];
        foreach ($details as $item) {
            $debit = money_round($item['debit'] ?? 0);
            $kredit = money_round($item['kredit'] ?? 0);

            $totalDebit = money_add($totalDebit, $debit);
            $totalKredit = money_add($totalKredit, $kredit);

            // Lookup COA ID by kode_akun or coa_id
            $coaId = $item['coa_id'] ?? null;
            if (!$coaId && !empty($item['coa_kode'])) {
                $coaRow = $this->db->table('coa')->where('kode_akun', $item['coa_kode'])->get()->getRow();
                if ($coaRow) {
                    $coaId = (int) $coaRow->id;
                }
            }

            if (!$coaId) {
                throw new \InvalidArgumentException('Akun COA tidak ditemukan untuk entri jurnal: ' . ($item['coa_kode'] ?? 'null'));
            }

            $formattedDetails[] = [
                'coa_id' => $coaId,
                'debit'  => $debit,
                'kredit' => $kredit,
                'memo'   => $item['memo'] ?? $keterangan,
            ];
        }

        // Validate double-entry balance: total debit must equal total credit
        if (bccomp($totalDebit, $totalKredit, 2) !== 0) {
            throw new JurnalTidakSeimbangException(sprintf(
                'Jurnal tidak seimbang! Total Debit (%s) tidak sama dengan Total Kredit (%s)',
                format_rupiah($totalDebit),
                format_rupiah($totalKredit)
            ));
        }

        $nomorJurnal = $this->nomorUrutService->generate('JRN', $tanggal);
        $user = session()->get('user');
        $userId = $user['id'] ?? null;

        $this->db->table('jurnal')->insert([
            'nomor_jurnal' => $nomorJurnal,
            'tanggal'      => $tanggal,
            'referensi'    => $referensi,
            'keterangan'   => $keterangan,
            'total_debit'  => $totalDebit,
            'total_kredit' => $totalKredit,
            'user_id'      => $userId,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $jurnalId = (int) $this->db->insertID();

        foreach ($formattedDetails as $row) {
            $row['jurnal_id'] = $jurnalId;
            $this->db->table('jurnal_detail')->insert($row);
        }

        return $jurnalId;
    }
}

