<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @page {
        size: landscape;
        margin: 0;
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
        .modal,
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
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
        }
        #tableStok {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
        }
        #tableStok th,
        #tableStok td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableStok th:last-child,
        #tableStok td:last-child,
        #tableStok .d-print-none {
            display: none !important;
        }
        #tableStok th {
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Dashboard Stok</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Real-Time</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Stok</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (MANAJEMEN STOK) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('stok') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
        <i class="fa-solid fa-chart-pie me-1"></i> Monitoring Stok
    </a>
    <a href="<?= base_url('stok/opname') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-pen-to-square me-1"></i> Stok Opname / Edit
    </a>
    <a href="<?= base_url('master/produk') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-cubes me-1"></i> Katalog Produk
    </a>
    <a href="<?= base_url('pembelian/create') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-cart-shopping me-1"></i> Buat PO Pengadaan
    </a>
    <a href="<?= base_url('pos') ?>" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-cash-register me-1"></i> Kasir Cepat (POS)
    </a>
</div>

<!-- 4 STAT CARDS (CLEAN & MODERN) -->
<div class="row g-2 g-sm-3 mb-4">
    <!-- 1. Total Produk (Blue) -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #E8F0EB; color: #2D6A4F;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary small fw-semibold px-2 py-1 rounded-pill">Total</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.04em;">TOTAL PRODUK</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-dark font-sans"><?= count($items) ?></span>
                <span class="small text-muted">Item</span>
            </div>
            <div class="mt-2 pt-2 border-top d-print-none">
                <a href="javascript:void(0)" id="filterAll" class="text-primary text-decoration-none small d-flex align-items-center justify-content-between fw-semibold">
                    <span>Semua Produk</span>
                    <i class="fa-solid fa-arrow-right small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Total Keseluruhan Stok (Green) -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #ecfdf5; color: #059669;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success small fw-semibold px-2 py-1 rounded-pill">Ready</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.04em;">KUANTITAS STOK</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-success font-sans"><?= number_format($totalQty, 0, ',', '.') ?></span>
                <span class="small text-muted">Unit</span>
            </div>
            <div class="mt-2 pt-2 border-top d-print-none">
                <a href="javascript:void(0)" id="filterAdaStok" class="text-success text-decoration-none small d-flex align-items-center justify-content-between fw-semibold">
                    <span>Stok Normal</span>
                    <i class="fa-solid fa-arrow-right small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Stok Menipis (Amber) -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="badge bg-warning bg-opacity-15 text-warning small fw-semibold px-2 py-1 rounded-pill">Perhatian</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.04em;">STOK MENIPIS</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-warning font-sans"><?= count($menipisItems) ?></span>
                <span class="small text-muted">Produk</span>
            </div>
            <div class="mt-2 pt-2 border-top d-print-none">
                <a href="javascript:void(0)" id="filterMenipis" class="text-warning text-decoration-none small d-flex align-items-center justify-content-between fw-semibold">
                    <span>Perlu Restok</span>
                    <i class="fa-solid fa-arrow-right small"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Stok Habis (Red) -->
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <span class="badge bg-danger bg-opacity-10 text-danger small fw-semibold px-2 py-1 rounded-pill">Kritis</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase text-truncate" style="font-size: 0.72rem; letter-spacing: 0.04em;">STOK HABIS</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-danger font-sans"><?= count($habisItems) ?></span>
                <span class="small text-muted">Produk</span>
            </div>
            <div class="mt-2 pt-2 border-top d-print-none">
                <a href="javascript:void(0)" id="filterHabis" class="text-danger text-decoration-none small d-flex align-items-center justify-content-between fw-semibold">
                    <span>Stok Kosong</span>
                    <i class="fa-solid fa-arrow-right small"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- CARD DATA STOK BARANG -->
<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-database text-primary"></i>
            <span>Data Stok Barang</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('stok/opname') ?>" class="btn btn-warning btn-sm text-dark fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Semua Stok (Massal)</span>
            </a>
            <button type="button" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInputStok">
                <i class="fa-solid fa-plus"></i>
                <span>Input Stok Manual</span>
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <i class="fa-solid fa-bars-staggered"></i>
                    <span>Tampil:</span>
                    <select class="form-select form-select-sm" id="pageLengthSelect" style="width: 85px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100" selected>100</option>
                    </select>
                </div>
                <div class="input-group input-group-sm" style="max-width: 280px;">
                    <input type="text" id="searchStok" class="form-control" placeholder="Cari kode/nama produk...">
                    <button class="btn btn-primary" type="button" id="btnSearchStok">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-1">
                <a href="<?= base_url('stok/export') ?>" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 shadow-xs">
                    <i class="fa-solid fa-file-excel text-success"></i> Export Excel
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="printTable()">
                    <i class="fa-solid fa-file-pdf text-danger"></i> Export PDF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" onclick="printTable()">
                    <i class="fa-solid fa-print text-primary"></i> Cetak / Print
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tableStok">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Rusak</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center d-print-none" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php 
                            $fisik = (float) $item['fisik'];
                            $reserved = (float) $item['reserved'];
                            $tersedia = $fisik - $reserved;
                            $minStok = (int) $item['stok_minimum'];

                            $isHabis = $tersedia <= 0;
                            $isMenipis = !$isHabis && ($tersedia <= $minStok);

                            $rowBg = '';
                            if ($isHabis) {
                                $rowBg = 'background-color: #fef2f2;';
                            } elseif ($isMenipis) {
                                $rowBg = 'background-color: #fffbeb;';
                            }
                        ?>
                        <tr style="<?= $rowBg ?>">
                            <td><span class="font-monospace text-muted small"><?= esc($item['kode_produk']) ?></span></td>
                            <td>
                                <span class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></span>
                            </td>
                            <td class="text-center font-monospace fw-bold <?= $isHabis ? 'text-danger' : ($isMenipis ? 'text-warning' : 'text-dark') ?>">
                                <?= number_format($tersedia, 0, ',', '.') ?>
                            </td>
                            <td class="text-center text-muted small">-</td>
                            <td class="text-center text-muted small"><?= esc($item['stok_minimum']) ?></td>
                            <td class="text-center">
                                <?php if ($isHabis): ?>
                                    <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.78rem;">&#10006; Habis</span>
                                <?php elseif ($isMenipis): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.78rem;">&#9888; Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">&#10003; Normal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="d-inline-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-warning btn-sm text-white p-0 d-inline-flex align-items-center justify-content-center rounded shadow-xs" style="width: 32px; height: 32px;" onclick="openAdjustModal(<?= $item['id'] ?>, '<?= esc(addslashes($item['nama_produk'])) ?>', <?= $tersedia ?>)" title="Sesuaikan Stok" data-bs-toggle="tooltip">
                                        <i class="fa-solid fa-pen" style="font-size: 0.8rem;"></i>
                                    </button>
                                    <a href="<?= base_url('stok/kartu/' . $item['id']) ?>" class="btn btn-primary btn-sm p-0 d-inline-flex align-items-center justify-content-center rounded shadow-xs" style="width: 32px; height: 32px;" title="Kartu Stok" data-bs-toggle="tooltip">
                                        <i class="fa-solid fa-list-check" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <a href="<?= base_url('master/produk/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm p-0 d-inline-flex align-items-center justify-content-center rounded shadow-xs btn-action-delete" style="width: 32px; height: 32px;" title="Hapus Produk" data-bs-toggle="tooltip">
                                        <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CARD FOOTER -->
    <div class="card-footer bg-white py-3 text-muted small">
        Total: <strong><?= count($items) ?> produk unik</strong> &bull; <?= count($items) ?> baris record stok
    </div>
</div>

<!-- ALERT STOK MENIPIS & HABIS SUMMARY CARD -->
<?php 
    $alertList = array_merge($menipisItems, $habisItems);
?>
<?php if (!empty($alertList)): ?>
<div class="card shadow-sm border mb-4 d-print-none">
    <div class="card-header py-2" style="background-color: #fefce8; border-bottom: 1px solid #fef08a;">
        <span class="fw-bold text-dark small"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Peringatan: Stok Menipis &amp; Habis</span>
    </div>
    <div class="card-body p-0" style="background-color: #fefce8;">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle" style="background-color: transparent;">
                <thead>
                    <tr class="small text-muted">
                        <th class="ps-3">Nama Produk</th>
                        <th class="text-center" style="width: 140px;">Stok Saat Ini</th>
                        <th class="text-center" style="width: 140px;">Batas Minimal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alertList as $alertItem): ?>
                        <?php 
                            $tersedia = (float) $alertItem['fisik'] - (float) $alertItem['reserved'];
                        ?>
                        <tr>
                            <td class="ps-3 fw-medium text-dark small"><?= esc($alertItem['nama_produk']) ?></td>
                            <td class="text-center font-monospace fw-bold text-danger small"><?= number_format($tersedia, 0, ',', '.') ?></td>
                            <td class="text-center font-monospace text-muted small"><?= esc($alertItem['stok_minimum']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL SESUAIKAN STOK MANUAL -->
<div class="modal fade" id="modalInputStok" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('stok/opname/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Input / Koreksi Stok Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk</label>
                        <select name="produk_id" id="modalSelectProduk" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?= $item['id'] ?>">[<?= esc($item['kode_produk']) ?>] <?= esc($item['nama_produk']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Stok Fisik Riil</label>
                        <input type="number" step="0.001" name="qty_fisik" id="modalQtyFisik" class="form-control form-control-sm font-monospace fw-bold" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan / Catatan Penyesuaian</label>
                        <textarea name="keterangan" class="form-control form-control-sm" rows="2" placeholder="Contoh: Koreksi stok opname fisik"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm text-dark fw-semibold">Simpan Penyesuaian</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let stokDataTable = null;

    function printTable() {
        if (!stokDataTable) {
            window.print();
            return;
        }

        const prevLen = stokDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        stokDataTable.page.len(-1).draw();

        setTimeout(function() {
            window.print();
            // Kembalikan ke panjang halaman semula setelah dialog print
            stokDataTable.page.len(prevLen).draw();
        }, 300);
    }

    function openAdjustModal(produkId, namaProduk, currentStok) {
        $('#modalSelectProduk').val(produkId).trigger('change');
        $('#modalQtyFisik').val(currentStok);
        const modal = new bootstrap.Modal(document.getElementById('modalInputStok'));
        modal.show();
    }

    $(document).ready(function() {
        stokDataTable = $('#tableStok').DataTable({
            dom: 'rtip',
            pageLength: 100,
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#pageLengthSelect').on('change', function() {
            stokDataTable.page.len(parseInt(this.value)).draw();
        });

        $('#btnSearchStok').on('click', function() {
            stokDataTable.search($('#searchStok').val()).draw();
        });

        $('#searchStok').on('keyup', function(e) {
            if (e.key === 'Enter') {
                stokDataTable.search(this.value).draw();
            }
        });

        $('#filterAll').on('click', function() {
            $('#searchStok').val('');
            stokDataTable.search('').columns().search('').draw();
        });

        $('#filterAdaStok').on('click', function() {
            stokDataTable.column(5).search('Normal').draw();
        });

        $('#filterMenipis').on('click', function() {
            stokDataTable.column(5).search('Menipis').draw();
        });

        $('#filterHabis').on('click', function() {
            stokDataTable.column(5).search('Habis').draw();
        });
    });
</script>
<?= $this->endSection() ?>

