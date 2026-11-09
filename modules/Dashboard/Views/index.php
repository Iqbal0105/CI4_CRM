<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP WELCOME & ACTION BAR -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="fw-bold text-dark mb-0">Dashboard Utama</h3>
            <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1 rounded-pill small">
                <i class="fa-solid fa-circle text-success me-1" style="font-size: 0.5rem;"></i>Sistem Normal
            </span>
        </div>
        <p class="text-muted small mb-0">
            Selamat datang kembali, <strong class="text-dark"><?= esc(session()->get('user')['nama'] ?? session()->get('user')['username'] ?? 'Admin') ?></strong>! Berikut rangkuman performa operasional bisnis Anda hari ini.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="<?= base_url('pos') ?>" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs">
            <i class="fa-solid fa-cash-register me-1"></i>Kasir Cepat (POS)
        </a>
        <a href="<?= base_url('penjualan/create') ?>" class="btn btn-sm btn-outline-primary fw-semibold px-3 bg-white">
            <i class="fa-solid fa-plus me-1"></i>Buat Pesanan SO
        </a>
        <a href="<?= base_url('marketplace/scan-resi') ?>" class="btn btn-sm btn-light border text-secondary px-3">
            <i class="fa-solid fa-barcode me-1"></i>Scan Resi
        </a>
    </div>
</div>

<!-- 0. PETA ALUR KERJA BISNIS ERP (INTERACTIVE WORKFLOW MAP) -->
<div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #FAF8F4 0%, #FFFFFF 100%); border: 1px solid var(--border-color) !important;">
    <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-route fs-6"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Alur Kerja Operasional ERP</h6>
                <small class="text-muted" style="font-size: 0.76rem;">Ikuti 6 langkah bisnis di bawah untuk alur transaksi yang tertib &amp; otomatis</small>
            </div>
        </div>
        <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalWorkflowGuide" style="font-size: 0.75rem;">
            <i class="fa-solid fa-circle-question"></i>
            <span>Panduan Alur Detail</span>
        </button>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-2 g-sm-3 align-items-stretch">
            <!-- Step 1: Master Data -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #2D6A4F !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #E8F5E9; color: #1B5E20; border: 1px solid #C8E6C9; padding: 0.2rem 0.45rem;">TAHAP 1</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #E8F5E9; color: #1B5E20;">
                            <i class="fa-solid fa-database small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">Master Data</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Katalog produk, harga, pelanggan &amp; supplier</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('master/produk') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-plus text-success me-1"></i> Produk
                        </a>
                        <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-users text-primary me-1"></i> Pelanggan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 2: Pembelian -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #0284c7 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; padding: 0.2rem 0.45rem;">TAHAP 2</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #E0F2FE; color: #0369A1;">
                            <i class="fa-solid fa-cart-shopping small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">Pembelian (PO)</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Pengadaan stok baru ke vendor resmi</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('pembelian/create') ?>" class="btn btn-xs btn-primary text-white text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-plus me-1"></i> Buat PO
                        </a>
                        <a href="<?= base_url('pembelian') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-file-lines text-secondary me-1"></i> Daftar PO
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 3: Manajemen Stok -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #d97706 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; padding: 0.2rem 0.45rem;">TAHAP 3</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #FEF3C7; color: #92400E;">
                            <i class="fa-solid fa-boxes-stacked small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">Manajemen Stok</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Penerimaan barang &amp; audit stok fisik</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('stok') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-chart-pie text-warning me-1"></i> Cek Stok
                        </a>
                        <a href="<?= base_url('stok/opname') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-pen-to-square text-secondary me-1"></i> Opname
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 4: Penjualan -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #dc2626 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; padding: 0.2rem 0.45rem;">TAHAP 4</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #FEE2E2; color: #B91C1C;">
                            <i class="fa-solid fa-cash-register small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">Penjualan &amp; Kasir</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Kasir POS cepat, order SO &amp; Marketplace</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('pos') ?>" class="btn btn-xs btn-success text-white text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-bolt me-1"></i> Kasir POS
                        </a>
                        <a href="<?= base_url('penjualan/create') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-plus text-primary me-1"></i> Buat SO
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 5: Keuangan -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #4b5563 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; padding: 0.2rem 0.45rem;">TAHAP 5</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #F1F5F9; color: #334155;">
                            <i class="fa-solid fa-money-bill-wave small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">Keuangan &amp; Kas</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Penerimaan kas piutang &amp; Buku Besar</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('keuangan/piutang') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-hand-holding-dollar text-primary me-1"></i> Piutang (AR)
                        </a>
                        <a href="<?= base_url('keuangan/bkm') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-arrow-down text-success me-1"></i> Kas Masuk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 6: CRM & Retensi -->
            <div class="col-6 col-sm-6 col-lg-2">
                <div class="card h-100 border shadow-xs rounded-3 p-2 p-sm-3 bg-white hover-lift" style="border-top: 3px solid #7c3aed !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill fw-bold" style="font-size: 0.65rem; background-color: #F3E8FF; color: #7E22CE; border: 1px solid #E9D5FF; padding: 0.2rem 0.45rem;">TAHAP 6</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background-color: #F3E8FF; color: #7E22CE;">
                            <i class="fa-solid fa-comments small" style="font-size: 0.75rem;"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-dark small mb-1">CRM &amp; RFM</div>
                    <div class="text-muted mb-2 mb-sm-3" style="font-size: 0.72rem; line-height: 1.35;">Follow up WhatsApp &amp; analisis loyalitas</div>
                    <div class="mt-auto d-grid gap-1">
                        <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-xs btn-success text-white text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-brands fa-whatsapp me-1"></i> Promo WA
                        </a>
                        <a href="<?= base_url('rfm') ?>" class="btn btn-xs btn-light border text-start py-1 px-2 text-truncate" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-chart-pie text-primary me-1"></i> Analisis RFM
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 1. 4 KEY METRIC CARDS (CLEAN & MODERN) -->
<div class="row g-2 g-sm-3 mb-4">
    <!-- Card 1: Transaksi Penjualan Aktif -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2 mb-sm-3">
                <div class="stat-icon-box" style="background-color: #E8F0EB; color: #2D6A4F;">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary small fw-semibold px-2 py-1 rounded-pill">
                    Aktif
                </span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                TRANSAKSI PENJUALAN
            </div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fs-sm-2 fw-bold text-dark font-sans"><?= esc($totalActiveOrders) ?></span>
                <span class="small text-muted">Pesanan</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2 mt-sm-3 pt-2 border-top">
                <span class="text-muted small d-none d-sm-inline" style="font-size: 0.75rem;">Total pesanan berjalan</span>
                <span class="text-muted small d-inline d-sm-none" style="font-size: 0.7rem;">Berjalan</span>
                <a href="<?= base_url('penjualan') ?>" class="small text-primary fw-semibold text-decoration-none">
                    Kelola <i class="fa-solid fa-arrow-right ms-1 small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card 2: Stok Barang Tersedia Fisik -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2 mb-sm-3">
                <div class="stat-icon-box" style="background-color: #ecfdf5; color: #059669;">
                    <i class="fa-solid fa-cubes-stacked"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success small fw-semibold px-2 py-1 rounded-pill">
                    Ready
                </span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                STOK FISIK TERSEDIA
            </div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fs-sm-2 fw-bold text-dark font-sans"><?= number_format($stokTersediaTotal, 0, ',', '.') ?></span>
                <span class="small text-muted">Unit</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2 mt-sm-3 pt-2 border-top">
                <span class="text-muted small d-none d-sm-inline" style="font-size: 0.75rem;">Total seluruh stok fisik</span>
                <span class="text-muted small d-inline d-sm-none" style="font-size: 0.7rem;">Stok Fisik</span>
                <a href="<?= base_url('stok') ?>" class="small text-success fw-semibold text-decoration-none">
                    Pantau <i class="fa-solid fa-arrow-right ms-1 small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Barang Terjual -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2 mb-sm-3">
                <div class="stat-icon-box" style="background-color: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <span class="badge bg-warning bg-opacity-15 text-warning small fw-semibold px-2 py-1 rounded-pill text-truncate">
                    +<?= number_format($totalBarangTerjualHariIni, 0, ',', '.') ?> Hari Ini
                </span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                TOTAL PRODUK TERJUAL
            </div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fs-sm-2 fw-bold text-dark font-sans"><?= number_format($totalBarangTerjual, 0, ',', '.') ?></span>
                <span class="small text-muted">Unit</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2 mt-sm-3 pt-2 border-top">
                <span class="text-muted small d-none d-sm-inline" style="font-size: 0.75rem;">Akumulasi penjualan</span>
                <span class="text-muted small d-inline d-sm-none" style="font-size: 0.7rem;">Invoices</span>
                <a href="<?= base_url('penjualan/invoice') ?>" class="small text-warning fw-semibold text-decoration-none">
                    Invoices <i class="fa-solid fa-arrow-right ms-1 small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card 4: Data Pelanggan -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2 mb-sm-3">
                <div class="stat-icon-box" style="background-color: #faf5ff; color: #7c3aed;">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <span class="badge bg-purple bg-opacity-10 text-purple small fw-semibold px-2 py-1 rounded-pill" style="background-color: #f3e8ff; color: #9333ea;">
                    Pelanggan
                </span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                DATA CUSTOMER
            </div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fs-sm-2 fw-bold text-dark font-sans"><?= esc($totalPelanggan) ?></span>
                <span class="small text-muted">Customer</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2 mt-sm-3 pt-2 border-top">
                <span class="text-muted small" style="font-size: 0.75rem;">Member &amp; Retail</span>
                <a href="<?= base_url('master/pelanggan') ?>" class="small text-decoration-none fw-semibold" style="color: #7c3aed;">
                    Kelola <i class="fa-solid fa-arrow-right ms-1 small"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 2. REVENUE & CASHFLOW HIGHLIGHTS ROW -->
<div class="row g-3 mb-4">
    <!-- Left: Omset Hari Ini & Bulan Ini -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #2B3D33 0%, #1A2E23 100%); color: white;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-10 text-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                <i class="fa-solid fa-chart-line small"></i>
                            </div>
                            <span class="fw-semibold text-white-50 small text-uppercase tracking-wider">Performa Pendapatan (Omset)</span>
                        </div>
                        <span class="badge bg-success px-3 py-1 rounded-pill small fw-semibold">Tahun 2026</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6 border-end border-white border-opacity-10">
                            <div class="text-white-50 small mb-1" style="font-size: 0.75rem;">Omset Penjualan Hari Ini</div>
                            <div class="fs-4 fw-bold text-white"><?= format_rupiah($omsetToday) ?></div>
                            <div class="text-success small mt-1" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i><?= $trxCountToday ?> Transaksi Tercatat
                            </div>
                        </div>
                        <div class="col-6 ps-md-4">
                            <div class="text-white-50 small mb-1" style="font-size: 0.75rem;">Total Omset Bulan Ini</div>
                            <div class="fs-4 fw-bold text-warning"><?= format_rupiah($omsetMonth) ?></div>
                            <div class="text-white-50 small mt-1" style="font-size: 0.78rem;">
                                Periode <?= date('F Y') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top border-white border-opacity-10 d-flex align-items-center justify-content-between">
                    <span class="text-white-50 small" style="font-size: 0.75rem;">Sinkron dengan modul Piutang &amp; POS</span>
                    <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                        Lihat Rincian Penjualan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Likuiditas & Piutang / Utang -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 border shadow-sm" style="border-radius: 16px; background-color: #ffffff;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                <i class="fa-solid fa-scale-balanced small"></i>
                            </div>
                            <span class="fw-semibold text-secondary small text-uppercase tracking-wider">Hak Piutang vs Kewajiban Utang</span>
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small">Buku Besar</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6 border-end">
                            <div class="text-muted small mb-1" style="font-size: 0.75rem;">Piutang Usaha (AR) Belum Lunas</div>
                            <div class="fs-4 fw-bold text-primary font-monospace"><?= format_rupiah($totalPiutang) ?></div>
                            <div class="text-muted small mt-1" style="font-size: 0.78rem;">
                                Hak tagih dari pelanggan
                            </div>
                        </div>
                        <div class="col-6 ps-md-4">
                            <div class="text-muted small mb-1" style="font-size: 0.75rem;">Utang Usaha (AP) Berjalan</div>
                            <div class="fs-4 fw-bold text-danger font-monospace"><?= format_rupiah($totalUtang) ?></div>
                            <div class="text-muted small mt-1" style="font-size: 0.78rem;">
                                Kewajiban ke supplier PO
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small" style="font-size: 0.75rem;">Arus kas terkonsolidasi</span>
                    <a href="<?= base_url('keuangan/jurnal') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                        Buku Besar (GL) <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. RINGKASAN MASTER DATA (COMPACT CLEAN TILES) -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-2">
        <div style="width: 4px; height: 16px; background-color: #2D6A4F; border-radius: 2px;"></div>
        <span class="fw-bold small text-secondary text-uppercase tracking-wider">NAVIGASI CEPAT MASTER DATA</span>
    </div>
    <span class="text-muted small" style="font-size: 0.75rem;">Klik untuk kelola katalog &amp; master data</span>
</div>

<div class="row g-3 mb-4">
    <!-- 1. Pelanggan -->
    <div class="col-6 col-md-3">
        <a href="<?= base_url('master/pelanggan') ?>" class="text-decoration-none">
            <div class="card border shadow-xs h-100 p-3" style="border-radius: 10px; transition: transform 0.15s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold" style="font-size: 0.78rem;">PELANGGAN</div>
                        <div class="fs-5 fw-bold text-dark mb-0"><?= esc($totalPelanggan) ?> <small class="text-muted fs-7 fw-normal">Kontak</small></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- 2. Produk -->
    <div class="col-6 col-md-3">
        <a href="<?= base_url('master/produk') ?>" class="text-decoration-none">
            <div class="card border shadow-xs h-100 p-3" style="border-radius: 10px; transition: transform 0.15s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold" style="font-size: 0.78rem;">PRODUK KATALOG</div>
                        <div class="fs-5 fw-bold text-dark mb-0"><?= esc($totalProduk) ?> <small class="text-muted fs-7 fw-normal">Item</small></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- 3. Kategori -->
    <div class="col-6 col-md-3">
        <a href="<?= base_url('master/kategori') ?>" class="text-decoration-none">
            <div class="card border shadow-xs h-100 p-3" style="border-radius: 10px; transition: transform 0.15s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle text-purple d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #f3e8ff; color: #9333ea;">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold" style="font-size: 0.78rem;">KATEGORI PRODUK</div>
                        <div class="fs-5 fw-bold text-dark mb-0"><?= esc($totalKategori) ?> <small class="text-muted fs-7 fw-normal">Jenis</small></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- 4. Satuan -->
    <div class="col-6 col-md-3">
        <a href="<?= base_url('master/satuan') ?>" class="text-decoration-none">
            <div class="card border shadow-xs h-100 p-3" style="border-radius: 10px; transition: transform 0.15s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold" style="font-size: 0.78rem;">UNIT SATUAN</div>
                        <div class="fs-5 fw-bold text-dark mb-0"><?= esc($totalSatuan) ?> <small class="text-muted fs-7 fw-normal">Satuan</small></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- 4. OPERATIONAL TABLES (PERINGATAN STOK & TRANSAKSI TERBARU) -->
<div class="row g-4">
    <!-- Peringatan Stok Minimum -->
    <div class="col-12 col-xl-6">
        <div class="card border shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-triangle-exclamation small"></i>
                    </div>
                    <span>Peringatan Stok Minimum</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?= $lowStockCount > 0 ? 'bg-danger' : 'bg-success' ?> rounded-pill px-2 py-1 small">
                        <?= esc($lowStockCount) ?> Kritis
                    </span>
                    <a href="<?= base_url('stok') ?>" class="small text-primary text-decoration-none fw-semibold">
                        Lihat Stok <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase" style="font-size: 0.78rem;">
                                <th class="ps-3 py-2">No</th>
                                <th>Produk</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Min</th>
                                <th class="text-center">Status</th>
                                <th class="pe-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lowStockItems)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; font-size: 20px;">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div class="fw-semibold text-dark small">Persediaan Stok Aman</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Semua barang berada di atas ambang batas minimum.</div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($lowStockItems as $item): ?>
                                    <?php 
                                        $sisaStok = (float) $item['sisa_stok'];
                                        $isHabis = $sisaStok <= 0;
                                    ?>
                                    <tr>
                                        <td class="ps-3 text-muted small"><?= $no++ ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark small"><?= esc($item['nama_produk']) ?></div>
                                            <div class="font-monospace text-muted" style="font-size: 0.78rem;"><?= esc($item['kode_produk']) ?></div>
                                        </td>
                                        <td class="text-center fw-bold text-danger"><?= number_format($sisaStok, 0, ',', '.') ?></td>
                                        <td class="text-center text-muted small"><?= esc($item['stok_minimum']) ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $isHabis ? 'bg-danger' : 'bg-warning text-dark' ?> rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                                <?= $isHabis ? 'Habis' : 'Kritis' ?>
                                            </span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="<?= base_url('pembelian/create') ?>" class="btn btn-sm btn-outline-primary py-0 px-2 fw-semibold" style="font-size: 0.75rem;">
                                                + PO Restok
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi & Retur Terbaru -->
    <div class="col-12 col-xl-6">
        <div class="card border shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-clock-rotate-left small"></i>
                    </div>
                    <span>Transaksi Retur Terbaru</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= base_url('retur/penjualan/create') ?>" class="btn btn-sm btn-outline-primary py-1 px-2 fw-semibold" style="font-size: 0.75rem;">
                        + Input Retur
                    </a>
                    <a href="<?= base_url('retur/penjualan') ?>" class="small text-secondary text-decoration-none">
                        Lihat Semua <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase" style="font-size: 0.78rem;">
                                <th class="ps-3 py-2">No. Retur</th>
                                <th>Pelanggan</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="pe-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentReturns)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 44px; height: 44px; background: var(--accent-green-light); color: var(--accent-green);">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div class="fw-semibold small" style="color: var(--text-primary);">Belum Ada Retur</div>
                                        <div class="small" style="font-size: 0.78rem; color: var(--text-muted);">Tidak ada transaksi retur penjualan yang tercatat.</div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentReturns as $ret): ?>
                                    <tr>
                                        <td class="ps-3"><span class="font-monospace fw-bold text-primary small"><?= esc($ret['nomor_retur']) ?></span></td>
                                        <td>
                                            <div class="fw-semibold text-dark small"><?= esc($ret['nama_pelanggan']) ?></div>
                                            <div class="text-muted" style="font-size: 0.78rem;"><i class="fa-regular fa-calendar me-1"></i><?= format_tanggal($ret['tanggal']) ?></div>
                                        </td>
                                        <td class="text-end font-monospace fw-bold text-danger small"><?= format_rupiah($ret['total']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                                &#10003; <?= strtoupper(esc($ret['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="<?= base_url('retur/penjualan') ?>" class="btn btn-sm btn-light border py-0 px-2 small">
                                                <i class="fa-solid fa-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
