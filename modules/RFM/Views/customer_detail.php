<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
@keyframes rfmFadeInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.rfm-detail-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  margin-bottom: 16px;
  overflow: hidden;
  animation: rfmFadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.rfm-stat-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 14px 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  text-align: center;
  margin-bottom: 0;
  transition: all 0.25s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.rfm-stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(45, 106, 79, 0.1);
  border-color: #2D6A4F;
}

.rfm-stat-card .val {
  font-size: 26px;
  font-weight: 800;
  letter-spacing: -0.5px;
  line-height: 1.2;
  font-family: var(--bs-font-monospace, monospace);
}

.rfm-stat-card .lbl {
  font-size: 11px;
  color: #64748b;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-top: 6px;
}

.badge-source-tag {
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  color: #fff;
  display: inline-block;
}

.table-rfm-detail th {
  background: #f8fafc;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #475569;
  font-weight: 700;
  white-space: nowrap;
  vertical-align: middle;
  padding: 12px 16px;
}
.table-rfm-detail td {
  font-size: 13px;
  vertical-align: middle;
  color: #334155;
  padding: 12px 16px;
}
.table-rfm-detail tbody tr {
  transition: background-color 0.15s ease;
}
.table-rfm-detail tbody tr:hover {
  background-color: rgba(45, 106, 79, 0.03);
}
</style>

<?php
  $disp_phone = $summary['phone'] ?? '-';
  $phone_clean = preg_replace('/[^0-9]/', '', $disp_phone);
  $has_valid_phone = (!empty($phone_clean) && strlen($phone_clean) >= 8);
  $top_wa_url = '#';
  if ($has_valid_phone) {
      $top_phone = preg_replace('/^0/', '62', $phone_clean);
      if (substr($top_phone, 0, 2) !== '62') $top_phone = '62' . $top_phone;
      $act_text = !empty($summary['rfm']['action']) ? $summary['rfm']['action'] : 'Terima kasih telah berbelanja bersama kami.';
      $top_msg = "Halo Kak " . $summary['nama'] . ",\n\nSalam dari Metafora! " . $act_text . "\n\nAda yang bisa kami bantu hari ini?";
      $top_wa_url = 'https://api.whatsapp.com/send?phone=' . $top_phone . '&text=' . rawurlencode($top_msg);
  }
  $rfm = $summary['rfm'];
  $badge = $rfm['badge'];
?>

<!-- TOP BANNER DETAIL CUSTOMER RFM (WARNA SISTEM) -->
<div class="card border-0 text-white mb-3 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
  <div class="card-body p-3 p-md-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-2">
      <div class="flex-grow-1">
        <div class="small text-white-50">
          <i class="fa-solid fa-chart-column me-1"></i>Analisis RFM &gt; <a href="<?= base_url('rfm/customer') ?>" class="text-white-50 text-decoration-none">Daftar Customer</a> &gt; Detail Profil Pelanggan
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
          <div class="bg-white bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.8rem; border: 1px solid rgba(255,255,255,0.25);">
            <i class="fa-solid fa-circle-user"></i>
          </div>
          <div>
            <h3 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <?= esc($summary['nama']); ?>
              <span class="badge bg-white text-dark fw-bold font-monospace px-2 py-1" style="font-size: 11px; border-radius: 6px; color: #000000 !important; -webkit-text-fill-color: #000000 !important; background-color: #ffffff !important; border: 1px solid rgba(0,0,0,0.1);">
                <?= esc($customer['kode_pelanggan'] ?? ('CUST-' . str_pad((string)($summary['id'] ?? 0), 4, '0', STR_PAD_LEFT))) ?>
              </span>
            </h3>
            <div class="text-white-50 small d-flex align-items-center gap-2 flex-wrap mt-1">
              <span>
                <?php if (!empty($summary['phone']) && $summary['phone'] !== '-'): ?>
                  <i class="fa-solid fa-phone me-1"></i><?= esc($summary['phone']); ?>
                <?php else: ?>
                  <i class="fa-solid fa-phone-slash me-1 opacity-75"></i>(Tanpa No. HP)
                <?php endif; ?>
              </span>
              <?php foreach ($summary['sources'] as $src): ?>
                <?php 
                  $srcLower = strtolower($src);
                  $bgClass = match($srcLower) {
                      'shopee' => 'background-color: #ee4d2d;',
                      'tokopedia' => 'background-color: #03ac0e;',
                      'tiktok' => 'background-color: #000000;',
                      default => 'background-color: rgba(255,255,255,0.25);',
                  };
                ?>
                <span class="badge-source-tag" style="<?= $bgClass ?>">
                  <?= esc($src) ?>
                </span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex flex-wrap gap-2 ms-auto flex-shrink-0 align-self-end align-self-md-center text-nowrap">
        <a href="<?= base_url('rfm/customer'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 fw-semibold">
          <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <a href="<?= base_url('crm/customer_detail?id=' . $summary['id']); ?>" class="btn btn-sm bg-white text-dark rounded-pill px-3 py-1 fw-bold shadow-sm" title="Lihat Profil CRM Detail Pelanggan">
          <i class="fa-solid fa-address-card me-1 text-primary"></i> CRM Detail
        </a>
        <?php if ($has_valid_phone): ?>
          <a href="<?= $top_wa_url; ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm" style="background-color: #25D366; border-color: #25D366;" title="Kirim Pesan WhatsApp Langsung">
            <i class="fa-brands fa-whatsapp me-1"></i> Chat Direct WA
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- 6 SUB-NAVIGATION TABS (STANDAR SISTEM RFM) -->
    <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
      <a href="<?= base_url('rfm'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
        1. Dashboard RFM
      </a>
      <a href="<?= base_url('rfm/customer'); ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
        &#9679; 2. Daftar Customer
      </a>
      <a href="<?= base_url('rfm/tracking'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
        3. Tracking RFM
      </a>
      <a href="<?= base_url('rfm/kohort'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
        4. Analisis Kohort
      </a>
      <a href="<?= base_url('rfm/tren-produk'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
        5. Tren Segmen Produk
      </a>
      <a href="<?= base_url('rfm/matriks'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
        6. Matriks Aturan RFM
      </a>
    </div>
  </div>
</div>

<!-- 4 STAT CARDS ATAS (PERSIS SESUAI FOTO SISTEM) -->
<div class="row g-2 g-md-3 mb-3">
  <div class="col-lg-3 col-md-6 col-6">
    <div class="rfm-stat-card">
      <div class="val" style="color: #0284c7;"><?= (int) $summary['total_orders']; ?></div>
      <div class="lbl">TOTAL ORDER CUSTOMER &darr;</div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6">
    <div class="rfm-stat-card">
      <div class="val text-success">Rp <?= number_format($summary['total_spend'], 0, ',', '.'); ?></div>
      <div class="lbl">TOTAL BELANJA CUSTOMER &darr;</div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6">
    <div class="rfm-stat-card">
      <div class="val" style="color: #ea580c;"><?= !empty($summary['first_order']) ? date('d M Y', strtotime($summary['first_order'])) : '-'; ?></div>
      <div class="lbl">ORDER PERTAMA &darr;</div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6">
    <div class="rfm-stat-card">
      <div class="val" style="color: #6366f1;"><?= !empty($summary['last_order']) ? date('d M Y', strtotime($summary['last_order'])) : '-'; ?></div>
      <div class="lbl">ORDER TERAKHIR &darr;</div>
    </div>
  </div>
</div>

  <!-- SECTION TENGAH: SKOR RFM & REKOMENDASI ACTION (PERSIS SESUAI FOTO) -->
  <div class="row g-3 mb-3">
    <!-- KARTU KIRI: SKOR & SEGMENTASI RFM KESELURUHAN CUSTOMER -->
    <div class="col-lg-7 col-12">
      <div class="rfm-detail-card p-3 p-md-4 mb-0">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #1A2E23; font-size: 0.98rem;">
          <i class="fa-solid fa-chart-simple text-success fs-5"></i> Skor &amp; Segmentasi RFM Keseluruhan Customer
        </h6>

        <!-- ROW RECENCY -->
        <div class="row align-items-center mb-2.5 g-2">
          <div class="col-4 col-sm-3 text-muted small fw-bold d-flex align-items-center gap-1">
            <i class="fa-regular fa-clock text-info"></i> RECENCY
          </div>
          <div class="col-4 col-sm-5">
            <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
              <div class="progress-bar" style="width: <?= min(100, ($rfm['r_score'] / 5) * 100) ?>%; background-color: #10b981; border-radius: 10px;"></div>
            </div>
          </div>
          <div class="col-2 col-sm-1 text-center">
            <span class="badge text-white fw-bold px-2 py-1" style="background-color: #10b981; border-radius: 6px; font-size: 0.8rem; min-width: 26px;">
              <?= $rfm['r_score'] ?>
            </span>
          </div>
          <div class="col-12 col-sm-3 text-muted small text-sm-end">
            <?= $rfm['r_label'] ?> (<?= $rfm['days_since_last'] ?> hr)
          </div>
        </div>

        <!-- ROW FREQUENCY -->
        <div class="row align-items-center mb-2.5 g-2">
          <div class="col-4 col-sm-3 text-muted small fw-bold d-flex align-items-center gap-1">
            <i class="fa-solid fa-rotate text-success"></i> FREQUENCY
          </div>
          <div class="col-4 col-sm-5">
            <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
              <div class="progress-bar" style="width: <?= min(100, ($rfm['f_score'] / 5) * 100) ?>%; background-color: #10b981; border-radius: 10px;"></div>
            </div>
          </div>
          <div class="col-2 col-sm-1 text-center">
            <span class="badge text-white fw-bold px-2 py-1" style="background-color: #10b981; border-radius: 6px; font-size: 0.8rem; min-width: 26px;">
              <?= $rfm['f_score'] ?>
            </span>
          </div>
          <div class="col-12 col-sm-3 text-muted small text-sm-end">
            <?= $rfm['f_label'] ?> (<?= (int)$summary['total_orders'] ?>x)
          </div>
        </div>

        <!-- ROW MONETARY -->
        <div class="row align-items-center mb-3 g-2">
          <div class="col-4 col-sm-3 text-muted small fw-bold d-flex align-items-center gap-1">
            <i class="fa-solid fa-coins text-warning"></i> MONETARY
          </div>
          <div class="col-4 col-sm-5">
            <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
              <div class="progress-bar" style="width: <?= min(100, ($rfm['m_score'] / 5) * 100) ?>%; background-color: #10b981; border-radius: 10px;"></div>
            </div>
          </div>
          <div class="col-2 col-sm-1 text-center">
            <span class="badge text-white fw-bold px-2 py-1" style="background-color: #10b981; border-radius: 6px; font-size: 0.8rem; min-width: 26px;">
              <?= $rfm['m_score'] ?>
            </span>
          </div>
          <div class="col-12 col-sm-3 text-muted small text-sm-end">
            <?= $rfm['m_label'] ?>
          </div>
        </div>

        <!-- CALLOUT BOX SEGMEN PELANGGAN (WARNA SISTEM) -->
        <div class="p-3 d-flex align-items-center gap-3 rounded-3 mt-3" style="background-color: <?= $badge['bg'] ?>; border: 1px solid <?= $badge['border'] ?>;">
          <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 20px; color: <?= $badge['color'] ?>; background: rgba(255,255,255,0.7); box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
            <i class="fa <?= $badge['icon'] ?>"></i>
          </div>
          <div>
            <div class="fw-bold fs-5" style="color: <?= $badge['color'] ?>;">
              <?= esc($rfm['segment']) ?>
            </div>
            <div class="small opacity-90" style="color: #475569;">
              <?= esc($rfm['goal']) ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KARTU KANAN: REKOMENDASI ACTION & KODE SKOR RFM -->
    <div class="col-lg-5 col-12">
      <div class="rfm-detail-card p-3 p-md-4 mb-0">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #1A2E23; font-size: 0.98rem;">
          <i class="fa-regular fa-lightbulb text-warning fs-5"></i> Rekomendasi Action
        </h6>

        <!-- KOTAK GOAL STRATEGIS -->
        <div class="p-2.5 p-sm-3 rounded-3 mb-2" style="background-color: #fffbeb; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b;">
          <div class="small fw-bold mb-1 d-flex align-items-center gap-1" style="color: #b45309;">
            <i class="fa-solid fa-bullseye me-1"></i> Goal Strategis
          </div>
          <div class="small text-dark fw-medium">
            <?= esc($rfm['goal']) ?>
          </div>
        </div>

        <!-- KOTAK REKOMENDASI AKSI -->
        <div class="p-2.5 p-sm-3 rounded-3 mb-3" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #10b981;">
          <div class="small fw-bold mb-1 d-flex align-items-center gap-1" style="color: #047857;">
            <i class="fa-solid fa-circle-check me-1"></i> Rekomendasi Aksi
          </div>
          <div class="small text-dark fw-medium">
            <?= esc($rfm['action']) ?>
          </div>
        </div>

        <!-- DISPLAY KODE SKOR RFM (R - F - M) -->
        <div class="text-center pt-2 pb-1 border-top mt-3">
          <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1.2px;">
            KODE SKOR RFM (R - F - M)
          </div>
          <div class="fw-bold font-monospace" style="color: #10b981; font-size: 1.8rem; letter-spacing: 10px; line-height: 1.2;">
            <?= $rfm['r_score'] ?> &ndash; <?= $rfm['f_score'] ?> &ndash; <?= $rfm['m_score'] ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION TABEL 1: SEMUA PRODUK YANG DIBELI CUSTOMER INI (PERSIS SESUAI FOTO) -->
  <div class="rfm-detail-card mb-3">
    <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #1A2E23;">
        <i class="fa-solid fa-boxes-stacked text-success"></i> Semua Produk yang Dibeli Customer Ini
      </h6>
      <span class="badge bg-light text-secondary border px-3 py-1">
        Total <?= count($product_rfm) ?> SKU Produk
      </span>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-rfm-detail mb-0">
        <thead>
          <tr>
            <th class="ps-4" style="width: 40px;">#</th>
            <th>NAMA PRODUK</th>
            <th>KODE SKU</th>
            <th>TERAKHIR BELI</th>
            <th>RECENCY</th>
            <th>ORDER (F)</th>
            <th>QTY</th>
            <th>TOTAL SPEND (M)</th>
            <th>% KONTRIBUSI</th>
            <th class="pe-4 text-center text-nowrap" style="width: 100px;">AKSI</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($product_rfm)): ?>
            <tr>
              <td colspan="10" class="text-center py-4 text-muted">
                <i class="fa-solid fa-circle-info fa-2x mb-2 d-block opacity-50"></i>
                Belum ada rincian transaksi produk untuk pelanggan ini.
              </td>
            </tr>
          <?php else: ?>
            <?php 
              $cust_total_spend = max(1, $summary['total_spend']);
              foreach ($product_rfm as $pIdx => $pr): 
                $p_pct = round(($pr->total_spend / $cust_total_spend) * 100, 1);
            ?>
              <tr>
                <td class="ps-4 text-muted small"><?= $pIdx + 1 ?></td>
                <td>
                  <div class="fw-bold text-dark"><?= esc($pr->nama_produk) ?></div>
                </td>
                <td>
                  <span class="badge bg-light text-secondary border px-2 py-1 font-monospace" style="font-size: 11px;">
                    <?= esc($pr->kode_produk) ?>
                  </span>
                </td>
                <td class="text-muted small">
                  <?= !empty($pr->last_bought_date) ? date('d M Y', strtotime($pr->last_bought_date)) : '-' ?>
                </td>
                <td>
                  <span class="fw-bold text-success">
                    <?= (int) $pr->recency_days ?> hari
                  </span>
                </td>
                <td>
                  <span class="badge bg-light text-dark border px-2 py-1">
                    <?= (int) $pr->order_count ?>x
                  </span>
                </td>
                <td class="fw-bold text-dark">
                  <?= (int) $pr->total_qty ?>
                </td>
                <td class="fw-bold text-success font-monospace">
                  Rp <?= number_format((float) $pr->total_spend, 0, ',', '.') ?>
                </td>
                <td>
                  <span class="badge rounded-pill px-2 py-1 fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">
                    <?= $p_pct ?>%
                  </span>
                </td>
                <td class="pe-4 text-center text-nowrap">
                  <div class="d-inline-flex align-items-center justify-content-center gap-2">
                    <a href="<?= base_url('rfm/customer?produk_id=' . $pr->produk_id) ?>" class="btn btn-sm text-white rounded d-inline-flex align-items-center justify-content-center shadow-sm" style="background-color: #2D6A4F; width: 32px; height: 32px;" title="RFM Produk Ini (Lihat Customer Lain)">
                      <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <a href="<?= base_url('rfm/tren-produk') ?>" class="btn btn-sm text-white rounded d-inline-flex align-items-center justify-content-center shadow-sm" style="background-color: #0284c7; width: 32px; height: 32px;" title="Halaman RFM Produk (Tren Segmen)">
                      <i class="fa-solid fa-link"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- SECTION TABEL 2: RIWAYAT TRANSAKSI (PERSIS SESUAI FOTO) -->
  <div class="rfm-detail-card mb-3">
    <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #1A2E23;">
        <i class="fa-solid fa-clock-rotate-left text-success"></i> Riwayat Transaksi (<?= count($transactions) ?>)
      </h6>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-rfm-detail mb-0">
        <thead>
          <tr>
            <th class="ps-4" style="width: 40px;">#</th>
            <th>TANGGAL</th>
            <th>NO PESANAN</th>
            <th>SUMBER</th>
            <th>PRODUK</th>
            <th>TOTAL</th>
            <th class="pe-4 text-center">STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($transactions)): ?>
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">
                <i class="fa-solid fa-receipt fa-2x mb-2 d-block opacity-50"></i>
                Belum ada transaksi tercatat untuk pelanggan ini.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($transactions as $tIdx => $t): ?>
              <?php 
                $srcLower = strtolower($t['source']);
                $srcBg = match($srcLower) {
                    'shopee' => 'background-color: #ee4d2d;',
                    'tokopedia' => 'background-color: #03ac0e;',
                    'tiktok' => 'background-color: #000000;',
                    default => 'background-color: #1A2E23;',
                };
                $stLower = strtolower($t['status']);
                $stStyle = match($stLower) {
                    'completed', 'selesai', 'delivered' => 'background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;',
                    'processing', 'proses'              => 'background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe;',
                    'shipped', 'dikirim'               => 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;',
                    'cancelled', 'batal'               => 'background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;',
                    default                            => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                };
                $stLabel = match($stLower) {
                    'completed', 'selesai', 'delivered' => 'SELESAI',
                    'processing', 'proses'              => 'PROSES',
                    'shipped', 'dikirim'               => 'DIKIRIM',
                    'cancelled', 'batal'               => 'BATAL',
                    default                            => strtoupper($t['status']),
                };
              ?>
              <tr>
                <td class="ps-4 text-muted small"><?= $tIdx + 1 ?></td>
                <td class="small">
                  <?= !empty($t['tanggal']) && $t['tanggal'] !== '-' ? date('d M Y', strtotime($t['tanggal'])) : '-' ?>
                </td>
                <td class="font-monospace fw-bold small text-dark">
                  <?= esc($t['no_pesanan']) ?>
                </td>
                <td>
                  <span class="badge-source-tag" style="<?= $srcBg ?>">
                    <?= esc(ucfirst($t['source'])) ?>
                  </span>
                </td>
                <td class="text-truncate" style="max-width: 320px;" title="<?= esc($t['items']) ?>">
                  <?= esc($t['items']) ?>
                </td>
                <td class="fw-bold font-monospace text-success">
                  Rp <?= number_format($t['total'], 0, ',', '.') ?>
                </td>
                <td class="pe-4 text-center">
                  <span class="badge rounded-pill px-3 py-1 fw-bold" style="<?= $stStyle ?>">
                    <?= $stLabel ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?= $this->endSection() ?>

