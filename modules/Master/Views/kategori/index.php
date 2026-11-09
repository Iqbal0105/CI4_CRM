<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Mobile Kategori Card Styles */
    .mobile-kategori-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-kategori-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-kategori-card:active {
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
        #mobileKategoriCardsContainer,
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
        #tableKategori {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            visibility: visible !important;
        }
        #tableKategori th,
        #tableKategori td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableKategori th:last-child,
        #tableKategori td:last-child,
        #tableKategori .d-print-none {
            display: none !important;
        }
        #tableKategori th {
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Kategori Barang</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Klasifikasi &amp; Pengelompokan Produk</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Master Data</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Kategori Barang</span>
    </div>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-tags text-primary"></i>
            <span>Daftar Kategori Barang</span>
            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;"><?= count($kategori) ?> Item</span>
        </div>
        <div class="d-flex flex-row align-items-center gap-2 d-print-none w-100 w-sm-auto">
            <button type="button" class="btn btn-success btn-sm flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-3 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalKategori">
                <i class="fa-solid fa-plus" style="font-size: 0.78rem;"></i>
                <span>Tambah Kategori</span>
            </button>
            <a href="<?= base_url('master/kategori/export') ?>" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" title="Export Excel (XLS)">
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
        <div class="row g-2">
            <div class="col-12 col-md-5 col-lg-4">
                <div class="input-group input-group-sm shadow-2xs rounded-3 overflow-hidden border bg-white" style="height: 38px;">
                    <button class="btn btn-white bg-white border-0 text-muted ps-3 pe-1 shadow-none" type="button" id="btnSearchKat" title="Cari Kategori">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <input type="text" id="customSearchKat" class="form-control border-0 ps-1 shadow-none" placeholder="Cari kategori barang..." style="font-size: 0.84rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-2.5 shadow-none" type="button" id="btnResetKat" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: DATA TABLE (HIDDEN ON MOBILE) -->
    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableKategori">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 140px;">Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="width: 160px;">Tanggal Dibuat</th>
                        <th class="text-center d-print-none" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kategori as $k): ?>
                        <tr>
                            <td>
                                <span class="badge px-2 py-1 font-monospace" style="background-color: #0284c7; color: white; font-size: 0.75rem;">
                                    <?= esc($k['kode_kategori']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="fw-semibold text-primary text-decoration-none btn-view-kat" data-kode="<?= esc($k['kode_kategori']) ?>" data-nama="<?= esc($k['nama_kategori']) ?>" data-desc="<?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>">
                                    <?= esc($k['nama_kategori']) ?>
                                </a>
                            </td>
                            <td class="small text-muted">
                                <?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>
                            </td>
                            <td class="small text-secondary font-monospace">
                                <?= !empty($k['created_at']) ? date('d/m/Y H:i', strtotime($k['created_at'])) : date('d/m/Y H:i') ?>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-action-view py-1 px-2 rounded btn-view-kat" data-kode="<?= esc($k['kode_kategori']) ?>" data-nama="<?= esc($k['nama_kategori']) ?>" data-desc="<?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>" title="Lihat">
                                        <i class="fa-solid fa-eye" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-edit py-1 px-2 rounded btn-edit-kat" data-id="<?= $k['id'] ?>" data-kode="<?= esc($k['kode_kategori']) ?>" data-nama="<?= esc($k['nama_kategori']) ?>" title="Edit">
                                        <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <a href="<?= base_url('master/kategori/delete/' . $k['id']) ?>" class="btn btn-sm btn-action-delete py-1 px-2 rounded" title="Hapus">
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

    <!-- 2. MOBILE VIEW: RESPONSIVE KATEGORI CARDS (VISIBLE ON SCREENS < 768px) -->
    <div class="card-body p-2 p-sm-3 d-block d-md-none bg-light bg-opacity-25" id="mobileKategoriCardsContainer">
        <?php if (empty($kategori)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-tags text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Kategori</h6>
                <p class="text-muted small mb-0">Klik tombol Tambah Kategori untuk mulai membuat klasifikasi barang.</p>
            </div>
        <?php else: ?>
            <div id="mobileKategoriCardsList">
                <?php foreach ($kategori as $k): ?>
                    <div class="mobile-kategori-card shadow-xs mb-3 p-3 bg-white"
                         data-id="<?= $k['id'] ?>"
                         data-search="<?= strtolower(esc($k['nama_kategori'] . ' ' . $k['kode_kategori'] . ' ' . ($k['deskripsi'] ?? ''))) ?>">
                        
                        <!-- Top Bar: Kode & Tanggal -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2.5 border-bottom">
                            <span class="font-monospace text-muted small fw-semibold px-2 py-0.5 bg-light rounded border text-truncate">
                                <i class="fa-solid fa-tag text-secondary me-1"></i><?= esc($k['kode_kategori']) ?>
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border small px-2 py-0.5 rounded font-monospace" style="font-size: 0.72rem;">
                                <i class="fa-regular fa-calendar me-1"></i><?= !empty($k['created_at']) ? date('d/m/Y', strtotime($k['created_at'])) : date('d/m/Y') ?>
                            </span>
                        </div>

                        <!-- Nama Kategori (Clickable) -->
                        <div class="mb-2.5">
                            <a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none fs-6 d-block text-truncate btn-view-kat"
                                data-kode="<?= esc($k['kode_kategori']) ?>" 
                                data-nama="<?= esc($k['nama_kategori']) ?>" 
                                data-desc="<?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>">
                                <?= esc($k['nama_kategori']) ?>
                            </a>
                        </div>

                        <!-- Deskripsi Box -->
                        <div class="mb-2.5 py-2 px-3 rounded-3 bg-light bg-opacity-50 border">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-align-left text-secondary me-1.5"></i><?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>
                            </small>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="mobile-card-actions">
                            <button type="button" class="mobile-card-btn mobile-btn-detail btn-view-kat"
                                data-kode="<?= esc($k['kode_kategori']) ?>" 
                                data-nama="<?= esc($k['nama_kategori']) ?>" 
                                data-desc="<?= esc($k['deskripsi'] ?? 'Kategori standar produk sistem') ?>"
                                title="Lihat Detail Kategori">
                                <i class="fa-solid fa-eye"></i>
                                <span>Detail</span>
                            </button>
                            <button type="button" class="mobile-card-btn mobile-btn-edit btn-edit-kat"
                                data-id="<?= $k['id'] ?>" 
                                data-kode="<?= esc($k['kode_kategori']) ?>" 
                                data-nama="<?= esc($k['nama_kategori']) ?>"
                                title="Edit Kategori">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </button>
                            <a href="<?= base_url('master/kategori/delete/' . $k['id']) ?>" class="mobile-card-btn mobile-btn-delete" title="Hapus Kategori">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty State -->
            <div id="mobileKategoriEmptyState" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Kategori Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian kategori.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- MOBILE LOAD MORE / PROGRESSIVE PAGINATION -->
    <div id="mobilePaginationContainer" class="d-md-none p-3 text-center border-top bg-white">
        <div class="text-muted small mb-2">
            Menampilkan <span id="mobileVisibleCount" class="fw-bold text-dark">0</span> dari <span id="mobileTotalCount" class="fw-bold text-dark"><?= count($kategori) ?></span> kategori
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-1.5 fw-semibold shadow-xs" id="btnMobileLoadMore">
            <i class="fa-solid fa-arrow-down me-1"></i> Tampilkan Lebih Banyak (+10)
        </button>
    </div>

    <!-- CARD FOOTER (DESKTOP) -->
    <div class="card-footer bg-white py-3 text-muted small d-none d-md-block">
        Total: <strong><?= count($kategori) ?> kategori</strong>
    </div>
</div>

<!-- MODAL TAMBAH KATEGORI -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('master/kategori/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalKategoriLabel">
                        <i class="fa-solid fa-tags me-2"></i>Tambah Kategori Barang
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode_kategori" class="form-control form-control-sm font-monospace text-uppercase" placeholder="Contoh: KAT-FNB" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control form-control-sm" placeholder="Contoh: Makanan & Minuman" required>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                        <i class="fa-solid fa-check me-1"></i>Simpan Kategori ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW KATEGORI -->
<div class="modal fade" id="modalViewKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light py-3 px-4">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-tags me-2 text-success"></i>Detail Kategori Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="mb-3">
                    <label class="text-muted small">Kode Kategori:</label>
                    <div class="font-monospace fw-bold text-success fs-6" id="viewKodeKat">-</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nama Kategori:</label>
                    <div class="fw-bold fs-6 text-dark" id="viewNamaKat">-</div>
                </div>
                <div>
                    <label class="text-muted small">Deskripsi:</label>
                    <div class="p-2 bg-light rounded text-dark small" id="viewDescKat">-</div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT KATEGORI -->
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditKategori" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Kategori Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode_kategori" id="editKodeKat" class="form-control form-control-sm font-monospace text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" id="editNamaKat" class="form-control form-control-sm" required>
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
    let kategoriDataTable = null;

    function printTable() {
        if (!kategoriDataTable) {
            window.print();
            return;
        }

        const prevLen = kategoriDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        kategoriDataTable.page.len(-1).draw();
        
        let restored = false;
        const restorePage = function() {
            if (!restored) {
                restored = true;
                window.removeEventListener('afterprint', restorePage);
                kategoriDataTable.page.len(prevLen).draw();
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
        kategoriDataTable = $('#tableKategori').DataTable({
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearchKat').on('click', function() {
            kategoriDataTable.search($('#customSearchKat').val()).draw();
        });

        $('#customSearchKat').on('keyup', function(e) {
            if (e.key === 'Enter') {
                kategoriDataTable.search(this.value).draw();
            }
        });

        $('#btnResetKat').on('click', function() {
            $('#customSearchKat').val('');
            kategoriDataTable.search('').draw();
            mobileVisibleLimit = 10;
            updateMobileKategoriCards();
        });

        // Mobile Cards Progressive Display & Live Search
        const mobileCards = $('.mobile-kategori-card');
        let mobileVisibleLimit = 10;

        function updateMobileKategoriCards() {
            const query = ($('#customSearchKat').val() || '').toLowerCase().trim();

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
                $('#mobileKategoriEmptyState').removeClass('d-none');
                $('#mobilePaginationContainer').addClass('d-none');
            } else {
                $('#mobileKategoriEmptyState').addClass('d-none');
                if (visibleCount < matchCount) {
                    $('#mobilePaginationContainer').removeClass('d-none');
                } else {
                    $('#mobilePaginationContainer').addClass('d-none');
                }
            }
        }

        $('#btnMobileLoadMore').on('click', function() {
            mobileVisibleLimit += 10;
            updateMobileKategoriCards();
        });

        $('#customSearchKat').on('input keyup', function() {
            mobileVisibleLimit = 10;
            updateMobileKategoriCards();
        });

        updateMobileKategoriCards();

        $(document).on('click', '.btn-view-kat', function() {
            const btn = $(this);
            $('#viewKodeKat').text(btn.data('kode'));
            $('#viewNamaKat').text(btn.data('nama'));
            $('#viewDescKat').text(btn.data('desc'));
            const modal = new bootstrap.Modal(document.getElementById('modalViewKategori'));
            modal.show();
        });

        $(document).on('click', '.btn-edit-kat', function() {
            const btn = $(this);
            const id = btn.data('id');
            $('#formEditKategori').attr('action', '<?= base_url('master/kategori/update') ?>/' + id);
            $('#editKodeKat').val(btn.data('kode'));
            $('#editNamaKat').val(btn.data('nama'));
            const modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
            modal.show();
        });
    });
</script>
<?= $this->endSection() ?>

