<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   ANIMASI & INTERAKTIVITAS TABEL ANALISIS KOHORT
   ========================================================= */

/* Entrance animation untuk card tabel */
@keyframes cohortCardFade {
    0% {
        opacity: 0;
        transform: translateY(14px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.cohort-animated-card {
    animation: cohortCardFade 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* Staggered entrance animation untuk baris tabel */
@keyframes cohortRowSlideIn {
    0% {
        opacity: 0;
        transform: translateY(8px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.table-cohort tbody tr {
    animation: cohortRowSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.table-cohort tbody tr:nth-child(1)  { animation-delay: 0.03s; }
.table-cohort tbody tr:nth-child(2)  { animation-delay: 0.06s; }
.table-cohort tbody tr:nth-child(3)  { animation-delay: 0.09s; }
.table-cohort tbody tr:nth-child(4)  { animation-delay: 0.12s; }
.table-cohort tbody tr:nth-child(5)  { animation-delay: 0.15s; }
.table-cohort tbody tr:nth-child(6)  { animation-delay: 0.18s; }
.table-cohort tbody tr:nth-child(7)  { animation-delay: 0.21s; }
.table-cohort tbody tr:nth-child(8)  { animation-delay: 0.24s; }
.table-cohort tbody tr:nth-child(9)  { animation-delay: 0.27s; }
.table-cohort tbody tr:nth-child(10) { animation-delay: 0.30s; }
.table-cohort tbody tr:nth-child(11) { animation-delay: 0.33s; }
.table-cohort tbody tr:nth-child(12) { animation-delay: 0.36s; }

/* ---------------------------------------------------------
   TABEL 1: HEATMAP PERSENTASE BELANJA ULANG
   --------------------------------------------------------- */
.table-cohort-matrix {
    border-collapse: separate;
    border-spacing: 0;
}

.table-cohort-matrix th,
.table-cohort-matrix td {
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

/* Cell Heatmap transitions & hover */
.cohort-cell-heat {
    position: relative;
    cursor: pointer;
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.22s ease,
                filter 0.2s ease,
                background-color 0.2s ease;
    user-select: none;
}

.cohort-cell-heat:hover {
    transform: scale(1.15);
    z-index: 15;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    filter: brightness(1.12);
    border-radius: 6px;
}

.cohort-cell-heat:active {
    transform: scale(0.95);
}

/* Heatmap cell color tiers */
.cohort-cell-100 {
    background-color: #1A2E23 !important;
    color: #ffffff !important;
    font-weight: 700;
}

.cohort-cell-high {
    background-color: #10b981 !important;
    color: #ffffff !important;
    font-weight: 700;
}

.cohort-cell-mid {
    background-color: #fde68a !important;
    color: #92400e !important;
    font-weight: 700;
}

.cohort-cell-low {
    background-color: #fee2e2 !important;
    color: #b91c1c !important;
    font-weight: 600;
}

.cohort-cell-empty {
    background-color: #ffffff;
    color: #94a3b8;
}

/* Pulse animation on highlight / legend hover */
@keyframes cohortCellPulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
    50% { transform: scale(1.12); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.cohort-highlight-pulse {
    animation: cohortCellPulse 0.9s ease infinite !important;
    z-index: 12 !important;
}

/* Crosshair highlight baris & kolom */
.table-cohort tbody tr.cohort-row-hover > td {
    background-color: rgba(45, 106, 79, 0.05);
}

.cohort-col-hover {
    background-color: rgba(45, 106, 79, 0.07) !important;
}

.table-cohort thead th.cohort-th-hover {
    background-color: #2D6A4F !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(45, 106, 79, 0.3);
}

/* ---------------------------------------------------------
   TABEL 2: PEROLEHAN OMSET PENJUALAN PER BULAN
   --------------------------------------------------------- */
.cohort-cell-omset {
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
                background-color 0.2s ease,
                box-shadow 0.2s ease;
    cursor: pointer;
    position: relative;
}

.cohort-cell-omset.has-omset {
    font-weight: 600;
}

.cohort-cell-omset:hover {
    transform: scale(1.08);
    background-color: #e6f4ea !important;
    color: #1A2E23 !important;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    z-index: 10;
    border-radius: 4px;
}

/* ---------------------------------------------------------
   TABEL 3: RETENSI KOHORT PER PRODUK
   --------------------------------------------------------- */
.table-cohort-product tbody tr {
    transition: transform 0.22s ease, background-color 0.22s ease;
}

.table-cohort-product tbody tr:hover {
    background-color: rgba(45, 106, 79, 0.04) !important;
    transform: translateX(4px);
}

.table-cohort-product tbody tr td:first-child {
    transition: border-left 0.2s ease;
}

.table-cohort-product tbody tr:hover td:first-child {
    border-left: 3px solid #2D6A4F;
}

.btn-filter-heatmap {
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-filter-heatmap:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.btn-filter-heatmap:active {
    transform: scale(0.96);
}

/* Interactive Color Legend Items */
.legend-item-clickable {
    cursor: pointer;
    padding: 3px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
    user-select: none;
}

.legend-item-clickable:hover {
    background-color: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transform: translateY(-1px);
}

/* Modal Table row hover animation */
#cohortModalBody tr {
    transition: background-color 0.2s ease, transform 0.2s ease;
}

#cohortModalBody tr:hover {
    background-color: rgba(45, 106, 79, 0.04);
}

/* Horizontal scroll for sub-navigation tabs on mobile */
.crm-subnav-scroll {
    overflow-x: auto;
    flex-wrap: nowrap !important;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding-bottom: 2px;
}
.crm-subnav-scroll::-webkit-scrollbar {
    display: none;
}
.crm-subnav-scroll .btn {
    white-space: nowrap;
    flex-shrink: 0;
}

/* Sticky column for wide cohort tables on horizontal mobile swipe */
.table-cohort .sticky-col {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: #ffffff;
    box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
}
.table-cohort thead th.sticky-col {
    z-index: 3;
    background-color: #f8fafc !important;
}
.table-cohort tbody tr.cohort-row-hover > td.sticky-col {
    background-color: #f4f8f5 !important;
}

/* Fluid Typography & Mobile Card Layout */
.cohort-stat-card {
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.25s ease;
}
.cohort-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}
.cohort-stat-card .stat-val {
    font-size: clamp(1.15rem, 3.2vw, 1.85rem);
    font-weight: 800;
    line-height: 1.15;
}
.cohort-stat-card .stat-val-money {
    font-size: clamp(0.92rem, 2.6vw, 1.6rem);
    font-weight: 800;
    line-height: 1.15;
}
.cohort-stat-card .stat-title {
    font-size: clamp(0.64rem, 1.8vw, 0.72rem);
    letter-spacing: 0.04em;
    line-height: 1.25;
}

@media (max-width: 767.98px) {
    .crm-top-banner {
        padding: 1rem !important;
    }
    .crm-header-actions {
        width: 100% !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        margin-top: 0.5rem !important;
    }
    .crm-header-actions .btn {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        padding: 0.4rem 0.6rem !important;
        font-size: 0.78rem !important;
        text-align: center !important;
        justify-content: center !important;
    }
    .cohort-stat-card {
        padding: 12px 10px !important;
    }
    .cohort-chart-container {
        height: 220px !important;
    }
    .table-cohort th, .table-cohort td {
        padding: 6px 8px !important;
        font-size: 0.75rem !important;
    }
    .table-cohort-matrix th[data-month-col],
    .table-cohort-matrix td[data-month] {
        min-width: 44px;
        padding: 6px 4px !important;
    }
    .table-cohort-omset th[data-month-col],
    .table-cohort-omset td[data-month] {
        min-width: 68px;
        padding: 6px 4px !important;
    }
    .cohort-cell-heat:hover {
        transform: none; /* Avoid sticky scale on mobile tap */
    }
}
</style>

<!-- TOP BANNER RFM -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Analisis Kohort</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-table-cells me-2"></i>Analisis Kohort
                </h3>
                <p class="text-white-50 small mb-0">Memantau persentase pelanggan yang kembali berbelanja (repeat order) dan total perolehan omset berdasarkan bulan pertama bertransaksi &mdash; Mode Bulanan: Tahun <?= date('Y') ?></p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap">
                    <i class="fa-brands fa-whatsapp"></i> Broadcast WA
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel">
                    <i class="fa-solid fa-file-excel"></i> Import Excel Data
                </button>
                <a href="<?= base_url('rfm/customer/export') ?>" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" style="background-color: #6366f1;">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- 6 SUB-NAVIGATION TABS (TOUCH-SCROLLABLE ON MOBILE) -->
        <div class="d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20 crm-subnav-scroll">
            <a href="<?= base_url('rfm') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard RFM
            </a>
            <a href="<?= base_url('rfm/customer') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                2. Daftar Customer
            </a>
            <a href="<?= base_url('rfm/tracking') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                3. Tracking RFM
            </a>
            <a href="<?= base_url('rfm/kohort') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 4. Analisis Kohort
            </a>
            <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Tren Segmen Produk
            </a>
            <a href="<?= base_url('rfm/matriks') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Matriks Aturan RFM
            </a>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="btn-group btn-group-sm w-100">
                    <button type="button" class="btn btn-primary fw-semibold">Tampilan Bulanan</button>
                    <button type="button" class="btn btn-light border text-secondary">Tampilan Harian</button>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select form-select-sm">
                    <option value="">-- Semua Produk --</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm">
                    <option value="2026" selected>Tahun 2026</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-1">
                <button type="button" class="btn btn-primary btn-sm w-100 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
                <button type="button" class="btn btn-success btn-sm fw-semibold" onclick="window.print()" title="Cetak / Unduh">
                    <i class="fa-solid fa-download"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 4 STAT CARDS (2x2 Grid di Mobile, 4 Kolom di Desktop) -->
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm cohort-stat-card h-100" style="border-left: 4px solid #ef4444 !important;">
            <div class="stat-val text-dark font-monospace text-nowrap text-truncate"><?= esc($repeatRate ?? 0) ?>%</div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">RATA-RATA BELANJA BULAN KE-2</div>
            <span class="badge bg-danger bg-opacity-10 text-danger mt-1 align-self-start small text-truncate">▲ Repeat Rate</span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm cohort-stat-card h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="stat-val text-dark font-monospace text-nowrap text-truncate"><?= !empty($cohortRows) ? esc($cohortRows[0]['cohort_label']) : '-' ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">KELOMPOK TERAWAL</div>
            <span class="text-muted small mt-1 font-monospace text-truncate" style="font-size: 0.75rem;"><?= !empty($cohortRows) ? esc($cohortRows[0]['customer_count']) : 0 ?> Pelanggan</span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm cohort-stat-card h-100" style="border-left: 4px solid #10b981 !important;">
            <div class="stat-val-money text-success font-monospace text-nowrap text-truncate"><?= format_rupiah($avgBelanja ?? 0) ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">RATA-RATA / PELANGGAN</div>
            <span class="text-success small mt-1 font-monospace text-truncate" style="font-size: 0.75rem;">✓ Belanja / Orang</span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm cohort-stat-card h-100" style="border-left: 4px solid #2D6A4F !important;">
            <div class="stat-val-money text-primary font-monospace text-nowrap text-truncate"><?= format_rupiah($totalOmset ?? 0) ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">TOTAL OMSET PENJUALAN</div>
            <span class="text-muted small mt-1 font-monospace text-truncate" style="font-size: 0.75rem;"><?= esc($totalPelanggan ?? 0) ?> Pelanggan Unik</span>
        </div>
    </div>
</div>

<!-- LINE CHART: TREN BELANJA ULANG -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-line text-primary"></i> Grafik Tren Belanja Ulang Pelanggan
            </h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Grafik ini memperlihatkan seberapa banyak pelanggan yang kembali belanja di bulan-bulan berikutnya</p>
        </div>
        <div class="btn-group btn-group-sm w-100 w-sm-auto">
            <button type="button" class="btn btn-primary fw-semibold flex-fill flex-sm-grow-0">Tampilkan Semua</button>
            <button type="button" class="btn btn-light border text-secondary flex-fill flex-sm-grow-0">Hanya Rata-rata</button>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="cohort-chart-container" style="height: 240px; position: relative;">
            <canvas id="chartKohortLine"></canvas>
        </div>
    </div>
</div>

<!-- 1. TABEL PERSENTASE PELANGGAN BELANJA ULANG (%) -->
<div class="card border-0 shadow-sm mb-4 cohort-animated-card" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-percent text-success"></i> 1. Tabel Persentase Pelanggan Belanja Ulang (%) &mdash; Tahun <?= date('Y') ?>
        </h6>
        <button type="button" class="btn btn-sm btn-success fw-semibold w-100 w-sm-auto text-nowrap" id="btnExportExcelKohort">
            <i class="fa-solid fa-file-excel me-1"></i>Unduh Excel Kohort
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center mb-0 font-monospace table-cohort table-cohort-matrix" style="font-size: 0.8rem;">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-3 sticky-col text-nowrap">BULAN PERTAMA BELANJA</th>
                        <th class="text-nowrap px-2">JUMLAH PELANGGAN</th>
                        <th data-month-col="01">JAN</th>
                        <th data-month-col="02">FEB</th>
                        <th data-month-col="03">MAR</th>
                        <th data-month-col="04">APR</th>
                        <th data-month-col="05">MEI</th>
                        <th data-month-col="06">JUN</th>
                        <th data-month-col="07">JUL</th>
                        <th data-month-col="08" style="background-color: #1A2E23; color: white;">AGS</th>
                        <th data-month-col="09">SEP</th>
                        <th data-month-col="10" style="background-color: #10b981; color: white;">OKT</th>
                        <th data-month-col="11">NOV</th>
                        <th data-month-col="12">DES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cohortRows)): ?>
                        <?php foreach ($cohortRows as $crow): ?>
                            <tr>
                                <td class="text-start ps-3 fw-semibold sticky-col text-nowrap">&bull; <?= esc($crow['cohort_label']) ?></td>
                                <td class="fw-bold text-dark text-nowrap"><?= esc($crow['customer_count']) ?></td>
                                <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                                    <?php 
                                        $val = $crow['months'][$m] ?? '-';
                                        $cellClass = 'cohort-cell-heat ';
                                        $tier = 'empty';
                                        if ($val === '100%') {
                                            $cellClass .= 'cohort-cell-100';
                                            $tier = '100';
                                        } elseif ($val !== '-' && $val !== '0%') {
                                            $numVal = (float)rtrim($val, '%');
                                            if ($numVal >= 50) {
                                                $cellClass .= 'cohort-cell-high';
                                                $tier = 'high';
                                            } elseif ($numVal >= 20) {
                                                $cellClass .= 'cohort-cell-mid';
                                                $tier = 'mid';
                                            } else {
                                                $cellClass .= 'cohort-cell-low';
                                                $tier = 'low';
                                            }
                                        } else {
                                            $cellClass .= 'cohort-cell-empty';
                                            $tier = 'zero';
                                        }
                                    ?>
                                    <td class="<?= $cellClass ?>" data-month="<?= $m ?>" data-tier="<?= $tier ?>" data-value="<?= esc($val) ?>"><?= esc($val) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="14" class="text-center text-muted py-4 small">
                                Belum ada riwayat transaksi kohort untuk tahun ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- COLOR LEGEND BAR -->
        <div class="p-2 p-md-3 border-top bg-light d-flex flex-wrap align-items-center gap-2 gap-md-3" style="font-size: 0.75rem;">
            <span class="text-muted fw-semibold"><i class="fa-solid fa-circle-info text-primary me-1"></i>Keterangan Warna:</span>
            <span class="d-inline-flex align-items-center gap-1 legend-item-clickable" data-legend-tier="zero"><span style="display:inline-block; width:14px; height:14px; background:#ffffff; border:1px solid #cbd5e1; border-radius:2px;"></span> 0% (Belum Ada)</span>
            <span class="d-inline-flex align-items-center gap-1 legend-item-clickable" data-legend-tier="low"><span style="display:inline-block; width:14px; height:14px; background:#fee2e2; border-radius:2px;"></span> 1-10% (Rendah)</span>
            <span class="d-inline-flex align-items-center gap-1 legend-item-clickable" data-legend-tier="mid"><span style="display:inline-block; width:14px; height:14px; background:#fde68a; border-radius:2px;"></span> 20-30% (Sedang)</span>
            <span class="d-inline-flex align-items-center gap-1 legend-item-clickable" data-legend-tier="high"><span style="display:inline-block; width:14px; height:14px; background:#10b981; border-radius:2px;"></span> 50%+ (Tinggi)</span>
            <span class="d-inline-flex align-items-center gap-1 legend-item-clickable" data-legend-tier="100"><span style="display:inline-block; width:14px; height:14px; background:#1A2E23; border-radius:2px;"></span> 100% (Bulan Pertama)</span>
        </div>
    </div>
</div>

<!-- 2. TABEL PEROLEHAN OMSET PENJUALAN PER BULAN -->
<div class="card border-0 shadow-sm mb-4 cohort-animated-card" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-money-bill-trend-up text-primary"></i> 2. Tabel Perolehan Omset Penjualan per Bulan (Rp) &mdash; Tahun <?= date('Y') ?>
        </h6>
        <button type="button" class="btn btn-sm btn-success fw-semibold w-100 w-sm-auto text-nowrap" id="btnExportExcelOmset">
            <i class="fa-solid fa-file-excel me-1"></i>Unduh Excel Omset
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center mb-0 font-monospace table-cohort table-cohort-omset" style="font-size: 0.8rem;">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-3 sticky-col text-nowrap">BULAN PERTAMA BELANJA</th>
                        <th class="text-nowrap px-2">JUMLAH PELANGGAN</th>
                        <th class="text-nowrap px-2">TOTAL OMSET</th>
                        <th data-month-col="01">JAN</th><th data-month-col="02">FEB</th><th data-month-col="03">MAR</th>
                        <th data-month-col="04">APR</th><th data-month-col="05">MEI</th><th data-month-col="06">JUN</th>
                        <th data-month-col="07">JUL</th><th data-month-col="08">AGS</th><th data-month-col="09">SEP</th>
                        <th data-month-col="10">OKT</th><th data-month-col="11">NOV</th><th data-month-col="12">DES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cohortRows)): ?>
                        <?php foreach ($cohortRows as $crow): ?>
                            <tr>
                                <td class="text-start ps-3 fw-semibold sticky-col text-nowrap">&bull; <?= esc($crow['cohort_label']) ?></td>
                                <td class="fw-bold text-dark text-nowrap"><?= esc($crow['customer_count']) ?></td>
                                <td class="text-success fw-bold text-nowrap"><?= format_rupiah($crow['total_omset']) ?></td>
                                <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                                    <?php 
                                        $omsetVal = $crow['omset_months'][$m] ?? '-';
                                        $hasVal = ($omsetVal !== '-' && $omsetVal !== 'Rp 0');
                                    ?>
                                    <td class="cohort-cell-omset <?= $hasVal ? 'has-omset text-success' : 'text-muted' ?>" data-month="<?= $m ?>"><?= esc($omsetVal) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center text-muted py-4 small">
                                Belum ada riwayat omset kohort untuk tahun ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 3. TABEL RETENSI KOHORT PER PRODUK -->
<div class="card border-0 shadow-sm mb-4 cohort-animated-card" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-box-archive text-purple" style="color: #8b5cf6;"></i> Tabel Perbandingan Retensi Kohort Per Produk &mdash; Tahun <?= date('Y') ?>
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-cohort table-cohort-product">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">#</th>
                        <th class="sticky-col text-nowrap">NAMA PRODUK</th>
                        <th class="text-center text-nowrap px-2">TOTAL CUSTOMER (AKUISISI)</th>
                        <th class="text-center text-nowrap px-2">TOTAL TRANSAKSI ORDER</th>
                        <th class="text-end text-nowrap px-2">TOTAL OMSET (MONETARY)</th>
                        <th class="pe-3 text-center text-nowrap" style="width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $idx => $p): ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td class="sticky-col text-nowrap">
                                    <div class="fw-bold text-dark small d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-tag text-primary small"></i> <?= esc($p['nama_produk']) ?>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap">
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1">
                                        <i class="fa-solid fa-user me-1"></i><?= esc($totalPelanggan ?? 1) ?> Pelanggan
                                    </span>
                                </td>
                                <td class="text-center font-monospace small text-nowrap">
                                    Aktif
                                </td>
                                <td class="text-end font-monospace fw-bold text-success small text-nowrap">
                                    <?= format_rupiah($p['harga_jual'] ?? 0) ?>
                                </td>
                                <td class="pe-3 text-center text-nowrap">
                                    <button type="button" class="btn btn-sm text-white rounded d-inline-flex align-items-center justify-content-center shadow-sm btn-filter-heatmap" data-prod="<?= esc($p['nama_produk']) ?>" style="background-color: #6366f1; width: 32px; height: 32px;" title="Filter Heatmap Produk Ini">
                                        <i class="fa-solid fa-table-cells"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- PANDUAN PRAKTIS CARA MEMBACA DATA -->
<div class="card border-0 shadow-sm p-3 p-md-4 mb-4" style="background-color: #E8F0EB; border-radius: 10px;">
    <h6 class="fw-bold text-primary mb-2 d-flex align-items-center gap-2">
        <i class="fa-solid fa-lightbulb text-warning"></i> Panduan Praktis Cara Membaca Data di Halaman Ini
    </h6>
    <ul class="text-secondary small mb-0 ps-3" style="line-height: 1.6;">
        <li><strong>Baris (Bulan Pertama Belanja):</strong> Kelompok pelanggan yang pertama kali berbelanja di bulan tersebut.</li>
        <li><strong>Kolom Bulan:</strong> Bulan ketika pelanggan tersebut datang kembali untuk belanja ulang.</li>
        <li><strong>Tabel 1 (Persentase %):</strong> Menunjukkan berapa persen pelanggan lama yang setia belanja lagi. Makin tinggi persentasenya, makin bagus retensinya.</li>
        <li><strong>Tabel 2 (Omset Rp):</strong> Total uang belanja yang masuk dari pelanggan lama tersebut di setiap bulannya.</li>
        <li><strong>Fitur Klik Kotak:</strong> Anda bisa mengklik kotak angka mana saja pada tabel untuk langsung melihat siapa saja nama pelanggannya dan nomor WhatsApp mereka.</li>
    </ul>
</div>

<!-- MODAL COHORT DRILLDOWN -->
<div class="modal fade" id="modalCohortDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #1A2E23;">
                <div>
                    <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-users-viewfinder me-2"></i>Daftar Pelanggan Belanja Ulang (Kohort)</h6>
                    <small class="text-white-50" id="cohortModalSubtitle">Kohort: Ags 2026 &bull; Bulan Belanja Ulang: Okt 2026</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Nama Pelanggan</th>
                                <th>No. WhatsApp</th>
                                <th>Kota</th>
                                <th class="text-center">Total Order</th>
                                <th class="text-end">Total Nominal</th>
                                <th class="pe-3 text-end">Aksi WA</th>
                            </tr>
                        </thead>
                        <tbody id="cohortModalBody">
                            <?php if (!empty($allCustomers)): ?>
                                <?php foreach ($allCustomers as $ac): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark"><?= esc($ac['nama_pelanggan']) ?></td>
                                        <td class="font-monospace"><?= esc($ac['no_telp'] ?? '-') ?></td>
                                        <td class="text-muted small"><?= esc($ac['kota'] ?? '-') ?></td>
                                        <td class="text-center font-monospace"><?= (int)($ac['total_order'] ?? 0) ?>x Order</td>
                                        <td class="text-end font-monospace text-success fw-bold"><?= format_rupiah($ac['total_spend'] ?? 0) ?></td>
                                        <td class="pe-3 text-end">
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $ac['no_telp'] ?? '') ?>" target="_blank" class="btn btn-sm btn-success py-0 px-2" style="font-size: 0.78rem;">
                                                <i class="fa-brands fa-whatsapp me-1"></i>Chat WA
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4 small">
                                        Belum ada data pelanggan untuk kelompok kohort ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-primary btn-sm">
                    <i class="fa-brands fa-whatsapp me-1"></i>Kirim Promo Broadcast ke Kohort Ini
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/modal_import_excel.php'; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartKohortLine').getContext('2d');
    <?php
        $chartCohortData = !empty($cohortRows) ? [100, (float)($repeatRate ?? 0), 0, 0, 0] : [0, 0, 0, 0, 0];
    ?>
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Bulan ke-1 (Awal)', 'Bulan ke-2', 'Bulan ke-3', 'Bulan ke-4', 'Bulan ke-5'],
            datasets: [{
                label: 'Rata-rata Retensi (%)',
                data: <?= json_encode($chartCohortData) ?>,
                borderColor: '#2D6A4F',
                backgroundColor: 'rgba(45, 106, 79, 0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => v + '%' }
                }
            }
        }
    });

    // Filter Heatmap button
    document.querySelectorAll('.btn-filter-heatmap').forEach(btn => {
        btn.addEventListener('click', function() {
            const prod = this.dataset.prod;
            const targetTable = document.querySelector('.table-cohort-matrix');
            if (targetTable) {
                targetTable.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetTable.style.transition = 'box-shadow 0.3s ease';
                targetTable.style.boxShadow = '0 0 20px rgba(99, 102, 241, 0.6)';
                setTimeout(() => { targetTable.style.boxShadow = ''; }, 1500);
            }
            alert('Heatmap kohort difilter khusus untuk produk: ' + prod);
        });
    });

    // Crosshair row & column hover highlight for cohort tables
    document.querySelectorAll('.table-cohort-matrix, .table-cohort-omset').forEach(table => {
        table.querySelectorAll('tbody td[data-month]').forEach(td => {
            td.addEventListener('mouseenter', function() {
                const m = this.getAttribute('data-month');
                const tr = this.closest('tr');
                if (tr) tr.classList.add('cohort-row-hover');

                // Highlight corresponding column header
                const th = table.querySelector(`thead th[data-month-col="${m}"]`);
                if (th) th.classList.add('cohort-th-hover');

                // Highlight column cells
                table.querySelectorAll(`tbody td[data-month="${m}"]`).forEach(colTd => {
                    if (colTd !== this) colTd.classList.add('cohort-col-hover');
                });
            });

            td.addEventListener('mouseleave', function() {
                const m = this.getAttribute('data-month');
                const tr = this.closest('tr');
                if (tr) tr.classList.remove('cohort-row-hover');

                const th = table.querySelector(`thead th[data-month-col="${m}"]`);
                if (th) th.classList.remove('cohort-th-hover');

                table.querySelectorAll(`tbody td[data-month="${m}"]`).forEach(colTd => {
                    colTd.classList.remove('cohort-col-hover');
                });
            });
        });
    });

    // Interactive Legend Hover Highlight
    document.querySelectorAll('.legend-item-clickable').forEach(item => {
        const tier = item.getAttribute('data-legend-tier');
        item.addEventListener('mouseenter', function() {
            document.querySelectorAll(`.table-cohort-matrix td[data-tier="${tier}"]`).forEach(td => {
                td.classList.add('cohort-highlight-pulse');
            });
        });
        item.addEventListener('mouseleave', function() {
            document.querySelectorAll(`.table-cohort-matrix td[data-tier="${tier}"]`).forEach(td => {
                td.classList.remove('cohort-highlight-pulse');
            });
        });
    });

    // Make percentage and omset table cells clickable for customer drilldown
    document.querySelectorAll('.table-cohort-matrix tbody td.cohort-cell-heat, .table-cohort-omset tbody td.cohort-cell-omset').forEach(td => {
        const text = td.textContent.trim();
        if (text !== '-' && text !== '0%' && text !== 'Rp 0') {
            td.title = 'Klik untuk melihat rincian pelanggan';
            td.addEventListener('click', function() {
                // Tactile feedback
                this.style.transform = 'scale(0.92)';
                setTimeout(() => { this.style.transform = ''; }, 160);

                const tr = this.closest('tr');
                const cohortMonth = tr ? tr.querySelector('td:first-child').textContent.trim() : 'Ags 2026';
                const mCol = this.getAttribute('data-month') || '';
                const mNames = {'01':'Januari','02':'Februari','03':'Maret','04':'April','05':'Mei','06':'Juni','07':'Juli','08':'Agustus','09':'September','10':'Oktober','11':'November','12':'Desember'};
                const mLabel = mNames[mCol] || '';

                const subTitle = document.getElementById('cohortModalSubtitle');
                if (subTitle) {
                    subTitle.innerHTML = `Kelompok Kohort: <strong>${cohortMonth}</strong> &bull; Bulan Belanja Ulang: <strong>${mLabel} 2026</strong> &bull; Performa: <span class="badge bg-success">${text}</span>`;
                }
                new bootstrap.Modal(document.getElementById('modalCohortDetail')).show();
            });
        }
    });

    // Export Excel Kohort
    function downloadCsv(filename, rows) {
        let csv = '';
        rows.forEach(r => {
            csv += r.map(c => '"' + c.replace(/"/g, '""') + '"').join(',') + "\n";
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    const btnExportKohort = document.getElementById('btnExportExcelKohort');
    if (btnExportKohort) {
        btnExportKohort.addEventListener('click', function() {
            const rows = [
                ['Bulan Pertama Belanja', 'Jumlah Pelanggan', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                <?php foreach (($cohortRows ?? []) as $cr): ?>
                    [
                        <?= json_encode($cr['cohort_label']) ?>,
                        <?= json_encode((string)$cr['customer_count']) ?>,
                        <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                            <?= json_encode($cr['months'][$m] ?? '-') ?>,
                        <?php endforeach; ?>
                    ],
                <?php endforeach; ?>
            ];
            downloadCsv('Kohort_Retensi_Pelanggan_<?= date('Y') ?>.csv', rows);
        });
    }

    const btnExportOmset = document.getElementById('btnExportExcelOmset');
    if (btnExportOmset) {
        btnExportOmset.addEventListener('click', function() {
            const rows = [
                ['Bulan Pertama Belanja', 'Jumlah Pelanggan', 'Total Omset', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                <?php foreach (($cohortRows ?? []) as $cr): ?>
                    [
                        <?= json_encode($cr['cohort_label']) ?>,
                        <?= json_encode((string)$cr['customer_count']) ?>,
                        <?= json_encode((string)$cr['total_omset']) ?>,
                        <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                            <?= json_encode($cr['omset_months'][$m] ?? '-') ?>,
                        <?php endforeach; ?>
                    ],
                <?php endforeach; ?>
            ];
            downloadCsv('Kohort_Omset_Penjualan_<?= date('Y') ?>.csv', rows);
        });
    }
});
</script>
<?= $this->endSection() ?>

