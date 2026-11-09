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
        .filter-box,
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
        #tableReturPenjualan {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
        }
        #tableReturPenjualan th,
        #tableReturPenjualan td {
            padding: 6px 8px !important;
            border: 1px solid #dee2e6 !important;
            white-space: normal !important;
            word-break: normal !important;
            color: #0f172a !important;
        }
        #tableReturPenjualan th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        /* Sembunyikan kolom AKSI terakhir saat cetak tabel laporan */
        #tableReturPenjualan th:last-child,
        #tableReturPenjualan td:last-child {
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
           FORMAT KHUSUS CETAK SURAT RETUR (MODAL DETAIL RETUR)
           Naik ke atas halaman (top: 0, no vertical centering gap)
           ============================================================== */
        body:not(.modal-open) #modalDetailRetur {
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
        /* Sembunyikan semua elemen selain modal saat cetak surat retur */
        body.modal-open .content-wrapper > *:not(#modalDetailRetur) {
            display: none !important;
        }
        body.modal-open .content-wrapper,
        body.modal-open #main-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        /* Posisi modal langsung di atas (naik ke atas halaman, bukan di tengah) */
        body.modal-open #modalDetailRetur {
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
        body.modal-open #modalDetailRetur .modal-dialog,
        body.modal-open #modalDetailRetur .modal-dialog-centered {
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
        body.modal-open #modalDetailRetur .modal-dialog-centered::before {
            display: none !important;
            content: none !important;
            height: 0 !important;
            min-height: 0 !important;
        }
        body.modal-open #modalDetailRetur .modal-content {
            position: static !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 16px 20px !important;
            background: #ffffff !important;
            width: 100% !important;
        }
        body.modal-open #modalDetailRetur .modal-header {
            border-bottom: 2px solid #0f172a !important;
            padding: 0 0 10px 0 !important;
            margin-bottom: 14px !important;
            background: transparent !important;
        }
        body.modal-open #modalDetailRetur .modal-body {
            padding: 0 !important;
            margin: 0 !important;
        }
        body.modal-open #modalDetailRetur .modal-footer,
        body.modal-open #modalDetailRetur .btn-close {
            display: none !important;
        }
        /* Rincian item barang pada modal surat retur */
        body.modal-open #modalDetailRetur #tableDetailItems {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 6px !important;
        }
        body.modal-open #modalDetailRetur #tableDetailItems th,
        body.modal-open #modalDetailRetur #tableDetailItems td {
            display: table-cell !important;
            padding: 6px 10px !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.modal-open #modalDetailRetur #tableDetailItems th:last-child,
        body.modal-open #modalDetailRetur #tableDetailItems td:last-child {
            display: table-cell !important;
        }
        body.modal-open #modalDetailRetur #tableDetailItems th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3 d-print-none">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Retur Penjualan</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Daftar Retur Pelanggan</span>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Retur Penjualan</span>
    </div>
</div>

<!-- Header Judul Khusus Cetak / PDF -->
<div class="d-none d-print-block mb-3">
    <h4 class="fw-bold text-dark mb-1">LAPORAN DATA RETUR PENJUALAN</h4>
    <div class="small text-muted">Dicetak pada: <?= date('d/m/Y H:i') ?></div>
</div>

<div class="card shadow-sm border mb-4">
    <!-- CARD HEADER -->
    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3 d-print-none">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-rotate-left text-primary"></i>
            <span>Data Retur Penjualan</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('retur/penjualan/export/csv') ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export XLS</span>
            </a>
            <button type="button" class="btn btn-info btn-sm text-white d-inline-flex align-items-center gap-1 shadow-sm" onclick="printLaporanTable()">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
            <a href="<?= base_url('retur/penjualan/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Retur Baru</span>
            </a>
        </div>
    </div>

    <!-- FILTER BOX -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3 d-print-none">
        <div class="row g-3">
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-regular fa-calendar me-1"></i>Rentang Tanggal Mulai:</label>
                <input type="date" id="tglMulai" class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-regular fa-calendar me-1"></i>Rentang Tanggal Selesai:</label>
                <input type="date" id="tglSelesai" class="form-control form-control-sm">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-calendar-days me-1"></i>Bulan:</label>
                <select class="form-select form-select-sm" id="filterBulan">
                    <option value="">-- Semua Bulan --</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= sprintf('%02d', $m) ?>"><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-regular fa-clock me-1"></i>Tahun:</label>
                <select class="form-select form-select-sm" id="filterTahun">
                    <option value="">-- Semua Tahun --</option>
                    <option value="2026" selected>2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="col-12 col-md-7">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-magnifying-glass me-1"></i>Kata Kunci (No. Retur / Invoice / Pelanggan):</label>
                <input type="text" id="customSearchRetur" class="form-control form-control-sm" placeholder="Cari nomor retur, invoice, atau nama pelanggan...">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-tag me-1"></i>Status Retur:</label>
                <select class="form-select form-select-sm" id="filterStatusRetur">
                    <option value="">-- Semua Status --</option>
                    <option value="SELESAI">Selesai</option>
                    <option value="DRAFT">Draft</option>
                    <option value="APPROVED">Approved</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-end gap-1">
                <button type="button" class="btn btn-primary btn-sm flex-fill" id="btnFilterRetur">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnResetRetur" title="Reset">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableReturPenjualan">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>NO. RETUR</th>
                        <th>INVOICE</th>
                        <th>PELANGGAN</th>
                        <th class="text-center">TGL. RETUR</th>
                        <th class="text-end">TOTAL</th>
                        <th class="text-center">TINDAK LANJUT</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($returns)): ?>
                        <?php foreach ($returns as $ret): ?>
                            <tr>
                                <td>
                                    <span class="font-monospace fw-bold text-dark small"><?= esc($ret['nomor_retur']) ?></span>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted small"><?= esc($ret['nomor_so'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark small"><?= esc($ret['nama_pelanggan'] ?? '-') ?></span>
                                </td>
                                <td class="text-center small text-secondary font-monospace" data-order="<?= esc($ret['tanggal']) ?>">
                                    <?= date('d/m/Y', strtotime($ret['tanggal'])) ?>
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark small">
                                    <?= format_rupiah($ret['total']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.78rem;">
                                        ★ <?= ucfirst(str_replace('_', ' ', esc($ret['tindak_lanjut']))) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                                        SELESAI
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="d-inline-flex gap-1 justify-content-center align-items-center">
                                        <button type="button" class="btn btn-sm btn-action-view p-0 d-inline-flex align-items-center justify-content-center rounded btn-detail-retur" data-id="<?= $ret['id'] ?>" data-nomor="<?= esc($ret['nomor_retur']) ?>" data-pelanggan="<?= esc($ret['nama_pelanggan'] ?? 'Pelanggan') ?>" data-so="<?= esc($ret['nomor_so'] ?? '-') ?>" data-tgl="<?= date('d/m/Y', strtotime($ret['tanggal'])) ?>" data-total="<?= format_rupiah($ret['total']) ?>" data-tindak="<?= esc($ret['tindak_lanjut']) ?>" data-kondisi="<?= esc($ret['kondisi_barang'] ?? 'baik') ?>" data-alasan="<?= esc($ret['alasan'] ?? 'Retur barang pelanggan') ?>" style="width: 30px; height: 30px;" title="Lihat Detail Retur">
                                            <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-retur-penjualan-thermal" data-id="<?= $ret['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Bukti Retur Thermal">
                                            <i class="fa-solid fa-receipt text-warning" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info text-white p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-surat-row" data-id="<?= $ret['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Surat Retur">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <a href="<?= base_url('retur/penjualan/delete/' . $ret['id']) ?>" class="btn btn-sm btn-danger p-0 d-inline-flex align-items-center justify-content-center rounded btn-action-delete" style="width: 30px; height: 30px;" title="Hapus Data Retur">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4 small">
                                Belum ada transaksi retur penjualan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CARD FOOTER -->
    <div class="card-footer bg-white py-3 text-muted small">
        Total: <strong><?= !empty($returns) ? count($returns) : 0 ?> record Retur</strong>
    </div>
</div>

<!-- MODAL DETAIL RETUR PENJUALAN -->
<div class="modal fade" id="modalDetailRetur" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Detail Retur Penjualan</h5>
                    <span class="font-monospace text-primary small" id="detailNomorRetur">RTJ-XXXX</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Pelanggan:</label>
                        <div class="fw-bold text-dark" id="detailPelanggan">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Ref. Sales Order:</label>
                        <div class="font-monospace text-primary fw-semibold" id="detailRefSo">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Tanggal Retur:</label>
                        <div class="font-monospace" id="detailTanggal">-</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Tindak Lanjut:</label>
                        <div><span class="badge bg-warning text-dark px-2 py-1" id="detailTindakLanjut">-</span></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small">Kondisi Barang:</label>
                        <div><span class="badge bg-info text-white px-2 py-1" id="detailKondisi">-</span></div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="text-muted small">Grand Total:</label>
                        <div class="fs-5 fw-bold text-success font-monospace" id="detailTotalRetur">Rp 0</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Alasan Pengajuan Retur:</label>
                        <div class="p-2 bg-light rounded text-dark small" id="detailAlasan">-</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-boxes-stacked text-primary me-1"></i>Barang yang Diretur</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0" id="tableDetailItems">
                        <thead class="table-light small">
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center" style="width: 100px;">Qty Retur</th>
                                <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                <th class="text-end" style="width: 140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detailItemsBody">
                            <tr>
                                <td colspan="4" class="text-center text-muted small py-3">Memuat rincian barang...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tanda Tangan Khusus Cetak Surat Retur -->
                <div class="row mt-4 pt-3 d-none d-print-flex text-center">
                    <div class="col-4">
                        <div class="small text-muted mb-5">Yang Mengajukan (Pelanggan)</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted mb-5">Bagian Gudang</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted mb-5">Menyetujui (Admin/Kasir)</div>
                        <div class="fw-bold text-dark">( .................................... )</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-dark btn-sm" id="btnModalCetakThermalRetur">
                    <i class="fa-solid fa-receipt text-warning me-1"></i>Cetak Thermal
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="printSuratRetur()">
                    <i class="fa-solid fa-print me-1"></i>Cetak Surat Retur
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Helper Pengaturan Orientasi Cetak (Landscape untuk tabel, Portrait untuk Surat Retur)
    window.setPrintOrientation = function(orientation) {
        let style = document.getElementById('print-orientation-style');
        if (!style) {
            style = document.createElement('style');
            style.id = 'print-orientation-style';
            document.head.appendChild(style);
        }
        const margin = orientation === 'landscape' ? '8mm 6mm' : '10mm 12mm';
        style.innerHTML = `@media print { @page { size: ${orientation}; margin: ${margin}; } }`;
    };

    window.printLaporanTable = function() {
        window.setPrintOrientation('landscape');
        window.print();
    };

    window.printSuratRetur = function() {
        window.setPrintOrientation('portrait');
        window.print();
    };

    window.addEventListener('afterprint', function() {
        const style = document.getElementById('print-orientation-style');
        if (style) style.remove();
    });

    $(document).ready(function() {
        const table = $('#tableReturPenjualan').DataTable({
            dom: 'rtip',
            pageLength: 10,
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [7] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnFilterRetur').on('click', function() {
            table.search($('#customSearchRetur').val()).draw();
        });

        $('#customSearchRetur').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.search(this.value).draw();
            }
        });

        $('#btnResetRetur').on('click', function() {
            $('#customSearchRetur').val('');
            $('#tglMulai').val('');
            $('#tglSelesai').val('');
            $('#filterBulan').val('');
            $('#filterStatusRetur').val('');
            table.search('').columns().search('').draw();
        });

        $('#filterStatusRetur').on('change', function() {
            table.column(6).search(this.value).draw();
        });

        $(document).on('click', '.btn-detail-retur', function() {
            const btn = $(this);
            const id = btn.data('id');
            $('#detailNomorRetur').text(btn.data('nomor'));
            $('#detailPelanggan').text(btn.data('pelanggan'));
            $('#detailRefSo').text(btn.data('so'));
            $('#detailTanggal').text(btn.data('tgl'));
            $('#detailTindakLanjut').text(btn.data('tindak'));
            $('#detailKondisi').text(btn.data('kondisi'));
            $('#detailTotalRetur').text(btn.data('total'));
            $('#detailAlasan').text(btn.data('alasan'));

            if (id) {
                $.getJSON('<?= base_url('retur/penjualan/detail') ?>/' + id, function(res) {
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
                        $('#detailItemsBody').html(rows);
                    } else {
                        $('#detailItemsBody').html('<tr><td colspan="4" class="text-center text-muted small py-2">Tidak ada item tercatat dalam retur ini.</td></tr>');
                    }
                }).fail(function() {
                    $('#detailItemsBody').html('<tr><td colspan="4" class="text-center text-muted small py-3">Gagal memuat rincian item.</td></tr>');
                });
            } else {
                $('#detailItemsBody').html('<tr><td colspan="4" class="text-center text-muted small py-3">Rincian item tidak tersedia.</td></tr>');
            }

            const modal = new bootstrap.Modal(document.getElementById('modalDetailRetur'));
            modal.show();
        });

        let currentActiveReturId = null;
        $(document).on('click', '.btn-detail-retur', function() {
            currentActiveReturId = $(this).data('id');
        });

        $('#btnModalCetakThermalRetur').on('click', function() {
            if (currentActiveReturId) {
                triggerThermalReturPenjualan(currentActiveReturId);
            }
        });

        $(document).on('click', '.btn-print-retur-penjualan-thermal', function() {
            const id = $(this).data('id');
            triggerThermalReturPenjualan(id);
        });

        // Handler Cetak Surat Retur langsung dari baris tabel
        $(document).on('click', '.btn-print-surat-row', function() {
            const row = $(this).closest('tr');
            const detailBtn = row.find('.btn-detail-retur');
            detailBtn.trigger('click');
            setTimeout(function() {
                printSuratRetur();
            }, 450);
        });

        async function triggerThermalReturPenjualan(id) {
            try {
                const res = await $.getJSON('<?= base_url('retur/penjualan/detail') ?>/' + id);
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
                        <div class="fw-bold" style="font-size: 14px;">BUKTI RETUR PENJUALAN</div>
                        <div class="small fw-semibold text-secondary">${store.name}</div>
                    </div>
                    <div class="double-line"></div>
                    <div class="small">
                        <div>No. Retur : <strong class="font-monospace">${ret.nomor_retur || '-'}</strong></div>
                        <div>No. SO    : ${ret.nomor_so || '-'}</div>
                        <div>Pelanggan : <strong>${ret.nama_pelanggan || 'Pelanggan'}</strong></div>
                        <div>Tanggal   : ${ret.tanggal || '-'}</div>
                        <div>Tindak    : <span class="badge bg-warning text-dark">${(ret.tindak_lanjut || '-').toUpperCase()}</span></div>
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
                        <div>Dokumen ini merupakan bukti sah pengembalian barang.</div>
                    </div>
                `;

                ThermalPrintHelper.showModal({
                    title: 'Retur Penjualan #' + (ret.nomor_retur || id),
                    subtitle: 'Pelanggan: ' + (ret.nama_pelanggan || 'Umum') + ' &bull; Total: Rp ' + total,
                    previewHtml: previewHtml,
                    onPrintBluetooth: async () => {
                        await window.posBluetoothPrinter.printRetur('penjualan', ret, items, store);
                    },
                    onPrintSerial: async () => {
                        await window.posBluetoothPrinter.printRetur('penjualan', ret, items, store);
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
                    text: err.message || 'Tidak dapat memuat bukti retur thermal.'
                });
            }
        }
    });
</script>
<?= $this->endSection() ?>

