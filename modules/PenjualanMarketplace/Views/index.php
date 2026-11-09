<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @media print {
        @page {
            size: landscape;
            margin: 8mm 6mm;
        }
        body {
            padding: 8mm 6mm !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 8pt !important;
        }
        .d-print-none,
        .btn,
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history,
        #mainMarketplaceCard {
            display: none !important;
        }
        /* Hilangkan cetakan URL otomatis di belakang link */
        a[href]:after {
            content: none !important;
        }
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
        /* TAMPILKAN TABEL LAPORAN KHUSUS SESUAI FORMAT FOTO */
        #printMarketplaceReport {
            display: block !important;
            width: 100% !important;
        }
        .table-print-report {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 7.5pt !important;
            margin-top: 5px;
        }
        .table-print-report thead {
            display: table-header-group !important;
        }
        .table-print-report tbody {
            display: table-row-group !important;
        }
        .table-print-report tr {
            display: table-row !important;
            page-break-inside: avoid !important;
        }
        .table-print-report th {
            display: table-cell !important;
            background-color: #1a44a6 !important; /* Warna biru pekat sesuai foto */
            color: #ffffff !important;
            font-weight: 700 !important;
            text-align: center !important;
            vertical-align: middle !important;
            padding: 8px 4px !important;
            border: 1px solid #13368a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 7.8pt !important;
            line-height: 1.2 !important;
        }
        .table-print-report td {
            display: table-cell !important;
            padding: 5px 4px !important;
            border: 1px solid #cbd5e1 !important;
            vertical-align: middle !important;
            color: #0f172a !important;
            word-break: break-word !important;
            line-height: 1.25 !important;
        }
        .table-print-report tbody tr:nth-child(even) td {
            background-color: #f8fafc !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .table-print-report tfoot td {
            display: table-cell !important;
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            padding: 7px 4px !important;
            border: 1px solid #cbd5e1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        /* Pastikan th:last-child dan td:last-child tidak disembunyikan oleh layout main.php */
        .table-print-report th:last-child,
        .table-print-report td:last-child,
        .table-print-report tfoot td:last-child {
            display: table-cell !important;
        }
    }
    /* Mobile Responsive Order Cards */
    .mobile-order-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-order-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .mobile-card-btn {
        flex: 1 1 0;
        min-width: 0;
        height: 36px;
        border-radius: 50rem !important;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        text-decoration: none;
        white-space: nowrap;
        padding: 0 6px;
        transition: all 0.15s ease-in-out;
        box-shadow: none !important;
    }
    .mobile-card-btn i {
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .mobile-card-btn:active, .mobile-icon-btn:active {
        transform: scale(0.94);
    }
    .mobile-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
        box-shadow: none !important;
        flex-shrink: 0;
    }
    .mobile-icon-btn i {
        font-size: 0.8rem;
    }
    .mobile-btn-thermal {
        background-color: #0f172a !important;
        border: 1px solid #0f172a !important;
        color: #ffffff !important;
    }
    .mobile-btn-wa {
        background-color: #f0fdf4 !important;
        border: 1px solid #86efac !important;
        color: #166534 !important;
    }
    .mobile-btn-detail {
        background-color: #eff6ff !important;
        border: 1px solid #93c5fd !important;
        color: #1d4ed8 !important;
    }
    .mobile-btn-edit {
        background-color: #fffbeb !important;
        border: 1px solid #fcd34d !important;
        color: #92400e !important;
    }
    .mobile-btn-delete {
        background-color: #fef2f2 !important;
        border: 1px solid #fca5a5 !important;
        color: #b91c1c !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    /* Stat Cards Marketplace Modern & Sleek Proportions */
    .stat-marketplace-card {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        padding: 0.75rem 0.65rem 0.75rem 0.85rem !important;
        min-height: 82px;
        display: flex;
        align-items: center;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
    }
    .stat-marketplace-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08) !important;
    }
    /* Sleek vertical accent strip (completely eliminates the curved claw bug) */
    .stat-marketplace-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: var(--stat-accent, #2563eb);
    }
    .stat-accent-orders {
        --stat-accent: #2563eb;
    }
    .stat-accent-omset {
        --stat-accent: #10b981;
    }
    .stat-accent-pending {
        --stat-accent: #f59e0b;
    }
    .stat-accent-done {
        --stat-accent: #0ea5e9;
    }
    .stat-mkt-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
    .stat-marketplace-card .stat-mkt-val {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.01em;
        font-variant-numeric: tabular-nums;
        color: #0f172a;
    }
    .stat-marketplace-card .stat-mkt-val-money {
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
        line-height: 1.15;
        color: #0f172a;
    }
    .stat-marketplace-card .stat-mkt-currency {
        font-size: 0.72em;
        font-weight: 600;
        color: #64748b;
        margin-right: 3px;
        flex-shrink: 0;
    }
    .stat-marketplace-card .stat-mkt-amount {
        display: inline-block;
        padding-right: 3px; /* Prevents subpixel clipping on last digit */
    }
    .stat-mkt-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-top: 2px;
        line-height: 1.2;
    }
    @media (min-width: 576px) {
        .stat-marketplace-card {
            padding: 0.85rem 0.85rem 0.85rem 1rem !important;
            min-height: 86px;
        }
        .stat-mkt-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            font-size: 1.18rem;
        }
        .stat-marketplace-card .stat-mkt-val {
            font-size: 1.35rem;
        }
        .stat-marketplace-card .stat-mkt-val-money {
            font-size: 1.25rem;
        }
        .stat-mkt-label {
            font-size: 0.7rem;
        }
    }
    @media (min-width: 992px) {
        .stat-marketplace-card {
            padding: 0.9rem 0.8rem 0.9rem 1rem !important;
            min-height: 88px;
        }
        .stat-marketplace-card .stat-mkt-val {
            font-size: 1.45rem;
        }
        .stat-marketplace-card .stat-mkt-val-money {
            font-size: 1.32rem;
        }
    }
    @media (min-width: 1200px) {
        .stat-marketplace-card {
            padding: 1rem 0.9rem 1rem 1.15rem !important;
            min-height: 92px;
        }
        .stat-mkt-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            font-size: 1.25rem;
        }
        .stat-marketplace-card .stat-mkt-val {
            font-size: 1.55rem;
        }
        .stat-marketplace-card .stat-mkt-val-money {
            font-size: 1.4rem;
        }
        .stat-mkt-label {
            font-size: 0.72rem;
        }
    }
    @media (min-width: 1400px) {
        .stat-marketplace-card {
            padding: 1.1rem 1rem 1.1rem 1.25rem !important;
            min-height: 96px;
        }
        .stat-marketplace-card .stat-mkt-val {
            font-size: 1.65rem;
        }
        .stat-marketplace-card .stat-mkt-val-money {
            font-size: 1.48rem;
        }
    }
    .btn-header-scan-resi {
        background-color: #ffffff !important;
        color: var(--accent-green) !important;
        border: 1px solid var(--accent-green) !important;
        transition: all var(--transition-fast);
    }
    .btn-header-scan-resi span {
        color: var(--accent-green) !important;
    }
    .btn-header-scan-resi:hover,
    .btn-header-scan-resi:focus,
    .btn-header-scan-resi:active {
        background-color: #f0fdf4 !important;
        border-color: var(--accent-green) !important;
        color: var(--accent-green) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(45, 106, 79, 0.15);
    }

    @media (max-width: 575.98px) {
        .mkt-header-actions {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.4rem !important;
            width: 100% !important;
        }
        .mkt-header-actions .btn {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            padding: 0.4rem 0.5rem !important;
            font-size: 0.76rem !important;
            text-align: center !important;
            justify-content: center !important;
        }
    }
</style>
<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3 d-print-none">
    <div class="d-flex align-items-center gap-2">
        <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
            <i class="fa-solid fa-shop fs-5"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold text-dark mb-0 fs-5 fs-sm-4">Penjualan Marketplace</h4>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;"><i class="fa-solid fa-cart-shopping me-1"></i><?= count($orders) ?> Order</span>
            </div>
            <span class="text-muted small">Monitoring &amp; Fulfillment Pesanan Multi-Channel (Shopee, Tokopedia, TikTok)</span>
        </div>
    </div>
    <div class="mkt-header-actions d-flex flex-wrap align-items-center gap-1.5 gap-sm-2 d-print-none w-100 w-sm-auto justify-content-start justify-content-sm-end mt-2 mt-sm-0">
        <a href="<?= base_url('marketplace/scan-resi') ?>" class="btn btn-sm btn-header-scan-resi fw-semibold d-inline-flex align-items-center gap-1 shadow-xs px-2.5 py-1.5" title="Scan Resi (Foto/PDF)">
            <i class="fa-solid fa-barcode text-primary"></i>
            <span>Scan Resi</span>
        </a>
        <a href="<?= base_url('marketplace/import') ?>" class="btn btn-sm btn-light border fw-semibold text-secondary d-inline-flex align-items-center gap-1 shadow-xs px-2.5 py-1.5" title="Import CSV">
            <i class="fa-solid fa-file-import text-primary"></i>
            <span>Import CSV</span>
        </a>
        <a href="<?= base_url('penjualan/create') ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-xs px-2.5 py-1.5 fw-semibold" title="Tambah Pesanan Baru">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah</span>
        </a>
        <a href="<?= base_url('marketplace/export') ?>" class="btn btn-light border btn-sm text-secondary d-inline-flex align-items-center gap-1 shadow-xs px-2.5 py-1.5" title="Export XLS">
            <i class="fa-solid fa-file-excel text-success"></i>
            <span>XLS</span>
        </a>
        <button type="button" class="btn btn-light border btn-sm text-secondary d-inline-flex align-items-center gap-1 shadow-xs px-2.5 py-1.5" onclick="printMarketplaceReport()" title="Export PDF">
            <i class="fa-solid fa-file-pdf text-danger"></i>
            <span>PDF</span>
        </button>
    </div>
</div>

<!-- 4 STAT CARDS REKAP MARKETPLACE -->
<div class="row g-2 g-sm-3 mb-3 d-print-none">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-marketplace-card stat-accent-orders">
            <div class="d-flex align-items-center gap-2 gap-xl-2.5 w-100">
                <div class="stat-mkt-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="stat-mkt-val text-dark text-nowrap" title="<?= number_format($totalOrders ?? count($orders), 0, ',', '.') ?>">
                        <span class="stat-mkt-amount"><?= number_format($totalOrders ?? count($orders), 0, ',', '.') ?></span>
                    </div>
                    <div class="stat-mkt-label text-truncate">TOTAL PESANAN</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-marketplace-card stat-accent-omset">
            <div class="d-flex align-items-center gap-2 gap-xl-2.5 w-100">
                <div class="stat-mkt-icon-box bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="stat-mkt-val-money text-dark text-nowrap" title="Rp <?= number_format((float) ($totalOmset ?? 0), 0, ',', '.') ?>">
                        <span class="stat-mkt-currency">Rp</span>
                        <span class="stat-mkt-amount"><?= number_format((float) ($totalOmset ?? 0), 0, ',', '.') ?></span>
                    </div>
                    <div class="stat-mkt-label text-truncate">TOTAL OMSET</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-marketplace-card stat-accent-pending">
            <div class="d-flex align-items-center gap-2 gap-xl-2.5 w-100">
                <div class="stat-mkt-icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="stat-mkt-val text-dark text-nowrap" title="<?= number_format($perluDikirim ?? 0, 0, ',', '.') ?>">
                        <span class="stat-mkt-amount"><?= number_format($perluDikirim ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="stat-mkt-label text-truncate">PERLU DIKIRIM</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-marketplace-card stat-accent-done">
            <div class="d-flex align-items-center gap-2 gap-xl-2.5 w-100">
                <div class="stat-mkt-icon-box bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="min-w-0 flex-grow-1 overflow-hidden">
                    <div class="stat-mkt-val text-dark text-nowrap" title="<?= number_format($selesai ?? 0, 0, ',', '.') ?>">
                        <span class="stat-mkt-amount"><?= number_format($selesai ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="stat-mkt-label text-truncate">SELESAI / LUNAS</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border mb-4 d-print-none" id="mainMarketplaceCard">
    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2 align-items-center">
            <!-- Search Bar: Full width on mobile (< 768px), 5 cols on desktop -->
            <div class="col-12 col-md-5 order-1 order-md-4">
                <label class="form-label small text-muted mb-1 d-none d-md-block"><i class="fa-solid fa-magnifying-glass me-1"></i>CARI PESANAN / RESI / BUYER</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="searchMkt" class="form-control" placeholder="Cari No. Pesanan, Resi, atau Pembeli...">
                    <button class="btn btn-primary" type="button" id="btnSearchMkt">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> <span class="d-none d-sm-inline">Search</span>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetMkt" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>

            <!-- Kategori / Saluran -->
            <div class="col-6 col-md-3 order-2 order-md-1">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-tag me-1"></i>SALURAN</label>
                <select class="form-select form-select-sm" id="filterChannel">
                    <option value="">Semua Saluran</option>
                    <option value="Shopee">Shopee</option>
                    <option value="TikTok">TikTok Shop</option>
                    <option value="Tokopedia">Tokopedia</option>
                    <option value="Lazada">Lazada</option>
                </select>
            </div>

            <!-- Status Pesanan -->
            <div class="col-6 col-md-2 order-3 order-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-clock-rotate-left me-1"></i>STATUS</label>
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="Perlu Dikirim">Perlu Dikirim</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <!-- Tampilkan (Desktop Only) -->
            <div class="col-12 col-md-2 order-4 order-md-3 d-none d-lg-block">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-list-ol me-1"></i>TAMPILKAN</label>
                <select class="form-select form-select-sm" id="pageLengthMkt">
                    <option value="10">10 Data</option>
                    <option value="25" selected>25 Data</option>
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: DATA TABLE (HIDDEN ON MOBILE/TABLET PORTRAIT) -->
    <div class="card-body p-0 d-none d-lg-block">
        <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-hover align-middle mb-0" id="tableMarketplace" style="min-width: 1050px;">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th style="width: 35px;" class="d-print-none"><input type="checkbox" id="selectAll"></th>
                        <th>NO PESANAN &amp; PRODUK</th>
                        <th class="text-center" style="width: 120px;">STATUS PESANAN</th>
                        <th style="width: 150px;">PENGIRIMAN &amp; RESI</th>
                        <th class="text-center" style="width: 50px;">QTY</th>
                        <th style="width: 130px;">WAKTU BAYAR</th>
                        <th>PENERIMA &amp; KONTAK</th>
                        <th class="text-center" style="width: 90px;">PACK</th>
                        <th class="text-center" style="width: 140px;">FOLLOW-UP CRM</th>
                        <th class="text-center d-print-none" style="width: 130px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <?php 
                                $cleanPhone = preg_replace('/[^0-9]/', '', $order['no_telepon'] ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            ?>
                            <tr>
                                <td class="d-print-none"><input type="checkbox" class="order-cb" value="<?= $order['id'] ?>"></td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 font-monospace fw-bold text-dark small">
                                        <span><?= esc($order['no_pesanan']) ?></span>
                                        <button class="btn btn-link text-muted p-0" onclick="navigator.clipboard.writeText('<?= esc($order['no_pesanan']) ?>')" title="Salin"><i class="fa-regular fa-copy" style="font-size: 0.78rem;"></i></button>
                                    </div>
                                    <?php if ($order['source'] === 'tiktok'): ?>
                                        <span class="badge bg-dark text-white fw-normal" style="font-size: 0.75rem;"><i class="fa-brands fa-tiktok me-1"></i>TikTok Shop</span>
                                    <?php elseif ($order['source'] === 'shopee'): ?>
                                        <span class="badge bg-danger text-white fw-normal" style="font-size: 0.75rem;"><i class="fa-solid fa-bag-shopping me-1"></i>Shopee</span>
                                    <?php else: ?>
                                        <span class="badge bg-success text-white fw-normal" style="font-size: 0.75rem;"><?= ucfirst(esc($order['source'])) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($order['is_cod'])): ?>
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-0 fw-semibold" style="font-size: 0.72rem;"><i class="fa-solid fa-hand-holding-dollar me-1"></i>COD</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0 fw-semibold" style="font-size: 0.72rem;"><i class="fa-solid fa-credit-card me-1"></i>Non-COD</span>
                                    <?php endif; ?>
                                    <div class="small text-dark mt-1"><?= esc($order['nama_produk_marketplace'] ?? $order['items_summary'] ?? 'Pesanan Marketplace') ?></div>
                                    <?php if (!empty($order['items_sku_summary'])): ?>
                                        <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.72rem;"><?= esc($order['items_sku_summary']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">Produk Terdaftar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php $isDone = in_array(strtolower((string)($order['status'] ?? '')), ['completed', 'selesai'], true); ?>
                                    <span class="badge <?= $isDone ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning border-warning-subtle' ?> border rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                        &#9679; <?= $isDone ? 'Selesai' : 'Perlu Dikirim' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge px-2 py-1 text-white" style="background-color: #9333ea; font-size: 0.75rem;">
                                        <?= esc($order['kurir'] ?? 'J&T / SPX') ?>
                                    </span>
                                    <?php if (!empty($order['nomor_resi'])): ?>
                                        <div class="font-monospace fw-bold text-primary small d-flex align-items-center gap-1 mt-1">
                                            <span><?= esc($order['nomor_resi']) ?></span>
                                            <button class="btn btn-link text-muted p-0" onclick="navigator.clipboard.writeText('<?= esc($order['nomor_resi']) ?>')" title="Salin Resi"><i class="fa-regular fa-copy" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="fw-bold text-dark font-monospace small text-nowrap">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></div>
                                </td>
                                <td class="text-center font-monospace fw-bold text-dark small"><?= number_format((float) ($order['total_qty'] ?? 1), 0, ',', '.') ?></td>
                                <td class="small text-secondary font-monospace" data-order="<?= esc(($order['tanggal_pesanan'] ?? $order['created_at'] ?? '2000-01-01 00:00:00') . '_' . str_pad((string)$order['id'], 10, '0', STR_PAD_LEFT)) ?>">
                                    <div><i class="fa-regular fa-calendar me-1"></i><?= !empty($order['tanggal_pesanan']) ? date('Y-m-d', strtotime($order['tanggal_pesanan'])) : '-' ?></div>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i><?= !empty($order['tanggal_pesanan']) ? date('H:i', strtotime($order['tanggal_pesanan'])) : '-' ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small"><?= esc($order['nama_penerima']) ?></div>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <small class="text-muted font-monospace"><?= esc($order['no_telepon'] ?? '-') ?></small>
                                        <?php if (!empty($cleanPhone)): ?>
                                            <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="badge bg-success text-white text-decoration-none px-1 py-0" style="font-size: 0.75rem;">
                                                <i class="fa-brands fa-whatsapp"></i> WA
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;">PACKED</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="badge bg-danger rounded-pill px-2 py-0" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>H+5 Terlewat
                                        </span>
                                        <div class="d-flex gap-1" style="font-size: 0.72rem;">
                                            <span class="badge bg-light text-secondary border">H-0</span>
                                            <span class="badge bg-light text-secondary border">H+3</span>
                                            <span class="badge bg-light text-secondary border">H+7</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center d-print-none">
                                    <div class="d-inline-flex flex-nowrap gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-dark py-1 px-2 rounded btn-print-resi-thermal" data-id="<?= $order['id'] ?>" title="Cetak Label Resi Thermal">
                                            <i class="fa-solid fa-barcode text-warning" style="font-size: 0.85rem;"></i>
                                        </button>
                                        <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-success py-1 px-2 rounded" title="Follow-Up WhatsApp (CRM)">
                                            <i class="fa-brands fa-whatsapp" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <a href="<?= base_url('marketplace/detail/' . $order['id']) ?>" class="btn btn-sm btn-primary py-1 px-2 rounded" title="Detail Pesanan">
                                            <i class="fa-solid fa-eye" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <a href="<?= base_url('marketplace/edit/' . $order['id']) ?>" class="btn btn-sm btn-warning py-1 px-2 rounded text-dark" title="Edit Pesanan">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <a href="<?= base_url('marketplace/delete/' . $order['id']) ?>" class="btn btn-sm btn-danger py-1 px-2 rounded btn-action-delete" title="Hapus">
                                            <i class="fa-solid fa-trash" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. MOBILE VIEW: RESPONSIVE ORDER CARDS (VISIBLE ON SCREENS < 992px) -->
    <div class="card-body p-2 p-sm-3 d-block d-lg-none bg-light bg-opacity-25" id="mobileMarketplaceCardsContainer">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-cart-shopping text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Pesanan Marketplace</h6>
                <p class="text-muted small mb-0">Pesanan dari Shopee, TikTok Shop, atau Tokopedia akan otomatis tercatat di sini.</p>
            </div>
        <?php else: ?>
            <div id="mobileMarketplaceCardsList">
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $cleanPhone = preg_replace('/[^0-9]/', '', $order['no_telepon'] ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $isDone = in_array(strtolower((string)($order['status'] ?? '')), ['completed', 'selesai'], true);
                        $src = strtolower((string)($order['source'] ?? ''));
                        $channelName = $src === 'shopee' ? 'Shopee' : ($src === 'tiktok' ? 'TikTok' : ($src === 'tokopedia' ? 'Tokopedia' : ucfirst($src ?: '-')));
                        $statusText = $isDone ? 'Selesai' : 'Perlu Dikirim';
                        $searchStr = strtolower(esc($order['no_pesanan'] . ' ' . ($order['nomor_resi'] ?? '') . ' ' . ($order['nama_penerima'] ?? '') . ' ' . ($order['nama_produk_marketplace'] ?? '') . ' ' . ($order['items_summary'] ?? '') . ' ' . $channelName . ' ' . $statusText));
                        $orderDate = !empty($order['tanggal_pesanan']) ? $order['tanggal_pesanan'] : ($order['created_at'] ?? '');
                    ?>
                    <div class="mobile-order-card shadow-xs mb-2.5 p-3 bg-white"
                         data-id="<?= $order['id'] ?>"
                         data-channel="<?= esc(strtolower($channelName)) ?>"
                         data-status="<?= esc(strtolower($statusText)) ?>"
                         data-search="<?= $searchStr ?>">
                        
                        <!-- Top Bar: Channel Badge, COD Badge, Status Badge -->
                        <div class="d-flex align-items-center justify-content-between gap-1 mb-2 pb-2 border-bottom flex-wrap">
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <?php if ($src === 'tiktok'): ?>
                                    <span class="badge bg-dark text-white fw-normal" style="font-size: 0.72rem;"><i class="fa-brands fa-tiktok me-1"></i>TikTok</span>
                                <?php elseif ($src === 'shopee'): ?>
                                    <span class="badge bg-danger text-white fw-normal" style="font-size: 0.72rem;"><i class="fa-solid fa-bag-shopping me-1"></i>Shopee</span>
                                <?php else: ?>
                                    <span class="badge bg-success text-white fw-normal" style="font-size: 0.72rem;"><?= esc($channelName) ?></span>
                                <?php endif; ?>

                                <?php if (!empty($order['is_cod'])): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0 fw-semibold" style="font-size: 0.7rem;">COD</span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0 fw-semibold" style="font-size: 0.7rem;">Non-COD</span>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <span class="badge <?= $isDone ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning border-warning-subtle' ?> border rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                                    &#9679; <?= $statusText ?>
                                </span>
                            </div>
                        </div>

                        <!-- No. Pesanan & Total Nilai -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <div class="d-flex align-items-center gap-1 min-w-0">
                                <span class="font-monospace fw-bold text-dark small text-truncate"><?= esc($order['no_pesanan']) ?></span>
                                <button class="btn btn-link text-muted p-0 flex-shrink-0" onclick="navigator.clipboard.writeText('<?= esc($order['no_pesanan']) ?>')" title="Salin No Pesanan">
                                    <i class="fa-regular fa-copy" style="font-size: 0.75rem;"></i>
                                </button>
                            </div>
                            <div class="fw-bold font-monospace text-primary text-nowrap" style="font-size: 0.95rem;">
                                Rp <?= number_format((float) $order['total'], 0, ',', '.') ?>
                            </div>
                        </div>

                        <!-- Detail Produk & Qty -->
                        <div class="p-2 rounded bg-light bg-opacity-75 mb-2 border">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="small text-dark fw-medium line-clamp-2"><?= esc($order['nama_produk_marketplace'] ?? $order['items_summary'] ?? 'Pesanan Marketplace') ?></div>
                                <span class="badge bg-white text-dark border font-monospace flex-shrink-0">x<?= number_format((float) ($order['total_qty'] ?? 1), 0, ',', '.') ?></span>
                            </div>
                            <?php if (!empty($order['items_sku_summary'])): ?>
                                <div class="mt-1">
                                    <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.7rem;"><?= esc($order['items_sku_summary']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Ekspedisi & Resi + Waktu Bayar -->
                        <div class="row g-2 mb-2 py-1.5 px-2 rounded-2 bg-light bg-opacity-50 border small align-items-center">
                            <div class="col-7">
                                <div class="d-flex align-items-center gap-1 text-truncate">
                                    <span class="badge px-1.5 py-0 text-white flex-shrink-0" style="background-color: #9333ea; font-size: 0.68rem;"><?= esc($order['kurir'] ?? 'Reguler') ?></span>
                                    <?php if (!empty($order['nomor_resi'])): ?>
                                        <span class="font-monospace fw-semibold text-primary text-truncate" style="font-size: 0.75rem;"><?= esc($order['nomor_resi']) ?></span>
                                        <button class="btn btn-link text-muted p-0 flex-shrink-0" onclick="navigator.clipboard.writeText('<?= esc($order['nomor_resi']) ?>')" title="Salin Resi">
                                            <i class="fa-regular fa-copy" style="font-size: 0.7rem;"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.72rem;">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-5 text-end text-muted font-monospace text-truncate" style="font-size: 0.72rem;">
                                <i class="fa-regular fa-clock me-1"></i><?= !empty($orderDate) ? date('d/m/y H:i', strtotime($orderDate)) : '-' ?>
                            </div>
                        </div>

                        <!-- Penerima & Kontak + WhatsApp -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-1.5 text-truncate">
                                <i class="fa-solid fa-user text-secondary" style="font-size: 0.75rem;"></i>
                                <span class="fw-semibold text-dark small text-truncate"><?= esc($order['nama_penerima']) ?></span>
                                <span class="text-muted font-monospace small text-truncate" style="font-size: 0.72rem;">(<?= esc($order['no_telepon'] ?? '-') ?>)</span>
                            </div>
                            <?php if (!empty($cleanPhone)): ?>
                                <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="badge bg-success text-white text-decoration-none px-2 py-1 flex-shrink-0" style="font-size: 0.72rem;">
                                    <i class="fa-brands fa-whatsapp me-1"></i>WA
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="mobile-card-actions">
                            <div class="d-flex align-items-center gap-1.5 flex-grow-1">
                                <button type="button" class="mobile-card-btn mobile-btn-thermal btn-print-resi-thermal" data-id="<?= $order['id'] ?>" title="Cetak Resi Thermal">
                                    <i class="fa-solid fa-barcode text-warning"></i>
                                    <span>Resi</span>
                                </button>
                                <a href="<?= base_url('crm') ?>" class="mobile-card-btn mobile-btn-wa" title="Follow-Up WhatsApp (CRM)">
                                    <i class="fa-brands fa-whatsapp text-success"></i>
                                    <span>CRM</span>
                                </a>
                                <a href="<?= base_url('marketplace/detail/' . $order['id']) ?>" class="mobile-card-btn mobile-btn-detail" title="Detail Pesanan">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                    <span>Detail</span>
                                </a>
                            </div>
                            <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                <a href="<?= base_url('marketplace/edit/' . $order['id']) ?>" class="mobile-icon-btn mobile-btn-edit" title="Edit Pesanan">
                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <a href="<?= base_url('marketplace/delete/' . $order['id']) ?>" class="mobile-icon-btn mobile-btn-delete btn-action-delete" title="Hapus">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty Search State -->
            <div id="mobileEmptyState" class="text-center py-4 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-2 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Pesanan Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau reset filter saluran/status.</p>
            </div>

            <!-- Mobile Pagination / Load More Bar -->
            <div class="mt-3 text-center" id="mobilePaginationContainer">
                <div class="small text-muted mb-2">
                    Menampilkan <strong id="mobileVisibleCount">0</strong> dari <strong id="mobileTotalCount"><?= count($orders) ?></strong> order
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-primary btn-sm px-3 rounded-pill shadow-xs" id="btnMobileLoadMore">
                        <i class="fa-solid fa-chevron-down me-1"></i> Muat Lebih Banyak (+10)
                    </button>
                    <button type="button" class="btn btn-light border btn-sm px-3 rounded-pill text-secondary" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                        <i class="fa-solid fa-arrow-up me-1"></i> Ke Atas
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- DEDICATED REPORT TABLE FOR PRINT / PDF EXPORT (SESUAI FOTO) -->
<div class="d-none d-print-block" id="printMarketplaceReport">
    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark" style="font-size: 14pt;">LAPORAN PENJUALAN MARKETPLACE</h4>
            <div class="small text-muted">IDMETAFORA ERP Enterprise &bull; Rekap Data Pesanan Multi-Channel Omnichannel</div>
        </div>
        <div class="text-end small text-muted">
            <div>Tanggal Cetak: <strong><?= date('d/m/Y H:i') ?></strong></div>
            <div>Total Pesanan: <strong id="printTotalOrders"><?= count($orders) ?> Order</strong></div>
        </div>
    </div>

    <table class="table-print-report" id="tablePrintMarketplace">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">No</th>
                <th style="width: 14%;" class="text-center">No. Pesanan</th>
                <th style="width: 11%;" class="text-center">No. Transaksi Penjualan</th>
                <th style="width: 7%;" class="text-center">Saluran</th>
                <th style="width: 8%;" class="text-center">Status Pesanan</th>
                <th style="width: 10%;" class="text-center">No. Resi</th>
                <th style="width: 8%;" class="text-center">Kurir / Opsi Kirim</th>
                <th style="width: 9%;" class="text-center">Waktu Bayar</th>
                <th style="width: 8%;" class="text-center">Username Pembeli</th>
                <th style="width: 9%;" class="text-center">Nama Penerima</th>
                <th style="width: 7%;" class="text-center">No. Telepon</th>
                <th style="width: 6%;" class="text-end">Total Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $grandTotalPrint = 0;
                foreach ($orders as $idx => $o): 
                    $grandTotalPrint += (float) ($o['total'] ?? 0);
                    $st = strtolower((string)($o['status'] ?? ''));
                    $statusText = in_array($st, ['completed', 'selesai'], true) ? 'Selesai' : (in_array($st, ['ready_to_ship', 'perlu dikirim', 'unpaid'], true) ? 'Perlu Dikirim' : ucfirst($st ?: 'Selesai'));
                    $src = strtolower((string)($o['source'] ?? ''));
                    $channelName = $src === 'shopee' ? 'Shopee' : ($src === 'tiktok' ? 'TikTok Shop' : ($src === 'tokopedia' ? 'Tokopedia' : ucfirst($src ?: '-')));
                    $rawTime = $o['tanggal_pesanan'] ?? $o['created_at'] ?? '';
                    $waktuBayar = !empty($rawTime) ? date('Y-m-d H:i', strtotime($rawTime)) : '-';
                    $noSo = !empty($o['nomor_so']) ? $o['nomor_so'] : (!empty($o['nomor_invoice']) ? $o['nomor_invoice'] : (!empty($o['so_id']) ? 'SO-' . $o['so_id'] : '-'));
                    $buyer = !empty($o['buyer_username']) ? $o['buyer_username'] : (!empty($o['username_pembeli']) ? $o['username_pembeli'] : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', trim((string)($o['nama_penerima'] ?? 'user')))));
            ?>
                <tr data-order-id="<?= $o['id'] ?>" data-channel="<?= esc(strtolower($channelName)) ?>" data-status="<?= esc(strtolower($statusText)) ?>" data-total="<?= (float)($o['total'] ?? 0) ?>">
                    <td class="text-center print-row-no"><?= $idx + 1 ?></td>
                    <td class="font-monospace fw-semibold"><?= esc($o['no_pesanan']) ?></td>
                    <td class="font-monospace text-center"><?= esc($noSo) ?></td>
                    <td class="text-center"><?= esc($channelName) ?></td>
                    <td class="text-center"><?= esc($statusText) ?></td>
                    <td class="font-monospace text-center"><?= esc($o['nomor_resi'] ?? '-') ?></td>
                    <td class="text-center"><?= esc($o['kurir'] ?? 'Reguler') ?></td>
                    <td class="text-center font-monospace" style="white-space: nowrap;"><?= esc($waktuBayar) ?></td>
                    <td><?= esc($buyer) ?></td>
                    <td><?= esc($o['nama_penerima'] ?? '-') ?></td>
                    <td class="font-monospace text-center"><?= esc($o['no_telepon'] ?? '-') ?></td>
                    <td class="text-end font-monospace fw-bold">Rp <?= number_format((float)$o['total'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: 700;">
                <td colspan="11" class="text-end" id="printGrandTotalColspanText">TOTAL KESELURUHAN (<?= count($orders) ?> Pesanan):</td>
                <td class="text-end font-monospace text-primary" id="printGrandTotalText">Rp <?= number_format($grandTotalPrint, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const table = $('#tableMarketplace').DataTable({
            dom: 'rtip',
            pageLength: 25,
            order: [[5, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 9] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });
        window.marketplaceTable = table;

        function syncPrintTable() {
            let visibleCount = 0;
            let sumTotal = 0;

            if (window.marketplaceTable) {
                const filteredRows = window.marketplaceTable.rows({ search: 'applied' }).nodes();
                const visibleIds = new Set();
                $(filteredRows).each(function() {
                    const id = $(this).find('.order-cb').val();
                    if (id) visibleIds.add(String(id));
                });

                $('#tablePrintMarketplace tbody tr').each(function() {
                    const row = $(this);
                    const orderId = String(row.data('order-id') || '');
                    const rowTotal = parseFloat(row.data('total') || 0);

                    if (visibleIds.has(orderId)) {
                        row.show();
                        visibleCount++;
                        sumTotal += rowTotal;
                        row.find('.print-row-no').text(visibleCount);
                    } else {
                        row.hide();
                    }
                });
            } else {
                $('#tablePrintMarketplace tbody tr').each(function() {
                    const row = $(this);
                    row.show();
                    visibleCount++;
                    sumTotal += parseFloat(row.data('total') || 0);
                    row.find('.print-row-no').text(visibleCount);
                });
            }

            $('#printTotalOrders').text(visibleCount + ' Order');
            $('#printGrandTotalColspanText').text('TOTAL KESELURUHAN (' + visibleCount + ' Pesanan):');
            $('#printGrandTotalText').text('Rp ' + Math.round(sumTotal).toLocaleString('id-ID'));
        }

        window.printMarketplaceReport = function() {
            syncPrintTable();
            setTimeout(function() {
                window.print();
            }, 50);
        };

        window.addEventListener('beforeprint', syncPrintTable);

        // Mobile Cards Progressive Loading & Search
        const mobilePageSize = 10;
        let mobileVisibleLimit = mobilePageSize;
        let activeSearchTerm = '';
        let activeChannel = '';
        let activeStatus = '';

        function updateMobileCards() {
            let matchedCards = [];
            $('.mobile-order-card').each(function() {
                const searchData = String($(this).data('search') || '').toLowerCase();
                const itemChannel = String($(this).data('channel') || '').toLowerCase();
                const itemStatus = String($(this).data('status') || '').toLowerCase();

                const matchSearch = !activeSearchTerm || searchData.includes(activeSearchTerm);
                const matchChannel = !activeChannel || itemChannel.includes(activeChannel);
                const matchStatus = !activeStatus || itemStatus.includes(activeStatus);

                if (matchSearch && matchChannel && matchStatus) {
                    matchedCards.push($(this));
                } else {
                    $(this).addClass('d-none');
                }
            });

            const totalMatched = matchedCards.length;
            $('#mobileTotalCount').text(totalMatched);

            if (totalMatched === 0) {
                $('#mobileEmptyState').removeClass('d-none');
                $('#mobilePaginationContainer').addClass('d-none');
            } else {
                $('#mobileEmptyState').addClass('d-none');
                $('#mobilePaginationContainer').removeClass('d-none');

                let currentlyShown = 0;
                matchedCards.forEach((card, index) => {
                    if (index < mobileVisibleLimit) {
                        card.removeClass('d-none');
                        currentlyShown++;
                    } else {
                        card.addClass('d-none');
                    }
                });

                $('#mobileVisibleCount').text(currentlyShown);
                if (currentlyShown >= totalMatched) {
                    $('#btnMobileLoadMore').addClass('d-none');
                } else {
                    $('#btnMobileLoadMore').removeClass('d-none');
                }
            }
        }

        $('#btnMobileLoadMore').on('click', function() {
            mobileVisibleLimit += mobilePageSize;
            updateMobileCards();
        });

        function triggerSearch(term) {
            activeSearchTerm = (term || '').toLowerCase().trim();
            if (table) {
                table.search(term).draw();
            }
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        }

        $('#pageLengthMkt').on('change', function() {
            table.page.len(parseInt(this.value)).draw();
        });

        $('#btnSearchMkt').on('click', function() {
            triggerSearch($('#searchMkt').val());
        });

        $('#searchMkt').on('input', function() {
            triggerSearch(this.value);
        });

        $('#searchMkt').on('keyup', function(e) {
            if (e.key === 'Enter') {
                triggerSearch(this.value);
            }
        });

        $('#btnResetMkt').on('click', function() {
            $('#searchMkt').val('');
            $('#filterChannel').val('');
            $('#filterStatus').val('');
            activeSearchTerm = '';
            activeChannel = '';
            activeStatus = '';
            table.search('').columns().search('').draw();
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        });

        $('#filterChannel').on('change', function() {
            activeChannel = (this.value || '').toLowerCase().trim();
            table.column(1).search(this.value).draw();
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        });

        $('#filterStatus').on('change', function() {
            activeStatus = (this.value || '').toLowerCase().trim();
            table.column(2).search(this.value).draw();
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        });

        $('#selectAll').on('click', function() {
            $('.order-cb').prop('checked', this.checked);
        });

        // Initialize mobile cards on load
        updateMobileCards();

        // Print Shipping Label Resi Thermal
        $(document).on('click', '.btn-print-resi-thermal', async function() {
            const orderId = $(this).data('id');
            const btn = $(this);
            const orig = btn.html();

            try {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
                const res = await $.getJSON('<?= base_url('marketplace/resi-thermal-data/') ?>' + orderId);
                if (res.status !== 'success') {
                    throw new Error(res.message || 'Gagal memuat data resi marketplace.');
                }

                const ord = res.order;
                const items = res.items;
                const store = res.store;

                let itemsHtml = '';
                items.forEach((it, idx) => {
                    itemsHtml += `
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>${idx + 1}. ${it.nama_produk_marketplace || it.nama_produk || 'Produk'}</span>
                            <span class="fw-bold">x${it.qty || 1}</span>
                        </div>
                    `;
                });

                const total = Math.round(parseFloat(ord.total || 0)).toLocaleString('id-ID');

                const previewHtml = `
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 15px;">[ ${(ord.kurir || 'REGULER').toUpperCase()} ]</div>
                        <div class="small fw-semibold text-uppercase text-secondary">${(ord.source || 'MARKETPLACE')} ORDER</div>
                    </div>
                    <div class="double-line"></div>
                    <div class="text-center my-2 p-2 bg-light border rounded">
                        <div class="text-muted small">NO. RESI / AWB:</div>
                        <div class="fw-bold font-monospace" style="font-size: 16px; letter-spacing: 1px;">${ord.nomor_resi || '-'}</div>
                        <div class="small text-muted">No. Pesanan: ${ord.no_pesanan || ord.nomor_so || '-'}</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold text-uppercase text-dark">Kepada (Penerima):</div>
                        <div class="fw-bold fs-6">${ord.nama_penerima || 'Penerima'}</div>
                        <div>Telp: ${ord.no_telepon || '-'}</div>
                        <div class="mt-1">${ord.alamat_penerima || '-'}</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold text-uppercase text-dark">Dari (Pengirim):</div>
                        <div>${store.name} (${store.phone})</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold mb-1">Rincian Barang (${items.length} item):</div>
                        ${itemsHtml}
                    </div>
                    <div class="dashed-line"></div>
                    <div class="text-center mt-2">
                        <div class="fw-bold fs-6">TOTAL: Rp ${total}</div>
                        <div class="small text-muted">Non-COD / Lunas di Marketplace</div>
                    </div>
                `;

                ThermalPrintHelper.showModal({
                    title: 'Label Resi ' + (ord.kurir || '') + ' #' + (ord.nomor_resi || ord.no_pesanan),
                    subtitle: 'Channel: ' + (ord.source || '').toUpperCase() + ' &bull; Penerima: ' + ord.nama_penerima,
                    previewHtml: previewHtml,
                    onPrintBluetooth: async () => {
                        await window.posBluetoothPrinter.printShippingLabel(ord, items, store);
                    },
                    onPrintSerial: async () => {
                        await window.posBluetoothPrinter.printShippingLabel(ord, items, store);
                    },
                    onPrintBrowser: () => {
                        const widthMm = window.posBluetoothPrinter.getPaperWidth() === 32 ? 58 : 80;
                        ThermalPrintHelper.printBrowserHtml(previewHtml, widthMm);
                    }
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Tidak dapat memuat label resi thermal.'
                });
            } finally {
                btn.prop('disabled', false).html(orig);
            }
        });
    });
</script>
<?= $this->endSection() ?>

