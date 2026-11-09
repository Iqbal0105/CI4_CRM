<?php

declare(strict_types=1);

namespace Modules\Master\Models;

use CodeIgniter\Model;

class KursModel extends Model
{
    protected $table            = 'kurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['mata_uang', 'nilai_kurs', 'tanggal'];
    protected $useTimestamps    = true;

    /**
     * Get latest exchange rate for a given currency code.
     */
    public function getLatestRate(string $currency): string
    {
        if (strtoupper($currency) === 'IDR') {
            return '1.000000';
        }

        $row = $this->where('mata_uang', strtoupper($currency))
            ->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        return $row ? (string) $row['nilai_kurs'] : '1.000000';
    }
}

