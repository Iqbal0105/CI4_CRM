<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('stok') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Monitoring Stok
    </a>
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mt-2">
        <div>
            <h3 class="fw-bold mb-1">Kartu Stok: <?= esc($produk['nama_produk']) ?></h3>
            <div class="text-muted small">
                SKU: <span class="font-monospace fw-bold text-dark"><?= esc($produk['kode_produk']) ?></span> &bull; 
                Lokasi Rak: <span class="badge bg-light text-dark border"><?= esc($currentStok['lokasi_rak']) ?></span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-lg-auto">
            <div class="card px-3 py-2 border-0 bg-white shadow-sm text-center flex-fill">
                <div class="text-muted small text-uppercase fw-semibold">Stok Fisik</div>
                <div class="fs-4 fw-bold text-dark"><?= number_format((float) $currentStok['fisik'], 0, ',', '.') ?></div>
            </div>
            <div class="card px-3 py-2 border-0 bg-white shadow-sm text-center flex-fill">
                <div class="text-muted small text-uppercase fw-semibold">Reserved</div>
                <div class="fs-4 fw-bold text-muted"><?= number_format((float) $currentStok['reserved'], 0, ',', '.') ?></div>
            </div>
            <div class="card px-3 py-2 border-0 bg-white shadow-sm text-center flex-fill">
                <div class="text-muted small text-uppercase fw-semibold">Tersedia</div>
                <div class="fs-4 fw-bold <?= $currentStok['is_low'] ? 'text-danger' : 'text-success' ?>">
                    <?= number_format((float) $currentStok['tersedia'], 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <span class="fw-bold"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Buku Mutasi Inventori (Append-Only Ledger)</span>
        <a href="<?= base_url('stok/opname') ?>" class="btn btn-outline-warning btn-sm text-dark">
            <i class="fa-solid fa-pen-to-square me-1"></i> Sesuaikan Stok (Opname)
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 160px;">Waktu & Tanggal</th>
                        <th>Tipe Mutasi</th>
                        <th>No. Referensi Dokumen</th>
                        <th class="text-end" style="width: 120px;">Qty Mutasi</th>
                        <th class="text-end" style="width: 120px;">Saldo Akhir</th>
                        <th>Operator</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-boxes-packing fs-3 mb-2 d-block text-secondary opacity-50"></i>
                                Belum ada riwayat mutasi stok untuk produk ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="small text-muted font-monospace"><?= format_datetime($log['created_at']) ?></td>
                                <td>
                                    <?php if ($log['tipe'] === 'in'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-arrow-down me-1"></i>Masuk (IN)</span>
                                    <?php elseif ($log['tipe'] === 'out'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fa-solid fa-arrow-up me-1"></i>Keluar (OUT)</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="fa-solid fa-sliders me-1"></i>Koreksi (ADJ)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary"><?= esc($log['referensi_dokumen']) ?></span>
                                </td>
                                <td class="text-end font-monospace fw-bold <?= $log['tipe'] === 'in' ? 'text-success' : ($log['tipe'] === 'out' ? 'text-danger' : 'text-warning') ?>">
                                    <?= $log['tipe'] === 'in' ? '+' : ($log['tipe'] === 'out' ? '-' : '') ?><?= number_format((float) abs((float) $log['qty']), 0, ',', '.') ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark">
                                    <?= number_format((float) $log['saldo_akhir'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="small text-dark fw-medium"><?= esc($log['operator_nama'] ?? 'Sistem') ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= esc($log['keterangan'] ?? '-') ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

