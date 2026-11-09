<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   ANIMASI & TEMA TREN SEGMEN PRODUK RFM
   ========================================================= */

/* Entrance animation untuk card */
@keyframes trenCardFadeIn {
    0% { opacity: 0; transform: translateY(14px); }
    100% { opacity: 1; transform: translateY(0); }
}

.tren-animated-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    margin-bottom: 24px;
    overflow: hidden;
    animation: trenCardFadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* Staggered row slide-in animation */
@keyframes trenRowSlideIn {
    0% { opacity: 0; transform: translateY(8px); }
    100% { opacity: 1; transform: translateY(0); }
}

.table-tren-matrix tbody tr,
.table-tren-product tbody tr {
    animation: trenRowSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.table-tren-matrix tbody tr:nth-child(1)  { animation-delay: 0.03s; }
.table-tren-matrix tbody tr:nth-child(2)  { animation-delay: 0.06s; }
.table-tren-matrix tbody tr:nth-child(3)  { animation-delay: 0.09s; }
.table-tren-matrix tbody tr:nth-child(4)  { animation-delay: 0.12s; }
.table-tren-matrix tbody tr:nth-child(5)  { animation-delay: 0.15s; }
.table-tren-matrix tbody tr:nth-child(6)  { animation-delay: 0.18s; }
.table-tren-matrix tbody tr:nth-child(7)  { animation-delay: 0.21s; }
.table-tren-matrix tbody tr:nth-child(8)  { animation-delay: 0.24s; }
.table-tren-matrix tbody tr:nth-child(9)  { animation-delay: 0.27s; }
.table-tren-matrix tbody tr:nth-child(10) { animation-delay: 0.30s; }
.table-tren-matrix tbody tr:nth-child(11) { animation-delay: 0.33s; }
.table-tren-matrix tbody tr:nth-child(12) { animation-delay: 0.36s; }

/* Stat Cards hover lift animation with sleek accent strip */
.stat-tren-card {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0 !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.stat-tren-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(45, 106, 79, 0.12);
}
.stat-tren-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: var(--card-accent, #2D6A4F);
}

.table-tren-product {
    min-width: 860px;
}

/* Table general */
.table-tren th {
    background: #f8fafc;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #475569;
    font-weight: 700;
    white-space: nowrap;
    vertical-align: middle;
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

.table-tren td {
    font-size: 12.5px;
    vertical-align: middle;
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

/* Active Cell Heatmap Effects */
.cell-active-val {
    background-color: #ecfdf5 !important;
    color: #047857 !important;
    font-weight: 700 !important;
    position: relative;
    cursor: pointer;
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.22s ease,
                background-color 0.2s ease !important;
    user-select: none;
}

.cell-active-val:hover {
    transform: scale(1.18);
    z-index: 15;
    position: relative;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3);
    border-radius: 6px;
    filter: brightness(1.05);
}

.cell-active-val:active {
    transform: scale(0.94);
}

/* Crosshair highlight */
.table-tren-matrix tbody tr.tren-row-hover > td {
    background-color: rgba(45, 106, 79, 0.05);
}

.tren-col-hover {
    background-color: rgba(45, 106, 79, 0.07) !important;
}

.table-tren-matrix thead th.tren-th-hover {
    background-color: #2D6A4F !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(45, 106, 79, 0.3);
}

/* Product table row slide on hover */
.table-tren-product tbody tr {
    transition: transform 0.22s ease, background-color 0.22s ease;
}

.table-tren-product tbody tr:hover {
    background-color: rgba(45, 106, 79, 0.04) !important;
    transform: translateX(4px);
}

.table-tren-product tbody tr td:first-child {
    transition: border-left 0.2s ease;
}

.table-tren-product tbody tr:hover td:first-child {
    border-left: 3px solid #2D6A4F;
}

/* Square icon button */
.btn-icon-square {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-icon-square:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.btn-icon-square:active {
    transform: scale(0.95);
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

/* Sticky column for wide tren matrix tables on horizontal swipe */
.table-tren .sticky-col {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: #ffffff;
    box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
}
.table-tren thead th.sticky-col {
    z-index: 3;
    background-color: #f8fafc !important;
}
.table-tren tbody tr.tren-row-hover > td.sticky-col {
    background-color: #f4f8f5 !important;
}
.table-tren tr.table-light > td.sticky-col {
    background-color: #f8fafc !important;
}

/* Fluid Typography & Stat Card Layout */
.stat-tren-card {
    padding: 1rem;
}
.stat-tren-card .stat-val {
    font-size: clamp(1.15rem, 3.2vw, 1.85rem);
    font-weight: 800;
    line-height: 1.15;
}
.stat-tren-card .stat-val-money {
    font-size: clamp(0.92rem, 2.6vw, 1.6rem);
    font-weight: 800;
    line-height: 1.15;
}
.stat-tren-card .stat-title {
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
    .stat-tren-card {
        padding: 12px 10px !important;
    }
    .chart-tren-container {
        height: 220px !important;
    }
    .table-tren th, .table-tren td {
        padding: 6px 6px !important;
        font-size: 0.75rem !important;
    }
    .table-tren-matrix th[data-month-col],
    .table-tren-matrix td[data-month] {
        min-width: 38px;
        padding: 6px 3px !important;
    }
    .cell-active-val:hover {
        transform: none; /* Avoid sticky scale on touch screen tap */
    }
}
</style>

<!-- TOP BANNER RFM (WARNA SISTEM HIJAU) -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Tren Segmen Produk</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-table-list me-2"></i>Analisis Tren Segmen RFM
                </h3>
                <p class="text-white-50 small mb-0">Matriks pergerakan dan dinamika pelanggan di tiap segmen RFM dari bulan ke bulan secara realtime</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap">
                    <i class="fa-brands fa-whatsapp"></i> Broadcast WA
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel">
                    <i class="fa-solid fa-file-excel"></i> Import Excel Data
                </button>
                <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" style="background-color: #6366f1;" id="btnExportTrenCsv">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- 6 SUB-NAVIGATION TABS (STANDAR RFM SISTEM) -->
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
            <a href="<?= base_url('rfm/kohort') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Analisis Kohort
            </a>
            <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 5. Tren Segmen Produk
            </a>
            <a href="<?= base_url('rfm/matriks') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Matriks Aturan RFM
            </a>
        </div>
    </div>
</div>

<!-- FILTER BAR INTERAKTIF -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-body p-3">
        <form method="GET" action="<?= base_url('rfm/tren-produk') ?>" id="formTrenFilter" class="row g-2 align-items-center">
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-filter me-1 text-primary"></i>Kategori:</label>
                <select name="kategori" class="form-select form-select-sm" onchange="document.getElementById('formTrenFilter').submit()">
                    <option value="">-- Semua Kategori --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <?php 
                                $catId = $cat['id'];
                                $isCatSel = ((string)$selectedKategori === (string)$catId || strcasecmp((string)$selectedKategori, $cat['nama_kategori']) === 0);
                            ?>
                            <option value="<?= esc($catId) ?>" <?= $isCatSel ? 'selected' : '' ?>>
                                <?= esc($cat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-box me-1 text-primary"></i>Produk:</label>
                <select name="produk" class="form-select form-select-sm" onchange="document.getElementById('formTrenFilter').submit()">
                    <option value="">-- Semua Produk --</option>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $prd): ?>
                            <?php 
                                $prdId = $prd['id'];
                                $isPrdSel = ((string)$selectedProduk === (string)$prdId || strcasecmp((string)$selectedProduk, $prd['nama_produk']) === 0);
                            ?>
                            <option value="<?= esc($prdId) ?>" <?= $isPrdSel ? 'selected' : '' ?>>
                                <?= esc($prd['nama_produk']) ?> (<?= esc($prd['kode_produk']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-calendar-days me-1 text-primary"></i>Tahun:</label>
                <select name="tahun" class="form-select form-select-sm" onchange="document.getElementById('formTrenFilter').submit()">
                    <option value="2026" <?= (int)$selectedTahun === 2026 ? 'selected' : '' ?>>Tahun 2026</option>
                    <option value="2025" <?= (int)$selectedTahun === 2025 ? 'selected' : '' ?>>Tahun 2025</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i>Bulan:</label>
                <select name="bulan" class="form-select form-select-sm" onchange="document.getElementById('formTrenFilter').submit()">
                    <option value="">-- Semua Bulan --</option>
                    <?php 
                        $mNames = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        foreach ($mNames as $mNum => $mTxt):
                    ?>
                        <option value="<?= $mNum ?>" <?= (string)$selectedBulan === (string)$mNum ? 'selected' : '' ?>>
                            <?= $mTxt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end gap-1 mt-1 mt-md-0">
                <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold text-nowrap">
                    <i class="fa-solid fa-filter me-1"></i>Terapkan
                </button>
                <?php if (!empty($selectedKategori) || !empty($selectedProduk) || !empty($selectedBulan) || (int)$selectedTahun !== 2026): ?>
                    <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-outline-danger btn-sm text-nowrap" title="Reset Semua Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- 4 STAT CARDS (STANDAR RFM SISTEM - 2x2 Grid di Mobile) -->
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-tren-card" style="--card-accent: #10b981;">
            <div class="stat-val text-dark font-monospace text-nowrap text-truncate"><?= number_format($totalPelanggan ?? 0, 0, ',', '.') ?> Org</div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">TOTAL BASIS PELANGGAN</div>
            <span class="badge bg-success bg-opacity-10 text-success mt-1 align-self-start small text-truncate">✓ Pelanggan Terfilter</span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-tren-card" style="--card-accent: #2D6A4F;">
            <div class="stat-val-money text-primary font-monospace text-nowrap text-truncate"><?= format_rupiah($totalOmset ?? 0) ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">AKUMULASI OMSET PENJUALAN</div>
            <span class="text-muted small mt-1 font-monospace text-truncate" style="font-size: 0.75rem;">Tahun <?= esc($selectedTahun) ?></span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-tren-card" style="--card-accent: #3b82f6;">
            <?php 
                $latestMom = '+100%';
                if (!empty($momGrowth)) {
                    foreach (array_reverse($momGrowth, true) as $mVal) {
                        if ($mVal !== '-') { $latestMom = $mVal; break; }
                    }
                }
                $isMomPos = str_contains((string)$latestMom, '+');
                $isMomNeg = str_contains((string)$latestMom, '-') && $latestMom !== '-';
                $momColorClass = $isMomPos ? 'text-success' : ($isMomNeg ? 'text-danger' : 'text-muted');
                $momBadgeClass = $isMomPos ? 'bg-success bg-opacity-10 text-success' : ($isMomNeg ? 'bg-danger bg-opacity-10 text-danger' : 'bg-secondary bg-opacity-10 text-secondary');
                $momBadgeText  = $isMomPos ? '▲ Tren Positif' : ($isMomNeg ? '▼ Penurunan' : '— Stabil');
            ?>
            <div class="stat-val <?= $momColorClass ?> font-monospace text-nowrap text-truncate"><?= esc($latestMom) ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">STATUS PERTUMBUHAN MoM</div>
            <span class="badge <?= $momBadgeClass ?> mt-1 align-self-start small text-truncate"><?= $momBadgeText ?></span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-tren-card" style="--card-accent: #f59e0b;">
            <div class="stat-val text-dark text-nowrap text-truncate" style="font-size: clamp(0.95rem, 2.7vw, 1.55rem);"><?= esc($dominantSegment ?? 'Need Attention') ?></div>
            <div class="text-muted stat-title fw-semibold text-uppercase text-truncate mt-1">SEGMEN DOMINAN (<?= esc($dominantCount ?? 0) ?> CS)</div>
            <span class="badge bg-warning bg-opacity-10 text-warning mt-1 align-self-start small text-truncate">★ Dominansi Tertinggi</span>
        </div>
    </div>
</div>

<!-- GRAFIK TREN SEGMEN RFM BULANAN -->
<div class="card border-0 shadow-sm mb-4 tren-animated-card" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-line text-primary"></i> Grafik Tren Evolusi Segmen RFM &mdash; Tahun <?= esc($selectedTahun) ?>
            </h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Pergerakan jumlah pelanggan di masing-masing 10 segmen RFM dari bulan ke bulan</p>
        </div>
        <span class="badge bg-light text-secondary border px-3 py-1">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>Klik legenda untuk sembunyikan/tampilkan garis
        </span>
    </div>
    <div class="card-body p-3">
        <div class="chart-tren-container" style="height: 260px; position: relative;">
            <canvas id="chartTrenSegmen"></canvas>
        </div>
    </div>
</div>

<!-- 1. MATRIKS DISTRIBUSI SEGMEN PER BULAN (HEATMAP MATRIX TABLE) -->
<div class="card border-0 shadow-sm mb-4 tren-animated-card" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-table-cells text-success"></i> Matriks Distribusi 10 Segmen RFM per Bulan &mdash; Tahun <?= esc($selectedTahun) ?>
            </h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Distribusi jumlah pelanggan di setiap segmen RFM pada tiap periode bulanan</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1">
                Mode Dinamis Realtime
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center mb-0 font-monospace table-tren table-tren-matrix" style="font-size: 0.8rem;">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-3 py-2 sticky-col text-nowrap" style="min-width: 170px;">SEGMEN RFM</th>
                        <?php 
                            $monthHeaders = [
                                '01' => 'JAN', '02' => 'FEB', '03' => 'MAR',
                                '04' => 'APR', '05' => 'MEI', '06' => 'JUN',
                                '07' => 'JUL', '08' => 'AGS', '09' => 'SEP',
                                '10' => 'OKT', '11' => 'NOV', '12' => 'DES'
                            ];
                        ?>
                        <?php foreach ($monthHeaders as $mKey => $mLabel): ?>
                            <?php $isMonthSel = ((string)$selectedBulan === (string)$mKey); ?>
                            <th data-month-col="<?= $mKey ?>" class="text-nowrap px-1" style="<?= $isMonthSel ? 'background-color: #2D6A4F !important; color: #ffffff !important;' : '' ?>">
                                <?= $mLabel ?>
                            </th>
                        <?php endforeach; ?>
                        <th class="text-nowrap px-2" style="background-color: #1A2E23; color: white;">TOTAL</th>
                        <th class="text-nowrap px-2">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($segmentList)): ?>
                        <?php foreach ($segmentList as $seg): ?>
                            <?php 
                                $segTotal = $distribution[$seg] ?? 0;
                                $segPct = ($totalPelanggan > 0) ? round(($segTotal / $totalPelanggan) * 100, 1) : 0;
                                $badgeStyle = match($seg) {
                                    'Champions'          => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                                    'Loyal Customers'    => 'background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;',
                                    'Potential Loyalist' => 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                                    'New Customers'      => 'background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd;',
                                    'Big Spenders'       => 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;',
                                    'Frequent Low Value' => 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;',
                                    'Need Attention'     => 'background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;',
                                    'At Risk Customers'  => 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
                                    "Can't Lose Them"    => 'background-color: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8;',
                                    default              => 'background-color: #f3f4f6; color: #1f2937; border: 1px solid #e5e7eb;',
                                };
                            ?>
                            <tr>
                                <td class="text-start ps-3 fw-bold text-dark sticky-col text-nowrap">
                                    <span class="badge rounded-pill me-1" style="<?= $badgeStyle ?>">&bull;</span>
                                    <?= esc($seg) ?>
                                </td>
                                <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                                    <?php 
                                        $val = $monthlyMatrix[$seg][$m] ?? 0; 
                                        $isMonthSelected = ((string)$selectedBulan === (string)$m);
                                    ?>
                                    <td class="<?= $val > 0 ? 'cell-active-val' : 'text-muted' ?> text-nowrap px-1" data-month="<?= $m ?>" style="<?= $isMonthSelected ? 'border: 2px solid #2D6A4F; background-color: rgba(45, 106, 79, 0.08); font-weight: 700;' : '' ?>">
                                        <?= $val > 0 ? esc($val) : '-' ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="fw-bold fs-6 text-nowrap px-2" style="background-color: #f8fafc; color: #1A2E23;">
                                    <?= esc($segTotal) ?>
                                </td>
                                <td class="text-nowrap px-2">
                                    <?php if ($segTotal > 0): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><?= $segPct ?>%</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1">0%</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- SUMMARY ROW: TOTAL PELANGGAN AKTIF (BULANAN) -->
                    <tr class="table-light fw-bold">
                        <td class="text-start ps-3 text-primary sticky-col text-nowrap">
                            <i class="fa-solid fa-users me-1"></i>TOTAL PELANGGAN AKTIF
                        </td>
                        <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                            <?php 
                                $uCnt = $monthlyUnique[$m] ?? 0; 
                                $isMonthSelected = ((string)$selectedBulan === (string)$m);
                            ?>
                            <td class="<?= $uCnt > 0 ? 'text-primary fw-bold' : 'text-muted' ?> text-nowrap px-1" data-month="<?= $m ?>" style="<?= $isMonthSelected ? 'border: 2px solid #2D6A4F; background-color: rgba(45, 106, 79, 0.12);' : '' ?>">
                                <?= $uCnt > 0 ? esc($uCnt) : '-' ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="text-primary fs-6 fw-bold text-nowrap px-2" style="background-color: #e8f0eb;">
                            <?= esc($totalPelanggan ?? 0) ?>
                        </td>
                        <td class="text-nowrap px-2">
                            <span class="badge bg-primary px-2 py-1"><?= esc($totalPelanggan ?? 0) ?> Unik</span>
                        </td>
                    </tr>

                    <!-- SUMMARY ROW: PERTUMBUHAN MoM (BULANAN %) -->
                    <tr class="table-light fw-bold text-muted">
                        <td class="text-start ps-3 sticky-col text-nowrap">
                            <i class="fa-solid fa-chart-line me-1 text-success"></i>PERTUMBUHAN MoM (%)
                        </td>
                        <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                            <?php 
                                $growthVal = $momGrowth[$m] ?? '-';
                                $isPositive = str_contains((string)$growthVal, '+');
                                $isNegative = str_contains((string)$growthVal, '-') && $growthVal !== '-';
                                $isMonthSelected = ((string)$selectedBulan === (string)$m);
                            ?>
                            <td class="<?= $isPositive ? 'text-success fw-bold' : ($isNegative ? 'text-danger fw-bold' : 'text-muted') ?> text-nowrap px-1" data-month="<?= $m ?>" style="<?= $isMonthSelected ? 'border: 2px solid #2D6A4F; background-color: rgba(45, 106, 79, 0.12);' : '' ?>">
                                <?= esc($growthVal) ?>
                            </td>
                        <?php endforeach; ?>
                        <td colspan="2" class="<?= $isMomPos ? 'text-success' : ($isMomNeg ? 'text-danger' : 'text-muted') ?> fw-bold text-center text-nowrap px-2" style="background-color: #e8f0eb;">
                            <?= esc($latestMom) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 2. TABEL RINGKASAN PERFORMA SEGMEN PER PRODUK -->
<div class="card border-0 shadow-sm mb-4 tren-animated-card" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-primary"></i> Ringkasan Performa &amp; Penjualan per Produk
            </h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Perbandingan total transaksi, jumlah pembeli unik, dan omset masing-masing produk</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-light text-secondary border px-3 py-1">
                Total <?= count($productSummary ?? []) ?> Produk Terdata
            </span>
            <!-- TOGGLE MODE VIEW: TABEL vs CARD -->
            <div class="btn-group btn-group-sm shadow-xs" role="group" aria-label="Pilih Mode Tampilan">
                <button type="button" class="btn btn-outline-primary active" id="btnModeTable" onclick="setProductSummaryView('table')" title="Tampilan Tabel Geser Samping">
                    <i class="fa-solid fa-table me-1"></i>Tabel
                </button>
                <button type="button" class="btn btn-outline-primary" id="btnModeCard" onclick="setProductSummaryView('card')" title="Tampilan Card Responsif">
                    <i class="fa-solid fa-grip me-1"></i>Card
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- MODE 1: TAMPILAN TABEL GESER KE SAMPING (HORIZONTALLY SCROLLABLE) -->
        <div id="productSummaryTableView" class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light bg-opacity-75 border-bottom text-muted small d-lg-none" style="font-size: 0.76rem;">
                <span class="d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-arrows-left-right text-primary"></i>
                    <span>Tabel dapat <strong>digeser ke samping</strong> (swipe)</span>
                </span>
                <span class="badge bg-white text-secondary border shadow-xs" style="font-size: 0.68rem;">Scroll Horizontal</span>
            </div>
            <table class="table table-hover align-middle mb-0 table-tren table-tren-product" style="min-width: 860px;">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted">
                        <th class="ps-3 py-3" style="width: 40px;">#</th>
                        <th class="text-nowrap" style="min-width: 260px;">NAMA PRODUK</th>
                        <th class="text-nowrap px-2" style="width: 140px;">KATEGORI</th>
                        <th class="text-center text-nowrap px-2" style="width: 130px;">TOTAL TRANSAKSI</th>
                        <th class="text-center text-nowrap px-2" style="width: 130px;">PELANGGAN UNIK</th>
                        <th class="text-end text-nowrap px-2" style="width: 150px;">TOTAL OMSET</th>
                        <th class="pe-3 text-center text-nowrap" style="width: 70px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productSummary)): ?>
                        <?php foreach ($productSummary as $pIdx => $prod): ?>
                            <tr>
                                <td class="ps-3 text-muted small text-nowrap"><?= $pIdx + 1 ?></td>
                                <td class="text-nowrap">
                                    <div class="fw-bold text-dark small d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-tag text-primary small"></i> <?= esc($prod['nama_produk']) ?>
                                    </div>
                                    <small class="text-muted font-monospace"><?= esc($prod['kode_produk'] ?? '-') ?></small>
                                </td>
                                <td class="text-nowrap px-2">
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?= esc($prod['nama_kategori']) ?>
                                    </span>
                                </td>
                                <td class="text-center font-monospace text-nowrap px-2">
                                    <?= (int)$prod['total_order'] ?>x Order
                                </td>
                                <td class="text-center text-nowrap px-2">
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1">
                                        <i class="fa-solid fa-user me-1"></i><?= (int)$prod['total_pelanggan'] ?> Orang
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-bold text-success text-nowrap px-2">
                                    <?= format_rupiah($prod['total_omset']) ?>
                                </td>
                                <td class="pe-3 text-center text-nowrap">
                                    <a href="<?= base_url('rfm/tren-produk?produk=' . $prod['id']) ?>" class="btn btn-sm text-white btn-icon-square shadow-sm" style="background-color: #6366f1;" title="Filter Tren Khusus Produk Ini">
                                        <i class="fa-solid fa-filter"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4 small">
                                Belum ada riwayat pesanan produk tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- MODE 2: TAMPILAN CARD RESPONTIF (TOUCH-FRIENDLY CARDS) -->
        <div id="productSummaryCardView" class="p-2.5 p-sm-3" style="display: none;">
            <?php if (!empty($productSummary)): ?>
                <div class="row g-2.5">
                    <?php foreach ($productSummary as $pIdx => $prod): ?>
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card border shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                                <!-- Header Card: Index, Nama Produk, Kategori -->
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                    <div class="d-flex align-items-start gap-2 min-w-0">
                                        <span class="badge bg-light text-muted border font-monospace mt-0.5">#<?= $pIdx + 1 ?></span>
                                        <div class="min-w-0">
                                            <h6 class="fw-bold text-dark mb-0 fs-6 text-truncate" style="line-height: 1.35;" title="<?= esc($prod['nama_produk']) ?>">
                                                <i class="fa-solid fa-tag text-primary small me-1"></i><?= esc($prod['nama_produk']) ?>
                                            </h6>
                                            <span class="text-muted font-monospace small" style="font-size: 0.75rem;">
                                                <?= esc($prod['kode_produk'] ?? '-') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-dark border px-2 py-1 text-nowrap flex-shrink-0" style="font-size: 0.72rem;">
                                        <?= esc($prod['nama_kategori']) ?>
                                    </span>
                                </div>

                                <!-- Ringkasan Metrik 3 Kolom: Order, Pelanggan, Omset -->
                                <div class="row g-2 py-2 my-1 border-top border-bottom bg-light bg-opacity-50 rounded text-center">
                                    <div class="col-4 border-end">
                                        <div class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.03em;">Total Order</div>
                                        <div class="fw-bold font-monospace text-dark" style="font-size: 0.85rem;">
                                            <?= (int)$prod['total_order'] ?>x
                                        </div>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.03em;">Pelanggan</div>
                                        <div class="fw-bold font-monospace text-primary" style="font-size: 0.85rem;">
                                            <?= (int)$prod['total_pelanggan'] ?> Org
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.03em;">Total Omset</div>
                                        <div class="fw-bold font-monospace text-success text-truncate px-1" style="font-size: 0.8rem;">
                                            <?= format_rupiah($prod['total_omset']) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer: Tombol Filter Tren Produk Ini -->
                                <div class="d-flex align-items-center justify-content-end mt-2 pt-1">
                                    <a href="<?= base_url('rfm/tren-produk?produk=' . $prod['id']) ?>" class="btn btn-sm text-white fw-semibold px-3 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-sm w-100 justify-content-center" style="background-color: #6366f1; border-radius: 6px; font-size: 0.78rem;">
                                        <i class="fa-solid fa-filter"></i> Filter Tren Produk Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4 small">
                    <i class="fa-solid fa-box-open fs-3 text-secondary mb-2 d-block"></i>
                    Belum ada riwayat pesanan produk tercatat.
                </div>
            <?php endif; ?>
        </div>
    </div>
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
    const ctx = document.getElementById('chartTrenSegmen');
    if (ctx) {
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        const colors = {
            'Champions': '#10b981',
            'Loyal Customers': '#0284c7',
            'Potential Loyalist': '#6366f1',
            'New Customers': '#06b6d4',
            'Big Spenders': '#a855f7',
            'Frequent Low Value': '#eab308',
            'Need Attention': '#f97316',
            'At Risk Customers': '#ef4444',
            "Can't Lose Them": '#ec4899',
            'Hibernating': '#64748b'
        };

        const datasets = [
            <?php foreach ($segmentList as $seg): ?>
                {
                    label: <?= json_encode($seg) ?>,
                    data: <?= json_encode(array_values($monthlyMatrix[$seg])) ?>,
                    borderColor: colors[<?= json_encode($seg) ?>] || '#64748b',
                    backgroundColor: 'transparent',
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
            <?php endforeach; ?>
        ];

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            padding: 8,
                            font: { size: 10, weight: '600' },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Interaktivitas Crosshair Matriks RFM (Highlight Baris & Kolom Sinkron)
    const matrixTable = document.querySelector('.table-tren-matrix');
    if (matrixTable) {
        const matrixCells = matrixTable.querySelectorAll('tbody td[data-month]');
        
        matrixCells.forEach(cell => {
            cell.addEventListener('mouseenter', function() {
                const month = this.getAttribute('data-month');
                if (!month) return;

                // Sorot baris aktif
                const tr = this.closest('tr');
                if (tr) tr.classList.add('tren-row-hover');

                // Sorot seluruh sel pada kolom bulan yang sama
                matrixTable.querySelectorAll(`td[data-month="${month}"]`).forEach(c => {
                    c.classList.add('tren-col-hover');
                });
                const th = matrixTable.querySelector(`th[data-month-col="${month}"]`);
                if (th) th.classList.add('tren-th-hover');
            });

            cell.addEventListener('mouseleave', function() {
                const month = this.getAttribute('data-month');

                // Hapus sorot baris
                const tr = this.closest('tr');
                if (tr) tr.classList.remove('tren-row-hover');

                // Hapus sorot kolom
                if (month) {
                    matrixTable.querySelectorAll(`td[data-month="${month}"]`).forEach(c => {
                        c.classList.remove('tren-col-hover');
                    });
                    const th = matrixTable.querySelector(`th[data-month-col="${month}"]`);
                    if (th) th.classList.remove('tren-th-hover');
                }
            });
        });
    }

    // CSV Export
    const btnExport = document.getElementById('btnExportTrenCsv');
    if (btnExport) {
        btnExport.addEventListener('click', function() {
            const rows = [
                ['Segmen RFM', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des', 'Total'],
                <?php foreach ($segmentList as $seg): ?>
                    [
                        <?= json_encode($seg) ?>,
                        <?php foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $m): ?>
                            <?= json_encode((string)($monthlyMatrix[$seg][$m] ?? 0)) ?>,
                        <?php endforeach; ?>
                        <?= json_encode((string)($distribution[$seg] ?? 0)) ?>
                    ],
                <?php endforeach; ?>
            ];
            let csv = '';
            rows.forEach(r => {
                csv += r.map(c => '"' + c.replace(/"/g, '""') + '"').join(',') + "\n";
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", 'Tren_Segmen_RFM_<?= esc($selectedTahun) ?>.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    // Toggle Mode Tampilan Tabel vs Card Ringkasan Produk
    window.setProductSummaryView = function(mode) {
        const tableView = document.getElementById('productSummaryTableView');
        const cardView = document.getElementById('productSummaryCardView');
        const btnTable = document.getElementById('btnModeTable');
        const btnCard = document.getElementById('btnModeCard');

        if (!tableView || !cardView) return;

        if (mode === 'card') {
            tableView.style.setProperty('display', 'none', 'important');
            cardView.style.setProperty('display', 'block', 'important');
            if (btnTable) { 
                btnTable.classList.remove('active', 'btn-primary'); 
                btnTable.classList.add('btn-outline-primary'); 
            }
            if (btnCard) { 
                btnCard.classList.add('active', 'btn-primary'); 
                btnCard.classList.remove('btn-outline-primary'); 
            }
            try { localStorage.setItem('rfm_product_view_mode', 'card'); } catch(e){}
        } else {
            tableView.style.setProperty('display', 'block', 'important');
            cardView.style.setProperty('display', 'none', 'important');
            if (btnTable) { 
                btnTable.classList.add('active', 'btn-primary'); 
                btnTable.classList.remove('btn-outline-primary'); 
            }
            if (btnCard) { 
                btnCard.classList.remove('active', 'btn-primary'); 
                btnCard.classList.add('btn-outline-primary'); 
            }
            try { localStorage.setItem('rfm_product_view_mode', 'table'); } catch(e){}
        }
    };

    // Deteksi awal mode tampilan: jika ada memori pilihan user gunakan itu, jika tidak default card untuk layar kecil (< 992px)
    try {
        const savedViewMode = localStorage.getItem('rfm_product_view_mode');
        if (savedViewMode === 'card' || savedViewMode === 'table') {
            window.setProductSummaryView(savedViewMode);
        } else if (window.innerWidth < 992) {
            window.setProductSummaryView('card');
        } else {
            window.setProductSummaryView('table');
        }
    } catch(e) {
        if (window.innerWidth < 992) {
            window.setProductSummaryView('card');
        }
    }
});
</script>
<?= $this->endSection() ?>
