<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
@media print {
    @page {
        size: landscape;
        margin: 6mm 6mm;
    }
    html, body {
        background-color: #ffffff !important;
        color: #1e293b !important;
        font-size: 7.5pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    #sidebar, .top-navbar, .sidebar-backdrop, .app-footer, .breadcrumb,
    .d-print-none, .btn, .modal, .toast-container, .alert,
    .dataTables_info, .dataTables_paginate, .dataTables_length, .dataTables_filter,
    th:last-child, td:last-child {
        display: none !important;
    }
    table.dataTable thead th:before,
    table.dataTable thead th:after,
    table.dataTable thead td:before,
    table.dataTable thead td:after {
        display: none !important;
        content: "" !important;
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
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 6px !important;
        margin-bottom: 10px !important;
        width: 100% !important;
    }
    .stat-cards-row > div {
        flex: 1 1 25% !important;
        max-width: 25% !important;
        width: 25% !important;
    }
    .stat-cards-row .card {
        padding: 4px 6px !important;
        border: 1px solid #cbd5e1 !important;
    }
    .stat-cards-row .fs-4 {
        font-size: 0.85rem !important;
    }
    .stat-cards-row .rounded-circle {
        display: none !important;
    }
    .dataTables_wrapper,
    .dataTables_wrapper .dataTables_scroll,
    .dataTables_wrapper .dataTables_scrollHead,
    .dataTables_wrapper .dataTables_scrollBody,
    .table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    #tableBukuBesar {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 7.5pt !important;
    }
    #tableBukuBesar th,
    #tableBukuBesar td {
        padding: 4px 5px !important;
        font-size: 7.5pt !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }
    #tableBukuBesar th:nth-child(1), #tableBukuBesar td:nth-child(1) { width: 4% !important; text-align: center !important; }
    #tableBukuBesar th:nth-child(2), #tableBukuBesar td:nth-child(2) { width: 9% !important; }
    #tableBukuBesar th:nth-child(3), #tableBukuBesar td:nth-child(3) { width: 14% !important; font-family: monospace !important; }
    #tableBukuBesar th:nth-child(4), #tableBukuBesar td:nth-child(4) { width: 12% !important; text-align: center !important; }
    #tableBukuBesar th:nth-child(5), #tableBukuBesar td:nth-child(5) { width: 14% !important; }
    #tableBukuBesar th:nth-child(6), #tableBukuBesar td:nth-child(6) { width: 27% !important; max-width: none !important; }
    #tableBukuBesar th:nth-child(7), #tableBukuBesar td:nth-child(7) { width: 10% !important; text-align: right !important; }
    #tableBukuBesar th:nth-child(8), #tableBukuBesar td:nth-child(8) { width: 10% !important; text-align: right !important; }
    #tableBukuBesar th:last-child, #tableBukuBesar td:last-child { display: none !important; width: 0 !important; }

    #tableBukuBesar thead th {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        background: transparent !important;
        font-size: 7pt !important;
        padding: 1px 4px !important;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">
            <i class="fa-solid fa-book-journal-whills text-primary me-2"></i>Buku Jurnal Umum &amp; Buku Besar (GL)
        </h4>
        <p class="text-muted small mb-0">Audit Trail Pembukuan Berpasangan (Double-Entry Bookkeeping) Seluruh Transaksi Perusahaan</p>
        <div class="d-none d-print-block text-muted small mt-1">Dicetak pada: <?= date('d/m/Y H:i') ?></div>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span> &gt;
        <span class="text-secondary">Keuangan</span> &gt;
        <span class="text-dark fw-medium">Buku Besar</span>
    </div>
</div>

<!-- INFO BANNER ARUS PEMBUKUAN GL -->
<div class="alert alert-light border shadow-xs d-flex align-items-center gap-3 py-2 px-3 mb-4 d-print-none" style="border-radius: 8px; border-left: 4px solid #2D6A4F !important;">
    <i class="fa-solid fa-circle-info text-success fs-5"></i>
    <div class="small text-muted">
        <strong class="text-dark">Arus Pembukuan Buku Besar (General Ledger):</strong> Transaksi disajikan sesuai arus data: 
        <span class="badge bg-success-subtle text-success border border-success-subtle">Sisi Debit (D)</span> mencatat transaksi masuk (Penjualan, Piutang, Kas Masuk) dan 
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Sisi Kredit (K)</span> mencatat transaksi keluar (Pembelian, Utang, Kas Keluar). Rincian akuntansi berpasangan (double-entry) dapat dilihat lengkap melalui tombol aksi rincian.
    </div>
</div>

<!-- 4 STAT CARDS -->
<div class="row g-3 mb-4 stat-cards-row">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" id="cardFilterDebit" style="border-radius: 10px; border-left: 4px solid #10b981 !important; cursor: pointer;" title="Klik untuk filter transaksi masuk (Debit)">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.78rem;">TOTAL TRANSAKSI MASUK (DEBIT)</span>
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="fa-solid fa-arrow-down-long"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark font-monospace">Rp <?= number_format((float)($totalDebit ?? 0), 0, ',', '.') ?></div>
            <div class="text-success small fw-semibold" style="font-size: 0.78rem;"><i class="fa-solid fa-filter me-1"></i>Klik untuk filter Debit</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" id="cardFilterKredit" style="border-radius: 10px; border-left: 4px solid #ef4444 !important; cursor: pointer;" title="Klik untuk filter transaksi keluar (Kredit)">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.78rem;">TOTAL TRANSAKSI KELUAR (KREDIT)</span>
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="fa-solid fa-arrow-up-long"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark font-monospace">Rp <?= number_format((float)($totalKredit ?? 0), 0, ',', '.') ?></div>
            <div class="text-danger small fw-semibold" style="font-size: 0.78rem;"><i class="fa-solid fa-filter me-1"></i>Klik untuk filter Kredit</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" id="cardFilterKasBank" style="border-radius: 10px; border-left: 4px solid #2D6A4F !important; cursor: pointer;" title="Klik filter kas & bank">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.78rem;">ARUS KAS &amp; NERACA BERSIH</span>
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold font-monospace <?= ($arusKas ?? 0) < 0 ? 'text-danger' : 'text-success' ?>">
                <?= ($arusKas ?? 0) >= 0 ? '+' : '' ?>Rp <?= number_format((float)($arusKas ?? 0), 0, ',', '.') ?>
            </div>
            <div class="text-primary small fw-semibold" style="font-size: 0.78rem;"><i class="fa-solid fa-filter me-1"></i>Arus Bersih (Masuk - Keluar)</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" id="cardFilterAll" style="border-radius: 10px; border-left: 4px solid #8b5cf6 !important; cursor: pointer;" title="Klik untuk menampilkan semua transaksi">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.78rem;">TOTAL CATATAN TRANSAKSI</span>
                <div class="rounded-circle bg-purple bg-opacity-10 text-purple d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="fa-solid fa-check text-success"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark font-monospace"><?= $totalCount ?? 0 ?> Entry</div>
            <div class="text-secondary small fw-semibold" style="font-size: 0.78rem;"><i class="fa-solid fa-rotate-left me-1"></i>Tampilkan Semua Entry</div>
        </div>
    </div>
</div>

<!-- FILTER CARD -->
<div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-filter text-primary"></i> Filter &amp; Pencarian Laporan Buku Besar
        </h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('keuangan/jurnal/export') ?>" id="btnExportJurnal" class="btn btn-sm btn-success fw-semibold px-3 shadow-xs">
                <i class="fa-solid fa-file-excel me-1"></i>Export Excel / CSV
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger fw-semibold px-3" onclick="window.print()">
                <i class="fa-solid fa-file-pdf me-1"></i>Export PDF
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                <input type="date" id="filterTglDari" class="form-control form-control-sm" value="<?= date('Y-m-01') ?>">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                <input type="date" id="filterTglSampai" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted mb-1">Kategori Modul</label>
                <select class="form-select form-select-sm" id="filterModulGL">
                    <option value="">-- Semua Kategori --</option>
                    <option value="penjualan">Penjualan (Invoice)</option>
                    <option value="pembelian">Pembelian (PO)</option>
                    <option value="piutang">Piutang Usaha (AR)</option>
                    <option value="penerimaan">Kas Masuk (BKM)</option>
                    <option value="utang">Utang Usaha (AP)</option>
                    <option value="pengeluaran">Kas Keluar (BKK)</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Akun COA (Buku Besar)</label>
                <select class="form-select form-select-sm" id="filterCoaGL">
                    <option value="">-- Semua Akun (Jurnal Lengkap) --</option>
                    <?php if (!empty($coaList)): ?>
                        <?php foreach ($coaList as $c): ?>
                            <option value="<?= esc($c['kode_akun']) ?>">[<?= esc($c['kode_akun']) ?>] <?= esc($c['nama_akun']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Cari (No. Ref / Uraian / Akun)</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="searchJurnalInput" class="form-control form-control-sm" placeholder="Ketik kata kunci...">
                    <button class="btn btn-primary" type="button" id="btnCariGL"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetGL" title="Reset Filter"><i class="fa-solid fa-rotate-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GENERAL LEDGER TABLE -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableBukuBesar">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">No</th>
                        <th>Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Kategori Modul</th>
                        <th>Pihak Terkait</th>
                        <th style="min-width: 260px;">Keterangan</th>
                        <th class="text-end" style="min-width: 120px;">SISI DEBIT (D)</th>
                        <th class="text-end" style="min-width: 120px;">SISI KREDIT (K)</th>
                        <th class="pe-3 text-end" style="width: 50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($jurnalList)): ?>
                        <?php foreach ($jurnalList as $idx => $j): ?>
                            <?php 
                                $jDetails = $details[$j['id']] ?? [];
                                $isDeb = $j['is_debit'] ?? true;
                                $nom = (float)($j['nominal'] ?? $j['total_debit'] ?? 0);
                            ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td class="small text-secondary font-monospace" data-order="<?= esc($j['tanggal']) ?>">
                                    <i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($j['tanggal'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1 small"><?= esc($j['referensi'] ?: ($j['nomor_transaksi'] ?? $j['nomor_jurnal'])) ?></span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2 py-1 small fw-semibold" style="<?= $j['modul_style'] ?? 'background-color: #e0f2fe; color: #0284c7;' ?>">
                                        <?= esc($j['modul_badge'] ?? 'Penjualan (Invoice)') ?>
                                    </span>
                                    <span class="d-none"><?= esc($j['modul_key'] ?? 'penjualan') ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?= esc($j['pihak_terkait'] ?? '-') ?></div>
                                </td>
                                <td class="small text-secondary" style="max-width: 320px; white-space: normal;">
                                    <?= esc($j['keterangan']) ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-success small">
                                    <?= ($isDeb && $nom > 0) ? 'Rp ' . number_format($nom, 0, ',', '.') : '-' ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-danger small">
                                    <?= (!$isDeb && $nom > 0) ? 'Rp ' . number_format($nom, 0, ',', '.') : '-' ?>
                                </td>
                                <td class="pe-3 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-view-jurnal rounded" 
                                        data-ref="<?= esc($j['referensi'] ?: ($j['nomor_transaksi'] ?? $j['nomor_jurnal'])) ?>"
                                        data-tgl="<?= date('d/m/Y', strtotime($j['tanggal'])) ?>"
                                        data-modul="<?= esc($j['modul_badge'] ?? 'Penjualan (Invoice)') ?>"
                                        data-entitas="<?= esc($j['pihak_terkait'] ?? 'Umum') ?>"
                                        data-ket="<?= esc($j['keterangan']) ?>"
                                        data-debit="<?= ($isDeb && $nom > 0) ? 'Rp ' . number_format($nom, 0, ',', '.') : '-' ?>"
                                        data-kredit="<?= (!$isDeb && $nom > 0) ? 'Rp ' . number_format($nom, 0, ',', '.') : '-' ?>"
                                        data-details="<?= htmlspecialchars(json_encode($jDetails), ENT_QUOTES) ?>"
                                        title="Lihat Rincian Voucher Jurnal">
                                        <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada transaksi di buku besar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL BUKU BESAR -->
<div class="modal fade" id="modalDetailJurnal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-book-bookmark me-2 text-primary"></i>Audit Trail &amp; Rincian Buku Besar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <label class="text-muted small">No. Referensi:</label>
                        <div class="font-monospace fw-bold text-primary" id="glModalRef">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="text-muted small">Tanggal Transaksi:</label>
                        <div class="font-monospace text-dark" id="glModalTgl">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="text-muted small">Kategori Modul:</label>
                        <div><span class="badge bg-primary bg-opacity-10 text-primary" id="glModalModul">-</span></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="text-muted small">Entitas Terkait:</label>
                        <div class="fw-semibold text-dark" id="glModalEntitas">-</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Uraian / Deskripsi Jurnal:</label>
                        <div class="p-2 bg-light rounded text-dark small" id="glModalKet">-</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-scale-balanced text-primary me-1"></i>Distribusi Saldo Akun Jurnal (Double-Entry)</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Akun COA / Buku Besar</th>
                                <th class="text-end" style="width: 160px;">Debit (D)</th>
                                <th class="text-end" style="width: 160px;">Kredit (K)</th>
                            </tr>
                        </thead>
                        <tbody id="glModalBody">
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">Memuat rincian jurnal...</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light small fw-bold">
                            <tr>
                                <td class="text-end">Total Nilai Jurnal:</td>
                                <td class="text-end text-success font-monospace" id="glModalTotalDebit">-</td>
                                <td class="text-end text-danger font-monospace" id="glModalTotalKredit">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()"><i class="fa-solid fa-print me-1"></i>Cetak Bukti Transaksi</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const table = $('#tableBukuBesar').DataTable({
            dom: 'rtip',
            pageLength: 15,
            order: [[1, 'desc']],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnCariGL').on('click', function() {
            table.search($('#searchJurnalInput').val()).draw();
        });

        $('#searchJurnalInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.search(this.value).draw();
            }
        });

        $('#filterModulGL').on('change', function() {
            table.column(3).search(this.value).draw();
        });

        $('#filterCoaGL').on('change', function() {
            table.search(this.value).draw();
        });

        // Client-side date filtering
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'tableBukuBesar') return true;
            const minStr = $('#filterTglDari').val();
            const maxStr = $('#filterTglSampai').val();
            if (!minStr && !maxStr) return true;
            const rowDate = $(table.row(dataIndex).node()).find('td:eq(1)').attr('data-order') || '';
            if (!rowDate) return true;
            if (minStr && rowDate < minStr) return false;
            if (maxStr && rowDate > maxStr) return false;
            return true;
        });

        $('#filterTglDari, #filterTglSampai').on('change', function() {
            table.draw();
        });

        $('#btnResetGL').on('click', function() {
            $('#searchJurnalInput').val('');
            $('#filterModulGL').val('');
            $('#filterCoaGL').val('');
            table.search('').columns().search('').draw();
        });

        // Export Excel / CSV dengan filter tanggal aktif
        $('#btnExportJurnal').on('click', function(e) {
            e.preventDefault();
            const dari = $('#filterTglDari').val();
            const sampai = $('#filterTglSampai').val();
            let url = '<?= base_url('keuangan/jurnal/export') ?>';
            const params = [];
            if (dari) params.push('tgl_dari=' + encodeURIComponent(dari));
            if (sampai) params.push('tgl_sampai=' + encodeURIComponent(sampai));
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            window.location.href = url;
        });

        // Stat Card click filters
        $('#cardFilterDebit').on('click', function() {
            table.search('').columns().search('');
            table.column(6).search('Rp').draw();
        });

        $('#cardFilterKredit').on('click', function() {
            table.search('').columns().search('');
            table.column(7).search('Rp').draw();
        });

        $('#cardFilterKasBank').on('click', function() {
            table.search('').columns().search('');
            table.column(3).search('Kas').draw();
        });

        $('#cardFilterAll').on('click', function() {
            $('#searchJurnalInput').val('');
            $('#filterModulGL').val('');
            $('#filterCoaGL').val('');
            table.search('').columns().search('').draw();
        });

        // Modal View handler
        $(document).on('click', '.btn-view-jurnal', function() {
            const btn = $(this);
            $('#glModalRef').text(btn.data('ref'));
            $('#glModalTgl').text(btn.data('tgl'));
            $('#glModalModul').text(btn.data('modul'));
            $('#glModalEntitas').text(btn.data('entitas'));
            $('#glModalKet').text(btn.data('ket'));

            const rawDetails = btn.attr('data-details');
            let details = [];
            try {
                details = typeof rawDetails === 'string' ? JSON.parse(rawDetails) : rawDetails;
            } catch (e) {
                details = [];
            }

            let rowsHtml = '';
            let totalDeb = 0;
            let totalKred = 0;
            if (Array.isArray(details) && details.length > 0) {
                details.forEach(d => {
                    const debNum = parseFloat(d.debit) || 0;
                    const kredNum = parseFloat(d.kredit) || 0;
                    totalDeb += debNum;
                    totalKred += kredNum;

                    const debVal = debNum > 0 ? 'Rp ' + Number(debNum.toFixed(2)).toLocaleString('id-ID') : '-';
                    const kredVal = kredNum > 0 ? 'Rp ' + Number(kredNum.toFixed(2)).toLocaleString('id-ID') : '-';
                    const posBadge = debNum > 0 
                        ? '<span class="badge bg-success-subtle text-success border border-success-subtle me-1 fw-bold">DEBIT</span>' 
                        : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle me-1 fw-bold">KREDIT</span>';

                    rowsHtml += `<tr>
                        <td>
                            ${posBadge} <span class="font-monospace fw-bold text-dark">[${d.kode_akun}]</span> <span class="fw-semibold">${d.nama_akun}</span>
                            ${d.memo ? '<div class="text-muted small ps-4 mt-0"><i class="fa-solid fa-angles-right text-secondary me-1"></i>' + d.memo + '</div>' : ''}
                        </td>
                        <td class="text-end font-monospace fw-bold text-success">${debVal}</td>
                        <td class="text-end font-monospace fw-bold text-danger">${kredVal}</td>
                    </tr>`;
                });
            } else {
                rowsHtml = '<tr><td colspan="3" class="text-center text-muted py-3">Tidak ada baris akun jurnal.</td></tr>';
            }

            $('#glModalBody').html(rowsHtml);
            $('#glModalTotalDebit').text(totalDeb > 0 ? 'Rp ' + Number(totalDeb.toFixed(2)).toLocaleString('id-ID') : '-');
            $('#glModalTotalKredit').text(totalKred > 0 ? 'Rp ' + Number(totalKred.toFixed(2)).toLocaleString('id-ID') : '-');

            const modal = new bootstrap.Modal(document.getElementById('modalDetailJurnal'));
            modal.show();
        });

        // Export CSV & Excel
        function exportGL(ext) {
            let csv = "No,Tanggal,No Ref,Kategori Modul,Entitas,Deskripsi,Debit,Kredit\n";
            $('#tableBukuBesar tbody tr').each(function() {
                const cols = $(this).find('td');
                if (cols.length >= 8) {
                    const row = [];
                    cols.each(function(i) {
                        if (i < 8) {
                            row.push('"' + $(this).text().trim().replace(/"/g, '""').replace(/\s+/g, ' ') + '"');
                        }
                    });
                    csv += row.join(',') + "\n";
                }
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", "Buku_Besar_" + new Date().toISOString().slice(0,10) + "." + ext);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        $('#btnExportGLCsv').on('click', function() { exportGL('csv'); });
        $('#btnExportGLExcel').on('click', function() { exportGL('xls'); });

        // Tampilkan seluruh data saat export PDF / cetak halaman
        window.addEventListener('beforeprint', () => {
            if (typeof table !== 'undefined') {
                table.page.len(-1).draw();
            }
        });
        window.addEventListener('afterprint', () => {
            if (typeof table !== 'undefined') {
                table.page.len(15).draw();
            }
        });
    });
</script>
<?= $this->endSection() ?>

