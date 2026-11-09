<?php

declare(strict_types=1);

namespace Modules\Pembelian\Services;

use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Stok\Services\StokService;
use RuntimeException;

class PurchaseOrderService
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
     * Create a new Purchase Order document.
     */
    public function createPO(array $header, array $items): int
    {
        $this->db->transException(true)->transStart();

        try {
            $nomorPO = $this->nomorUrutService->generate('PO', $header['tanggal_po']);
            $user = session()->get('user');
            $userId = $user['id'] ?? null;

            $kurs = $header['nilai_kurs'] ?? '1.000000';
            $subtotal = '0.00';

            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);
                $subtotal = bcadd($subtotal, $lineTotal, 2);
            }

            $diskon = money_round($header['diskon'] ?? 0);
            $pajak = money_round($header['pajak'] ?? 0);
            $totalValas = bcadd(bcsub($subtotal, $diskon, 2), $pajak, 2);
            $totalIdr = bcmul($totalValas, (string) $kurs, 2);

            $jatuhTempo = null;
            if (($header['termin'] ?? 'tunai') === 'tempo') {
                $days = (int) ($header['tempo_hari'] ?? 30);
                $days = $days > 0 ? $days : 30;
                $jatuhTempo = date('Y-m-d', strtotime($header['tanggal_po'] . " +{$days} days"));
            } else {
                $days = (int) ($header['tempo_hari'] ?? 0);
                if ($days > 0) {
                    $jatuhTempo = date('Y-m-d', strtotime($header['tanggal_po'] . " +{$days} days"));
                } else {
                    $jatuhTempo = $header['tanggal_po'];
                }
            }

            $poData = [
                'nomor_po'    => $nomorPO,
                'supplier_id' => (int) $header['supplier_id'],
                'tanggal_po'  => $header['tanggal_po'],
                'termin'      => $header['termin'] ?? 'tunai',
                'tempo_hari'  => (int) ($header['tempo_hari'] ?? 0),
                'jatuh_tempo' => $jatuhTempo,
                'mata_uang'   => strtoupper($header['mata_uang'] ?? 'IDR'),
                'nilai_kurs'  => $kurs,
                'subtotal'    => $subtotal,
                'diskon'      => $diskon,
                'pajak'       => $pajak,
                'total'       => $totalValas,
                'total_idr'   => $totalIdr,
                'status'      => 'approved',
                'catatan'     => $header['catatan'] ?? null,
                'user_id'     => $userId,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];

            $this->db->table('purchase_order')->insert($poData);
            $poId = (int) $this->db->insertID();

            foreach ($items as $item) {
                $qty = (string) $item['qty'];
                $harga = (string) $item['harga_satuan'];
                $lineTotal = bcmul($qty, $harga, 2);

                $this->db->table('purchase_order_item')->insert([
                    'po_id'        => $poId,
                    'produk_id'    => (int) $item['produk_id'],
                    'qty'          => $qty,
                    'qty_diterima' => '0.000',
                    'harga_satuan' => $harga,
                    'subtotal'     => $lineTotal,
                ]);
            }

            // Catat langsung kewajiban tagihan ke Utang Usaha (Accounts Payable / AP)
            $nomorUtang = $this->nomorUrutService->generate('AP', $header['tanggal_po']);
            $this->db->table('utang_usaha')->insert([
                'nomor_utang' => $nomorUtang,
                'po_id'       => $poId,
                'supplier_id' => (int) $header['supplier_id'],
                'tanggal'     => $header['tanggal_po'],
                'jatuh_tempo' => $jatuhTempo ?: $header['tanggal_po'],
                'total'       => $totalIdr,
                'sisa'        => $totalIdr,
                'status'      => 'belum',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            $this->auditService->log('create', 'purchase_order', $poId, null, $poData);

            $this->db->transComplete();

            return $poId;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Process receiving of PO goods at the warehouse.
     */
    public function receivePO(int $poId, array $receivedQtyPerItem): void
    {
        $this->db->transException(true)->transStart();

        try {
            $po = $this->db->table('purchase_order')->where('id', $poId)->get()->getRowArray();
            if (!$po) {
                throw new RuntimeException('Purchase Order tidak ditemukan.');
            }

            if (in_array($po['status'], ['received', 'cancelled'], true)) {
                throw new RuntimeException('Purchase Order ini sudah selesai atau dibatalkan.');
            }

            $items = $this->db->table('purchase_order_item')->where('po_id', $poId)->get()->getResultArray();
            $kurs = (string) $po['nilai_kurs'];
            $totalReceivedValueIdr = '0.00';
            $allCompleted = true;

            foreach ($items as $item) {
                $itemId = (int) $item['id'];
                $produkId = (int) $item['produk_id'];
                $qtyPesan = (string) $item['qty'];
                $alreadyReceived = (string) $item['qty_diterima'];
                $remaining = bcsub($qtyPesan, $alreadyReceived, 3);

                $incoming = (string) ($receivedQtyPerItem[$itemId] ?? 0);

                if (bccomp($incoming, '0', 3) > 0) {
                    if (bccomp($incoming, $remaining, 3) > 0) {
                        throw new RuntimeException("Jumlah penerimaan ({$incoming}) melebihi sisa pesanan ({$remaining})");
                    }

                    $newTotalReceived = bcadd($alreadyReceived, $incoming, 3);
                    $this->db->table('purchase_order_item')
                        ->where('id', $itemId)
                        ->update(['qty_diterima' => $newTotalReceived]);

                    // Add physical stock via StokService
                    $this->stokService->masuk(
                        $produkId,
                        $incoming,
                        $po['nomor_po'],
                        "Penerimaan Barang PO #{$po['nomor_po']}"
                    );

                    // Lock HPP in product (HPP IDR = unit price * exchange rate)
                    $hargaSatuanIdr = bcmul((string) $item['harga_satuan'], $kurs, 2);
                    $this->db->table('produk')
                        ->where('id', $produkId)
                        ->update([
                            'harga_beli' => $hargaSatuanIdr,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    $itemValueIdr = bcmul($incoming, $hargaSatuanIdr, 2);
                    $totalReceivedValueIdr = bcadd($totalReceivedValueIdr, $itemValueIdr, 2);

                    if (bccomp($newTotalReceived, $qtyPesan, 3) < 0) {
                        $allCompleted = false;
                    }
                } else {
                    if (bccomp($alreadyReceived, $qtyPesan, 3) < 0) {
                        $allCompleted = false;
                    }
                }
            }

            // Update PO Status
            $newStatus = $allCompleted ? 'received' : 'partial';
            $this->db->table('purchase_order')
                ->where('id', $poId)
                ->update([
                    'status'     => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // Pastikan data kewajiban Utang Usaha (AP) tercatat untuk PO ini
            $existingUtang = $this->db->table('utang_usaha')->where('po_id', $poId)->get()->getRowArray();
            if (!$existingUtang) {
                $nomorUtang = $this->nomorUrutService->generate('AP', $po['tanggal_po'] ?? date('Y-m-d'));
                $totalUtang = $po['total_idr'] ?? $totalReceivedValueIdr;
                $this->db->table('utang_usaha')->insert([
                    'nomor_utang' => $nomorUtang,
                    'po_id'       => $poId,
                    'supplier_id' => (int) $po['supplier_id'],
                    'tanggal'     => $po['tanggal_po'] ?? date('Y-m-d'),
                    'jatuh_tempo' => $po['jatuh_tempo'] ?? date('Y-m-d', strtotime('+30 days')),
                    'total'       => $totalUtang,
                    'sisa'        => $totalUtang,
                    'status'      => 'belum',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            // Jurnal Otomatis Penerimaan Barang PO:
            // Debit: Persediaan Barang Dagang (1150)
            // Kredit: Utang Usaha (2110)
            if (bccomp($totalReceivedValueIdr, '0.00', 2) > 0) {
                $this->jurnalService->catat(
                    date('Y-m-d'),
                    $po['nomor_po'],
                    "Penerimaan PO #{$po['nomor_po']}",
                    [
                        ['coa_kode' => '1150', 'debit' => $totalReceivedValueIdr, 'kredit' => '0.00'],
                        ['coa_kode' => '2110', 'debit' => '0.00', 'kredit' => $totalReceivedValueIdr],
                    ]
                );
            }

            $this->auditService->log('receive', 'purchase_order', $poId, null, [
                'status'        => $newStatus,
                'nilai_terima'  => $totalReceivedValueIdr,
            ]);

            $this->db->transComplete();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}

