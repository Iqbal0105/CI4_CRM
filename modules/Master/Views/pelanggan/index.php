<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Reset dropdown item delete styling agar tidak keblok merah */
    .dropdown-menu .dropdown-item.text-danger {
        color: #dc3545 !important;
        background-color: transparent !important;
    }
    .dropdown-menu .dropdown-item.text-danger:hover,
    .dropdown-menu .dropdown-item.text-danger:focus {
        color: #b02a37 !important;
        background-color: #fee2e2 !important;
    }

    /* Mobile Pelanggan Card Styles */
    .mobile-pelanggan-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-pelanggan-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-pelanggan-card:active {
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
        #mobilePelangganCardsContainer,
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
        #tablePelanggan {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            visibility: visible !important;
        }
        #tablePelanggan th,
        #tablePelanggan td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tablePelanggan th:last-child,
        #tablePelanggan td:last-child,
        #tablePelanggan .d-print-none {
            display: none !important;
        }
        #tablePelanggan th {
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
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
    }
</style>

<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Data Pelanggan</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Manajemen Data Pelanggan Terdaftar</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Master Data</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Pelanggan</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (MASTER DATA) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('master/produk') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-cubes me-1"></i> Produk &amp; Katalog
    </a>
    <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
        <i class="fa-solid fa-users me-1"></i> Data Pelanggan
    </a>
    <a href="<?= base_url('master/supplier') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-truck me-1"></i> Data Supplier
    </a>
    <a href="<?= base_url('master/kategori') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-tag me-1"></i> Kategori Barang
    </a>
    <a href="<?= base_url('master/satuan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-scale-balanced me-1"></i> Unit / Satuan
    </a>
    <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-brands fa-whatsapp me-1"></i> Broadcast WA
    </a>
</div>

<div class="card shadow-sm border">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-address-book text-primary d-print-none"></i>
            <span>Daftar Master Pelanggan</span>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;"><?= count($pelanggan) ?> Pelanggan</span>
        </div>
        <div class="d-flex flex-row align-items-center gap-2 d-print-none w-100 w-sm-auto">
            <a href="<?= base_url('master/pelanggan/create') ?>" class="btn btn-success btn-sm flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-3 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;">
                <i class="fa-solid fa-plus" style="font-size: 0.78rem;"></i>
                <span>Tambah Pelanggan</span>
            </a>
            <a href="<?= base_url('master/pelanggan/export') ?>" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" title="Export Excel (XLS)">
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
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm shadow-2xs rounded-3 overflow-hidden border bg-white" style="height: 38px;">
                    <button class="btn btn-white bg-white border-0 text-muted ps-3 pe-1 shadow-none" type="button" id="btnSearch" title="Cari Pelanggan">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <input type="text" id="customSearch" class="form-control border-0 ps-1 shadow-none" placeholder="Cari Kode, Nama, Kota, Telepon..." style="font-size: 0.84rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-2.5 shadow-none" type="button" id="btnReset" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select form-select-sm" id="filterKriteria">
                    <option value="">-- Semua Kriteria --</option>
                    <option value="retail">Retail / Reguler</option>
                    <option value="grosir">Grosir</option>
                    <option value="distributor">Distributor</option>
                </select>
            </div>
            <div class="col-6 col-md-4">
                <select class="form-select form-select-sm" id="filterChannel">
                    <option value="">-- Semua Kategori Channel --</option>
                    <option value="Regular">Toko / Regular</option>
                    <option value="Shopee">Shopee</option>
                    <option value="TikTok">TikTok Shop</option>
                    <option value="Tokopedia">Tokopedia</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: DATA TABLE (HIDDEN ON MOBILE) -->
    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePelanggan">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 45px;">No</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>Kategori</th>
                        <th>No. Telepon</th>
                        <th>Kota</th>
                        <th class="text-end">Limit Kredit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center d-print-none" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($pelanggan as $p): ?>
                        <?php 
                            $cleanPhone = preg_replace('/[^0-9]/', '', $p['no_telp'] ?? '');
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        ?>
                        <tr>
                            <td class="text-muted small"><?= $no++ ?></td>
                            <td><span class="font-monospace text-muted small"><?= esc($p['kode_pelanggan']) ?></span></td>
                            <td>
                                <a href="<?= base_url('master/pelanggan/edit/' . $p['id']) ?>" class="fw-semibold text-primary text-decoration-none">
                                    <?= esc($p['nama_pelanggan']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($p['tipe_pelanggan'] === 'distributor'): ?>
                                    <span class="badge bg-dark bg-opacity-75 text-white fw-normal"># TikTok Shop</span>
                                <?php elseif ($p['tipe_pelanggan'] === 'grosir'): ?>
                                    <span class="badge bg-danger text-white fw-normal"># Shopee</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border fw-normal"># Toko / Regular</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span class="small font-monospace"><?= esc($p['no_telp']) ?></span>
                                    <?php if (!empty($cleanPhone)): ?>
                                        <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="badge bg-success text-white text-decoration-none px-2 py-1 d-print-none" style="font-size: 0.78rem;">
                                            <i class="fa-brands fa-whatsapp me-1"></i>Chat WA
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="small text-secondary"><?= esc($p['kota'] ?? '-') ?></td>
                            <td class="text-end font-monospace small"><?= format_rupiah($p['limit_kredit']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.75rem;">Aktif</span>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2 btn-view-pelanggan" style="font-size: 0.775rem;"
                                        data-id="<?= $p['id'] ?>"
                                        data-kode="<?= esc($p['kode_pelanggan']) ?>"
                                        data-nama="<?= esc($p['nama_pelanggan']) ?>"
                                        data-telp="<?= esc($p['no_telp'] ?? '-') ?>"
                                        data-cleanphone="<?= $cleanPhone ?>"
                                        data-email="<?= esc($p['email'] ?? '-') ?>"
                                        data-kota="<?= esc($p['kota'] ?? '-') ?>"
                                        data-tipe="<?= esc($p['tipe_pelanggan']) ?>"
                                        data-limit="<?= format_rupiah($p['limit_kredit']) ?>"
                                        data-tempo="<?= (int)($p['tempo_hari'] ?? 0) ?>"
                                        data-alamat="<?= esc($p['alamat'] ?? '-') ?>"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye me-1"></i>Detail
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm small">
                                        <li><a class="dropdown-item" href="<?= base_url('master/pelanggan/edit/' . $p['id']) ?>"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Data</a></li>
                                        <?php if ($p['kode_pelanggan'] !== 'CUST-0001'): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="<?= base_url('master/pelanggan/delete/' . $p['id']) ?>" data-action="delete"><i class="fa-solid fa-trash me-2"></i>Hapus</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. MOBILE VIEW: RESPONSIVE PELANGGAN CARDS (VISIBLE ON SCREENS < 768px) -->
    <div class="card-body p-2 p-sm-3 d-block d-md-none bg-light bg-opacity-25" id="mobilePelangganCardsContainer">
        <?php if (empty($pelanggan)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-users text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Pelanggan</h6>
                <p class="text-muted small mb-0">Klik tombol Tambah Pelanggan untuk mulai mencatat pelanggan baru.</p>
            </div>
        <?php else: ?>
            <div id="mobileCardsList">
                <?php foreach ($pelanggan as $p): ?>
                    <?php 
                        $cleanPhone = preg_replace('/[^0-9]/', '', $p['no_telp'] ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        
                        $channelBadge = 'bg-secondary-subtle text-secondary border';
                        $channelLabel = '# Toko / Regular';
                        if ($p['tipe_pelanggan'] === 'distributor') {
                            $channelBadge = 'bg-dark bg-opacity-75 text-white';
                            $channelLabel = '# TikTok Shop';
                        } elseif ($p['tipe_pelanggan'] === 'grosir') {
                            $channelBadge = 'bg-danger text-white';
                            $channelLabel = '# Shopee';
                        }
                    ?>
                    <div class="mobile-pelanggan-card shadow-xs mb-3 p-3 bg-white"
                         data-id="<?= $p['id'] ?>"
                         data-tipe="<?= esc($p['tipe_pelanggan']) ?>"
                         data-search="<?= strtolower(esc($p['nama_pelanggan'] . ' ' . $p['kode_pelanggan'] . ' ' . ($p['no_telp'] ?? '') . ' ' . ($p['kota'] ?? '') . ' ' . $p['tipe_pelanggan'])) ?>">
                        
                        <!-- Top Bar: Kode & Badge Status -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2.5 border-bottom">
                            <span class="font-monospace text-muted small fw-semibold px-2 py-0.5 bg-light rounded border text-truncate">
                                <i class="fa-solid fa-address-card text-secondary me-1"></i><?= esc($p['kode_pelanggan']) ?>
                            </span>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="badge rounded-pill px-2.5 py-1 <?= $channelBadge ?>" style="font-size: 0.72rem;">
                                    <?= $channelLabel ?>
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                    Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Nama Pelanggan (Clickable) -->
                        <div class="mb-2.5">
                            <a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none fs-6 d-block text-truncate btn-view-pelanggan"
                                data-id="<?= $p['id'] ?>"
                                data-kode="<?= esc($p['kode_pelanggan']) ?>"
                                data-nama="<?= esc($p['nama_pelanggan']) ?>"
                                data-telp="<?= esc($p['no_telp'] ?? '-') ?>"
                                data-cleanphone="<?= $cleanPhone ?>"
                                data-email="<?= esc($p['email'] ?? '-') ?>"
                                data-kota="<?= esc($p['kota'] ?? '-') ?>"
                                data-tipe="<?= esc($p['tipe_pelanggan']) ?>"
                                data-limit="<?= format_rupiah($p['limit_kredit']) ?>"
                                data-tempo="<?= (int)($p['tempo_hari'] ?? 0) ?>"
                                data-alamat="<?= esc($p['alamat'] ?? '-') ?>">
                                <?= esc($p['nama_pelanggan']) ?>
                            </a>
                        </div>

                        <!-- Info Strip: Limit, Tempo, Kota, Telepon -->
                        <div class="row g-2 mb-2.5 py-2.5 px-3 rounded-3 bg-light bg-opacity-50 border align-items-center">
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Limit Piutang (Kredit)</span>
                                <span class="fw-bold font-monospace text-success fs-6"><?= format_rupiah($p['limit_kredit']) ?></span>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Tempo: <strong><?= (int)($p['tempo_hari'] ?? 0) ?> Hari</strong></small>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.02em;">Kota / Domisili</span>
                                <span class="fw-semibold text-dark small d-block text-truncate"><i class="fa-solid fa-location-dot text-danger me-1"></i><?= esc($p['kota'] ?? '-') ?></span>
                                <span class="font-monospace text-secondary small" style="font-size: 0.72rem;"><?= esc($p['no_telp'] ?? '-') ?></span>
                            </div>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="mobile-card-actions">
                            <button type="button" class="mobile-card-btn mobile-btn-detail btn-view-pelanggan"
                                data-id="<?= $p['id'] ?>"
                                data-kode="<?= esc($p['kode_pelanggan']) ?>"
                                data-nama="<?= esc($p['nama_pelanggan']) ?>"
                                data-telp="<?= esc($p['no_telp'] ?? '-') ?>"
                                data-cleanphone="<?= $cleanPhone ?>"
                                data-email="<?= esc($p['email'] ?? '-') ?>"
                                data-kota="<?= esc($p['kota'] ?? '-') ?>"
                                data-tipe="<?= esc($p['tipe_pelanggan']) ?>"
                                data-limit="<?= format_rupiah($p['limit_kredit']) ?>"
                                data-tempo="<?= (int)($p['tempo_hari'] ?? 0) ?>"
                                data-alamat="<?= esc($p['alamat'] ?? '-') ?>"
                                title="Lihat Profil Detail">
                                <i class="fa-solid fa-eye"></i>
                                <span>Detail</span>
                            </button>
                            <a href="<?= base_url('master/pelanggan/edit/' . $p['id']) ?>" class="mobile-card-btn mobile-btn-edit" title="Edit Data Pelanggan">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </a>
                            <?php if (!empty($cleanPhone)): ?>
                                <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="mobile-card-btn mobile-btn-wa" title="Kirim Pesan WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WA</span>
                                </a>
                            <?php else: ?>
                                <button type="button" class="mobile-card-btn mobile-btn-wa opacity-50" disabled title="Nomor telepon tidak tersedia">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WA</span>
                                </button>
                            <?php endif; ?>
                            <?php if ($p['kode_pelanggan'] !== 'CUST-0001'): ?>
                                <a href="<?= base_url('master/pelanggan/delete/' . $p['id']) ?>" class="mobile-card-btn mobile-btn-delete" title="Hapus Pelanggan">
                                    <i class="fa-solid fa-trash"></i>
                                    <span>Hapus</span>
                                </a>
                            <?php else: ?>
                                <button type="button" class="mobile-card-btn mobile-btn-delete opacity-50" disabled title="Pelanggan default tidak dapat dihapus">
                                    <i class="fa-solid fa-lock"></i>
                                    <span>Kunci</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty State -->
            <div id="mobileEmptyState" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Pelanggan Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau reset filter.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- MOBILE LOAD MORE / PROGRESSIVE PAGINATION -->
    <div id="mobilePaginationContainer" class="d-md-none p-3 text-center border-top bg-white">
        <div class="text-muted small mb-2">
            Menampilkan <span id="mobileVisibleCount" class="fw-bold text-dark">0</span> dari <span id="mobileTotalCount" class="fw-bold text-dark"><?= count($pelanggan) ?></span> pelanggan
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-1.5 fw-semibold shadow-xs" id="btnMobileLoadMore">
            <i class="fa-solid fa-arrow-down me-1"></i> Tampilkan Lebih Banyak (+10)
        </button>
    </div>

    <!-- CARD FOOTER (DESKTOP) -->
    <div class="card-footer bg-white d-none d-md-flex align-items-center justify-content-between py-3 text-muted small">
        <div>Total Data: <strong><?= count($pelanggan) ?> Pelanggan</strong></div>
    </div>
</div>

<!-- MODAL DETAIL PELANGGAN (READ-ONLY) -->
<div class="modal fade" id="modalViewPelanggan" tabindex="-1" aria-labelledby="modalViewPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-light py-3 px-4">
                <h5 class="modal-title fw-bold text-dark mb-0" id="modalViewPelangganLabel">
                    <i class="fa-solid fa-user-check text-primary me-2"></i>Rincian Profil Pelanggan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <!-- Info Utama -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                    <div>
                        <div class="text-muted small">Nama Pelanggan:</div>
                        <h4 class="fw-bold text-dark mb-0" id="viewNamaPelanggan">-</h4>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" id="viewTipePelanggan">-</span>
                        <span class="badge bg-success rounded-pill px-2 py-1">Aktif</span>
                    </div>
                </div>

                <!-- Kontak & Kota -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Kode Pelanggan:</label>
                        <div class="font-monospace fw-bold text-primary p-2 bg-light rounded border" id="viewKodePelanggan">-</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Nomor Telepon / WA:</label>
                        <div class="p-2 bg-light rounded border d-flex align-items-center justify-content-between font-monospace">
                            <span id="viewTelpPelanggan">-</span>
                            <a href="#" target="_blank" class="badge bg-success text-white text-decoration-none px-2 py-1" id="viewBtnWaPelanggan">
                                <i class="fa-brands fa-whatsapp me-1"></i>Chat WA
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Email:</label>
                        <div class="p-2 bg-light rounded border text-truncate" id="viewEmailPelanggan">-</div>
                    </div>
                </div>

                <!-- Kota, Limit Kredit, Tempo -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Kota / Domisili:</label>
                        <div class="fw-semibold text-dark" id="viewKotaPelanggan">-</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Limit Piutang (Kredit):</label>
                        <div class="fw-bold text-success font-monospace fs-6" id="viewLimitPelanggan">Rp 0</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Tempo Pembayaran:</label>
                        <div class="fw-semibold text-dark font-monospace" id="viewTempoPelanggan">0 Hari</div>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label class="text-muted small mb-1">Alamat Lengkap:</label>
                    <div class="p-3 bg-light rounded border text-dark small" id="viewAlamatPelanggan">-</div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-warning btn-sm text-dark fw-semibold" id="viewBtnEditPelanggan">
                    <i class="fa-solid fa-pen-to-square me-1"></i>Edit Data Pelanggan
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let pelangganDataTable = null;

    function printTable() {
        if (!pelangganDataTable) {
            window.print();
            return;
        }

        const prevLen = pelangganDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        pelangganDataTable.page.len(-1).draw();
        
        let restored = false;
        const restorePage = function() {
            if (!restored) {
                restored = true;
                window.removeEventListener('afterprint', restorePage);
                pelangganDataTable.page.len(prevLen).draw();
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
        pelangganDataTable = $('#tablePelanggan').DataTable({
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearch').on('click', function() {
            pelangganDataTable.search($('#customSearch').val()).draw();
        });

        $('#customSearch').on('keyup', function(e) {
            if (e.key === 'Enter') {
                pelangganDataTable.search(this.value).draw();
            }
        });

        $('#btnReset').on('click', function() {
            $('#customSearch').val('');
            $('#filterKriteria').val('');
            $('#filterChannel').val('');
            pelangganDataTable.search('').columns().search('').draw();
            mobileVisibleLimit = 10;
            updateMobileCardsView();
        });

        $('#filterKriteria').on('change', function() {
            pelangganDataTable.column(3).search(this.value).draw();
            mobileVisibleLimit = 10;
            updateMobileCardsView();
        });

        $('#filterChannel').on('change', function() {
            pelangganDataTable.column(3).search(this.value).draw();
            mobileVisibleLimit = 10;
            updateMobileCardsView();
        });

        // Mobile Cards Progressive Display & Live Search
        const mobileCards = $('.mobile-pelanggan-card');
        const totalMobileCards = mobileCards.length;
        let mobileVisibleLimit = 10;

        function updateMobileCardsView() {
            const query = ($('#customSearch').val() || '').toLowerCase().trim();
            const kriteria = ($('#filterKriteria').val() || '').toLowerCase().trim();
            const channel = ($('#filterChannel').val() || '').toLowerCase().trim();

            let matchCount = 0;
            let visibleCount = 0;

            mobileCards.each(function() {
                const card = $(this);
                const searchData = (card.data('search') || '').toString();
                const tipe = (card.data('tipe') || '').toString().toLowerCase();

                let matchesSearch = !query || searchData.indexOf(query) !== -1;
                let matchesKriteria = !kriteria || tipe.indexOf(kriteria) !== -1;
                let matchesChannel = !channel || searchData.indexOf(channel.toLowerCase()) !== -1;

                if (matchesSearch && matchesKriteria && matchesChannel) {
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
                $('#mobileEmptyState').removeClass('d-none');
                $('#mobilePaginationContainer').addClass('d-none');
            } else {
                $('#mobileEmptyState').addClass('d-none');
                if (visibleCount < matchCount) {
                    $('#mobilePaginationContainer').removeClass('d-none');
                } else {
                    $('#mobilePaginationContainer').addClass('d-none');
                }
            }
        }

        $('#btnMobileLoadMore').on('click', function() {
            mobileVisibleLimit += 10;
            updateMobileCardsView();
        });

        $('#customSearch').on('input keyup', function() {
            mobileVisibleLimit = 10;
            updateMobileCardsView();
        });

        updateMobileCardsView();

        $(document).on('click', '.btn-view-pelanggan', function() {
            const btn = $(this);
            $('#viewNamaPelanggan').text(btn.data('nama'));
            $('#viewKodePelanggan').text(btn.data('kode'));
            $('#viewTelpPelanggan').text(btn.data('telp'));
            $('#viewEmailPelanggan').text(btn.data('email'));
            $('#viewKotaPelanggan').text(btn.data('kota'));
            $('#viewLimitPelanggan').text(btn.data('limit'));
            $('#viewTempoPelanggan').text(btn.data('tempo') + ' Hari');
            $('#viewAlamatPelanggan').text(btn.data('alamat'));

            const tipe = btn.data('tipe');
            if (tipe === 'distributor') {
                $('#viewTipePelanggan').text('# TikTok Shop / Distributor');
            } else if (tipe === 'grosir') {
                $('#viewTipePelanggan').text('# Shopee / Grosir');
            } else {
                $('#viewTipePelanggan').text('# Toko / Regular');
            }

            const clean = btn.data('cleanphone');
            if (clean) {
                $('#viewBtnWaPelanggan').attr('href', 'https://wa.me/' + clean).show();
            } else {
                $('#viewBtnWaPelanggan').hide();
            }

            $('#viewBtnEditPelanggan').attr('href', '<?= base_url('master/pelanggan/edit') ?>/' + btn.data('id'));
            const modal = new bootstrap.Modal(document.getElementById('modalViewPelanggan'));
            modal.show();
        });
    });
</script>
<?= $this->endSection() ?>

