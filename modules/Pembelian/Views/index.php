<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
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
        #tablePO {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
        }
        #tablePO th,
        #tablePO td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tablePO th {
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Purchase Order (PO)</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Pesanan Pembelian Ke Supplier</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Pembelian</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Purchase Order</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (ALUR PENGADAAN & SUPPLIER) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('pembelian') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
        <i class="fa-solid fa-file-lines me-1"></i> Daftar Order PO
    </a>
    <a href="<?= base_url('pembelian/create') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-plus me-1"></i> Buat PO Baru
    </a>
    <a href="<?= base_url('master/supplier') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-truck me-1"></i> Supplier Rekanan
    </a>
    <a href="<?= base_url('stok') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-boxes-stacked me-1"></i> Monitoring Stok
    </a>
    <a href="<?= base_url('keuangan/utang') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Utang Usaha (AP)
    </a>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-primary"></i>
            <span>Daftar Seluruh Pesanan Pembelian</span>
            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.75rem;"><?= count($orders) ?> Data</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('pembelian/export') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-xs">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export XLS / CSV</span>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" onclick="printTable()">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
            <a href="<?= base_url('pembelian/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Purchase Order</span>
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" id="customSearchPO" class="form-control" placeholder="Cari nomor PO / supplier...">
                    <button class="btn btn-primary" type="button" id="btnSearchPO">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetPO" title="Reset">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePO">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>Nomor PO</th>
                        <th>Tanggal Order</th>
                        <th>Supplier</th>
                        <th class="text-center">Mata Uang</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center d-print-none" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('pembelian/detail/' . $order['id']) ?>" class="font-monospace fw-semibold text-primary text-decoration-none">
                                        <?= esc($order['nomor_po']) ?>
                                    </a>
                                </td>
                                <td class="small text-secondary font-monospace" data-order="<?= esc($order['tanggal_po']) ?>">
                                    <i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($order['tanggal_po'])) ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= esc($order['nama_supplier']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2 py-1 font-monospace" style="background-color: #6366f1; color: white; font-size: 0.78rem;">
                                        <?= esc($order['mata_uang']) ?>
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark small">
                                    <div><?= format_rupiah($order['total_idr']) ?></div>
                                    <?php if ($order['mata_uang'] !== 'IDR'): ?>
                                        <small class="text-muted fw-normal d-block" style="font-size: 0.72rem;"><?= format_valas($order['total'], $order['mata_uang']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($order['status'] === 'received'): ?>
                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">SELESAI (MASUK STOK)</span>
                                    <?php elseif ($order['status'] === 'approved'): ?>
                                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.78rem;">APPROVED</span>
                                    <?php elseif ($order['status'] === 'partial'): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.78rem;">PARSIAL</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.78rem;"><?= strtoupper(esc($order['status'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center d-print-none" style="vertical-align: middle;">
                                    <div class="d-inline-flex gap-1 justify-content-center align-items-center">
                                        <a href="<?= base_url('pembelian/detail/' . $order['id']) ?>" class="btn btn-sm btn-action-view p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Lihat Detail PO">
                                            <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <a href="<?= base_url('pembelian/pdf/' . $order['id']) ?>" target="_blank" class="btn btn-sm btn-primary p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Cetak PDF Purchase Order">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <a href="<?= base_url('keuangan/utang') ?>" class="btn btn-sm btn-success p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Buku Besar Utang Usaha (AP)">
                                            <i class="fa-solid fa-money-bill-wave" style="font-size: 0.8rem;"></i>
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

    <!-- CARD FOOTER -->
    <div class="card-footer bg-white py-3 text-muted small">
        Total: <strong><?= count($orders) ?> data Purchase Order</strong>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let poDataTable = null;

    function printTable() {
        if (!poDataTable) {
            window.print();
            return;
        }

        const prevLen = poDataTable.page.len();
        // Buka seluruh baris agar terprint penuh
        poDataTable.page.len(-1).draw();
        
        setTimeout(function() {
            window.print();
            // Kembalikan ke panjang halaman semula setelah dialog print
            poDataTable.page.len(prevLen).draw();
        }, 300);
    }

    $(document).ready(function() {
        poDataTable = $('#tablePO').DataTable({
            dom: 'rtip',
            pageLength: 10,
            order: [[1, 'desc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearchPO').on('click', function() {
            poDataTable.search($('#customSearchPO').val()).draw();
        });

        $('#customSearchPO').on('keyup', function(e) {
            if (e.key === 'Enter') {
                poDataTable.search(this.value).draw();
            }
        });

        $('#btnResetPO').on('click', function() {
            $('#customSearchPO').val('');
            poDataTable.search('').draw();
        });
    });
</script>
<?= $this->endSection() ?>

