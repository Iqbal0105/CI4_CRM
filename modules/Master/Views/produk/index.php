<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Mobile Product Card Styles */
    .mobile-product-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-product-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-product-card:active {
        transform: scale(0.99);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mobile Product Card Action Buttons with Balanced 25% Proportions & 8px Spacing */
    .mobile-card-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    .mobile-card-btn {
        flex: 1 1 0;
        min-width: 0;
        height: 38px;
        border-radius: 50rem !important;
        font-size: 0.74rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        white-space: nowrap;
        padding: 0 4px;
        transition: all 0.15s ease-in-out;
        box-shadow: none !important;
    }
    .mobile-card-btn i {
        font-size: 0.76rem;
        margin-right: 0;
        flex-shrink: 0;
    }
    .mobile-card-btn:active {
        transform: scale(0.97);
    }
    
    /* Semantic soft theme for each mobile button */
    .mobile-btn-detail {
        background-color: #f0fdf4 !important;
        border: 1px solid #86efac !important;
        color: #166534 !important;
    }
    .mobile-btn-detail:hover, .mobile-btn-detail:active {
        background-color: #dcfce7 !important;
        border-color: #4ade80 !important;
        color: #14532d !important;
    }
    .mobile-btn-edit {
        background-color: #fffbeb !important;
        border: 1px solid #fcd34d !important;
        color: #92400e !important;
    }
    .mobile-btn-edit:hover, .mobile-btn-edit:active {
        background-color: #fef3c7 !important;
        border-color: #f59e0b !important;
        color: #78350f !important;
    }
    .mobile-btn-stok {
        background-color: #f0f9ff !important;
        border: 1px solid #7dd3fc !important;
        color: #0369a1 !important;
    }
    .mobile-btn-stok:hover, .mobile-btn-stok:active {
        background-color: #e0f2fe !important;
        border-color: #38bdf8 !important;
        color: #075985 !important;
    }
    .mobile-btn-delete {
        background-color: #fef2f2 !important;
        border: 1px solid #fca5a5 !important;
        color: #b91c1c !important;
    }
    .mobile-btn-delete:hover, .mobile-btn-delete:active {
        background-color: #fee2e2 !important;
        border-color: #f87171 !important;
        color: #991b1b !important;
    }

    @media (max-width: 767.98px) {
        .nav-sub-pills {
            white-space: nowrap;
        }
    }

    @media print {
        @page {
            size: landscape;
            margin: 8mm 6mm;
        }
        body {
            padding: 8mm 6mm !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 9pt !important;
        }
        /* Sembunyikan elemen non-cetak yang ditandai user */
        .d-print-none,
        .btn,
        .card-header,
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        #mobileProductCardsContainer,
        #mobilePaginationContainer,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history {
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
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-footer {
            border-top: 1px solid #cbd5e1 !important;
            padding: 6px 0 !important;
            background: transparent !important;
        }
        /* PAKSA TAMPILKAN TABEL DAN KONTAINERNYA SAAT CETAK DI LAYAR APAPUN (MOBILE & DESKTOP) */
        .card-body.d-none.d-md-block,
        .d-none.d-md-block,
        .table-responsive {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            overflow: visible !important;
            width: 100% !important;
        }
        #tableProduk {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            visibility: visible !important;
        }
        #tableProduk thead {
            display: table-header-group !important;
        }
        #tableProduk tbody {
            display: table-row-group !important;
        }
        #tableProduk tr {
            display: table-row !important;
            page-break-inside: avoid !important;
        }
        #tableProduk th,
        #tableProduk td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableProduk th:last-child,
        #tableProduk td:last-child,
        #tableProduk .d-print-none {
            display: none !important;
        }
        #tableProduk th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .badge {
            border: 1px solid #94a3b8 !important;
            color: #0f172a !important;
            background: transparent !important;
            font-size: 8pt !important;
            padding: 2px 5px !important;
            font-weight: 600 !important;
        }
    }
</style>

<!-- HEADER KHUSUS CETAK PDF / PRINT -->
<div class="d-none d-print-block mb-3">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Daftar Katalog Produk</h4>
            <span class="small text-muted">IDMETAFORA ERP Enterprise</span>
        </div>
        <div class="text-end small text-muted">
            <div>Tanggal Cetak: <?= date('d/m/Y H:i') ?></div>
            <div>Total: <?= count($produk) ?> Produk</div>
        </div>
    </div>
</div>

<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Produk</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Katalog &amp; Master Data Produk</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Master Data</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Produk</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (MASTER DATA) -->
<div class="d-flex flex-nowrap overflow-x-auto align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none nav-sub-pills" style="scrollbar-width: none;">
    <a href="<?= base_url('master/produk') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs text-nowrap">
        <i class="fa-solid fa-cubes me-1"></i> Produk &amp; Katalog
    </a>
    <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-nowrap">
        <i class="fa-solid fa-users me-1"></i> Data Pelanggan
    </a>
    <a href="<?= base_url('master/supplier') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-nowrap">
        <i class="fa-solid fa-truck me-1"></i> Data Supplier
    </a>
    <a href="<?= base_url('master/kategori') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-nowrap">
        <i class="fa-solid fa-tag me-1"></i> Kategori Barang
    </a>
    <a href="<?= base_url('master/satuan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-nowrap">
        <i class="fa-solid fa-scale-balanced me-1"></i> Unit / Satuan
    </a>
    <a href="<?= base_url('stok') ?>" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1 fw-semibold text-nowrap">
        <i class="fa-solid fa-boxes-stacked me-1"></i> Cek Stok Barang
    </a>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-box text-secondary d-print-none"></i>
            <span>Daftar Master Produk</span>
            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;"><?= count($produk) ?> Item</span>
        </div>
        <div class="d-flex flex-row align-items-center gap-2 d-print-none w-100 w-sm-auto">
            <a href="<?= base_url('master/produk/create') ?>" class="btn btn-success btn-sm flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-3 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;">
                <i class="fa-solid fa-plus" style="font-size: 0.78rem;"></i>
                <span>Tambah Produk</span>
            </a>
            <a href="<?= base_url('master/produk/export') ?>" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" title="Export Excel (XLS)">
                <i class="fa-solid fa-file-excel" style="font-size: 0.82rem;"></i>
                <span><span class="d-none d-sm-inline">Export </span>XLS</span>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" onclick="printTable()" title="Export / Cetak PDF">
                <i class="fa-solid fa-file-pdf" style="font-size: 0.82rem;"></i>
                <span><span class="d-none d-sm-inline">Export </span>PDF</span>
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5 col-lg-4">
                <div class="input-group input-group-sm shadow-2xs rounded-3 overflow-hidden border bg-white" style="height: 38px;">
                    <span class="input-group-text bg-white border-0 text-muted ps-3 pe-1"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="customSearchProduk" class="form-control border-0 ps-1 shadow-none" placeholder="Cari nama, SKU, barcode..." style="font-size: 0.84rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-2.5 shadow-none" type="button" id="btnResetProduk" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-8 col-md-4 col-lg-3">
                <select id="filterKategoriProduk" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($kategori)): ?>
                        <?php foreach ($kategori as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-4 col-md-3 col-lg-5 text-end d-none d-sm-block">
                <span class="small text-muted">
                    Total: <strong class="text-dark"><?= count($produk) ?></strong> produk
                </span>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: COMPREHENSIVE DATA TABLE (HIDDEN ON MOBILE) -->
    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableProduk">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Status</th>
                        <th class="text-center d-print-none" style="width: 130px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $p): ?>
                        <tr>
                            <td><span class="font-monospace text-muted small"><?= esc($p['kode_produk']) ?></span></td>
                            <td>
                                <a href="javascript:void(0)" class="fw-semibold text-primary text-decoration-none btn-view-produk"
                                    data-id="<?= $p['id'] ?>"
                                    data-kode="<?= esc($p['kode_produk']) ?>"
                                    data-barcode="<?= esc($p['barcode'] ?? '-') ?>"
                                    data-nama="<?= esc($p['nama_produk']) ?>"
                                    data-kategori="<?= esc($p['nama_kategori'] ?? 'Tanpa Kategori') ?>"
                                    data-satuan="<?= esc($p['nama_satuan'] ?? 'pcs') ?>"
                                    data-hargabeli="<?= format_rupiah($p['harga_beli']) ?>"
                                    data-hargajual="<?= format_rupiah($p['harga_jual']) ?>"
                                    data-hargagrosir="<?= !empty($p['harga_grosir']) ? format_rupiah($p['harga_grosir']) : '-' ?>"
                                    data-hargadistributor="<?= !empty($p['harga_distributor']) ? format_rupiah($p['harga_distributor']) : '-' ?>"
                                    data-stokminimum="<?= (int)($p['stok_minimum'] ?? 0) ?>"
                                    data-stokfisik="<?= (int)($p['stok_fisik'] ?? 0) ?>"
                                    data-status="<?= ($p['status'] ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif' ?>">
                                    <?= esc($p['nama_produk']) ?>
                                </a>
                            </td>
                            <td class="small text-secondary"><?= esc($p['nama_kategori'] ?? 'Umum') ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill px-2 py-1" style="background-color: #0284c7; color: white; font-size: 0.78rem;">
                                    <?= strtolower(esc($p['nama_satuan'] ?? 'pcs')) ?>
                                </span>
                            </td>
                            <td class="text-end font-monospace small"><?= format_rupiah($p['harga_beli']) ?></td>
                            <td class="text-end font-monospace small fw-bold text-dark"><?= format_rupiah($p['harga_jual']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">AKTIF</span>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-action-view py-1 px-2 rounded btn-view-produk" 
                                        data-id="<?= $p['id'] ?>"
                                        data-kode="<?= esc($p['kode_produk']) ?>"
                                        data-barcode="<?= esc($p['barcode'] ?? '-') ?>"
                                        data-nama="<?= esc($p['nama_produk']) ?>"
                                        data-kategori="<?= esc($p['nama_kategori'] ?? 'Tanpa Kategori') ?>"
                                        data-satuan="<?= esc($p['nama_satuan'] ?? 'pcs') ?>"
                                        data-hargabeli="<?= format_rupiah($p['harga_beli']) ?>"
                                        data-hargajual="<?= format_rupiah($p['harga_jual']) ?>"
                                        data-hargagrosir="<?= !empty($p['harga_grosir']) ? format_rupiah($p['harga_grosir']) : '-' ?>"
                                        data-hargadistributor="<?= !empty($p['harga_distributor']) ? format_rupiah($p['harga_distributor']) : '-' ?>"
                                        data-stokminimum="<?= (int)($p['stok_minimum'] ?? 0) ?>"
                                        data-stokfisik="<?= (int)($p['stok_fisik'] ?? 0) ?>"
                                        data-status="<?= ($p['status'] ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif' ?>"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <a href="<?= base_url('master/produk/edit/' . $p['id']) ?>" class="btn btn-sm btn-action-edit py-1 px-2 rounded" title="Edit">
                                        <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                    </a>
                                    <a href="<?= base_url('stok') ?>" class="btn btn-sm btn-info text-white py-1 px-2 rounded" title="Kartu Stok">
                                        <i class="fa-solid fa-boxes-stacked" style="font-size: 0.75rem;"></i>
                                    </a>
                                    <a href="<?= base_url('master/produk/delete/' . $p['id']) ?>" class="btn btn-sm btn-action-delete py-1 px-2 rounded" title="Hapus">
                                        <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. MOBILE VIEW: RESPONSIVE PRODUCT CARDS (VISIBLE ON SCREENS < 768px) -->
    <div class="card-body p-2 p-sm-3 d-block d-md-none bg-light bg-opacity-25" id="mobileProductCardsContainer">
        <?php if (empty($produk)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-box-open text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Produk</h6>
                <p class="text-muted small mb-0">Klik tombol Tambah Produk Baru untuk mulai menambahkan katalog.</p>
            </div>
        <?php else: ?>
            <div id="mobileCardsList">
                <?php foreach ($produk as $p): ?>
                    <?php
                        $stokFisik = (float)($p['stok_fisik'] ?? 0);
                        $stokMin   = (float)($p['stok_minimum'] ?? 0);
                        $stokBadge = 'bg-success-subtle text-success border-success-subtle';
                        $stokLabel = 'Stok: ' . number_format($stokFisik, 0);
                        if ($stokFisik <= 0) {
                            $stokBadge = 'bg-danger-subtle text-danger border-danger-subtle';
                            $stokLabel = 'Habis (0)';
                        } elseif ($stokFisik <= $stokMin) {
                            $stokBadge = 'bg-warning-subtle text-warning-emphasis border-warning-subtle';
                            $stokLabel = 'Kritis: ' . number_format($stokFisik, 0);
                        }
                    ?>
                    <div class="mobile-product-card shadow-xs mb-3 p-3 bg-white"
                         data-id="<?= $p['id'] ?>"
                         data-kategori="<?= esc($p['kategori_id'] ?? '') ?>"
                         data-search="<?= strtolower(esc($p['nama_produk'] . ' ' . $p['kode_produk'] . ' ' . ($p['barcode'] ?? '') . ' ' . ($p['nama_kategori'] ?? '') . ' ' . ($p['nama_satuan'] ?? ''))) ?>">
                        
                        <!-- Card Top Bar: SKU, Barcode, Kategori & Status -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2.5 border-bottom">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                <span class="font-monospace text-muted small fw-semibold px-2 py-0.5 bg-light rounded border text-truncate">
                                    <i class="fa-solid fa-barcode text-secondary me-1"></i><?= esc($p['kode_produk']) ?>
                                </span>
                                <?php if (!empty($p['barcode']) && $p['barcode'] !== '-'): ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border small px-2 py-0.5 rounded d-none d-sm-inline-block" title="Barcode: <?= esc($p['barcode']) ?>">
                                        <?= esc($p['barcode']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="badge rounded-pill px-2.5 py-1" style="background-color: #0284c7; color: white; font-size: 0.72rem;">
                                    <?= esc($p['nama_kategori'] ?? 'Umum') ?>
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                    AKTIF
                                </span>
                            </div>
                        </div>

                        <!-- Card Title: Nama Produk -->
                        <div class="mb-2.5">
                            <a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none fs-6 d-block line-clamp-2 btn-view-produk"
                                data-id="<?= $p['id'] ?>"
                                data-kode="<?= esc($p['kode_produk']) ?>"
                                data-barcode="<?= esc($p['barcode'] ?? '-') ?>"
                                data-nama="<?= esc($p['nama_produk']) ?>"
                                data-kategori="<?= esc($p['nama_kategori'] ?? 'Tanpa Kategori') ?>"
                                data-satuan="<?= esc($p['nama_satuan'] ?? 'pcs') ?>"
                                data-hargabeli="<?= format_rupiah($p['harga_beli']) ?>"
                                data-hargajual="<?= format_rupiah($p['harga_jual']) ?>"
                                data-hargagrosir="<?= !empty($p['harga_grosir']) ? format_rupiah($p['harga_grosir']) : '-' ?>"
                                data-hargadistributor="<?= !empty($p['harga_distributor']) ? format_rupiah($p['harga_distributor']) : '-' ?>"
                                data-stokminimum="<?= (int)($p['stok_minimum'] ?? 0) ?>"
                                data-stokfisik="<?= (int)($p['stok_fisik'] ?? 0) ?>"
                                data-status="<?= ($p['status'] ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif' ?>">
                                <?= esc($p['nama_produk']) ?>
                            </a>
                        </div>

                        <!-- Card Metrics: Pricing & Stock Row -->
                        <div class="row g-2 mb-2.5 py-2.5 px-3 rounded-3 bg-light bg-opacity-50 border align-items-center">
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Harga Jual Retail</span>
                                <span class="fw-bold font-monospace text-success fs-6"><?= format_rupiah($p['harga_jual']) ?></span>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">per <?= strtolower(esc($p['nama_satuan'] ?? 'pcs')) ?></small>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Harga Beli (HPP)</span>
                                <span class="font-monospace text-secondary small fw-semibold"><?= format_rupiah($p['harga_beli']) ?></span>
                                <div class="mt-1">
                                    <span class="badge border rounded-pill px-2 py-0.5 <?= $stokBadge ?>" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-boxes-stacked me-1"></i><?= $stokLabel ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons with Equal 25% Proportions & 8px Spacing -->
                        <div class="mobile-card-actions">
                            <button type="button" class="mobile-card-btn mobile-btn-detail btn-view-produk"
                                data-id="<?= $p['id'] ?>"
                                data-kode="<?= esc($p['kode_produk']) ?>"
                                data-barcode="<?= esc($p['barcode'] ?? '-') ?>"
                                data-nama="<?= esc($p['nama_produk']) ?>"
                                data-kategori="<?= esc($p['nama_kategori'] ?? 'Tanpa Kategori') ?>"
                                data-satuan="<?= esc($p['nama_satuan'] ?? 'pcs') ?>"
                                data-hargabeli="<?= format_rupiah($p['harga_beli']) ?>"
                                data-hargajual="<?= format_rupiah($p['harga_jual']) ?>"
                                data-hargagrosir="<?= !empty($p['harga_grosir']) ? format_rupiah($p['harga_grosir']) : '-' ?>"
                                data-hargadistributor="<?= !empty($p['harga_distributor']) ? format_rupiah($p['harga_distributor']) : '-' ?>"
                                data-stokminimum="<?= (int)($p['stok_minimum'] ?? 0) ?>"
                                data-stokfisik="<?= (int)($p['stok_fisik'] ?? 0) ?>"
                                data-status="<?= ($p['status'] ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif' ?>"
                                title="Lihat Detail Produk">
                                <i class="fa-solid fa-eye"></i>
                                <span>Detail</span>
                            </button>
                            <a href="<?= base_url('master/produk/edit/' . $p['id']) ?>" class="mobile-card-btn mobile-btn-edit" title="Edit Data Produk">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </a>
                            <a href="<?= base_url('stok') ?>" class="mobile-card-btn mobile-btn-stok" title="Cek Kartu Stok">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Stok</span>
                            </a>
                            <a href="<?= base_url('master/produk/delete/' . $p['id']) ?>" class="mobile-card-btn mobile-btn-delete" title="Hapus Produk">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty Search State -->
            <div id="mobileEmptyState" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Produk Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- MOBILE LOAD MORE / PROGRESSIVE PAGINATION -->
    <div id="mobilePaginationContainer" class="d-md-none p-3 text-center border-top bg-white">
        <div class="text-muted small mb-2">
            Menampilkan <span id="mobileVisibleCount" class="fw-bold text-dark">0</span> dari <span id="mobileTotalCount" class="fw-bold text-dark"><?= count($produk) ?></span> produk
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-1.5 fw-semibold shadow-xs" id="btnMobileLoadMore">
            <i class="fa-solid fa-arrow-down me-1"></i> Tampilkan Lebih Banyak (+10)
        </button>
    </div>

    <!-- CARD FOOTER (DESKTOP) -->
    <div class="card-footer bg-white py-3 text-muted small d-none d-md-block">
        Total: <strong><?= count($produk) ?> produk</strong> terdaftar dalam katalog
    </div>
</div>

<!-- MODAL DETAIL PRODUK (READ-ONLY) -->
<div class="modal fade" id="modalViewProduk" tabindex="-1" aria-labelledby="modalViewProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-light py-3 px-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalViewProdukLabel">
                    <i class="fa-solid fa-box-open text-primary me-2"></i>Rincian Detail Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <!-- Info Utama -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                    <div>
                        <div class="text-muted small">Nama Produk:</div>
                        <h4 class="fw-bold text-dark mb-0" id="viewNamaProduk">-</h4>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" id="viewKategoriProduk">-</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" id="viewStatusProduk">-</span>
                    </div>
                </div>

                <!-- Kode SKU & Barcode -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Kode SKU Produk:</label>
                        <div class="p-2 bg-light rounded font-monospace fw-bold text-dark border" id="viewKodeProduk">-</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Barcode / EAN-13:</label>
                        <div class="p-2 bg-light rounded font-monospace text-dark border d-flex align-items-center justify-content-between">
                            <span id="viewBarcodeProduk">-</span>
                            <i class="fa-solid fa-barcode text-secondary fs-5"></i>
                        </div>
                    </div>
                </div>

                <!-- Struktur Harga -->
                <div class="mb-3">
                    <label class="text-muted small fw-semibold text-uppercase mb-2 d-block">Struktur Harga (IDR)</label>
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center h-100">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Harga Beli (HPP)</small>
                                <span class="fw-bold font-monospace text-dark" id="viewHargaBeli">-</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-success bg-opacity-10 text-center h-100 border-success-subtle">
                                <small class="text-success fw-semibold d-block" style="font-size: 0.72rem;">Harga Jual Retail</small>
                                <span class="fw-bold font-monospace text-success" id="viewHargaJual">-</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center h-100">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Harga Grosir (Tier 2)</small>
                                <span class="fw-bold font-monospace text-dark" id="viewHargaGrosir">-</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center h-100">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Distributor (Tier 3)</small>
                                <span class="fw-bold font-monospace text-dark" id="viewHargaDistributor">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok & Satuan -->
                <div class="row g-3">
                    <div class="col-4">
                        <label class="text-muted small mb-1">Satuan Dasar:</label>
                        <div class="fw-semibold text-dark font-monospace" id="viewSatuanProduk">-</div>
                    </div>
                    <div class="col-4">
                        <label class="text-muted small mb-1">Stok Fisik Saat Ini:</label>
                        <div class="fw-bold text-primary font-monospace fs-6" id="viewStokFisik">0</div>
                    </div>
                    <div class="col-4">
                        <label class="text-muted small mb-1">Batas Stok Minimum:</label>
                        <div class="fw-semibold text-danger font-monospace" id="viewStokMinimum">0</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-warning btn-sm text-dark fw-semibold" id="viewBtnEditProduk">
                    <i class="fa-solid fa-pen-to-square me-1"></i>Edit Produk Ini
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let produkDataTable = null;

    function printTable() {
        if (!produkDataTable) {
            window.print();
            return;
        }

        const prevLen = produkDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        produkDataTable.page.len(-1).draw();
        
        let restored = false;
        const restorePage = function() {
            if (!restored) {
                restored = true;
                window.removeEventListener('afterprint', restorePage);
                produkDataTable.page.len(prevLen).draw();
            }
        };
        window.addEventListener('afterprint', restorePage);
        
        setTimeout(function() {
            window.print();
            // Fallback restore timeout jika browser tidak menembakkan afterprint
            setTimeout(restorePage, 2500);
        }, 350);
    }

    $(document).ready(function() {
        produkDataTable = $('#tableProduk').DataTable({
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        // Mobile Cards Progressive Loading & Search
        const mobilePageSize = 10;
        let mobileVisibleLimit = mobilePageSize;
        let activeSearchTerm = '';
        let activeKategoriId = '';

        function updateMobileCards() {
            let matchedCards = [];
            $('.mobile-product-card').each(function() {
                const searchData = $(this).data('search') || '';
                const itemKategori = String($(this).data('kategori') || '');
                const matchSearch = !activeSearchTerm || searchData.includes(activeSearchTerm);
                const matchCat = !activeKategoriId || itemKategori === activeKategoriId;

                if (matchSearch && matchCat) {
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

        // Search Handlers (Desktop & Mobile Unified)
        function triggerSearch(term) {
            activeSearchTerm = term.toLowerCase().trim();
            if (produkDataTable) {
                produkDataTable.search(term).draw();
            }
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        }

        $('#customSearchProduk').on('input', function() {
            triggerSearch(this.value);
        });

        $('#btnSearchProduk').on('click', function() {
            triggerSearch($('#customSearchProduk').val());
        });

        $('#filterKategoriProduk').on('change', function() {
            activeKategoriId = $(this).val();
            const catText = $(this).val() ? $(this).find('option:selected').text().trim() : '';
            if (produkDataTable) {
                produkDataTable.column(2).search(catText).draw();
            }
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        });

        $('#btnResetProduk').on('click', function() {
            $('#customSearchProduk').val('');
            $('#filterKategoriProduk').val('');
            activeSearchTerm = '';
            activeKategoriId = '';
            if (produkDataTable) {
                produkDataTable.search('').column(2).search('').draw();
            }
            mobileVisibleLimit = mobilePageSize;
            updateMobileCards();
        });

        // Initialize mobile cards visibility
        updateMobileCards();

        // Modal View Detail Handler (Works for both Table & Mobile Cards)
        $(document).on('click', '.btn-view-produk', function(e) {
            e.preventDefault();
            const btn = $(this);
            $('#viewNamaProduk').text(btn.data('nama'));
            $('#viewKategoriProduk').text(btn.data('kategori'));
            $('#viewStatusProduk').text(btn.data('status'));
            $('#viewKodeProduk').text(btn.data('kode'));
            $('#viewBarcodeProduk').text(btn.data('barcode'));
            $('#viewHargaBeli').text(btn.data('hargabeli'));
            $('#viewHargaJual').text(btn.data('hargajual'));
            $('#viewHargaGrosir').text(btn.data('hargagrosir'));
            $('#viewHargaDistributor').text(btn.data('hargadistributor'));
            $('#viewSatuanProduk').text(btn.data('satuan'));
            $('#viewStokFisik').text(btn.data('stokfisik') + ' ' + btn.data('satuan'));
            $('#viewStokMinimum').text(btn.data('stokminimum') + ' ' + btn.data('satuan'));
            $('#viewBtnEditProduk').attr('href', '<?= base_url('master/produk/edit') ?>/' + btn.data('id'));
            const modal = new bootstrap.Modal(document.getElementById('modalViewProduk'));
            modal.show();
        });
    });
</script>
<?= $this->endSection() ?>

