<?php

declare(strict_types=1);

namespace Modules\PenjualanPOS\Services;

use App\Exceptions\StokTidakCukupException;
use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Stok\Services\StokService;
use RuntimeException;

class CheckoutPosService
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
     * Execute atomic POS checkout.
     *
     * @param array $payload [
     *   'pelanggan_id' => int|null,
     *   'metode_bayar' => 'tunai'|'qris'|'transfer'|'debit',
     *   'bayar'        => float|string,
     *   'diskon_total' => float|string,
     *   'ppn_persen'   => float|string,
     *   'catatan'      => string|null,
     *   'items'        => [ ['produk_id' => int, 'qty' => float, 'diskon' => float], ... ]
     * ]
     * @return array [ 'so_id' => int, 'nomor_so' => string, 'invoice_id' => int, 'nomor_invoice' => string, 'total' => string, 'kembalian' => string ]
     */
    public function checkout(array $payload): array
    {
        if (empty($payload['items']) || !is_array($payload['items'])) {
            throw new \InvalidArgumentException('Daftar item belanja POS tidak boleh kosong.');
        }

        $this->db->transException(true)->transStart();

        try {
            $user = session()->get('user');
            $userId = $user['id'] ?? null;
            $pelangganId = !empty($payload['pelanggan_id']) ? (int) $payload['pelanggan_id'] : 1; // 1 = Walk-in Default
            $today = date('Y-m-d');

            // 1. Fetch products & recalculate prices strictly on server
            $subtotalServer = '0.00';
            $processedItems = [];

            foreach ($payload['items'] as $item) {
                $produkId = (int) $item['produk_id'];
                $qty = (string) $item['qty'];

                if (bccomp($qty, '0', 3) <= 0) {
                    throw new \InvalidArgumentException('Qty item harus lebih besar dari 0.');
                }

                $produk = $this->db->table('produk')->where('id', $produkId)->where('status', 'active')->get()->getRowArray();
                if (!$produk) {
                    throw new RuntimeException("Produk dengan ID {$produkId} tidak ditemukan atau non-aktif.");
                }

                $hargaSatuan = (string) $produk['harga_jual'];
                $diskonItem = money_round($item['diskon'] ?? 0);
                $lineTotal = bcsub(bcmul($qty, $hargaSatuan, 2), $diskonItem, 2);

                if (bccomp($lineTotal, '0', 2) < 0) {
                    $lineTotal = '0.00';
                }

                $subtotalServer = bcadd($subtotalServer, $lineTotal, 2);

                $processedItems[] = [
                    'produk_id'    => $produkId,
                    'qty'          => $qty,
                    'harga_satuan' => $hargaSatuan,
                    'diskon'       => $diskonItem,
                    'subtotal'     => $lineTotal,
                ];
            }

            // 2. Calculate Final Total
            $diskonTotal = money_round($payload['diskon_total'] ?? 0);
            $afterDiskon = bcsub($subtotalServer, $diskonTotal, 2);
            if (bccomp($afterDiskon, '0', 2) < 0) {
                $afterDiskon = '0.00';
            }

            $ppnPersen = (string) ($payload['ppn_persen'] ?? 0);
            $ppnNominal = '0.00';
            if (bccomp($ppnPersen, '0', 2) > 0) {
                $ppnNominal = bcdiv(bcmul($afterDiskon, $ppnPersen, 2), '100', 2);
            }

            $grandTotal = bcadd($afterDiskon, $ppnNominal, 2);
            $rawBayar = str_replace('.', '', (string)($payload['bayar'] ?? $payload['nominal_bayar'] ?? $grandTotal));
            $uangDiterima = money_round($rawBayar);

            if (bccomp($uangDiterima, $grandTotal, 2) < 0) {
                throw new \InvalidArgumentException(sprintf(
                    'Uang pembayaran (%s) kurang dari total belanja (%s)',
                    format_rupiah($uangDiterima),
                    format_rupiah($grandTotal)
                ));
            }

            $kembalian = bcsub($uangDiterima, $grandTotal, 2);

            // 3. Generate Sequential Number: TRX-YYYYMM-XXXX
            $nomorSO = $this->nomorUrutService->generate('TRX', $today);

            // 4. Insert Sales Order (source = 'pos')
            $soData = [
                'nomor_so'     => $nomorSO,
                'tanggal_so'   => $today,
                'source'       => 'pos',
                'pelanggan_id' => $pelangganId,
                'subtotal'     => $subtotalServer,
                'diskon_total' => $diskonTotal,
                'ppn'          => $ppnNominal,
                'ongkir'       => '0.00',
                'total'        => $grandTotal,
                'bayar'        => $uangDiterima,
                'kembalian'    => $kembalian,
                'metode_bayar' => $payload['metode_bayar'] ?? 'tunai',
                'status'       => 'completed',
                'catatan'      => $payload['catatan'] ?? 'Transaksi Kasir POS',
                'user_id'      => $userId,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

            $this->db->table('sales_order')->insert($soData);
            $soId = (int) $this->db->insertID();

            // 5. Insert Items & Decrement Stock atomically via StokService
            foreach ($processedItems as $item) {
                $item['so_id'] = $soId;
                $this->db->table('sales_order_item')->insert($item);

                // Atomic stock reduction with prevent minus
                $this->stokService->keluar(
                    $item['produk_id'],
                    $item['qty'],
                    $nomorSO,
                    "Penjualan Kasir POS #{$nomorSO}",
                    $userId
                );
            }

            // 6. Generate Invoice (Status: paid)
            $nomorInvoice = $this->nomorUrutService->generate('INV', $today);
            $invoiceData = [
                'nomor_invoice'   => $nomorInvoice,
                'so_id'           => $soId,
                'pelanggan_id'    => $pelangganId,
                'tanggal_invoice' => $today,
                'jatuh_tempo'     => $today,
                'total'           => $grandTotal,
                'status'          => 'paid',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $this->db->table('invoice')->insert($invoiceData);
            $invoiceId = (int) $this->db->insertID();

            // 7. Choose Akun Kas / Bank based on payment method
            $metode = strtolower($payload['metode_bayar'] ?? 'tunai');
            $kodeAkunKas = match ($metode) {
                'qris'     => 'KAS-QRIS',
                'transfer' => 'BANK-BCA',
                'debit'    => 'BANK-BCA',
                default    => 'KAS-KASIR',
            };

            $kasBank = $this->db->table('akun_kas_bank')->where('kode_akun', $kodeAkunKas)->get()->getRowArray();
            $kasBankId = $kasBank ? (int) $kasBank['id'] : 1;

            // Update Saldo Kas/Bank
            $this->db->table('akun_kas_bank')
                ->where('id', $kasBankId)
                ->set('saldo', 'saldo + ' . $this->db->escape($grandTotal), false)
                ->update();

            // 8. Insert Bukti Kas Masuk (BKM)
            $nomorBKM = $this->nomorUrutService->generate('BKM', $today);
            $bkmData = [
                'nomor_bkm'         => $nomorBKM,
                'piutang_id'        => null,
                'so_id'             => $soId,
                'pelanggan_id'      => $pelangganId,
                'akun_kas_bank_id'  => $kasBankId,
                'tanggal_bayar'     => $today,
                'metode_pembayaran' => $metode,
                'nominal'           => $grandTotal,
                'catatan'           => "Penerimaan POS #{$nomorSO}",
                'user_id'           => $userId,
                'created_at'        => date('Y-m-d H:i:s'),
            ];
            $this->db->table('pembayaran_piutang')->insert($bkmData);

            // 9. Generate Balanced Journal Entry (Jurnal Otomatis)
            // Debit: Kas Kasir (1110) / Kas QRIS (1130) / Bank BCA (1120) = Grand Total
            // Kredit: Pendapatan Penjualan (4110) = Grand Total
            $coaKasKode = match ($metode) {
                'qris'     => '1130',
                'transfer' => '1120',
                'debit'    => '1120',
                default    => '1110',
            };

            $this->jurnalService->catat(
                $today,
                $nomorSO,
                "Penjualan Kasir POS #{$nomorSO} ({$nomorBKM})",
                [
                    ['coa_kode' => $coaKasKode, 'debit' => $grandTotal, 'kredit' => '0.00', 'memo' => 'Penerimaan Kasir POS'],
                    ['coa_kode' => '4110', 'debit' => '0.00', 'kredit' => $grandTotal, 'memo' => 'Pendapatan Penjualan POS'],
                ]
            );

            // 10. Audit Trail
            $this->auditService->log('checkout_pos', 'sales_order', $soId, null, [
                'nomor_so' => $nomorSO,
                'total'    => $grandTotal,
            ]);

            $this->db->transComplete();

            return [
                'so_id'         => $soId,
                'nomor_so'      => $nomorSO,
                'invoice_id'    => $invoiceId,
                'nomor_invoice' => $nomorInvoice,
                'total'         => $grandTotal,
                'bayar'         => $uangDiterima,
                'kembalian'     => $kembalian,
            ];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}

