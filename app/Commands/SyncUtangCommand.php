<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Keuangan\Services\KeuanganService;

class SyncUtangCommand extends BaseCommand
{
    protected $group       = 'Keuangan';
    protected $name        = 'utang:sync';
    protected $description = 'Sinkronisasi seluruh Purchase Order (PO) ke Buku Utang Usaha (Accounts Payable / AP).';

    public function run(array $params)
    {
        CLI::write("Menjalankan sinkronisasi data PO ke Utang Usaha (AP)...", 'cyan');

        $keuanganService = new KeuanganService();
        $synced = $keuanganService->syncMissingUtangFromPO();

        if ($synced > 0) {
            CLI::write("✔ Berhasil menyinkronkan {$synced} Purchase Order ke Utang Usaha (AP)!", 'green');
        } else {
            CLI::write("✔ Seluruh Purchase Order sudah sinkron dengan Utang Usaha (AP). Tidak ada data tertinggal.", 'green');
        }
    }
}

