<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @media print {
        @page {
            size: landscape;
            margin: 8mm 6mm;
        }
        body {
            padding: 8mm 6mm !important;
            background: #ffffff !important;
            color: #0f172a !important;
            font-size: 8pt !important;
        }
        .d-print-none,
        .btn,
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history,
        th:last-child,
        td:last-child {
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
            background: transparent !important;
        }
        .card-header {
            display: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
        }
        #tableBkkList {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 7.8pt !important;
            margin-top: 5px;
        }
        #tableBkkList thead {
            display: table-header-group !important;
        }
        #tableBkkList tbody {
            display: table-row-group !important;
        }
        #tableBkkList tr {
            page-break-inside: avoid !important;
        }
        #tableBkkList th,
        #tableBkkList td {
            padding: 5px 6px !important;
            border: 1px solid #cbd5e1 !important;
            white-space: normal !important;
            word-break: break-word !important;
            color: #0f172a !important;
            line-height: 1.25 !important;
        }
        #tableBkkList th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #tableBkkList tfoot td {
            background-color: #f8fafc !important;
            font-weight: 700 !important;
            border-top: 2px solid #94a3b8 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 d-print-none">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Pengeluaran Kas (BKK)</h4>
        <p class="text-muted small mb-0">Riwayat Pengeluaran Uang Kas &amp; Bank Pelunasan Utang</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="<?= base_url('keuangan/bkk/export') ?>" class="btn btn-sm btn-outline-success fw-semibold d-inline-flex align-items-center gap-1 shadow-xs">
            <i class="fa-solid fa-file-excel"></i> Export XLS
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" onclick="window.print()">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
        <a href="<?= base_url('keuangan/utang') ?>" class="btn btn-sm btn-primary fw-semibold px-3 d-inline-flex align-items-center gap-1">
            <i class="fa-solid fa-book-bookmark me-1"></i>Buku Utang Usaha
        </a>
    </div>
</div>

<!-- DEDICATED REPORT HEADER FOR PRINT / PDF EXPORT -->
<div class="d-none d-print-block mb-3 border-bottom pb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 13pt;">LAPORAN PENGELUARAN KAS (BUKTI KAS KELUAR - BKK)</h4>
            <div class="small text-muted">IDMETAFORA ERP Enterprise &bull; Rekap Data Pembayaran Utang &amp; Mutasi Kas Keluar</div>
        </div>
        <div class="text-end small text-muted">
            <div>Dicetak pada: <strong><?= date('d/m/Y H:i') ?></strong></div>
            <div>Total: <strong><?= count($bkkList) ?></strong> Bukti Kas Keluar</div>
        </div>
    </div>
</div>

<!-- CARD DAFTAR BUKTI KAS KELUAR BKK -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-print-none">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-receipt text-danger"></i> Daftar Bukti Kas Keluar (BKK)
        </h6>
    </div>
    <div class="card-body p-3">
        <!-- FILTER ROW -->
        <div class="row g-2 align-items-center mb-3 d-print-none">
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Pilih Supplier:</label>
                <select class="form-select form-select-sm" id="filterSupplierBkk">
                    <option value="">-- Semua Supplier --</option>
                    <?php 
                        $supplierUnique = array_unique(array_column($bkkList, 'nama_supplier'));
                        foreach ($supplierUnique as $sName):
                            if (!empty($sName)):
                    ?>
                        <option value="<?= esc($sName) ?>"><?= esc($sName) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1">Metode Pembayaran:</label>
                <select class="form-select form-select-sm">
                    <option value="">-- Semua Metode --</option>
                    <option value="transfer">Bank Transfer</option>
                    <option value="cash">Tunai</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1">Cari Nomor / Keyword:</label>
                <input type="text" id="searchBkk" class="form-control form-control-sm" placeholder="Cari No. BKK / Tagihan / Supplier...">
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-primary btn-sm w-100 fw-semibold" id="btnFilterBkk">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableBkkList">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">No</th>
                        <th>No. Bukti Kas Keluar</th>
                        <th>Tanggal Bayar</th>
                        <th>Supplier</th>
                        <th>No. Tagihan Utang</th>
                        <th>Akun Kas / Bank</th>
                        <th>Metode &amp; Referensi</th>
                        <th>Tipe Transaksi</th>
                        <th class="text-end">Nominal Keluar (Rp)</th>
                        <th>Dicatat Oleh</th>
                        <th class="pe-3 text-end d-print-none">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $grandTotalBkk = 0;
                        if (!empty($bkkList)): 
                    ?>
                        <?php foreach ($bkkList as $idx => $bkk): 
                            $grandTotalBkk += (float)($bkk['nominal'] ?? 0);
                        ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary"><?= esc($bkk['nomor_bkk']) ?></span>
                                </td>
                                <td class="small text-secondary font-monospace">
                                    <i class="fa-regular fa-calendar me-1 d-print-none"></i><?= date('d/m/Y', strtotime($bkk['tanggal_bayar'] ?? 'now')) ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?= esc($bkk['nama_supplier'] ?? '-') ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><i class="fa-solid fa-tag me-1 text-secondary d-print-none"></i><?= esc($bkk['kode_supplier'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <span class="font-monospace text-primary small"><i class="fa-solid fa-receipt me-1 d-print-none"></i><?= esc($bkk['nomor_utang'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-secondary"><i class="fa-solid fa-building-columns me-1 text-primary d-print-none"></i><?= esc($bkk['nama_kas_bank'] ?? 'Bank BCA') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 small fw-bold text-uppercase">
                                        <?= esc($bkk['metode_pembayaran'] ?? 'BANK TRANSFER') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger" style="font-size: 0.72rem;">KREDIT KAS</span>
                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary" style="font-size: 0.72rem;">DEBIT UTANG</span>
                                    </div>
                                </td>
                                <td class="text-end font-monospace fw-bold text-danger">
                                    Rp <?= number_format((float)($bkk['nominal'] ?? 0), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small fw-medium"><?= esc($bkk['dicatat_oleh'] ?? 'developer') ?></span>
                                </td>
                                <td class="pe-3 text-end d-print-none" style="vertical-align: middle;">
                                    <div class="d-inline-flex gap-1 justify-content-end align-items-center">
                                        <button type="button" class="btn btn-sm btn-dark p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-bkk-thermal" data-id="<?= $bkk['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Bukti Kas Keluar Thermal (POS)">
                                            <i class="fa-solid fa-receipt text-warning" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <a href="<?= base_url('keuangan/bkk-print/' . $bkk['id']) ?>" target="_blank" class="btn btn-sm btn-primary p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Cetak Kuitansi / Voucher BKK (A5)">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <a href="<?= base_url('keuangan/bkk-pdf/' . $bkk['id']) ?>" target="_blank" class="btn btn-sm btn-danger p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Unduh / Buka PDF Bukti Kas Keluar">
                                            <i class="fa-solid fa-file-pdf" style="font-size: 0.8rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">Belum ada bukti kas keluar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold" style="background-color: #f8fafc;">
                        <td colspan="8" class="text-end pe-3">TOTAL PENGELUARAN (<?= count($bkkList) ?> BKK):</td>
                        <td class="text-end font-monospace text-danger">Rp <?= number_format($grandTotalBkk, 0, ',', '.') ?></td>
                        <td colspan="2" class="d-print-none"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pt-3 text-muted small d-print-none">
            Total: <?= count($bkkList) ?> transaksi kas keluar
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSupplier = document.getElementById('filterSupplierBkk');
    const searchInput = document.getElementById('searchBkk');
    const btnFilter = document.getElementById('btnFilterBkk');
    const rows = document.querySelectorAll('#tableBkkList tbody tr');

    function filterTable() {
        const sup = filterSupplier.value.toLowerCase();
        const q = searchInput.value.toLowerCase().trim();

        rows.forEach(r => {
            const txt = r.textContent.toLowerCase();
            const matchesSup = !sup || txt.includes(sup);
            const matchesQ = !q || txt.includes(q);

            r.style.display = (matchesSup && matchesQ) ? '' : 'none';
        });
    }

    if (btnFilter) btnFilter.addEventListener('click', filterTable);
    if (filterSupplier) filterSupplier.addEventListener('change', filterTable);
    if (searchInput) searchInput.addEventListener('keyup', filterTable);

    // Thermal Print BKK
    $(document).on('click', '.btn-print-bkk-thermal', async function() {
        const id = $(this).data('id');
        const btn = $(this);
        const orig = btn.html();

        try {
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
            const res = await $.getJSON('<?= base_url('keuangan/bkk-thermal-data/') ?>' + id);
            if (res.status !== 'success') {
                throw new Error(res.message || 'Gagal memuat data BKK');
            }

            const d = res.data;
            const store = res.store;
            const nominal = Math.round(parseFloat(d.nominal || 0)).toLocaleString('id-ID');

            const previewHtml = `
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 14px;">BUKTI KAS KELUAR (BKK)</div>
                    <div class="small fw-semibold text-secondary">${store.name}</div>
                </div>
                <div class="double-line"></div>
                <div class="small">
                    <div>No. Bukti  : <strong class="font-monospace">${d.nomor_bkk || '-'}</strong></div>
                    <div>Tanggal    : ${d.tanggal_bayar || '-'}</div>
                    <div>Dibayar Ke : <strong>${d.nama_supplier || 'Supplier / Vendor'}</strong></div>
                    <div>Akun Kas   : ${d.nama_kas_bank || 'Bank BCA'}</div>
                    <div>Metode     : ${(d.metode_pembayaran || 'TRANSFER').toUpperCase()}</div>
                </div>
                <div class="dashed-line"></div>
                <div class="text-center my-2 p-2 bg-light border rounded">
                    <div class="text-muted small">NOMINAL DIBAYARKAN:</div>
                    <div class="fw-bold fs-5 text-danger font-monospace">Rp ${nominal}</div>
                </div>
                ${d.catatan ? '<div class="small mb-1">Catatan: ' + d.catatan + '</div>' : ''}
                <div class="dashed-line"></div>
                <div class="d-flex justify-content-between small text-center mt-2">
                    <div>Dicatat Oleh<br><br><strong>${d.dicatat_oleh || 'Finance'}</strong></div>
                    <div>Penerima Dana<br><br><strong>( .................... )</strong></div>
                </div>
            `;

            ThermalPrintHelper.showModal({
                title: 'Bukti Kas Keluar #' + (d.nomor_bkk || id),
                subtitle: 'Dibayarkan Ke: ' + (d.nama_supplier || 'Vendor') + ' &bull; Rp ' + nominal,
                previewHtml: previewHtml,
                onPrintBluetooth: async () => {
                    await window.posBluetoothPrinter.printVoucher('BKK', d, store);
                },
                onPrintSerial: async () => {
                    await window.posBluetoothPrinter.printVoucher('BKK', d, store);
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
                text: err.message || 'Tidak dapat memuat bukti kas keluar.'
            });
        } finally {
            btn.prop('disabled', false).html(orig);
        }
    });
});
</script>
<?= $this->endSection() ?>

