<?php

declare(strict_types=1);

namespace Modules\PenjualanRegular\Services;

use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Stok\Services\StokService;
use RuntimeException;

class SalesOrderService
{
    protected BaseConnection $db;
    protected NomorUrutService $nomorUrutService;
    protected StokService $stokService;
    protected JurnalService $jurnalService;
    protected AuditService $auditService;

    public function __construct(
        ?BaseConnection $db = null,
        ?NomorUrutService $nomorUrutService = null,
        ?StokService $stokService = null,
        ?JurnalService $jurnalService = null,
        ?AuditService $auditService = null
    ) {
        $this->db = $db ?? Database::connect();
        $this->nomorUrutService = $nomorUrutService ?? new NomorUrutService($this->db);
        $this->stokService = $stokService ?? new StokService($this->db);
        $this->jurnalService = $jurnalService ?? new JurnalService($this->db);
        $this->auditService = $auditService ?? new AuditService($this->db);
    }

    /**
     * Create regular sales order with automatic stock reservation.
     */
    public function createSalesOrder(array $header, array $items): int
    {
        $this->db->transException(true)->transStart();

        try {
            $user = session()->get('user');
            $userId = $user['id'] ?? null;
            $today = $header['tanggal_so'] ?? date('Y-m-d');

            $nomorSO = $this->nomorUrutService->generate('SO', $today);

            $subtotal = '0.00';
            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $diskon = money_round($item['diskon'] ?? 0);
                $lineTotal = bcsub(bcmul($qty, $harga, 2), $diskon, 2);
                $subtotal = bcadd($subtotal, $lineTotal, 2);
            }

            $diskonTotal = money_round($header['diskon_total'] ?? 0);
            $afterDiskon = bcsub($subtotal, $diskonTotal, 2);
            if (bccomp($afterDiskon, '0', 2) < 0) {
                $afterDiskon = '0.00';
            }

            $ppnPersen = (string) ($header['ppn_persen'] ?? 0);
            $ppnNominal = '0.00';
            if (bccomp($ppnPersen, '0', 2) > 0) {
                $ppnNominal = bcdiv(bcmul($afterDiskon, $ppnPersen, 2), '100', 2);
            }

            $ongkir = money_round($header['ongkir'] ?? 0);
            $grandTotal = bcadd(bcadd($afterDiskon, $ppnNominal, 2), $ongkir, 2);

            $soData = [
                'nomor_so'     => $nomorSO,
                'tanggal_so'   => $today,
                'source'       => 'manual',
                'pelanggan_id' => (int) $header['pelanggan_id'],
                'subtotal'     => $subtotal,
                'diskon_total' => $diskonTotal,
                'ppn'          => $ppnNominal,
                'ongkir'       => $ongkir,
                'total'        => $grandTotal,
                'bayar'        => '0.00',
                'kembalian'    => '0.00',
                'metode_bayar' => $header['metode_bayar'] ?? 'tempo',
                'status'       => 'draft',
                'catatan'      => $header['catatan'] ?? null,
                'user_id'      => $userId,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

            $this->db->table('sales_order')->insert($soData);
            $soId = (int) $this->db->insertID();

            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $diskon = money_round($item['diskon'] ?? 0);
                $lineTotal = bcsub(bcmul($qty, $harga, 2), $diskon, 2);

                $this->db->table('sales_order_item')->insert([
                    'so_id'        => $soId,
                    'produk_id'    => (int) $item['produk_id'],
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                    'diskon'       => $diskon,
                    'subtotal'     => $lineTotal,
                ]);

                // Reserve stock for pending shipment
                $this->stokService->reserve((int) $item['produk_id'], $qty);
            }

            // Generate Invoice immediately
            $nomorInvoice = $this->nomorUrutService->generate('INV', $today);
            $tempoHari = (int) ($header['tempo_hari'] ?? 30);
            $jatuhTempo = date('Y-m-d', strtotime($today . " +{$tempoHari} days"));

            $this->db->table('invoice')->insert([
                'nomor_invoice'   => $nomorInvoice,
                'so_id'           => $soId,
                'pelanggan_id'    => (int) $header['pelanggan_id'],
                'tanggal_invoice' => $today,
                'jatuh_tempo'     => $jatuhTempo,
                'total'           => $grandTotal,
                'status'          => 'unpaid',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            // Form Piutang Usaha
            $nomorPiutang = $this->nomorUrutService->generate('PIU', $today);
            $this->db->table('piutang_usaha')->insert([
                'nomor_piutang' => $nomorPiutang,
                'so_id'         => $soId,
                'pelanggan_id'  => (int) $header['pelanggan_id'],
                'tanggal'       => $today,
                'jatuh_tempo'   => $jatuhTempo,
                'total'         => $grandTotal,
                'sisa'          => $grandTotal,
                'status'        => 'belum',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

            $this->auditService->log('create', 'sales_order', $soId, null, $soData);

            $this->db->transComplete();

            return $soId;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Update shipping workflow status and mutates inventory on actual shipment.
     */
    public function updateStatus(int $soId, string $newStatus): void
    {
        $this->db->transException(true)->transStart();

        try {
            $so = $this->db->table('sales_order')->where('id', $soId)->get()->getRowArray();
            if (!$so) {
                throw new RuntimeException('Sales Order tidak ditemukan.');
            }

            $currentStatus = $so['status'];
            $items = $this->db->table('sales_order_item')->where('so_id', $soId)->get()->getResultArray();

            // When changing to 'shipped' from earlier statuses:
            // Release reservation and deduct actual physical stock
            if (in_array($newStatus, ['shipped', 'delivered', 'completed'], true) && !in_array($currentStatus, ['shipped', 'delivered', 'completed'], true)) {
                foreach ($items as $item) {
                    $this->stokService->releaseReserve((int) $item['produk_id'], (string) $item['qty']);
                    $this->stokService->keluar(
                        (int) $item['produk_id'],
                        (string) $item['qty'],
                        $so['nomor_so'],
                        "Pengiriman Regular SO #{$so['nomor_so']}"
                    );
                }

                // Balanced journal on shipment:
                // Debit: Piutang Usaha (1140) = Total
                // Kredit: Pendapatan Penjualan (4110) = Total
                $this->jurnalService->catat(
                    date('Y-m-d'),
                    $so['nomor_so'],
                    "Pengiriman SO #{$so['nomor_so']}",
                    [
                        ['coa_kode' => '1140', 'debit' => $so['total'], 'kredit' => '0.00'],
                        ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => $so['total']],
                    ]
                );
            }

            // If cancelled, release reserved stock
            if ($newStatus === 'cancelled' && !in_array($currentStatus, ['shipped', 'delivered', 'completed', 'cancelled'], true)) {
                foreach ($items as $item) {
                    $this->stokService->releaseReserve((int) $item['produk_id'], (string) $item['qty']);
                }
            }

            $this->db->table('sales_order')->where('id', $soId)->update([
                'status'     => $newStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $this->auditService->log('update_status', 'sales_order', $soId, ['status_lama' => $currentStatus], ['status_baru' => $newStatus]);

            $this->db->transComplete();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}

