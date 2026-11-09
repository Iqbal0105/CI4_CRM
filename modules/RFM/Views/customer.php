<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP BANNER RFM -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Daftar Customer &amp; Segmentasi</div>
                <h3 class="fw-bold text-white mb-1">
                    <i class="fa-solid fa-users-viewfinder me-2"></i>Segmentasi Pelanggan RFM
                </h3>
                <p class="text-white-50 small mb-0">Daftar rincian seluruh pelanggan beserta riwayat transaksi, nilai belanja, dan kategori loyalitasnya</p>
            </div>
            <div class="d-flex flex-wrap flex-md-nowrap align-items-center justify-content-end gap-2 ms-auto flex-shrink-0 align-self-end align-self-md-center text-nowrap">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap">
                    <i class="fa-brands fa-whatsapp"></i> Broadcast WA
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel">
                    <i class="fa-solid fa-file-excel"></i> Import Excel Data
                </button>
                <a href="<?= base_url('rfm/customer/export?' . http_build_query($_GET)) ?>" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap" style="background-color: #6366f1;">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- 6 SUB-NAVIGATION TABS -->
        <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('rfm') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard RFM
            </a>
            <a href="<?= base_url('rfm/customer') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 2. Daftar Customer
            </a>
            <a href="<?= base_url('rfm/tracking') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                3. Tracking RFM
            </a>
            <a href="<?= base_url('rfm/kohort') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Analisis Kohort
            </a>
            <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Tren Segmen Produk
            </a>
            <a href="<?= base_url('rfm/matriks') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Matriks Aturan RFM
            </a>
        </div>
    </div>
</div>

<!-- 4 STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #3b82f6 !important;">
            <div class="fs-3 fw-bold text-dark font-monospace text-nowrap"><?= number_format(count($customers), 0, ',', '.') ?></div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">TOTAL PELANGGAN</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="fs-3 fw-bold text-success font-monospace text-nowrap"><?= esc($totalFreq) ?>x</div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">TOTAL TRANSAKSI</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #2D6A4F !important;">
            <div class="fw-bold text-primary font-monospace text-nowrap d-flex align-items-baseline" style="font-size: 1.35rem;">
                <span class="fs-6 fw-semibold text-muted me-1">Rp</span>
                <span><?= number_format((float)$totalOmset, 0, ',', '.') ?></span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">TOTAL OMSET PENJUALAN</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #6366f1 !important;">
            <div class="fw-bold text-dark text-nowrap text-truncate" style="font-size: 1.35rem;"><?= !empty($filterSegment) ? esc($filterSegment) : (!empty($selectedProduk) || !empty($selectedKategori) || !empty($selectedBulan) ? 'Filtered' : 'Semua Data') ?></div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;"><?= (!empty($filterSegment) || !empty($selectedProduk) || !empty($selectedKategori) || !empty($selectedBulan)) ? 'FILTER TERPASANG' : 'TANPA FILTER' ?></div>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-body p-3">
        <form method="GET" action="<?= base_url('rfm/customer') ?>" id="rfmCustomerFilterForm" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <input type="text" name="q" id="rfmSearchCustomer" class="form-control form-control-sm" placeholder="Cari nama / no. HP / resi / platform..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-12 col-md-2">
                <select name="segment" id="rfmFilterSegmen" class="form-select form-select-sm">
                    <option value="">-- Semua Segmen --</option>
                    <?php
                    $allSegments = [
                        'Champions',
                        'Loyal Customers',
                        'Potential Loyalist',
                        'New Customers',
                        'Big Spenders',
                        'Frequent Low Value',
                        'Need Attention',
                        'At Risk Customers',
                        "Can't Lose Them",
                        'Hibernating',
                    ];
                    foreach ($allSegments as $segOpt):
                        $sel = (
                            strcasecmp($filterSegment ?? '', $segOpt) === 0 || 
                            (strcasecmp($filterSegment ?? '', 'Potential Loyalists') === 0 && $segOpt === 'Potential Loyalist') || 
                            (strcasecmp($filterSegment ?? '', 'At Risk') === 0 && $segOpt === 'At Risk Customers')
                        ) ? 'selected' : '';
                    ?>
                    <option value="<?= esc($segOpt) ?>" <?= $sel ?>><?= esc($segOpt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="kategori" id="rfmFilterKategori" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    <?php if (!empty($kategoriList)): ?>
                        <?php foreach ($kategoriList as $k): 
                            $kVal = $k['kode_kategori'] ?? $k['id'];
                            $kSel = (
                                (string)($selectedKategori ?? '') === (string)$kVal ||
                                (string)($selectedKategori ?? '') === (string)$k['id'] ||
                                strcasecmp((string)($selectedKategori ?? ''), (string)$k['nama_kategori']) === 0 ||
                                strcasecmp((string)($selectedKategori ?? ''), (string)($k['kode_kategori'] ?? '')) === 0
                            ) ? 'selected' : '';
                        ?>
                            <option value="<?= esc($kVal) ?>" <?= $kSel ?>><?= esc($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="produk" id="rfmFilterProduk" class="form-select form-select-sm">
                    <option value="">-- Semua Produk --</option>
                    <?php if (!empty($produkList)): ?>
                        <?php foreach ($produkList as $p):
                            $pSel = (
                                (string)($selectedProduk ?? '') === (string)$p['id'] ||
                                strcasecmp((string)($selectedProduk ?? ''), $p['nama_produk']) === 0
                            ) ? 'selected' : '';
                        ?>
                            <option value="<?= esc($p['id']) ?>" <?= $pSel ?>><?= esc($p['nama_produk']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold" style="background-color: #2D6A4F; border-color: #2D6A4F;">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
                <a href="<?= base_url('rfm/customer') ?>" class="btn btn-light btn-sm text-secondary border" title="Reset Filter">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- CUSTOMER TABLE -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="rfmCustomerTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <th class="ps-3 py-3" style="width: 40px;">#</th>
                        <th>NAMA CUSTOMER</th>
                        <th>SUMBER PLATFORM</th>
                        <th class="text-center">ORDER (F)</th>
                        <th>TOTAL SPEND (M)</th>
                        <th>RECENCY (R)</th>
                        <th class="text-center text-nowrap" style="min-width: 95px; white-space: nowrap;">SKOR RFM</th>
                        <th>SEGMEN MARKET</th>
                        <th class="pe-3 text-end text-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $idx => $c): ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($c['nama_pelanggan']) ?></div>
                                    <div class="text-muted small font-monospace"><i class="fa-solid fa-phone text-success me-1" style="font-size: 10px;"></i><?= esc($c['no_telp'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $custSources = $sourceMap[$c['id']] ?? ['toko'];
                                    ?>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php foreach ($custSources as $src): ?>
                                            <?php 
                                                $srcLower = strtolower($src);
                                                $bgClass = match($srcLower) {
                                                    'shopee' => 'background-color: #ee4d2d; color: white;',
                                                    'tokopedia' => 'background-color: #03ac0e; color: white;',
                                                    'tiktok' => 'background-color: #000000; color: white;',
                                                    default => 'background-color: #1e293b; color: white;',
                                                };
                                                $label = match($srcLower) {
                                                    'tiktok' => 'TikTok Shop',
                                                    'shopee' => 'Shopee',
                                                    'tokopedia' => 'Tokopedia',
                                                    default => 'Toko',
                                                };
                                            ?>
                                            <span class="badge rounded-pill px-2 py-1 fw-medium" style="<?= $bgClass ?>">
                                                <?= $label ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded px-2 py-1 fw-bold" style="border: 1px solid #10b981; color: #047857; background-color: #ecfdf5;">
                                        <?= esc($c['frequency_count'] ?? 1) ?>x
                                    </span>
                                </td>
                                <td class="fw-bold text-success font-monospace">
                                    Rp <?= number_format((float)($c['monetary_total'] ?? 0), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1 small">
                                        <?= esc($c['recency_days'] ?? 0) ?> hr lalu
                                    </span>
                                </td>
                                <td class="text-center text-nowrap" style="white-space: nowrap;">
                                    <?php
                                        $scoreBadgeCls = function(int $val): string {
                                            return match(true) {
                                                $val >= 4 => 'bg-success',
                                                $val == 3 => 'bg-warning',
                                                default   => 'bg-danger',
                                            };
                                        };
                                    ?>
                                    <div class="d-inline-flex align-items-center justify-content-center flex-nowrap font-monospace fw-bold" style="font-size: 0.75rem; gap: 3px; white-space: nowrap;">
                                        <span class="badge <?= $scoreBadgeCls((int)($c['r_score'] ?? 5)) ?> rounded px-2 py-1 flex-shrink-0" style="min-width: 22px;"><?= $c['r_score'] ?? 5 ?></span>
                                        <span class="badge <?= $scoreBadgeCls((int)($c['f_score'] ?? 3)) ?> rounded px-2 py-1 flex-shrink-0" style="min-width: 22px;"><?= $c['f_score'] ?? 3 ?></span>
                                        <span class="badge <?= $scoreBadgeCls((int)($c['m_score'] ?? 5)) ?> rounded px-2 py-1 flex-shrink-0" style="min-width: 22px;"><?= $c['m_score'] ?? 5 ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $seg = $c['segment'] ?? 'Need Attention';
                                        $segStyle = match($seg) {
                                            'Champions' => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                                            'Loyal Customers' => 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;',
                                            default => 'background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;',
                                        };
                                        $icon = match($seg) {
                                            'Champions' => 'fa-trophy',
                                            'Loyal Customers' => 'fa-gem',
                                            default => 'fa-bell',
                                        };
                                    ?>
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="<?= $segStyle ?>">
                                        <i class="fa-solid <?= $icon ?>"></i> <?= esc($seg) ?>
                                    </span>
                                </td>
                                <td class="pe-3 text-end text-nowrap">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <a href="<?= base_url('rfm/customer_detail?id=' . $c['id']) ?>" class="btn btn-primary btn-sm rounded shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Detail Customer RFM">
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                        <?php if (!empty($c['no_telp'])): ?>
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/\D/', '', $c['no_telp'])) ?>" target="_blank" class="btn btn-success btn-sm rounded shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Chat WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data pelanggan untuk segmentasi ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL IMPORT RFM EXCEL -->
<div class="modal fade" id="modalImportRfmExcel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-file-excel me-2 text-success"></i>Sinkronisasi File Excel / Transaksi RFM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportRfmExcel" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="text-muted small">Unggah berkas spreadsheet transaksi penjualan marketplace (Shopee, TikTok, Tokopedia) atau template RFM (<code>.xlsx</code> / <code>.csv</code>) untuk menyelaraskan data transaksi dan segmentasi pelanggan.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih File Spreadsheet (.xlsx / .csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" id="fileRfmExcel" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="p-3 bg-light rounded small text-muted">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>Sistem akan otomatis mengimpor pesanan, mendaftarkan pelanggan baru, dan menghitung ulang nilai Recency, Frequency, Monetary (RFM).
                    </div>
                    <div id="rfmImportAlert" class="mt-3" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" id="btnSyncRfmSubmit">
                        <i class="fa-solid fa-rotate me-1"></i>Proses Sinkronisasi Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Real-time instant search on table rows as user types
    $('#rfmSearchCustomer').on('keyup input', function() {
        var term = $(this).val().toLowerCase().trim();
        $('#rfmCustomerTable tbody tr').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (term === '' || rowText.indexOf(term) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Handle AJAX file import & synchronization
    $('#formImportRfmExcel').on('submit', function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('fileRfmExcel');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan pilih file spreadsheet (.xlsx / .csv) terlebih dahulu.');
            return;
        }

        var btn = $('#btnSyncRfmSubmit');
        var originalHtml = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyinkronkan...').prop('disabled', true);
        $('#rfmImportAlert').hide().removeClass('alert alert-danger alert-success').empty();

        var formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('rfm/import-excel') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    btn.html('<i class="fa-solid fa-check me-1"></i>Berhasil!');
                    $('#rfmImportAlert')
                        .addClass('alert alert-success small mb-0')
                        .html('<i class="fa-solid fa-circle-check me-1"></i>' + res.message)
                        .show();
                    setTimeout(function() {
                        window.location.reload();
                    }, 1400);
                } else {
                    btn.html(originalHtml).prop('disabled', false);
                    $('#rfmImportAlert')
                        .addClass('alert alert-danger small mb-0')
                        .html('<i class="fa-solid fa-triangle-exclamation me-1"></i>' + (res.message || 'Gagal memproses file.'))
                        .show();
                }
            },
            error: function(xhr) {
                btn.html(originalHtml).prop('disabled', false);
                var err = 'Terjadi kesalahan server saat memproses file.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    err = xhr.responseJSON.message;
                }
                $('#rfmImportAlert')
                    .addClass('alert alert-danger small mb-0')
                    .html('<i class="fa-solid fa-triangle-exclamation me-1"></i>' + err)
                    .show();
            }
        });
    });
});
</script>

<?= $this->endSection() ?>

