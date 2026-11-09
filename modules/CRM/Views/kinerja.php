<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$curStartDate = !empty($startDate) ? $startDate : (service('request')->getGet('start_date') ?: date('Y-m-01'));
$curEndDate   = !empty($endDate) ? $endDate : (service('request')->getGet('end_date') ?: date('Y-m-d'));
$selectedUserId = !empty($selectedUserId) ? (int)$selectedUserId : (int)(service('request')->getGet('user_id') ?: 0);
$selectedUserName = '';
if (!empty($selectedUserId) && !empty($allCsUsers)) {
    foreach ($allCsUsers as $cu) {
        if ($cu['id'] == $selectedUserId) {
            $selectedUserName = $cu['username'];
            break;
        }
    }
}
?>
<style>
    /* Mode Layar Biasa */
    .print-only-header,
    .print-only-footer {
        display: none;
    }

    /* Mobile Responsive Optimizations */
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
    .kinerja-stat-card {
        border-radius: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        padding: 0.85rem 0.65rem 0.85rem 0.95rem !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 88px;
        position: relative;
        overflow: hidden;
    }
    .kinerja-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }
    .kinerja-stat-num {
        font-size: clamp(1.15rem, 3.4vw, 1.85rem) !important;
        line-height: 1.2;
        margin-bottom: 0.15rem;
    }
    .kinerja-stat-omset {
        font-size: clamp(0.85rem, 2.7vw, 1.55rem) !important;
    }
    .kinerja-stat-unit {
        font-size: 0.72em;
        font-weight: 600;
        color: #64748b;
        font-family: var(--bs-body-font-family, system-ui, -apple-system, sans-serif);
    }
    .kinerja-stat-label {
        font-size: clamp(0.62rem, 1.8vw, 0.72rem) !important;
        line-height: 1.25 !important;
        letter-spacing: 0.025em;
        color: #64748b !important;
        margin-top: 0.15rem;
        word-break: normal;
    }
    @media (min-width: 768px) {
        .kinerja-stat-card {
            padding: 1.15rem 1rem 1.15rem 1.25rem !important;
            min-height: 104px;
        }
        .kinerja-stat-omset {
            font-size: clamp(1.1rem, 2vw, 1.65rem) !important;
        }
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
    }

    /* Mode Cetak / Ekspor PDF */
    @media print {
        @page {
            size: landscape;
            margin: 8mm 10mm;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 8.5pt !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hilangkan elemen navigasi web, sidebar, tombol, filter & banner */
        #sidebar,
        .topbar,
        .top-navbar,
        .sidebar-backdrop,
        .app-footer,
        .breadcrumb,
        .toast-container,
        .alert,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history,
        .d-print-none,
        .btn {
            display: none !important;
        }

        /* Reset container layout agar tidak ada margin kiri */
        #main-content,
        .content-wrapper,
        .container-fluid,
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            left: 0 !important;
            transform: none !important;
            position: static !important;
        }

        .card {
            box-shadow: none !important;
            margin-bottom: 0 !important;
        }

        /* Kop Laporan Header */
        .print-only-header {
            display: block !important;
            margin-bottom: 5mm !important;
            padding-bottom: 3mm !important;
            border-bottom: 2px solid #1A2E23 !important;
        }

        /* Sembunyikan Stat Cards & Charts di Mode Cetak PDF */
        .print-stat-row,
        .print-charts-row,
        .chart-container-bar,
        .chart-container-donut,
        .print-chart-badge {
            display: none !important;
        }

        /* Leaderboard Table di Mode Cetak */
        .print-leaderboard-card {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            box-shadow: none !important;
            page-break-inside: auto !important;
        }
        .print-leaderboard-card .card-header {
            display: none !important;
        }
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .print-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0 !important;
            font-size: 8.5pt !important;
        }
        .print-table th,
        .print-table td {
            border: 1px solid #cbd5e1 !important;
            padding: 6px 10px !important;
            vertical-align: middle !important;
        }
        .print-table thead th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            font-size: 8pt !important;
            letter-spacing: 0.04em !important;
        }
        .print-table tr {
            page-break-inside: avoid !important;
        }
        /* Hilangkan highlight warna baris akun Anda saat dicetak */
        .print-table tr.table-primary,
        .print-table tr.table-primary td {
            background-color: transparent !important;
            color: inherit !important;
        }
        /* Murni data saja: hilangkan tampilan pill/badge saat dicetak */
        .print-table .badge {
            border: none !important;
            background: transparent !important;
            color: inherit !important;
            padding: 0 !important;
            font-size: inherit !important;
            font-weight: 600 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        /* Footer khusus cetak */
        .print-only-footer {
            display: block !important;
        }

        a[href]:after {
            content: none !important;
        }
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
    }
</style>

<!-- KOP LAPORAN KHUSUS CETAK PDF -->
<div class="print-only-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1A2E23; font-size: 14pt; letter-spacing: -0.02em;">
                <i class="fa-solid fa-chart-line me-2"></i>LAPORAN PERFORMA TIM CS
            </h4>
            <div style="font-size: 8.5pt; color: #4B6354;">
                <span>Sistem CRM &amp; After Sales &mdash; IDMETAFORA Enterprise</span> &bull; 
                <span>Periode: <strong><?= date('d M Y', strtotime($curStartDate)) ?> &ndash; <?= date('d M Y', strtotime($curEndDate)) ?></strong></span>
                <?php if (!empty($selectedUserName)): ?>
                    &bull; <span>Filter Akun: <strong class="text-success"><?= esc($selectedUserName) ?></strong></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-end" style="font-size: 8pt; color: #64748b;">
            <div><i class="fa-solid fa-calendar-day me-1"></i>Tanggal Cetak: <strong><?= date('d/m/Y H:i') ?> WIB</strong></div>
            <div><i class="fa-solid fa-users me-1"></i>Tim CS Terdaftar: <strong><?= esc($csAktif) ?> Petugas</strong></div>
        </div>
    </div>
</div>

<!-- TOP BANNER CRM -->
<div class="card border-0 text-white mb-4 shadow-sm d-print-none" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; Performa CS</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-chart-line me-2"></i>Performa Tim CS
                </h3>
                <p class="text-white-50 small mb-0">Pantau jumlah handling follow-up, broadcast WA terkirim, respon positif customer, serta omset repeat order per CS</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/kinerja/export') . '?start_date=' . urlencode($curStartDate) . '&end_date=' . urlencode($curEndDate) . ($selectedUserId ? '&user_id=' . $selectedUserId : '') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" title="Export CSV">
                    <i class="fa-solid fa-file-csv"></i> <span>Export CSV</span>
                </a>
                <button type="button" class="btn btn-sm btn-outline-light fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" onclick="window.print()" title="Export PDF">
                    <i class="fa-solid fa-file-pdf"></i> <span>Export PDF</span>
                </button>
                <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-light fw-semibold text-primary d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" title="Journal Follow-Up">
                    <i class="fa-solid fa-book-open"></i> <span>Journal Follow-Up</span>
                </a>
            </div>
        </div>

        <!-- 7 SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('crm/dashboard') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard CRM
            </a>
            <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                2. Journal Follow-Up
            </a>
            <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                <i class="fa-brands fa-whatsapp me-1"></i>3. Broadcast WA
            </a>
            <a href="<?= base_url('crm/directory') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Customer Directory
            </a>
            <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 5. Kinerja Tim CS
            </a>
            <a href="<?= base_url('crm/kelompok') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Kelompok Follow-Up
            </a>
        </div>
    </div>
</div>

<!-- DATE & ACCOUNT FILTER -->
<div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 12px;">
    <div class="card-body p-3">
        <form class="row g-2 align-items-center" method="get" action="<?= base_url('crm/kinerja') ?>">
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Dari Tanggal:</label>
                <input type="date" class="form-control form-control-sm" name="start_date" value="<?= esc($curStartDate) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Sampai Tanggal:</label>
                <input type="date" class="form-control form-control-sm" name="end_date" value="<?= esc($curEndDate) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Pilih Akun CS:</label>
                <select class="form-select form-select-sm" name="user_id">
                    <option value="">-- Semua Akun CS --</option>
                    <?php if (!empty($allCsUsers)): ?>
                        <?php foreach ($allCsUsers as $cu): ?>
                            <option value="<?= $cu['id'] ?>" <?= ($selectedUserId == $cu['id']) ? 'selected' : '' ?>>
                                <?= esc($cu['username']) ?> (<?= esc($cu['role_name'] ?? 'Staff') ?>)<?= ($cu['id'] == ($currentUserId ?? 0)) ? ' - Akun Anda' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i>Terapkan Filter
                </button>
                <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-light btn-sm border text-secondary px-3" title="Reset Filter">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- 4 STAT CARDS -->
<div class="row g-2 g-sm-3 mb-4 print-stat-row d-print-none">
    <div class="col-6 col-lg-3 print-stat-col">
        <div class="card border-0 shadow-sm p-3 text-center print-stat-card kinerja-stat-card h-100" style="border-radius: 12px; border-left: 4px solid #2563eb !important;">
            <div class="fw-bold text-primary font-monospace print-stat-num kinerja-stat-num">
                <?php if (!empty($selectedUserName)): ?>
                    <span class="fs-6 text-truncate d-block" title="<?= esc($selectedUserName) ?>"><?= esc($selectedUserName) ?></span>
                <?php else: ?>
                    <span><?= esc($csAktif) ?></span> <span class="kinerja-stat-unit">CS</span>
                <?php endif; ?>
            </div>
            <div class="text-muted small fw-semibold text-uppercase print-stat-label kinerja-stat-label">
                <?= !empty($selectedUserName) ? 'AKUN CS TERPILIH' : 'TIM CS AKTIF' ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 print-stat-col">
        <div class="card border-0 shadow-sm p-3 text-center print-stat-card kinerja-stat-card h-100" style="border-radius: 12px; border-left: 4px solid #06b6d4 !important;">
            <div class="fw-bold text-info font-monospace print-stat-num kinerja-stat-num"><?= esc($totalFollowupHandled) ?></div>
            <div class="text-muted small fw-semibold text-uppercase print-stat-label kinerja-stat-label">TOTAL FOLLOW-UP HANDLED</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 print-stat-col">
        <div class="card border-0 shadow-sm p-3 text-center print-stat-card kinerja-stat-card h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="fw-bold text-success font-monospace print-stat-num kinerja-stat-num kinerja-stat-omset text-nowrap d-flex align-items-baseline justify-content-center" title="Rp <?= number_format((float)$totalOmsetRepeat, 0, ',', '.') ?>">
                <span class="kinerja-stat-unit me-1">Rp</span><span><?= number_format((float)$totalOmsetRepeat, 0, ',', '.') ?></span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase print-stat-label kinerja-stat-label">TOTAL OMSET REPEAT ORDER</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 print-stat-col">
        <div class="card border-0 shadow-sm p-3 text-center print-stat-card kinerja-stat-card h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="fw-bold text-warning font-monospace print-stat-num kinerja-stat-num">
                <span><?= esc($avgConversionRate) ?></span><span class="kinerja-stat-unit">%</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase print-stat-label kinerja-stat-label">
                <?= !empty($selectedUserName) ? 'CONVERSION RATE AKUN' : 'RATA-RATA CONVERSION' ?>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4 print-charts-row d-print-none">
    <!-- Bar Chart -->
    <div class="col-12 col-lg-8 print-chart-bar-col">
        <div class="card border-0 shadow-sm h-100 print-chart-card" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-column text-primary"></i> Perbandingan Omset Repeat Order per CS (Rp)
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="chart-container-bar" style="height: 280px; position: relative;">
                    <canvas id="chartOmsetCS"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Donut Chart -->
    <div class="col-12 col-lg-4 print-chart-donut-col">
        <div class="card border-0 shadow-sm h-100 print-chart-card" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-success"></i> Distribusi Aktivitas Follow-Up
                </h6>
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <div class="chart-container-donut" style="height: 220px; width: 220px; position: relative;">
                    <canvas id="chartDistribusiCS"></canvas>
                </div>
                <div class="mt-3 text-center print-chart-badge">
                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-3 py-1">
                        <i class="fa-solid fa-circle text-success me-1" style="font-size: 8px;"></i> <?= esc($csAktif) ?> Petugas Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LEADERBOARD TABLE -->
<div class="card border-0 shadow-sm print-leaderboard-card" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-trophy text-warning"></i> Leaderboard &amp; Performa Tim CS
            </h6>
            <?php if (!empty($selectedUserName)): ?>
                <span class="badge bg-info bg-opacity-10 text-info fw-semibold px-3 py-1">
                    <i class="fa-solid fa-user me-1"></i>Filter Aktif: <?= esc($selectedUserName) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 print-table">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <th class="ps-3 py-3">CS PETUGAS</th>
                        <th class="text-center">HANDLING COUNT</th>
                        <th class="text-center">WA SENT</th>
                        <th class="text-end">OMSET REPEAT ORDER</th>
                        <th class="text-center">CONVERSION RATE</th>
                        <th class="pe-3 text-end">STATUS AKTIF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($csList)): ?>
                        <?php foreach ($csList as $idx => $cs): ?>
                            <tr class="<?= !empty($cs['is_current']) ? 'table-primary bg-opacity-10' : '' ?>">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge <?= ($idx === 0) ? 'bg-warning text-dark' : 'bg-light text-secondary border' ?> rounded-circle p-1 d-flex align-items-center justify-content-center fw-bold d-print-none" style="width: 22px; height: 22px; font-size: 10px;">
                                            <?= ($idx === 0) ? '<i class="fa-solid fa-trophy text-warning-emphasis"></i>' : ($idx + 1) ?>
                                        </span>
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0 d-print-none" style="width: 32px; height: 32px; font-size: 13px;">
                                            <?= strtoupper(substr($cs['username'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                                <span class="d-none d-print-inline text-muted fw-normal me-1"><?= ($idx + 1) ?>.</span>
                                                <span><?= esc($cs['username']) ?></span>
                                                <?php if (!empty($cs['is_current'])): ?>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1 d-print-none" style="font-size: 0.65rem;">
                                                        <i class="fa-solid fa-user-check me-1"></i>Akun Anda
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-muted small d-print-none">Role: <?= esc($cs['role']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center font-monospace fw-semibold"><?= esc($cs['handling_count']) ?></td>
                                <td class="text-center font-monospace text-success fw-semibold"><?= esc($cs['sent_count']) ?></td>
                                <td class="text-end font-monospace fw-bold text-dark">Rp <?= number_format($cs['omset_repeat'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($cs['conversion'] > 0) ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' ?> fw-semibold">
                                        <?= esc($cs['conversion']) ?>%
                                    </span>
                                </td>
                                <td class="pe-3 text-end"><span class="badge bg-success bg-opacity-10 text-success fw-bold">Aktif</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4 small">
                                Belum ada tim CS atau petugas yang terdaftar di database.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FOOTER KHUSUS CETAK PDF -->
<div class="print-only-footer">
    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 7.5pt; border-top: 1px solid #cbd5e1; padding-top: 5px; margin-top: 15px;">
        <span><i class="fa-solid fa-shield-halved me-1"></i>Dokumen ini dicetak secara otomatis dari Sistem CRM IDMETAFORA Enterprise</span>
        <span>Halaman 1 / Laporan Ringkasan Performa CS</span>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart
    const ctxBar = document.getElementById('chartOmsetCS').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($csList ?? [], 'username')) ?>,
            datasets: [{
                label: 'Omset Repeat Order (Rp)',
                data: <?= json_encode(array_column($csList ?? [], 'omset_repeat')) ?>,
                backgroundColor: '#2D6A4F',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + Number(val).toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Donut Chart
    const ctxDonut = document.getElementById('chartDistribusiCS').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($csList ?? [], 'username')) ?>,
            datasets: [{
                data: <?= json_encode(array_map(fn($c) => (int)($c['sent_count'] + $c['handling_count']), $csList ?? [])) ?>,
                backgroundColor: ['#2D6A4F', '#10b981', '#f59e0b', '#6366f1', '#ec4899', '#06b6d4'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '70%'
        }
    });
});
</script>
<?= $this->endSection() ?>

