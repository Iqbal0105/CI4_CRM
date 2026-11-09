<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('pembelian') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Purchase Order
    </a>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mt-2">
        <div>
            <h3 class="fw-bold mb-1">Purchase Order #<?= esc($po['nomor_po']) ?></h3>
            <div class="text-muted small">
                Tanggal: <strong><?= format_tanggal($po['tanggal_po']) ?></strong> &bull; 
                Supplier: <strong><?= esc($po['nama_supplier']) ?></strong>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <?php if (in_array($po['status'], ['approved', 'partial'])): ?>
                <a href="<?= base_url('pembelian/penerimaan/' . $po['id']) ?>" class="btn btn-success flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                    <span>Verifikasi Penerimaan Barang</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Rincian Barang yang Dipesan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item Produk</th>
                                <th class="text-center" style="width: 100px;">Qty Pesan</th>
                                <th class="text-center" style="width: 110px;">Qty Diterima</th>
                                <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                <th class="text-end" style="width: 140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <?php
                                $pesan = (float) $item['qty'];
                                $terima = (float) $item['qty_diterima'];
                                $isLengkap = $terima >= $pesan;
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></div>
                                        <small class="text-muted font-monospace"><?= esc($item['kode_produk']) ?></small>
                                    </td>
                                    <td class="text-center font-monospace"><?= number_format($pesan, 0, ',', '.') ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $isLengkap ? 'bg-success' : ($terima > 0 ? 'bg-warning text-dark' : 'bg-secondary') ?> font-monospace">
                                            <?= number_format($terima, 0, ',', '.') ?>
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace">
                                        <div><?= format_valas($item['harga_satuan'], $po['mata_uang']) ?></div>
                                        <?php if ($po['mata_uang'] !== 'IDR'): ?>
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">≈ <?= format_rupiah(bcmul((string) $item['harga_satuan'], (string) $po['nilai_kurs'], 2)) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end font-monospace fw-bold">
                                        <div><?= format_valas($item['subtotal'], $po['mata_uang']) ?></div>
                                        <?php if ($po['mata_uang'] !== 'IDR'): ?>
                                            <small class="text-muted fw-normal d-block" style="font-size: 0.72rem;">≈ <?= format_rupiah(bcmul((string) $item['subtotal'], (string) $po['nilai_kurs'], 2)) ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <?php if ($po['mata_uang'] !== 'IDR'): ?>
                                <tr>
                                    <td colspan="4" class="text-end fw-semibold text-secondary">Total Nilai Valas (<?= esc($po['mata_uang']) ?>):</td>
                                    <td class="text-end font-monospace fw-bold fs-6 text-dark"><?= format_valas($po['total'], $po['mata_uang']) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-semibold text-muted small">Kurs Transaksi:</td>
                                    <td class="text-end font-monospace text-muted small">1 <?= esc($po['mata_uang']) ?> = Rp <?= number_format((float) $po['nilai_kurs'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="4" class="text-end fw-bold text-dark">Total Nilai PO (IDR):</td>
                                <td class="text-end font-monospace fw-bold fs-5 text-success"><?= format_rupiah($po['total_idr']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold">Ringkasan Dokumen</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Status:</span>
                    <span class="badge bg-primary text-uppercase"><?= esc($po['status']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Termin:</span>
                    <span class="fw-semibold text-dark text-capitalize"><?= esc($po['termin']) ?></span>
                </div>
                <?php if ($po['termin'] === 'tempo'): ?>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Jatuh Tempo:</span>
                        <span class="fw-semibold text-danger"><?= format_tanggal($po['jatuh_tempo']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Mata Uang:</span>
                    <span class="font-monospace fw-semibold badge bg-dark"><?= esc($po['mata_uang']) ?></span>
                </div>
                <?php if ($po['mata_uang'] !== 'IDR'): ?>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Nilai Kurs:</span>
                        <span class="font-monospace">Rp <?= number_format((float) $po['nilai_kurs'], 2, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Total Valas:</span>
                        <span class="font-monospace fw-bold text-dark"><?= format_valas($po['total'], $po['mata_uang']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Total Akhir IDR:</span>
                    <span class="font-monospace fw-bold text-success fs-6"><?= format_rupiah($po['total_idr']) ?></span>
                </div>
                <?php if (!empty($po['catatan'])): ?>
                    <div class="mt-3">
                        <span class="text-muted small fw-semibold">Catatan:</span>
                        <p class="small text-dark mb-0"><?= nl2br(esc($po['catatan'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

