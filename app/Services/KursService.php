<?php

declare(strict_types=1);

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Master\Models\KursModel;

class KursService
{
    protected BaseConnection $db;
    protected KursModel $kursModel;
    protected AuditService $auditService;

    /**
     * Supported foreign currencies.
     */
    public const SUPPORTED_CURRENCIES = ['USD', 'SGD', 'CNY', 'EUR'];

    public function __construct(?BaseConnection $db = null, ?KursModel $kursModel = null, ?AuditService $auditService = null)
    {
        $this->db = $db ?? Database::connect();
        $this->kursModel = $kursModel ?? new KursModel();
        $this->auditService = $auditService ?? new AuditService($this->db);
    }

    /**
     * Fetch live accurate rates from free open exchange rate API and update kurs table.
     * Uses open.er-api.com (No API Key required, live central bank / market data).
     *
     * @param array $currencies
     * @return array{success: bool, updated: array<string, float>, errors: array<string, string>, message: string}
     */
    public function syncRatesFromApi(array $currencies = self::SUPPORTED_CURRENCIES): array
    {
        $updated = [];
        $errors = [];
        $today = date('Y-m-d');

        foreach ($currencies as $curr) {
            $curr = strtoupper(trim($curr));
            if ($curr === 'IDR') {
                continue;
            }

            $url = "https://open.er-api.com/v6/latest/{$curr}";
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 6,
                    'header'  => "User-Agent: IDMETAFORA-ERP-KursSync/1.0\r\nAccept: application/json\r\n",
                ],
            ]);

            $response = @file_get_contents($url, false, $ctx);
            if ($response === false) {
                $errors[$curr] = "Koneksi ke API gagal atau timeout.";
                continue;
            }

            $data = json_decode($response, true);
            if (!isset($data['result']) || $data['result'] !== 'success' || !isset($data['rates']['IDR'])) {
                $errors[$curr] = "Format data API tidak valid atau kurs IDR tidak ditemukan.";
                continue;
            }

            $rateVal = (float) $data['rates']['IDR'];
            $rateStr = number_format($rateVal, 6, '.', '');

            // Insert into kurs table
            $insertData = [
                'mata_uang'  => $curr,
                'nilai_kurs' => $rateStr,
                'tanggal'    => $today,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->kursModel->insert($insertData);
            $kursId = $this->kursModel->getInsertID();

            $updated[$curr] = $rateVal;

            // Audit log
            $this->auditService->log('api_sync', 'kurs', (int) $kursId, null, [
                'mata_uang'  => $curr,
                'nilai_kurs' => $rateStr,
                'source'     => 'open.er-api.com',
            ]);
        }

        $allSuccess = count($errors) === 0 && count($updated) > 0;
        $message = $allSuccess
            ? 'Kurs seluruh mata uang asing berhasil diperbarui dari API secara akurat.'
            : (count($updated) > 0
                ? 'Sebagian kurs berhasil disinkronisasi: ' . implode(', ', array_keys($updated))
                : 'Gagal memperbarui kurs dari API.');

        return [
            'success' => count($updated) > 0,
            'updated' => $updated,
            'errors'  => $errors,
            'message' => $message,
        ];
    }

    /**
     * Get associative array of latest rates.
     *
     * @return array<string, array{nilai_kurs: float, formatted: string, tanggal: string}>
     */
    public function getLatestRates(): array
    {
        $rates = [];
        foreach (self::SUPPORTED_CURRENCIES as $curr) {
            $row = $this->kursModel->where('mata_uang', $curr)
                ->orderBy('tanggal', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();

            if ($row) {
                $val = (float) $row['nilai_kurs'];
                $rates[$curr] = [
                    'nilai_kurs' => $val,
                    'formatted'  => 'Rp ' . number_format($val, 2, ',', '.'),
                    'tanggal'    => $row['tanggal'],
                ];
            } else {
                $rates[$curr] = [
                    'nilai_kurs' => 1.0,
                    'formatted'  => 'Belum diatur',
                    'tanggal'    => null,
                ];
            }
        }

        return $rates;
    }

    /**
     * Get single rate for currency.
     */
    public function getRate(string $currency): float
    {
        $curr = strtoupper(trim($currency));
        if ($curr === 'IDR' || $curr === '') {
            return 1.0;
        }

        $row = $this->kursModel->where('mata_uang', $curr)
            ->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        return $row ? (float) $row['nilai_kurs'] : 1.0;
    }

    /**
     * Convert amount from given currency to IDR with exact scale.
     */
    public function convertToIdr(string|float|int $amount, string $currency, ?float $customRate = null): string
    {
        $rate = $customRate ?? $this->getRate($currency);
        return money_mul($amount, (string) $rate, 2);
    }
}

