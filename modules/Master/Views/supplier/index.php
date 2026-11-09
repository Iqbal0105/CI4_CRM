<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Mobile Supplier Card Styles */
    .mobile-supplier-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-supplier-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-supplier-card:active {
        transform: scale(0.99);
    }

    /* Mobile Action Buttons with Balanced Proportions & 8px Spacing */
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
        text-decoration: none;
        white-space: nowrap;
        padding: 0 4px;
        transition: all 0.15s ease-in-out;
        box-shadow: none !important;
    }
    .mobile-card-btn i {
        font-size: 0.76rem;
        margin-right: 5px;
        flex-shrink: 0;
    }
    .mobile-card-btn:active {
        transform: scale(0.97);
    }
    
    /* Semantic soft themes */
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
    .mobile-btn-wa {
        background-color: #f0fdf4 !important;
        border: 1px solid #86efac !important;
        color: #15803d !important;
    }
    .mobile-btn-wa:hover, .mobile-btn-wa:active {
        background-color: #dcfce7 !important;
        border-color: #22c55e !important;
        color: #14532d !important;
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

    @media print {
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            padding: 10mm 8mm !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 10pt !important;
        }
        .d-print-none,
        .btn,
        .card-header,
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #mobileSupplierCardsContainer,
        #mobilePaginationContainer,
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
        #tableSupplier {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            visibility: visible !important;
        }
        #tableSupplier th,
        #tableSupplier td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableSupplier th:last-child,
        #tableSupplier td:last-child,
        #tableSupplier .d-print-none {
            display: none !important;
        }
        #tableSupplier th {
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

<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Master Supplier &amp; Kurs Valas</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Rekanan Pemasok &amp; Kurs Mata Uang Asing</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Master Data</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Supplier</span>
    </div>
</div>

<!-- KURS VALAS SUMMARY STRIP -->
<div class="card shadow-sm border mb-3 d-print-none">
    <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between gap-2">
        <div class="fw-bold text-dark small d-flex align-items-center gap-2 min-w-0">
            <i class="fa-solid fa-coins text-warning flex-shrink-0"></i>
            <span class="text-truncate">Nilai Kurs Valas Terkini <span class="d-none d-md-inline text-muted fw-normal">(Konversi ke IDR)</span></span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <form action="<?= base_url('master/kurs/sync-api') ?>" method="post" class="d-inline" id="formSyncKursApi">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary btn-sm py-1.5 px-2.5 d-inline-flex align-items-center justify-content-center shadow-xs text-nowrap" id="btnSyncKursApi" title="Tarik Kurs Otomatis (API Live)" style="font-size: 0.775rem; height: 34px; border-radius: 8px;">
                    <i class="fa-solid fa-arrows-rotate me-2" id="syncSpinner"></i>
                    <span class="d-none d-sm-inline fw-semibold">Tarik Kurs Otomatis (API Live)</span>
                </button>
            </form>
            <button type="button" class="btn btn-outline-success btn-sm py-1.5 px-2.5 d-inline-flex align-items-center justify-content-center shadow-xs text-nowrap" data-bs-toggle="modal" data-bs-target="#modalKurs" title="Input Manual Kurs" style="font-size: 0.775rem; height: 34px; border-radius: 8px;">
                <i class="fa-solid fa-pen-to-square me-2"></i>
                <span class="d-none d-sm-inline fw-semibold">Input Manual Kurs</span>
            </button>
        </div>
    </div>
    <div class="card-body p-3 bg-light bg-opacity-25">
        <div class="row g-2">
            <?php
                $currencies = [
                    'USD' => ['label' => 'United States Dollar', 'symbol' => '$', 'color' => 'success'],
                    'SGD' => ['label' => 'Singapore Dollar', 'symbol' => 'S$', 'color' => 'primary'],
                    'CNY' => ['label' => 'Chinese Yuan', 'symbol' => '¥', 'color' => 'info'],
                    'EUR' => ['label' => 'Euro', 'symbol' => '€', 'color' => 'secondary'],
                ];
            ?>
            <?php foreach ($currencies as $curr => $meta): ?>
                <?php
                    $rateData = $latestRates[$curr] ?? null;
                    $rateVal = $rateData ? (float)$rateData['nilai_kurs'] : null;
                    $rateDate = $rateData ? date('d/m/Y', strtotime($rateData['tanggal'])) : '-';
                ?>
                <div class="col-6 col-md-3">
                    <div class="p-2 p-sm-3 bg-white rounded border h-100 shadow-2xs">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="badge bg-<?= $meta['color'] ?>-subtle text-<?= $meta['color'] ?> border border-<?= $meta['color'] ?>-subtle fw-bold font-monospace">
                                1 <?= $curr ?> (<?= $meta['symbol'] ?>)
                            </span>
                            <small class="text-muted" style="font-size: 0.7rem;"><?= $rateDate ?></small>
                        </div>
                        <div class="fs-6 fw-bold font-monospace text-dark">
                            <?php if ($rateVal): ?>
                                Rp <?= number_format($rateVal, 2, ',', '.') ?>
                            <?php else: ?>
                                <span class="text-muted fw-normal small">Belum diinput</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;"><?= $meta['label'] ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- SUPPLIER TABLE CARD -->
<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white py-3 d-print-none">
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-3">
            <!-- Header Title & Badge -->
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-truck-moving text-success"></i>
                    <span>Daftar Rekanan Pemasok (Supplier)</span>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill flex-shrink-0" style="font-size: 0.75rem;">
                    <?= count($supplier) ?> Pemasok
                </span>
            </div>

            <!-- Header Action Buttons (Proportional Grid on Mobile, Flex on Desktop) -->
            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                <!-- Kurs Utility Buttons -->
                <div class="d-flex align-items-center gap-2 w-100 w-sm-auto">
                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center shadow-xs text-nowrap px-2.5" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" onclick="document.getElementById('formSyncKursApi').requestSubmit();" title="Tarik Kurs Otomatis via API Live">
                        <i class="fa-solid fa-arrows-rotate me-2" style="font-size: 0.78rem;"></i>
                        <span>Tarik Kurs API</span>
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center shadow-xs text-nowrap px-2.5" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalKurs" title="Update Kurs Manual">
                        <i class="fa-solid fa-coins me-2" style="font-size: 0.78rem;"></i>
                        <span>Update Kurs</span>
                    </button>
                </div>

                <!-- CRUD & Export Buttons -->
                <div class="d-flex align-items-center gap-2 w-100 w-sm-auto">
                    <button type="button" class="btn btn-success btn-sm flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center shadow-xs px-3 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalSupplier">
                        <i class="fa-solid fa-plus me-2" style="font-size: 0.78rem;"></i>
                        <span>Tambah Supplier</span>
                    </button>
                    <a href="<?= base_url('master/supplier/export') ?>" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" title="Export Excel (XLS)">
                        <i class="fa-solid fa-file-excel me-2" style="font-size: 0.82rem;"></i>
                        <span class="d-none d-sm-inline me-1">Export</span><span>XLS</span>
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" onclick="printTable()" title="Export / Cetak PDF">
                        <i class="fa-solid fa-file-pdf me-2" style="font-size: 0.82rem;"></i>
                        <span class="d-none d-sm-inline me-1">Export</span><span>PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5 col-lg-4">
                <div class="input-group input-group-sm shadow-2xs rounded-3 overflow-hidden border bg-white" style="height: 38px;">
                    <button class="btn btn-white bg-white border-0 text-muted ps-3 pe-1 shadow-none" type="button" id="btnSearchSupplier" title="Cari Supplier">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <input type="text" id="customSearchSupplier" class="form-control border-0 ps-1 shadow-none" placeholder="Cari kode, nama, atau no telp supplier..." style="font-size: 0.84rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-2.5 shadow-none" type="button" id="btnResetSupplier" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-7 col-lg-8 text-md-end d-none d-sm-block">
                <span class="small text-muted">
                    Total: <strong class="text-dark"><?= count($supplier) ?></strong> supplier terdaftar
                </span>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: DATA TABLE (HIDDEN ON MOBILE) -->
    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableSupplier">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 130px;">Kode Supplier</th>
                        <th>Nama Pemasok / Vendor</th>
                        <th style="width: 160px;">No. Telepon / WA</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th class="text-center" style="width: 100px;">Mata Uang</th>
                        <th class="text-center" style="width: 90px;">Status</th>
                        <th class="text-center d-print-none" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($supplier)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-truck-moving fs-3 mb-1 d-block text-secondary opacity-50"></i>
                                Belum ada data supplier yang tersimpan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($supplier as $s): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.78rem;">
                                        <?= esc($s['kode_supplier']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($s['nama_supplier']) ?></div>
                                </td>
                                <td>
                                    <span class="small font-monospace">
                                        <i class="fa-brands fa-whatsapp text-success me-1"></i><?= esc($s['no_telp']) ?>
                                    </span>
                                </td>
                                <td><span class="small text-muted"><?= esc($s['email'] ?? '-') ?></span></td>
                                <td class="small text-muted text-truncate" style="max-width: 200px;" title="<?= esc($s['alamat'] ?? '-') ?>">
                                    <?= esc($s['alamat'] ?? '-') ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2 py-1 font-monospace" style="background-color: #2D6A4F; color: white;">
                                        <?= esc($s['mata_uang']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        Aktif
                                    </span>
                                </td>
                                <td class="text-center d-print-none">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-action-view py-1 px-2 rounded btn-view-supplier" 
                                            data-kode="<?= esc($s['kode_supplier']) ?>" 
                                            data-nama="<?= esc($s['nama_supplier']) ?>" 
                                            data-telp="<?= esc($s['no_telp']) ?>" 
                                            data-email="<?= esc($s['email'] ?? '-') ?>" 
                                            data-alamat="<?= esc($s['alamat'] ?? '-') ?>" 
                                            data-matauang="<?= esc($s['mata_uang']) ?>" 
                                            title="Lihat Detail">
                                            <i class="fa-solid fa-eye" style="font-size: 0.75rem;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-action-edit py-1 px-2 rounded btn-edit-supplier" 
                                            data-id="<?= $s['id'] ?>" 
                                            data-kode="<?= esc($s['kode_supplier']) ?>" 
                                            data-nama="<?= esc($s['nama_supplier']) ?>" 
                                            data-telp="<?= esc($s['no_telp']) ?>" 
                                            data-email="<?= esc($s['email'] ?? '') ?>" 
                                            data-alamat="<?= esc($s['alamat'] ?? '') ?>" 
                                            data-matauang="<?= esc($s['mata_uang']) ?>" 
                                            title="Edit Data">
                                            <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                        </button>
                                        <a href="<?= base_url('master/supplier/delete/' . $s['id']) ?>" 
                                           class="btn btn-sm btn-action-delete py-1 px-2 rounded" 
                                           title="Hapus Supplier">
                                            <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
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

    <!-- 2. MOBILE VIEW: RESPONSIVE SUPPLIER CARDS (VISIBLE ON SCREENS < 768px) -->
    <div class="card-body p-2 p-sm-3 d-block d-md-none bg-light bg-opacity-25" id="mobileSupplierCardsContainer">
        <?php if (empty($supplier)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-truck-moving text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Supplier</h6>
                <p class="text-muted small mb-0">Klik tombol Tambah Supplier Baru untuk mendaftarkan rekanan pemasok.</p>
            </div>
        <?php else: ?>
            <div id="mobileSupplierCardsList">
                <?php foreach ($supplier as $s): ?>
                    <?php 
                        $cleanPhone = preg_replace('/[^0-9]/', '', $s['no_telp'] ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    ?>
                    <div class="mobile-supplier-card shadow-xs mb-3 p-3 bg-white"
                         data-id="<?= $s['id'] ?>"
                         data-search="<?= strtolower(esc($s['nama_supplier'] . ' ' . $s['kode_supplier'] . ' ' . ($s['no_telp'] ?? '') . ' ' . ($s['email'] ?? '') . ' ' . ($s['mata_uang'] ?? '') . ' ' . ($s['alamat'] ?? ''))) ?>">
                        
                        <!-- Top Bar: Kode, Mata Uang & Status -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2.5 border-bottom">
                            <span class="font-monospace text-muted small fw-semibold px-2 py-0.5 bg-light rounded border text-truncate">
                                <i class="fa-solid fa-truck text-secondary me-1"></i><?= esc($s['kode_supplier']) ?>
                            </span>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="badge px-2.5 py-1 font-monospace rounded-pill" style="background-color: #2D6A4F; color: white; font-size: 0.72rem;">
                                    <?= esc($s['mata_uang']) ?>
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                    Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Nama Supplier (Clickable to view modal) -->
                        <div class="mb-2.5">
                            <a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none fs-6 d-block text-truncate btn-view-supplier"
                                data-kode="<?= esc($s['kode_supplier']) ?>" 
                                data-nama="<?= esc($s['nama_supplier']) ?>" 
                                data-telp="<?= esc($s['no_telp']) ?>" 
                                data-email="<?= esc($s['email'] ?? '-') ?>" 
                                data-alamat="<?= esc($s['alamat'] ?? '-') ?>" 
                                data-matauang="<?= esc($s['mata_uang']) ?>">
                                <?= esc($s['nama_supplier']) ?>
                            </a>
                        </div>

                        <!-- Info Strip: Kontak & Alamat -->
                        <div class="row g-2 mb-2.5 py-2.5 px-3 rounded-3 bg-light bg-opacity-50 border align-items-center">
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Telepon / Kontak</span>
                                <span class="fw-semibold font-monospace text-dark small d-block text-truncate">
                                    <i class="fa-solid fa-phone text-success me-1"></i><?= esc($s['no_telp']) ?>
                                </span>
                                <small class="text-muted d-block text-truncate" style="font-size: 0.7rem;"><?= esc($s['email'] ?? '-') ?></small>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Alamat Rekanan</span>
                                <span class="small text-secondary d-block text-truncate" title="<?= esc($s['alamat'] ?? '-') ?>">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i><?= !empty($s['alamat']) ? esc($s['alamat']) : '-' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="mobile-card-actions">
                            <button type="button" class="mobile-card-btn mobile-btn-detail btn-view-supplier"
                                data-kode="<?= esc($s['kode_supplier']) ?>" 
                                data-nama="<?= esc($s['nama_supplier']) ?>" 
                                data-telp="<?= esc($s['no_telp']) ?>" 
                                data-email="<?= esc($s['email'] ?? '-') ?>" 
                                data-alamat="<?= esc($s['alamat'] ?? '-') ?>" 
                                data-matauang="<?= esc($s['mata_uang']) ?>"
                                title="Lihat Detail Supplier">
                                <i class="fa-solid fa-eye"></i>
                                <span>Detail</span>
                            </button>
                            <button type="button" class="mobile-card-btn mobile-btn-edit btn-edit-supplier"
                                data-id="<?= $s['id'] ?>" 
                                data-kode="<?= esc($s['kode_supplier']) ?>" 
                                data-nama="<?= esc($s['nama_supplier']) ?>" 
                                data-telp="<?= esc($s['no_telp']) ?>" 
                                data-email="<?= esc($s['email'] ?? '') ?>" 
                                data-alamat="<?= esc($s['alamat'] ?? '') ?>" 
                                data-matauang="<?= esc($s['mata_uang']) ?>"
                                title="Edit Data Supplier">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </button>
                            <?php if (!empty($cleanPhone)): ?>
                                <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="mobile-card-btn mobile-btn-wa" title="Hubungi via WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WA</span>
                                </a>
                            <?php else: ?>
                                <button type="button" class="mobile-card-btn mobile-btn-wa opacity-50" disabled title="No telepon tidak tersedia">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WA</span>
                                </button>
                            <?php endif; ?>
                            <a href="<?= base_url('master/supplier/delete/' . $s['id']) ?>" class="mobile-card-btn mobile-btn-delete" title="Hapus Supplier">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty State -->
            <div id="mobileSupplierEmptyState" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Supplier Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian supplier.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- MOBILE LOAD MORE / PROGRESSIVE PAGINATION -->
    <div id="mobilePaginationContainer" class="d-md-none p-3 text-center border-top bg-white">
        <div class="text-muted small mb-2">
            Menampilkan <span id="mobileVisibleCount" class="fw-bold text-dark">0</span> dari <span id="mobileTotalCount" class="fw-bold text-dark"><?= count($supplier) ?></span> supplier
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-1.5 fw-semibold shadow-xs" id="btnMobileLoadMore">
            <i class="fa-solid fa-arrow-down me-1"></i> Tampilkan Lebih Banyak (+10)
        </button>
    </div>

    <!-- CARD FOOTER (DESKTOP) -->
    <div class="card-footer bg-white py-3 text-muted small d-none d-md-flex align-items-center justify-content-between">
        <div>Total: <strong><?= count($supplier) ?> Supplier Terdaftar</strong></div>
        <div class="text-muted">Data tersimpan di database MySQL</div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL UPDATE KURS VALAS (CENTERED & PROPER HEADER)      -->
<!-- ======================================================== -->
<div class="modal fade" id="modalKurs" tabindex="-1" aria-labelledby="modalKursLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('master/kurs/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalKursLabel">
                        <i class="fa-solid fa-coins me-2"></i>Update Nilai Kurs Valas ke IDR
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="alert alert-primary bg-primary-subtle border-primary-subtle py-2 px-3 small d-flex align-items-center justify-content-between mb-3 rounded">
                        <div class="me-2 text-primary">
                            <i class="fa-solid fa-cloud-arrow-down me-1"></i>
                            <span>Tersedia sinkronisasi otomatis harian via ExchangeRate Open API resmi (Akurat &amp; Gratis tanpa API key).</span>
                        </div>
                        <button type="button" class="btn btn-primary btn-xs flex-shrink-0" onclick="document.getElementById('formSyncKursApi').requestSubmit();">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>Tarik dari API
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Mata Uang Asing <span class="text-danger">*</span></label>
                        <select name="mata_uang" class="form-select form-select-sm" required>
                            <option value="USD">USD - United States Dollar ($)</option>
                            <option value="SGD">SGD - Singapore Dollar (S$)</option>
                            <option value="CNY">CNY - Chinese Yuan (¥)</option>
                            <option value="EUR">EUR - Euro (€)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nilai Kurs ke IDR (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted fw-bold">Rp</span>
                            <input type="number" step="0.01" min="1" name="nilai_kurs" class="form-control font-monospace" placeholder="16250.00" required>
                        </div>
                        <small class="text-muted">Contoh: 1 USD = 16250.00 IDR</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Tanggal Efektif Kurs <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Kurs ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL TAMBAH SUPPLIER (CENTERED & PROPER HEADER)         -->
<!-- ======================================================== -->
<div class="modal fade" id="modalSupplier" tabindex="-1" aria-labelledby="modalSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('master/supplier/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalSupplierLabel">
                        <i class="fa-solid fa-truck-moving me-2"></i>Tambah Rekanan Supplier Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="kode_supplier" class="form-control form-control-sm font-monospace text-uppercase" value="SUP-<?= date('ymd') ?>-<?= rand(10, 99) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Supplier / Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" class="form-control form-control-sm" placeholder="Contoh: PT Sumber Distribusi Nusantara" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="no_telp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="supplier@domain.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Mata Uang Transaksi Default <span class="text-danger">*</span></label>
                        <select name="mata_uang" class="form-select form-select-sm" required>
                            <option value="IDR" selected>IDR (Rupiah Indonesia)</option>
                            <option value="USD">USD (US Dollar)</option>
                            <option value="SGD">SGD (Singapore Dollar)</option>
                            <option value="CNY">CNY (Chinese Yuan)</option>
                            <option value="EUR">EUR (Euro)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control form-control-sm" rows="2" placeholder="Alamat kantor / toko supplier..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                        <i class="fa-solid fa-check me-1"></i>Simpan Supplier ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL DETAIL SUPPLIER (CENTERED)                         -->
<!-- ======================================================== -->
<div class="modal fade" id="modalViewSupplier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light py-3 px-4">
                <h5 class="modal-title fw-bold text-dark mb-0">
                    <i class="fa-solid fa-building me-2 text-success"></i>Detail Rekanan Supplier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="mb-3">
                    <label class="text-muted small">Kode Supplier:</label>
                    <div class="font-monospace fw-bold text-success fs-6" id="viewKodeSupplier">-</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nama Supplier / Vendor:</label>
                    <div class="fw-bold fs-6 text-dark" id="viewNamaSupplier">-</div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="text-muted small">Telepon / WhatsApp:</label>
                        <div class="font-monospace text-dark" id="viewTelpSupplier">-</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small">Mata Uang:</label>
                        <div><span class="badge bg-success px-2 py-1 font-monospace" id="viewMataUangSupplier">-</span></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email:</label>
                    <div class="text-dark" id="viewEmailSupplier">-</div>
                </div>
                <div>
                    <label class="text-muted small">Alamat:</label>
                    <div class="p-2 bg-light rounded text-dark small" id="viewAlamatSupplier">-</div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL EDIT SUPPLIER (CENTERED)                           -->
<!-- ======================================================== -->
<div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditSupplier" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Data Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="kode_supplier" id="editKodeSupplier" class="form-control form-control-sm font-monospace text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Supplier / Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" id="editNamaSupplier" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="no_telp" id="editTelpSupplier" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email</label>
                        <input type="email" name="email" id="editEmailSupplier" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Mata Uang Default <span class="text-danger">*</span></label>
                        <select name="mata_uang" id="editMataUangSupplier" class="form-select form-select-sm" required>
                            <option value="IDR">IDR (Rupiah Indonesia)</option>
                            <option value="USD">USD (US Dollar)</option>
                            <option value="SGD">SGD (Singapore Dollar)</option>
                            <option value="CNY">CNY (Chinese Yuan)</option>
                            <option value="EUR">EUR (Euro)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Alamat Lengkap</label>
                        <textarea name="alamat" id="editAlamatSupplier" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let supplierDataTable = null;

    function printTable() {
        if (!supplierDataTable) {
            window.print();
            return;
        }

        const prevLen = supplierDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        supplierDataTable.page.len(-1).draw();
        
        let restored = false;
        const restorePage = function() {
            if (!restored) {
                restored = true;
                window.removeEventListener('afterprint', restorePage);
                supplierDataTable.page.len(prevLen).draw();
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
        supplierDataTable = $('#tableSupplier').DataTable({
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ supplier",
                emptyTable: "Belum ada data supplier di database."
            }
        });

        $('#btnSearchSupplier').on('click', function() {
            supplierDataTable.search($('#customSearchSupplier').val()).draw();
        });

        $('#customSearchSupplier').on('keyup', function(e) {
            if (e.key === 'Enter') {
                supplierDataTable.search(this.value).draw();
            }
        });

        $('#btnResetSupplier').on('click', function() {
            $('#customSearchSupplier').val('');
            supplierDataTable.search('').draw();
            mobileVisibleLimit = 10;
            updateMobileSupplierCards();
        });

        // Mobile Cards Progressive Display & Live Search
        const mobileCards = $('.mobile-supplier-card');
        let mobileVisibleLimit = 10;

        function updateMobileSupplierCards() {
            const query = ($('#customSearchSupplier').val() || '').toLowerCase().trim();

            let matchCount = 0;
            let visibleCount = 0;

            mobileCards.each(function() {
                const card = $(this);
                const searchData = (card.data('search') || '').toString();

                let matchesSearch = !query || searchData.indexOf(query) !== -1;

                if (matchesSearch) {
                    matchCount++;
                    if (matchCount <= mobileVisibleLimit) {
                        card.removeClass('d-none');
                        visibleCount++;
                    } else {
                        card.addClass('d-none');
                    }
                } else {
                    card.addClass('d-none');
                }
            });

            $('#mobileVisibleCount').text(visibleCount);
            $('#mobileTotalCount').text(matchCount);

            if (matchCount === 0) {
                $('#mobileSupplierEmptyState').removeClass('d-none');
                $('#mobilePaginationContainer').addClass('d-none');
            } else {
                $('#mobileSupplierEmptyState').addClass('d-none');
                if (visibleCount < matchCount) {
                    $('#mobilePaginationContainer').removeClass('d-none');
                } else {
                    $('#mobilePaginationContainer').addClass('d-none');
                }
            }
        }

        $('#btnMobileLoadMore').on('click', function() {
            mobileVisibleLimit += 10;
            updateMobileSupplierCards();
        });

        $('#customSearchSupplier').on('input keyup', function() {
            mobileVisibleLimit = 10;
            updateMobileSupplierCards();
        });

        updateMobileSupplierCards();

        $(document).on('click', '.btn-view-supplier', function() {
            const btn = $(this);
            $('#viewKodeSupplier').text(btn.data('kode'));
            $('#viewNamaSupplier').text(btn.data('nama'));
            $('#viewTelpSupplier').text(btn.data('telp'));
            $('#viewEmailSupplier').text(btn.data('email'));
            $('#viewMataUangSupplier').text(btn.data('matauang'));
            $('#viewAlamatSupplier').text(btn.data('alamat'));
            const modal = new bootstrap.Modal(document.getElementById('modalViewSupplier'));
            modal.show();
        });

        $(document).on('click', '.btn-edit-supplier', function() {
            const btn = $(this);
            const id = btn.data('id');
            $('#formEditSupplier').attr('action', '<?= base_url('master/supplier/update') ?>/' + id);
            $('#editKodeSupplier').val(btn.data('kode'));
            $('#editNamaSupplier').val(btn.data('nama'));
            $('#editTelpSupplier').val(btn.data('telp'));
            $('#editEmailSupplier').val(btn.data('email'));
            $('#editMataUangSupplier').val(btn.data('matauang'));
            $('#editAlamatSupplier').val(btn.data('alamat'));
            const modal = new bootstrap.Modal(document.getElementById('modalEditSupplier'));
            modal.show();
        });

        $('#formSyncKursApi').on('submit', function() {
            $('#syncSpinner').addClass('fa-spin');
            $('#btnSyncKursApi').prop('disabled', true).find('span').text('Menghubungi API Kurs...');
        });
    });
</script>
<?= $this->endSection() ?>
