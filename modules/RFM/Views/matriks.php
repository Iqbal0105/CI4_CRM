<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* =========================================================
   ANIMASI & TEMA MATRIKS ATURAN RFM
   ========================================================= */

/* Entrance animation untuk card */
@keyframes matriksCardFadeIn {
    0% { opacity: 0; transform: translateY(14px); }
    100% { opacity: 1; transform: translateY(0); }
}

.matriks-animated-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    margin-bottom: 24px;
    overflow: hidden;
    animation: matriksCardFadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* Stat Cards hover lift animation */
.stat-matriks-card {
    border-radius: 12px;
    background: #ffffff;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    animation: matriksCardFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.stat-matriks-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(45, 106, 79, 0.12);
}

.stat-matriks-card.cursor-pointer:hover {
    border-color: #6366f1 !important;
}

/* Icon Box Rounded */
.stat-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

/* Staggered row slide-in animation */
@keyframes matriksRowSlideIn {
    0% { opacity: 0; transform: translateY(6px); }
    100% { opacity: 1; transform: translateY(0); }
}

#tableMatriksRfm tbody tr {
    animation: matriksRowSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: background-color 0.18s ease;
}

#tableMatriksRfm tbody tr:hover {
    background-color: rgba(45, 106, 79, 0.04);
}

/* Filter buttons */
.filter-btn {
    transition: all 0.2s ease;
    font-size: 0.78rem;
    white-space: nowrap;
    flex-shrink: 0;
}

.filter-btn:hover {
    transform: translateY(-1px);
}

/* Responsive RFM Matriks System */
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

/* Stat Matriks Cards */
.stat-matriks-card .stat-val {
    font-size: clamp(1.2rem, 3.4vw, 1.85rem);
    font-weight: 800;
    line-height: 1.15;
}
.stat-matriks-card .stat-title {
    font-size: clamp(0.64rem, 1.8vw, 0.72rem);
    letter-spacing: 0.04em;
    line-height: 1.25;
}

/* Mobile Rule Card */
.rule-card-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.rule-card-item .card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.rule-card-item .card:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,0.06) !important;
}
.rule-card-item .card:active {
    transform: scale(0.99);
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
    .stat-matriks-card {
        padding: 12px 10px !important;
    }
    .stat-icon-box {
        width: 32px !important;
        height: 32px !important;
        font-size: 0.95rem !important;
    }
}
</style>

<!-- TOP BANNER RFM (WARNA SISTEM GRADASI HIJAU) -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Matriks Aturan RFM</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-table me-2"></i>Matriks Aturan RFM (125 Aturan)
                </h3>
                <p class="text-white-50 small mb-0">Kamus lengkap pemetaan kombinasi skor Recency (1-5), Frequency (1-5), dan Monetary (1-5) ke 10 Segmen RFM &amp; strategi rekomendasi</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap">
                    <i class="fa-brands fa-whatsapp"></i> Broadcast WA
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel">
                    <i class="fa-solid fa-file-excel"></i> Import Excel Data
                </button>
                <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" style="background-color: #6366f1;" id="btnExportMatriksCsv">
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
            <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Tren Segmen Produk
            </a>
            <a href="<?= base_url('rfm/matriks') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm" style="color: #2D6A4F !important;">
                &#9679; 6. Matriks Aturan RFM
            </a>
        </div>
    </div>
</div>

<!-- 4 STAT CARDS (PERSIS STANDAR SISTEM CRM/RFM - 2x2 Grid di Mobile) -->
<div class="row g-2 g-md-3 mb-4">
    <!-- Card 1: TOTAL ATURAN RFM -->
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-matriks-card" style="border-radius: 12px; border-left: 4px solid #2D6A4F !important;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="stat-icon-box" style="background-color: #e8f5e9; color: #2D6A4F;">
                    <i class="fa-solid fa-table-cells"></i>
                </div>
                <div class="text-muted small fw-semibold text-uppercase text-truncate stat-title">
                    TOTAL ATURAN RFM
                </div>
            </div>
            <div class="stat-val text-dark font-monospace text-nowrap text-truncate mb-1">
                125 Aturan
            </div>
            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">
                Kombinasi 5 &times; 5 &times; 5 Lengkap
            </div>
        </div>
    </div>

    <!-- Card 2: KATEGORI SEGMEN -->
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-matriks-card" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="stat-icon-box" style="background-color: #ecfdf5; color: #047857;">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="text-muted small fw-semibold text-uppercase text-truncate stat-title">
                    KATEGORI SEGMEN
                </div>
            </div>
            <div class="stat-val text-success font-monospace text-nowrap text-truncate mb-1">
                10 Segmen
            </div>
            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">
                Champions s/d Hibernating
            </div>
        </div>
    </div>

    <!-- Card 3: PELANGGAN TERPETAKAN -->
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-matriks-card" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="stat-icon-box" style="background-color: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="text-muted small fw-semibold text-uppercase text-truncate stat-title">
                    PELANGGAN TERPETAKAN
                </div>
            </div>
            <div class="stat-val text-dark font-monospace text-nowrap text-truncate mb-1">
                <?= number_format($totalPelanggan ?? 0, 0, ',', '.') ?> Org
            </div>
            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">
                <?= (int)($aturanTerisi ?? 0) ?> Aturan aktif terisi
            </div>
        </div>
    </div>

    <!-- Card 4: STANDAR ACUAN EXCEL -->
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100 stat-matriks-card cursor-pointer" style="border-radius: 12px; border-left: 4px solid #6366f1 !important; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel" title="Klik untuk sinkronisasi template Excel RFM">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-box" style="background-color: #eef2ff; color: #4f46e5;">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate stat-title">
                        STANDAR ACUAN
                    </div>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary small px-1.5 py-0.5">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </span>
            </div>
            <div class="stat-val text-dark text-nowrap text-truncate mb-1" style="font-size: clamp(0.92rem, 2.6vw, 1.25rem) !important;">
                rfm magang.xlsx
            </div>
            <div class="text-primary small text-truncate fw-semibold" style="font-size: 0.75rem;">
                <i class="fa-solid fa-rotate me-1"></i>Klik sinkronisasi aturan
            </div>
        </div>
    </div>
</div>

<!-- SEGMENT PILL QUICK FILTERS CARD (10 SEGMEN LENGKAP & DINAMIS) -->
<div class="card border-0 shadow-sm mb-4 matriks-animated-card" style="border-radius: 12px;">
    <div class="card-body p-2.5 p-md-3">
        <div class="d-flex align-items-center gap-2 crm-subnav-scroll flex-nowrap flex-md-wrap">
            <span class="text-muted fw-bold me-1 text-uppercase d-flex align-items-center flex-shrink-0" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                <i class="fa-solid fa-filter me-1 text-success"></i>Filter:
            </span>
            <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold filter-btn" data-segment="all">
                Semua (125)
            </button>
            <?php 
                $segmentsOrder = [
                    'Champions', 'Loyal Customers', 'Potential Loyalist', 'New Customers',
                    'Big Spenders', 'Frequent Low Value', 'Need Attention', 'At Risk Customers',
                    "Can't Lose Them", 'Hibernating'
                ];
                foreach ($segmentsOrder as $segItem):
                    $cntRules = $segmentRulesCount[$segItem] ?? 0;
            ?>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold filter-btn" data-segment="<?= esc($segItem) ?>">
                    &bull; <?= esc($segItem) ?> (<?= $cntRules ?>)
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- 125 RULES TABLE & MOBILE CARDS -->
<div class="card border-0 shadow-sm matriks-animated-card" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-ol text-primary"></i> Daftar Aturan Kombinasi Matriks RFM
            </h6>
            <span class="text-muted small" style="font-size: 0.75rem;">Menampilkan <?= count($rules) ?> dari total 125 kombinasi aturan segmentasi</span>
        </div>
        <div class="d-flex gap-2 w-100 w-sm-auto">
            <input type="text" id="filterMatriksInput" class="form-control form-control-sm w-100 w-sm-auto" placeholder="Cari cepat (555, Champions...)" style="min-width: 220px;">
        </div>
    </div>
    <div class="card-body p-0">
        <!-- TAMPILAN TABEL PADA DESKTOP & TABLET (d-none d-md-block) -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0" id="tableMatriksRfm">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">#</th>
                        <th class="text-center">RECENCY (R)</th>
                        <th class="text-center">FREQ (F)</th>
                        <th class="text-center">MONET (M)</th>
                        <th class="text-center">SKOR RFM</th>
                        <th>SEGMEN</th>
                        <th>TARGET PERILAKU (GOALS)</th>
                        <th>STRATEGI PROMO &amp; CRM (ACTIONS)</th>
                        <th class="pe-3 text-end">PELANGGAN SAAT INI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rules as $r): ?>
                        <?php 
                            $seg = $r['segment'];
                            $style = match($seg) {
                                'Champions'          => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                                'Loyal Customers'    => 'background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;',
                                'Potential Loyalist' => 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                                'New Customers'      => 'background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd;',
                                'Big Spenders'       => 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;',
                                'Frequent Low Value' => 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;',
                                'Need Attention'     => 'background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;',
                                'At Risk Customers'  => 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
                                "Can't Lose Them"    => 'background-color: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8;',
                                default              => 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;',
                            };
                            $icon = match($seg) {
                                'Champions'          => 'fa-trophy',
                                'Loyal Customers'    => 'fa-gem',
                                'Potential Loyalist' => 'fa-star',
                                'New Customers'      => 'fa-seedling',
                                'Big Spenders'       => 'fa-sack-dollar',
                                'Frequent Low Value' => 'fa-bag-shopping',
                                'Need Attention'     => 'fa-bell',
                                'At Risk Customers'  => 'fa-triangle-exclamation',
                                "Can't Lose Them"    => 'fa-heart-crack',
                                default              => 'fa-bed',
                            };
                        ?>
                        <tr class="rule-row" data-segment="<?= esc($r['segment']) ?>" data-score="<?= esc($r['score']) ?>">
                            <td class="ps-3 text-muted small"><?= $r['id'] ?></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><?= $r['r'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info fw-bold px-2 py-1"><?= $r['f'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning bg-opacity-10 text-warning fw-bold px-2 py-1"><?= $r['m'] ?></span>
                            </td>
                            <td class="text-center font-monospace fw-bold text-dark">
                                <?= $r['score'] ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="<?= $style ?>">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= esc($seg) ?>
                                </span>
                            </td>
                            <td class="small text-secondary" style="max-width: 250px; white-space: normal;">
                                <?= esc($r['goal']) ?>
                            </td>
                            <td class="small text-muted" style="max-width: 320px; white-space: normal;">
                                <i class="fa-solid fa-bullhorn text-warning me-1"></i><?= esc($r['action']) ?>
                            </td>
                            <td class="pe-3 text-end">
                                <?php if ($r['count'] > 0): ?>
                                    <span class="badge rounded-pill bg-primary px-2 py-1 fw-bold">
                                        <i class="fa-solid fa-users me-1"></i><?= $r['count'] ?> Orang
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">0 Orang</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- TAMPILAN CARD PADA LAYAR MOBILE (d-block d-md-none) -->
        <div class="d-block d-md-none p-2.5 p-sm-3" id="mobileCardsContainer">
            <div class="row g-2.5">
                <?php foreach ($rules as $r): ?>
                    <?php 
                        $seg = $r['segment'];
                        $style = match($seg) {
                            'Champions'          => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                            'Loyal Customers'    => 'background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;',
                            'Potential Loyalist' => 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                            'New Customers'      => 'background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd;',
                            'Big Spenders'       => 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;',
                            'Frequent Low Value' => 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;',
                            'Need Attention'     => 'background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;',
                            'At Risk Customers'  => 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
                            "Can't Lose Them"    => 'background-color: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8;',
                            default              => 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;',
                        };
                        $icon = match($seg) {
                            'Champions'          => 'fa-trophy',
                            'Loyal Customers'    => 'fa-gem',
                            'Potential Loyalist' => 'fa-star',
                            'New Customers'      => 'fa-seedling',
                            'Big Spenders'       => 'fa-sack-dollar',
                            'Frequent Low Value' => 'fa-bag-shopping',
                            'Need Attention'     => 'fa-bell',
                            'At Risk Customers'  => 'fa-triangle-exclamation',
                            "Can't Lose Them"    => 'fa-heart-crack',
                            default              => 'fa-bed',
                        };
                    ?>
                    <div class="col-12 rule-card-item" data-segment="<?= esc($r['segment']) ?>" data-score="<?= esc($r['score']) ?>">
                        <div class="card border shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                            <!-- Header Baris: #ID, Skor RFM & Jumlah Pelanggan -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="badge bg-light text-muted border font-monospace">#<?= $r['id'] ?></span>
                                    <span class="fw-bold font-monospace text-dark fs-6 bg-light px-2 py-0.5 rounded border">
                                        Skor <?= $r['score'] ?>
                                    </span>
                                </div>
                                <div>
                                    <?php if ($r['count'] > 0): ?>
                                        <span class="badge rounded-pill bg-primary px-2.5 py-1 fw-bold">
                                            <i class="fa-solid fa-users me-1"></i><?= $r['count'] ?> Orang
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-0.5 small">0 Orang</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Baris Skor R, F, M & Segmen Badge -->
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-1 font-monospace" style="font-size: 0.76rem;">
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">R: <?= $r['r'] ?></span>
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold px-2 py-1">F: <?= $r['f'] ?></span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning fw-bold px-2 py-1">M: <?= $r['m'] ?></span>
                                </div>
                                <span class="badge rounded-pill px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="<?= $style ?> font-size: 0.78rem;">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= esc($seg) ?>
                                </span>
                            </div>

                            <!-- Target Perilaku (Goals) -->
                            <div class="mb-2">
                                <div class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.04em;">
                                    Target Perilaku (Goals):
                                </div>
                                <div class="text-secondary small" style="line-height: 1.4; font-size: 0.78rem;">
                                    <?= esc($r['goal']) ?>
                                </div>
                            </div>

                            <!-- Strategi Promo & CRM (Actions) -->
                            <div>
                                <div class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.04em;">
                                    Strategi Promo &amp; CRM:
                                </div>
                                <div class="text-dark small fw-medium" style="line-height: 1.4; font-size: 0.78rem;">
                                    <i class="fa-solid fa-bullhorn text-warning me-1"></i><?= esc($r['action']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Empty State jika tidak ada aturan yang cocok dengan filter -->
        <div id="noRulesFound" class="text-center py-4 text-muted small" style="display: none;">
            <i class="fa-solid fa-circle-question fs-3 text-secondary mb-2 d-block"></i>
            Tidak ada kombinasi aturan RFM yang cocok dengan kata kunci atau filter yang dipilih.
        </div>
    </div>
</div>

<?php include __DIR__ . '/modal_import_excel.php'; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('filterMatriksInput');
    const rows = document.querySelectorAll('.rule-row');
    const cards = document.querySelectorAll('.rule-card-item');
    const noRulesMsg = document.getElementById('noRulesFound');

    function filterItems() {
        const query = input ? input.value.toLowerCase().trim() : '';
        const activeBtn = document.querySelector('.filter-btn.btn-primary');
        const seg = activeBtn ? activeBtn.getAttribute('data-segment') : 'all';

        let visibleCount = 0;

        const matchItem = (el) => {
            const segMatch = (seg === 'all' || el.getAttribute('data-segment') === seg);
            const txtMatch = !query || el.textContent.toLowerCase().includes(query);
            const isVisible = (segMatch && txtMatch);
            el.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        };

        rows.forEach(matchItem);
        cards.forEach(matchItem);

        if (noRulesMsg) {
            noRulesMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    if (input) {
        input.addEventListener('keyup', filterItems);
        input.addEventListener('input', filterItems);
        input.addEventListener('search', filterItems);
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
            filterItems();
        });
    });

    // Export CSV of 125 rules
    const btnExport = document.getElementById('btnExportMatriksCsv');
    if (btnExport) {
        btnExport.addEventListener('click', function() {
            let csv = "ID,Recency (R),Frequency (F),Monetary (M),Skor RFM,Segmen,Target Perilaku (Goals),Strategi Promo (Actions),Jumlah Pelanggan\n";
            rows.forEach(r => {
                const cols = r.querySelectorAll('td');
                if (cols.length >= 9) {
                    const rowData = [];
                    cols.forEach(c => {
                        rowData.push('"' + c.textContent.trim().replace(/"/g, '""').replace(/\s+/g, ' ') + '"');
                    });
                    csv += rowData.join(',') + "\n";
                }
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", "Matriks_Aturan_RFM_125_" + new Date().toISOString().slice(0,10) + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
});
</script>
<?= $this->endSection() ?>
