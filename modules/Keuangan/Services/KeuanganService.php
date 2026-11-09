<?php

declare(strict_types=1);

namespace Modules\Keuangan\Services;

use App\Exceptions\PembayaranMelebihiSisaException;
use App\Services\AuditService;
use App\Services\JurnalService;
use App\Services\NomorUrutService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use RuntimeException;

class KeuanganService
{
    protected BaseConnection $db;
    protected NomorUrutService $nomorUrutService;
    protected JurnalService $jurnalService;
    protected AuditService $auditService;

    public function __construct(
        ?BaseConnection $db = null,
        ?NomorUrutService $nomorUrutService = null,
        ?JurnalService $jurnalService = null,
        ?AuditService $auditService = null
    ) {
        $this->db = $db ?? Database::connect();
        $this->nomorUrutService = $nomorUrutService ?? new NomorUrutService($this->db);
        $this->jurnalService = $jurnalService ?? new JurnalService($this->db);
        $this->auditService = $auditService ?? new AuditService($this->db);
    }

    /**
     * Catat pelunasan / cicilan piutang pelanggan (Bukti Kas Masuk - BKM).
     */
    public function bayarPiutang(int $piutangId, int $akunKasBankId, float|string $nominal, string $tanggal, string $metode, ?string $catatan = null): string
    {
        $this->db->transException(true)->transStart();

        try {
            $piutang = $this->db->table('piutang_usaha')->where('id', $piutangId)->get()->getRowArray();
            if (!$piutang) {
                throw new RuntimeException('Data piutang tidak ditemukan.');
            }

            $sisaPiutang = (string) $piutang['sisa'];
            $nominalStr = money_round($nominal);

            if (bccomp($nominalStr, '0.00', 2) <= 0) {
                throw new \InvalidArgumentException('Nominal pembayaran harus lebih besar dari 0.');
            }

            $diff = bcsub($nominalStr, $sisaPiutang, 2);
            $selisihPembulatan = '0.00';

            if (bccomp($diff, '0.00', 2) > 0) {
                // Jika pembayaran melebihi sisa piutang karena pembulatan nominal transfer (toleransi pembulatan s/d Rp 50.000)
                if (bccomp($diff, '50000.00', 2) <= 0) {
                    $selisihPembulatan = $diff;
                    $nominalPiutangTerbayar = $sisaPiutang;
                    $newSisa = '0.00';
                    $newStatus = 'lunas';
                } else {
                    throw new PembayaranMelebihiSisaException(sprintf(
                        'Nominal pembayaran (%s) melebihi sisa piutang (%s)',
                        format_rupiah($nominalStr),
                        format_rupiah($sisaPiutang)
                    ));
                }
            } else {
                $nominalPiutangTerbayar = $nominalStr;
                $newSisa = bcsub($sisaPiutang, $nominalStr, 2);
                $newStatus = bccomp($newSisa, '0.00', 2) === 0 ? 'lunas' : 'sebagian';
            }

            $this->db->table('piutang_usaha')->where('id', $piutangId)->update([
                'sisa'       => $newSisa,
                'status'     => $newStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Update status invoice jika lunas
            if ($newStatus === 'lunas' && !empty($piutang['so_id'])) {
                $this->db->table('invoice')->where('so_id', $piutang['so_id'])->update([
                    'status'     => 'paid',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Generate BKM number
            $nomorBKM = $this->nomorUrutService->generate('BKM', $tanggal);
            $user = session()->get('user');
            $userId = $user['id'] ?? null;

            $this->db->table('pembayaran_piutang')->insert([
                'nomor_bkm'         => $nomorBKM,
                'piutang_id'        => $piutangId,
                'so_id'             => $piutang['so_id'],
                'pelanggan_id'      => $piutang['pelanggan_id'],
                'akun_kas_bank_id'  => $akunKasBankId,
                'tanggal_bayar'     => $tanggal,
                'metode_pembayaran' => $metode,
                'nominal'           => $nominalStr,
                'catatan'           => $catatan ?: "Pelunasan Piutang #{$piutang['nomor_piutang']}",
                'user_id'           => $userId,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);

            // Update Saldo Kas/Bank
            $this->db->table('akun_kas_bank')
                ->where('id', $akunKasBankId)
                ->set('saldo', 'saldo + ' . $this->db->escape($nominalStr), false)
                ->update();

            // Fetch COA of selected kas/bank
            $kasBank = $this->db->table('akun_kas_bank')->where('id', $akunKasBankId)->get()->getRowArray();
            $coaIdKas = $kasBank['coa_id'] ?? 1;

            // Balanced Journal:
            // Debit: Kas/Bank (Akun terpilih)
            // Kredit: Piutang Usaha (1140)
            // Kredit (opsional): Pendapatan Penjualan / Pembulatan (4110)
            $journalEntries = [
                ['coa_id' => $coaIdKas, 'debit' => $nominalStr, 'kredit' => '0.00'],
                ['coa_kode' => '1140', 'debit' => '0.00', 'kredit' => $nominalPiutangTerbayar],
            ];

            if (bccomp($selisihPembulatan, '0.00', 2) > 0) {
                $journalEntries[] = [
                    'coa_kode' => '4110',
                    'debit'    => '0.00',
                    'kredit'   => $selisihPembulatan,
                ];
            }

            $keteranganJurnal = "Bukti Kas Masuk #{$nomorBKM} - Pembayaran Piutang #{$piutang['nomor_piutang']}";
            if (bccomp($selisihPembulatan, '0.00', 2) > 0) {
                $keteranganJurnal .= " (Termasuk Pembulatan Transfer " . format_rupiah($selisihPembulatan) . ")";
            }

            $this->jurnalService->catat(
                $tanggal,
                $nomorBKM,
                $keteranganJurnal,
                $journalEntries
            );

            $this->auditService->log('bayar_piutang', 'piutang_usaha', $piutangId, ['sisa_lama' => $sisaPiutang], ['sisa_baru' => $newSisa, 'bkm' => $nomorBKM]);

            $this->db->transComplete();

            return $nomorBKM;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Catat pembayaran utang supplier (Bukti Kas Keluar - BKK).
     */
    public function bayarUtang(int $utangId, int $akunKasBankId, float|string $nominal, string $tanggal, string $metode, ?string $catatan = null): string
    {
        $this->db->transException(true)->transStart();

        try {
            $utang = $this->db->table('utang_usaha')->where('id', $utangId)->get()->getRowArray();
            if (!$utang) {
                throw new RuntimeException('Data utang tidak ditemukan.');
            }

            $sisaUtang = (string) $utang['sisa'];
            $nominalStr = money_round($nominal);

            if (bccomp($nominalStr, '0.00', 2) <= 0) {
                throw new \InvalidArgumentException('Nominal pembayaran harus lebih besar dari 0.');
            }

            $diff = bcsub($nominalStr, $sisaUtang, 2);
            $selisihPembulatan = '0.00';

            if (bccomp($diff, '0.00', 2) > 0) {
                // Jika pembayaran melebihi sisa utang karena pembulatan nominal transfer bank (toleransi pembulatan s/d Rp 50.000)
                if (bccomp($diff, '50000.00', 2) <= 0) {
                    $selisihPembulatan = $diff;
                    $nominalUtangTerbayar = $sisaUtang;
                    $newSisa = '0.00';
                    $newStatus = 'lunas';
                } else {
                    throw new PembayaranMelebihiSisaException(sprintf(
                        'Nominal pembayaran (%s) melebihi sisa utang (%s)',
                        format_rupiah($nominalStr),
                        format_rupiah($sisaUtang)
                    ));
                }
            } else {
                $nominalUtangTerbayar = $nominalStr;
                $newSisa = bcsub($sisaUtang, $nominalStr, 2);
                $newStatus = bccomp($newSisa, '0.00', 2) === 0 ? 'lunas' : 'sebagian';
            }

            $this->db->table('utang_usaha')->where('id', $utangId)->update([
                'sisa'       => $newSisa,
                'status'     => $newStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Generate BKK number
            $nomorBKK = $this->nomorUrutService->generate('BKK', $tanggal);
            $user = session()->get('user');
            $userId = $user['id'] ?? null;

            $this->db->table('pembayaran_utang')->insert([
                'nomor_bkk'         => $nomorBKK,
                'utang_id'          => $utangId,
                'supplier_id'       => $utang['supplier_id'],
                'akun_kas_bank_id'  => $akunKasBankId,
                'tanggal_bayar'     => $tanggal,
                'metode_pembayaran' => $metode,
                'nominal'           => $nominalStr,
                'catatan'           => $catatan ?: "Pembayaran Utang #{$utang['nomor_utang']}",
                'user_id'           => $userId,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);

            // Update Saldo Kas/Bank (kurangi saldo sesuai uang riil yang keluar)
            $this->db->table('akun_kas_bank')
                ->where('id', $akunKasBankId)
                ->set('saldo', 'saldo - ' . $this->db->escape($nominalStr), false)
                ->update();

            // Fetch COA of selected kas/bank
            $kasBank = $this->db->table('akun_kas_bank')->where('id', $akunKasBankId)->get()->getRowArray();
            $coaIdKas = $kasBank['coa_id'] ?? 1;

            // Balanced Journal:
            // Debit: Utang Usaha (2110)
            // Debit (opsional): Beban Operasional / Selisih Pembulatan (5210)
            // Kredit: Kas/Bank (Akun terpilih)
            $journalEntries = [
                ['coa_kode' => '2110', 'debit' => $nominalUtangTerbayar, 'kredit' => '0.00'],
            ];

            if (bccomp($selisihPembulatan, '0.00', 2) > 0) {
                $journalEntries[] = [
                    'coa_kode' => '5210',
                    'debit'    => $selisihPembulatan,
                    'kredit'   => '0.00',
                ];
            }

            $journalEntries[] = [
                'coa_id' => $coaIdKas,
                'debit'  => '0.00',
                'kredit' => $nominalStr,
            ];

            $keteranganJurnal = "Bukti Kas Keluar #{$nomorBKK} - Pembayaran Utang #{$utang['nomor_utang']}";
            if (bccomp($selisihPembulatan, '0.00', 2) > 0) {
                $keteranganJurnal .= " (Termasuk Pembulatan Transfer " . format_rupiah($selisihPembulatan) . ")";
            }

            $this->jurnalService->catat(
                $tanggal,
                $nomorBKK,
                $keteranganJurnal,
                $journalEntries
            );

            $this->auditService->log('bayar_utang', 'utang_usaha', $utangId, ['sisa_lama' => $sisaUtang], ['sisa_baru' => $newSisa, 'bkk' => $nomorBKK]);

            $this->db->transComplete();

            return $nomorBKK;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Sinkronisasi otomatis data Purchase Order (PO) ke Buku Utang Usaha (AP).
     * Memastikan seluruh data PO yang aktif tercatat di AP.
     */
    public function syncMissingUtangFromPO(): int
    {
        // Rapikan tanggal pada utang_usaha lama yang belum valid jika ada
        try {
            $this->db->query("
                UPDATE utang_usaha uu
                JOIN purchase_order po ON po.id = uu.po_id
                SET uu.tanggal = po.tanggal_po
                WHERE CAST(uu.tanggal AS CHAR) LIKE '0000%' OR uu.tanggal IS NULL OR uu.tanggal <= '1970-01-01'
            ");
        } catch (\Throwable $ignored) {
            // Abaikan jika database strict mode membatasi query update
        }

        $missingPOs = $this->db->table('purchase_order po')
            ->select('po.*')
            ->join('utang_usaha uu', 'uu.po_id = po.id', 'left')
            ->where('uu.id IS NULL')
            ->where('po.status !=', 'cancelled')
            ->orderBy('po.id', 'ASC')
            ->get()
            ->getResultArray();

        $count = 0;
        foreach ($missingPOs as $po) {
            $tanggal = !empty($po['tanggal_po']) ? $po['tanggal_po'] : date('Y-m-d');
            $jatuhTempo = !empty($po['jatuh_tempo']) ? $po['jatuh_tempo'] : null;
            if (empty($jatuhTempo)) {
                $days = (int) ($po['tempo_hari'] ?? 30);
                $days = $days > 0 ? $days : 30;
                $jatuhTempo = date('Y-m-d', strtotime($tanggal . " +{$days} days"));
            }

            $total = (string) ($po['total_idr'] ?? $po['total'] ?? '0.00');
            $nomorUtang = $this->nomorUrutService->generate('AP', $tanggal);

            $this->db->table('utang_usaha')->insert([
                'nomor_utang' => $nomorUtang,
                'po_id'       => (int) $po['id'],
                'supplier_id' => (int) $po['supplier_id'],
                'tanggal'     => $tanggal,
                'jatuh_tempo' => $jatuhTempo,
                'total'       => $total,
                'sisa'        => $total,
                'status'      => 'belum',
                'created_at'  => !empty($po['created_at']) ? $po['created_at'] : date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $count++;
        }

        return $count;
    }
}

