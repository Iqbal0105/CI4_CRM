<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .drop-zone {
        border: 2px dashed #93c5fd;
        border-radius: 0.75rem;
        background-color: #f8fafc;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .drop-zone:hover, .drop-zone.dragover {
        border-color: #2D6A4F;
        background-color: #E8F0EB;
    }
    .ocr-progress-container {
        display: none;
    }
    .preview-image-container {
        max-height: 380px;
        overflow: hidden;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background: #0f172a;
    }
    .preview-image-container img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }
    .table-batch-resi th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background-color: #f8fafc;
        font-weight: 700;
        color: #475569;
    }
    .table-batch-resi td {
        font-size: 0.82rem;
        vertical-align: middle;
    }
    .channel-badge-shopee {
        background-color: #ee4d2d !important;
        color: white;
    }
    .channel-badge-tiktok {
        background-color: #000000 !important;
        color: white;
    }
    .channel-badge-tokopedia {
        background-color: #03ac0e !important;
        color: white;
    }
    .channel-badge-lazada {
        background-color: #0f146d !important;
        color: white;
    }
    .channel-badge-manual {
        background-color: #64748b !important;
        color: white;
    }
    .batch-summary-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border-width: 2px !important;
    }
    .batch-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px -2px rgba(0,0,0,0.08);
    }
    .batch-summary-card.active-card {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.3) !important;
        transform: translateY(-2px);
    }
    .batch-summary-card.active-card-cod {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.3) !important;
        transform: translateY(-2px);
    }
    .table-batch-resi tr.row-is-cod {
        border-left: 4px solid #dc3545 !important;
        background-color: #fffbfb;
    }
    .table-batch-resi tr.row-is-non-cod {
        border-left: 4px solid #0d6efd !important;
    }
    .nav-pills .nav-link.active-filter-cod {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    .nav-pills .nav-link.active-filter-non-cod {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    .nav-pills .nav-link.active-filter-all {
        background-color: #475569 !important;
        color: #fff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
    $initialBatch = $batch['items'] ?? [];
    $isInitialBatch = !empty($batch['is_batch']) && count($initialBatch) > 1;
?>

<div class="mb-4">
    <a href="<?= base_url('marketplace') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Marketplace
    </a>
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mt-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold mb-0">Import &amp; Scraper Resi Otomatis</h3>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Mendukung Multi-Resi 1 PDF</span>
            </div>
            <p class="text-muted small mb-0 mt-1">Pindai foto resi tunggal atau unggah berkas PDF berisi banyak resi pengiriman untuk integrasi serentak ke ERP, CRM &amp; RFM.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
            <a href="<?= base_url('marketplace') ?>" class="btn btn-outline-secondary flex-fill flex-sm-grow-0">
                <i class="fa-solid fa-table-list me-1"></i> Riwayat Pesanan
            </a>
            <a href="<?= base_url('crm') ?>" class="btn btn-outline-success flex-fill flex-sm-grow-0">
                <i class="fa-brands fa-whatsapp me-1"></i> Jadwal CRM
            </a>
        </div>
    </div>
</div>

<div class="row g-4" id="mainScanLayout">
    <!-- LEFT COLUMN: UPLOAD, PASTE & OCR / PDF PARSING -->
    <div class="col-12 col-xl-4 col-lg-5" id="leftUploadCol">
        <div class="card shadow-sm border-0 mb-4 sticky-lg-top" style="top: 1rem; z-index: 10;">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-file-image text-primary me-2"></i>Pindai / Unggah Berkas Resi</span>
                <span class="badge bg-primary-subtle text-primary border">OCR + PDF Multi-Page</span>
            </div>
            <div class="card-body p-3 p-md-4">
                <!-- DROP ZONE -->
                <div class="drop-zone mb-3 position-relative" id="dropZone" style="cursor: pointer;">
                    <i class="fa-solid fa-cloud-arrow-up text-primary fs-1 mb-2 d-block"></i>
                    <div class="fw-bold text-dark mb-1">Tarik &amp; Lepas Gambar Resi atau PDF di Sini</div>
                    <div class="text-muted small mb-2">Pilih foto resi tunggal (.png, .jpg) atau 1 PDF berisi banyak label resi</div>
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2 flex-wrap">
                        <label for="resiFileInput" class="btn btn-primary btn-sm rounded-pill px-3 shadow-xs mb-0" id="btnPilihGambar" style="cursor: pointer;">
                            <i class="fa-solid fa-file-arrow-up me-1"></i> Pilih Berkas Resi
                        </label>
                        <span class="badge bg-secondary-subtle text-secondary border">Bisa juga tekan Ctrl + V (Paste Foto)</span>
                    </div>
                    <div id="selectedFileNameBadge" class="small text-success fw-bold mt-2" style="display: none;"></div>
                    <input type="file" id="resiFileInput" class="d-none" multiple accept=".png, .jpg, .jpeg, .webp, .pdf, image/*, application/pdf">
                </div>

                <!-- PROGRESS INDICATOR -->
                <div class="ocr-progress-container mb-3" id="ocrProgressBox">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-primary" id="ocrStatusText">Mempersiapkan pemrosesan...</span>
                        <span class="small font-monospace fw-bold text-primary" id="ocrPercentText">0%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="ocrProgressBar" style="width: 0%"></div>
                    </div>
                </div>

                <!-- IMAGE PREVIEW -->
                <div id="imagePreviewWrapper" class="mb-3" style="display: none;">
                    <div class="small fw-semibold text-muted mb-1">Pratinjau Berkas:</div>
                    <div class="preview-image-container text-center p-2">
                        <img id="imagePreview" src="" alt="Pratinjau Resi">
                    </div>
                </div>

                <!-- RAW TEXT COLLAPSIBLE / PASTE MANUALLY -->
                <div class="accordion mb-3" id="accordionRawText">
                    <div class="accordion-item border">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 px-3 small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRaw">
                                <i class="fa-solid fa-file-lines text-muted me-2"></i>Teks Hasil Ekstraksi / Tempel Teks Manual
                            </button>
                        </h2>
                        <div id="collapseRaw" class="accordion-collapse collapse" data-bs-parent="#accordionRawText">
                            <div class="accordion-body p-2">
                                <textarea id="rawTextScraped" class="form-control font-monospace small" rows="5" placeholder="Teks hasil scan OCR / PDF akan muncul di sini..."><?= esc($parsed['raw_text'] ?? '') ?></textarea>
                                <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" id="btnParseTextManual">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Parsing Ulang Teks
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border small text-muted mb-0">
                    <div class="fw-semibold text-dark mb-1"><i class="fa-solid fa-circle-info text-info me-1"></i> Format Resi Didukung:</div>
                    Shopee Xpress (SPX), J&amp;T Express, SiCepat, JNE Express, Anteraja, Ninja Xpress, ID Express, Tokopedia, dan TikTok Shop.
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: MULTI-RESI BATCH TABLE & SINGLE VERIFICATION FORM -->
    <div class="col-12 col-xl-8 col-lg-7" id="rightContentCol">

        <!-- ========================================== -->
        <!-- 1. BATCH VIEW: MULTI-RESI IN 1 FILE / PDF  -->
        <!-- ========================================== -->
        <div id="wrapperBatchResi" style="<?= $isInitialBatch ? 'display: block;' : 'display: none;' ?>">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5 text-primary"><i class="fa-solid fa-layer-group"></i></span>
                        <h5 class="fw-bold mb-0">Multi-Resi Terdeteksi: <span class="badge bg-primary rounded-pill fs-6 px-3" id="badgeBatchCount"><?= count($initialBatch) ?> Resi</span></h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnToggleExpandBatch" title="Lebarkan Tampilan Tabel">
                            <i class="fa-solid fa-expand me-1"></i> <span id="textExpandBtn">Lebarkan</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnSwitchToSingleForm">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Mode Form Satuan
                        </button>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <!-- INFORMATIONAL BANNER -->
                    <div class="alert alert-primary border-primary-subtle d-flex align-items-start gap-3 py-2 px-3 mb-3">
                        <i class="fa-solid fa-circle-check fs-4 text-primary mt-1"></i>
                        <div class="small">
                            <div>Sistem berhasil mendeteksi <strong id="alertBatchCount"><?= count($initialBatch) ?> label resi</strong> dalam dokumen ini.</div>
                            <div class="text-muted">Anda dapat menetapkan SKU produk masal, menyesuaikan data setiap resi, lalu menekan tombol <strong>Simpan</strong> untuk memproses seluruh transaksi sekaligus ke ERP, CRM WhatsApp &amp; RFM.</div>
                        </div>
                    </div>

                    <!-- BATCH SKU & GLOBAL CONTROLS -->
                    <div class="card bg-light border-0 p-3 mb-3">
                        <div class="fw-semibold small text-dark mb-2">
                            <i class="fa-solid fa-sliders text-secondary me-1"></i> Pengaturan Masal Produk (Sinkron Stok ERP):
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-6">
                                <select id="batchGlobalSku" class="form-select form-select-sm font-monospace">
                                    <option value="">-- Tetapkan SKU ke Seluruh Resi --</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= esc($p['kode_produk']) ?>" data-harga="<?= esc($p['harga_jual']) ?>">
                                            [<?= esc($p['kode_produk']) ?>] <?= esc($p['nama_produk']) ?> (Rp <?= number_format((float) $p['harga_jual'], 0, ',', '.') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Qty</span>
                                    <input type="number" id="batchGlobalQty" class="form-control text-center font-monospace" value="1" min="1">
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="btnApplyGlobalSku">
                                    <i class="fa-solid fa-check-double me-1"></i> Terapkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SUMMARY CARDS: PEMISAHAN KATEGORI COD VS NON-COD -->
                    <div class="row g-2 mb-3" id="batchSummaryCards">
                        <div class="col-12 col-sm-6">
                            <div class="card p-3 border batch-summary-card h-100" id="cardBatchNonCod" data-filter="non_cod" style="background-color: #f8fbff; border-color: #bfdbfe !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary text-white px-2 py-1"><i class="fa-solid fa-credit-card me-1"></i> Non-COD / Cashless</span>
                                    <span class="badge bg-white text-primary border font-monospace fw-bold fs-6" id="badgeCardNonCodCount">0 Resi</span>
                                </div>
                                <div class="fs-4 fw-bold text-primary font-monospace" id="textCardNonCodTotal">Rp 0</div>
                                <div class="text-muted small mt-1 d-flex align-items-center justify-content-between">
                                    <span><i class="fa-solid fa-circle-check text-success me-1"></i>Lunas Online di Marketplace</span>
                                    <span class="text-primary fw-semibold" style="font-size: 0.72rem;">Klik untuk filter &rarr;</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card p-3 border batch-summary-card h-100" id="cardBatchCod" data-filter="cod" style="background-color: #fff8f8; border-color: #fecaca !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-hand-holding-dollar me-1"></i> COD (Bayar di Tempat)</span>
                                    <span class="badge bg-white text-danger border font-monospace fw-bold fs-6" id="badgeCardCodCount">0 Resi</span>
                                </div>
                                <div class="fs-4 fw-bold text-danger font-monospace" id="textCardCodTotal">Rp 0</div>
                                <div class="text-muted small mt-1 d-flex align-items-center justify-content-between">
                                    <span><i class="fa-solid fa-hand-holding-dollar text-danger me-1"></i>Kurir Tagih Tunai</span>
                                    <span class="text-danger fw-semibold" style="font-size: 0.72rem;">Klik untuk filter &rarr;</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FILTER TABS: SEMUA, NON-COD, COD -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <ul class="nav nav-pills p-1 bg-light rounded border mb-0" id="batchNavTabs">
                            <li class="nav-item">
                                <button type="button" class="nav-link py-1 px-3 active fw-semibold" data-filter="all" id="btnFilterAll">
                                    <i class="fa-solid fa-boxes-stacked me-1"></i> Semua Resi (<span id="countFilterAll"><?= count($initialBatch) ?></span>)
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link py-1 px-3 fw-semibold text-primary" data-filter="non_cod" id="btnFilterNonCod">
                                    <i class="fa-solid fa-credit-card me-1"></i> Non-COD / Cashless (<span id="countFilterNonCod">0</span>)
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link py-1 px-3 fw-semibold text-danger" data-filter="cod" id="btnFilterCod">
                                    <i class="fa-solid fa-hand-holding-dollar me-1"></i> COD (<span id="countFilterCod">0</span>)
                                </button>
                            </li>
                        </ul>
                        <div class="text-muted small">
                            <span id="batchFilterDescription"><i class="fa-solid fa-circle-info text-info me-1"></i>Menampilkan semua resi hasil scan.</span>
                        </div>
                    </div>

                    <!-- BATCH TABLE -->
                    <div class="table-responsive border rounded-3 mb-3" style="max-height: 480px; overflow-y: auto;">
                        <table class="table table-hover table-batch-resi mb-0" id="tableBatchResi">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 35px;">#</th>
                                    <th>Kurir &amp; Channel</th>
                                    <th>No. Resi &amp; Pesanan</th>
                                    <th>Penerima</th>
                                    <th>Pengirim (Toko)</th>
                                    <th>Produk &amp; Variasi</th>
                                    <th class="text-end">Total / COD (Rp)</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBatchBody">
                                <!-- Populated dynamically by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- BOTTOM SUBMIT ACTION -->
                    <div class="card bg-success-subtle border border-success-subtle p-3 mb-0">
                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                            <div class="small">
                                <strong class="text-success d-block" id="textBatchActionTitle"><i class="fa-solid fa-bolt me-1"></i> Siap Diimpor ke Database</strong>
                                <span class="text-secondary" id="textBatchActionDesc"><span class="batchCountBtnText"><?= count($initialBatch) ?></span> pesanan akan membuat Sales Order, memotong stok fisik, menjadwalkan 3 CRM WhatsApp, dan mengkalkulasi RFM.</span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2 w-100 w-sm-auto justify-content-end">
                                <button type="button" class="btn btn-outline-success fw-bold px-3 py-2 text-nowrap" id="btnSubmitFilteredBatch" style="display: none;">
                                    <i class="fa-solid fa-filter me-1"></i> <span id="textSubmitFilteredBtn">Simpan Kategori Ini</span>
                                </button>
                                <button type="button" class="btn btn-success fw-bold px-4 py-2 shadow-sm text-nowrap flex-grow-1 flex-sm-grow-0" id="btnSubmitBatchResi">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. SINGLE VIEW: INDIVIDUAL RESI VERIFICATION -->
        <!-- ========================================== -->
        <div id="wrapperSingleResi" style="<?= $isInitialBatch ? 'display: none;' : 'display: block;' ?>">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="p-2 rounded bg-success-subtle text-success">
                            <i class="fa-solid fa-clipboard-check fs-5"></i>
                        </span>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold mb-0">Verifikasi Data Resi</h5>
                                <span class="badge bg-success-subtle text-success border px-2.5 py-1 rounded-pill fw-bold" id="badgeConfidence">
                                    Akurasi: <?= esc(round(($parsed['confidence'] ?? 0) * 100)) ?>%
                                </span>
                            </div>
                            <span class="text-muted small">Periksa &amp; lengkapi data hasil pemindaian sebelum integrasi otomatis ke ERP</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnBackToBatchView" style="display: none;">
                            <i class="fa-solid fa-layer-group me-1"></i> Lihat Daftar Batch (<span class="batchCountBtnText">0</span>)
                        </button>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form action="<?= base_url('marketplace/save-resi-order') ?>" method="POST" id="formSaveResi">
                        <?= csrf_field() ?>

                        <!-- 1. INFORMASI RESI & MARKETPLACE -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <span class="fw-bold text-dark small text-uppercase">
                                    <i class="fa-solid fa-receipt text-primary me-1"></i> 1. Informasi Resi &amp; Platform Marketplace
                                </span>
                                <span class="text-muted small" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-circle-check text-success me-1"></i>Identifikasi Kurir &amp; Channel
                                </span>
                            </div>
                            <div class="row g-3">
                                <!-- Nomor Resi -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Nomor Resi / AWB <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-barcode"></i></span>
                                        <input type="text" name="nomor_resi" id="inputResi" class="form-control font-monospace fw-bold text-primary" value="<?= esc($parsed['tracking_number'] ?? '') ?>" placeholder="CONTOH: ID25838628439GK" required>
                                    </div>
                                </div>

                                <!-- Ekspedisi Kurir -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Ekspedisi Kurir <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-truck-fast"></i></span>
                                        <input type="text" name="kurir" id="inputKurir" class="form-control fw-semibold" value="<?= esc($parsed['courier'] ?? 'SPX Express') ?>" placeholder="SPX Express / J&amp;T" required>
                                    </div>
                                </div>

                                <!-- Marketplace Channel -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Marketplace / Channel <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-shop"></i></span>
                                        <select name="source" id="selectSource" class="form-select">
                                            <option value="shopee" <?= ($parsed['source'] ?? '') === 'shopee' ? 'selected' : '' ?>>Shopee</option>
                                            <option value="tiktok" <?= ($parsed['source'] ?? '') === 'tiktok' ? 'selected' : '' ?>>TikTok Shop</option>
                                            <option value="tokopedia" <?= ($parsed['source'] ?? '') === 'tokopedia' ? 'selected' : '' ?>>Tokopedia</option>
                                            <option value="lazada" <?= ($parsed['source'] ?? '') === 'lazada' ? 'selected' : '' ?>>Lazada</option>
                                            <option value="manual" <?= ($parsed['source'] ?? '') === 'manual' ? 'selected' : '' ?>>Regular / Manual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- No. Pesanan / Order ID -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">No. Pesanan / Order ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" name="no_pesanan" id="inputNoPesanan" class="form-control font-monospace" value="<?= esc($parsed['no_pesanan'] ?? '') ?>" placeholder="2505220HM1MBOGK" required>
                                    </div>
                                </div>

                                <!-- Tanggal Pesanan / Batas Kirim -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Tanggal Pesanan (Batas Kirim)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-regular fa-calendar"></i></span>
                                        <input type="date" name="tanggal" id="inputTanggal" class="form-control font-monospace" value="<?= esc($parsed['tanggal'] ?? date('Y-m-d')) ?>">
                                    </div>
                                </div>

                                <!-- Tipe Pembayaran (Read-Only Label) -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1 d-flex justify-content-between align-items-center">
                                        <span>Tipe Pembayaran Pesanan</span>
                                        <span class="text-muted small" style="font-size: 0.68rem;"><i class="fa-solid fa-circle-check text-success me-1"></i>Otomatis Resi</span>
                                    </label>
                                    <div class="p-1.5 px-2 bg-light rounded border d-flex align-items-center justify-content-between" style="min-height: 38px;">
                                        <div>
                                            <span id="badgePaymentTypeCod" class="badge bg-danger text-white rounded-pill px-2.5 py-1 shadow-xs" style="<?= !empty($parsed['is_cod']) ? 'display: inline-flex;' : 'display: none;' ?> align-items: center; gap: 4px; font-size: 0.78rem;">
                                                <i class="fa-solid fa-hand-holding-dollar"></i> COD
                                            </span>
                                            <span id="badgePaymentTypeNonCod" class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="<?= empty($parsed['is_cod']) ? 'display: inline-flex;' : 'display: none;' ?> align-items: center; gap: 4px; font-size: 0.78rem;">
                                                <i class="fa-solid fa-credit-card"></i> Non-COD
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="is_cod" id="inputIsCod" value="<?= !empty($parsed['is_cod']) ? '1' : '0' ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 2. DATA PEMBELI & TUJUAN PENGIRIMAN -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <span class="fw-bold text-dark small text-uppercase">
                                    <i class="fa-solid fa-user-check text-primary me-1"></i> 2. Data Penerima &amp; Alamat Tujuan
                                </span>
                                <span class="text-muted small" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-address-book text-secondary me-1"></i>Sinkron Master Pelanggan &amp; CRM
                                </span>
                            </div>

                            <!-- SENDER DATA (DATA PENGIRIM TERISOLASI) -->
                            <div class="alert alert-light border py-2 px-3 mb-3 shadow-xs rounded-2" id="boxSenderData" style="<?= (!empty($parsed['sender_name']) || !empty($parsed['sender_phone'])) ? 'display: block;' : 'display: none;' ?>">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 small">
                                    <div>
                                        <span class="text-secondary fw-semibold"><i class="fa-solid fa-store me-1 text-primary"></i> Data Pengirim:</span>
                                        <strong class="text-dark ms-1" id="textSenderName"><?= esc($parsed['sender_name'] ?? '-') ?></strong>
                                        <span class="text-muted mx-1">&bull;</span>
                                        <span class="text-secondary font-monospace" id="textSenderPhone"><?= esc($parsed['sender_phone'] ?? '-') ?></span>
                                        <span class="text-muted mx-1">&bull;</span>
                                        <span class="badge bg-light text-dark border" id="textSenderCity"><?= esc($parsed['sender_city'] ?? '-') ?></span>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Terisolasi (Data Pembeli CRM Bersih)
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Nama Penerima -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Nama Penerima / Pelanggan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="nama_penerima" id="inputNamaPenerima" class="form-control fw-bold" value="<?= esc($parsed['recipient_name'] ?? '') ?>" placeholder="Nama lengkap pembeli" required>
                                    </div>
                                </div>

                                <!-- Nomor WhatsApp / Telepon -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Nomor WhatsApp / HP (Penerima)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-success"><i class="fa-brands fa-whatsapp"></i></span>
                                        <input type="text" name="no_telepon" id="inputTelepon" class="form-control font-monospace" value="<?= esc($parsed['recipient_phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">
                                        Kosongkan jika tidak tertera di label resi.
                                    </div>
                                </div>

                                <!-- Kota / Kabupaten -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold small text-dark mb-1">Kota / Kabupaten</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-location-dot"></i></span>
                                        <input type="text" name="kota" id="inputKota" class="form-control" value="<?= esc($parsed['city'] ?? '') ?>" placeholder="Kota tujuan pengiriman">
                                    </div>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark mb-1">Alamat Lengkap Pengiriman</label>
                                    <textarea name="alamat_penerima" id="inputAlamat" class="form-control" rows="2" placeholder="Nama jalan, kelurahan, kecamatan, kode pos"><?= esc($parsed['recipient_address'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RINCIAN PRODUK & HARGA PESANAN -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-dark small text-uppercase">
                                        <i class="fa-solid fa-boxes-stacked text-primary me-1"></i> 3. Rincian Produk &amp; Integrasi Stok
                                    </span>
                                    <span id="badgeCod" class="badge bg-danger text-white rounded-pill px-2 py-0.5 shadow-xs" style="<?= !empty($parsed['is_cod']) ? 'display: inline-block;' : 'display: none;' ?> font-size: 0.72rem;">
                                        <i class="fa-solid fa-hand-holding-dollar me-1"></i> COD
                                    </span>
                                    <span id="badgeNonCod" class="badge bg-primary text-white rounded-pill px-2 py-0.5 shadow-xs" style="<?= empty($parsed['is_cod']) ? 'display: inline-block;' : 'display: none;' ?> font-size: 0.72rem;">
                                        <i class="fa-solid fa-credit-card me-1"></i> Non-COD
                                    </span>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2.5 shadow-xs" id="btnAddProductRow">
                                    <i class="fa-solid fa-plus me-1"></i> Tambah Produk
                                </button>
                            </div>

                            <input type="hidden" name="items_json" id="inputItemsJson" value="">
                            <!-- Fallback hidden single-item fields -->
                            <input type="hidden" name="sku" id="hiddenSingleSku" value="<?= esc($parsed['sku'] ?? '') ?>">
                            <input type="hidden" name="qty" id="hiddenSingleQty" value="<?= esc($parsed['qty'] ?? 1) ?>">
                            <input type="hidden" name="product_name" id="hiddenSingleName" value="<?= esc($parsed['product_name'] ?? '') ?>">
                            <input type="hidden" name="variasi" id="hiddenSingleVariasi" value="<?= esc($parsed['variasi'] ?? '') ?>">

                            <div class="table-responsive bg-white rounded border mb-2 shadow-xs">
                                <table class="table table-sm table-hover align-middle mb-0" id="tableSingleProducts">
                                    <thead class="table-light small">
                                        <tr>
                                            <th style="width: 32px;" class="text-center">#</th>
                                            <th style="min-width: 170px;">Nama Produk di Resi</th>
                                            <th style="width: 130px;">SKU di Resi</th>
                                            <th style="width: 90px;">Variasi</th>
                                            <th style="width: 65px;" class="text-center">Qty</th>
                                            <th style="min-width: 200px;">Hubungkan Master Data Produk</th>
                                            <th style="width: 125px;" class="text-end">Harga Satuan (Rp)</th>
                                            <th style="width: 125px;" class="text-end">Subtotal (Rp)</th>
                                            <th style="width: 38px;" class="text-center"><i class="fa-solid fa-trash text-muted"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodySingleProducts">
                                        <!-- Generated dynamically by JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-1 mb-3">
                                <div class="small text-muted" id="productCalcHint" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-lightbulb text-warning me-1"></i> Jika produk belum ada di master data, pilih <strong>[+ Tambah Otomatis ke Master Data]</strong>.
                                </div>
                                <div class="fw-semibold small text-dark font-monospace">
                                    Total Kuantitas: <span id="textTotalItemQty" class="badge bg-secondary font-monospace px-2">1</span> item
                                </div>
                            </div>

                            <!-- FINANCIAL SUMMARY & TOTAL TAGIHAN BAR -->
                            <div class="card bg-light border p-3 shadow-xs">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong class="text-dark small"><i class="fa-solid fa-receipt me-1 text-primary"></i> Aturan Perhitungan ERP:</strong>
                                            <span id="labelTotalType" class="badge <?= !empty($parsed['is_cod']) ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?> font-monospace" style="font-size: 0.75rem;">
                                                <?= !empty($parsed['is_cod']) ? 'Nominal COD' : 'Akumulasi Master Data' ?>
                                            </span>
                                        </div>
                                        <div class="text-secondary small" id="textTotalCalculationDesc" style="font-size: 0.78rem;">
                                            <?= !empty($parsed['is_cod']) ? 'Pesanan COD: Nilai tagihan dibaca langsung dari nominal label resi.' : 'Pesanan Non-COD: Nilai pesanan disinkronkan otomatis dari Master Data Produk.' ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100 w-md-auto justify-content-md-end">
                                        <label class="form-label fw-bold text-dark small mb-0 text-nowrap">Total Pesanan:</label>
                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                            <span class="input-group-text bg-white font-monospace fw-bold text-primary">Rp</span>
                                            <input type="number" step="0.01" name="total" id="inputTotal" class="form-control font-monospace fw-bold fs-6 text-end text-dark bg-white" value="<?= (!empty($parsed['total']) && (float)$parsed['total'] > 0) ? esc($parsed['total']) : '' ?>" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. OTOMATISASI & ACTION BUTTONS -->
                        <div class="card bg-light border p-2.5 mb-3 rounded-2">
                            <div class="row g-2 text-center text-md-start small">
                                <div class="col-6 col-md-3">
                                    <div class="p-2 bg-white rounded border h-100">
                                        <strong class="text-primary d-block" style="font-size: 0.78rem;"><i class="fa-solid fa-boxes-stacked me-1"></i> Penjualan &amp; Stok</strong>
                                        <span class="text-muted" style="font-size: 0.72rem;">SO terbit &amp; stok fisik terpotong.</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 bg-white rounded border h-100">
                                        <strong class="text-secondary d-block" style="font-size: 0.78rem;"><i class="fa-solid fa-file-invoice-dollar me-1"></i> Faktur &amp; Jurnal</strong>
                                        <span class="text-muted" style="font-size: 0.72rem;">Invoice Lunas &amp; Jurnal double-entry.</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 bg-white rounded border h-100">
                                        <strong class="text-success d-block" style="font-size: 0.78rem;"><i class="fa-brands fa-whatsapp me-1"></i> CRM WhatsApp</strong>
                                        <span class="text-muted" style="font-size: 0.72rem;">Follow-up H+0, H+3, H+7 aktif.</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 bg-white rounded border h-100">
                                        <strong class="text-info d-block" style="font-size: 0.78rem;"><i class="fa-solid fa-chart-pie me-1"></i> RFM &amp; Notifikasi</strong>
                                        <span class="text-muted" style="font-size: 0.72rem;">Skor segmen RFM ter-update.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 pt-2 border-top">
                            <span class="text-muted small">
                                <i class="fa-solid fa-shield-check text-success me-1"></i> Seluruh modul otomatis terintegrasi saat pesanan disimpan.
                            </span>
                            <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-end">
                                <a href="<?= base_url('marketplace') ?>" class="btn btn-light border px-4 flex-fill flex-sm-grow-0 text-center">Batal</a>
                                <button type="submit" class="btn btn-success px-4 py-2 fw-bold flex-fill flex-sm-grow-0 shadow-sm d-inline-flex align-items-center justify-content-center gap-2" id="btnSubmitResi">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Simpan ke Sistem</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- PDF.js CDN for in-browser multi-page PDF thermal label reading -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<!-- Tesseract.js CDN for in-browser OCR -->
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<!-- ZXing JS CDN for in-browser 1D/2D Barcode reading -->
<script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.21.3/umd/index.min.js"></script>

<script>
// Configure PDF.js Worker
if (window.pdfjsLib) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

$(document).ready(function() {
    const dropZone = $('#dropZone');
    const fileInput = $('#resiFileInput');
    const rawTextArea = $('#rawTextScraped');
    const ocrBox = $('#ocrProgressBox');
    const ocrStatus = $('#ocrStatusText');
    const ocrPercent = $('#ocrPercentText');
    const ocrBar = $('#ocrProgressBar');
    const imgPreview = $('#imagePreview');
    const imgWrapper = $('#imagePreviewWrapper');

    // Batch items storage & Master Data lookup
    window.currentBatchItems = <?= json_encode($initialBatch) ?>;
    const productsMaster = <?= json_encode($products ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const masterProductsMap = {};
    productsMaster.forEach(p => {
        masterProductsMap[p.kode_produk] = p;
    });

    // Intelligent master product linker (matches by SKU, normalized SKU typos, or product title keywords)
    function findMasterProduct(sku, prodName) {
        if (!productsMaster || productsMaster.length === 0) return null;

        const cleanSku = (sku || '').trim();
        const cleanName = (prodName || '').trim();

        // 1. Direct SKU match
        if (cleanSku && cleanSku !== '-' && masterProductsMap[cleanSku]) {
            return masterProductsMap[cleanSku];
        }

        // 2. Normalized SKU prefix match (e.g. PRO- -> PRD-, PR0- -> PRD-)
        if (cleanSku && cleanSku !== '-') {
            const normalized = cleanSku.replace(/^(?:PR[O0]|PB[DO0]|PA[DO0])[-_]/i, 'PRD-')
                                       .replace(/[-_][oO]{2}(\d)$/, '-00$1')
                                       .replace(/[-_][oO](\d{2})$/, '-0$1')
                                       .replace(/[-_ ]+$/, '')
                                       .toUpperCase();
            if (masterProductsMap[normalized]) return masterProductsMap[normalized];

            const foundSku = productsMaster.find(p => (p.kode_produk || '').toUpperCase() === normalized || (p.kode_produk || '').toUpperCase() === cleanSku.toUpperCase());
            if (foundSku) return foundSku;
        }

        // 3. Name-based match against master products
        if (cleanName && cleanName !== '-') {
            const lowerName = cleanName.toLowerCase();
            // Direct substring
            for (const p of productsMaster) {
                const pLower = (p.nama_produk || '').toLowerCase();
                if (pLower && (lowerName.includes(pLower) || pLower.includes(lowerName))) {
                    return p;
                }
            }
            // Word overlap check (e.g. 2+ significant words match)
            for (const p of productsMaster) {
                const pWords = (p.nama_produk || '').toLowerCase().split(/\s+/).filter(w => w.length > 2);
                if (pWords.length >= 2) {
                    const matchCount = pWords.filter(w => lowerName.includes(w)).length;
                    if (matchCount >= 2 && matchCount >= pWords.length * 0.5) {
                        return p;
                    }
                }
            }
        }

        return null;
    }
    window.currentBatchFilter = 'all';
    window.currentEditingBatchIndex = null;

    // Trigger file input click on dropzone box (excluding when clicking directly on the label button)
    dropZone.on('click', function(e) {
        if (!$(e.target).closest('#btnPilihGambar').length && e.target.id !== 'resiFileInput') {
            triggerFilePicker();
        }
    });

    function triggerFilePicker() {
        const fileEl = document.getElementById('resiFileInput');
        if (fileEl) {
            fileEl.value = '';
            fileEl.click();
        }
    }

    // Drag and drop handlers
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        dropZone.addClass('dragover');
    });
    dropZone.on('dragleave drop', function(e) {
        e.preventDefault();
        dropZone.removeClass('dragover');
    });

    dropZone.on('drop', function(e) {
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelection(files[0]);
        }
    });

    fileInput.on('change', function() {
        if (this.files && this.files.length > 0) {
            const f = this.files[0];
            handleFileSelection(f);
        }
    });

    // Clipboard Paste (Ctrl + V) support
    $(document).on('paste', function(e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let index in items) {
            const item = items[index];
            if (item.kind === 'file' && item.type.startsWith('image/')) {
                const blob = item.getAsFile();
                handleFileSelection(blob);
                break;
            }
        }
    });

    let currentSelectedFile = null;

    function handleFileSelection(file) {
        if (!file) return;
        currentSelectedFile = file;

        $('#selectedFileNameBadge').html('<i class="fa-solid fa-circle-check me-1"></i> Memproses: <strong>' + $('<div>').text(file.name).html() + '</strong> (' + Math.round(file.size / 1024) + ' KB)').show();

        // 1. If file is PDF, use automated server-side batch processor (instant PyMuPDF render + Code 128 + high-speed OCR)
        if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
            uploadAndProcessPdfServer(file);
            return;
        }

        // 2. If file is image, display preview and run in-browser OCR + Barcode detection
        if (file.type.startsWith('image/') || /\.(png|jpe?g|webp)$/i.test(file.name)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.attr('src', e.target.result);
                imgWrapper.slideDown();
                preprocessImageForOcr(e.target.result, function(optimizedDataUrl, imgElement, canvasElement) {
                    runClientBarcodeAndOcr(optimizedDataUrl, imgElement, canvasElement);
                });
            };
            reader.readAsDataURL(file);
        }
    }

    // Helper to infer courier from tracking prefix
    function inferCourierFromTracking(trk, fallback = 'UNKNOWN') {
        if (!trk) return fallback;
        if (trk.startsWith('SPX') || /^ID\d{9,14}/i.test(trk)) return 'SPX Express';
        if (/^(?:JP|JT|JZ|JX)/i.test(trk)) return 'J&T Express';
        if (/^TKP/i.test(trk)) return 'Tokopedia Logistic';
        if (/^(?:IDE|IDS)/i.test(trk)) return 'ID Express';
        if (/^(?:NLID|NINJA)/i.test(trk)) return 'Ninja Xpress';
        if (/^(?:JNE|CM|TL|BDO|SOCG?\d)/i.test(trk)) return 'JNE';
        if (/^(?:00|SOC)/i.test(trk)) return 'SiCepat';
        if (/^(?:POS|169|179)/i.test(trk)) return 'Pos Indonesia';
        if (/^LP/i.test(trk)) return 'Lion Parcel';
        return fallback;
    }

    // Automated Server-Side Multi-Page PDF Batch Processor
    // Extracts barcodes, product names, SKUs, variations, quantities & recipients automatically
    function uploadAndProcessPdfServer(file) {
        ocrBox.slideDown();
        ocrStatus.html('<i class="fa-solid fa-cloud-arrow-up me-1 text-primary"></i> Mengunggah berkas PDF resi (' + Math.round(file.size / 1024) + ' KB) ke server...');
        ocrPercent.text('20%');
        ocrBar.css('width', '20%');

        const formData = new FormData();
        formData.append('resi_file', file);
        const csrfToken = typeof getCsrfCookie === 'function' ? getCsrfCookie() : $('meta[name="csrf-token-hash"]').attr('content');
        formData.append('<?= csrf_token() ?>', csrfToken);

        let progressVal = 20;
        const progressTimer = setInterval(() => {
            if (progressVal < 92) {
                progressVal += 6;
                ocrPercent.text(progressVal + '%');
                ocrBar.css('width', progressVal + '%');
                if (progressVal >= 35 && progressVal < 65) {
                    ocrStatus.html('<i class="fa-solid fa-barcode me-1 text-primary"></i> Membaca seluruh barcode nomor resi & merender halaman...');
                } else if (progressVal >= 65) {
                    ocrStatus.html('<i class="fa-solid fa-wand-magic-sparkles me-1 text-success"></i> Membaca teks produk, variasi, dan penerima secara otomatis...');
                }
            }
        }, 1100);

        $.ajax({
            url: '<?= base_url('marketplace/process-scan-resi') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 300000,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(resp) {
                clearInterval(progressTimer);
                ocrPercent.text('100%');
                ocrBar.css('width', '100%');
                ocrStatus.html('<i class="fa-solid fa-circle-check text-success me-1"></i> Selesai! ' + (resp.count || (resp.items ? resp.items.length : 1)) + ' label resi berhasil diproses otomatis.');
                setTimeout(() => ocrBox.slideUp(), 800);

                if (resp && resp.status === 'success') {
                    if (resp.is_batch && resp.items && resp.items.length > 1) {
                        showBatchView(resp.items);
                    } else if (resp.data) {
                        showSingleView(resp.data);
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: (resp && resp.message) ? resp.message : 'Tidak ada data resi yang dapat terbaca dari berkas PDF.'
                    });
                }
            },
            error: function(xhr) {
                clearInterval(progressTimer);
                console.warn('Server PDF parse failed, trying client fallback...', xhr);
                parsePdfMultiPages(file);
            }
        });
    }

    // Multi-Page PDF Parser using PDF.js with pure JS barcode decode & reusable Tesseract worker (Client Fallback)
    async function parsePdfMultiPages(file) {
        ocrBox.slideDown();
        ocrStatus.text('Membuka dokumen PDF...');
        ocrPercent.text('10%');
        ocrBar.css('width', '10%');

        try {
            const arrayBuffer = await file.arrayBuffer();
            const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
            const pdf = await loadingTask.promise;
            const numPages = pdf.numPages;

            ocrStatus.text('Mempersiapkan pembacaan ' + numPages + ' halaman label resi...');
            ocrPercent.text('15%');
            ocrBar.css('width', '15%');

            let worker = null;
            if (typeof Tesseract !== 'undefined') {
                try {
                    worker = await Tesseract.createWorker('eng');
                } catch (wErr) {
                    console.warn('Worker init fallback:', wErr);
                }
            }

            let fullExtractedText = '';

            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                ocrStatus.text('Membaca barcode & teks halaman ' + pageNum + ' dari ' + numPages + '...');
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({ scale: 1.8 });
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                await page.render({ canvasContext: ctx, viewport: viewport }).promise;

                // 1. Direct Code 128 Pure JS Barcode decode on canvas
                let pageBarcode = decodeCode128FromCanvas(canvas) || '';

                // 2. BarcodeDetector fallback if native API available
                if (!pageBarcode && 'BarcodeDetector' in window) {
                    try {
                        const detector = new BarcodeDetector({ formats: ['code_128', 'qr_code', 'code_39'] });
                        const barcodes = await detector.detect(canvas);
                        if (barcodes && barcodes.length > 0) {
                            pageBarcode = barcodes[0].rawValue.trim();
                        }
                    } catch (e) {}
                }

                // 3. Prepare dataUrl for OCR
                const pageDataUrl = canvas.toDataURL('image/jpeg', 0.85);

                // 4. Box 3 Crop fallback for tracking number if not yet found
                if (!pageBarcode && canvas.height > 400) {
                    try {
                        const b3Canvas = document.createElement('canvas');
                        const b3X = Math.round(canvas.width * 0.52);
                        const b3Y = Math.round(canvas.height * 0.055);
                        const b3W = Math.round(canvas.width * 0.35);
                        const b3H = Math.round(canvas.height * 0.038);
                        b3Canvas.width = b3W * 3;
                        b3Canvas.height = b3H * 3;
                        const b3Ctx = b3Canvas.getContext('2d');
                        b3Ctx.drawImage(canvas, b3X, b3Y, b3W, b3H, 0, 0, b3Canvas.width, b3Canvas.height);
                        const b3DataUrl = b3Canvas.toDataURL('image/jpeg', 0.85);
                        let b3Res = null;
                        if (worker) {
                            try { b3Res = await worker.recognize(b3DataUrl); } catch (e) {}
                        }
                        if (!b3Res && typeof Tesseract !== 'undefined') {
                            try { b3Res = await Tesseract.recognize(b3DataUrl, 'eng'); } catch (e) {}
                        }
                        if (b3Res && b3Res.data && b3Res.data.text) {
                            const m = b3Res.data.text.match(/(?:SPXID[0-9]{8,15}[A-Z0-9]{0,4}|ID[0-9]{9,15}[A-Z0-9]{0,4}|JP[0-9]{10,12}|JX[0-9]{10,12}|JNE[0-9]{10,15}|POS[0-9]{10,15}|00[0-9]{10,12})/i);
                            if (m) pageBarcode = m[0];
                        }
                    } catch (e) {}
                }

                // 5. Box COD crop on PDF page (TikTok Shop / J&T / Shopee COD)
                let pageCodText = '';
                if (canvas.height > 300) {
                    try {
                        const codCanvas = document.createElement('canvas');
                        const codX = Math.round(canvas.width * 0.02);
                        const codY = Math.round(canvas.height * 0.245);
                        const codW = Math.round(canvas.width * 0.40);
                        const codH = Math.round(canvas.height * 0.065);
                        const codScale = 2;
                        codCanvas.width = codW * codScale;
                        codCanvas.height = codH * codScale;
                        const cCtx = codCanvas.getContext('2d');
                        cCtx.drawImage(canvas, codX, codY, codW, codH, 0, 0, codW * codScale, codH * codScale);
                        const codDataUrl = codCanvas.toDataURL('image/jpeg', 0.85);
                        let cRes = null;
                        if (worker) {
                            try { cRes = await worker.recognize(codDataUrl); } catch (e) {}
                        }
                        if (!cRes && typeof Tesseract !== 'undefined') {
                            try { cRes = await Tesseract.recognize(codDataUrl, 'eng'); } catch (e) {}
                        }
                        if (cRes && cRes.data && cRes.data.text) {
                            const raw = cRes.data.text.trim();
                            if (/C[O0on][D0oO]|Bayar|Rp|[0-9Il]{2,4}[\.,\s][0-9IlO]{2,4}/i.test(raw)) {
                                pageCodText = raw;
                            }
                        }
                    } catch (e) {}
                }

                // 6. Full page text from PDF stream with preserved line breaks, or OCR
                const textContent = await page.getTextContent();
                let pageText = '';
                if (textContent && textContent.items && textContent.items.length > 0) {
                    const validItems = textContent.items.filter(it => (it.str || '').trim().length > 0);
                    // Sort items by vertical position y (descending), then horizontal x (ascending)
                    validItems.sort((a, b) => {
                        const yA = a.transform ? a.transform[5] : 0;
                        const yB = b.transform ? b.transform[5] : 0;
                        if (Math.abs(yA - yB) > 4) {
                            return yB - yA;
                        }
                        const xA = a.transform ? a.transform[4] : 0;
                        const xB = b.transform ? b.transform[4] : 0;
                        return xA - xB;
                    });

                    let lastY = null;
                    let currentLine = [];
                    const lines = [];
                    for (const it of validItems) {
                        const str = it.str.trim();
                        const curY = it.transform ? it.transform[5] : 0;
                        if (lastY === null) {
                            lastY = curY;
                            currentLine.push(str);
                        } else if (Math.abs(curY - lastY) > 4) {
                            lines.push(currentLine.join(' '));
                            currentLine = [str];
                            lastY = curY;
                        } else {
                            currentLine.push(str);
                        }
                    }
                    if (currentLine.length > 0) {
                        lines.push(currentLine.join(' '));
                    }
                    pageText = lines.join('\n');
                }

                if (pageText.length <= 25) {
                    try {
                        let fullRes = null;
                        if (worker) {
                            try {
                                fullRes = await worker.recognize(pageDataUrl);
                            } catch (wErr) {
                                console.warn('Worker recognize fallback to Tesseract.recognize:', wErr);
                            }
                        }
                        if (!fullRes && typeof Tesseract !== 'undefined') {
                            fullRes = await Tesseract.recognize(pageDataUrl, 'eng');
                        }
                        if (fullRes && fullRes.data && fullRes.data.text) {
                            pageText = fullRes.data.text;
                        }
                    } catch (ocrErr) {
                        console.warn('Page ' + pageNum + ' OCR error:', ocrErr);
                    }
                }

                let pageHeader = '\n--- PAGE ' + pageNum + ' ---\n';
                if (pageBarcode) {
                    pageHeader += 'No. Resi: ' + pageBarcode + '\n';
                }
                if (pageCodText) {
                    pageHeader += pageCodText + '\n';
                }
                fullExtractedText += pageHeader + pageText + '\n';

                const pct = Math.round(15 + ((pageNum / numPages) * 80));
                ocrPercent.text(pct + '%');
                ocrBar.css('width', pct + '%');
            }

            if (worker) {
                await worker.terminate();
            }

            ocrStatus.text('Selesai membaca berkas! Menganalisis resi...');
            ocrPercent.text('100%');
            ocrBar.css('width', '100%');
            setTimeout(() => ocrBox.slideUp(), 1000);

            // Strip Shopee delivery place type badge (kotak HOME / OFFICE / KANTOR / RUMAH)
            fullExtractedText = fullExtractedText.replace(/^[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s*$/gmi, '');
            fullExtractedText = fullExtractedText.replace(/^[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s+/gmi, '');

            rawTextArea.val(fullExtractedText);
            parseTextOnServer(fullExtractedText);
        } catch (err) {
            console.warn('PDF.js client parsing error:', err);
            ocrBox.slideUp();
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membaca PDF',
                text: 'Dokumen PDF tidak dapat diproses. Pastikan berkas PDF label tidak rusak atau terproteksi sandi.'
            });
        }
    }

    function preprocessImageForOcr(imageSrc, callback) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            try {
                const canvas = document.createElement('canvas');
                let width = img.naturalWidth || img.width;
                let height = img.naturalHeight || img.height;
                const isSmallCrop = (height < 160 || (width / height) > 2.8);

                // Scale up small crops or full labels for crisp text recognition
                if (width < 900) {
                    const factor = 1200 / width;
                    width = 1200;
                    height = Math.round(height * factor);
                } else if (width > 2400) {
                    const factor = 2000 / width;
                    width = 2000;
                    height = Math.round(height * factor);
                }

                if (isSmallCrop) {
                    // For small crop image (e.g. cropped COD box with borders touching edge):
                    // Add 30px white padding and wipe outer edge borders so Tesseract does not fail on edge borders
                    const padX = 30;
                    const padY = 30;
                    canvas.width = width + (padX * 2);
                    canvas.height = height + (padY * 2);
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, img.naturalWidth || img.width, img.naturalHeight || img.height, padX, padY, width, height);

                    // Wipe border margins to remove table borders touching crop boundaries
                    const wipeTopBottom = Math.max(6, Math.round(height * 0.12));
                    const wipeLeftRight = Math.max(6, Math.round(width * 0.04));
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(padX, padY, width, wipeTopBottom);
                    ctx.fillRect(padX, padY + height - wipeTopBottom, width, wipeTopBottom);
                    ctx.fillRect(padX, padY, wipeLeftRight, height);
                    ctx.fillRect(padX + width - wipeLeftRight, padY, wipeLeftRight, height);

                    const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const d = imgData.data;
                    for (let i = 0; i < d.length; i += 4) {
                        const lum = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                        const adjusted = Math.min(255, Math.max(0, ((lum - 128) * 1.35) + 128));
                        d[i] = adjusted;
                        d[i + 1] = adjusted;
                        d[i + 2] = adjusted;
                    }
                    ctx.putImageData(imgData, 0, 0);
                    callback(canvas.toDataURL('image/png'), img, canvas);
                    return;
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(img, 0, 0, width, height);

                const imgData = ctx.getImageData(0, 0, width, height);
                const d = imgData.data;
                // High-precision grayscale with gentle contrast stretch (preserves thermal dots)
                for (let i = 0; i < d.length; i += 4) {
                    const lum = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                    const adjusted = Math.min(255, Math.max(0, ((lum - 128) * 1.35) + 128));
                    d[i] = adjusted;
                    d[i + 1] = adjusted;
                    d[i + 2] = adjusted;
                }
                ctx.putImageData(imgData, 0, 0);
                callback(canvas.toDataURL('image/png'), img, canvas);
            } catch (err) {
                console.warn('Canvas pre-processing error, using raw image:', err);
                callback(imageSrc, img, null);
            }
        };
        img.onerror = function() {
            callback(imageSrc, null, null);
        };
        img.src = imageSrc;
    }

    // Pure JS Code 128 1D Barcode Scanner (zero external dependencies, fast & offline)
    const PATTERNS_128 = [
        "212222", "222122", "222221", "121223", "121322", "131222", "122213", "122312", "132212", "221213",
        "221312", "231212", "112232", "122132", "122231", "113222", "123122", "123221", "223211", "221132",
        "221231", "213212", "223112", "312131", "311222", "321122", "321221", "312212", "322112", "322211",
        "212123", "212321", "232121", "111323", "131123", "131321", "112313", "132113", "132311", "211313",
        "231113", "231311", "112133", "112331", "132131", "113123", "113321", "133121", "313121", "211331",
        "231131", "213113", "213311", "213131", "311123", "311321", "331121", "312113", "312311", "332111",
        "314111", "221411", "431111", "111224", "111422", "121124", "121421", "141122", "141221", "112214",
        "112412", "122114", "122411", "142112", "142211", "241211", "221114", "413111", "241112", "134111",
        "111242", "121142", "121241", "114212", "124112", "124211", "411212", "421112", "421211", "212141",
        "214121", "412121", "111143", "111341", "131141", "114113", "114311", "411113", "411311", "113141",
        "114131", "311141", "411131", "211412", "211214", "211232", "2331112"
    ];
    const PATTERN_MAP_128 = {};
    for (let i = 0; i < PATTERNS_128.length; i++) {
        PATTERN_MAP_128[PATTERNS_128[i]] = i;
    }

    function decodeCode128Row(rowLuminance, threshold) {
        const bars = [];
        let currentIsBlack = null;
        let currentLen = 0;
        const len = rowLuminance.length;

        for (let x = 0; x < len; x++) {
            const isBlack = (rowLuminance[x] < threshold);
            if (currentIsBlack === null) {
                currentIsBlack = isBlack;
                currentLen = 1;
            } else if (isBlack === currentIsBlack) {
                currentLen++;
            } else {
                bars.push({ black: currentIsBlack, len: currentLen });
                currentIsBlack = isBlack;
                currentLen = 1;
            }
        }
        bars.push({ black: currentIsBlack, len: currentLen });

        const barCount = bars.length;
        for (let startBarIdx = 0; startBarIdx < barCount - 20; startBarIdx++) {
            if (!bars[startBarIdx].black) continue;

            const startChunk = bars.slice(startBarIdx, startBarIdx + 6);
            if (startChunk.length < 6) continue;

            const startTotPx = startChunk.reduce((s, b) => s + b.len, 0);
            const mSize = startTotPx / 11;
            if (mSize < 0.8 || mSize > 35) continue;

            let startPat = '';
            for (const b of startChunk) {
                let modCount = Math.round(b.len / mSize);
                if (modCount < 1) modCount = 1;
                if (modCount > 4) modCount = 4;
                startPat += modCount;
            }

            const startSym = PATTERN_MAP_128[startPat];
            if (startSym !== 103 && startSym !== 104 && startSym !== 105) {
                continue;
            }

            const symbols = [startSym];
            let idx = startBarIdx + 6;
            let foundStop = false;

            while (idx < barCount) {
                if (idx + 7 <= barCount) {
                    const stopChunk = bars.slice(idx, idx + 7);
                    const stopTotPx = stopChunk.reduce((s, b) => s + b.len, 0);
                    const stopMSize = stopTotPx / 13;
                    let stopPat = '';
                    for (const b of stopChunk) {
                        let mc = Math.round(b.len / stopMSize);
                        if (mc < 1) mc = 1;
                        if (mc > 4) mc = 4;
                        stopPat += mc;
                    }
                    if (stopPat === "2331112") {
                        symbols.push(106);
                        foundStop = true;
                        break;
                    }
                }

                const chunk = bars.slice(idx, idx + 6);
                if (chunk.length < 6) break;

                const totPx = chunk.reduce((s, b) => s + b.len, 0);
                const symMSize = totPx / 11;
                let pat = '';
                for (const b of chunk) {
                    let mc = Math.round(b.len / symMSize);
                    if (mc < 1) mc = 1;
                    if (mc > 4) mc = 4;
                    pat += mc;
                }

                let sym = PATTERN_MAP_128[pat];
                if (sym === undefined) {
                    let bestSym = -1;
                    let bestDiff = 999;
                    for (let s = 0; s < 106; s++) {
                        const p = PATTERNS_128[s];
                        let diff = 0;
                        for (let k = 0; k < 6; k++) {
                            diff += Math.abs(parseInt(p[k], 10) - parseInt(pat[k], 10));
                        }
                        if (diff < bestDiff) {
                            bestDiff = diff;
                            bestSym = s;
                        }
                    }
                    sym = (bestDiff <= 2) ? bestSym : -1;
                }

                if (sym < 0) break;
                symbols.push(sym);
                idx += 6;
            }

            if (foundStop && symbols.length >= 4) {
                const checksumIndex = symbols.length - 2;
                let sum = symbols[0];
                for (let i = 1; i < checksumIndex; i++) {
                    sum += (symbols[i] * i);
                }
                const expectedChecksum = sum % 103;

                let mode = symbols[0];
                let text = '';
                for (let i = 1; i < checksumIndex; i++) {
                    const s = symbols[i];
                    if (s === 100) { mode = 104; continue; }
                    if (s === 99)  { mode = 105; continue; }
                    if (s === 101) { mode = 103; continue; }

                    if (mode === 104 || mode === 103) {
                        text += String.fromCharCode(32 + s);
                    } else if (mode === 105) {
                        text += (s < 10 ? '0' : '') + s;
                    }
                }

                if (text.length >= 6) {
                    return {
                        text: text,
                        checksumValid: (symbols[checksumIndex] === expectedChecksum)
                    };
                }
            }
        }
        return null;
    }

    function decodeCode128FromCanvas(canvas) {
        if (!canvas) return null;
        const ctx = canvas.getContext('2d');
        const w = canvas.width;
        const h = canvas.height;
        if (w < 50 || h < 20) return null;

        const sampleY = [];
        // Prioritize upper barcode region (8% to 35% height)
        for (let pct = 0.08; pct <= 0.35; pct += 0.02) {
            sampleY.push(Math.round(h * pct));
        }
        // Secondary barcode regions
        for (let pct = 0.38; pct <= 0.70; pct += 0.04) {
            sampleY.push(Math.round(h * pct));
        }

        for (const y of sampleY) {
            if (y < 0 || y >= h) continue;
            const imgData = ctx.getImageData(0, y, w, 1);
            const d = imgData.data;

            const lum = new Uint8Array(w);
            let minL = 255, maxL = 0, sumL = 0;
            for (let x = 0; x < w; x++) {
                const idx = x * 4;
                const l = Math.round(0.299 * d[idx] + 0.587 * d[idx + 1] + 0.114 * d[idx + 2]);
                lum[x] = l;
                if (l < minL) minL = l;
                if (l > maxL) maxL = l;
                sumL += l;
            }

            if ((maxL - minL) < 50) continue;
            const mid = Math.round((minL + maxL) / 2);
            const avg = Math.round(sumL / w);

            const thresholds = [mid, Math.round(mid * 0.9), Math.round(mid * 1.1), avg, 120];
            for (const th of thresholds) {
                const res = decodeCode128Row(lum, th);
                if (res && res.text) {
                    const trk = res.text.trim();
                    if (/^(?:SPX|JP|JT|JX|JZ|JNE|POS|SOC|LP|ID\d|NLID|TKP|IDE|IDS|\d{10,})/i.test(trk)) {
                        return trk;
                    }
                }
            }
        }
        return null;
    }

    async function runClientBarcodeAndOcr(imageSource, imgElement, canvasElement) {
        ocrBox.slideDown();
        ocrStatus.text('Mempersiapkan engine scan barcode & OCR JavaScript...');
        ocrPercent.text('15%');
        ocrBar.css('width', '15%');

        let detectedBarcode = '';
        let detectedResiText = '';
        window.detectedCodNominal = null;

        // 1. Direct Pure JavaScript Code 128 Scanner (Fast, 100% deterministic, zero dependencies)
        try {
            if (canvasElement) {
                detectedBarcode = decodeCode128FromCanvas(canvasElement) || '';
            }
            if (!detectedBarcode && imgElement) {
                const rawCanvas = document.createElement('canvas');
                rawCanvas.width = imgElement.naturalWidth || imgElement.width;
                rawCanvas.height = imgElement.naturalHeight || imgElement.height;
                const rCtx = rawCanvas.getContext('2d');
                rCtx.drawImage(imgElement, 0, 0);
                detectedBarcode = decodeCode128FromCanvas(rawCanvas) || '';
            }

            if (detectedBarcode) {
                window.lastDetectedBarcode = detectedBarcode;
                $('#inputResi').val(detectedBarcode);
                $('#inputKurir').val(inferCourierFromTracking(detectedBarcode, 'SPX Express'));
                ocrStatus.text('Barcode terdeteksi: ' + detectedBarcode + ' | Membaca detail teks...');
            }
        } catch (code128Err) {
            console.warn('Pure JS Code 128 scanner bypass:', code128Err);
        }

        // Prepare barcode crop, Box 3 crop, & Box COD crop canvases
        let bcCanvas = null;
        let boxResiCanvas = null;
        let boxCodCanvas = null;
        if (canvasElement) {
            try {
                // Barcode crop
                bcCanvas = document.createElement('canvas');
                const bcY = Math.round(canvasElement.height * 0.07);
                const bcH = Math.round(canvasElement.height * 0.28);
                bcCanvas.width = canvasElement.width;
                bcCanvas.height = bcH;
                const bcCtx = bcCanvas.getContext('2d');
                bcCtx.drawImage(canvasElement, 0, bcY, canvasElement.width, bcH, 0, 0, canvasElement.width, bcH);

                // Box 3 Header Resi crop (clean inner area without outer borders, scaled 3x)
                boxResiCanvas = document.createElement('canvas');
                const bxX = Math.round(canvasElement.width * 0.52);
                const bxY = Math.round(canvasElement.height * 0.055);
                const bxW = Math.round(canvasElement.width * 0.35);
                const bxH = Math.round(canvasElement.height * 0.038);
                boxResiCanvas.width = bxW * 3;
                boxResiCanvas.height = bxH * 3;
                const bxCtx = boxResiCanvas.getContext('2d');
                bxCtx.imageSmoothingEnabled = true;
                bxCtx.imageSmoothingQuality = 'high';
                bxCtx.drawImage(canvasElement, bxX, bxY, bxW, bxH, 0, 0, boxResiCanvas.width, boxResiCanvas.height);
                // Clean border margins to isolate text
                bxCtx.fillStyle = '#ffffff';
                bxCtx.fillRect(0, 0, boxResiCanvas.width, 6);
                bxCtx.fillRect(0, boxResiCanvas.height - 6, boxResiCanvas.width, 6);
                bxCtx.fillRect(0, 0, 6, boxResiCanvas.height);
                bxCtx.fillRect(boxResiCanvas.width - 6, 0, 6, boxResiCanvas.height);

                // Box COD Crop (TikTok Shop / J&T / Shopee COD cell: X ~ 2%..42%, Y ~ 24%..32%, scaled 3x with binarization)
                if (canvasElement.height > 300) {
                    boxCodCanvas = document.createElement('canvas');
                    const codX = Math.round(canvasElement.width * 0.02);
                    const codY = Math.round(canvasElement.height * 0.245);
                    const codW = Math.round(canvasElement.width * 0.40);
                    const codH = Math.round(canvasElement.height * 0.065);
                    const codScale = 3;
                    const codMargin = 20;
                    boxCodCanvas.width = (codW * codScale) + (codMargin * 2);
                    boxCodCanvas.height = (codH * codScale) + (codMargin * 2);
                    const codCtx = boxCodCanvas.getContext('2d');
                    codCtx.imageSmoothingEnabled = true;
                    codCtx.imageSmoothingQuality = 'high';
                    codCtx.fillStyle = '#ffffff';
                    codCtx.fillRect(0, 0, boxCodCanvas.width, boxCodCanvas.height);
                    codCtx.drawImage(canvasElement, codX, codY, codW, codH, codMargin, codMargin, codW * codScale, codH * codScale);
                    // High-contrast binarization for clean OCR
                    try {
                        const imgData = codCtx.getImageData(0, 0, boxCodCanvas.width, boxCodCanvas.height);
                        const d = imgData.data;
                        for (let i = 0; i < d.length; i += 4) {
                            const lum = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                            const val = lum < 150 ? 0 : 255;
                            d[i] = val;
                            d[i + 1] = val;
                            d[i + 2] = val;
                        }
                        codCtx.putImageData(imgData, 0, 0);
                    } catch (bErr) {}
                }
            } catch (e) {
                console.warn('Sub-canvas crop bypass:', e);
            }
        }

        // 2. Native BarcodeDetector (safe invocation if formats supported)
        if (!detectedBarcode && 'BarcodeDetector' in window) {
            try {
                let supportedFormats = [];
                if (typeof BarcodeDetector.getSupportedFormats === 'function') {
                    supportedFormats = await BarcodeDetector.getSupportedFormats();
                }
                const testFormats = ['code_128', 'code_39', 'codabar', 'ean_13', 'itf', 'qr_code'].filter(f => supportedFormats.includes(f));
                if (testFormats.length > 0) {
                    const barcodeDetector = new BarcodeDetector({ formats: testFormats });
                    const targets = [bcCanvas, canvasElement, imgElement].filter(Boolean);
                    for (const target of targets) {
                        const barcodes = await barcodeDetector.detect(target);
                        if (barcodes && barcodes.length > 0) {
                            for (const bc of barcodes) {
                                const val = (bc.rawValue || '').trim();
                                if (/^(?:SPX|JP|JT|JX|JZ|JNE|POS|SOC|LP|ID\d|NLID|TKP|IDE|IDS)/i.test(val) || val.length >= 10) {
                                    detectedBarcode = val;
                                    break;
                                }
                            }
                            if (!detectedBarcode && barcodes[0].rawValue) {
                                detectedBarcode = barcodes[0].rawValue.trim();
                            }
                        }
                        if (detectedBarcode) break;
                    }
                    if (detectedBarcode) {
                        window.lastDetectedBarcode = detectedBarcode;
                        $('#inputResi').val(detectedBarcode);
                        $('#inputKurir').val(inferCourierFromTracking(detectedBarcode, 'SPX Express'));
                        ocrStatus.text('Barcode terdeteksi: ' + detectedBarcode + ' | Membaca detail teks...');
                    }
                }
            } catch (bcErr) {
                console.warn('Native BarcodeDetector bypass:', bcErr);
            }
        }

        // 3. Fallback: ZXing JS
        if (!detectedBarcode && typeof ZXing !== 'undefined') {
            try {
                const codeReader = new ZXing.BrowserMultiFormatReader();
                const zxTargets = [bcCanvas, canvasElement].filter(Boolean);
                for (const zxTarget of zxTargets) {
                    try {
                        const zxResult = await codeReader.decodeFromCanvas(zxTarget);
                        if (zxResult && zxResult.getText()) {
                            detectedBarcode = zxResult.getText().trim();
                            window.lastDetectedBarcode = detectedBarcode;
                            $('#inputResi').val(detectedBarcode);
                            $('#inputKurir').val(inferCourierFromTracking(detectedBarcode, 'SPX Express'));
                            ocrStatus.text('Barcode terdeteksi (ZXing): ' + detectedBarcode + ' | Membaca detail teks...');
                            break;
                        }
                    } catch (zErr) {}
                }
            } catch (zxErr) {
                console.warn('ZXing bypass:', zxErr);
            }
        }

        // 4. High-speed OCR with Tesseract.js (WASM)
        if (typeof Tesseract !== 'undefined') {
            try {
                let detectedCodText = '';

                // If Box COD crop is prepared, run fast OCR on Box COD first!
                if (boxCodCanvas) {
                    try {
                        const codDataUrl = boxCodCanvas.toDataURL('image/png');
                        const codRes = await Tesseract.recognize(codDataUrl, 'eng');
                        if (codRes && codRes.data && codRes.data.text) {
                            const rawCod = codRes.data.text.trim();
                            if (/C[O0on][D0oO]|Bayar|Rp|[0-9Il]{2,4}[\.,\s][0-9IlO]{2,4}/i.test(rawCod)) {
                                detectedCodText = rawCod;
                                const codNumMatch = rawCod.match(/(?:C[O0on][D0oO]|Bayar|Rp)?[:\|\-\s]*([0-9Il]{1,4}[\.,\s][0-9IlO]{3}(?:[\.,\s][0-9IlO]{3})*|[0-9]{4,8})/i);
                                if (codNumMatch) {
                                    let cleanNum = codNumMatch[1].replace(/[Il]/g, '1').replace(/[O]/g, '0').replace(/[\.,\s]/g, '');
                                    const parsedNum = parseFloat(cleanNum);
                                    if (parsedNum > 0) {
                                        window.detectedCodNominal = parsedNum;
                                    }
                                }
                            }
                        }
                    } catch (cErr) {
                        console.warn('Box COD OCR bypass:', cErr);
                    }
                }

                // If barcode wasn't found, run a fast OCR on the cleaned Box 3 crop first!
                if (!detectedBarcode && boxResiCanvas) {
                    try {
                        ocrStatus.text('Membaca kotak nomor resi...');
                        const boxDataUrl = boxResiCanvas.toDataURL('image/png');
                        const boxRes = await Tesseract.recognize(boxDataUrl, 'eng');
                        if (boxRes && boxRes.data && boxRes.data.text) {
                            const bt = boxRes.data.text.trim();
                            const m = bt.match(/(?:SPXID[0-9]{8,15}[A-Z0-9]{0,4}|ID[0-9]{9,15}[A-Z0-9]{0,4}|JP[0-9]{10,12}|JX[0-9]{10,12}|JNE[0-9]{10,15}|POS[0-9]{10,15}|00[0-9]{10,12})/i);
                            if (m) {
                                detectedResiText = m[0];
                                window.lastDetectedBarcode = detectedResiText;
                                $('#inputResi').val(detectedResiText);
                                ocrStatus.text('Nomor resi teridentifikasi: ' + detectedResiText);
                            }
                        }
                    } catch (bErr) {
                        console.warn('Box 3 OCR bypass:', bErr);
                    }
                }

                // Full Image OCR for items, recipient, address, cod
                const { data: { text } } = await Tesseract.recognize(
                    imageSource,
                    'eng',
                    {
                        logger: m => {
                            if (m.status === 'recognizing text') {
                                const pct = Math.max(25, Math.round((m.progress || 0) * 100));
                                ocrStatus.text('Membaca detail teks resi (' + pct + '%)...');
                                ocrPercent.text(pct + '%');
                                ocrBar.css('width', pct + '%');
                            }
                        }
                    }
                );

                let finalText = text || '';

                // Strip Shopee delivery place type badge (kotak HOME / OFFICE / KANTOR / RUMAH)
                finalText = finalText.replace(/^[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s*$/gmi, '');
                finalText = finalText.replace(/^[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s+/gmi, '');

                // Prepend detected COD text if extracted
                if (detectedCodText) {
                    finalText = detectedCodText + '\n' + finalText;
                }

                // Prepend detected tracking number explicitly to guarantee 100% accuracy on backend parse
                const activeTrk = detectedBarcode || detectedResiText || window.lastDetectedBarcode || '';
                if (activeTrk) {
                    finalText = 'No. Resi: ' + activeTrk + '\n' + finalText;
                }

                ocrStatus.text('Ekstraksi teks selesai! Menganalisis data...');
                ocrPercent.text('100%');
                ocrBar.css('width', '100%');
                setTimeout(() => ocrBox.slideUp(), 1000);

                // Quick client-side date extraction from Batas Kirim
                try {
                    const dateMatch = finalText.match(/(?:Batas\s*(?:K[il1]r[il1]m|Peng[il1]r[il1]man|Waktu(?:\s*K[il1]r[il1]m)?)|K[il1]r[il1]m\s*Sebe[il1]um|Ship\s*by(?:\s*date)?|Deadline(?:\s*K[il1]r[il1]m)?|Tgl\.?\s*(?:K[il1]r[il1]m|Peng[il1]r[il1]man|Batas))\s*[:\|\-;\s]*([0-9]{1,4}\s*[\-\/\.]\s*[0-9]{1,2}\s*[\-\/\.]\s*[0-9]{2,4})/i);
                    if (dateMatch) {
                        const rawParts = dateMatch[1].replace(/\s+/g, '').split(/[\-\/\.]/);
                        if (rawParts.length === 3) {
                            let y, m, d;
                            if (rawParts[0].length === 4) {
                                y = rawParts[0]; m = rawParts[1].padStart(2, '0'); d = rawParts[2].padStart(2, '0');
                            } else {
                                d = rawParts[0].padStart(2, '0'); m = rawParts[1].padStart(2, '0'); y = rawParts[2].length === 2 ? '20' + rawParts[2] : rawParts[2];
                            }
                            if (parseInt(m) >= 1 && parseInt(m) <= 12 && parseInt(d) >= 1 && parseInt(d) <= 31) {
                                $('#inputTanggal').val(`${y}-${m}-${d}`);
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Client date extraction error:', e);
                }

                rawTextArea.val(finalText);
                parseTextOnServer(finalText);
            } catch (err) {
                console.warn('Tesseract OCR error:', err);
                ocrBox.slideUp();
                const activeTrk = detectedBarcode || window.lastDetectedBarcode || '';
                if (activeTrk) {
                    const fallbackText = 'No. Resi: ' + activeTrk;
                    rawTextArea.val(fallbackText);
                    parseTextOnServer(fallbackText);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Teks Label Tidak Terbaca Jelas',
                        text: 'Pastikan foto label cukup tajam dan kontras, atau masukkan nomor resi secara manual.'
                    });
                }
            }
        } else if (detectedBarcode || window.lastDetectedBarcode) {
            const activeTrk = detectedBarcode || window.lastDetectedBarcode;
            const finalText = 'No. Resi: ' + activeTrk;
            ocrBox.slideUp();
            rawTextArea.val(finalText);
            parseTextOnServer(finalText);
        } else {
            ocrBox.slideUp();
            Swal.fire({
                icon: 'error',
                title: 'Library OCR Belum Siap',
                text: 'Silakan refresh halaman untuk memuat engine OCR JavaScript.'
            });
        }
    }

    function parseTextOnServer(text) {
        $.ajax({
            url: '<?= base_url('marketplace/parse-resi-text') ?>',
            type: 'POST',
            data: {
                raw_text: text,
                <?= csrf_token() ?>: typeof getCsrfCookie === 'function' ? getCsrfCookie() : $('meta[name="csrf-token-hash"]').attr('content')
            },
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    if (resp.is_batch && resp.items && resp.items.length > 1) {
                        showBatchView(resp.items);
                    } else if (resp.data) {
                        showSingleView(resp.data);
                    }
                    if (resp.data && resp.data.raw_text) {
                        rawTextArea.val(resp.data.raw_text);
                    }
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses Resi',
                    text: 'Terjadi gangguan saat memproses data resi.'
                });
            }
        });
    }

    function generateProductOptionsHtml(selectedSku) {
        let html = `<option value="">-- Hubungkan Master Data Produk --</option>`;
        html += `<option value="__AUTO_NEW__" ${selectedSku === '__AUTO_NEW__' ? 'selected' : ''}>[+ Tambah Otomatis ke Master Data]</option>`;
        let found = false;
        productsMaster.forEach(p => {
            const isSel = (p.kode_produk === selectedSku) ? 'selected' : '';
            if (isSel) found = true;
            const hargaNum = parseFloat(p.harga_jual) || 0;
            html += `<option value="${$('<div>').text(p.kode_produk).html()}" data-harga="${hargaNum}" ${isSel}>[${$('<div>').text(p.kode_produk).html()}] ${$('<div>').text(p.nama_produk).html()} (Rp ${hargaNum.toLocaleString('id-ID')})</option>`;
        });
        if (selectedSku && selectedSku !== '__AUTO_NEW__' && !found) {
            html += `<option value="${$('<div>').text(selectedSku).html()}" selected>[SKU Label: ${$('<div>').text(selectedSku).html()}] Gunakan SKU Ini</option>`;
        }
        return html;
    }

    function renderSingleProductRows(items, isCod) {
        const tbody = $('#bodySingleProducts');
        tbody.empty();

        const itemsList = (items && items.length > 0) ? items : [
            {
                nama_produk: $('#hiddenSingleName').val() || '',
                variasi: $('#hiddenSingleVariasi').val() || '',
                sku: $('#hiddenSingleSku').val() || '',
                qty: parseInt($('#hiddenSingleQty').val()) || 1,
                harga_satuan: 0
            }
        ];

        itemsList.forEach((it, idx) => {
            let nama = it.nama_produk || it.nama || it.product_name || '';
            const variasi = it.variasi || '';
            let sku = it.sku || '';
            let qty = parseInt(it.qty) || 1;
            if (qty < 1) qty = 1;

            const matched = findMasterProduct(sku, nama);
            if (matched) {
                if (!sku || sku === '-') sku = matched.kode_produk;
                if (!nama) nama = matched.nama_produk;
            }

            let unitPrice = 0;
            if (isCod && it.harga_satuan && parseFloat(it.harga_satuan) > 0) {
                unitPrice = parseFloat(it.harga_satuan);
            } else if (matched) {
                unitPrice = parseFloat(matched.harga_jual) || 0;
            } else if (sku && masterProductsMap[sku]) {
                unitPrice = parseFloat(masterProductsMap[sku].harga_jual) || 0;
            } else if (it.harga_satuan && parseFloat(it.harga_satuan) > 0) {
                unitPrice = parseFloat(it.harga_satuan);
            } else if (isCod && (parseFloat($('#inputTotal').val()) || window.currentParsedTotal || 0) > 0 && itemsList.length === 1) {
                const codNominal = parseFloat($('#inputTotal').val()) || window.currentParsedTotal || 0;
                unitPrice = Math.round(codNominal / qty);
            }

            const subtotal = unitPrice * qty;

            const row = `
                <tr class="product-item-row" data-index="${idx}">
                    <td class="text-center font-monospace text-muted small">${idx + 1}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-nama bg-white" value="${$('<div>').text(nama).html()}" placeholder="Nama produk di resi">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm font-monospace item-resi-sku fw-semibold text-primary" value="${$('<div>').text(sku).html()}" placeholder="SKU di label">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-variasi bg-white" value="${$('<div>').text(variasi).html()}" placeholder="Variasi (misal: 20)">
                    </td>
                    <td>
                        <input type="number" min="1" step="1" class="form-control form-control-sm text-center font-monospace fw-bold item-qty" value="${qty}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm font-monospace item-sku">
                            ${generateProductOptionsHtml(sku)}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm text-end font-monospace item-harga" value="${unitPrice > 0 ? unitPrice : ''}" placeholder="0">
                    </td>
                    <td class="text-end font-monospace fw-bold text-dark item-subtotal-text">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item-row py-0 px-2" title="Hapus"><i class="fa-solid fa-times"></i></button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        recalculateSingleTotals();
    }

    function recalculateSingleTotals() {
        const isCod = $('#inputIsCod').val() === '1';
        let totalQty = 0;
        let sumSubtotal = 0;
        const serializedItems = [];

        $('#bodySingleProducts tr.product-item-row').each(function(idx) {
            $(this).find('td:first').text(idx + 1);
            const nama = $(this).find('.item-nama').val().trim();
            const resiSku = $(this).find('.item-resi-sku').val().trim();
            const variasi = $(this).find('.item-variasi').val().trim();
            const masterSku = $(this).find('.item-sku').val();
            const sku = resiSku || masterSku;
            const qty = Math.max(1, parseInt($(this).find('.item-qty').val()) || 1);
            let harga = parseFloat($(this).find('.item-harga').val()) || 0;

            const subtotal = harga * qty;
            totalQty += qty;
            sumSubtotal += subtotal;

            $(this).find('.item-subtotal-text').text('Rp ' + subtotal.toLocaleString('id-ID'));

            serializedItems.push({
                nama_produk: nama,
                variasi: variasi,
                sku: sku,
                master_sku: masterSku,
                qty: qty,
                harga_satuan: harga,
                subtotal: subtotal
            });
        });

        $('#textTotalItemQty').text(totalQty);
        $('#inputItemsJson').val(JSON.stringify(serializedItems));

        if (serializedItems.length > 0) {
            $('#hiddenSingleSku').val(serializedItems[0].sku);
            $('#hiddenSingleQty').val(serializedItems[0].qty);
            $('#hiddenSingleName').val(serializedItems[0].nama_produk);
            $('#hiddenSingleVariasi').val(serializedItems[0].variasi);
        }

        if (!isCod) {
            // Non-COD: total is strictly adjusted from Master Data calculations
            if (sumSubtotal > 0) {
                $('#inputTotal').val(sumSubtotal);
                $('#textTotalCalculationDesc').text('Pesanan Non-COD: Nilai pesanan dihitung otomatis dari akumulasi harga Master Data (Rp ' + sumSubtotal.toLocaleString('id-ID') + ').');
            } else {
                $('#inputTotal').val('');
                $('#textTotalCalculationDesc').text('Pesanan Non-COD: Produk belum ada di Master Data / belum ada harga.');
            }
            $('#labelTotalType').removeClass('bg-danger-subtle text-danger').addClass('bg-primary-subtle text-primary').text('Akumulasi Master Data (Non-COD)');
        } else {
            // COD: total is read from label, but if empty, use sumSubtotal
            const curVal = parseFloat($('#inputTotal').val()) || 0;
            if (curVal <= 0 && sumSubtotal > 0) {
                $('#inputTotal').val(sumSubtotal);
            } else if (curVal <= 0) {
                $('#inputTotal').val('');
            }
            $('#labelTotalType').removeClass('bg-primary-subtle text-primary').addClass('bg-danger-subtle text-danger').text('Nominal COD');
            $('#textTotalCalculationDesc').text('Pesanan COD: Nilai tagihan dibaca langsung dari nominal label resi.');
        }
    }

    // Single Resi Form Populate
    function showSingleView(data) {
        $('#wrapperBatchResi').slideUp();
        $('#wrapperSingleResi').slideDown();

        if (window.currentBatchItems && window.currentBatchItems.length > 1) {
            $('#btnBackToBatchView').show();
        } else {
            $('#btnBackToBatchView').hide();
        }

        populateFormFields(data);
    }

    function populateFormFields(data) {
        if (!data) return;
        const trackingVal = data.tracking_number || window.lastDetectedBarcode || data.no_pesanan || '';
        if (trackingVal) {
            $('#inputResi').val(trackingVal);
        }
        if (data.courier && data.courier !== 'UNKNOWN') {
            $('#inputKurir').val(data.courier);
        } else {
            $('#inputKurir').val(inferCourierFromTracking(trackingVal, 'SPX Express'));
        }
        if (data.source) $('#selectSource').val(data.source);
        if (data.no_pesanan) $('#inputNoPesanan').val(data.no_pesanan);
        if (data.recipient_name) {
            const cleanName = data.recipient_name.replace(/\b(?:HOME|OFFICE|KANTOR|RUMAH)\b/gi, '').trim();
            $('#inputNamaPenerima').val(cleanName || data.recipient_name);
        }
        $('#inputTelepon').val(data.recipient_phone || '');
        if (data.recipient_address) {
            const cleanAddr = data.recipient_address.replace(/^(?:HOME|OFFICE|KANTOR|RUMAH)\s*,\s*/i, '').replace(/\b(?:HOME|OFFICE|KANTOR|RUMAH)\b\s*,\s*/i, '').trim();
            $('#inputAlamat').val(cleanAddr || data.recipient_address);
        }
        if (data.city) $('#inputKota').val(data.city);
        if (data.tanggal) $('#inputTanggal').val(data.tanggal);

        let isCod = Boolean(data.is_cod);
        if (!isCod && window.detectedCodNominal) {
            isCod = true;
            data.is_cod = true;
        }

        if (window.detectedCodNominal && (!data.total || parseFloat(data.total) <= 0)) {
            data.total = window.detectedCodNominal;
            isCod = true;
            data.is_cod = true;
        }

        setSinglePaymentMode(isCod, false);

        if (isCod) {
            if (data.total && data.total > 0) {
                $('#inputTotal').val(data.total);
                window.currentParsedTotal = parseFloat(data.total) || 0;
            }
        }

        // Sender data isolation
        if (data.sender_name || data.sender_phone || data.sender_city) {
            $('#textSenderName').text(data.sender_name || '-');
            $('#textSenderPhone').text(data.sender_phone || '-');
            $('#textSenderCity').text(data.sender_city || '-');
            $('#boxSenderData').slideDown();
        } else {
            $('#boxSenderData').hide();
        }

        // Render multi-product rows
        let items = data.items;
        if (!items || items.length === 0) {
            items = [
                {
                    nama_produk: data.product_name || data.nama_produk || '',
                    variasi: data.variasi || '',
                    sku: data.sku || '',
                    qty: data.qty || 1,
                    harga_satuan: isCod ? data.total : (masterProductsMap[data.sku]?.harga_jual || 0)
                }
            ];
        } else if (isCod && data.total > 0 && items.length === 1) {
            items[0].harga_satuan = Math.round(data.total / Math.max(1, parseInt(items[0].qty) || 1));
        }
        renderSingleProductRows(items, isCod);

        const pct = Math.round((data.confidence || 0.85) * 100);
        const badge = $('#badgeConfidence');
        if (pct >= 85) {
            badge.removeClass('bg-warning-subtle text-warning bg-danger-subtle text-danger').addClass('bg-success-subtle text-success').html('<i class="fa-solid fa-circle-check me-1"></i> Akurasi: ' + pct + '% (Sangat Baik)');
        } else if (pct >= 60) {
            badge.removeClass('bg-success-subtle text-success bg-danger-subtle text-danger').addClass('bg-warning-subtle text-warning').html('<i class="fa-solid fa-triangle-exclamation me-1"></i> Akurasi: ' + pct + '% (Perlu Verifikasi)');
        } else {
            badge.removeClass('bg-success-subtle text-success bg-warning-subtle text-warning').addClass('bg-danger-subtle text-danger').html('<i class="fa-solid fa-circle-xmark me-1"></i> Akurasi: ' + pct + '% (Rendah)');
        }
    }

    // Payment Mode Display for Single Form View (Read-Only Badge Terbaca dari Resi)
    function setSinglePaymentMode(isCod, shouldRecalculate = true) {
        $('#inputIsCod').val(isCod ? '1' : '0');

        if (isCod) {
            $('#badgePaymentTypeCod').css('display', 'inline-flex');
            $('#badgePaymentTypeNonCod').hide();
            $('#badgeCod').show();
            $('#badgeNonCod').hide();
        } else {
            $('#badgePaymentTypeNonCod').css('display', 'inline-flex');
            $('#badgePaymentTypeCod').hide();
            $('#badgeNonCod').show();
            $('#badgeCod').hide();
        }

        if (shouldRecalculate) {
            recalculateSingleTotals();
        }
    }

    // Batch Multi-Resi Render & Table Population
    function showBatchView(items) {
        window.currentBatchItems = items;
        window.currentEditingBatchIndex = null;
        $('#wrapperSingleResi').slideUp();
        $('#wrapperBatchResi').slideDown();
        renderBatchTable(items);
    }

    function renderBatchTable(items) {
        const allItems = items || [];
        const countAll = allItems.length;
        const countCod = allItems.filter(it => it.is_cod).length;
        const countNonCod = allItems.filter(it => !it.is_cod).length;

        let sumTotalNonCod = 0;
        let sumTotalCod = 0;

        allItems.forEach(item => {
            const isCod = Boolean(item.is_cod);
            let total = parseFloat(item.total || 0);
            if (!isCod) {
                let computed = 0;
                if (item.items && item.items.length > 0) {
                    item.items.forEach(it => {
                        const pr = masterProductsMap[it.sku];
                        const h = pr ? parseFloat(pr.harga_jual) : (parseFloat(it.harga_satuan) || 0);
                        computed += h * (parseInt(it.qty) || 1);
                    });
                } else if (item.sku && masterProductsMap[item.sku]) {
                    computed = parseFloat(masterProductsMap[item.sku].harga_jual) * (parseInt(item.qty) || 1);
                }
                if (computed > 0) {
                    total = computed;
                    item.total = total;
                }
            }
            if (total <= 0) total = 0;

            if (isCod) {
                sumTotalCod += total;
            } else {
                sumTotalNonCod += total;
            }
        });

        // Update counts & total amounts on summary cards
        $('#badgeCardNonCodCount').text(countNonCod + ' Resi');
        $('#textCardNonCodTotal').text('Rp ' + sumTotalNonCod.toLocaleString('id-ID'));
        $('#badgeCardCodCount').text(countCod + ' Resi');
        $('#textCardCodTotal').text('Rp ' + sumTotalCod.toLocaleString('id-ID'));

        // Update badge counts on nav pills
        $('#countFilterAll').text(countAll);
        $('#countFilterCod').text(countCod);
        $('#countFilterNonCod').text(countNonCod);

        $('#badgeBatchCount').text(countAll + ' Resi');
        $('#alertBatchCount').text(countAll + ' label resi');
        $('.batchCountBtnText').text(countAll);

        const tbody = $('#tableBatchBody');
        tbody.empty();

        if (countAll === 0) {
            tbody.html('<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada resi dalam daftar batch.</td></tr>');
            $('#btnSubmitFilteredBatch').hide();
            return;
        }

        const filter = window.currentBatchFilter || 'all';

        // Update active tab & summary card styles
        $('#batchNavTabs button').removeClass('active active-filter-all active-filter-non-cod active-filter-cod');
        $('#cardBatchNonCod').removeClass('active-card');
        $('#cardBatchCod').removeClass('active-card-cod');

        if (filter === 'cod') {
            $('#btnFilterCod').addClass('active active-filter-cod');
            $('#cardBatchCod').addClass('active-card-cod');
            $('#batchFilterDescription').html('<i class="fa-solid fa-hand-holding-dollar text-danger me-1"></i>Menampilkan <strong>' + countCod + ' pesanan COD</strong> (Wajib tagih tunai).');
            if (countCod > 0) {
                $('#btnSubmitFilteredBatch').show().removeClass('btn-outline-primary').addClass('btn-outline-danger')
                    .html('<i class="fa-solid fa-hand-holding-dollar me-1"></i> Simpan Hanya Resi COD (' + countCod + ')');
            } else {
                $('#btnSubmitFilteredBatch').hide();
            }
        } else if (filter === 'non_cod') {
            $('#btnFilterNonCod').addClass('active active-filter-non-cod');
            $('#cardBatchNonCod').addClass('active-card');
            $('#batchFilterDescription').html('<i class="fa-solid fa-credit-card text-primary me-1"></i>Menampilkan <strong>' + countNonCod + ' pesanan Non-COD / Cashless</strong> (Lunas online).');
            if (countNonCod > 0) {
                $('#btnSubmitFilteredBatch').show().removeClass('btn-outline-danger').addClass('btn-outline-primary')
                    .html('<i class="fa-solid fa-credit-card me-1"></i> Simpan Hanya Resi Non-COD (' + countNonCod + ')');
            } else {
                $('#btnSubmitFilteredBatch').hide();
            }
        } else {
            $('#btnFilterAll').addClass('active active-filter-all');
            $('#batchFilterDescription').html('<i class="fa-solid fa-boxes-stacked text-secondary me-1"></i>Menampilkan semua <strong>' + countAll + ' resi</strong> (' + countNonCod + ' Non-COD, ' + countCod + ' COD).');
            $('#btnSubmitFilteredBatch').hide();
        }

        const displayItems = allItems.filter(it => {
            if (filter === 'cod') return it.is_cod;
            if (filter === 'non_cod') return !it.is_cod;
            return true;
        });

        if (displayItems.length === 0) {
            tbody.html(`<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada resi dalam kategori <strong>${filter.toUpperCase()}</strong>.</td></tr>`);
            return;
        }

        displayItems.forEach((item) => {
            const idx = allItems.indexOf(item);
            const channel = (item.source || 'shopee').toLowerCase();
            const channelBadgeClass = 'channel-badge-' + channel;
            if (!item.tracking_number && window.lastDetectedBarcode && allItems.length === 1) {
                item.tracking_number = window.lastDetectedBarcode;
            }
            const courier = (item.courier && item.courier !== 'UNKNOWN') ? item.courier : inferCourierFromTracking(item.tracking_number, 'SPX Express');
            const resi = item.tracking_number || '-';
            const noPesanan = item.no_pesanan || '-';
            const penerima = item.recipient_name || 'Pelanggan Resi';
            const telepon = (item.recipient_phone || '').trim();
            const kota = item.city || 'Kota';
            const senderName = item.sender_name || '-';
            const senderCity = item.sender_city || '';
            const isCod = Boolean(item.is_cod);
            const rowClass = isCod ? 'row-is-cod' : 'row-is-non-cod';

            // Compute total for Non-COD from master data
            let total = parseFloat(item.total || 0);
            if (!isCod) {
                let computed = 0;
                if (item.items && item.items.length > 0) {
                    item.items.forEach(it => {
                        const matched = findMasterProduct(it.sku, it.nama_produk || it.nama);
                        const pr = matched || masterProductsMap[it.sku];
                        const h = pr ? parseFloat(pr.harga_jual) : (parseFloat(it.harga_satuan) || 0);
                        computed += h * (parseInt(it.qty) || 1);
                    });
                } else {
                    const matched = findMasterProduct(item.sku, item.product_name || item.nama_produk);
                    const pr = matched || masterProductsMap[item.sku];
                    if (pr) {
                        computed = parseFloat(pr.harga_jual) * (parseInt(item.qty) || 1);
                    }
                }
                if (computed > 0) {
                    total = computed;
                    item.total = total;
                }
            }
            if (total <= 0) total = 0;

            // Multi-product display
            let productHtml = '';
            if (item.items && item.items.length > 1) {
                item.items.forEach(it => {
                    const matched = findMasterProduct(it.sku, it.nama_produk || it.nama);
                    if (matched) {
                        if (!it.sku || it.sku === '-') it.sku = matched.kode_produk;
                        if (!it.nama_produk) it.nama_produk = matched.nama_produk;
                    }
                });
                productHtml = `
                    <div class="mb-1">
                        ${item.items.map((it, i) => `
                            <div class="font-monospace small text-dark fw-semibold text-truncate" style="max-width: 220px;" title="${$('<div>').text((it.nama_produk || it.nama || '') + ' (' + (it.variasi || '-') + ')').html()}">
                                ${i + 1}. [${$('<div>').text(it.sku || 'Auto').html()}] ${$('<div>').text(it.nama_produk || it.nama || '-').html()} x ${it.qty || 1}
                            </div>
                        `).join('')}
                    </div>
                    <span class="badge bg-info-subtle text-info border" style="font-size: 0.68rem;">
                        <i class="fa-solid fa-layer-group me-1"></i>${item.items.length} Macam Produk
                    </span>
                `;
            } else {
                let sku = item.sku || (item.items && item.items[0] && item.items[0].sku) || '';
                let prodName = item.product_name || item.nama_produk || (item.items && item.items[0] && item.items[0].nama_produk) || '';
                let variasi = item.variasi || (item.items && item.items[0] && item.items[0].variasi) || '';
                const qty = item.qty || (item.items && item.items[0] && item.items[0].qty) || 1;

                if (sku === '-') sku = '';
                if (prodName === '-') prodName = '';
                if (variasi === '-') variasi = '';

                // Intelligent Master Product auto-matching!
                const matched = findMasterProduct(sku, prodName);
                if (matched) {
                    if (!sku) sku = matched.kode_produk;
                    if (!prodName) prodName = matched.nama_produk;
                    item.sku = sku;
                    item.product_name = prodName;
                    if (!item.items || item.items.length === 0) {
                        item.items = [{
                            nama_produk: prodName,
                            sku: sku,
                            variasi: variasi,
                            qty: qty,
                            harga_satuan: isCod ? item.total : (parseFloat(matched.harga_jual) || 0)
                        }];
                    } else if (item.items.length === 1) {
                        item.items[0].sku = sku;
                        item.items[0].nama_produk = prodName;
                    }
                }

                let nameDisplay = '';
                if (prodName) {
                    nameDisplay = `<div class="fw-semibold text-dark text-truncate" style="max-width: 200px; font-size: 0.78rem;" title="${$('<div>').text(prodName).html()}">${$('<div>').text(prodName).html()}</div>`;
                } else if (!sku) {
                    nameDisplay = `<div class="text-danger small" style="font-size: 0.72rem;"><i class="fa-solid fa-triangle-exclamation me-1"></i>Nama belum terdeteksi (klik Detail untuk ubah)</div>`;
                }

                const skuBadge = sku ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fw-bold" style="font-size: 0.72rem;">${$('<div>').text(sku).html()}</span>` : '';
                const varText = variasi ? variasi : '-';

                productHtml = `
                    ${nameDisplay}
                    <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                        ${skuBadge}
                        <small class="badge bg-light text-secondary border">Var: ${$('<div>').text(varText).html()} | Qty: ${qty}</small>
                    </div>
                `;
            }

            const paymentBadge = isCod 
                ? '<span class="badge bg-danger text-white border border-danger px-2 py-1 shadow-xs fw-bold" style="font-size: 0.72rem;"><i class="fa-solid fa-hand-holding-dollar me-1"></i>COD</span>'
                : '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold" style="font-size: 0.72rem;"><i class="fa-solid fa-credit-card me-1"></i>NON-COD</span>';

            const row = `
                <tr id="batch-row-${idx}" class="${rowClass}">
                    <td class="text-center font-monospace small text-muted">${idx + 1}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <span class="badge ${channelBadgeClass} text-uppercase px-2 py-1" style="font-size: 0.72rem;">${channel}</span>
                            ${paymentBadge}
                        </div>
                        <div class="fw-semibold text-dark small">${$('<div>').text(courier).html()}</div>
                    </td>
                    <td>
                        <div class="font-monospace fw-bold text-primary">${$('<div>').text(resi).html()}</div>
                        <div class="font-monospace text-secondary small text-truncate" style="max-width: 130px;" title="${$('<div>').text(noPesanan).html()}">${$('<div>').text(noPesanan).html()}</div>
                        ${item.tanggal ? `<div class="small text-muted mt-1" style="font-size: 0.7rem;"><i class="fa-regular fa-calendar-check me-1 text-primary"></i>${$('<div>').text(item.tanggal).html()}</div>` : ''}
                    </td>
                    <td>
                        <div class="fw-bold text-dark">${$('<div>').text(penerima).html()}</div>
                        ${telepon ? `<div class="text-success font-monospace small"><i class="fa-brands fa-whatsapp"></i> ${$('<div>').text(telepon).html()}</div>` : `<div class="text-muted font-monospace small"><span class="badge bg-light text-muted border py-0"><i class="fa-solid fa-phone-slash me-1"></i>Tanpa No. HP</span></div>`}
                        <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.7rem;">${$('<div>').text(kota).html()}</span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark small"><i class="fa-solid fa-store me-1 text-secondary"></i>${$('<div>').text(senderName).html()}</div>
                        ${senderCity ? `<small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>${$('<div>').text(senderCity).html()}</small>` : ''}
                    </td>
                    <td>
                        ${productHtml}
                    </td>
                    <td class="text-end font-monospace">
                        <div class="fw-bold fs-6 ${isCod ? 'text-danger' : 'text-primary'}">Rp ${total.toLocaleString('id-ID')}</div>
                        ${isCod 
                            ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1 py-0 mt-1" style="font-size: 0.68rem;"><i class="fa-solid fa-hand-holding-dollar me-1"></i>Kurir Tagih Tunai</span>' 
                            : '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1 py-0 mt-1" style="font-size: 0.68rem;"><i class="fa-solid fa-credit-card me-1"></i>Lunas Online (Master)</span>'}
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary btn-load-row" data-index="${idx}" title="Edit / Lihat di Form Satuan">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-delete-row" data-index="${idx}" title="Hapus dari Batch">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Filter Buttons & Summary Cards in Batch View
    $('#batchNavTabs button, #cardBatchNonCod, #cardBatchCod').on('click', function() {
        const filter = $(this).data('filter');
        window.currentBatchFilter = filter;
        renderBatchTable(window.currentBatchItems);
    });

    // Add Product Row in Single Form
    $('#btnAddProductRow').on('click', function() {
        const tbody = $('#bodySingleProducts');
        const nextIdx = tbody.find('tr.product-item-row').length;
        const row = `
            <tr class="product-item-row" data-index="${nextIdx}">
                <td class="text-center font-monospace text-muted small">${nextIdx + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm item-nama bg-white" value="" placeholder="Nama produk di resi">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm font-monospace item-resi-sku fw-semibold text-primary" value="" placeholder="SKU di label">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-variasi bg-white" value="" placeholder="Variasi (misal: 20)">
                </td>
                <td>
                    <input type="number" min="1" step="1" class="form-control form-control-sm text-center font-monospace fw-bold item-qty" value="1">
                </td>
                <td>
                    <select class="form-select form-select-sm font-monospace item-sku">
                        ${generateProductOptionsHtml('')}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm text-end font-monospace item-harga" value="" placeholder="0">
                </td>
                <td class="text-end font-monospace fw-bold text-dark item-subtotal-text">
                    Rp 0
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item-row py-0 px-2" title="Hapus"><i class="fa-solid fa-times"></i></button>
                </td>
            </tr>
        `;
        tbody.append(row);
        recalculateSingleTotals();
    });

    // Remove Product Row
    $(document).on('click', '.btn-remove-item-row', function() {
        const rows = $('#bodySingleProducts tr.product-item-row');
        if (rows.length > 1) {
            $(this).closest('tr').remove();
            recalculateSingleTotals();
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Minimal 1 Produk',
                text: 'Pesanan harus memiliki minimal 1 baris produk.'
            });
        }
    });

    // Change SKU in product table
    $(document).on('change', '.item-sku', function() {
        const tr = $(this).closest('tr');
        const selectedSku = $(this).val();
        if (selectedSku && masterProductsMap[selectedSku]) {
            const h = parseFloat(masterProductsMap[selectedSku].harga_jual) || 0;
            tr.find('.item-harga').val(h);
            if (!tr.find('.item-nama').val()) {
                tr.find('.item-nama').val(masterProductsMap[selectedSku].nama_produk);
            }
        }
        recalculateSingleTotals();
    });

    // Input on item fields
    $(document).on('input', '.item-qty, .item-harga, .item-resi-sku, .item-variasi, .item-nama', function() {
        recalculateSingleTotals();
    });

    // Toggle expand table view (12 columns full width)
    let isExpanded = false;
    $('#btnToggleExpandBatch').on('click', function() {
        isExpanded = !isExpanded;
        if (isExpanded) {
            $('#leftUploadCol').removeClass('col-xl-4 col-lg-5').addClass('d-none');
            $('#rightContentCol').removeClass('col-xl-8 col-lg-7').addClass('col-12');
            $('#textExpandBtn').text('Kecilkan');
            $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
        } else {
            $('#leftUploadCol').removeClass('d-none').addClass('col-xl-4 col-lg-5');
            $('#rightContentCol').removeClass('col-12').addClass('col-xl-8 col-lg-7');
            $('#textExpandBtn').text('Lebarkan');
            $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
        }
    });

    // Switch between batch view and single form view
    $('#btnSwitchToSingleForm').on('click', function() {
        if (window.currentBatchItems && window.currentBatchItems.length > 0) {
            showSingleView(window.currentBatchItems[0]);
        } else {
            $('#wrapperBatchResi').slideUp();
            $('#wrapperSingleResi').slideDown();
        }
    });

    $('#btnBackToBatchView').on('click', function() {
        if (window.currentEditingBatchIndex !== null && window.currentBatchItems && window.currentBatchItems[window.currentEditingBatchIndex]) {
            const item = window.currentBatchItems[window.currentEditingBatchIndex];
            item.recipient_name = $('#inputNamaPenerima').val();
            item.recipient_phone = $('#inputTelepon').val();
            item.recipient_address = $('#inputAlamat').val();
            item.city = $('#inputKota').val();
            item.tanggal = $('#inputTanggal').val();
            item.is_cod = $('#inputIsCod').val() === '1';
            item.total = parseFloat($('#inputTotal').val()) || item.total;
            const itemsJson = $('#inputItemsJson').val();
            if (itemsJson) {
                try {
                    item.items = JSON.parse(itemsJson);
                    item.qty = parseInt($('#textTotalItemQty').text()) || item.qty;
                } catch (e) {}
            }
        }
        $('#wrapperSingleResi').slideUp();
        $('#wrapperBatchResi').slideDown();
        renderBatchTable(window.currentBatchItems);
    });

    // Load row into single form
    $(document).on('click', '.btn-load-row', function() {
        const idx = parseInt($(this).data('index'));
        if (window.currentBatchItems && window.currentBatchItems[idx]) {
            window.currentEditingBatchIndex = idx;
            showSingleView(window.currentBatchItems[idx]);
        }
    });

    // Delete row from batch
    $(document).on('click', '.btn-delete-row', function() {
        const idx = parseInt($(this).data('index'));
        if (window.currentBatchItems && window.currentBatchItems[idx]) {
            window.currentBatchItems.splice(idx, 1);
            renderBatchTable(window.currentBatchItems);
            if (window.currentBatchItems.length === 0) {
                $('#wrapperBatchResi').slideUp();
                $('#wrapperSingleResi').slideDown();
            }
        }
    });

    // Apply Global SKU to all batch items
    $('#btnApplyGlobalSku').on('click', function() {
        const selectedSku = $('#batchGlobalSku').val();
        const globalQty = parseInt($('#batchGlobalQty').val()) || 1;
        if (!selectedSku) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih SKU Produk',
                text: 'Silakan pilih SKU produk dari dropdown sebelum menerapkan ke seluruh resi.'
            });
            return;
        }

        const opt = $(`#batchGlobalSku option[value="${selectedSku}"]`);
        const harga = parseFloat(opt.data('harga')) || 0;

        if (window.currentBatchItems && window.currentBatchItems.length > 0) {
            window.currentBatchItems.forEach(item => {
                item.sku = selectedSku;
                item.qty = globalQty;
                if (harga > 0) {
                    item.total = harga * globalQty;
                }
            });
            renderBatchTable(window.currentBatchItems);
            Swal.fire({
                icon: 'success',
                title: 'SKU Berhasil Diterapkan',
                text: `SKU [${selectedSku}] berhasil diset ke seluruh ${window.currentBatchItems.length} resi pesanan!`,
                timer: 1500,
                showConfirmButton: false
            });
        }
    });

    // Shared Function to Save Batch Resi (Semua, atau Khusus COD, atau Khusus Non-COD)
    function executeBatchSave(itemsToSave, categoryLabel) {
        if (!itemsToSave || itemsToSave.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Daftar Resi Kosong',
                text: 'Tidak ada resi dalam kategori ini untuk disimpan ke database.'
            });
            return;
        }

        const count = itemsToSave.length;
        const totalCount = window.currentBatchItems ? window.currentBatchItems.length : count;
        const isPartial = count < totalCount;

        Swal.fire({
            title: `Simpan ${count} Resi ${categoryLabel}?`,
            html: `Sistem akan otomatis merekap & mengintegrasikan:<br>
                   <ul class="text-start small mt-2 mb-0">
                       <li>Menerbitkan <strong>${count} Sales Order (SO)</strong> di modul ERP Penjualan</li>
                       <li>Menerbitkan <strong>${count} Faktur Penjualan (Invoice Lunas)</strong> di modul Invoice</li>
                       <li>Mencatat <strong>${count} Jurnal Akuntansi</strong> di Buku Jurnal Umum (Debit Piutang 1140, Kredit Pendapatan 4110)</li>
                       <li>Memotong <strong>stok fisik SKU</strong> di modul Manajemen Stok</li>
                       <li>Menjadwalkan <strong>${count * 3} Follow-up CRM WhatsApp</strong> (H+0 resi, H+3 kedatangan, H+7 review)</li>
                       <li>Mengkalkulasi <strong>skor & segmen RFM</strong> pelanggan</li>
                       <li>Memperbarui <strong>Statistik Marketplace</strong> & Pusat Notifikasi sistem</li>
                   </ul>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2D6A4F',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="fa-solid fa-rocket me-1"></i> Ya, Simpan (${count} Resi)`,
            cancelButtonText: 'Periksa Ulang'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan & Merekap ke Seluruh Sistem...',
                    text: 'Sedang memproses seluruh resi ke ERP Penjualan, Faktur Invoice, Jurnal Akuntansi, Stok Fisik, CRM & RFM...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?= base_url('marketplace/save-batch-resi') ?>',
                    type: 'POST',
                    data: {
                        items: JSON.stringify(itemsToSave),
                        <?= csrf_token() ?>: typeof getCsrfCookie === 'function' ? getCsrfCookie() : $('meta[name="csrf-token-hash"]').attr('content')
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(resp) {
                        if (resp && resp.status === 'success') {
                            if (isPartial) {
                                // Remove saved items from current batch
                                const savedSet = new Set((resp.saved_resi || []).map(r => String(r).toUpperCase()));
                                window.currentBatchItems = (window.currentBatchItems || []).filter(item => {
                                    const r = String(item.tracking_number || item.nomor_resi || item.no_pesanan || '').toUpperCase();
                                    return !savedSet.has(r);
                                });
                                renderBatchTable(window.currentBatchItems);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Kategori Berhasil Disimpan!',
                                    html: `Berhasil merekap <strong>${resp.success_count} pesanan ${categoryLabel}</strong>.<br>Sisa <strong>${window.currentBatchItems.length} resi</strong> lainnya masih tersedia di tabel batch untuk diproses berikutnya.`,
                                    confirmButtonColor: '#2D6A4F',
                                    confirmButtonText: 'Lanjutkan Sisa Resi'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Integrasi Berhasil!',
                                    html: `Berhasil merekap <strong>${resp.success_count} pesanan marketplace</strong> ke seluruh modul: Sales Order, Faktur Invoice, Buku Jurnal, Stok Fisik, CRM WhatsApp, Segmentasi RFM & Pusat Notifikasi!`,
                                    confirmButtonColor: '#2D6A4F',
                                    confirmButtonText: '<i class="fa-solid fa-table-list me-1"></i> Buka Riwayat Pesanan',
                                    showCancelButton: true,
                                    cancelButtonText: 'Pindai Berkas Lain'
                                }).then((r) => {
                                    if (r.isConfirmed) {
                                        window.location.href = '<?= base_url('marketplace') ?>';
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: resp.message || 'Terjadi kesalahan saat memproses data resi.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal menyimpan batch resi ke server.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errMsg
                        });
                    }
                });
            }
        });
    }

    // Submit All in Batch
    $('#btnSubmitBatchResi').on('click', function() {
        executeBatchSave(window.currentBatchItems, 'Semua');
    });

    // Submit Filtered in Batch (Hanya COD atau Hanya Non-COD)
    $('#btnSubmitFilteredBatch').on('click', function() {
        const filter = window.currentBatchFilter;
        const allItems = window.currentBatchItems || [];
        const filtered = allItems.filter(it => {
            if (filter === 'cod') return it.is_cod;
            if (filter === 'non_cod') return !it.is_cod;
            return true;
        });
        const label = (filter === 'cod') ? 'COD' : 'Non-COD';
        executeBatchSave(filtered, label);
    });

    // Initial render if batch already loaded from backend
    if (window.currentBatchItems && window.currentBatchItems.length > 1) {
        renderBatchTable(window.currentBatchItems);
    }

    // Manual re-parse button
    $('#btnParseTextManual').on('click', function() {
        const text = rawTextArea.val();
        if (!text.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Teks Kosong',
                text: 'Silakan masukkan atau tempel teks resi terlebih dahulu.'
            });
            return;
        }
        parseTextOnServer(text);
    });

    // Form submission hook to ensure items JSON is fresh
    $('#formSaveResi').on('submit', function() {
        recalculateSingleTotals();
    });

    // Initial population for single view if parsed data exists
    <?php if (!$isInitialBatch && !empty($parsed)): ?>
    populateFormFields(<?= json_encode($parsed, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>);
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
