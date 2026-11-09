<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Mobile Satuan Card Styles */
    .mobile-satuan-card {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mobile-satuan-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .mobile-satuan-card:active {
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
        #mobileSatuanCardsContainer,
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
        #tableSatuan {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            visibility: visible !important;
        }
        #tableSatuan th,
        #tableSatuan td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableSatuan th:last-child,
        #tableSatuan td:last-child,
        #tableSatuan .d-print-none {
            display: none !important;
        }
        #tableSatuan th {
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Unit Barang (Satuan)</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Standar Satuan Unit Produk</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Master Data</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Unit Barang</span>
    </div>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-scale-balanced text-primary"></i>
            <span>Daftar Unit Barang (Satuan)</span>
            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;"><?= count($satuan) ?> Item</span>
        </div>
        <div class="d-flex flex-row align-items-center gap-2 d-print-none w-100 w-sm-auto">
            <button type="button" class="btn btn-success btn-sm flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-3 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalSatuan">
                <i class="fa-solid fa-plus" style="font-size: 0.78rem;"></i>
                <span>Tambah Unit</span>
            </button>
            <a href="<?= base_url('master/satuan/export') ?>" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-xs px-2.5 text-nowrap" style="height: 38px; border-radius: 8px; font-weight: 600; font-size: 0.82rem;" title="Export Excel (XLS)">
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
                    <button class="btn btn-white bg-white border-0 text-muted ps-3 pe-1 shadow-none" type="button" id="btnSearchSatuan" title="Cari Satuan">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <input type="text" id="customSearchSatuan" class="form-control border-0 ps-1 shadow-none" placeholder="Cari unit barang..." style="font-size: 0.84rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-2.5 shadow-none" type="button" id="btnResetSatuan" title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. DESKTOP VIEW: DATA TABLE (HIDDEN ON MOBILE) -->
    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableSatuan">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 120px;">Kode Satuan</th>
                        <th>Nama Satuan</th>
                        <th>Keterangan</th>
                        <th style="width: 160px;">Dibuat Pada</th>
                        <th style="width: 160px;">Diperbarui</th>
                        <th class="text-center d-print-none" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($satuan as $s): ?>
                        <tr>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 font-monospace" style="background-color: #0284c7; color: white; font-size: 0.78rem;">
                                    <?= esc($s['kode_satuan']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark"><?= esc($s['nama_satuan']) ?></span>
                            </td>
                            <td class="small text-muted">
                                <?= esc($s['keterangan'] ?? 'Satuan standar produk') ?>
                            </td>
                            <td class="small text-secondary font-monospace">
                                <?= !empty($s['created_at']) ? date('Y-m-d H:i', strtotime($s['created_at'])) : date('Y-m-d H:i') ?>
                            </td>
                            <td class="small text-secondary font-monospace">
                                <?= !empty($s['updated_at']) ? date('Y-m-d H:i', strtotime($s['updated_at'])) : date('Y-m-d H:i') ?>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-action-edit py-1 px-2 rounded btn-edit-satuan" data-id="<?= $s['id'] ?>" data-kode="<?= esc($s['kode_satuan']) ?>" data-nama="<?= esc($s['nama_satuan']) ?>" title="Edit">
                                        <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <a href="<?= base_url('master/satuan/delete/' . $s['id']) ?>" class="btn btn-sm btn-action-delete py-1 px-2 rounded" title="Hapus">
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

    <!-- 2. MOBILE VIEW: RESPONSIVE SATUAN CARDS (VISIBLE ON SCREENS < 768px) -->
    <div class="card-body p-2 p-sm-3 d-block d-md-none bg-light bg-opacity-25" id="mobileSatuanCardsContainer">
        <?php if (empty($satuan)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-scale-balanced text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Unit Barang</h6>
                <p class="text-muted small mb-0">Klik tombol Tambah Unit untuk mulai membuat satuan produk.</p>
            </div>
        <?php else: ?>
            <div id="mobileSatuanCardsList">
                <?php foreach ($satuan as $s): ?>
                    <div class="mobile-satuan-card shadow-xs mb-3 p-3 bg-white"
                         data-id="<?= $s['id'] ?>"
                         data-search="<?= strtolower(esc($s['nama_satuan'] . ' ' . $s['kode_satuan'] . ' ' . ($s['keterangan'] ?? ''))) ?>">
                        
                        <!-- Top Bar: Kode Satuan & Tanggal -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2.5 border-bottom">
                            <span class="badge rounded-pill px-2.5 py-1 font-monospace" style="background-color: #0284c7; color: white; font-size: 0.75rem;">
                                <?= esc($s['kode_satuan']) ?>
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border small px-2 py-0.5 rounded font-monospace" style="font-size: 0.72rem;">
                                <i class="fa-regular fa-calendar me-1"></i><?= !empty($s['created_at']) ? date('d/m/Y', strtotime($s['created_at'])) : date('d/m/Y') ?>
                            </span>
                        </div>

                        <!-- Nama Satuan -->
                        <div class="mb-2.5">
                            <h6 class="fw-bold text-dark mb-0 fs-6 text-truncate">
                                <?= esc($s['nama_satuan']) ?>
                            </h6>
                        </div>

                        <!-- Keterangan Box -->
                        <div class="mb-2.5 py-2 px-3 rounded-3 bg-light bg-opacity-50 border">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-info-circle text-secondary me-1.5"></i><?= esc($s['keterangan'] ?? 'Satuan standar unit barang') ?>
                            </small>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="mobile-card-actions">
                            <button type="button" class="mobile-card-btn mobile-btn-edit btn-edit-satuan"
                                data-id="<?= $s['id'] ?>" 
                                data-kode="<?= esc($s['kode_satuan']) ?>" 
                                data-nama="<?= esc($s['nama_satuan']) ?>"
                                title="Edit Satuan">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </button>
                            <a href="<?= base_url('master/satuan/delete/' . $s['id']) ?>" class="mobile-card-btn mobile-btn-delete" title="Hapus Satuan">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Empty State -->
            <div id="mobileSatuanEmptyState" class="text-center py-5 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted opacity-50 fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Satuan Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Coba ubah kata kunci pencarian unit satuan.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- MOBILE LOAD MORE / PROGRESSIVE PAGINATION -->
    <div id="mobilePaginationContainer" class="d-md-none p-3 text-center border-top bg-white">
        <div class="text-muted small mb-2">
            Menampilkan <span id="mobileVisibleCount" class="fw-bold text-dark">0</span> dari <span id="mobileTotalCount" class="fw-bold text-dark"><?= count($satuan) ?></span> unit
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-1.5 fw-semibold shadow-xs" id="btnMobileLoadMore">
            <i class="fa-solid fa-arrow-down me-1"></i> Tampilkan Lebih Banyak (+10)
        </button>
    </div>

    <!-- CARD FOOTER (DESKTOP) -->
    <div class="card-footer bg-white py-3 text-muted small d-none d-md-block">
        Total: <strong><?= count($satuan) ?> unit</strong>
    </div>
</div>

<!-- MODAL TAMBAH SATUAN -->
<div class="modal fade" id="modalSatuan" tabindex="-1" aria-labelledby="modalSatuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('master/satuan/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalSatuanLabel">
                        <i class="fa-solid fa-scale-balanced me-2"></i>Tambah Unit Barang (Satuan)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_satuan" class="form-control form-control-sm font-monospace text-uppercase" placeholder="Contoh: PCS, BOX, KG, LTR" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_satuan" class="form-control form-control-sm" placeholder="Contoh: Pieces / Buah" required>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                        <i class="fa-solid fa-check me-1"></i>Simpan Satuan ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT SATUAN -->
<div class="modal fade" id="modalEditSatuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditSatuan" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Satuan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_satuan" id="editKodeSatuan" class="form-control form-control-sm font-monospace text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_satuan" id="editNamaSatuan" class="form-control form-control-sm" required>
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
    let satuanDataTable = null;

    function printTable() {
        if (!satuanDataTable) {
            window.print();
            return;
        }

        const prevLen = satuanDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        satuanDataTable.page.len(-1).draw();
        
        let restored = false;
        const restorePage = function() {
            if (!restored) {
                restored = true;
                window.removeEventListener('afterprint', restorePage);
                satuanDataTable.page.len(prevLen).draw();
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
        satuanDataTable = $('#tableSatuan').DataTable({
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearchSatuan').on('click', function() {
            satuanDataTable.search($('#customSearchSatuan').val()).draw();
        });

        $('#customSearchSatuan').on('keyup', function(e) {
            if (e.key === 'Enter') {
                satuanDataTable.search(this.value).draw();
            }
        });

        $('#btnResetSatuan').on('click', function() {
            $('#customSearchSatuan').val('');
            satuanDataTable.search('').draw();
            mobileVisibleLimit = 10;
            updateMobileSatuanCards();
        });

        // Mobile Cards Progressive Display & Live Search
        const mobileCards = $('.mobile-satuan-card');
        let mobileVisibleLimit = 10;

        function updateMobileSatuanCards() {
            const query = ($('#customSearchSatuan').val() || '').toLowerCase().trim();

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
                $('#mobileSatuanEmptyState').removeClass('d-none');
                $('#mobilePaginationContainer').addClass('d-none');
            } else {
                $('#mobileSatuanEmptyState').addClass('d-none');
                if (visibleCount < matchCount) {
                    $('#mobilePaginationContainer').removeClass('d-none');
                } else {
                    $('#mobilePaginationContainer').addClass('d-none');
                }
            }
        }

        $('#btnMobileLoadMore').on('click', function() {
            mobileVisibleLimit += 10;
            updateMobileSatuanCards();
        });

        $('#customSearchSatuan').on('input keyup', function() {
            mobileVisibleLimit = 10;
            updateMobileSatuanCards();
        });

        updateMobileSatuanCards();

        $(document).on('click', '.btn-edit-satuan', function() {
            const btn = $(this);
            const id = btn.data('id');
            $('#formEditSatuan').attr('action', '<?= base_url('master/satuan/update') ?>/' + id);
            $('#editKodeSatuan').val(btn.data('kode'));
            $('#editNamaSatuan').val(btn.data('nama'));
            const modal = new bootstrap.Modal(document.getElementById('modalEditSatuan'));
            modal.show();
        });
    });
</script>
<?= $this->endSection() ?>

