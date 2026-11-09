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
        #tableBkmList {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 7.8pt !important;
            margin-top: 5px;
        }
        #tableBkmList thead {
            display: table-header-group !important;
        }
        #tableBkmList tbody {
            display: table-row-group !important;
        }
        #tableBkmList tr {
            page-break-inside: avoid !important;
        }
        #tableBkmList th,
        #tableBkmList td {
            padding: 5px 6px !important;
            border: 1px solid #cbd5e1 !important;
            white-space: normal !important;
            word-break: break-word !important;
            color: #0f172a !important;
            line-height: 1.25 !important;
        }
        #tableBkmList th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #tableBkmList tfoot td {
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
        <h4 class="fw-bold mb-1 text-dark">Penerimaan Kas Masuk (BKM)</h4>
        <p class="text-muted small mb-0">Riwayat Penerimaan Uang Kas &amp; Bank Pelunasan Piutang</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="<?= base_url('keuangan/bkm/export') ?>" class="btn btn-sm btn-outline-success fw-semibold d-inline-flex align-items-center gap-1 shadow-xs">
            <i class="fa-solid fa-file-excel"></i> Export XLS
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" onclick="window.print()">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
        <a href="<?= base_url('keuangan/piutang') ?>" class="btn btn-sm btn-primary fw-semibold px-3 d-inline-flex align-items-center gap-1">
            <i class="fa-solid fa-book-bookmark me-1"></i>Buku Piutang Usaha (AR)
        </a>
    </div>
</div>

<!-- DEDICATED REPORT HEADER FOR PRINT / PDF EXPORT -->
<div class="d-none d-print-block mb-3 border-bottom pb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 13pt;">LAPORAN PENERIMAAN KAS MASUK (BUKTI KAS MASUK - BKM)</h4>
            <div class="small text-muted">IDMETAFORA ERP Enterprise &bull; Rekap Transaksi Penerimaan Kas &amp; Bank</div>
        </div>
        <div class="text-end small text-muted">
            <div>Dicetak pada: <strong><?= date('d/m/Y H:i') ?></strong></div>
            <div>Total: <strong><?= count($bkmList) ?></strong> Bukti Kas Masuk</div>
        </div>
    </div>
</div>

<!-- CARD DAFTAR BUKTI KAS MASUK BKM -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-print-none">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-receipt text-success"></i> Daftar Bukti Kas Masuk (BKM)
        </h6>
    </div>
    <div class="card-body p-3">
        <!-- FILTER ROW -->
        <div class="row g-2 align-items-center mb-3 d-print-none">
            <div class="col-12 col-md-4">
                <select class="form-select form-select-sm" id="filterPelangganBkm">
                    <option value="">-- Semua Pelanggan --</option>
                    <?php 
                        $pelangganUnique = array_unique(array_column($bkmList, 'nama_pelanggan'));
                        foreach ($pelangganUnique as $pName):
                            if (!empty($pName)):
                    ?>
                        <option value="<?= esc($pName) ?>"><?= esc($pName) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" id="searchBkm" class="form-control form-control-sm" placeholder="Cari No. BKM / Pelanggan / Ref...">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-success btn-sm w-100 fw-semibold" id="btnFilterBkm">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableBkmList">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 40px;">No</th>
                        <th>No. Bukti Kas Masuk (BKM)</th>
                        <th>Tanggal Terima</th>
                        <th>Pelanggan</th>
                        <th>No. Tagihan Piutang</th>
                        <th>Akun Kas / Bank</th>
                        <th>Metode &amp; Ref</th>
                        <th class="text-end">Nominal Diterima (Rp)</th>
                        <th>Petugas</th>
                        <th class="pe-3 text-end d-print-none">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $grandTotalBkm = 0;
                        if (!empty($bkmList)): 
                    ?>
                        <?php foreach ($bkmList as $idx => $bkm): 
                            $grandTotalBkm += (float)($bkm['nominal'] ?? 0);
                        ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary"><?= esc($bkm['nomor_bkm']) ?></span>
                                </td>
                                <td class="small text-secondary font-monospace">
                                    <i class="fa-regular fa-calendar me-1 d-print-none"></i><?= date('d/m/Y', strtotime($bkm['tanggal_bayar'] ?? 'now')) ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small"><?= esc($bkm['nama_pelanggan'] ?? 'Umum') ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><i class="fa-solid fa-tag me-1 text-secondary d-print-none"></i><?= esc($bkm['kode_pelanggan'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted small"><?= esc($bkm['nomor_piutang'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-secondary"><i class="fa-solid fa-building-columns me-1 text-primary d-print-none"></i><?= esc($bkm['nama_kas_bank'] ?? 'Cash') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 small fw-bold text-uppercase">
                                        <?= esc($bkm['metode_pembayaran'] ?? 'TUNAI') ?>
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-bold text-success">
                                    Rp <?= number_format((float)($bkm['nominal'] ?? 0), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small fw-medium"><?= esc($bkm['petugas'] ?? 'developer') ?></span>
                                </td>
                                <td class="pe-3 text-end d-print-none" style="vertical-align: middle;">
                                    <div class="d-inline-flex gap-1 justify-content-end align-items-center">
                                        <button type="button" class="btn btn-sm btn-dark p-0 d-inline-flex align-items-center justify-content-center rounded btn-print-bkm-thermal" data-id="<?= $bkm['id'] ?>" style="width: 30px; height: 30px;" title="Cetak Bukti Kas Masuk Thermal (POS)">
                                            <i class="fa-solid fa-receipt text-warning" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <a href="<?= base_url('keuangan/bkm-print/' . $bkm['id']) ?>" target="_blank" class="btn btn-sm btn-primary p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Cetak Kuitansi / Voucher BKM (A5)">
                                            <i class="fa-solid fa-print" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <a href="<?= base_url('keuangan/bkm-pdf/' . $bkm['id']) ?>" target="_blank" class="btn btn-sm btn-danger p-0 d-inline-flex align-items-center justify-content-center rounded" style="width: 30px; height: 30px;" title="Unduh / Buka PDF Bukti Kas Masuk">
                                            <i class="fa-solid fa-file-pdf" style="font-size: 0.8rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Belum ada bukti kas masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold" style="background-color: #f8fafc;">
                        <td colspan="7" class="text-end pe-3">TOTAL KESELURUHAN (<?= count($bkmList) ?> BKM):</td>
                        <td class="text-end font-monospace text-success">Rp <?= number_format($grandTotalBkm, 0, ',', '.') ?></td>
                        <td colspan="2" class="d-print-none"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pt-3 text-muted small d-print-none">
            Total Data: <?= count($bkmList) ?> Bukti Kas Masuk
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterPelanggan = document.getElementById('filterPelangganBkm');
    const searchInput = document.getElementById('searchBkm');
    const btnFilter = document.getElementById('btnFilterBkm');
    const rows = document.querySelectorAll('#tableBkmList tbody tr');

    function filterTable() {
        const pel = filterPelanggan.value.toLowerCase();
        const q = searchInput.value.toLowerCase().trim();

        rows.forEach(r => {
            const txt = r.textContent.toLowerCase();
            const matchesPel = !pel || txt.includes(pel);
            const matchesQ = !q || txt.includes(q);

            r.style.display = (matchesPel && matchesQ) ? '' : 'none';
        });
    }

    if (btnFilter) btnFilter.addEventListener('click', filterTable);
    if (filterPelanggan) filterPelanggan.addEventListener('change', filterTable);
    if (searchInput) searchInput.addEventListener('keyup', filterTable);

    // Thermal Print BKM
    $(document).on('click', '.btn-print-bkm-thermal', async function() {
        const id = $(this).data('id');
        const btn = $(this);
        const orig = btn.html();

        try {
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
            const res = await $.getJSON('<?= base_url('keuangan/bkm-thermal-data/') ?>' + id);
            if (res.status !== 'success') {
                throw new Error(res.message || 'Gagal memuat data BKM');
            }

            const d = res.data;
            const store = res.store;
            const nominal = Math.round(parseFloat(d.nominal || 0)).toLocaleString('id-ID');

            const previewHtml = `
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 14px;">BUKTI KAS MASUK (BKM)</div>
                    <div class="small fw-semibold text-secondary">${store.name}</div>
                </div>
                <div class="double-line"></div>
                <div class="small">
                    <div>No. Bukti : <strong class="font-monospace">${d.nomor_bkm || '-'}</strong></div>
                    <div>Tanggal   : ${d.tanggal_bayar || '-'}</div>
                    <div>Pelanggan : <strong>${d.nama_pelanggan || 'Customer Umum'}</strong></div>
                    <div>Akun Kas  : ${d.nama_kas_bank || 'Kas Utama'}</div>
                    <div>Metode    : ${(d.metode_pembayaran || 'TUNAI').toUpperCase()}</div>
                </div>
                <div class="dashed-line"></div>
                <div class="text-center my-2 p-2 bg-light border rounded">
                    <div class="text-muted small">NOMINAL DITERIMA:</div>
                    <div class="fw-bold fs-5 text-success font-monospace">Rp ${nominal}</div>
                </div>
                ${d.catatan ? '<div class="small mb-1">Catatan: ' + d.catatan + '</div>' : ''}
                <div class="dashed-line"></div>
                <div class="d-flex justify-content-between small text-center mt-2">
                    <div>Petugas Kasir<br><br><strong>${d.petugas || 'Finance'}</strong></div>
                    <div>Penyetor / Customer<br><br><strong>( .................... )</strong></div>
                </div>
            `;

            ThermalPrintHelper.showModal({
                title: 'Bukti Kas Masuk #' + (d.nomor_bkm || id),
                subtitle: 'Diterima Dari: ' + (d.nama_pelanggan || 'Umum') + ' &bull; Rp ' + nominal,
                previewHtml: previewHtml,
                onPrintBluetooth: async () => {
                    await window.posBluetoothPrinter.printVoucher('BKM', d, store);
                },
                onPrintSerial: async () => {
                    await window.posBluetoothPrinter.printVoucher('BKM', d, store);
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
                text: err.message || 'Tidak dapat memuat bukti kas masuk.'
            });
        } finally {
            btn.prop('disabled', false).html(orig);
        }
    });
});
</script>
<?= $this->endSection() ?>

