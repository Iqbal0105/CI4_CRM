<?php

declare(strict_types=1);

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use Config\Database;
use RuntimeException;

class NomorUrutService
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Generate an atomic, race-condition-free sequential document number.
     * Must be called within an active database transaction.
     *
     * Example outputs: PO-202610-0001, TRX-202610-0001, SO-202610-0001, BKK-202611-0001
     *
     * @param string $kodeDokumen (PO, TRX, SO, INV, BKM, BKK, UTG, PIU, RET_JUAL, RET_BELI, JRN)
     * @param string|null $date Y-m-d format (defaults to current date)
     * @param int $padding Pad length (default 4 digits: 0001)
     * @return string
     */
    public function generate(string $kodeDokumen, ?string $date = null, int $padding = 4): string
    {
        $dateObj = $date ? strtotime($date) : time();
        $periode = date('Ym', $dateObj);

        $tableMappings = [
            'BKK'      => ['table' => 'pembayaran_utang',   'column' => 'nomor_bkk'],
            'BKM'      => ['table' => 'pembayaran_piutang', 'column' => 'nomor_bkm'],
            'INV'      => ['table' => 'invoice',            'column' => 'nomor_invoice'],
            'SO'       => ['table' => 'sales_order',        'column' => 'nomor_so'],
            'PO'       => ['table' => 'purchase_order',     'column' => 'nomor_po'],
            'TRX'      => ['table' => 'sales_order',        'column' => 'nomor_so'],
            'UTG'      => ['table' => 'utang_usaha',        'column' => 'nomor_utang'],
            'AP'       => ['table' => 'utang_usaha',        'column' => 'nomor_utang'],
            'PIU'      => ['table' => 'piutang_usaha',      'column' => 'nomor_piutang'],
            'RET_JUAL' => ['table' => 'retur_penjualan',    'column' => 'nomor_retur'],
            'RET_BELI' => ['table' => 'retur_pembelian',    'column' => 'nomor_retur'],
            'JRN'      => ['table' => 'jurnal',             'column' => 'nomor_jurnal'],
            'CUST'     => ['table' => 'pelanggan',          'column' => 'kode_pelanggan'],
            'PRD'      => ['table' => 'produk',             'column' => 'kode_produk'],
            'SUP'      => ['table' => 'supplier',           'column' => 'kode_supplier'],
        ];

        $prefix = "{$kodeDokumen}-{$periode}-";
        $maxNum = 0;

        // Query real highest sequence from target table if it exists
        if (isset($tableMappings[$kodeDokumen])) {
            $tbl = $tableMappings[$kodeDokumen]['table'];
            $col = $tableMappings[$kodeDokumen]['column'];

            if ($this->db->tableExists($tbl)) {
                $lastDocs = $this->db->table($tbl)
                    ->select($col)
                    ->like($col, $prefix, 'after')
                    ->orderBy($col, 'DESC')
                    ->limit(20)
                    ->get()
                    ->getResultArray();

                foreach ($lastDocs as $doc) {
                    $val = (string) ($doc[$col] ?? '');
                    if (preg_match('/-(\d+)$/', $val, $matches)) {
                        $num = (int) $matches[1];
                        if ($num > $maxNum) {
                            $maxNum = $num;
                        }
                    }
                }
            }
        }

        $builder = $this->db->table('nomor_urut');

        // Check tracked sequence in nomor_urut table
        $row = $builder->where('kode_dokumen', $kodeDokumen)
            ->where('periode', $periode)
            ->get(null, 0, false)
            ->getRow();

        $lastSeq = $row ? (int) $row->nomor_terakhir : 0;
        $highestKnown = max($lastSeq, $maxNum);
        $nextNumber = $highestKnown + 1;

        // Collision safety check: ensure candidate number does not exist in target table
        if (isset($tableMappings[$kodeDokumen])) {
            $tbl = $tableMappings[$kodeDokumen]['table'];
            $col = $tableMappings[$kodeDokumen]['column'];

            if ($this->db->tableExists($tbl)) {
                while (true) {
                    $candidate = sprintf('%s-%s-%s', strtoupper($kodeDokumen), $periode, str_pad((string) $nextNumber, $padding, '0', STR_PAD_LEFT));
                    $exists = $this->db->table($tbl)->where($col, $candidate)->countAllResults();
                    if ($exists === 0) {
                        break;
                    }
                    $nextNumber++;
                }
            }
        }

        if ($row) {
            $builder->where('id', $row->id)->update([
                'nomor_terakhir' => $nextNumber,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        } else {
            $builder->insert([
                'kode_dokumen'   => $kodeDokumen,
                'periode'        => $periode,
                'nomor_terakhir' => $nextNumber,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        $paddedNumber = str_pad((string) $nextNumber, $padding, '0', STR_PAD_LEFT);
        return sprintf('%s-%s-%s', strtoupper($kodeDokumen), $periode, $paddedNumber);
    }
}

