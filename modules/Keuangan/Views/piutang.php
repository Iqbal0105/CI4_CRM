<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
@media print {
    @page {
        size: landscape;
        margin: 8mm 6mm;
    }
    html, body {
        background-color: #ffffff !important;
        color: #1e293b !important;
        font-size: 8pt !important;
        width: 100% !important;
    }
    #sidebar, .top-navbar, .sidebar-backdrop, .app-footer, .breadcrumb,
    .d-print-none, .btn, .modal, .toast-container, .alert,
    th:last-child, td:last-child {
        display: none !important;
    }
    #main-content, .content-wrapper, .container-fluid, .card, .card-body {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        overflow: visible !important;
    }
    .stat-cards-row {
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 8px !important;
        margin-bottom: 12px !important;
    }
    .stat-cards-row > div {
        flex: 1 1 25% !important;
        max-width: 25% !important;
    }
    .stat-cards-row .card {
        padding: 6px 8px !important;
        border: 1px solid #cbd5e1 !important;
    }
    .stat-cards-row .fs-4 {
        font-size: 0.95rem !important;
    }
    .table-responsive {
        overflow: visible !important;
        display: block !important;
        width: 100% !important;
    }
    #tablePiutangUsaha {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: auto !important;
        border-collapse: collapse !important;
        font-size: 7.5pt !important;
    }
    #tablePiutangUsaha th,
    #tablePiutangUsaha td {
        padding: 5px 6px !important;
        font-size: 7.5pt !important;
        white-space: normal !important;
        word-break: break-word !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle !important;
    }
    #tablePiutangUsaha thead th {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: 1px solid #cbd5e1 !important;
        font-size: 7pt !important;
        padding: 2px 4px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Piutang Usaha (AR)</h4>
        <p class="text-muted small mb-0">Monitoring &amp; Pelunasan Tagihan Penjualan Pelanggan</p>
        <div class="d-none d-print-block text-muted small mt-1">Dicetak pada: <?= date('d/m/Y H:i') ?></div>
    </div>
    <div class="d-flex gap-2 flex-wrap d-print-none">
        <a href="<?= base_url('keuangan/piutang/export') ?>" class="btn btn-sm btn-success fw-semibold px-3 shadow-xs">
            <i class="fa-solid fa-file-excel me-1"></i>Export XLS
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger fw-semibold px-3" onclick="window.print()">
            <i class="fa-solid fa-file-pdf me-1"></i>Export PDF
        </button>
        <a href="<?= base_url('keuangan/bkm') ?>" class="btn btn-sm btn-light border fw-semibold px-3">
            <i class="fa-solid fa-clock-rotate-left me-1"></i>Riwayat Kas Masuk (BKM)
        </a>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (KEUANGAN & KAS) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('keuangan/piutang') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
        <i class="fa-solid fa-hand-holding-dollar me-1"></i> Piutang Usaha (AR)
    </a>
    <a href="<?= base_url('keuangan/bkm') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-arrow-down-long me-1"></i> Penerimaan Kas (BKM)
    </a>
    <a href="<?= base_url('keuangan/utang') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Utang Usaha (AP)
    </a>
    <a href="<?= base_url('keuangan/bkk') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-arrow-up-long me-1"></i> Pengeluaran Kas (BKK)
    </a>
    <a href="<?= base_url('keuangan/jurnal') ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-book me-1"></i> Buku Besar (GL)
    </a>
</div>

<!-- 4 STAT CARDS -->
<div class="row g-3 mb-4 stat-cards-row">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 d-flex flex-column justify-content-between" style="border-radius: 10px; border-left: 4px solid #2D6A4F !important;">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.75rem; line-height: 1.25;">TOTAL HAK PIUTANG</span>
                    <span class="badge bg-light text-primary border small" style="font-size: 0.72rem;">SEMUA</span>
                </div>
                <div class="fs-4 fw-bold text-dark font-monospace">Rp <?= number_format((float)$totalHakPiutang, 0, ',', '.') ?></div>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.78rem;"><i class="fa-solid fa-file-invoice me-1"></i><?= count($piutangList) ?> Total Faktur Penjualan</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 d-flex flex-column justify-content-between" style="border-radius: 10px; border-left: 4px solid #10b981 !important;">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.75rem; line-height: 1.25;">TOTAL TELAH DITERIMA</span>
                    <i class="fa-regular fa-money-bill-1 text-success opacity-50 fs-6"></i>
                </div>
                <div class="fs-4 fw-bold text-success font-monospace">Rp <?= number_format((float)$totalDiterima, 0, ',', '.') ?></div>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.78rem;"><i class="fa-solid fa-arrow-down-long text-success me-1"></i>Kas Masuk Terealisasi</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 d-flex flex-column justify-content-between" style="border-radius: 10px; border-left: 4px solid #f59e0b !important;">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.75rem; line-height: 1.25;">SISA TAGIHAN BELUM LUNAS</span>
                    <i class="fa-solid fa-hourglass-half text-warning opacity-50 fs-6"></i>
                </div>
                <div class="fs-4 fw-bold text-dark font-monospace">Rp <?= number_format((float)$sisaBelumLunas, 0, ',', '.') ?></div>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.78rem;">Saldo Piutang Aktif</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 d-flex flex-column justify-content-between" style="border-radius: 10px; border-left: 4px solid #ef4444 !important;">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.75rem; line-height: 1.25;">LEWAT JATUH TEMPO (OVERDUE)</span>
                    <i class="fa-solid fa-bell text-danger opacity-50 fs-6"></i>
                </div>
                <div class="fs-4 fw-bold text-danger font-monospace">Rp 0</div>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.78rem;"><i class="fa-solid fa-triangle-exclamation text-danger me-1"></i><?= $overdueCount ?> Tagihan Mendesak Ditagih</div>
        </div>
    </div>
</div>

<!-- CARD BUKU BESAR PIUTANG -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-book-bookmark text-primary"></i> Buku Besar Piutang Usaha &amp; Status Pelunasan
        </h6>
    </div>
    <div class="card-body p-3">
        <!-- FILTER ROW -->
        <div class="row g-2 align-items-center mb-3 d-print-none">
            <div class="col-12 col-md-3">
                <select class="form-select form-select-sm" id="filterPelangganPiutang">
                    <option value="">-- Semua Pelanggan --</option>
                    <?php 
                        $pelangganUnique = array_unique(array_column($piutangList, 'nama_pelanggan'));
                        foreach ($pelangganUnique as $pName):
                    ?>
                        <option value="<?= esc($pName) ?>"><?= esc($pName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select form-select-sm" id="filterStatusPiutang">
                    <option value="">-- Semua Status --</option>
                    <option value="lunas">Lunas (Paid)</option>
                    <option value="sebagian">Sebagian</option>
                    <option value="belum_bayar">Belum Bayar</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <input type="text" id="searchPiutang" class="form-control form-control-sm" placeholder="Cari No. Piutang / Pelanggan...">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-primary btn-sm w-100 fw-semibold" id="btnFilterPiutang">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePiutangUsaha">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">No</th>
                        <th>No. Tagihan Piutang</th>
                        <th>Pelanggan</th>
                        <th>Ref Transaksi / Invoice</th>
                        <th>Tgl Faktur / Jatuh Tempo</th>
                        <th class="text-end">Total Tagihan</th>
                        <th class="text-end">Telah Diterima</th>
                        <th class="text-end">Sisa Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="pe-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($piutangList)): ?>
                        <?php foreach ($piutangList as $idx => $pu): ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary"><?= esc($pu['nomor_piutang']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?= esc($pu['nama_pelanggan']) ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><i class="fa-solid fa-tag me-1 text-secondary"></i><?= esc($pu['kode_pelanggan'] ?? 'CUST-'.str_pad((string)$pu['pelanggan_id'], 4, '0', STR_PAD_LEFT)) ?></div>
                                </td>
                                <td>
                                    <div class="small text-muted font-monospace">Penjualan: <?= esc($pu['nomor_so'] ?? '-') ?></div>
                                    <span class="badge bg-info bg-opacity-10 text-info fw-semibold font-monospace" style="font-size: 0.78rem;">
                                        INV: INV-<?= date('Ym', strtotime($pu['created_at'] ?? 'now')) ?>-<?= str_pad((string)$pu['id'], 4, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td class="small text-secondary font-monospace">
                                    <div><i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($pu['created_at'] ?? 'now')) ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;">JT: <?= date('d/m/Y', strtotime($pu['jatuh_tempo'] ?? 'now')) ?></div>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark small">
                                    Rp <?= number_format((float)$pu['total'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end font-monospace text-success small">
                                    Rp <?= number_format((float)$pu['telah_dibayar'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end font-monospace fw-bold <?= (float)$pu['sisa'] > 0 ? 'text-danger' : 'text-muted' ?> small">
                                    Rp <?= number_format((float)$pu['sisa'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($pu['status'] === 'lunas'): ?>
                                        <span class="badge rounded-pill bg-success px-2 py-1 fw-bold" style="font-size: 0.78rem;">LUNAS (PAID)</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-warning text-dark px-2 py-1 fw-bold" style="font-size: 0.78rem;">BELUM LUNAS</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-3 text-end">
                                    <?php if ($pu['status'] === 'lunas'): ?>
                                        <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Selesai</span>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-primary py-0 px-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalBayarPiutang<?= $pu['id'] ?>">
                                            Bayar
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Belum ada data piutang usaha.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pt-3 text-muted small">
            Total Data: <?= count($piutangList) ?> Record
        </div>
    </div>
</div>

<!-- MODAL BAYAR PIUTANG -->
<?php if (!empty($piutangList)): ?>
    <?php foreach ($piutangList as $pu): ?>
        <?php if ($pu['status'] !== 'lunas'): ?>
            <div class="modal fade" id="modalBayarPiutang<?= $pu['id'] ?>" tabindex="-1" aria-labelledby="modalLabelPiutang<?= $pu['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="<?= base_url('keuangan/bayar-piutang') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="piutang_id" value="<?= $pu['id'] ?>">
                            <div class="modal-header bg-primary text-white py-3">
                                <h6 class="modal-title fw-bold mb-0" id="modalLabelPiutang<?= $pu['id'] ?>">
                                    <i class="fa-solid fa-money-bill-wave me-2"></i>Penerimaan Pembayaran Piutang
                                </h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="p-3 bg-light rounded mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>No. Tagihan:</span>
                                        <span class="fw-bold font-monospace text-dark"><?= esc($pu['nomor_piutang']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Pelanggan:</span>
                                        <span class="fw-bold text-dark"><?= esc($pu['nama_pelanggan']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Total Tagihan:</span>
                                        <span class="font-monospace">Rp <?= number_format((float)$pu['total'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-0">
                                        <span class="fw-bold text-danger">Sisa Belum Lunas:</span>
                                        <span class="fw-bold text-danger font-monospace fs-6">Rp <?= number_format((float)$pu['sisa'], 0, ',', '.') ?></span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">Akun Kas / Bank Penerima <span class="text-danger">*</span></label>
                                    <select name="akun_kas_bank_id" class="form-select form-select-sm" required>
                                        <?php if (!empty($kasBankList)): ?>
                                            <?php foreach ($kasBankList as $kb): ?>
                                                <option value="<?= $kb['id'] ?>"><?= esc($kb['nama_akun']) ?> (<?= esc($kb['nomor_rekening'] ?? '-') ?>)</option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1">Kas Utama (Cash)</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-dark">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_bayar" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-dark">Metode Pembayaran</label>
                                        <select name="metode_pembayaran" class="form-select form-select-sm">
                                            <option value="transfer">Bank Transfer</option>
                                            <option value="tunai">Tunai / Cash</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-bold text-dark mb-0">Nominal Pembayaran (Rp) <span class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small" style="font-size: 0.72rem;" onclick="document.getElementById('inputNominalPiutang<?= $pu['id'] ?>').value = '<?= (float)$pu['sisa'] ?>'">
                                            Isi Sisa Piutang
                                        </button>
                                    </div>
                                    <input type="number" step="any" min="1" id="inputNominalPiutang<?= $pu['id'] ?>" name="nominal" class="form-control font-monospace fw-bold text-success" value="<?= (float)$pu['sisa'] ?>" required>
                                    <div class="form-text small" style="font-size: 0.75rem;">Otomatis terisi sesuai sisa piutang (Rp <?= number_format((float)$pu['sisa'], 0, ',', '.') ?>). Mendukung pembulatan transfer atau pembayaran bertahap.</div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-dark">Catatan / Referensi</label>
                                    <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Contoh: Transfer via BCA ref #8921"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2 px-4">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-sm btn-primary fw-semibold">
                                    <i class="fa-solid fa-receipt me-1"></i>Simpan &amp; Terbitkan BKM
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterPelanggan = document.getElementById('filterPelangganPiutang');
    const filterStatus = document.getElementById('filterStatusPiutang');
    const searchInput = document.getElementById('searchPiutang');
    const btnFilter = document.getElementById('btnFilterPiutang');
    const rows = document.querySelectorAll('#tablePiutangUsaha tbody tr');

    function filterTable() {
        const pel = filterPelanggan.value.toLowerCase();
        const stat = filterStatus.value.toLowerCase();
        const q = searchInput.value.toLowerCase().trim();

        rows.forEach(r => {
            const txt = r.textContent.toLowerCase();
            const matchesPel = !pel || txt.includes(pel);
            const matchesStat = !stat || (stat === 'lunas' && txt.includes('lunas')) || (stat !== 'lunas' && !txt.includes('lunas (paid)'));
            const matchesQ = !q || txt.includes(q);

            r.style.display = (matchesPel && matchesStat && matchesQ) ? '' : 'none';
        });
    }

    if (btnFilter) btnFilter.addEventListener('click', filterTable);
    if (filterPelanggan) filterPelanggan.addEventListener('change', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
    if (searchInput) searchInput.addEventListener('keyup', filterTable);
});
</script>
<?= $this->endSection() ?>
