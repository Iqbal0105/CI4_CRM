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
        #tableSales {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
        }
        #tableSales th,
        #tableSales td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableSales th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #tableSales th:last-child,
        #tableSales td:last-child {
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
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Penjualan</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Pesanan &amp; Transaksi Penjualan</span>
    </div>
    <div class="small text-muted d-print-none">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Penjualan</span>
    </div>
</div>

<!-- SUB-NAVIGATION TABS (ALUR PENJUALAN LENGKAP) -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-2 bg-white rounded-3 border shadow-xs d-print-none">
    <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-xs">
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
    <a href="<?= base_url('penjualan/invoice') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold">
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
            <i class="fa-solid fa-receipt text-primary"></i>
            <span>Daftar Dokumen Penjualan</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('pos') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-bolt"></i>
                <span>Penjualan Cepat (POS)</span>
            </a>
            <a href="<?= base_url('penjualan/create') ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Penjualan</span>
            </a>
            <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-address-book"></i>
                <span>Data Pelanggan</span>
            </a>
            <a href="<?= base_url('penjualan/export') ?>" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-file-excel text-success"></i>
                <span>Export Excel</span>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" onclick="printTable()">
                <i class="fa-solid fa-file-pdf text-danger"></i>
                <span>Cetak PDF</span>
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Pelanggan:</label>
                <select class="form-select form-select-sm" id="filterPelanggan">
                    <option value="">-- Semua Pelanggan --</option>
                    <?php if (!empty($pelangganList)): ?>
                        <?php foreach ($pelangganList as $pel): ?>
                            <option value="<?= esc($pel['nama_pelanggan']) ?>"><?= esc($pel['nama_pelanggan']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Saluran / Channel:</label>
                <select class="form-select form-select-sm" id="filterSaluran">
                    <option value="">-- Semua Saluran --</option>
                    <option value="POS">POS Kasir</option>
                    <option value="Offline">Offline / Regular</option>
                    <option value="Shopee">Shopee</option>
                    <option value="TikTok">TikTok Shop</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label small text-muted mb-1">Cari No. Transaksi Penjualan / Nama:</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="customSearchSO" class="form-control" placeholder="Ketik No. Transaksi / Nama...">
                    <button class="btn btn-primary" type="button" id="btnSearchSO">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetSO" title="Reset">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableSales">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 45px;">No</th>
                        <th>No. Transaksi Penjualan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Pelanggan</th>
                        <th class="text-end">Grand Total (Rp)</th>
                        <th class="text-center">Metode Pembayaran</th>
                        <th class="text-center d-print-none" style="width: 85px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($orders as $order): ?>
                        <tr>
                            <td class="text-muted small"><?= $no++ ?></td>
                            <td>
                                <div class="font-monospace fw-bold text-primary"><?= esc($order['nomor_so']) ?></div>
                                <?php if ($order['source'] === 'pos'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;">+ POS Kasir (Cepat)</span>
                                <?php elseif (in_array($order['source'], ['shopee', 'tiktok', 'tokopedia', 'lazada'])): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.75rem;"><?= ucfirst(esc($order['source'])) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.75rem;">Transaksi Offline</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-secondary font-monospace" data-order="<?= esc($order['tanggal_so']) ?>">
                                <i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($order['tanggal_so'])) ?>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?= esc($order['nama_pelanggan'] ?? 'Pelanggan Walk-In') ?></div>
                                <?php if (!empty($order['no_telp'])): ?>
                                    <small class="text-muted font-monospace"><i class="fa-solid fa-phone me-1" style="font-size: 0.75rem;"></i><?= esc($order['no_telp']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-end font-monospace fw-bold text-dark small">
                                <?= format_rupiah($order['total']) ?>
                            </td>
                            <td class="text-center">
                                <div class="small fw-semibold text-dark">
                                    <i class="fa-solid fa-wallet text-secondary me-1"></i><?= ucfirst(esc($order['metode_bayar'] ?? $order['metode_pembayaran'] ?? 'Tunai (Cash)')) ?>
                                </div>
                                <?php if ($order['source'] === 'pos'): ?>
                                    <span class="badge bg-success rounded-pill px-2 py-0" style="font-size: 0.75rem;">&#10003; Lunas (POS)</span>
                                <?php elseif (in_array($order['source'], ['shopee', 'tiktok', 'tokopedia', 'lazada'])): ?>
                                    <span class="badge bg-primary rounded-pill px-2 py-0" style="font-size: 0.75rem;">&#10003; Lunas (Marketplace)</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0" style="font-size: 0.75rem;">&#10003; Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center d-print-none" style="vertical-align: middle;">
                                <div class="d-grid gap-1 justify-content-center mx-auto" style="grid-template-columns: repeat(2, 30px); width: fit-content;">
                                    <a href="<?= base_url('penjualan/detail/' . $order['id']) ?>" class="btn btn-sm btn-action-view p-0 d-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Lihat Detail Transaksi">
                                        <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-dark p-0 d-flex align-items-center justify-content-center rounded btn-print-thermal-so" data-id="<?= $order['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Struk Thermal (Bluetooth/USB/Browser)">
                                        <i class="fa-solid fa-receipt text-warning" style="font-size: 0.8rem;"></i>
                                    </button>
                                    <a href="<?= base_url('penjualan/invoice-pdf/' . $order['id']) ?>" target="_blank" class="btn btn-sm btn-primary p-0 d-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Cetak PDF">
                                        <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <?php if (!empty($order['nomor_invoice'])): ?>
                                        <a href="<?= base_url('invoice') ?>" class="btn btn-sm btn-info text-white p-0 d-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Lihat Invoice: <?= esc($order['nomor_invoice']) ?>">
                                            <i class="fa-solid fa-file-invoice" style="font-size: 0.8rem;"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="btn btn-sm btn-light border p-0 d-flex align-items-center justify-content-center rounded opacity-50" style="width: 30px; height: 30px; cursor: not-allowed;" title="Belum Ada Invoice">
                                            <i class="fa-solid fa-file-invoice text-muted" style="font-size: 0.8rem;"></i>
                                        </span>
                                    <?php endif; ?>
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
        Total: <strong><?= count($orders) ?> Transaksi Penjualan</strong>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let salesTable = null;

    function printTable() {
        if (!salesTable) {
            window.print();
            return;
        }

        const prevLen = salesTable.page.len();
        // Tampilkan seluruh baris untuk dicetak
        salesTable.page.len(-1).draw();

        setTimeout(function() {
            window.print();
            // Kembalikan ke pagination semula setelah cetak
            salesTable.page.len(prevLen).draw();
        }, 300);
    }

    $(document).ready(function() {
        salesTable = $('#tableSales').DataTable({
            dom: 'rtip',
            pageLength: 10,
            order: [[2, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 6] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearchSO').on('click', function() {
            salesTable.search($('#customSearchSO').val()).draw();
        });

        $('#customSearchSO').on('keyup', function(e) {
            if (e.key === 'Enter') {
                salesTable.search(this.value).draw();
            }
        });

        $('#btnResetSO').on('click', function() {
            $('#customSearchSO').val('');
            $('#filterPelanggan').val('');
            $('#filterSaluran').val('');
            salesTable.search('').columns().search('').draw();
        });

        $('#filterPelanggan').on('change', function() {
            salesTable.column(3).search(this.value).draw();
        });

        $('#filterSaluran').on('change', function() {
            salesTable.column(1).search(this.value).draw();
        });

        // Thermal Print Handler
        $(document).on('click', '.btn-print-thermal-so', async function() {
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
                    <div>No. Trx : ${ord.nomor_so || '-'}</div>
                    <div>No. Inv : ${ord.nomor_invoice || '-'}</div>
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
                    title: 'Cetak Thermal #' + ord.nomor_so,
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

