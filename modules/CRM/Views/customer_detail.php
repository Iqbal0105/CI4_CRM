<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
@keyframes crmFadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

.crm-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  margin-bottom: 16px;
  overflow: hidden;
  animation: crmFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.crm-stat-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 12px 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  height: 100%;
  transition: all 0.25s ease;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.crm-stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(45, 106, 79, 0.1);
  border-color: #2D6A4F;
}

.table-system th {
  background: #f8fafc;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #475569;
  font-weight: 700;
  vertical-align: middle;
  border-color: #e2e8f0;
}
.table-system td {
  font-size: 0.84rem;
  vertical-align: middle;
  border-color: #f1f5f9;
}

.badge-source-crm {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  color: #fff;
  display: inline-block;
}

.note-item {
  position: relative;
  padding: 10px 14px;
  background: #f8fafc;
  border-radius: 8px;
  border-left: 3px solid #2D6A4F;
  margin-bottom: 10px;
  font-size: 12px;
}

.trx-row {
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.trx-row:hover {
  background-color: #f8fafc !important;
}

.trx-detail-panel {
  display: none;
  background-color: #f8fafc;
  border-top: 2px solid #2D6A4F;
  border-bottom: 2px solid #cbd5e1;
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
  $aov = $summary['total_orders'] > 0 ? round($summary['total_spend'] / $summary['total_orders']) : 0;
?>

<!-- TOP CRM BANNER (WARNA SISTEM GRADASI HIJAU) -->
<div class="card border-0 text-white mb-3 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
  <div class="card-body p-3 p-md-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-0">
      <div class="flex-grow-1">
        <div class="small text-white-50">
          <i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; <a href="<?= base_url('crm/directory') ?>" class="text-white text-decoration-underline">Customer Directory</a> &gt; Detail Profil Pelanggan
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
          <div class="bg-white bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.6rem; border: 1px solid rgba(255,255,255,0.25);">
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
              <?php if (!empty($customer['email']) && $customer['email'] !== '-'): ?>
                <span><i class="fa-regular fa-envelope me-1"></i><?= esc($customer['email']); ?></span>
              <?php endif; ?>
              <?php if (!empty($customer['kota']) && $customer['kota'] !== '-'): ?>
                <span><i class="fa-solid fa-location-dot me-1"></i><?= esc($customer['kota']); ?></span>
              <?php endif; ?>
              <?php foreach ($summary['sources'] as $s): ?>
                <?php
                  $sLower = strtolower($s);
                  $bgS = match($sLower) {
                    'shopee' => 'background-color: #ee4d2d;',
                    'tokopedia' => 'background-color: #03ac0e;',
                    'tiktok' => 'background-color: #000000;',
                    default => 'background-color: rgba(255,255,255,0.2);',
                  };
                ?>
                <span class="badge-source-crm" style="<?= $bgS ?>"><?= esc($s); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- TOMBOL AKSI ATAS -->
      <div class="d-flex flex-wrap gap-2 ms-auto flex-shrink-0 align-self-end align-self-md-center text-nowrap">
        <a href="<?= base_url('crm/directory'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 fw-semibold">
          <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Directory
        </a>
        <a href="<?= base_url('penjualan/create' . (!empty($summary['id']) ? '?pelanggan_id=' . $summary['id'] : '')); ?>" class="btn btn-sm btn-warning text-dark rounded-pill px-3 py-1 fw-bold shadow-sm" title="Buat Transaksi Penjualan Repeat Order">
          <i class="fa-solid fa-cart-plus me-1"></i> Repeat Order
        </a>
        <?php if ($has_valid_phone): ?>
          <a href="<?= $top_wa_url; ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm" style="background-color: #25D366; border-color: #25D366;" title="Kirim Chat WhatsApp">
            <i class="fa-brands fa-whatsapp me-1"></i> Chat WA
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- 6 SUMMARY STAT CARDS (PERSIS SESUAI FOTO SISTEM CRM) -->
<div class="row g-2 g-sm-3 mb-3">
  <!-- Total Order -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-3 fw-bold mb-0" style="color: #0284c7;"><?= (int) $summary['total_orders']; ?></div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL ORDER</div>
    </div>
  </div>

  <!-- Total Spend -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-5 fw-bold font-monospace mb-0" style="color: #2D6A4F;">
        Rp <?= number_format($summary['total_spend'], 0, ',', '.'); ?>
      </div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL SPEND</div>
    </div>
  </div>

  <!-- Order Toko -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-3 fw-bold mb-0" style="color: #ea580c;"><?= (int) $summary['order_count_toko']; ?></div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ORDER TOKO</div>
    </div>
  </div>

  <!-- Order Online -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-3 fw-bold mb-0" style="color: #7c3aed;"><?= (int) $summary['order_count_online']; ?></div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ORDER ONLINE</div>
    </div>
  </div>

  <!-- Order Pertama -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-5 fw-bold mb-0" style="color: #0284c7;">
        <?= !empty($summary['first_order']) ? date('d/m/y', strtotime($summary['first_order'])) : '-'; ?>
      </div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ORDER PERTAMA</div>
    </div>
  </div>

  <!-- Order Terakhir -->
  <div class="col-6 col-sm-4 col-xl-2">
    <div class="crm-stat-card text-center p-2 p-sm-3 h-100">
      <div class="fs-5 fw-bold mb-0" style="color: #dc2626;">
        <?= !empty($summary['last_order']) ? date('d/m/y', strtotime($summary['last_order'])) : '-'; ?>
      </div>
      <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ORDER TERAKHIR</div>
    </div>
  </div>
</div>

<!-- BAGIAN 1: ANALISIS & SEGMENTASI RFM (STANDAR SISTEM CRM) -->
<div class="crm-card mb-3">
  <div class="card-header bg-white border-bottom py-2 py-md-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
      <i class="fa-solid fa-chart-pie text-success"></i> Analisis &amp; Intelijen Segmentasi RFM Pelanggan
    </h6>
    <div class="d-flex align-items-center gap-2">
      <a href="<?= base_url('rfm/customer_detail?id=' . $summary['id']); ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold" style="font-size: 11.5px;" title="Lihat Profil Lengkap di Modul RFM">
        <i class="fa-solid fa-chart-column me-1"></i> Buka Detail RFM
      </a>
      <span class="badge rounded-pill px-3 py-1 fw-bold" style="background:<?= $badge['bg']; ?>;color:<?= $badge['color']; ?>;font-size:12px;border:1px solid <?= $badge['border']; ?>;">
        <i class="fa <?= $badge['icon']; ?> me-1"></i> <?= esc($rfm['segment']); ?>
      </span>
    </div>
  </div>
  <div class="card-body p-3">
      <!-- 4 Kartu Metrik RFM Ringkas -->
      <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
          <div class="p-3 bg-light rounded-3 border text-center h-100">
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Recency (Kebaruan)</div>
            <div class="fs-4 fw-bold text-info font-monospace my-1"><?= $rfm['days_since_last']; ?> Hari</div>
            <div class="text-muted small" style="font-size: 0.75rem;">Skor R: <strong><?= $rfm['r_score']; ?>/5</strong> (<?= $rfm['r_label']; ?>)</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 bg-light rounded-3 border text-center h-100">
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Frequency (Frekuensi)</div>
            <div class="fs-4 fw-bold text-success font-monospace my-1"><?= $summary['total_orders']; ?>x Order</div>
            <div class="text-muted small" style="font-size: 0.75rem;">Skor F: <strong><?= $rfm['f_score']; ?>/5</strong> (<?= $rfm['f_label']; ?>)</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 bg-light rounded-3 border text-center h-100">
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Monetary (Nominal)</div>
            <div class="fs-5 fw-bold text-dark font-monospace my-1 text-truncate">Rp <?= number_format($summary['total_spend'], 0, ',', '.'); ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Skor M: <strong><?= $rfm['m_score']; ?>/5</strong> (<?= $rfm['m_label']; ?>)</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 bg-light rounded-3 border text-center h-100">
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Matriks RFM &amp; AOV</div>
            <div class="my-1">
              <span class="badge bg-success rounded px-2 py-1 font-monospace fw-bold"><?= $rfm['r_score']; ?></span>
              <span class="badge bg-warning rounded px-2 py-1 font-monospace fw-bold"><?= $rfm['f_score']; ?></span>
              <span class="badge bg-info rounded px-2 py-1 font-monospace fw-bold"><?= $rfm['m_score']; ?></span>
            </div>
            <div class="text-muted small" style="font-size: 0.75rem;">AOV: <strong class="text-dark">Rp <?= number_format($aov, 0, ',', '.'); ?></strong></div>
          </div>
        </div>
      </div>

      <!-- Goal & Rekomendasi Aksi Promo RFM -->
      <div class="p-3 bg-white border rounded-3">
        <div class="row g-3 align-items-center">
          <div class="col-12 col-md-6 border-end-md">
            <div class="small fw-bold text-dark d-flex align-items-center gap-1 mb-1">
              <i class="fa-solid fa-bullseye text-danger"></i> Target Perilaku Pelanggan (Goal)
            </div>
            <div class="p-2 rounded" style="background:#f0fdf4; border-left:3px solid #2D6A4F; font-size: 13px; color: #1A2E23;">
              <?= esc($rfm['goal']); ?>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="small fw-bold text-dark d-flex align-items-center gap-1 mb-1">
              <i class="fa-solid fa-wand-magic-sparkles text-success"></i> Rekomendasi Strategi Promo &amp; CRM (Action)
            </div>
            <div class="p-2 rounded" style="background:#fffbeb; border-left:3px solid #d97706; font-size: 13px; color: #78350f;">
              <?= esc($rfm['action']); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BAGIAN 2: STATUS & JADWAL AKTIVITAS FOLLOW-UP CRM (STANDAR SISTEM CRM) -->
  <div class="crm-card mb-3">
    <div class="card-header bg-white border-bottom py-2 py-md-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
        <i class="fa-solid fa-users-gear text-success"></i> Aktivitas Follow-Up CRM Pelanggan
      </h6>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold">
          <i class="fa-solid fa-clock me-1"></i><?= (int)$pendingCount ?> Pending
        </span>
        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold">
          <i class="fa-solid fa-check me-1"></i><?= (int)$doneCount ?> Selesai
        </span>
      </div>
    </div>
    <div class="card-body p-3">
      <div class="row g-3">
        <!-- Kolom Kiri: Jadwal Tahapan Follow-Up Transaksi -->
        <div class="col-12 col-lg-7 border-end-lg">
          <div class="fw-semibold small text-dark mb-2 d-flex align-items-center gap-1">
            <i class="fa-solid fa-list-check text-primary"></i> Jadwal Tahapan Follow-Up
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-hover table-system mb-0">
              <thead>
                <tr>
                  <th>Tahapan &amp; Kategori</th>
                  <th>Pesanan &amp; Produk</th>
                  <th>Target Tanggal &amp; Resi</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($followupLabels)): ?>
                  <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                      <i class="fa-solid fa-inbox fa-2x mb-1 d-block opacity-50"></i>
                      Belum ada jadwal tahapan follow-up untuk pelanggan ini.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($followupLabels as $fl): ?>
                    <?php 
                      $isPending = ($fl['status'] === 'pending');
                      $badgeSt = $isPending
                        ? '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold"><i class="fa-solid fa-clock me-1"></i>Pending</span>'
                        : '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold"><i class="fa-solid fa-check me-1"></i>Terkirim</span>';
                    ?>
                    <tr>
                      <td class="fw-semibold text-dark">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1"><?= esc($fl['label_code'] ?: '-') ?></span>
                        <?= esc($fl['label_name'] ?: 'Follow-up') ?>
                      </td>
                      <td>
                        <div class="fw-semibold text-dark"><?= esc($fl['nomor_so'] ?: ('Order #' . $fl['order_id'])) ?></div>
                        <div class="text-muted small text-truncate" style="max-width: 180px;"><?= esc($fl['product_name'] ?: '-') ?></div>
                      </td>
                      <td>
                        <div class="text-dark"><?= !empty($fl['target_date']) ? date('d M Y', strtotime($fl['target_date'])) : '-' ?></div>
                        <div class="text-muted small"><?= !empty($fl['courier']) ? esc($fl['courier']) : '-' ?></div>
                      </td>
                      <td class="text-center"><?= $badgeSt ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Kolom Kanan: Catatan Follow-Up CRM & Form Input Cepat -->
        <div class="col-12 col-lg-5">
          <div class="fw-semibold small text-dark mb-2 d-flex align-items-center gap-1">
            <i class="fa-solid fa-comments text-success"></i> Catatan &amp; Riwayat Interaksi CRM
          </div>
          
          <!-- Riwayat Catatan -->
          <div style="max-height: 280px; overflow-y: auto;" class="pe-1">
            <?php if (empty($notes)): ?>
              <div class="p-3 bg-light rounded text-center text-muted small mb-3">
                <i class="fa-regular fa-comment-dots me-1"></i> Belum ada catatan interaksi untuk customer ini.
              </div>
            <?php else: ?>
              <?php foreach ($notes as $n): ?>
                <div class="note-item">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-dark"><?= esc($n['cs_name'] ?? 'CS Staff') ?></strong>
                    <span class="text-muted" style="font-size: 10.5px;"><?= !empty($n['created_at']) ? date('d/m/y H:i', strtotime($n['created_at'])) : '-' ?></span>
                  </div>
                  <div class="text-secondary"><?= nl2br(esc($n['catatan'])) ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Form Tambah Catatan Follow-Up Cepat -->
          <div class="p-3 bg-light rounded-3 border mt-3">
            <div class="small fw-bold text-dark mb-2"><i class="fa-solid fa-pen-to-square text-primary me-1"></i>Tambah Catatan Cepat</div>
            <form action="<?= base_url('crm/journal/save_note') ?>" method="POST">
              <input type="hidden" name="pelanggan_id" value="<?= esc($summary['id']) ?>">
              <input type="hidden" name="customer_phone" value="<?= esc($summary['phone']) ?>">
              <div class="mb-2">
                <textarea class="form-control form-control-sm" name="catatan" rows="2" placeholder="Tuliskan hasil percakapan atau respon customer..." required></textarea>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <select name="label" class="form-select form-select-sm" style="width: auto;">
                  <option value="Follow-up">Follow-up</option>
                  <option value="Repeat Order">Repeat Order</option>
                  <option value="Closed">Closed</option>
                </select>
                <button type="submit" class="btn btn-sm btn-success fw-semibold px-3 shadow-sm" style="background-color: #2D6A4F;">
                  <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Catatan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BAGIAN 3: RIWAYAT TRANSAKSI PESANAN (STANDAR SISTEM CRM) -->
  <div class="crm-card mb-3">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
        <i class="fa-solid fa-clock-rotate-left text-success"></i> Riwayat Transaksi Pesanan (<?= count($transactions); ?>)
      </h6>
      <small class="text-muted">Klik baris transaksi untuk melihat rincian item &amp; timeline</small>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-system mb-0" id="tbl-trx">
        <thead>
          <tr>
            <th class="ps-3" style="width:30px">#</th>
            <th>Tanggal</th>
            <th>No. Pesanan</th>
            <th>Sumber</th>
            <th>Produk</th>
            <th class="text-end">Nominal</th>
            <th>Status</th>
            <th>Resi</th>
            <th class="pe-3 text-center">Follow-Up</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($transactions)): ?>
            <tr>
              <td colspan="9" class="text-center py-4 text-muted">
                <i class="fa-solid fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                Belum ada transaksi tercatat untuk pelanggan ini.
              </td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($transactions as $t): ?>
              <?php
                $sLower = strtolower($t->sumber_penjualan);
                $cls = match($sLower) {
                  'shopee' => 'background-color: #ee4d2d;',
                  'tokopedia' => 'background-color: #03ac0e;',
                  'tiktok' => 'background-color: #000000;',
                  default => 'background-color: #1A2E23;',
                };

                $st = strtolower($t->status ?? '');
                $st_p = strtolower($t->status_pesanan ?? '');
                $stStyle = match(true) {
                  in_array($st, ['shipped','dikirim']) || strpos($st_p, 'dikirim') !== false => 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;',
                  in_array($st, ['delivered','terkirim', 'completed','selesai']) => 'background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;',
                  in_array($st, ['cancelled','dibatalkan']) => 'background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;',
                  in_array($st, ['processing','confirmed','proses','packing']) => 'background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe;',
                  default => 'background: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                };
              ?>
              <tr class="trx-row" data-table="<?= $t->tbl; ?>" data-id="<?= $t->id; ?>">
                <td class="ps-3"><?= $no++; ?></td>
                <td><?= !empty($t->tanggal) ? date('d M Y', strtotime($t->tanggal)) : '-'; ?></td>
                <td><strong class="text-dark font-monospace"><?= htmlspecialchars($t->no_pesanan ?: $t->nomor_so); ?></strong></td>
                <td>
                  <span class="badge-source-crm" style="<?= $cls; ?>"><?= htmlspecialchars($t->sumber_penjualan); ?></span>
                </td>
                <td style="max-width:260px;" class="text-truncate" title="<?= htmlspecialchars($t->produk_ringkasan ?: '-'); ?>">
                  <?= htmlspecialchars($t->produk_ringkasan ?: '-'); ?>
                </td>
                <td class="text-end fw-bold font-monospace" style="color: #2D6A4F;">
                  Rp <?= number_format($t->total, 0, ',', '.'); ?>
                </td>
                <td>
                  <span class="badge rounded-pill px-2 py-1 fw-bold" style="<?= $stStyle; ?>">
                    <?= ucfirst($t->status_pesanan ?: $t->status); ?>
                  </span>
                </td>
                <td style="font-size:11px;" class="font-monospace"><?= htmlspecialchars($t->nomor_resi ?: '-'); ?></td>
                <td class="pe-3 text-center">
                  <button class="btn btn-sm btn-outline-success btn-timeline p-1 px-2" data-table="<?= $t->tbl; ?>" data-id="<?= $t->id; ?>" title="Lihat Detail Pesanan &amp; Timeline Follow-Up">
                    <i class="fa-regular fa-clock"></i>
                  </button>
                </td>
              </tr>
              <!-- Detail Panel Ekspansi -->
              <tr class="trx-detail-panel" id="detail-<?= $t->tbl; ?>-<?= $t->id; ?>">
                <td colspan="9" style="padding: 18px 24px;">
                  <div class="row g-4">
                    <div class="col-md-7">
                      <h6 class="fw-bold text-dark"><i class="fa-solid fa-boxes-stacked me-1 text-success"></i> Rincian Item Pesanan</h6>
                      <div class="detail-items-<?= $t->tbl; ?>-<?= $t->id; ?>">
                        <p class="text-muted small"><i class="fa-solid fa-spinner fa-spin me-1"></i> Memuat rincian item pesanan...</p>
                      </div>
                      <hr>
                      <div style="font-size:12px; color:#475569;">
                        <strong>Penerima:</strong> <?= htmlspecialchars($t->nama_penerima ?: '-'); ?><br>
                        <strong>Alamat:</strong> <?= htmlspecialchars($t->alamat_pengiriman ?: '-'); ?><br>
                        <strong>Ekspedisi:</strong> <?= htmlspecialchars($t->kurir_ekspedisi ?: '-'); ?>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <h6 class="fw-bold text-dark"><i class="fa-regular fa-clock me-1 text-success"></i> Timeline Follow-Up Transaksi</h6>
                      <div class="timeline-content-<?= $t->tbl; ?>-<?= $t->id; ?>">
                        <p class="text-muted small"><i class="fa-solid fa-spinner fa-spin me-1"></i> Memuat timeline...</p>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<script>
$(document).ready(function() {
  // Toggle transaction detail panel
  $('.trx-row').on('click', function() {
    var tbl = $(this).data('table');
    var id = $(this).data('id');
    var panel = $('#detail-' + tbl + '-' + id);
    
    if (panel.is(':visible')) {
      panel.slideUp(200);
      return;
    }
    
    // Close other panels
    $('.trx-detail-panel').slideUp(200);
    panel.slideDown(300);
    
    // Load detail via AJAX
    $.ajax({
      url: '<?= base_url("crm/transaction_detail_ajax"); ?>',
      type: 'GET',
      data: { table: tbl, order_id: id },
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          // Render items
          var itemHtml = '<table class="table table-sm table-bordered" style="font-size:12px;margin:0;">';
          itemHtml += '<thead class="table-light"><tr><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead><tbody>';
          if (res.items && res.items.length > 0) {
            $.each(res.items, function(i, item) {
              itemHtml += '<tr>';
              itemHtml += '<td>' + (item.nama_produk || '-') + '</td>';
              itemHtml += '<td class="text-center">' + parseFloat(item.qty) + ' ' + (item.satuan || '') + '</td>';
              itemHtml += '<td class="text-end">Rp ' + parseInt(item.harga_satuan || 0).toLocaleString('id-ID') + '</td>';
              itemHtml += '<td class="text-end">Rp ' + parseInt(item.subtotal || 0).toLocaleString('id-ID') + '</td>';
              itemHtml += '</tr>';
            });
          } else {
            itemHtml += '<tr><td colspan="4" class="text-center text-muted">Tidak ada detail item</td></tr>';
          }
          itemHtml += '</tbody></table>';
          $('.detail-items-' + tbl + '-' + id).html(itemHtml);
          
          // Render timeline
          var tlHtml = '';
          if (res.timeline && res.timeline.length > 0) {
            $.each(res.timeline, function(i, tl) {
              var isDone = (tl.status_label === 'Selesai');
              var catName = tl.nama_kategori || '';
              if (catName.indexOf('H+') === -1 && tl.jumlah_hari !== undefined) {
                catName += ' (H+' + tl.jumlah_hari + ')';
              }
              tlHtml += '<div class="p-2 mb-2 rounded border" style="background:#fff; border-left: 3px solid ' + (isDone ? '#10b981' : '#f59e0b') + ' !important;">';
              tlHtml += '<div class="fw-bold text-dark" style="font-size:12px;">' + catName + '</div>';
              tlHtml += '<div class="text-muted" style="font-size:11px;">Target: ' + tl.tanggal_label_muncul + '</div>';
              tlHtml += '<div class="mt-1">';
              tlHtml += '<span class="badge ' + (isDone ? 'bg-success' : 'bg-warning text-dark') + '" style="font-size:10px;">' + tl.status_label + '</span>';
              if (tl.broadcast_count > 0) {
                tlHtml += ' <span class="badge bg-primary" style="font-size:10px;"><i class="fa-brands fa-whatsapp"></i> ' + tl.broadcast_count + 'x</span>';
              }
              tlHtml += '</div>';
              if (tl.notes) {
                tlHtml += '<div style="font-size:11px;color:#64748b;margin-top:4px;"><i class="fa-regular fa-comment me-1"></i> ' + tl.notes + '</div>';
              }
              tlHtml += '</div>';
            });
          } else {
            tlHtml = '<p class="text-muted small">Belum ada data follow-up</p>';
          }
          $('.timeline-content-' + tbl + '-' + id).html(tlHtml);
        }
      },
      error: function() {
        $('.detail-items-' + tbl + '-' + id).html('<p class="text-danger small">Gagal memuat detail item.</p>');
        $('.timeline-content-' + tbl + '-' + id).html('<p class="text-danger small">Gagal memuat timeline.</p>');
      }
    });
  });
});
</script>
<?= $this->endSection() ?>
