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
        #tableInvoice {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
        }
        #tableInvoice th,
        #tableInvoice td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableInvoice th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #tableInvoice th:last-child,
        #tableInvoice td:last-child {
            display: none !important;
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Invoice</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Invoice</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Invoice</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (ALUR PENJUALAN LENGKAP) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-receipt me-1"></i> Transaksi Penjualan
    </a>
    <a href="<?= base_url('pos') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-bolt me-1"></i> Kasir Cepat (POS)
    </a>
    <a href="<?= base_url('penjualan/create') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-plus me-1"></i> Buat Order (SO)
    </a>
    <a href="<?= base_url('marketplace') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-shop me-1"></i> Penjualan Marketplace
    </a>
    <a href="<?= base_url('marketplace/scan-resi') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-barcode me-1"></i> Scan Resi
    </a>
    <a href="<?= base_url('penjualan/invoice') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
        <i class="fa-solid fa-file-invoice me-1"></i> Faktur (Invoice)
    </a>
    <a href="<?= base_url('retur/penjualan') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
        <i class="fa-solid fa-rotate-left me-1"></i> Retur
    </a>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-primary"></i>
            <span>Data Invoice Penjualan</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('penjualan/invoice/export') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export XLS</span>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 shadow-sm" onclick="printInvoiceTable()">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
            <a href="<?= base_url('penjualan/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Invoice (Penjualan)</span>
            </a>
        </div>
    </div>

    <!-- FILTER & QUICK TABS ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" id="customSearchInv" class="form-control" placeholder="Cari nomor invoice / pelanggan...">
                    <button class="btn btn-primary" type="button" id="btnSearchInv">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetInv" title="Reset">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-7 d-flex flex-wrap align-items-center justify-content-md-end gap-2">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-tab-inv active" data-filter="all">
                    Semua Transaksi
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-tab-inv" data-filter="POS">
                    <i class="fa-solid fa-bolt text-warning me-1"></i>Kasir Cepat (POS)
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-tab-inv" data-filter="Regular">
                    <i class="fa-solid fa-file-invoice text-primary me-1"></i>Transaksi Penjualan
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-tab-inv" data-filter="Marketplace">
                    <i class="fa-solid fa-shop text-success me-1"></i>Marketplace
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableInvoice">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>No Invoice</th>
                        <th>Sumber Transaksi</th>
                        <th>No. Transaksi Penjualan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status Bayar</th>
                        <th class="text-center">Status Kirim</th>
                        <th class="text-center d-print-none" style="width: 85px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invoices)): ?>
                        <?php foreach ($invoices as $inv): ?>
                            <tr>
                                <td>
                                    <span class="font-monospace fw-bold text-dark small"><?= esc($inv['nomor_invoice']) ?></span>
                                </td>
                                <td>
                                    <?php if ($inv['source'] === 'pos'): ?>
                                        <span class="badge bg-success text-white fw-normal" style="font-size: 0.78rem;">
                                            <i class="fa-solid fa-bolt me-1"></i>Kasir Cepat (POS)
                                        </span>
                                    <?php elseif (in_array($inv['source'] ?? '', ['shopee', 'tiktok', 'tokopedia', 'lazada'], true)): ?>
                                        <span class="badge bg-warning text-dark fw-normal" style="font-size: 0.78rem;">
                                            <i class="fa-solid fa-shop me-1"></i>Marketplace (<?= ucfirst(esc($inv['source'])) ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary text-white fw-normal" style="font-size: 0.78rem;">
                                            Transaksi Penjualan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-monospace text-primary small"><?= esc($inv['nomor_so']) ?></span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark small"><?= esc($inv['nama_pelanggan'] ?? 'Pelanggan Walk-In') ?></span>
                                </td>
                                <?php 
                                    $rawDate = $inv['tanggal_invoice'] ?? $inv['tanggal_so'] ?? $inv['created_at'] ?? '';
                                    $rawJt = $inv['jatuh_tempo'] ?? $inv['tanggal_so'] ?? '';
                                ?>
                                <td class="small text-secondary font-monospace" data-order="<?= esc($rawDate) ?>">
                                    <?= !empty($rawDate) ? date('d/m/Y', strtotime($rawDate)) : '-' ?>
                                </td>
                                <td class="small text-secondary font-monospace" data-order="<?= esc($rawJt) ?>">
                                    <?= !empty($rawJt) ? date('d/m/Y', strtotime($rawJt)) : '-' ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark small">
                                    <?= format_rupiah($inv['total']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">PAID</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">DELIVERED</span>
                                </td>
                                <td class="text-center d-print-none" style="vertical-align: middle;">
                                    <div class="d-grid gap-1 justify-content-center mx-auto" style="grid-template-columns: repeat(2, 30px); width: fit-content;">
                                        <a href="<?= base_url('penjualan/detail/' . $inv['so_id']) ?>" class="btn btn-sm btn-info text-white p-0 d-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Lihat Detail Transaksi">
                                            <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success p-0 d-flex align-items-center justify-content-center rounded btn-print-thermal-inv" data-id="<?= $inv['so_id'] ?>" style="width: 30px; height: 30px;" title="Cetak Struk Thermal (Bluetooth/USB/Browser)">
                                            <i class="fa-solid fa-receipt" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <a href="<?= base_url('penjualan/invoice-pdf/' . $inv['so_id']) ?>" target="_blank" class="btn btn-sm btn-primary p-0 d-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Cetak PDF Invoice">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger p-0 d-flex align-items-center justify-content-center rounded btn-action-delete" style="width: 30px; height: 30px;" title="Hapus Invoice">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </button>
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
        Total: <strong><?= count($invoices) ?> record Invoice</strong>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let invoiceDataTable = null;

    function printInvoiceTable() {
        if (!invoiceDataTable) {
            window.print();
            return;
        }

        const prevLen = invoiceDataTable.page.len();
        invoiceDataTable.page.len(-1).draw();

        setTimeout(function() {
            window.print();
            invoiceDataTable.page.len(prevLen).draw();
        }, 300);
    }

    $(document).ready(function() {
        const table = $('#tableInvoice').DataTable({
            dom: 'rtip',
            pageLength: 10,
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: [9] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });
        invoiceDataTable = table;

        $('#btnSearchInv').on('click', function() {
            table.search($('#customSearchInv').val()).draw();
        });

        $('#customSearchInv').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.search(this.value).draw();
            }
        });

        $('#btnResetInv').on('click', function() {
            $('#customSearchInv').val('');
            table.search('').columns().search('').draw();
        });

        $('.btn-tab-inv').on('click', function() {
            $('.btn-tab-inv').removeClass('btn-primary active').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');

            const filter = $(this).data('filter');
            if (filter === 'all') {
                table.column(1).search('').draw();
            } else {
                table.column(1).search(filter).draw();
            }
        });

        // Thermal Print Invoice
        $(document).on('click', '.btn-print-thermal-inv', async function() {
            const soId = $(this).data('id');
            const btn = $(this);
            const orig = btn.html();

            try {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
                const res = await $.getJSON('<?= base_url('penjualan/thermal-data/') ?>' + soId);
                if (res.status !== 'success') {
                    throw new Error(res.message || 'Gagal memuat data transaksi');
                }

                const ord = res.order;
                const items = res.items;
                const store = res.store;

                let itemsHtml = '';
                items.forEach(it => {
                    const sub = Math.round(parseFloat(it.subtotal || (it.qty * it.harga_satuan))).toLocaleString('id-ID');
                    itemsHtml += `
                        <tr>
                            <td colspan="3" class="fw-bold">${it.nama_produk || 'Produk'}</td>
                        </tr>
                        <tr>
                            <td>${parseFloat(it.qty)} x ${Math.round(parseFloat(it.harga_satuan)).toLocaleString('id-ID')}</td>
                            <td></td>
                            <td class="text-end font-monospace">${sub}</td>
                        </tr>
                    `;
                });

                const grand = Math.round(parseFloat(ord.total || 0)).toLocaleString('id-ID');
                const subtotal = Math.round(parseFloat(ord.subtotal || ord.total || 0)).toLocaleString('id-ID');
                const bayar = Math.round(parseFloat(ord.bayar || ord.total || 0)).toLocaleString('id-ID');
                const kembalian = Math.round(parseFloat(ord.kembalian || 0)).toLocaleString('id-ID');

                const previewHtml = `
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 13px;">${store.name}</div>
                        <div>${store.address}</div>
                        <div>${store.phone}</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div>No. Inv : ${ord.nomor_invoice || '-'}</div>
                    <div>No. Trx : ${ord.nomor_so || '-'}</div>
                    <div>Waktu   : ${ord.created_at || new Date().toLocaleString('id-ID')}</div>
                    <div>Cust    : ${ord.nama_pelanggan || 'Pelanggan'}</div>
                    <div>Metode  : ${(ord.metode_bayar || 'Tunai').toUpperCase()}</div>
                    <div class="dashed-line"></div>
                    <table>${itemsHtml}</table>
                    <div class="dashed-line"></div>
                    <table>
                        <tr><td>Subtotal:</td><td class="text-end">Rp ${subtotal}</td></tr>
                        <tr class="fw-bold"><td>TOTAL:</td><td class="text-end">Rp ${grand}</td></tr>
                        <tr><td>Bayar:</td><td class="text-end">Rp ${bayar}</td></tr>
                        <tr class="fw-bold"><td>Kembalian:</td><td class="text-end">Rp ${kembalian}</td></tr>
                    </table>
                    <div class="dashed-line"></div>
                    <div class="text-center" style="margin-top: 6px;">
                        <div>${store.footer}</div>
                    </div>
                `;

                ThermalPrintHelper.showModal({
                    title: 'Invoice Thermal #' + (ord.nomor_invoice || ord.nomor_so),
                    subtitle: 'Pelanggan: ' + (ord.nama_pelanggan || 'Umum') + ' &bull; Total: Rp ' + grand,
                    previewHtml: previewHtml,
                    onPrintBluetooth: async () => {
                        await window.posBluetoothPrinter.printReceipt(ord, items, store);
                    },
                    onPrintSerial: async () => {
                        await window.posBluetoothPrinter.printReceipt(ord, items, store);
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
                    text: err.message || 'Tidak dapat memuat data cetak thermal.'
                });
            } finally {
                btn.prop('disabled', false).html(orig);
            }
        });
    });
</script>
<?= $this->endSection() ?>

