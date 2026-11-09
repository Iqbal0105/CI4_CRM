<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
@keyframes rfmTrackingFade {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

.rfm-track-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  margin-bottom: 24px;
  overflow: hidden;
  animation: rfmTrackingFade 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.rfm-stat-box {
  background: #ffffff;
  border-radius: 12px;
  padding: 18px 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  margin-bottom: 0;
  transition: all 0.25s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.rfm-stat-box:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.rfm-stat-box .title-row {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.82rem;
  font-weight: 700;
  color: #475569;
  margin-bottom: 6px;
}
.rfm-stat-box .number-val {
  font-size: clamp(1.35rem, 3.8vw, 2.1rem);
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 4px;
}
.rfm-stat-box .sub-txt {
  font-size: clamp(0.68rem, 1.8vw, 0.78rem);
  color: #94a3b8;
  font-weight: 500;
  line-height: 1.25;
}

.table-tracking th {
  background-color: #f8fafc;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  color: #475569;
  font-weight: 700;
  vertical-align: middle;
  border-color: #e2e8f0;
}
.table-tracking td {
  font-size: 0.84rem;
  vertical-align: middle;
  border-color: #f1f5f9;
}

.delta-down {
  color: #dc2626;
  font-size: 0.72rem;
  font-weight: 600;
  display: block;
}
.delta-up {
  color: #16a34a;
  font-size: 0.72rem;
  font-weight: 600;
  display: block;
}

.cursor-pointer {
  cursor: pointer;
}

/* Horizontal scroll for sub-navigation tabs on mobile */
.crm-subnav-scroll {
  overflow-x: auto;
  flex-wrap: nowrap !important;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  -ms-overflow-style: none;
  padding-bottom: 2px;
}
.crm-subnav-scroll::-webkit-scrollbar {
  display: none;
}
.crm-subnav-scroll .btn {
  white-space: nowrap;
  flex-shrink: 0;
}

/* Sticky column for wide RFM tracking table on horizontal mobile swipe */
.table-tracking .sticky-col {
  position: sticky;
  left: 0;
  z-index: 2;
  background-color: #ffffff;
  box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
}
.table-tracking thead th.sticky-col {
  z-index: 3;
  background-color: #f8fafc !important;
}

@media (max-width: 767.98px) {
  .crm-top-banner {
    padding: 1rem !important;
  }
  .crm-header-actions {
    width: 100% !important;
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
    margin-top: 0.5rem !important;
  }
  .crm-header-actions .btn {
    flex: 1 1 auto !important;
    min-width: 0 !important;
    padding: 0.4rem 0.6rem !important;
    font-size: 0.78rem !important;
    text-align: center !important;
    justify-content: center !important;
  }
  .rfm-stat-box {
    padding: 12px 10px !important;
    border-radius: 10px !important;
  }
  .rfm-stat-box .title-row {
    font-size: 0.72rem !important;
    gap: 4px !important;
    margin-bottom: 3px !important;
    line-height: 1.25 !important;
  }
  .rfm-stat-box .number-val {
    font-size: 1.35rem !important;
    margin-bottom: 2px !important;
  }
  .rfm-stat-box .sub-txt {
    font-size: 0.65rem !important;
    line-height: 1.2 !important;
  }
  .chart-trend-container {
    height: 260px !important;
  }
  .table-tracking th, .table-tracking td {
    padding: 6px 8px !important;
    font-size: 0.76rem !important;
  }
}
</style>

<!-- TOP BANNER RFM (WARNA SISTEM HIJAU) -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4 crm-top-banner">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1 me-md-3">
                <div class="small text-white-50"><i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; Tracking RFM</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-chart-line me-2"></i>RFM Tracking Harian
                </h3>
                <p class="text-white-50 small mb-0">Ringkasan segmentasi Recency (Kebaruan), Frequency (Frekuensi), dan Monetary (Nominal Belanja) pergerakan 10 segmen harian</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap">
                    <i class="fa-brands fa-whatsapp"></i> <span>Broadcast WA</span>
                </a>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalImportRfmExcel">
                    <i class="fa-solid fa-file-excel"></i> <span>Import Excel Data</span>
                </button>
                <a href="<?= base_url('rfm/customer/export') ?>" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-1 shadow-sm px-2.5 px-md-3 flex-fill flex-md-grow-0 text-nowrap" style="background-color: #6366f1;">
                    <i class="fa-solid fa-file-csv"></i> <span>Export CSV</span>
                </a>
            </div>
        </div>

        <!-- 6 SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('rfm') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard RFM
            </a>
            <a href="<?= base_url('rfm/customer') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                2. Daftar Customer
            </a>
            <a href="<?= base_url('rfm/tracking') ?>" class="btn btn-sm bg-white text-success rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 3. Tracking RFM
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

<!-- TRACKING FILTER BAR (FUNGSIONAL) -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <div class="p-2 rounded bg-success bg-opacity-10 text-success">
            <i class="fa-solid fa-filter fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-dark">Filter Pelacakan &amp; Pergerakan Skor RFM</h6>
            <div class="text-muted small" style="font-size: 0.78rem;">Pantau pergeseran segmen pelanggan antar periode transaksi harian</div>
        </div>
    </div>
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="<?= base_url('rfm/tracking') ?>" class="row g-2 align-items-end">
            <input type="hidden" name="filter_applied" value="1">
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Tanggal Awal <span class="text-danger">*</span></label>
                <input type="date" class="form-control form-control-sm" name="tgl_awal" value="<?= esc($tglAwal) ?>" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Tanggal Akhir <span class="text-danger">*</span></label>
                <input type="date" class="form-control form-control-sm" name="tgl_akhir" value="<?= esc($tglAkhir) ?>" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Kategori</label>
                <select class="form-select form-select-sm" name="kategori_id">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($kategoriId == $cat['id']) ? 'selected' : '' ?>>
                            <?= esc($cat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Produk</label>
                <select class="form-select form-select-sm" name="produk_id">
                    <option value="">Semua Produk</option>
                    <?php foreach ($products as $prd): ?>
                        <option value="<?= $prd['id'] ?>" <?= ($produkId == $prd['id']) ? 'selected' : '' ?>>
                            <?= esc($prd['nama_produk']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Status RFM</label>
                <select class="form-select form-select-sm" name="segment">
                    <option value="">Semua Status RFM</option>
                    <?php foreach ($segmentsList as $segOpt): ?>
                        <option value="<?= $segOpt ?>" <?= ($statusRfm === $segOpt) ? 'selected' : '' ?>>
                            <?= $segOpt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm w-100 fw-semibold text-white shadow-sm d-flex align-items-center justify-content-center gap-1" style="background-color: #2D6A4F;" title="Filter Data Tracking">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('rfm/tracking') ?>" class="btn btn-sm btn-light border text-secondary d-flex align-items-center justify-content-center px-2" title="Reset Filter">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($isFiltered)): ?>
<!-- KOMPONEN 1: 4 STAT CARDS (PERSIS SESUAI FOTO 1) -->
<div class="row g-2 g-sm-3 mb-4">
    <!-- Card 1: Data Awal (Saldo Awal) -->
    <div class="col-6 col-lg-3">
        <div class="rfm-stat-box" style="border-left: 4px solid #64748b !important;">
            <div class="title-row">
                <i class="fa-solid fa-clock-rotate-left text-secondary"></i>
                <span class="text-truncate">Data Awal (Saldo)</span>
            </div>
            <div class="number-val text-dark">
                <?= (int)$dataAwalSaldo ?>
            </div>
            <div class="sub-txt">
                Pelanggan &lt; <?= date('d/m/Y', strtotime($tglAwal)) ?>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Customer Terdaftar -->
    <div class="col-6 col-lg-3">
        <div class="rfm-stat-box" style="border-left: 4px solid #0284c7 !important;">
            <div class="title-row">
                <i class="fa-solid fa-users" style="color: #0284c7;"></i>
                <span class="text-truncate">Total Customer</span>
            </div>
            <div class="number-val" style="color: #0284c7;">
                <?= (int)$totalCustomer ?>
            </div>
            <div class="sub-txt">
                Customer aktif database
            </div>
        </div>
    </div>

    <!-- Card 3: Total Transaksi -->
    <div class="col-6 col-lg-3">
        <div class="rfm-stat-box" style="border-left: 4px solid #10b981 !important;">
            <div class="title-row">
                <i class="fa-solid fa-cart-shopping" style="color: #10b981;"></i>
                <span class="text-truncate">Total Transaksi</span>
            </div>
            <div class="number-val" style="color: #10b981;">
                <?= (int)$totalTransaksi ?>
            </div>
            <div class="sub-txt">
                Item pesanan teranalisis
            </div>
        </div>
    </div>

    <!-- Card 4: Total Baris Harian -->
    <div class="col-6 col-lg-3">
        <div class="rfm-stat-box" style="border-left: 4px solid #8b5cf6 !important;">
            <div class="title-row">
                <i class="fa-regular fa-calendar-check" style="color: #8b5cf6;"></i>
                <span class="text-truncate">Total Baris Hari</span>
            </div>
            <div class="number-val" style="color: #8b5cf6;">
                <?= (int)$totalBarisHarian ?>
            </div>
            <div class="sub-txt">
                Hari riwayat tracking
            </div>
        </div>
    </div>
</div>

<!-- KOMPONEN 2: GRAFIK TREN RFM TRACKING HARIAN (PERSIS SESUAI FOTO 2) -->
<div class="rfm-track-card mb-4">
    <div class="p-3 px-3 px-md-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #1A2E23;">
            <i class="fa-solid fa-chart-line text-primary"></i> Grafik Tren RFM Tracking Harian
        </h6>
        <div class="text-muted small" style="font-size: 0.76rem;">
            <i class="fa-solid fa-circle-info text-primary me-1"></i> Klik label segmen pada legenda untuk menyembunyikan / menampilkan garis tren
        </div>
    </div>
    <div class="p-3 p-md-4 bg-white">
        <div class="chart-trend-container" style="position: relative; width: 100%; height: 320px;">
            <canvas id="chartRfmTrend"></canvas>
        </div>
    </div>
</div>

<!-- KOMPONEN 3: TABEL RFM TRACKING BERISI 10 SEGMEN DAN HARI (PERSIS SESUAI FOTO 3) -->
<div class="rfm-track-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-tracking align-middle text-center mb-0">
            <thead>
                <tr>
                    <th rowspan="2" class="text-start ps-3 sticky-col" style="min-width: 145px; background-color: #f8fafc;">
                        Hari, Tanggal 
                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info fw-normal ms-1 px-2 py-0" style="font-size: 10px;">Terbaru</span>
                        <i class="fa-solid fa-sort ms-1 opacity-50"></i>
                    </th>
                    <th rowspan="2" style="min-width: 90px; background-color: #f8fafc;">
                        Data Awal <i class="fa-solid fa-sort ms-1 opacity-50"></i>
                    </th>
                    <th colspan="10" class="text-center py-2" style="background-color: #f1f5f9; letter-spacing: 1px;">
                        RFM Tracking
                    </th>
                </tr>
                <tr>
                    <?php foreach ($segmentsList as $seg): ?>
                        <th style="min-width: 95px; font-size: 0.74rem;">
                            <?= $seg ?> <i class="fa-solid fa-sort opacity-50" style="font-size: 9px;"></i>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tableRows)): ?>
                    <tr>
                        <td colspan="12" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-calendar-xmark fa-2x mb-2 d-block opacity-50"></i>
                            Tidak ada data tracking untuk rentang tanggal yang dipilih.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tableRows as $row): ?>
                        <tr>
                            <td class="text-start ps-3 fw-semibold text-dark sticky-col">
                                <?= esc($row['formatted']) ?>
                            </td>
                            <td class="fw-bold" style="color: #0284c7;">
                                <?= (int)$row['data_awal'] ?>
                            </td>
                            <?php foreach ($segmentsList as $seg): ?>
                                <?php 
                                    $val = $row['segments'][$seg] ?? 0;
                                    $delta = $row['deltas'][$seg] ?? '';
                                ?>
                                <td>
                                    <?php if ($val > 0): ?>
                                        <span class="fw-bold" style="color: #0284c7;"><?= $val ?></span>
                                        <?php if (!empty($delta)): ?>
                                            <?php if (str_starts_with($delta, '(↓')): ?>
                                                <span class="delta-down"><?= $delta ?></span>
                                            <?php else: ?>
                                                <span class="delta-up"><?= $delta ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted opacity-50">0</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- KOMPONEN 4: TABEL RINGKASAN PERFORMA & REKAP BULANAN HISTORIS (PERSIS SESUAI FOTO 4) -->
<div class="rfm-track-card mb-4">
    <div class="p-3 px-3 px-md-4 border-bottom bg-white d-flex align-items-center justify-content-between cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseRekapBulanan">
        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #1A2E23;">
            <i class="fa-solid fa-chart-column text-primary"></i> Ringkasan Performa &amp; Rekap Bulanan Historis
        </h6>
        <i class="fa-solid fa-chevron-down text-muted"></i>
    </div>
    <div class="collapse show" id="collapseRekapBulanan">
        <div class="table-responsive">
            <table class="table table-hover table-tracking align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 ps-md-4 text-nowrap">Periode Bulan</th>
                        <th class="text-nowrap">Produk</th>
                        <th class="text-center text-nowrap">Pembeli Unik</th>
                        <th class="text-center text-nowrap">Total Transaksi</th>
                        <th class="text-end text-nowrap">Total Omset (Rp)</th>
                        <th class="pe-3 pe-md-4 text-end text-nowrap">Rata-rata Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekapBulanan)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada rekap data bulanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rekapBulanan as $mb): ?>
                            <tr>
                                <td class="ps-3 ps-md-4 fw-bold text-dark text-nowrap">
                                    <?= esc($mb['periode_bulan']) ?>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-light text-secondary border px-2 px-md-3 py-1 fw-normal" style="border-radius: 6px;">
                                        <?= esc($mb['produk']) ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold" style="color: #0284c7;">
                                    <?= (int)$mb['pembeli_unik'] ?>
                                </td>
                                <td class="text-center text-dark fw-medium">
                                    <?= (int)$mb['total_transaksi'] ?>
                                </td>
                                <td class="text-end fw-bold font-monospace text-nowrap" style="color: #10b981;">
                                    Rp <?= number_format($mb['total_omset'], 0, ',', '.') ?>
                                </td>
                                <td class="pe-3 pe-md-4 text-end font-monospace text-secondary fw-semibold text-nowrap">
                                    Rp <?= number_format($mb['rata_rata_order'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KOMPONEN 5: TABEL RINGKASAN FOLLOW-UP HARIAN (H+0 s/d MASUK GRUP) (PERSIS SESUAI FOTO 5) -->
<div class="rfm-track-card mb-4">
    <div class="p-3 px-3 px-md-4 border-bottom bg-white d-flex align-items-center justify-content-between cursor-pointer flex-wrap gap-2" data-bs-toggle="collapse" data-bs-target="#collapseFollowUpHarian">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <i class="fa-brands fa-whatsapp text-success fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">
                Ringkasan Follow-Up Harian (H+0 s/d Masuk Grup)
            </h6>
            <span class="badge rounded-pill bg-success px-2.5 px-md-3 py-1 fw-semibold text-white ms-0 ms-sm-2" style="font-size: 11px;">
                <?= (int)$fuSummaryTotals['total_terkirim'] ?> Terkirim
            </span>
        </div>
        <i class="fa-solid fa-chevron-down text-muted ms-auto"></i>
    </div>
    <div class="collapse show" id="collapseFollowUpHarian">
        <div class="table-responsive">
            <table class="table table-hover table-tracking align-middle text-center mb-0">
                <thead>
                    <tr>
                        <th class="text-start ps-3 ps-md-4 text-nowrap" style="min-width: 150px;">Hari, Tanggal</th>
                        <th class="text-nowrap">Total Terkirim</th>
                        <th class="text-nowrap">H+0</th>
                        <th class="text-nowrap">H+3</th>
                        <th class="text-nowrap">H+7</th>
                        <th class="text-nowrap">H+11</th>
                        <th class="pe-3 pe-md-4 text-nowrap">H+30</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekapFollowUp)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada aktivitas pengiriman follow-up harian.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rekapFollowUp as $fu): ?>
                            <tr>
                                <td class="text-start ps-3 ps-md-4 fw-semibold text-dark text-nowrap">
                                    <i class="fa-regular fa-calendar-check text-primary me-2"></i><?= esc($fu['tanggal_formatted']) ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1 fw-bold" style="background-color: #e0f2fe; color: #0284c7; font-size: 12px;">
                                        <?= (int)$fu['total_terkirim'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fu['h0'] > 0): ?>
                                        <span class="badge rounded-pill px-2 py-1 text-white fw-bold" style="background-color: #0284c7; min-width: 26px;">
                                            <?= (int)$fu['h0'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted opacity-50">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fu['h3'] > 0): ?>
                                        <span class="badge rounded-pill px-2 py-1 text-white fw-bold" style="background-color: #0284c7; min-width: 26px;">
                                            <?= (int)$fu['h3'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted opacity-50">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted opacity-50"><?= (int)$fu['h7'] ?></span>
                                </td>
                                <td>
                                    <span class="text-muted opacity-50"><?= (int)$fu['h11'] ?></span>
                                </td>
                                <td class="pe-3 pe-md-4">
                                    <span class="text-muted opacity-50"><?= (int)$fu['h30'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- ROW TOTAL KESELURUHAN -->
                        <tr style="background-color: #f8fafc; font-weight: 700;">
                            <td class="text-start ps-3 ps-md-4 text-dark text-nowrap">
                                <i class="fa-solid fa-calendar-days text-primary me-2"></i>Total Keseluruhan
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-1 text-white fw-bold" style="background-color: #0284c7; font-size: 12px;">
                                    <?= (int)$fuSummaryTotals['total_terkirim'] ?>
                                </span>
                            </td>
                            <td class="fw-bold text-dark">
                                <?= (int)$fuSummaryTotals['h0'] ?>
                            </td>
                            <td class="fw-bold text-dark">
                                <?= (int)$fuSummaryTotals['h3'] ?>
                            </td>
                            <td class="text-muted opacity-50">
                                <?= (int)$fuSummaryTotals['h7'] ?>
                            </td>
                            <td class="text-muted opacity-50">
                                <?= (int)$fuSummaryTotals['h11'] ?>
                            </td>
                            <td class="pe-3 pe-md-4 text-muted opacity-50">
                                <?= (int)$fuSummaryTotals['h30'] ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SCRIPT INISIALISASI CHART.JS UNTUK TREN RFM HARIAN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartRfmTrend');
    if (!ctx) return;

    const chartLabels = <?= json_encode($chartDates) ?>;
    const chartDatasets = <?= json_encode($chartDatasets) ?>;
    const isMobile = window.innerWidth < 768;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: chartDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: isMobile ? 8 : 12,
                        boxHeight: isMobile ? 8 : 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: isMobile ? 8 : 14,
                        font: {
                            size: isMobile ? 9 : 11,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(26, 46, 35, 0.95)',
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 10 },
                        color: '#64748b'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        stepSize: 5,
                        font: { size: 10 },
                        color: '#64748b'
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/modal_import_excel.php'; ?>

<?= $this->endSection() ?>
