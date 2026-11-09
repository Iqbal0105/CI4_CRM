<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <a href="<?= base_url('marketplace') ?>" class="text-decoration-none text-secondary small">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Penjualan Marketplace
            </a>
            <h3 class="fw-bold mt-2 mb-1">Impor Laporan Pesanan Marketplace</h3>
            <p class="text-muted small mb-0">Dukungan impor massal file laporan CSV / Excel dari Shopee, TikTok Shop, Tokopedia, dan Lazada ke dalam sistem ERP.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('marketplace/scan-resi') ?>" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-barcode me-1"></i> Scan Label Resi Thermal
            </a>
            <a href="<?= base_url('marketplace') ?>" class="btn btn-sm btn-outline-primary">
                <i class="fa-solid fa-layer-group me-1"></i> Semua Pesanan Marketplace
            </a>
        </div>
    </div>
</div>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="fa-solid fa-circle-check fs-5 text-success mt-1"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation fs-5 text-danger mt-1"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- SECTION FORM UNGGAH & PETUNJUK -->
<div class="row g-4 mb-4">
    <!-- FORM UPLOAD -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-bold d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-file-arrow-up text-primary fs-5"></i>
                    Unggah Berkas Laporan Marketplace
                </span>
                <span class="badge bg-light text-secondary border font-monospace">CSV / XLSX</span>
            </div>
            <div class="card-body p-4">
                <form id="formImportMarketplace" action="<?= base_url('marketplace/process-import') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Channel Platform <span class="text-danger">*</span></label>
                        <select name="source" id="importSource" class="form-select shadow-xs" required>
                            <option value="shopee" selected>Shopee (Laporan Pesanan Saya / CSV / Excel)</option>
                            <option value="tiktok">TikTok Shop (Order Report / XLSX / CSV)</option>
                            <option value="tokopedia">Tokopedia (Daftar Pesanan Siap Dikirim / CSV / Excel)</option>
                            <option value="lazada">Lazada (Order Overview / XLSX / CSV)</option>
                        </select>
                        <div class="form-text small text-muted">Sistem juga otomatis mendeteksi channel jika kolom file memiliki format khas.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Berkas Laporan (.csv, .xlsx, .xls) <span class="text-danger">*</span></label>
                        <input type="file" name="file_laporan" id="importFileInput" class="form-control shadow-xs" accept=".csv, .xlsx, .xls" required>
                        <div class="small text-muted mt-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span><i class="fa-solid fa-wand-magic-sparkles text-success me-1"></i> Pratinjau data dimuat <strong>otomatis</strong> begitu berkas dipilih.</span>
                            <span id="selectedFileSizeBadge" class="badge bg-light text-dark border" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end pt-2 border-top">
                        <button type="button" class="btn btn-outline-secondary px-3 w-100 w-sm-auto" id="btnPreviewImport">
                            <i class="fa-solid fa-table-list me-1"></i> Pratinjau Ulang Berkas
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold w-100 w-sm-auto" id="btnSubmitImport">
                            <i class="fa-solid fa-upload me-1"></i> Mulai Proses Impor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PETUNJUK OTOMASI ERP -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100 bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-bold d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-wand-magic-sparkles text-success fs-5"></i>
                    Otomasi Integrasi ERP &amp; Akuntansi
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">Full Automatic</span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-cube fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Otomatis Terhubung ke Master Produk</div>
                            <div class="small text-muted">SKU / Nama barang otomatis dipetakan ke katalog ERP. Jika belum ada, sistem langsung mendaftarkan produk baru ke katalog &amp; kartu stok.</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-users fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Otomatis Registrasi Pelanggan &amp; CRM</div>
                            <div class="small text-muted">Penerima dan nomor HP otomatis masuk ke Master Pelanggan. Jadwal CRM follow-up WhatsApp purnajual (H+0, H+3, H+7) langsung aktif.</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-file-invoice-dollar fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Sales Order, Faktur &amp; Jurnal Keuangan</div>
                            <div class="small text-muted">Menerbitkan SO ERP resmi, Invoice lunas, dan membukukan mutasi Jurnal Umum piutang settlement marketplace tanpa input manual.</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-shield-halved fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Idempoten &amp; Anti-Duplikasi</div>
                            <div class="small text-muted">Mengimpor berkas yang sama berulang kali tidak akan menggandakan data pesanan. Pesanan yang sudah ada otomatis dilewati secara aman.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION PRATINJAU BERKAS (PREVIEW TABLE) - MUNCUL KETIKA PREVIEW DIJALANKAN -->
<div class="card shadow-sm border-0 mb-4" id="cardPreviewImport" style="display: none;">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
        <div>
            <span class="fw-bold fs-6 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-magnifying-glass-chart text-primary"></i>
                Pratinjau Data Laporan Berkas: <span id="previewFileName" class="text-primary font-monospace fw-bold"></span>
            </span>
            <div class="small text-muted mt-1" id="previewMetaSummary">
                Membaca isi berkas laporan...
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClosePreview">
                <i class="fa-solid fa-xmark me-1"></i> Tutup Pratinjau
            </button>
            <button type="button" class="btn btn-success btn-sm fw-bold px-3" id="btnConfirmImportFromPreview">
                <i class="fa-solid fa-rocket me-1"></i> Proses Impor Data Ini
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0" id="tablePreviewRows">
                <thead class="table-light sticky-top">
                    <tr class="small text-uppercase">
                        <th style="width: 40px;" class="text-center">No</th>
                        <th style="width: 170px;">No. Pesanan</th>
                        <th style="width: 140px;">Ekspedisi / Resi</th>
                        <th style="width: 160px;">Penerima &amp; Kontak</th>
                        <th>Rincian Produk &amp; SKU</th>
                        <th class="text-center" style="width: 70px;">Qty</th>
                        <th class="text-end" style="width: 130px;">Total Nilai</th>
                        <th class="text-center" style="width: 130px;">Status Impor</th>
                    </tr>
                </thead>
                <tbody id="tablePreviewBody">
                    <!-- Populated via JavaScript AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // 1. File Input change listener -> Auto Trigger Preview
    $('#importFileInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            const kb = Math.round(file.size / 1024);
            $('#selectedFileSizeBadge').html('<i class="fa-solid fa-file-excel text-success me-1"></i> ' + $('<div>').text(file.name).html() + ' (' + kb + ' KB)').show();
            // Otomatis jalankan pratinjau data begitu berkas dipilih
            loadPreview(file);
        } else {
            $('#selectedFileSizeBadge').hide();
            $('#cardPreviewImport').slideUp();
        }
    });

    // 2. Channel Change listener -> Auto refresh preview if file already selected
    $('#importSource').on('change', function() {
        const fileInput = document.getElementById('importFileInput');
        if (fileInput && fileInput.files && fileInput.files[0]) {
            loadPreview(fileInput.files[0]);
        }
    });

    // 3. Manual Preview Button
    $('#btnPreviewImport').on('click', function() {
        const fileInput = document.getElementById('importFileInput');
        if (!fileInput.files || !fileInput.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Berkas Terlebih Dahulu',
                text: 'Silakan pilih berkas laporan CSV atau Excel terlebih dahulu.'
            });
            return;
        }
        loadPreview(fileInput.files[0]);
    });

    // Main Preview Loader (AJAX)
    function loadPreview(file) {
        if (!file) return;

        const source = $('#importSource').val();
        const formData = new FormData();
        formData.append('file_laporan', file);
        formData.append('source', source);
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
        formData.append('<?= csrf_token() ?>', csrfToken);

        const btn = $('#btnPreviewImport');
        const origText = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Membaca Berkas...');

        // Tampilkan card pratinjau langsung dengan indikator loading otomatis
        $('#cardPreviewImport').slideDown();
        $('#previewFileName').text(file.name);
        $('#previewMetaSummary').html('<span class="text-primary"><i class="fa-solid fa-spinner fa-spin me-1"></i> Membaca berkas dan menganalisis pesanan secara otomatis...</span>');
        $('#tablePreviewBody').html(`
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <i class="fa-solid fa-spinner fa-spin fs-4 text-primary d-block mb-2"></i>
                    Sedang memetakan produk, variasi, dan memeriksa duplikasi data di database ERP...
                </td>
            </tr>
        `);

        $('html, body').animate({
            scrollTop: $('#cardPreviewImport').offset().top - 80
        }, 300);

        $.ajax({
            url: '<?= base_url('marketplace/preview-import') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(resp) {
                btn.prop('disabled', false).html(origText);

                if (resp.status !== 'success') {
                    $('#cardPreviewImport').slideUp();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Membaca Berkas',
                        text: resp.message || 'Format data di dalam berkas tidak dapat dipetakan.'
                    });
                    return;
                }

                // Render Preview Table
                renderPreviewTable(resp);
                $('#cardPreviewImport').slideDown();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(origText);
                $('#cardPreviewImport').slideUp();
                let err = 'Terjadi kesalahan saat memproses pratinjau berkas.';
                try {
                    const j = JSON.parse(xhr.responseText);
                    if (j.message) err = j.message;
                } catch(e) {}
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: err
                });
            }
        });
    }

    // Render Preview Table Function
    function renderPreviewTable(data) {
        $('#previewFileName').text(data.file_name + ' (' + data.file_size + ')');
        const channelBadge = `<span class="badge bg-primary text-uppercase">${data.source}</span>`;
        $('#previewMetaSummary').html(`
            Ditemukan <strong>${data.total_orders}</strong> pesanan dari berkas ${channelBadge}. 
            <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i>${data.new_orders} Pesanan Baru (Siap Diimpor)</span> &bull; 
            <span class="text-secondary fw-semibold"><i class="fa-solid fa-clock-rotate-left me-1"></i>${data.duplicate_orders} Sudah Ada di Sistem</span>
        `);

        let html = '';
        data.orders.forEach((ord, i) => {
            let itemsHtml = '';
            let totalQty = 0;
            if (ord.items && ord.items.length > 0) {
                ord.items.forEach(it => {
                    const q = parseFloat(it.qty) || 1;
                    totalQty += q;
                    itemsHtml += `<div class="small text-truncate" style="max-width: 280px;">&bull; ${it.nama || it.sku} <span class="badge bg-light text-dark border">x${q}</span></div>`;
                });
            } else {
                itemsHtml = '<span class="text-muted small">-</span>';
                totalQty = 1;
            }

            const isDup = ord.is_duplicate;
            const statusBadge = isDup 
                ? '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="fa-solid fa-check-double me-1"></i>Sudah Pernah Diimpor</span>'
                : '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-plus me-1"></i>Siap Diimpor</span>';

            const rowClass = isDup ? 'table-light opacity-75' : '';

            html += `
                <tr class="${rowClass}">
                    <td class="text-center font-monospace small text-muted">${i + 1}</td>
                    <td>
                        <div class="fw-bold font-monospace text-dark small">${ord.no_pesanan}</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">${ord.tanggal || '-'}</div>
                    </td>
                    <td>
                        <div class="badge bg-purple text-white px-2 py-0.5" style="background-color: #9333ea; font-size: 0.72rem;">${ord.kurir || 'Ekspedisi'}</div>
                        <div class="font-monospace small text-primary fw-bold mt-0.5">${ord.resi || '-'}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark small text-truncate" style="max-width: 150px;">${ord.nama_penerima || 'Pembeli'}</div>
                        <div class="small text-muted font-monospace" style="font-size: 0.72rem;">${ord.no_telepon || '-'}</div>
                        <div class="small text-muted text-truncate" style="max-width: 150px; font-size: 0.72rem;">${ord.kota || ''}</div>
                    </td>
                    <td>${itemsHtml}</td>
                    <td class="text-center font-monospace fw-bold small">${totalQty}</td>
                    <td class="text-end font-monospace fw-bold text-dark small">Rp ${Math.round(ord.total).toLocaleString('id-ID')}</td>
                    <td class="text-center">${statusBadge}</td>
                </tr>
            `;
        });

        $('#tablePreviewBody').html(html);
    }

    // Close preview table
    $('#btnClosePreview').on('click', function() {
        $('#cardPreviewImport').slideUp();
    });

    // Submit form directly from preview button
    $('#btnConfirmImportFromPreview').on('click', function() {
        $('#formImportMarketplace').submit();
    });
});
</script>
<?= $this->endSection() ?>
