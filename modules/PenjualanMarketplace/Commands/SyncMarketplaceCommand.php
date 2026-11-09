<?php

declare(strict_types=1);

namespace Modules\PenjualanMarketplace\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\PenjualanMarketplace\Services\MarketplaceImportService;

class SyncMarketplaceCommand extends BaseCommand
{
    protected $group       = 'Marketplace';
    protected $name        = 'marketplace:sync';
    protected $description = 'Sinkronisasi data pesanan marketplace retroaktif ke Master Data, Sales Order ERP, dan Jadwal CRM.';

    public function run(array $params)
    {
        CLI::write('Memulai sinkronisasi data marketplace ke Master Data, SO ERP, dan CRM...', 'yellow');

        try {
            $service = new MarketplaceImportService();
            $result = $service->syncExistingUnmappedData();

            CLI::write('Sinkronisasi selesai!', 'green');
            CLI::write("- Produk tersinkronisasi: {$result['synced_products']}");
            CLI::write("- Pelanggan & SO tersinkronisasi: {$result['synced_customers']}");
        } catch (\Throwable $e) {
            CLI::error('Terjadi kesalahan: ' . $e->getMessage());
            CLI::error($e->getTraceAsString());
        }
    }
}

