<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .card {
        border-radius: 12px;
    }
    /* Mobile responsive optimizations for CRM Dashboard */
    .crm-stat-card {
        border-radius: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .crm-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07) !important;
    }
    .crm-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .crm-stat-val {
        font-size: clamp(0.95rem, 3.2vw, 1.35rem);
        line-height: 1.2;
    }
    .crm-stat-label {
        font-size: 0.68rem;
        letter-spacing: 0.04em;
        line-height: 1.2;
    }
    /* Tombol Aksi Responsive: Teks saat lebar, Ikon saja saat mobile */
    .col-aksi-btn {
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
        border-radius: 6px !important;
        padding: 0.35rem 0.65rem !important;
        margin: 0 auto !important;
        transition: all 0.15s ease-in-out;
    }
    .col-aksi-btn i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: 0 !important;
        line-height: 1 !important;
        margin: 0 !important;
        text-align: center !important;
        font-size: 0.85rem !important;
    }
    .col-aksi-btn .btn-text {
        display: inline-block;
        line-height: 1;
        vertical-align: middle;
    }
    @media (max-width: 767.98px) {
        .col-aksi-btn .btn-text {
            display: none !important;
        }
        .col-aksi-btn {
            width: 34px !important;
            height: 34px !important;
            min-width: 34px !important;
            min-height: 34px !important;
            max-width: 34px !important;
            max-height: 34px !important;
            padding: 0 !important;
            border-radius: 8px !important;
            gap: 0 !important;
            margin: 0 auto !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .col-aksi-btn i {
            margin: 0 !important;
            font-size: 0.95rem !important;
            line-height: 1 !important;
            vertical-align: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
        }
    }
    /* Horizontal smooth scroll for sub-navigation tabs on mobile */
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
    @media (max-width: 575.98px) {
        .crm-top-banner {
            padding: 1rem !important;
        }
        .crm-stat-card {
            padding: 0.65rem 0.75rem !important;
        }
        .crm-stat-icon {
            width: 34px;
            height: 34px;
        }
        .crm-stat-icon i {
            font-size: 0.95rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP CRM BANNER -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1">
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; Dashboard Utama</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-chart-pie me-2"></i>Dashboard Performa CRM &amp; After Sales
                </h3>
                <p class="text-white-50 small mb-0">Pantau keterlibatan pelanggan, tren pengiriman follow-up, repeat order rate, dan performa tim CS secara real-time.</p>
            </div>
            <div class="d-flex flex-nowrap align-items-center justify-content-end gap-2 w-100 w-md-auto text-nowrap">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-success btn-sm text-white flex-fill flex-md-grow-0 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm">
                    <i class="fa-brands fa-whatsapp"></i> Broadcast WA
                </a>
                <a href="<?= base_url('rfm') ?>" class="btn btn-light btn-sm text-primary flex-fill flex-md-grow-0 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm">
                    <i class="fa-solid fa-chart-column"></i> Analisis RFM
                </a>
            </div>
        </div>

        <!-- SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('crm/dashboard') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 1. Dashboard CRM
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
            <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Kinerja Tim CS
            </a>
            <a href="<?= base_url('crm/kelompok') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Kelompok Follow-Up
            </a>
        </div>
    </div>
</div>

<!-- 5 STAT CARDS ROW (Responsive Grid: 2x2+1 on mobile, 3+2 on tablet, 5 equal on desktop) -->
<div class="row g-2 g-sm-3 mb-4">
    <!-- 1. Total Pelanggan -->
    <div class="col-6 col-md-4 col-xl">
        <div class="card h-100 crm-stat-card shadow-sm p-2 p-sm-3" style="border-left: 4px solid #2563eb !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="crm-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-users fs-5"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="crm-stat-val fw-bold text-dark font-monospace text-nowrap"><?= number_format((int)$totalPelanggan, 0, ',', '.') ?></div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate crm-stat-label">TOTAL PELANGGAN</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Total Volume Transaksi -->
    <div class="col-6 col-md-4 col-xl">
        <div class="card h-100 crm-stat-card shadow-sm p-2 p-sm-3" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="crm-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-wallet fs-5"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="crm-stat-val fw-bold text-dark font-monospace text-nowrap d-flex align-items-baseline" title="<?= format_rupiah($totalVolumeTransaksi) ?>">
                        <span class="small fw-semibold text-muted me-1" style="font-size: 0.75em;">Rp</span><span><?= number_format((float) ($totalVolumeTransaksi ?? 0), 0, ',', '.') ?></span>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate crm-stat-label">TOTAL TRANSAKSI</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Repeat Order Rate -->
    <div class="col-6 col-md-4 col-xl">
        <div class="card h-100 crm-stat-card shadow-sm p-2 p-sm-3" style="border-left: 4px solid #9333ea !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="crm-stat-icon text-purple" style="background-color: #f3e8ff; color: #9333ea;">
                    <i class="fa-solid fa-rotate fs-5"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="crm-stat-val fw-bold text-dark font-monospace text-nowrap"><?= esc($repeatRate) ?>%</div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate crm-stat-label">REPEAT ORDER</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Pending Hari Ini -->
    <div class="col-6 col-md-6 col-xl">
        <div class="card h-100 crm-stat-card shadow-sm p-2 p-sm-3" style="border-left: 4px solid #f59e0b !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="crm-stat-icon" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fa-solid fa-clock fs-5"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span class="crm-stat-val fw-bold text-dark font-monospace"><?= esc($pendingToday) ?></span>
                        <?php if ($overdueCount > 0): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-semibold px-1 py-0.5" style="font-size: 0.65rem;" title="<?= esc($overdueCount) ?> tugas lewat jatuh tempo (Overdue)">
                                <i class="fa-solid fa-triangle-exclamation me-0.5"></i><?= esc($overdueCount) ?> Overdue
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate crm-stat-label">PENDING HARI INI</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. WA Broadcast Terkirim -->
    <div class="col-12 col-md-6 col-xl">
        <div class="card h-100 crm-stat-card shadow-sm p-2 p-sm-3" style="border-left: 4px solid #ef4444 !important;">
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="crm-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-paper-plane fs-5"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="crm-stat-val fw-bold text-dark font-monospace"><?= esc($broadcastTerkirim) ?></div>
                        <a href="<?= base_url('crm/broadcast') ?>" class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle text-decoration-none px-2 py-1 small fw-semibold d-inline-flex align-items-center gap-1 d-md-none">
                            Broadcast WA <i class="fa-solid fa-arrow-right" style="font-size: 0.65rem;"></i>
                        </a>
                    </div>
                    <div class="text-muted small fw-semibold text-uppercase text-truncate crm-stat-label">WA BROADCAST TERKIRIM</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ANALISIS RFM STRIP -->
<div class="card border-0 mb-4 shadow-sm" style="background-color: #ede9fe; border-radius: 12px; border-left: 4px solid #6366f1 !important;">
    <div class="card-body p-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            <div class="rounded text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="background-color: #6366f1; width: 42px; height: 42px; border-radius: 10px;">
                <i class="fa-solid fa-chart-simple fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-0">Analisis RFM Pelanggan</h6>
                <span class="text-secondary small">Segmentasi Recency, Frequency, &amp; Monetary di modul terpisah</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
            <a href="<?= base_url('rfm') ?>" class="btn btn-primary btn-sm flex-fill flex-md-grow-0 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm py-1.5 px-3">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard RFM</span>
            </a>
            <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-outline-secondary btn-sm bg-white flex-fill flex-md-grow-0 d-inline-flex align-items-center justify-content-center gap-1 py-1.5 px-3">
                <i class="fa-solid fa-users"></i>
                <span>Daftar Customer RFM</span>
            </a>
        </div>
    </div>
</div>

<!-- CHARTS ROW 1 -->
<div class="row g-3 mb-4">
    <!-- Trend Chart -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Tugas Follow-Up vs WA Broadcast Terkirim</span>
                <span class="badge bg-light text-muted border">15 Hari Terakhir</span>
            </div>
            <div class="card-body p-2 p-sm-3">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer per Channel Donut -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-chart-pie text-success me-2"></i>Customer per Channel</span>
            </div>
            <div class="card-body p-2 p-sm-3 d-flex align-items-center justify-content-center">
                <div style="position: relative; height: 230px; width: 100%;">
                    <canvas id="channelChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS & TABLES ROW 2 -->
<div class="row g-3 mb-4">
    <!-- Status Follow-Up per Kategori Stacked Bar -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-bars-progress text-warning me-2"></i>Status Follow-Up per Kategori H+</span>
            </div>
            <div class="card-body p-2 p-sm-3">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="stackedBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Pelanggan Repeat Order -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-trophy text-warning me-2"></i>Top 5 Pelanggan Repeat Order</span>
                <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-link btn-sm text-decoration-none p-0">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th>PELANGGAN</th>
                            <th>SUMBER</th>
                            <th class="text-center">TOTAL ORDER</th>
                            <th>ORDER TERAKHIR</th>
                            <th class="text-center col-aksi col-aksi-th">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topCustomers)): ?>
                            <?php foreach ($topCustomers as $tc): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary fw-bold small" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <?= strtoupper(substr($tc['nama_pelanggan'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark small"><?= esc($tc['nama_pelanggan']) ?></div>
                                                <?php 
                                                    $rawTc = preg_replace('/\D/', '', (string)($tc['no_telp'] ?? ''));
                                                    $tcWa = '';
                                                    if (!empty($rawTc)) {
                                                        if (str_starts_with($rawTc, '62')) $tcWa = $rawTc;
                                                        elseif (str_starts_with($rawTc, '0')) $tcWa = '62' . substr($rawTc, 1);
                                                        elseif (str_starts_with($rawTc, '8')) $tcWa = '62' . $rawTc;
                                                        else $tcWa = '62' . $rawTc;
                                                    }
                                                ?>
                                                <?php if (!empty($tcWa)): ?>
                                                    <a href="https://wa.me/<?= $tcWa ?>" target="_blank" class="text-decoration-none small text-muted font-monospace d-inline-flex align-items-center gap-1" title="Chat WhatsApp">
                                                        <i class="fa-brands fa-whatsapp text-success"></i> <?= esc($tc['no_telp']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <small class="text-muted font-monospace"><?= esc($tc['no_telp'] ?? '-') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border small">Shopee, Toko</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-2 py-1 small"><?= esc($tc['total_order']) ?>x Order</span>
                                    </td>
                                    <td class="small text-secondary font-monospace"><?= date('d/m/Y', strtotime($tc['last_order'])) ?></td>
                                    <td class="text-center col-aksi col-aksi-th align-middle">
                                        <a href="<?= base_url('master/pelanggan/edit/' . $tc['id']) ?>" class="btn btn-primary btn-sm rounded col-aksi-btn shadow-xs" title="Detail Pelanggan">
                                            <i class="fa-solid fa-eye"></i><span class="btn-text ms-1">Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4 small">
                                    Belum ada data pelanggan repeat order.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ROW 3: URGENT CS TASKS & RECENT ACTIVITY -->
<div class="row g-3 mb-4">
    <!-- Membutuhkan Perhatian CS Segera -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-start border-4 border-danger">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Membutuhkan Perhatian CS Segera</span>
                <span class="badge bg-danger rounded-pill px-2 py-1"><?= count($urgentTasks) ?> Item</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th>Customer</th>
                            <th>Kategori</th>
                            <th class="text-center">Target Tgl</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($urgentTasks)): ?>
                            <?php foreach ($urgentTasks as $ut): ?>
                                <?php 
                                    $rawDigits = preg_replace('/\D/', '', (string)($ut['customer_phone'] ?: ($ut['no_telp'] ?? '')));
                                    $waPhone = '';
                                    if (!empty($rawDigits)) {
                                        if (str_starts_with($rawDigits, '62')) {
                                            $waPhone = $rawDigits;
                                        } elseif (str_starts_with($rawDigits, '0')) {
                                            $waPhone = '62' . substr($rawDigits, 1);
                                        } elseif (str_starts_with($rawDigits, '8')) {
                                            $waPhone = '62' . $rawDigits;
                                        } else {
                                            $waPhone = '62' . $rawDigits;
                                        }
                                    }

                                    $cName = $ut['customer_nama'] ?: ($ut['nama_pelanggan'] ?? 'Pelanggan');
                                    $soNum = $ut['nomor_so'] ?? $ut['nomor_resi'] ?? (string)$ut['order_id'];
                                    $label = $ut['label_code'] ?? 'H+0';

                                    $msg = match($label) {
                                        'H+0' => "Halo kak {$cName}, terima kasih telah berbelanja di Metafora! Pesanan Anda #{$soNum} sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!",
                                        'H+3' => "Halo kak {$cName}, bagaimana kabar paket produk pesanan Anda (#{$soNum})? Semoga sudah sampai dengan selamat dan sesuai harapan ya. Jika butuh panduan, silakan balas chat ini ya kak!",
                                        'H+7' => "Halo kak {$cName}, terima kasih telah mempercayai Metafora untuk pesanan #{$soNum}! Jangan lupa berikan ulasan terbaik Anda. Gunakan kode promo REPEAT10 untuk diskon belanja berikutnya ya!",
                                        default => "Halo kak {$cName}, terima kasih telah berbelanja di Metafora! Follow-up {$label} untuk pesanan #{$soNum}.",
                                    };

                                    $waLink = !empty($waPhone) ? "https://wa.me/{$waPhone}?text=" . rawurlencode($msg) : "javascript:alert('Nomor HP tidak valid')";
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark small"><?= esc($ut['customer_nama']) ?></div>
                                        <?php if (!empty($waPhone)): ?>
                                            <a href="<?= $waLink ?>" target="_blank" onclick="markDashboardSent(<?= $ut['id'] ?>, this)" class="text-decoration-none small text-muted font-monospace d-inline-flex align-items-center gap-1" title="Klik untuk chat WhatsApp">
                                                <i class="fa-brands fa-whatsapp text-success"></i> <?= esc($ut['customer_phone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <small class="text-muted font-monospace"><?= esc($ut['customer_phone']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle small"><?= esc($ut['label_code'] ?? 'Follow-Up') ?></span>
                                    </td>
                                    <td class="text-center small text-secondary font-monospace"><?= date('d/m/Y', strtotime($ut['target_date'])) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill badge-status-cell">Pending</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $waLink ?>" target="_blank" onclick="markDashboardSent(<?= $ut['id'] ?>, this)" class="btn btn-sm btn-warning text-white p-0 d-inline-flex align-items-center justify-content-center rounded shadow-sm btn-wa-action" style="width: 32px; height: 32px;" title="Kirim Follow-Up WhatsApp ke <?= esc($waPhone) ?>">
                                            <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4 small">
                                    Tidak ada tugas follow-up mendesak saat ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aktivitas CS & Broadcast Terbaru -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-bold small text-dark"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i>Aktivitas CS &amp; Broadcast Terbaru</span>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-3">
                    <?php if (!empty($recentLogs)): ?>
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="p-2 rounded bg-light border-start border-3 border-success small">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-semibold text-dark"><i class="fa-brands fa-whatsapp text-success me-1"></i>Kirim WA ke <?= esc($log['customer_nama'] ?? 'Pelanggan') ?></span>
                                    <span class="text-muted font-monospace" style="font-size: 0.78rem;"><?= !empty($log['sent_at']) ? date('d/m H:i', strtotime($log['sent_at'])) : '-' ?></span>
                                </div>
                                <div class="text-muted small text-truncate"><?= esc($log['message_log'] ?? 'Pesan WhatsApp berhasil terkirim.') ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4 small">
                            <i class="fa-brands fa-whatsapp text-muted fs-4 d-block mb-2"></i>
                            Belum ada riwayat pengiriman pesan WhatsApp.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // 1. Line Chart Trend
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: <?= json_encode($trendLabels ?? []) ?>,
                datasets: [
                    {
                        label: 'Target Follow-Up Label',
                        data: <?= json_encode($trendTarget ?? []) ?>,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'WA Broadcast Terkirim',
                        data: <?= json_encode($trendSent ?? []) ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 2. Donut Chart Channel
        const ctxChannel = document.getElementById('channelChart').getContext('2d');
        new Chart(ctxChannel, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($channelLabels ?? ['Toko / POS']) ?>,
                datasets: [{
                    data: <?= json_encode($channelData ?? [0]) ?>,
                    backgroundColor: ['#2D6A4F', '#ea580c', '#10b981', '#18181b', '#6366f1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // 3. Stacked Bar Chart Status
        const ctxBar = document.getElementById('stackedBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: <?= json_encode($stageLabels ?? ['H+0', 'H+3', 'H+7', 'H+11', 'H+30', 'H+100']) ?>,
                datasets: [
                    { label: 'Pending', data: <?= json_encode($stagePending ?? [0,0,0,0,0,0]) ?>, backgroundColor: '#f59e0b' },
                    { label: 'Selesai / Terkirim', data: <?= json_encode($stageSent ?? [0,0,0,0,0,0]) ?>, backgroundColor: '#2D6A4F' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { x: { stacked: true }, y: { stacked: true } },
                plugins: { legend: { position: 'top' } }
            }
        });
    });

    function markDashboardSent(id, el) {
        if (!id) return;
        $.post('<?= base_url('crm/mark-sent') ?>/' + id, function() {
            if (el) {
                const row = $(el).closest('tr');
                row.find('.badge-status-cell')
                   .removeClass('bg-danger-subtle text-danger border-danger-subtle')
                   .addClass('bg-success-subtle text-success border-success-subtle')
                   .text('Selesai');
                row.find('.btn-wa-action')
                   .removeClass('btn-warning')
                   .addClass('btn-success')
                   .attr('title', 'Terkirim (Klik untuk chat WA lagi)');
            }
        });
    }
</script>
<?= $this->endSection() ?>

