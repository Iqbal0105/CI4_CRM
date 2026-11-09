<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Mobile responsive optimizations for Dashboard Analisis RFM */
    .card {
        border-radius: 12px;
    }
    .rfm-stat-card {
        border-radius: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        background: #ffffff;
    }
    .rfm-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }
    /* Horizontal scroll for top sub-navigation on mobile */
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
    /* Horizontal scroll for bottom tabs on mobile */
    #rfmBottomTabs {
        border-bottom: 2px solid #e2e8f0;
        overflow-x: auto;
        flex-wrap: nowrap !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #rfmBottomTabs::-webkit-scrollbar {
        display: none;
    }
    #rfmBottomTabs .nav-item {
        flex-shrink: 0;
    }
    #rfmBottomTabs .nav-link {
        color: #475569;
        border-radius: 8px 8px 0 0;
        border: 1px solid transparent;
        border-bottom: none;
        padding: 9px 16px;
        font-size: 0.85rem;
        background-color: transparent;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    #rfmBottomTabs .nav-link:hover {
        color: #1e293b;
        background-color: #f8fafc;
    }
    #rfmBottomTabs .nav-link.active {
        background-color: #ffffff !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        border: 1px solid #e2e8f0 !important;
        border-top: 3px solid #4f46e5 !important;
        border-bottom: 2px solid #ffffff !important;
        margin-bottom: -2px;
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
        .rfm-donut-container {
            min-height: 280px !important;
            height: 290px !important;
        }
        .rfm-seg-card {
            padding: 0.5rem 0.6rem !important;
        }
        .rfm-seg-title {
            font-size: 0.78rem !important;
        }
        .rfm-seg-meta {
            font-size: 0.7rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP BANNER RFM -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Dashboard Utama</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-layer-group me-2"></i>Dashboard Analisis RFM
                </h3>
                <p class="text-white-50 small mb-0">Ringkasan segmentasi Recency (Kebaruan), Frequency (Frekuensi), dan Monetary (Nominal Belanja)</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" title="Broadcast WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i> <span>Broadcast WA</span>
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel" title="Import Excel Data">
                    <i class="fa-solid fa-file-excel"></i> <span>Import Excel</span>
                </button>
                <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" style="background-color: #6366f1;" id="btnExportRfmDashboardCsv" title="Export CSV">
                    <i class="fa-solid fa-file-csv"></i> <span>Export CSV</span>
                </button>
            </div>
        </div>

        <!-- 6 SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('rfm') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 1. Dashboard RFM
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
        <form method="GET" action="<?= base_url('rfm') ?>" id="rfmFilterForm" class="row g-2 align-items-center">
            <input type="hidden" name="days" value="<?= esc($days ?? 365) ?>">
            <?php if (!empty($filterSegment)): ?>
                <input type="hidden" name="segment" value="<?= esc($filterSegment) ?>">
            <?php endif; ?>
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-filter me-1 text-primary"></i>Kategori:</label>
                <select name="kategori" class="form-select form-select-sm" onchange="document.getElementById('rfmFilterForm').submit()">
                    <option value="">-- Semua Kategori --</option>
                    <?php if (!empty($kategoriList)): ?>
                        <?php foreach ($kategoriList as $k): ?>
                            <?php 
                                $kVal = $k['kode_kategori'] ?? $k['id'];
                                $kSelected = (
                                    (string)$selectedKategori === (string)$kVal ||
                                    (string)$selectedKategori === (string)$k['id'] ||
                                    strcasecmp((string)$selectedKategori, (string)$k['nama_kategori']) === 0 ||
                                    strcasecmp((string)$selectedKategori, (string)($k['kode_kategori'] ?? '')) === 0 ||
                                    (strtolower((string)$selectedKategori) === 'fnb' && stripos($k['nama_kategori'], 'makanan') !== false)
                                );
                            ?>
                            <option value="<?= esc($kVal) ?>" <?= $kSelected ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="fnb" <?= in_array(strtolower((string)($selectedKategori ?? '')), ['fnb', 'makanan & minuman']) ? 'selected' : '' ?>>Makanan &amp; Minuman</option>
                        <option value="elektronik" <?= in_array(strtolower((string)($selectedKategori ?? '')), ['elektronik', 'elektronik & gadget']) ? 'selected' : '' ?>>Elektronik</option>
                        <option value="umum" <?= strtolower((string)($selectedKategori ?? '')) === 'umum' ? 'selected' : '' ?>>Umum</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-box me-1 text-primary"></i>Produk:</label>
                <select name="produk" class="form-select form-select-sm" onchange="document.getElementById('rfmFilterForm').submit()">
                    <option value="">-- Semua Produk --</option>
                    <?php if (!empty($produkList)): ?>
                        <?php foreach ($produkList as $p): ?>
                            <?php 
                                $pSelected = (
                                    (string)$selectedProduk === (string)$p['id'] ||
                                    strcasecmp((string)$selectedProduk, (string)$p['nama_produk']) === 0
                                );
                            ?>
                            <option value="<?= esc($p['id']) ?>" <?= $pSelected ? 'selected' : '' ?>>
                                <?= esc($p['nama_produk']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1" <?= (string)($selectedProduk ?? '') === '1' ? 'selected' : '' ?>>Laptop Pro 15-inch</option>
                        <option value="2" <?= (string)($selectedProduk ?? '') === '2' ? 'selected' : '' ?>>Kopi Arabika Gayo</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="form-label small text-muted mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i>Bulan:</label>
                    <?php if (!empty($selectedKategori) || !empty($selectedProduk) || !empty($selectedBulan)): ?>
                        <a href="<?= base_url('rfm') ?>" class="text-danger small text-decoration-none fw-semibold">
                            <i class="fa-solid fa-rotate-left me-1"></i>Reset Filter
                        </a>
                    <?php endif; ?>
                </div>
                <select name="bulan" class="form-select form-select-sm" onchange="document.getElementById('rfmFilterForm').submit()">
                    <option value="">-- Semua Bulan --</option>
                    <option value="11" <?= ($selectedBulan === '2026-11' || $selectedBulan === '11' || stripos((string)($selectedBulan ?? ''), 'november') !== false) ? 'selected' : '' ?>>November 2026</option>
                    <option value="10" <?= ($selectedBulan === '2026-10' || $selectedBulan === '10' || stripos((string)($selectedBulan ?? ''), 'oktober') !== false) ? 'selected' : '' ?>>Oktober 2026</option>
                    <option value="9" <?= ($selectedBulan === '2026-09' || $selectedBulan === '9' || stripos((string)($selectedBulan ?? ''), 'september') !== false) ? 'selected' : '' ?>>September 2026</option>
                    <option value="8" <?= ($selectedBulan === '2026-08' || $selectedBulan === '8' || stripos((string)($selectedBulan ?? ''), 'agustus') !== false) ? 'selected' : '' ?>>Agustus 2026</option>
                    <option value="7" <?= ($selectedBulan === '2026-07' || $selectedBulan === '7' || stripos((string)($selectedBulan ?? ''), 'juli') !== false) ? 'selected' : '' ?>>Juli 2026</option>
                </select>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($selectedKategori) || !empty($selectedProduk) || !empty($selectedBulan) || !empty($filterSegment)): ?>
    <div class="alert alert-info py-2 px-3 d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2" style="border-radius: 8px; font-size: 0.85rem;">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <i class="fa-solid fa-filter text-primary"></i><strong>Filter Aktif:</strong>
            <?php if (!empty($selectedKategori)): ?>
                <span class="badge bg-primary">Kategori: <?= esc($activeKategoriName ?? $selectedKategori) ?></span>
            <?php endif; ?>
            <?php if (!empty($selectedProduk)): ?>
                <span class="badge bg-primary">Produk: <?= esc($activeProdukName ?? $selectedProduk) ?></span>
            <?php endif; ?>
            <?php if (!empty($selectedBulan)): ?>
                <span class="badge bg-primary">Bulan: <?= esc($activeBulanName ?? $selectedBulan) ?></span>
            <?php endif; ?>
            <?php if (!empty($filterSegment)): ?>
                <span class="badge bg-warning text-dark">Segmen: <?= esc($filterSegment) ?></span>
            <?php endif; ?>
            <span class="text-muted ms-1">(Ditemukan <strong><?= number_format(count($customers), 0, ',', '.') ?></strong> pembeli)</span>
        </div>
        <a href="<?= base_url('rfm') ?>" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.78rem;">
            <i class="fa-solid fa-xmark me-1"></i>Hapus Filter
        </a>
    </div>
<?php endif; ?>

<!-- 4 STAT CARDS -->
<div class="row g-2 g-sm-3 mb-4">
    <!-- 1. Total Pembeli -->
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-2 p-sm-3 h-100 rfm-stat-card" style="border-radius: 12px; border-left: 4px solid #2563eb !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.15rem;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark font-monospace text-nowrap text-truncate" style="font-size: clamp(1rem, 3.2vw, 1.4rem); line-height: 1.2;">
                        <?= number_format(count($customers), 0, ',', '.') ?>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.68rem; letter-spacing: 0.04em;">
                        TOTAL PEMBELI
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Recency -->
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-2 p-sm-3 h-100 rfm-stat-card" style="border-radius: 12px; border-left: 4px solid #06b6d4 !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.15rem;">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark font-monospace text-nowrap text-truncate" style="font-size: clamp(1rem, 3.2vw, 1.4rem); line-height: 1.2;">
                        <?= esc($avgRecencyDays ?? 15) ?> <span class="fw-normal text-muted" style="font-size: 0.8em;">Hari</span>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.68rem; letter-spacing: 0.04em;">
                        RECENCY (R: <?= esc($avgRScore ?? 3.2) ?>/5)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Frequency -->
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-2 p-sm-3 h-100 rfm-stat-card" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.15rem;">
                    <i class="fa-solid fa-rotate"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark font-monospace text-nowrap text-truncate" style="font-size: clamp(1rem, 3.2vw, 1.4rem); line-height: 1.2;">
                        <?= esc($avgFrequency ?? 1.4) ?>x <span class="fw-normal text-muted" style="font-size: 0.8em;">Order</span>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.68rem; letter-spacing: 0.04em;">
                        FREQUENCY (F: <?= esc($avgFScore ?? 3.0) ?>/5)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Monetary -->
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-2 p-sm-3 h-100 rfm-stat-card" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.15rem;">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark font-monospace text-nowrap text-truncate" style="font-size: clamp(0.92rem, 2.7vw, 1.35rem); line-height: 1.2;">
                        <span class="fw-semibold text-muted" style="font-size: 0.8em;">Rp</span> <?= number_format((float) ($avgMonetary ?? 2073515), 0, ',', '.') ?>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.68rem; letter-spacing: 0.04em;">
                        MONETARY (M: <?= esc($avgMScore ?? 2.8) ?>/5)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS & 10 SEGMENTS ROW -->
<div class="row g-3 mb-4">
    <!-- Donut Chart -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-primary"></i> Distribusi Segmen Pelanggan
                </h6>
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <?php if (count($customers) > 0): ?>
                    <div class="w-100 rfm-donut-container" style="min-height: 380px; height: 390px; position: relative;">
                        <canvas id="chartRfmDistribution"></canvas>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-chart-pie fa-2x mb-2 text-secondary opacity-50"></i>
                        <p class="small mb-0">Tidak ada data distribusi untuk filter ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 10 Segments Cards Grid -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-cubes text-primary"></i> Matriks 10 Segmen RFM
                </h6>
                <a href="<?= base_url('rfm/customer') ?>" class="text-primary small text-decoration-none fw-semibold">
                    <i class="fa-solid fa-users me-1"></i>Buka Semua Customer
                </a>
            </div>
            <div class="card-body p-3">
                <?php
                $totalAllCust = count($customers);
                $buildSegUrl = function(string $segName) use ($selectedKategori, $selectedProduk, $selectedBulan): string {
                    $params = ['segment' => $segName];
                    if (!empty($selectedKategori)) $params['kategori'] = $selectedKategori;
                    if (!empty($selectedProduk)) $params['produk'] = $selectedProduk;
                    if (!empty($selectedBulan)) $params['bulan'] = $selectedBulan;
                    return base_url('rfm/customer?' . http_build_query($params));
                };
                $getSegStats = function(string $key, array $aliases = []) use ($distribution, $totalAllCust): string {
                    $cnt = $distribution[$key] ?? 0;
                    foreach ($aliases as $alt) {
                        if (isset($distribution[$alt])) {
                            $cnt = max($cnt, $distribution[$alt]);
                        }
                    }
                    $pct = $totalAllCust > 0 ? round(($cnt / $totalAllCust) * 100, 1) : 0;
                    return "{$cnt} ({$pct}%)";
                };
                ?>
                <div class="row g-2">
                    <!-- Champions -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-warning small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-trophy me-1"></i>Champions</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Champions') ?></span>
                                <a href="<?= $buildSegUrl('Champions') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Loyal Customers -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-success small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-gem me-1"></i>Loyal Customers</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Loyal Customers') ?></span>
                                <a href="<?= $buildSegUrl('Loyal Customers') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Potential Loyalist -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-primary small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-star me-1"></i>Potential Loyalist</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Potential Loyalist', ['Potential Loyalists']) ?></span>
                                <a href="<?= $buildSegUrl('Potential Loyalist') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- New Customers -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-info small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-user-plus me-1"></i>New Customers</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('New Customers') ?></span>
                                <a href="<?= $buildSegUrl('New Customers') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Big Spenders -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-purple small text-truncate rfm-seg-title mb-1" style="color: #8b5cf6;"><i class="fa-solid fa-credit-card me-1"></i>Big Spenders</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Big Spenders') ?></span>
                                <a href="<?= $buildSegUrl('Big Spenders') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Frequent Low Value -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-secondary small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-basket-shopping me-1"></i>Frequent Low Value</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Frequent Low Value') ?></span>
                                <a href="<?= $buildSegUrl('Frequent Low Value') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Need Attention -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-danger small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-bell me-1"></i>Need Attention</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Need Attention') ?></span>
                                <a href="<?= $buildSegUrl('Need Attention') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- At Risk Customers -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-danger small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>At Risk Customers</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('At Risk Customers', ['At Risk']) ?></span>
                                <a href="<?= $buildSegUrl('At Risk Customers') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Can't Lose Them -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-danger small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-heart-crack me-1"></i>Can't Lose Them</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats("Can't Lose Them") ?></span>
                                <a href="<?= $buildSegUrl("Can't Lose Them") ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Hibernating -->
                    <div class="col-6">
                        <div class="p-2 rounded border bg-light rfm-seg-card h-100">
                            <div class="fw-bold text-secondary small text-truncate rfm-seg-title mb-1"><i class="fa-solid fa-moon me-1"></i>Hibernating</div>
                            <div class="text-muted d-flex align-items-center justify-content-between rfm-seg-meta" style="font-size: 0.76rem;">
                                <span class="text-truncate"><?= $getSegStats('Hibernating') ?></span>
                                <a href="<?= $buildSegUrl('Hibernating') ?>" class="text-decoration-none ms-1 flex-shrink-0">Lihat &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM TABS STRATEGI & ACTION -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-3">
        <ul class="nav nav-tabs card-header-tabs" id="rfmBottomTabs">
            <li class="nav-item">
                <a class="nav-link active fw-semibold small" href="#tabStrategi" data-bs-toggle="tab">
                    <i class="fa-solid fa-book-bookmark text-primary me-1"></i>1. Strategi &amp; Threshold Segmen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold small" href="#tabTren" data-bs-toggle="tab">
                    <i class="fa-solid fa-chart-line text-success me-1"></i>2. Ringkasan Tren Bulanan (2 Bln)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold small" href="#tabTopProduk" data-bs-toggle="tab">
                    <i class="fa-solid fa-bag-shopping text-warning me-1"></i>3. Top Produk RFM (<?= esc($totalAllProductsCount ?? 23) ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold small" href="#tabPreview" data-bs-toggle="tab">
                    <i class="fa-solid fa-users text-primary me-1"></i>4. Preview Customer Terkini (Top 10)
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content">
            <!-- TAB 1: STRATEGI & THRESHOLD SEGMEN -->
            <div class="tab-pane fade show active" id="tabStrategi">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="fw-bold text-dark small d-flex align-items-center gap-2">
                        <i class="fa-solid fa-atom" style="color: #6366f1;"></i>
                        <span>Definisi Perilaku, Target Goal &amp; Rekomendasi Aksi Promo</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 w-100 w-md-auto">
                        <a href="<?= base_url('rfm/matriks') ?>" class="btn btn-sm text-white fw-semibold flex-fill flex-sm-grow-0" style="background-color: #4f46e5; font-size: 0.78rem;">
                            <i class="fa-solid fa-table-cells me-1"></i>125 Matriks Aturan
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold flex-fill flex-sm-grow-0" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#modalThresholdSkor">
                            <i class="fa-solid fa-sliders me-1"></i>Threshold Skor
                        </button>
                        <button type="button" class="btn btn-sm text-white fw-semibold flex-fill flex-sm-grow-0" style="background-color: #0284c7; font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#modalTambahStrategi">
                            <i class="fa-solid fa-circle-plus me-1"></i>Tambah Strategi
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 18%;">SEGMEN</th>
                                <th style="width: 12%;">CUSTOMER</th>
                                <th style="width: 32%;">GOAL (TARGET PERILAKU)</th>
                                <th style="width: 33%;">ACTION (STRATEGI PROMO &amp; CRM)</th>
                                <th style="width: 5%; text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $strategiesList = [
                                [
                                    'segment' => 'Champions',
                                    'icon'    => 'fa-solid fa-trophy text-warning',
                                    'goal'    => 'Pertahankan loyalitas dan beri apresiasi',
                                    'action'  => 'Berikan hadiah eksklusif, early access, dan testimoni program',
                                ],
                                [
                                    'segment' => 'Loyal Customers',
                                    'icon'    => 'fa-solid fa-gem text-success',
                                    'goal'    => 'Naikkan frekuensi atau AOV',
                                    'action'  => 'Tawarkan bundling, loyalty reward, dan referral program',
                                ],
                                [
                                    'segment' => 'Potential Loyalist',
                                    'alias'   => ['Potential Loyalists'],
                                    'icon'    => 'fa-solid fa-star text-primary',
                                    'goal'    => 'Dorong jadi loyal customer',
                                    'action'  => 'Follow up rutin, edukasi produk, dan masuk grup WA konsultasi',
                                ],
                                [
                                    'segment' => 'New Customers',
                                    'icon'    => 'fa-solid fa-user-plus text-info',
                                    'goal'    => 'Bina jadi pembeli ulang',
                                    'action'  => 'Reminder, konsultasi awal, dan edukasi cara konsumsi',
                                ],
                                [
                                    'segment' => 'Big Spenders',
                                    'icon'    => 'fa-solid fa-credit-card',
                                    'icon_color' => 'color: #8b5cf6;',
                                    'goal'    => 'Pertahankan nilai belanja',
                                    'action'  => 'Berikan penawaran VIP, diskon eksklusif, dan layanan personal',
                                ],
                                [
                                    'segment' => 'Frequent Low Value',
                                    'icon'    => 'fa-solid fa-basket-shopping text-secondary',
                                    'goal'    => 'Dorong naik kelas belanja',
                                    'action'  => 'Upsell produk premium, bundling hemat',
                                ],
                                [
                                    'segment' => 'Need Attention',
                                    'icon'    => 'fa-solid fa-bell text-danger',
                                    'goal'    => 'Kenalkan kembali brand &amp; manfaat',
                                    'action'  => 'Kirim ulang value produk + testimoni relevan',
                                ],
                                [
                                    'segment' => 'At Risk Customers',
                                    'alias'   => ['At Risk'],
                                    'icon'    => 'fa-solid fa-triangle-exclamation text-danger',
                                    'goal'    => 'Cegah churn, reaktivasi',
                                    'action'  => 'Diskon khusus, personal outreach, tanya feedback',
                                ],
                                [
                                    'segment' => "Can't Lose Them",
                                    'icon'    => 'fa-solid fa-heart-crack',
                                    'icon_color' => 'color: #ec4899;',
                                    'goal'    => 'Win back segera',
                                    'action'  => 'Kontak langsung, penawaran istimewa, eskalasi ke manajemen',
                                ],
                                [
                                    'segment' => 'Hibernating',
                                    'icon'    => 'fa-solid fa-moon text-secondary',
                                    'goal'    => 'Reaktivasi atau lepaskan',
                                    'action'  => 'Kampanye re-engagement, survei alasan berhenti',
                                ],
                            ];

                            // Sinkronkan Goal & Action dari database rfm_segments
                            if (!empty($strategyDbMap)) {
                                foreach ($strategiesList as &$st) {
                                    $lookupKey = strtolower(trim($st['segment']));
                                    $matchedRow = $strategyDbMap[$lookupKey] ?? null;
                                    if (!$matchedRow && !empty($st['alias'])) {
                                        foreach ($st['alias'] as $al) {
                                            $alKey = strtolower(trim($al));
                                            if (isset($strategyDbMap[$alKey])) {
                                                $matchedRow = $strategyDbMap[$alKey];
                                                break;
                                            }
                                        }
                                    }
                                    if ($matchedRow) {
                                        if (isset($matchedRow['description']) && $matchedRow['description'] !== null && $matchedRow['description'] !== '') {
                                            $st['goal'] = $matchedRow['description'];
                                        }
                                        if (isset($matchedRow['recommended_action']) && $matchedRow['recommended_action'] !== null && $matchedRow['recommended_action'] !== '') {
                                            $st['action'] = $matchedRow['recommended_action'];
                                        }
                                    }
                                }
                                unset($st);
                            }
                            ?>
                            <?php foreach ($strategiesList as $st): ?>
                                <tr data-segment="<?= esc($st['segment']) ?>">
                                    <td class="fw-semibold text-dark text-nowrap">
                                        <i class="<?= $st['icon'] ?> me-2" <?= !empty($st['icon_color']) ? 'style="' . $st['icon_color'] . '"' : '' ?>></i><?= esc($st['segment']) ?>
                                    </td>
                                    <td class="text-dark small">
                                        <?= $getSegStats($st['segment'], $st['alias'] ?? []) ?>
                                    </td>
                                    <td class="text-secondary small strat-goal-cell">
                                        <?= esc($st['goal']) ?>
                                    </td>
                                    <td class="text-secondary small strat-action-cell">
                                        <?= esc($st['action']) ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-light border py-1 px-2 text-secondary btn-edit-strat" title="Edit Strategi" onclick="editStrategy('<?= esc($st['segment']) ?>', <?= htmlspecialchars(json_encode($st['goal']), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($st['action']), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: RINGKASAN TREN BULANAN -->
            <div class="tab-pane fade" id="tabTren">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="fw-bold text-dark small d-flex align-items-center gap-1">
                        <i class="fa-solid fa-chart-line text-success me-1"></i>
                        <span>Tren Performa Omset &amp; Pelanggan Bulanan</span>
                        <span class="text-muted small fw-normal ms-1">(Menampilkan ringkasan periode terbaru)</span>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="<?= base_url('rfm/tracking') ?>" class="btn btn-sm text-white fw-semibold w-100 w-md-auto" style="background-color: #059669; font-size: 0.78rem;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Tracking Bulanan Lengkap &rarr;
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>PERIODE</th>
                                <th>PEMBELI UNIK</th>
                                <th>TRANSAKSI (F)</th>
                                <th>QTY</th>
                                <th>TOTAL OMSET</th>
                                <th>PERTUMBUHAN</th>
                                <th>PROFIT</th>
                                <th style="text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthlyTrends as $mt): ?>
                                <tr>
                                    <td class="fw-semibold text-dark text-nowrap">
                                        <i class="fa-regular fa-calendar me-2 text-muted"></i><?= esc($mt['periode_label']) ?>
                                    </td>
                                    <td>
                                        <span class="badge py-1 px-2 fw-bold" style="background-color: #0284c7; color: #fff; font-size: 0.75rem;"><?= (int)$mt['pembeli_unik'] ?> cust</span>
                                    </td>
                                    <td class="font-monospace small"><?= (int)$mt['transaksi_f'] ?>x</td>
                                    <td class="font-monospace small"><?= (int)$mt['qty_total'] ?> pcs</td>
                                    <td class="font-monospace fw-bold text-success">Rp <?= number_format((float)$mt['total_omset'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-success py-1 px-2 fw-semibold" style="font-size: 0.75rem;">+<?= number_format((float)$mt['growth_pct'], 1) ?>%</span>
                                    </td>
                                    <td class="font-monospace fw-bold text-success">Rp <?= number_format((float)$mt['profit'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('rfm?bulan=' . urlencode($mt['periode_key'])) ?>" class="btn btn-sm btn-outline-secondary py-1 px-2 fw-semibold" style="font-size: 0.78rem;">
                                            <i class="fa-solid fa-filter me-1"></i>Filter
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: TOP PRODUK RFM -->
            <div class="tab-pane fade" id="tabTopProduk">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="fw-bold text-dark small d-flex align-items-center gap-1">
                        <i class="fa-solid fa-bag-shopping text-warning me-1"></i>
                        <span>Top 10 Produk Berdasarkan Kontribusi Omset &amp; Pembeli</span>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm text-white fw-semibold w-100 w-md-auto" style="background-color: #d97706; font-size: 0.78rem;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Analisis Tren Produk Lengkap &rarr;
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 4%;">#</th>
                                <th style="width: 32%;">NAMA PRODUK</th>
                                <th style="width: 9%; text-align: center;">PEMBELI</th>
                                <th style="width: 10%;">TOTAL ORDER</th>
                                <th style="width: 6%;">QTY</th>
                                <th style="width: 14%;">TOTAL OMSET</th>
                                <th style="width: 12%;">RECENCY TERAKHIR</th>
                                <th style="width: 10%;">STATUS SEGMEN</th>
                                <th style="width: 6%; text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $tIdx => $tp): ?>
                                <tr>
                                    <td class="text-muted small"><?= $tIdx + 1 ?></td>
                                    <td class="fw-semibold text-dark small"><?= esc($tp['nama_produk']) ?></td>
                                    <td class="text-center">
                                        <span class="badge rounded-circle text-white fw-bold" style="background-color: #6366f1; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                            <?= (int)$tp['pembeli_count'] ?>
                                        </span>
                                    </td>
                                    <td class="font-monospace small"><?= (int)$tp['order_count'] ?>x</td>
                                    <td class="font-monospace small"><?= (int)$tp['qty_sum'] ?></td>
                                    <td class="font-monospace fw-bold text-success small">Rp <?= number_format((float)$tp['omset_sum'], 0, ',', '.') ?></td>
                                    <td class="text-muted small"><?= esc($tp['recency_str']) ?></td>
                                    <td>
                                        <?php if ($tp['status_segmen'] === 'High Demand'): ?>
                                            <span class="badge py-1 px-2 fw-semibold" style="background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 4px; font-size: 0.75rem;">
                                                High Demand
                                            </span>
                                        <?php else: ?>
                                            <span class="badge py-1 px-2 fw-semibold" style="background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; border-radius: 4px; font-size: 0.75rem;">
                                                <i class="fa-regular fa-clock me-1"></i>Steady Sales
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('rfm?produk=' . urlencode($tp['id'] ?? $tp['nama_produk'])) ?>" class="btn btn-sm text-white py-1 px-2 fw-semibold" style="background-color: #0284c7; font-size: 0.75rem;" title="Filter Produk Ini">
                                            <i class="fa-solid fa-filter me-1"></i>Filter
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: PREVIEW CUSTOMER TERKINI -->
            <div class="tab-pane fade" id="tabPreview">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="fw-bold text-dark small d-flex align-items-center gap-1">
                        <i class="fa-solid fa-users text-primary me-1"></i>
                        <span>Sampel 10 Pelanggan Terkini</span>
                        <span class="text-muted small fw-normal ms-1">(Total <?= number_format(count($customers), 0, ',', '.') ?> pelanggan dalam database)</span>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="<?= base_url('rfm/customer') ?>" class="btn btn-sm text-white fw-semibold w-100 w-md-auto" style="background-color: #06b6d4; font-size: 0.78rem;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Buka Semua Customer &rarr;
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 4%;">#</th>
                                <th style="width: 18%;">NAMA PEMBELI</th>
                                <th style="width: 16%;">NO. TELEPON</th>
                                <th style="width: 9%;">FREKUENSI</th>
                                <th style="width: 13%;">TOTAL BELANJA</th>
                                <th style="width: 11%;">TERAKHIR BELI</th>
                                <th class="text-center text-nowrap" style="min-width: 95px; width: 10%; white-space: nowrap;">SKOR RFM</th>
                                <th style="width: 13%;">SEGMEN</th>
                                <th style="width: 6%; text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getScoreBadge = function(int $score): string {
                                $bg = match(true) {
                                    $score >= 4 => '#10b981',
                                    $score == 3 => '#f59e0b',
                                    default     => '#ef4444',
                                };
                                return '<span class="badge text-white d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="background-color: ' . $bg . '; width: 20px; height: 22px; font-size: 0.75rem; border-radius: 4px; padding: 0;">' . $score . '</span>';
                            };

                            $getSegmentBadge = function(string $seg): string {
                                return match($seg) {
                                    'Champions'           => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-trophy me-1 text-warning"></i>Champions</span>',
                                    'Loyal Customers'     => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #d1fae5; color: #047857; border: 1px solid #a7f3d0; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-gem me-1 text-success"></i>Loyal Customers</span>',
                                    'Potential Loyalist', 'Potential Loyalists' => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-star me-1 text-primary"></i>Potential Loyalist</span>',
                                    'New Customers'       => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-user-plus me-1 text-info"></i>New Customers</span>',
                                    'Big Spenders'        => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-credit-card me-1 text-purple"></i>Big Spenders</span>',
                                    'Frequent Low Value'  => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-basket-shopping me-1 text-secondary"></i>Frequent Low Value</span>',
                                    'At Risk Customers', 'At Risk' => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-triangle-exclamation me-1 text-danger"></i>At Risk Customers</span>',
                                    "Can't Lose Them"     => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #fce7f3; color: #be185d; border: 1px solid #fbcfe8; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-heart-crack me-1 text-danger"></i>Can\'t Lose Them</span>',
                                    'Hibernating'         => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-moon me-1 text-secondary"></i>Hibernating</span>',
                                    default               => '<span class="badge px-3 py-1 fw-semibold" style="background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; border-radius: 20px; font-size: 0.78rem;"><i class="fa-solid fa-bell me-1 text-danger"></i>Need Attention</span>',
                                };
                            };
                            ?>
                            <?php foreach (array_slice($customers, 0, 10) as $cIdx => $c): ?>
                                <tr>
                                    <td class="text-muted small"><?= $cIdx + 1 ?></td>
                                    <td class="fw-semibold text-dark small"><?= esc($c['nama_pelanggan']) ?></td>
                                    <td class="text-muted small">
                                        <i class="fa-solid fa-phone me-1 text-secondary"></i><?= esc($c['no_telp'] ?? '-') ?>
                                    </td>
                                    <td class="font-monospace small"><?= (int)$c['frequency_count'] ?>x</td>
                                    <td class="font-monospace fw-bold text-success small">Rp <?= number_format((float)$c['monetary_total'], 0, ',', '.') ?></td>
                                    <td class="text-muted small"><?= (int)$c['recency_days'] ?> hr lalu</td>
                                    <td class="text-center text-nowrap" style="white-space: nowrap;">
                                        <div class="d-inline-flex align-items-center justify-content-center flex-nowrap" style="gap: 3px; white-space: nowrap;">
                                            <?= $getScoreBadge((int)($c['r_score'] ?? 3)) . $getScoreBadge((int)($c['f_score'] ?? 3)) . $getScoreBadge((int)($c['m_score'] ?? 3)) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?= $getSegmentBadge($c['segment'] ?? 'Need Attention') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <a href="<?= base_url('rfm/customer_detail?id=' . $c['id']) ?>" class="btn btn-sm btn-light border py-1 px-2 text-secondary" title="Detail Customer RFM">
                                                <i class="fa-regular fa-user"></i>
                                            </a>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', (string)($c['no_telp'] ?? '')) ?>" target="_blank" class="btn btn-sm text-white py-1 px-2" style="background-color: #10b981;" title="Chat WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL THRESHOLD SKOR R, F, M -->
<div class="modal fade" id="modalThresholdSkor" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-sliders me-2 text-primary"></i>Aturan Batasan Skor (Threshold) R, F, M
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Sistem mengelompokkan pelanggan ke dalam 5 kuantil skor (1 sampai 5) untuk setiap dimensi RFM:</p>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="border rounded p-3 h-100 bg-light">
                            <h6 class="fw-bold text-primary small mb-2"><i class="fa-solid fa-clock me-1"></i>Recency (R)</h6>
                            <table class="table table-sm table-bordered bg-white small mb-0">
                                <thead><tr class="table-light"><th>Skor</th><th>Batas Waktu</th></tr></thead>
                                <tbody>
                                    <tr><td><span class="badge bg-success">5</span></td><td>&le; 15 hari</td></tr>
                                    <tr><td><span class="badge bg-success">4</span></td><td>16 &ndash; 30 hari</td></tr>
                                    <tr><td><span class="badge bg-warning text-dark">3</span></td><td>31 &ndash; 60 hari</td></tr>
                                    <tr><td><span class="badge bg-danger">2</span></td><td>61 &ndash; 120 hari</td></tr>
                                    <tr><td><span class="badge bg-danger">1</span></td><td>&gt; 120 hari</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="border rounded p-3 h-100 bg-light">
                            <h6 class="fw-bold text-success small mb-2"><i class="fa-solid fa-rotate me-1"></i>Frequency (F)</h6>
                            <table class="table table-sm table-bordered bg-white small mb-0">
                                <thead><tr class="table-light"><th>Skor</th><th>Jumlah Order</th></tr></thead>
                                <tbody>
                                    <tr><td><span class="badge bg-success">5</span></td><td>&ge; 8 order</td></tr>
                                    <tr><td><span class="badge bg-success">4</span></td><td>5 &ndash; 7 order</td></tr>
                                    <tr><td><span class="badge bg-warning text-dark">3</span></td><td>3 &ndash; 4 order</td></tr>
                                    <tr><td><span class="badge bg-danger">2</span></td><td>2 order</td></tr>
                                    <tr><td><span class="badge bg-danger">1</span></td><td>1 order</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="border rounded p-3 h-100 bg-light">
                            <h6 class="fw-bold text-warning small mb-2"><i class="fa-solid fa-money-bill-wave me-1"></i>Monetary (M)</h6>
                            <table class="table table-sm table-bordered bg-white small mb-0">
                                <thead><tr class="table-light"><th>Skor</th><th>Total Belanja</th></tr></thead>
                                <tbody>
                                    <tr><td><span class="badge bg-success">5</span></td><td>&ge; Rp 10.000.000</td></tr>
                                    <tr><td><span class="badge bg-success">4</span></td><td>&ge; Rp 5.000.000</td></tr>
                                    <tr><td><span class="badge bg-warning text-dark">3</span></td><td>&ge; Rp 2.000.000</td></tr>
                                    <tr><td><span class="badge bg-danger">2</span></td><td>&ge; Rp 500.000</td></tr>
                                    <tr><td><span class="badge bg-danger">1</span></td><td>&lt; Rp 500.000</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH STRATEGI -->
<div class="modal fade" id="modalTambahStrategi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-circle-plus me-2 text-primary"></i>Tambah / Sesuaikan Strategi Segmen
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formStrategiSegmen" onsubmit="event.preventDefault(); saveStrategy();">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Segmen RFM</label>
                        <select class="form-select form-select-sm" id="inputStratSegmen" name="segment">
                            <option value="Champions">Champions</option>
                            <option value="Loyal Customers">Loyal Customers</option>
                            <option value="Potential Loyalist">Potential Loyalist</option>
                            <option value="New Customers">New Customers</option>
                            <option value="Big Spenders">Big Spenders</option>
                            <option value="Frequent Low Value">Frequent Low Value</option>
                            <option value="Need Attention">Need Attention</option>
                            <option value="At Risk Customers">At Risk Customers</option>
                            <option value="Can't Lose Them">Can't Lose Them</option>
                            <option value="Hibernating">Hibernating</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Goal (Target Perilaku)</label>
                        <input type="text" class="form-control form-control-sm" id="inputStratGoal" name="goal" placeholder="Misal: Dorong jadi pembeli rutin...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Action (Strategi Promo &amp; CRM)</label>
                        <textarea class="form-control form-control-sm" id="inputStratAction" name="action" rows="3" placeholder="Misal: Tawarkan bundling diskon dan voucher repeat order..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm fw-semibold" id="btnSimpanStrategi" onclick="saveStrategy()">
                    <i class="fa-solid fa-save me-1"></i>Simpan Strategi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL IMPORT RFM EXCEL -->
<div class="modal fade" id="modalImportRfmExcel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-file-excel me-2 text-success"></i>Sinkronisasi File Excel / Transaksi RFM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportRfmExcelDash" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="text-muted small">Unggah berkas spreadsheet transaksi penjualan marketplace (Shopee, TikTok, Tokopedia) atau template RFM (<code>.xlsx</code> / <code>.csv</code>) untuk menyelaraskan data transaksi dan segmentasi pelanggan.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih File Spreadsheet (.xlsx / .csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" id="fileRfmExcelDash" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="p-3 bg-light rounded small text-muted">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>Sistem akan otomatis mengimpor pesanan, mendaftarkan pelanggan baru, dan menghitung ulang nilai Recency, Frequency, Monetary (RFM).
                    </div>
                    <div id="rfmImportAlertDash" class="mt-3" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" id="btnSyncRfmSubmitDash">
                        <i class="fa-solid fa-rotate me-1"></i>Proses Sinkronisasi Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('chartRfmDistribution');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_keys($distribution ?? [])) ?>,
                datasets: [{
                    data: <?= json_encode(array_values($distribution ?? [])) ?>,
                    backgroundColor: [
                        '#f59e0b', // Champions
                        '#10b981', // Loyal Customers
                        '#3b82f6', // Potential Loyalist
                        '#06b6d4', // New Customers
                        '#8b5cf6', // Big Spenders
                        '#64748b', // Frequent Low Value
                        '#f97316', // Need Attention
                        '#ef4444', // At Risk Customers
                        '#ec4899', // Can't Lose Them
                        '#94a3b8'  // Hibernating
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 5,
                        bottom: 5,
                        left: 8,
                        right: 8
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            padding: 10,
                            font: {
                                size: 11,
                                family: "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif",
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const dataset = context.dataset.data;
                                const total = dataset.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return ` ${label}: ${value} customer (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }

    const btnExport = document.getElementById('btnExportRfmDashboardCsv');
    if (btnExport) {
        btnExport.addEventListener('click', function() {
            let csv = "Segmen,Jumlah Pelanggan\n";
            <?php foreach (($distribution ?? []) as $seg => $cnt): ?>
                csv += <?= json_encode($seg) ?> + "," + <?= json_encode((string)$cnt) ?> + "\n";
            <?php endforeach; ?>

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", "Ringkasan_Analisis_RFM_" + new Date().toISOString().slice(0,10) + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    $('#formImportRfmExcelDash').on('submit', function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('fileRfmExcelDash');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan pilih file spreadsheet (.xlsx / .csv) terlebih dahulu.');
            return;
        }

        var btn = $('#btnSyncRfmSubmitDash');
        var originalHtml = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyinkronkan...').prop('disabled', true);
        $('#rfmImportAlertDash').hide().removeClass('alert alert-danger alert-success').empty();

        var formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('rfm/import-excel') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    btn.html('<i class="fa-solid fa-check me-1"></i>Berhasil!');
                    $('#rfmImportAlertDash')
                        .addClass('alert alert-success small mb-0')
                        .html('<i class="fa-solid fa-circle-check me-1"></i>' + res.message)
                        .show();
                    setTimeout(function() {
                        window.location.reload();
                    }, 1400);
                } else {
                    btn.html(originalHtml).prop('disabled', false);
                    $('#rfmImportAlertDash')
                        .addClass('alert alert-danger small mb-0')
                        .html('<i class="fa-solid fa-triangle-exclamation me-1"></i>' + (res.message || 'Gagal memproses file.'))
                        .show();
                }
            },
            error: function(xhr) {
                btn.html(originalHtml).prop('disabled', false);
                var err = 'Terjadi kesalahan server saat memproses file.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    err = xhr.responseJSON.message;
                }
                $('#rfmImportAlertDash')
                    .addClass('alert alert-danger small mb-0')
                    .html('<i class="fa-solid fa-triangle-exclamation me-1"></i>' + err)
                    .show();
            }
        });
    });
});

function editStrategy(segmen, goal, action) {
    const sel = document.getElementById('inputStratSegmen');
    if (sel) sel.value = segmen;
    const gInput = document.getElementById('inputStratGoal');
    if (gInput) gInput.value = goal;
    const aInput = document.getElementById('inputStratAction');
    if (aInput) aInput.value = action;
    const modalEl = document.getElementById('modalTambahStrategi');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function saveStrategy() {
    const form = document.getElementById('formStrategiSegmen');
    if (!form) return;

    const segInput = document.getElementById('inputStratSegmen');
    const goalInput = document.getElementById('inputStratGoal');
    const actionInput = document.getElementById('inputStratAction');
    const btn = document.getElementById('btnSimpanStrategi');

    const seg = segInput ? segInput.value.trim() : '';
    const goal = goalInput ? goalInput.value.trim() : '';
    const action = actionInput ? actionInput.value.trim() : '';

    if (!seg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih segmen RFM terlebih dahulu!' });
        } else {
            alert('Pilih segmen RFM terlebih dahulu!');
        }
        return;
    }

    const originalBtnHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyimpan...';
    }

    const formData = new FormData(form);

    fetch('<?= base_url('rfm/strategy/save') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }

        if (data.status === 'success') {
            // Update row di tabel seketika tanpa perlu reload
            const rows = document.querySelectorAll('tr[data-segment]');
            rows.forEach(tr => {
                const trSeg = tr.getAttribute('data-segment');
                if (trSeg && trSeg.toLowerCase() === seg.toLowerCase()) {
                    const goalCell = tr.querySelector('.strat-goal-cell');
                    if (goalCell) goalCell.textContent = goal;
                    const actionCell = tr.querySelector('.strat-action-cell');
                    if (actionCell) actionCell.textContent = action;

                    // Update onclick tombol edit agar sinkron saat dibuka lagi
                    const editBtn = tr.querySelector('.btn-edit-strat');
                    if (editBtn) {
                        editBtn.onclick = function() {
                            editStrategy(seg, goal, action);
                        };
                    }
                }
            });

            // Perbarui token CSRF jika sistem me-refresh token
            if (data.csrf_token && data.csrf_hash) {
                const csrfInput = form.querySelector(`input[name="${data.csrf_token}"]`);
                if (csrfInput) csrfInput.value = data.csrf_hash;
            }

            // Tutup Modal
            const modalEl = document.getElementById('modalTambahStrategi');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();

            // Notifikasi Sukses
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disimpan!',
                    text: data.message || `Strategi segmen ${seg} berhasil diperbarui.`,
                    timer: 1800,
                    showConfirmButton: false
                });
            } else {
                alert(data.message || `Strategi segmen ${seg} berhasil diperbarui.`);
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: data.message || 'Terjadi kesalahan sistem.' });
            } else {
                alert(data.message || 'Gagal menyimpan strategi.');
            }
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
        console.error(err);
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Error Jaringan', text: 'Tidak dapat terhubung ke server.' });
        } else {
            alert('Terjadi kesalahan koneksi saat menyimpan strategi.');
        }
    });
}
</script>
<?= $this->endSection() ?>
