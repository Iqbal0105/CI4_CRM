<?php

declare(strict_types=1);

namespace Modules\Retur\Services;

use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Stok\Services\StokService;
use RuntimeException;

class ReturService
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
     * Proses Retur Penjualan dari Pelanggan.
     */
    public function prosesReturPenjualan(int $soId, string $tindakLanjut, string $kondisiBarang, string $alasan, array $items): string
    {
        $this->db->transException(true)->transStart();

        try {
            $so = $this->db->table('sales_order')->where('id', $soId)->get()->getRowArray();
            if (!$so) {
                throw new RuntimeException('Sales Order tidak ditemukan.');
            }

            $today = date('Y-m-d');
            $nomorRetur = $this->nomorUrutService->generate('RET_JUAL', $today);
            $user = session()->get('user');
            $userId = $user['id'] ?? null;

            $totalRetur = '0.00';
            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);
                $totalRetur = bcadd($totalRetur, $lineTotal, 2);
            }

            // Insert retur_penjualan
            $returData = [
                'nomor_retur'    => $nomorRetur,
                'so_id'          => $soId,
                'pelanggan_id'   => $so['pelanggan_id'],
                'tanggal'        => $today,
                'tindak_lanjut'  => $tindakLanjut,
                'kondisi_barang' => $kondisiBarang,
                'total'          => $totalRetur,
                'status'         => 'completed',
                'alasan'         => $alasan,
                'user_id'        => $userId,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
            $this->db->table('retur_penjualan')->insert($returData);
            $returId = (int) $this->db->insertID();

            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);

                $this->db->table('retur_penjualan_item')->insert([
                    'retur_id'     => $returId,
                    'produk_id'    => (int) $item['produk_id'],
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                    'subtotal'     => $lineTotal,
                ]);

                // Return goods to physical stock if condition is good
                if ($kondisiBarang === 'baik') {
                    $this->stokService->masuk(
                        (int) $item['produk_id'],
                        $qty,
                        $nomorRetur,
                        "Retur Penjualan #{$nomorRetur}"
                    );
                }
            }

            // Financial & Accounting Impact based on tindak lanjut:
            if ($tindakLanjut === 'potong_piutang') {
                $piutang = $this->db->table('piutang_usaha')->where('so_id', $soId)->get()->getRowArray();
                if ($piutang) {
                    $newSisa = bcsub((string) $piutang['sisa'], $totalRetur, 2);
                    if (bccomp($newSisa, '0.00', 2) < 0) $newSisa = '0.00';
                    $newStatus = bccomp($newSisa, '0.00', 2) === 0 ? 'lunas' : 'sebagian';

                    $this->db->table('piutang_usaha')->where('id', $piutang['id'])->update([
                        'sisa'       => $newSisa,
                        'status'     => $newStatus,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Journal: Debit Pendapatan Penjualan (4110), Kredit Piutang Usaha (1140)
                $this->jurnalService->catat(
                    $today,
                    $nomorRetur,
                    "Retur Penjualan #{$nomorRetur} (Potong Piutang)",
                    [
                        ['coa_kode' => '4110', 'debit' => $totalRetur, 'kredit' => '0.00'],
                        ['coa_kode' => '1140', 'debit' => '0.00', 'kredit' => $totalRetur],
                    ]
                );
            } elseif ($tindakLanjut === 'refund') {
                // Journal: Debit Pendapatan Penjualan (4110), Kredit Kas Tunai (1110)
                $this->jurnalService->catat(
                    $today,
                    $nomorRetur,
                    "Retur Penjualan #{$nomorRetur} (Refund Kas)",
                    [
                        ['coa_kode' => '4110', 'debit' => $totalRetur, 'kredit' => '0.00'],
                        ['coa_kode' => '1110', 'debit' => '0.00', 'kredit' => $totalRetur],
                    ]
                );
            }

            $this->auditService->log('retur_penjualan', 'retur_penjualan', $returId, null, $returData);

            $this->db->transComplete();

            return $nomorRetur;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Proses Retur Pembelian ke Supplier.
     */
    public function prosesReturPembelian(int $poId, string $tindakLanjut, string $alasan, array $items): string
    {
        $this->db->transException(true)->transStart();

        try {
            $po = $this->db->table('purchase_order')->where('id', $poId)->get()->getRowArray();
            if (!$po) {
                throw new RuntimeException('Purchase Order tidak ditemukan.');
            }

            $today = date('Y-m-d');
            $nomorRetur = $this->nomorUrutService->generate('RET_BELI', $today);
            $user = session()->get('user');
            $userId = $user['id'] ?? null;

            $totalRetur = '0.00';
            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);
                $totalRetur = bcadd($totalRetur, $lineTotal, 2);
            }

            $returData = [
                'nomor_retur'   => $nomorRetur,
                'po_id'         => $poId,
                'supplier_id'   => $po['supplier_id'],
                'tanggal'       => $today,
                'tindak_lanjut' => $tindakLanjut,
                'total'         => $totalRetur,
                'status'        => 'completed',
                'alasan'        => $alasan,
                'user_id'       => $userId,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
            $this->db->table('retur_pembelian')->insert($returData);
            $returId = (int) $this->db->insertID();

            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);

                $this->db->table('retur_pembelian_item')->insert([
                    'retur_id'     => $returId,
                    'produk_id'    => (int) $item['produk_id'],
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                    'subtotal'     => $lineTotal,
                ]);

                // Reduce stock via StokService
                $this->stokService->keluar(
                    (int) $item['produk_id'],
                    $qty,
                    $nomorRetur,
                    "Retur Pembelian ke Supplier #{$nomorRetur}",
                    $userId
                );
            }

            if ($tindakLanjut === 'potong_utang') {
                $utang = $this->db->table('utang_usaha')->where('po_id', $poId)->get()->getRowArray();
                if ($utang) {
                    $newSisa = bcsub((string) $utang['sisa'], $totalRetur, 2);
                    if (bccomp($newSisa, '0.00', 2) < 0) $newSisa = '0.00';
                    $newStatus = bccomp($newSisa, '0.00', 2) === 0 ? 'lunas' : 'sebagian';

                    $this->db->table('utang_usaha')->where('id', $utang['id'])->update([
                        'sisa'       => $newSisa,
                        'status'     => $newStatus,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Journal: Debit Utang Usaha (2110), Kredit Persediaan Barang Dagang (1150)
                $this->jurnalService->catat(
                    $today,
                    $nomorRetur,
                    "Retur Pembelian #{$nomorRetur} (Potong Utang)",
                    [
                        ['coa_kode' => '2110', 'debit' => $totalRetur, 'kredit' => '0.00'],
                        ['coa_kode' => '1150', 'debit' => '0.00', 'kredit' => $totalRetur],
                    ]
                );
            } else {
                // Refund Kas: Debit Kas Tunai (1110), Kredit Persediaan (1150)
                $this->jurnalService->catat(
                    $today,
                    $nomorRetur,
                    "Retur Pembelian #{$nomorRetur} (Refund Kas)",
                    [
                        ['coa_kode' => '1110', 'debit' => $totalRetur, 'kredit' => '0.00'],
                        ['coa_kode' => '1150', 'debit' => '0.00', 'kredit' => $totalRetur],
                    ]
                );
            }

            $this->auditService->log('retur_pembelian', 'retur_pembelian', $returId, null, $returData);

            $this->db->transComplete();

            return $nomorRetur;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}

