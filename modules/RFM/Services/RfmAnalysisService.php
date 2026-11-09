<?php

declare(strict_types=1);

namespace Modules\RFM\Services;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class RfmAnalysisService
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Compute aggregated RFM scores and segments for all customers.
     *
     * @param int $daysWindow Time window in days (default 365)
     * @param array $filters Optional filters: 'kategori', 'produk', 'bulan', 'tahun'
     * @return array [ 'customers' => array, 'distribution' => array, 'strategies' => array ]
     */
    public function calculateRfm(int $daysWindow = 365, array $filters = []): array
    {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysWindow} days"));
        $params     = [];
        $joinExtra  = "";
        $whereExtra = " WHERE so.status != 'cancelled' AND p.deleted_at IS NULL ";

        // Tentukan tanggal acuan untuk perhitungan Recency
        $refDate = 'CURDATE()';

        // 1. Filter Bulan & Tahun
        if (!empty($filters['bulan'])) {
            $b = $filters['bulan'];
            if (str_contains((string) $b, '-')) {
                $whereExtra .= " AND DATE_FORMAT(so.tanggal_so, '%Y-%m') = ? ";
                $params[] = $b;
                $refDate = "'" . date('Y-m-t', strtotime("{$b}-01")) . "'";
            } else {
                $bNum = (int) $b;
                $yNum = !empty($filters['tahun']) ? (int) $filters['tahun'] : 2026;
                $whereExtra .= " AND MONTH(so.tanggal_so) = ? ";
                $params[] = $bNum;
                if (!empty($filters['tahun'])) {
                    $whereExtra .= " AND YEAR(so.tanggal_so) = ? ";
                    $params[] = $yNum;
                }
                if ($bNum >= 1 && $bNum <= 12) {
                    $refDate = "'" . date('Y-m-t', strtotime("{$yNum}-" . str_pad((string)$bNum, 2, '0', STR_PAD_LEFT) . "-01")) . "'";
                }
            }
        } elseif (!empty($filters['tahun'])) {
            $yNum = (int)$filters['tahun'];
            $whereExtra .= " AND YEAR(so.tanggal_so) = ? ";
            $params[] = $yNum;
            $currentYear = (int)date('Y');
            if ($yNum < $currentYear) {
                $refDate = "'{$yNum}-12-31'";
            } else {
                $refDate = 'CURDATE()';
            }
        } else {
            $whereExtra .= " AND so.tanggal_so >= ? ";
            $params[] = $cutoffDate;
        }

        // 2. Filter Produk / Kategori
        $needItemJoin = false;
        if (!empty($filters['produk'])) {
            $needItemJoin = true;
            if (is_numeric($filters['produk'])) {
                $whereExtra .= " AND (soi.produk_id = ? OR pr.id = ?) ";
                $params[] = (int) $filters['produk'];
                $params[] = (int) $filters['produk'];
            } else {
                $whereExtra .= " AND (pr.nama_produk LIKE ? OR pr.kode_produk LIKE ?) ";
                $params[] = '%' . $filters['produk'] . '%';
                $params[] = '%' . $filters['produk'] . '%';
            }
        }

        if (!empty($filters['kategori'])) {
            $needItemJoin = true;
            if (is_numeric($filters['kategori'])) {
                $whereExtra .= " AND pr.kategori_id = ? ";
                $params[] = (int) $filters['kategori'];
            } else {
                $kat = strtolower(trim($filters['kategori']));
                $katMap = [
                    'fnb'        => ['Makanan & Minuman', 'FNB'],
                    'elektronik' => ['Elektronik & Gadget', 'ELEKTRONIK'],
                    'fashion'    => ['Pakaian & Aksesoris', 'FASHION'],
                    'kesehatan'  => ['Kesehatan & Kecantikan', 'KESEHATAN'],
                    'umum'       => ['Kategori Umum', 'UMUM'],
                ];
                if (isset($katMap[$kat])) {
                    $whereExtra .= " AND (kp.kode_kategori IN (?, ?) OR kp.nama_kategori IN (?, ?)) ";
                    $params[] = $katMap[$kat][1];
                    $params[] = $katMap[$kat][0];
                    $params[] = $katMap[$kat][1];
                    $params[] = $katMap[$kat][0];
                } else {
                    $whereExtra .= " AND (kp.nama_kategori LIKE ? OR kp.kode_kategori LIKE ?) ";
                    $params[] = '%' . $filters['kategori'] . '%';
                    $params[] = '%' . $filters['kategori'] . '%';
                }
            }
        }

        $monetaryCol = "COALESCE(SUM(so.total), 0)";
        if ($needItemJoin) {
            $joinExtra .= " JOIN sales_order_item soi ON soi.so_id = so.id 
                            JOIN produk pr ON pr.id = soi.produk_id 
                            LEFT JOIN kategori_produk kp ON kp.id = pr.kategori_id ";
            $monetaryCol = "COALESCE(SUM(soi.subtotal), SUM(so.total), 0)";
        }

        $sql = "
            SELECT 
                p.id,
                p.kode_pelanggan,
                p.nama_pelanggan,
                p.no_telp,
                p.email,
                p.kota,
                DATEDIFF({$refDate}, MAX(so.tanggal_so)) AS recency_days,
                COUNT(DISTINCT so.id) AS frequency_count,
                {$monetaryCol} AS monetary_total,
                MAX(so.tanggal_so) AS last_order_date
            FROM pelanggan p
            JOIN sales_order so ON so.pelanggan_id = p.id
            {$joinExtra}
            {$whereExtra}
            GROUP BY p.id
            ORDER BY monetary_total DESC
        ";

        $results = $this->db->query($sql, $params)->getResultArray();

        $customers = [];
        $distribution = [
            'Champions'          => 0,
            'Loyal Customers'    => 0,
            'Potential Loyalist' => 0,
            'New Customers'      => 0,
            'Big Spenders'       => 0,
            'Frequent Low Value' => 0,
            'Need Attention'     => 0,
            'At Risk Customers'  => 0,
            "Can't Lose Them"    => 0,
            'Hibernating'        => 0,
        ];

        foreach ($results as $row) {
            $rDays = max(0, (int) ($row['recency_days'] ?? 999));
            $fCount = (int) ($row['frequency_count'] ?? 0);
            $mTotal = (float) ($row['monetary_total'] ?? 0);

            // 1. Recency Quintile (1 - 5)
            $rScore = match (true) {
                $rDays <= 15 => 5,
                $rDays <= 30 => 4,
                $rDays <= 60 => 3,
                $rDays <= 120 => 2,
                default      => 1,
            };

            // 2. Frequency Quintile (1 - 5)
            $fScore = match (true) {
                $fCount >= 8 => 5,
                $fCount >= 5 => 4,
                $fCount >= 3 => 3,
                $fCount >= 2 => 2,
                default      => 1,
            };

            // 3. Monetary Quintile (1 - 5)
            $mScore = match (true) {
                $mTotal >= 10000000 => 5,
                $mTotal >= 5000000  => 4,
                $mTotal >= 2000000  => 3,
                $mTotal >= 500000   => 2,
                default             => 1,
            };

            // 4. Map Segment according to SKILL 4.8
            $segment = $this->determineSegment($rScore, $fScore, $mScore);
            $distribution[$segment] = ($distribution[$segment] ?? 0) + 1;

            $customers[] = [
                'id'              => (int) $row['id'],
                'kode_pelanggan'  => $row['kode_pelanggan'],
                'nama_pelanggan'  => $row['nama_pelanggan'],
                'no_telp'         => $row['no_telp'],
                'email'           => $row['email'] ?? null,
                'kota'            => $row['kota'],
                'recency_days'    => $rDays,
                'frequency_count' => $fCount,
                'monetary_total'  => $mTotal,
                'last_order_date' => $row['last_order_date'],
                'r_score'         => $rScore,
                'f_score'         => $fScore,
                'm_score'         => $mScore,
                'rfm_score'       => "{$rScore}{$fScore}{$mScore}",
                'segment'         => $segment,
            ];
        }

        // Fetch segment strategies from rfm_segments table
        $strategies = $this->db->table('rfm_segments')->get()->getResultArray();
        $strategyMap = [];
        foreach ($strategies as $strat) {
            $strategyMap[$strat['segment_name']] = $strat;
        }

        return [
            'customers'    => $customers,
            'distribution' => $distribution,
            'strategies'   => $strategyMap,
        ];
    }

    /**
     * Determine segment from R, F, M quintiles (10 Segments standard).
     */
    public function determineSegment(int $r, int $f, int $m): string
    {
        if ($r >= 4 && $f >= 4 && $m >= 4) {
            return 'Champions';
        }
        if ($r <= 2 && $f >= 4 && $m >= 4) {
            return "Can't Lose Them";
        }
        if ($r >= 3 && $f >= 3 && $m >= 3) {
            return 'Loyal Customers';
        }
        if ($m >= 4 && $r >= 3) {
            return 'Big Spenders';
        }
        if ($r >= 4 && $f >= 2) {
            return 'Potential Loyalist';
        }
        if ($r >= 4 && $f == 1) {
            return 'New Customers';
        }
        if ($f >= 3 && $m <= 2) {
            return 'Frequent Low Value';
        }
        if ($r <= 2 && $f >= 3) {
            return 'At Risk Customers';
        }
        if ($r <= 2 && $f <= 2 && $m <= 2) {
            return 'Hibernating';
        }
        return 'Need Attention';
    }
}

