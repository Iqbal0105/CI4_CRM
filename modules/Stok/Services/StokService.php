<?php

declare(strict_types=1);

namespace Modules\Stok\Services;

use App\Exceptions\StokTidakCukupException;
use App\Services\AuditService;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use RuntimeException;

class StokService
{
    protected BaseConnection $db;
    protected AuditService $auditService;

    public function __construct(?BaseConnection $db = null, ?AuditService $auditService = null)
    {
        $this->db = $db ?? Database::connect();
        $this->auditService = $auditService ?? new AuditService($this->db);
    }

    /**
     * Tambah stok fisik (Penerimaan PO, Retur Penjualan, dll).
     */
    public function masuk(int $produkId, float|string $qty, string $referensiDokumen, ?string $keterangan = null, ?int $operatorId = null): string
    {
        $qtyStr = (string) $qty;
        if (bccomp($qtyStr, '0', 3) <= 0) {
            throw new \InvalidArgumentException('Jumlah penambahan stok harus lebih besar dari 0');
        }

        $operatorId = $operatorId ?? (session()->get('user')['id'] ?? null);

        // Ensure record exists in stok table
        $stokRow = $this->db->table('stok')->where('produk_id', $produkId)->get()->getRow();
        if (!$stokRow) {
            $this->db->table('stok')->insert([
                'produk_id'    => $produkId,
                'qty'          => '0.000',
                'reserved_qty' => '0.000',
                'lokasi_rak'   => 'RAK-UTAMA',
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
            $currentQty = '0.000';
        } else {
            $currentQty = (string) $stokRow->qty;
        }

        $newSaldo = bcadd($currentQty, $qtyStr, 3);

        // Update stok
        $this->db->table('stok')
            ->where('produk_id', $produkId)
            ->update([
                'qty'        => $newSaldo,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // Append to stok_log (append-only)
        $this->db->table('stok_log')->insert([
            'produk_id'         => $produkId,
            'tipe'              => 'in',
            'qty'               => $qtyStr,
            'saldo_akhir'       => $newSaldo,
            'referensi_dokumen' => $referensiDokumen,
            'operator_id'       => $operatorId,
            'keterangan'        => $keterangan ?? 'Penambahan stok masuk',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return $newSaldo;
    }

    /**
     * Kurangi stok fisik (POS Checkout, Pengiriman SO, Retur Pembelian).
     * Atomic update to guarantee prevention of negative stock.
     */
    public function keluar(
        int $produkId,
        float|string $qty,
        string $referensiDokumen,
        ?string $keterangan = null,
        ?int $operatorId = null,
        bool $allowNegative = false,
        bool $managerApproved = false
    ): string {
        $qtyStr = (string) $qty;
        if (bccomp($qtyStr, '0', 3) <= 0) {
            throw new \InvalidArgumentException('Jumlah pengeluaran stok harus lebih besar dari 0');
        }

        $operatorId = $operatorId ?? (session()->get('user')['id'] ?? null);

        // Fetch current product and stock
        $stokRow = $this->db->table('stok')->where('produk_id', $produkId)->get()->getRow();
        $currentQty = $stokRow ? (string) $stokRow->qty : '0.000';
        $produkRow = $this->db->table('produk')->where('id', $produkId)->get()->getRow();
        $namaProduk = $produkRow ? $produkRow->nama_produk : 'Produk #' . $produkId;

        // Atomic decrement query
        if (!$allowNegative) {
            $sql = 'UPDATE stok SET qty = qty - ?, updated_at = ? WHERE produk_id = ? AND qty >= ?';
            $this->db->query($sql, [$qtyStr, date('Y-m-d H:i:s'), $produkId, $qtyStr]);

            if ($this->db->affectedRows() === 0) {
                throw new StokTidakCukupException(sprintf(
                    'Stok untuk produk "%s" tidak mencukupi! Stok saat ini: %s, diminta: %s',
                    $namaProduk,
                    number_format((float) $currentQty, 0, ',', '.'),
                    number_format((float) $qtyStr, 0, ',', '.')
                ));
            }

            $newSaldo = bcsub($currentQty, $qtyStr, 3);
        } else {
            if (!$managerApproved) {
                throw new StokTidakCukupException('Pengurangan stok minus memerlukan otorisasi manajer.');
            }

            $this->db->table('stok')
                ->where('produk_id', $produkId)
                ->set('qty', 'qty - ' . $this->db->escape($qtyStr), false)
                ->set('updated_at', date('Y-m-d H:i:s'))
                ->update();

            $newSaldo = bcsub($currentQty, $qtyStr, 3);

            // Audit trail for authorized negative stock override
            $this->auditService->log(
                'negative_stock_override',
                'stok',
                $produkId,
                ['qty_lama' => $currentQty],
                ['qty_baru' => $newSaldo, 'dokumen' => $referensiDokumen, 'alasan' => 'Otorisasi Manajer']
            );
        }

        // Append to stok_log (append-only)
        $this->db->table('stok_log')->insert([
            'produk_id'         => $produkId,
            'tipe'              => 'out',
            'qty'               => $qtyStr,
            'saldo_akhir'       => $newSaldo,
            'referensi_dokumen' => $referensiDokumen,
            'operator_id'       => $operatorId,
            'keterangan'        => $keterangan ?? 'Pengeluaran stok barang',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return $newSaldo;
    }

    /**
     * Penyesuaian stok fisik (Stock Opname).
     * Selalu mencatat selisih ke stok_log dengan tipe 'adj'.
     */
    public function penyesuaian(
        int $produkId,
        float|string $qtyFisikBaru,
        string $referensiDokumen,
        string $alasan,
        ?int $operatorId = null
    ): string {
        $qtyBaruStr = (string) $qtyFisikBaru;
        $operatorId = $operatorId ?? (session()->get('user')['id'] ?? null);

        $stokRow = $this->db->table('stok')->where('produk_id', $produkId)->get()->getRow();
        $qtyLama = $stokRow ? (string) $stokRow->qty : '0.000';
        $selisih = bcsub($qtyBaruStr, $qtyLama, 3);

        $this->db->table('stok')
            ->where('produk_id', $produkId)
            ->update([
                'qty'        => $qtyBaruStr,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // Append to stok_log
        $this->db->table('stok_log')->insert([
            'produk_id'         => $produkId,
            'tipe'              => 'adj',
            'qty'               => $selisih,
            'saldo_akhir'       => $qtyBaruStr,
            'referensi_dokumen' => $referensiDokumen,
            'operator_id'       => $operatorId,
            'keterangan'        => 'Stock Opname / Penyesuaian: ' . $alasan,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        $this->auditService->log('stock_adjustment', 'stok', $produkId, ['qty_lama' => $qtyLama], ['qty_baru' => $qtyBaruStr, 'alasan' => $alasan]);

        return $qtyBaruStr;
    }

    /**
     * Alokasi / cadangkan stok (Reserved) untuk pesanan yang belum dikirim.
     */
    public function reserve(int $produkId, float|string $qty): void
    {
        $qtyStr = (string) $qty;
        $stok = $this->getStok($produkId);

        if (bccomp($stok['tersedia'], $qtyStr, 3) < 0) {
            throw new StokTidakCukupException('Stok tersedia tidak mencukupi untuk reservasi pesanan');
        }

        $this->db->table('stok')
            ->where('produk_id', $produkId)
            ->set('reserved_qty', 'reserved_qty + ' . $this->db->escape($qtyStr), false)
            ->set('updated_at', date('Y-m-d H:i:s'))
            ->update();
    }

    /**
     * Lepas reservasi stok (misal jika pesanan dibatalkan).
     */
    public function releaseReserve(int $produkId, float|string $qty): void
    {
        $qtyStr = (string) $qty;
        $this->db->table('stok')
            ->where('produk_id', $produkId)
            ->set('reserved_qty', 'GREATEST(0, reserved_qty - ' . $this->db->escape($qtyStr) . ')', false)
            ->set('updated_at', date('Y-m-d H:i:s'))
            ->update();
    }

    /**
     * Ambil data stok terperinci: fisik, reserved, tersedia, minimum, dan status low-stock.
     */
    public function getStok(int $produkId): array
    {
        $builder = $this->db->table('produk p');
        $builder->select('p.id, p.nama_produk, p.stok_minimum, COALESCE(s.qty, 0) as fisik, COALESCE(s.reserved_qty, 0) as reserved, COALESCE(s.lokasi_rak, "RAK-UTAMA") as lokasi_rak');
        $builder->join('stok s', 's.produk_id = p.id', 'left');
        $builder->where('p.id', $produkId);
        $row = $builder->get()->getRow();

        if (!$row) {
            throw new RuntimeException('Produk tidak ditemukan');
        }

        $fisik = (string) $row->fisik;
        $reserved = (string) $row->reserved;
        $tersedia = bcsub($fisik, $reserved, 3);
        $minimum = (int) $row->stok_minimum;
        $isLow = bccomp($tersedia, (string) $minimum, 3) <= 0;

        return [
            'produk_id'    => (int) $row->id,
            'nama_produk'  => $row->nama_produk,
            'fisik'        => $fisik,
            'reserved'     => $reserved,
            'tersedia'     => $tersedia,
            'stok_minimum' => $minimum,
            'lokasi_rak'   => $row->lokasi_rak,
            'is_low'       => $isLow,
        ];
    }
}

