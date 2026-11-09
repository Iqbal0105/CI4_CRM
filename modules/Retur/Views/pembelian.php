<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @media print {
        @page {
            size: auto;
            margin: 8mm 6mm;
        }
        body {
            padding: 0 !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 9.5pt !important;
        }
        .d-print-none,
        .btn,
        .card-header,
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        #sidebar,
        .top-navbar,
        .sidebar-backdrop,
        .app-footer,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history {
            display: none !important;
        }
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
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
        }
        #tableReturBeli {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
        }
        #tableReturBeli th,
        #tableReturBeli td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableReturBeli th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #tableReturBeli th:last-child,
        #tableReturBeli td:last-child {
            display: none !important;
        }
        .badge {
            border: 1px solid #94a3b8 !important;
            color: #0f172a !important;
            background: transparent !important;
            font-size: 7.5pt !important;
            padding: 1px 4px !important;
            font-weight: 600 !important;
        }

        /* ==============================================================
           FORMAT KHUSUS CETAK DOKUMEN RETUR PEMBELIAN (MODAL)
           Naik ke atas halaman (top: 0, no vertical centering gap)
           ============================================================== */
        body:not(.modal-open) #modalDetailReturBeli {
            display: none !important;
        }

        body.modal-open {
            overflow: visible !important;
            height: auto !important;
            background: #fff !important;
        }
        body.modal-open .modal-backdrop {
            display: none !important;
        }
        /* Sembunyikan semua elemen selain modal saat cetak dokumen retur beli */
        body.modal-open .content-wrapper > *:not(#modalDetailReturBeli) {
            display: none !important;
        }
        body.modal-open .content-wrapper,
        body.modal-open #main-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        /* Posisi modal langsung di atas (naik ke atas halaman, bukan di tengah) */
        body.modal-open #modalDetailReturBeli {
            position: static !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            opacity: 1 !important;
            transform: none !important;
            top: 0 !important;
            left: 0 !important;
        }
        body.modal-open #modalDetailReturBeli .modal-dialog,
        body.modal-open #modalDetailReturBeli .modal-dialog-centered {
            display: block !important;
            position: static !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: none !important;
            top: 0 !important;
            left: 0 !important;
        }
        /* Hilangkan elemen pseudo-before yang memaksakan tinggi 100vh di Bootstrap modal */
        body.modal-open #modalDetailReturBeli .modal-dialog-centered::before {
            display: none !important;
            content: none !important;
            height: 0 !important;
            min-height: 0 !important;
        }
        body.modal-open #modalDetailReturBeli .modal-content {
            position: static !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 16px 20px !important;
            background: #ffffff !important;
            width: 100% !important;
        }
        body.modal-open #modalDetailReturBeli .modal-header {
            border-bottom: 2px solid #0f172a !important;
            padding: 0 0 10px 0 !important;
            margin-bottom: 14px !important;
            background: transparent !important;
        }
        body.modal-open #modalDetailReturBeli .modal-body {
            padding: 0 !important;
            margin: 0 !important;
        }
        body.modal-open #modalDetailReturBeli .modal-footer,
        body.modal-open #modalDetailReturBeli .btn-close {
            display: none !important;
        }
        /* Rincian item barang pada modal saat cetak */
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 6px !important;
        }
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems th,
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems td {
            display: table-cell !important;
            padding: 6px 10px !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems th:last-child,
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems td:last-child {
            display: table-cell !important;
        }
        body.modal-open #modalDetailReturBeli #tableDetailBeliItems th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4 d-print-none">
    <div>
        <h3 class="fw-bold mb-1">Retur Pembelian (Vendor Returns)</h3>
        <p class="text-muted small mb-0">Pengembalian barang ke supplier, pemotongan utang usaha, atau pengembalian kas.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
        <a href="<?= base_url('retur/penjualan') ?>" class="btn btn-outline-secondary flex-fill flex-sm-grow-0 text-center">
            <i class="fa-solid fa-arrow-left me-1"></i> Retur dari Pelanggan
        </a>
        <a href="<?= base_url('retur/pembelian/export/csv') ?>" class="btn btn-outline-primary flex-fill flex-sm-grow-0 text-center">
            <i class="fa-solid fa-file-excel me-1"></i> Export XLS
        </a>
        <button type="button" class="btn btn-outline-info flex-fill flex-sm-grow-0 text-center" onclick="printLaporanReturBeliTable()">
            <i class="fa-solid fa-file-pdf me-1"></i> Export PDF
        </button>
        <a href="<?= base_url('retur/pembelian/create') ?>" class="btn btn-danger flex-fill flex-sm-grow-0 d-flex align-items-center justify-content-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Retur Pembelian</span>
        </a>
    </div>
</div>

<div class="d-none d-print-block mb-3">
    <h4 class="fw-bold text-dark mb-1">LAPORAN DATA RETUR PEMBELIAN KE SUPPLIER</h4>
    <div class="small text-muted">Dicetak pada: <?= date('d/m/Y H:i') ?></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableReturBeli">
                <thead class="table-light">
                    <tr>
                        <th>No. Retur</th>
                        <th>Ref. Purchase Order</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Tindak Lanjut</th>
                        <th class="text-end">Nilai Retur</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($returns)): ?>
                        <?php foreach ($returns as $ret): ?>
                            <tr>
                                <td><span class="font-monospace fw-bold text-danger"><?= esc($ret['nomor_retur']) ?></span></td>
                                <td><span class="font-monospace text-dark"><?= esc($ret['nomor_po'] ?? '-') ?></span></td>
                                <td><?= esc($ret['nama_supplier'] ?? 'Supplier') ?></td>
                                <td data-order="<?= esc($ret['tanggal']) ?>"><?= format_tanggal($ret['tanggal']) ?></td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle text-capitalize">
                                        <?= str_replace('_', ' ', esc($ret['tindak_lanjut'])) ?>
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark"><?= format_rupiah($ret['total']) ?></td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="d-inline-flex gap-1 justify-content-center align-items-center">
                                        <button type="button" class="btn btn-sm btn-action-view p-0 d-inline-flex align-items-center justify-content-center rounded btn-detail-returbeli" data-id="<?= $ret['id'] ?>" data-nomor="<?= esc($ret['nomor_retur']) ?>" data-supplier="<?= esc($ret['nama_supplier'] ?? 'Supplier') ?>" data-po="<?= esc($ret['nomor_po'] ?? '-') ?>" data-tgl="<?= date('d/m/Y', strtotime($ret['tanggal'])) ?>" data-total="<?= format_rupiah($ret['total']) ?>" data-tindak="<?= esc($ret['tindak_lanjut']) ?>" data-alasan="<?= esc($ret['alasan'] ?? '-') ?>" style="width: 30px; height: 30px;" title="Lihat Detail Retur Pembelian">
                                            <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-retur-pembelian-thermal" data-id="<?= $ret['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Bukti Retur Thermal">
                                            <i class="fa-solid fa-receipt text-warning" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info text-white p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-surat-beli-row" data-id="<?= $ret['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Dokumen Retur">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <a href="<?= base_url('retur/pembelian/delete/' . $ret['id']) ?>" class="btn btn-sm btn-danger p-0 d-inline-flex align-items-center justify-content-center rounded btn-action-delete" style="width: 30px; height: 30px;" title="Hapus Retur Pembelian">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4 small">
                                Belum ada transaksi retur pembelian.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL RETUR PEMBELIAN -->
<div class="modal fade" id="modalDetailReturBeli" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Detail Retur Pembelian ke Vendor</h5>
                    <span class="font-monospace text-danger small" id="beliNomorRetur">RTB-XXXX</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Vendor / Supplier:</label>
                        <div class="fw-bold text-dark" id="beliSupplier">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Ref. Purchase Order:</label>
                        <div class="font-monospace text-primary fw-semibold" id="beliRefPo">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Tanggal Retur:</label>
                        <div class="font-monospace" id="beliTanggal">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Tindak Lanjut:</label>
                        <div><span class="badge bg-danger text-white px-2 py-1" id="beliTindakLanjut">-</span></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Total Nilai Retur:</label>
                        <div class="fs-5 fw-bold text-danger font-monospace" id="beliTotalRetur">Rp 0</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Alasan Pengembalian Barang:</label>
                        <div class="p-2 bg-light rounded text-dark small" id="beliAlasan">-</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-boxes-stacked text-danger me-1"></i>Daftar Barang yang Dikembalikan</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0" id="tableDetailBeliItems">
                        <thead class="table-light small">
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center" style="width: 100px;">Qty Retur</th>
                                <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                <th class="text-end" style="width: 140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="beliItemsBody">
                            <tr>
                                <td colspan="4" class="text-center text-muted small py-3">Memuat rincian barang...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tanda Tangan Khusus Cetak Dokumen Retur Beli -->
                <div class="row mt-4 pt-3 d-none d-print-flex text-center">
                    <div class="col-4">
                        <div class="small text-muted mb-5">Dibuat Oleh (Purchasing)</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted mb-5">Petugas Gudang / Ekspedisi</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted mb-5">Penerima (Vendor / Supplier)</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-dark btn-sm" id="btnModalCetakThermalReturBeli">
                    <i class="fa-solid fa-receipt text-warning me-1"></i>Cetak Thermal
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="printSuratReturBeli()">
                    <i class="fa-solid fa-print me-1"></i>Cetak Dokumen Retur Vendor
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Helper Pengaturan Orientasi Cetak (Landscape untuk tabel, Portrait untuk Dokumen Retur)
    window.setPrintOrientationBeli = function(orientation) {
        let style = document.getElementById('print-orientation-style');
        if (!style) {
            style = document.createElement('style');
            style.id = 'print-orientation-style';
            document.head.appendChild(style);
        }
        const margin = orientation === 'landscape' ? '8mm 6mm' : '10mm 12mm';
        style.innerHTML = `@media print { @page { size: ${orientation}; margin: ${margin}; } }`;
    };

    window.printLaporanReturBeliTable = function() {
        window.setPrintOrientationBeli('landscape');
        window.print();
    };

    window.printSuratReturBeli = function() {
        window.setPrintOrientationBeli('portrait');
        window.print();
    };

    window.addEventListener('afterprint', function() {
        const style = document.getElementById('print-orientation-style');
        if (style) style.remove();
    });

    $(document).ready(function() {
        $('#tableReturBeli').DataTable({
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            language: {
                emptyTable: "Belum ada data retur pembelian ke supplier.",
                zeroRecords: "Tidak ada data retur yang cocok dengan pencarian",
                search: "Cari Retur:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { previous: "&larr;", next: "&rarr;" }
            }
        });

        $(document).on('click', '.btn-detail-returbeli', function() {
            const btn = $(this);
            const id = btn.data('id');
            $('#beliNomorRetur').text(btn.data('nomor'));
            $('#beliSupplier').text(btn.data('supplier'));
            $('#beliRefPo').text(btn.data('po'));
            $('#beliTanggal').text(btn.data('tgl'));
            $('#beliTindakLanjut').text(btn.data('tindak'));
            $('#beliTotalRetur').text(btn.data('total'));
            $('#beliAlasan').text(btn.data('alasan'));

            if (id) {
                $.getJSON('<?= base_url('retur/pembelian/detail') ?>/' + id, function(res) {
                    if (res && res.status === 'success' && res.items.length > 0) {
                        let rows = '';
                        res.items.forEach(function(it) {
                            rows += `<tr>
                                <td><div class="fw-semibold text-dark">${it.nama_produk || '-'}</div><small class="text-muted font-monospace">${it.kode_produk || ''}</small></td>
                                <td class="text-center font-monospace">${Number(it.qty).toLocaleString('id-ID')} ${it.nama_satuan || 'PCS'}</td>
                                <td class="text-end font-monospace">Rp ${Number(it.harga_satuan).toLocaleString('id-ID')}</td>
                                <td class="text-end font-monospace fw-bold">Rp ${Number(it.subtotal).toLocaleString('id-ID')}</td>
                            </tr>`;
                        });
                        $('#beliItemsBody').html(rows);
                    } else {
                        $('#beliItemsBody').html('<tr><td colspan="4" class="text-center text-muted small py-2">Tidak ada rincian item tercatat.</td></tr>');
                    }
                }).fail(function() {
                    $('#beliItemsBody').html('<tr><td colspan="4" class="text-center text-muted small py-2">Rincian item tidak tersedia.</td></tr>');
                });
            }

            const modal = new bootstrap.Modal(document.getElementById('modalDetailReturBeli'));
            modal.show();
        });

        let currentActiveReturBeliId = null;
        $(document).on('click', '.btn-detail-returbeli', function() {
            currentActiveReturBeliId = $(this).data('id');
        });

        $('#btnModalCetakThermalReturBeli').on('click', function() {
            if (currentActiveReturBeliId) {
                triggerThermalReturPembelian(currentActiveReturBeliId);
            }
        });

        $(document).on('click', '.btn-print-retur-pembelian-thermal', function() {
            const id = $(this).data('id');
            triggerThermalReturPembelian(id);
        });

        // Handler Cetak Dokumen Retur langsung dari baris tabel
        $(document).on('click', '.btn-print-surat-beli-row', function() {
            const row = $(this).closest('tr');
            const detailBtn = row.find('.btn-detail-returbeli');
            detailBtn.trigger('click');
            setTimeout(function() {
                printSuratReturBeli();
            }, 450);
        });

        async function triggerThermalReturPembelian(id) {
            try {
                const res = await $.getJSON('<?= base_url('retur/pembelian/detail') ?>/' + id);
                if (!res || res.status !== 'success') {
                    throw new Error('Data retur tidak ditemukan');
                }

                const ret = res.retur;
                const items = res.items || [];
                const store = res.store || { name: 'METAFORA ERP STORE' };

                let itemsHtml = '';
                items.forEach((it, idx) => {
                    itemsHtml += `
                        <tr>
                            <td colspan="3" class="fw-bold">${it.nama_produk || 'Produk'}</td>
                        </tr>
                        <tr>
                            <td>${parseFloat(it.qty)} x ${Math.round(parseFloat(it.harga_satuan)).toLocaleString('id-ID')}</td>
                            <td></td>
                            <td class="text-end font-monospace">Rp ${Math.round(parseFloat(it.subtotal)).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                });

                const total = Math.round(parseFloat(ret.total || 0)).toLocaleString('id-ID');

                const previewHtml = `
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 14px;">BUKTI RETUR PEMBELIAN</div>
                        <div class="small fw-semibold text-secondary">Pengembalian ke Supplier / Vendor</div>
                    </div>
                    <div class="double-line"></div>
                    <div class="small">
                        <div>No. Retur : <strong class="font-monospace">${ret.nomor_retur || '-'}</strong></div>
                        <div>No. PO    : ${ret.nomor_po || '-'}</div>
                        <div>Supplier  : <strong>${ret.nama_supplier || 'Supplier'}</strong></div>
                        <div>Tanggal   : ${ret.tanggal || '-'}</div>
                        <div>Tindak    : <span class="badge bg-danger text-white">${(ret.tindak_lanjut || '-').toUpperCase()}</span></div>
                        ${ret.alasan ? '<div>Alasan: ' + ret.alasan + '</div>' : ''}
                    </div>
                    <div class="dashed-line"></div>
                    <table>${itemsHtml}</table>
                    <div class="dashed-line"></div>
                    <table>
                        <tr class="fw-bold">
                            <td>TOTAL NILAI RETUR:</td>
                            <td class="text-end">Rp ${total}</td>
                        </tr>
                    </table>
                    <div class="dashed-line"></div>
                    <div class="text-center small text-muted mt-2">
                        <div>Barang diserahkan kembali kepada vendor untuk diganti / dipotong utang.</div>
                    </div>
                `;

                ThermalPrintHelper.showModal({
                    title: 'Retur Pembelian #' + (ret.nomor_retur || id),
                    subtitle: 'Supplier: ' + (ret.nama_supplier || 'Vendor') + ' &bull; Total: Rp ' + total,
                    previewHtml: previewHtml,
                    onPrintBluetooth: async () => {
                        await window.posBluetoothPrinter.printRetur('pembelian', ret, items, store);
                    },
                    onPrintSerial: async () => {
                        await window.posBluetoothPrinter.printRetur('pembelian', ret, items, store);
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
                    text: err.message || 'Tidak dapat memuat bukti retur pembelian.'
                });
            }
        }
    });
</script>
<?= $this->endSection() ?>

