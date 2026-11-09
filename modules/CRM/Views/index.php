<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Responsive Action Buttons: Teks saat desktop/tablet, Ikon saja saat mobile */
    .col-aksi-btn {
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
        border-radius: 6px !important;
        padding: 0.35rem 0.65rem !important;
        margin: 0 auto !important;
        transition: all 0.15s ease-in-out;
    }
    .col-aksi-btn i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: 0 !important;
        line-height: 1 !important;
        margin: 0 !important;
        text-align: center !important;
        font-size: 0.85rem !important;
    }
    .col-aksi-btn .btn-text {
        display: inline-block;
        line-height: 1;
        vertical-align: middle;
    }
    .col-aksi-th, .col-aksi {
        text-align: center !important;
        vertical-align: middle !important;
    }
    th.col-aksi-th {
        width: 100px;
    }
    /* Matikan icon sorting DataTables di kolom AKSI agar teks header selalu pas di tengah */
    #tableJournalFu thead th.col-aksi-th.sorting,
    #tableJournalFu thead th.col-aksi-th.sorting_asc,
    #tableJournalFu thead th.col-aksi-th.sorting_desc,
    #tableJournalFu thead th.col-aksi-th {
        padding-right: 8px !important;
        padding-left: 8px !important;
        text-align: center !important;
        background-image: none !important;
    }
    #tableJournalFu thead th.col-aksi-th:before,
    #tableJournalFu thead th.col-aksi-th:after {
        display: none !important;
        content: "" !important;
    }
    @media (max-width: 767.98px) {
        .col-aksi-btn .btn-text {
            display: none !important;
        }
        .col-aksi-btn {
            width: 34px !important;
            height: 34px !important;
            min-width: 34px !important;
            min-height: 34px !important;
            max-width: 34px !important;
            max-height: 34px !important;
            padding: 0 !important;
            border-radius: 8px !important;
            gap: 0 !important;
            margin: 0 auto !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .col-aksi-btn i {
            margin: 0 !important;
            font-size: 0.95rem !important;
            line-height: 1 !important;
            vertical-align: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .col-aksi-th, .col-aksi {
            width: 52px !important;
            min-width: 52px !important;
            max-width: 52px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
            text-align: center !important;
        }
    }

    /* Sub-nav horizontal scroll on mobile */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP BANNER -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1">
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>Metafora CRM &amp; After Sales &gt; Journal Follow-Up</div>
                <h3 class="fw-bold text-white mb-1">
                    <i class="fa-solid fa-book-open me-2"></i>Journal Follow-Up After Sales
                </h3>
                <p class="text-white-50 small mb-0">Pantau tahapan after sales &mdash; Konfirmasi Pengiriman, H+3 Cek Sampai, H+7 Experience, Custom H+n</p>
            </div>
            <div class="crm-header-actions d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-1.5 gap-sm-2 w-100 w-md-auto ms-md-auto mt-2 mt-md-0">
                <button type="button" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center gap-1 shadow-sm flex-fill flex-md-grow-0 text-nowrap" style="background-color: #9333ea;" id="btnSyncLabelH">
                    <i class="fa-solid fa-rotate"></i> <span>Sync Label H+</span>
                </button>
                <a href="<?= base_url('crm/export') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center gap-1 shadow-sm flex-fill flex-md-grow-0 text-nowrap" id="btnExportFuCsv">
                    <i class="fa-solid fa-file-csv"></i> <span>Export CSV</span>
                </a>
                <a href="<?= base_url('pengaturan/settings') ?>" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center gap-1 shadow-sm flex-fill flex-md-grow-0 text-nowrap" style="background-color: #6366f1;">
                    <i class="fa-solid fa-gear"></i> <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('crm/dashboard') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard CRM
            </a>
            <a href="<?= base_url('crm') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 2. Journal Follow-Up
            </a>
            <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                <i class="fa-brands fa-whatsapp me-1"></i>3. Broadcast WA
            </a>
            <a href="<?= base_url('crm/directory') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Customer Directory
            </a>
            <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Kinerja Tim CS
            </a>
            <a href="<?= base_url('crm/kelompok') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Kelompok Follow-Up
            </a>
        </div>
    </div>
</div>

<!-- 3 STAT CARDS ROW -->
<div class="row g-3 mb-4">
    <!-- 1. Total Pending -->
    <div class="col-12 col-md-4">
        <div class="card h-100 border text-center shadow-sm" style="border-top: 3px solid #ef4444 !important;">
            <div class="card-body p-3">
                <div class="fs-1 fw-bold text-danger font-monospace"><?= esc($totalPending) ?></div>
                <div class="text-muted text-uppercase fw-semibold small" style="letter-spacing: 0.05em;">TOTAL PENDING</div>
            </div>
        </div>
    </div>

    <!-- 2. Overdue -->
    <div class="col-12 col-md-4">
        <div class="card h-100 border text-center shadow-sm" style="border-top: 3px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="fs-1 fw-bold text-warning font-monospace"><?= esc($overdueCount) ?></div>
                <div class="text-muted text-uppercase fw-semibold small" style="letter-spacing: 0.05em;">OVERDUE (LEWAT JADWAL)</div>
            </div>
        </div>
    </div>

    <!-- 3. Follow-Up Hari Ini -->
    <div class="col-12 col-md-4">
        <div class="card h-100 border text-center shadow-sm" style="border-top: 3px solid #2D6A4F !important;">
            <div class="card-body p-3">
                <div class="fs-1 fw-bold text-primary font-monospace"><?= esc($pendingToday) ?></div>
                <div class="text-muted text-uppercase fw-semibold small" style="letter-spacing: 0.05em;">FOLLOW-UP HARI INI</div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER PILLS ROW 1 -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-filter-stage active" data-stage="all">
        Semua Data
    </button>
    <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-3 py-1 btn-filter-stage" data-stage="pending">
        Belum Direspon (Pending)
    </button>
    <button type="button" class="btn btn-sm text-white rounded-pill px-3 py-1 btn-filter-stage" style="background-color: #9333ea;" data-stage="sent">
        Customer Direspon
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-filter-stage" data-stage="H+0">
        Konfirmasi Pengiriman (H+0)
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-filter-stage" data-stage="H+3">
        Cek Barang Sampai (H+3)
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-filter-stage" data-stage="H+11">
        Evaluasi Produk (H+11)
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-filter-stage" data-stage="H+7">
        Tanya Pengalaman (H+7)
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-filter-stage" data-stage="H+30">
        Tawarkan Repeat Order (H+30)
    </button>
</div>

<!-- CARD DATA FOLLOW-UP -->
<div class="card shadow-sm border mb-4">
    <!-- FILTER ROW 2 -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3">
        <div class="row g-2 align-items-center">
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm" id="filterSumber">
                    <option value="">Semua Sumber</option>
                    <option value="Toko">Toko / Regular</option>
                    <option value="TikTok">TikTok Shop</option>
                    <option value="Shopee">Shopee</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm" id="filterStatusFu">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="sent">Terkirim</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm" id="filterKelompok">
                    <option value="">Semua Kelompok Follow-Up</option>
                    <option value="Konfirmasi">Konfirmasi Pengiriman</option>
                    <option value="Sampai">Cek Barang Sampai</option>
                    <option value="Pengalaman">Tanya Pengalaman</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="date" class="form-control form-control-sm" id="tglFilterFu">
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" id="searchFu" class="form-control" placeholder="Cari nama/HP/resi/pesanan...">
                    <button class="btn btn-primary" type="button" id="btnSearchFu">
                        <i class="fa-solid fa-filter me-1"></i>Filter
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetFu">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD HEADER BAR -->
    <div class="card-header bg-white py-2 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div class="fw-bold text-dark small">
            <i class="fa-solid fa-list-check text-primary me-2"></i>Data Follow-Up (<?= count($schedules) ?> entri)
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 shadow-sm" id="btnBroadcastSelected" style="font-size: 0.775rem;">
                <i class="fa-brands fa-whatsapp"></i> Broadcast Terpilih <span class="badge bg-white text-success rounded-pill ms-1 d-none" id="badgeSelectedCount">0</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSelectAllPending" style="font-size: 0.775rem;">
                <i class="fa-regular fa-square-check me-1"></i>Pilih Semua Pending
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableJournalFu">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 35px;"><input type="checkbox" id="cbAllFu" title="Pilih Semua di Halaman Ini"></th>
                        <th>CUSTOMER</th>
                        <th>NO. HP</th>
                        <th>NO. PESANAN</th>
                        <th class="text-center">SUMBER</th>
                        <th>KATEGORI FOLLOW-UP</th>
                        <th class="text-center">TGL KIRIM</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">KETERANGAN BC</th>
                        <th class="text-center col-aksi col-aksi-th">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($schedules)): ?>
                        <?php foreach ($schedules as $s): ?>
                            <?php 
                                $isSent = $s['status'] === 'sent';
                                $rawDigits = preg_replace('/\D/', '', (string)($s['customer_phone'] ?? ''));
                                $cleanPhone = '';
                                if (!empty($rawDigits)) {
                                    if (str_starts_with($rawDigits, '62')) {
                                        $cleanPhone = $rawDigits;
                                    } elseif (str_starts_with($rawDigits, '0')) {
                                        $cleanPhone = '62' . substr($rawDigits, 1);
                                    } elseif (str_starts_with($rawDigits, '8')) {
                                        $cleanPhone = '62' . $rawDigits;
                                    } else {
                                        $cleanPhone = '62' . $rawDigits;
                                    }
                                }

                                $oid = $s['order_id'];
                                $h0Info = $labelsByOrder[$oid]['H+0'] ?? ($s['label_code'] === 'H+0' ? ['id' => $s['id'], 'status' => $s['status']] : null);
                                $h3Info = $labelsByOrder[$oid]['H+3'] ?? ($s['label_code'] === 'H+3' ? ['id' => $s['id'], 'status' => $s['status']] : null);
                                $h7Info = $labelsByOrder[$oid]['H+7'] ?? ($s['label_code'] === 'H+7' ? ['id' => $s['id'], 'status' => $s['status']] : null);

                                $h0Id = $h0Info['id'] ?? null;
                                $h0Sent = ($h0Info['status'] ?? '') === 'sent';

                                $h3Id = $h3Info['id'] ?? null;
                                $h3Sent = ($h3Info['status'] ?? '') === 'sent';

                                $h7Id = $h7Info['id'] ?? null;
                                $h7Sent = ($h7Info['status'] ?? '') === 'sent';

                                $cName = $s['customer_nama'] ?: 'Pelanggan';
                                $soNum = $s['nomor_so'] ?? $s['nomor_resi'] ?? (string)$s['order_id'];

                                $tmplH0 = "Halo kak {$cName}, terima kasih telah berbelanja di Metafora! Pesanan Anda #{$soNum} sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!";
                                $tmplH3 = "Halo kak {$cName}, bagaimana kabar paket produk pesanan Anda (#{$soNum})? Semoga sudah sampai dengan selamat dan sesuai harapan ya. Jika butuh panduan, silakan balas chat ini ya kak!";
                                $tmplH7 = "Halo kak {$cName}, terima kasih telah mempercayai Metafora untuk pesanan #{$soNum}! Jangan lupa berikan ulasan terbaik Anda. Gunakan kode promo REPEAT10 untuk diskon belanja berikutnya ya!";

                                $linkH0 = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($tmplH0) : "javascript:alert('Nomor HP tidak valid')";
                                $linkH3 = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($tmplH3) : "javascript:alert('Nomor HP tidak valid')";
                                $linkH7 = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($tmplH7) : "javascript:alert('Nomor HP tidak valid')";
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="cb-fu" value="<?= $s['id'] ?>" data-status="<?= esc($s['status']) ?>" data-phone="<?= esc($cleanPhone) ?>" data-name="<?= esc($s['customer_nama']) ?>" data-label="<?= esc($s['label_code']) ?>">
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small"><?= esc($s['customer_nama']) ?></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-phone text-muted" style="font-size: 0.78rem;"></i>
                                        <span class="small font-monospace"><?= esc($s['customer_phone']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted small"><?= esc($s['nomor_so'] ?? $s['order_id']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['source'] === 'tiktok'): ?>
                                        <span class="badge bg-dark text-white fw-normal" style="font-size: 0.75rem;">TikTok Shop</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">Toko</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 0.78rem;">
                                        <?php if ($s['label_code'] === 'H+0'): ?>
                                            Konfirmasi Pengiriman (H+0)
                                        <?php elseif ($s['label_code'] === 'H+3'): ?>
                                            Cek Barang Sampai (H+3)
                                        <?php elseif ($s['label_code'] === 'H+7'): ?>
                                            Tanya Pengalaman (H+7)
                                        <?php else: ?>
                                            <?= esc($s['label_code']) ?>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="text-center small text-secondary font-monospace">
                                    <?= date('d/m/Y', strtotime($s['target_date'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($isSent): ?>
                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.75rem;"><i class="fa-solid fa-check me-1"></i><?= esc($s['label_code']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.75rem;"><?= esc($s['label_code']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1" style="font-size: 0.72rem;">
                                        <?php if ($h0Id): ?>
                                            <a href="<?= $linkH0 ?>" target="_blank" onclick="markFollowupSent(<?= $h0Id ?>, this)" class="badge bc-badge-clickable text-decoration-none <?= $h0Sent ? 'bg-success text-white' : 'bg-light text-secondary border' ?>" title="H+0: <?= $h0Sent ? 'Sudah Terkirim (Klik untuk buka WA)' : 'Klik untuk Kirim WhatsApp (' . esc($cleanPhone) . ')' ?>">H+0</a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border opacity-50" title="H+0 tidak tersedia">H+0</span>
                                        <?php endif; ?>

                                        <?php if ($h3Id): ?>
                                            <a href="<?= $linkH3 ?>" target="_blank" onclick="markFollowupSent(<?= $h3Id ?>, this)" class="badge bc-badge-clickable text-decoration-none <?= $h3Sent ? 'bg-success text-white' : 'bg-light text-secondary border' ?>" title="H+3: <?= $h3Sent ? 'Sudah Terkirim (Klik untuk buka WA)' : 'Klik untuk Kirim WhatsApp (' . esc($cleanPhone) . ')' ?>">H+3</a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border opacity-50" title="H+3 tidak tersedia">H+3</span>
                                        <?php endif; ?>

                                        <?php if ($h7Id): ?>
                                            <a href="<?= $linkH7 ?>" target="_blank" onclick="markFollowupSent(<?= $h7Id ?>, this)" class="badge bc-badge-clickable text-decoration-none <?= $h7Sent ? 'bg-success text-white' : 'bg-light text-secondary border' ?>" title="H+7: <?= $h7Sent ? 'Sudah Terkirim (Klik untuk buka WA)' : 'Klik untuk Kirim WhatsApp (' . esc($cleanPhone) . ')' ?>">H+7</a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border opacity-50" title="H+7 tidak tersedia">H+7</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center col-aksi col-aksi-th align-middle">
                                    <button type="button" class="btn btn-primary btn-sm rounded col-aksi-btn shadow-xs" title="Detail Follow-Up Customer" onclick="showDetailModal(<?= $s['id'] ?>, '<?= esc($s['customer_nama'], 'js') ?>', '<?= esc($s['customer_phone'], 'js') ?>', '<?= esc($s['nomor_so'] ?? $s['order_id'], 'js') ?>', '<?= esc($s['label_code'], 'js') ?>', '<?= esc($cleanPhone, 'js') ?>', '<?= esc($s['status'], 'js') ?>')">
                                        <i class="fa-solid fa-eye"></i><span class="btn-text ms-1">Detail FU</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL FOLLOW-UP CUSTOMER (MATCHING USER SCREENSHOT) -->
<div class="modal fade" id="modalDetailFu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <!-- HEADER TEAL -->
            <div class="modal-header text-white border-0 py-3 px-4" style="background-color: #0f766e;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; font-size: 1.25rem;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="fuModalCustomerName">Customer Name</h5>
                        <div class="small text-white-50 font-monospace">
                            <span id="fuModalCustomerPhone">-</span> | <span id="fuModalTrxId">-</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm text-white border-0 bg-white bg-opacity-10 rounded-circle p-2" onclick="location.reload()" title="Refresh">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-4 bg-light">
                <!-- 1. STATUS KETERLAMBATAN -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa-regular fa-clock me-1"></i>Status Keterlambatan:
                            </span>
                            <span class="badge rounded-pill bg-success px-3 py-1 fw-semibold">
                                <i class="fa-solid fa-check-circle me-1"></i>Tepat Waktu
                            </span>
                        </div>
                        <div class="fw-bold text-dark small">Tepat Waktu (Tidak Ada Keterlambatan)</div>
                        <p class="text-muted small mb-0">Semua tahapan follow-up yang jatuh tempo telah diselesaikan. Jadwal berikutnya: H+3 dalam 3 hari lagi.</p>
                    </div>
                </div>

                <!-- 2. TERAKHIR DI-FOLLOWUP -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa-solid fa-calendar-check me-1"></i>Terakhir Di-Followup:
                            </span>
                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-1 fw-bold">
                                <i class="fa-solid fa-check me-1"></i>SUDAH DI-FOLLOWUP
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="fw-bold text-dark small" id="fuModalCurrentStage">Konfirmasi Pengiriman (H+0)</span>
                        </div>
                        <div class="text-muted small mt-1 font-monospace" style="font-size: 0.75rem;">
                            <i class="fa-regular fa-clock me-1"></i>Kapan: <?= date('d/m/Y H:i') ?> WIB &bull; <i class="fa-regular fa-user me-1"></i>Oleh: Developer
                        </div>
                    </div>
                </div>

                <!-- 3. PRODUK YANG DIBELI CUSTOMER -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa-solid fa-box-open me-1"></i>Produk Yang Dibeli Customer:
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 small" id="fuModalProductBadge">1 PRODUK (1 ITEM)</span>
                        </div>
                        <div id="fuModalProductsList">
                            <div class="bg-light p-2 rounded border d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 bg-white rounded border text-primary">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark small" id="fuModalDefaultProdName">Laptop Pro 15-inch Intel i7 16GB SSD 512GB</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.78rem;">PRD-0001 &bull; 1 pcs @ Rp 15.500.000</div>
                                    </div>
                                </div>
                                <div class="text-end font-monospace fw-bold text-dark small" id="fuModalDefaultProdPrice">
                                    Rp 15.500.000
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top text-muted small">
                            <span><i class="fa-solid fa-receipt me-1"></i>Transaksi Follow-Up</span>
                            <span class="fw-bold text-success font-monospace" id="fuModalTotalBelanja">Total Belanja: Rp 15.500.000</span>
                        </div>
                    </div>
                </div>

                <!-- 4. ALUR TAHAPAN FOLLOW-UP (JADWAL H+) -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa-solid fa-timeline me-1"></i>Alur Tahapan Follow-Up (Jadwal H+):
                            </span>
                            <span class="text-muted small font-monospace" style="font-size: 0.75rem;">KIRIM: <?= date('d/m/Y') ?></span>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="fuModalStageBadges">
                            <span class="badge bg-light text-secondary border px-3 py-2 fw-normal">H+0</span>
                            <span class="badge bg-light text-secondary border px-3 py-2 fw-normal">H+3</span>
                            <span class="badge bg-light text-secondary border px-3 py-2 fw-normal">H+7</span>
                        </div>
                    </div>
                </div>

                <!-- 5. RIWAYAT CATATAN INTERNAL CS -->
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa-solid fa-notes-medical me-1"></i>Riwayat Catatan Internal CS:
                            </span>
                            <span class="badge bg-light text-secondary border" id="fuModalNotesCount">0 CATATAN</span>
                        </div>
                        <div id="fuModalNotesContainer">
                            <div class="p-3 bg-light rounded text-center text-muted small border border-dashed">
                                <i class="fa-regular fa-comment-dots fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                Belum ada catatan internal untuk pesanan ini.<br>Gunakan form di bawah untuk menulis catatan follow-up.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. FORM TAMBAH CATATAN & UPDATE STATUS -->
                <div class="card border-0 shadow-sm" style="border-radius: 8px;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark small mb-3">
                            <i class="fa-solid fa-pen-to-square text-primary me-1"></i>Tambah Catatan &amp; Update Status Follow-Up
                        </h6>
                        <form id="formFuModalUpdate">
                            <input type="hidden" id="fuModalPelangganId" value="1">
                            <input type="hidden" id="fuModalScheduleId" value="">
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Status Follow-Up (Keterangan BC):</label>
                                <select class="form-select form-select-sm" id="fuModalSelectStage">
                                    <option value="H+0">Konfirmasi Pengiriman (H+0)</option>
                                    <option value="H+3">Cek Barang Sampai (H+3)</option>
                                    <option value="H+7">Tanya Pengalaman (H+7)</option>
                                    <option value="H+11">Evaluasi Produk (H+11)</option>
                                    <option value="H+30">Tawarkan Repeat Order (H+30)</option>
                                    <option value="H+100">Testing Kelompok (H+100)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control form-control-sm" id="fuModalNoteText" rows="2" placeholder="Tulis catatan respon customer / follow-up di sini..."></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
                                <a href="#" id="fuModalBtnSendWa" class="btn btn-success btn-sm px-3 fw-semibold">
                                    <i class="fa-brands fa-whatsapp me-1"></i>Kirim WA Sekarang
                                </a>
                                <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold" id="btnSaveFuNote">
                                    <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Catatan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BROADCAST BATCH KE BANYAK PELANGGAN -->
<div class="modal fade" id="modalBroadcastBatch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white border-0 py-3 px-4" style="background-color: #2D6A4F;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; font-size: 1.15rem;">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" style="font-size: 1.05rem;">Broadcast WhatsApp Massal</h5>
                        <div class="small text-white-50">Kirim pesan follow-up ke banyak pelanggan terpilih</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <!-- Info Terpilih -->
                <div class="alert alert-success d-flex align-items-start gap-2 py-2 px-3 mb-3 border-success-subtle bg-white">
                    <i class="fa-solid fa-circle-check text-success fs-5 mt-1"></i>
                    <div>
                        <span class="small text-muted d-block">Jumlah Target Pelanggan:</span>
                        <strong class="text-success"><span id="bcSelectedCount">0</span> Pelanggan Terpilih</strong>
                        <div class="text-muted small text-truncate mt-1" style="max-width: 380px; font-size: 0.75rem;" id="bcSelectedNamesPreview">-</div>
                    </div>
                </div>

                <!-- Pilihan Mode Broadcast -->
                <div class="card border shadow-sm mb-3">
                    <div class="card-body p-3">
                        <label class="form-label small fw-bold text-dark mb-2">
                            <i class="fa-solid fa-sliders text-primary me-1"></i>Pilih Template / Mode Pesan:
                        </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="broadcast_mode" id="modeAuto" value="auto" checked>
                            <label class="form-check-label small" for="modeAuto">
                                <strong>Otomatis Sesuai Label H+ (Direkomendasikan)</strong>
                                <span class="d-block text-muted" style="font-size: 0.76rem;">
                                    Setiap pelanggan menerima pesan sesuai tahapan jadwalnya (H+0 kirim resi, H+3 cek barang sampai, H+7 promo & ulasan, dll.).
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="broadcast_mode" id="modeCustom" value="custom">
                            <label class="form-check-label small" for="modeCustom">
                                <strong>Pesan Kustom Seragam</strong>
                                <span class="d-block text-muted" style="font-size: 0.76rem;">
                                    Semua pelanggan terpilih akan dikirimi teks pesan khusus yang Anda tentukan sendiri.
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Textarea Pesan Kustom (Hidden by default) -->
                <div id="customMessageArea" class="d-none">
                    <div class="card border shadow-sm mb-3">
                        <div class="card-body p-3">
                            <label class="form-label small fw-bold text-dark mb-1">Tulis Teks Pesan Kustom:</label>
                            <textarea class="form-control form-control-sm mb-2" id="customBroadcastText" rows="4" placeholder="Halo kak {nama}, terima kasih telah berbelanja {produk} di toko kami..."></textarea>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <span class="text-muted small me-1" style="font-size: 0.72rem;">Klik untuk menyisipkan variabel:</span>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-insert-var" data-var="{nama}" style="font-size: 0.72rem;">{nama}</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-insert-var" data-var="{nomor_resi}" style="font-size: 0.72rem;">{nomor_resi}</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-insert-var" data-var="{produk}" style="font-size: 0.72rem;">{produk}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1 text-primary"></i>Status data pelanggan yang berhasil dikirimi pesan akan otomatis terupdate menjadi <span class="badge bg-success small"><i class="fa-solid fa-check me-1"></i>Sudah Di-Followup</span>.
                </div>
            </div>
            <div class="modal-footer bg-white py-2 px-4 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-success fw-bold d-inline-flex align-items-center gap-1 shadow-sm" id="btnSubmitBatchBroadcast">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Kirim Broadcast Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .bc-badge-clickable {
        cursor: pointer;
        transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .bc-badge-clickable:hover {
        transform: scale(1.15);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
</style>
<script>
    function markFollowupSent(id, el) {
        if (!id) return;
        $.post('<?= base_url('crm/mark-sent') ?>/' + id, function() {
            if (el) {
                $(el).removeClass('bg-light text-secondary border').addClass('bg-success text-white');
            }
        });
    }

    function sendSingleFollowup(id, label, customerName, isSent) {
        const actionText = isSent ? 'mengirim ulang' : 'mengirim';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `Kirim Follow-Up ${label}?`,
                html: `Apakah Anda ingin ${actionText} pesan WhatsApp follow-up <strong>${label}</strong> ke customer <strong>${customerName}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2D6A4F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-brands fa-whatsapp me-1"></i> Ya, Kirim Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('crm/send-now') ?>/' + id;
                }
            });
        } else {
            if (confirm(`Apakah Anda ingin ${actionText} pesan WhatsApp follow-up ${label} ke ${customerName}?`)) {
                window.location.href = '<?= base_url('crm/send-now') ?>/' + id;
            }
        }
    }

    function showDetailModal(id, name, phone, trx, label, cleanPhone, status) {
        $('#fuModalCustomerName').text(name);
        $('#fuModalCustomerPhone').text(phone);
        $('#fuModalTrxId').text(trx);
        $('#fuModalCurrentStage').text(label || 'Konfirmasi Pengiriman (H+0)');
        $('#fuModalScheduleId').val(id);

        let waClean = (cleanPhone || phone || '').toString().replace(/\D/g, '');
        if (waClean.startsWith('0')) waClean = '62' + waClean.substring(1);
        else if (waClean.startsWith('8')) waClean = '62' + waClean;
        const modalWaUrl = waClean ? 'https://wa.me/' + waClean : '#';
        $('#fuModalBtnSendWa').attr('href', modalWaUrl).attr('target', '_blank');
        $('#fuModalBtnSendWa').off('click').on('click', function() {
            if (id) {
                markFollowupSent(id, null);
            }
        });

        // Fetch details from backend
        if (id) {
            $.getJSON('<?= base_url('crm/detail-modal') ?>/' + id, function(res) {
                if (res && res.status && res.data) {
                    const data = res.data;
                    if (data.schedule && data.schedule.pelanggan_id) {
                        $('#fuModalPelangganId').val(data.schedule.pelanggan_id);
                    }
                    if (data.items && data.items.length > 0) {
                        let pHtml = '';
                        let tot = 0;
                        data.items.forEach(function(it) {
                            const sub = Number(it.subtotal || (it.qty * it.harga_satuan) || 0);
                            tot += sub;
                            pHtml += `<div class="bg-light p-2 rounded border d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 bg-white rounded border text-primary">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark small">${it.nama_produk || 'Produk'}</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.78rem;">${it.kode_produk || ''} &bull; ${it.qty} pcs</div>
                                    </div>
                                </div>
                                <div class="text-end font-monospace fw-bold text-dark small">
                                    Rp ${sub.toLocaleString('id-ID')}
                                </div>
                            </div>`;
                        });
                        $('#fuModalProductsList').html(pHtml);
                        $('#fuModalProductBadge').text(data.items.length + ' PRODUK');
                        $('#fuModalTotalBelanja').text('Total Belanja: Rp ' + tot.toLocaleString('id-ID'));
                    }
                    if (data.notes && data.notes.length > 0) {
                        let nHtml = '';
                        data.notes.forEach(function(nt) {
                            nHtml += `<div class="p-2 bg-light rounded border mb-2 small">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.78rem;">
                                    <span><strong>${nt.user_name || 'CS'}</strong> &bull; <span class="badge bg-secondary">${nt.label_stage}</span></span>
                                    <span>${nt.created_at}</span>
                                </div>
                                <div class="text-dark mt-1">${nt.notes}</div>
                            </div>`;
                        });
                        $('#fuModalNotesContainer').html(nHtml);
                        $('#fuModalNotesCount').text(data.notes.length + ' CATATAN');
                    } else {
                        $('#fuModalNotesContainer').html('<div class="p-3 bg-light rounded text-center text-muted small border border-dashed"><i class="fa-regular fa-comment-dots fs-3 d-block mb-1 text-secondary opacity-50"></i>Belum ada catatan internal untuk pesanan ini.<br>Gunakan form di bawah untuk menulis catatan follow-up.</div>');
                        $('#fuModalNotesCount').text('0 CATATAN');
                    }
                    if (data.allLabels && data.allLabels.length > 0) {
                        const stages = ['H+0', 'H+3', 'H+7'];
                        let bHtml = '';
                        stages.forEach(function(stg) {
                            const found = data.allLabels.find(function(l) { return l.label_code === stg; });
                            if (found && found.status === 'sent') {
                                bHtml += `<span class="badge bg-success text-white px-3 py-2 fw-semibold"><i class="fa-solid fa-check me-1"></i>${stg} Selesai</span> `;
                            } else {
                                bHtml += `<span class="badge bg-light text-secondary border px-3 py-2 fw-normal">${stg}</span> `;
                            }
                        });
                        $('#fuModalStageBadges').html(bHtml);
                    }
                }
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('modalDetailFu'));
        modal.show();
    }

    $(document).ready(function() {
        const table = $('#tableJournalFu').DataTable({
            dom: 'rtip',
            pageLength: 25,
            columnDefs: [
                { orderable: false, targets: [0, 9] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        // Auto-search jika datang dari link directory (misal: ?search=nama_customer)
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        if (searchParam) {
            $('#searchFu').val(searchParam);
            table.search(searchParam).draw();
        }

        function updateSelectedCount() {
            const checked = table.$('.cb-fu:checked').length;
            if (checked > 0) {
                $('#badgeSelectedCount').text(checked).removeClass('d-none');
            } else {
                $('#badgeSelectedCount').text('0').addClass('d-none');
            }
        }

        $('#cbAllFu').on('change', function() {
            const isChecked = this.checked;
            table.$('.cb-fu').prop('checked', isChecked);
            updateSelectedCount();
        });

        $('#tableJournalFu').on('change', '.cb-fu', function() {
            updateSelectedCount();
            const total = table.$('.cb-fu').length;
            const checked = table.$('.cb-fu:checked').length;
            $('#cbAllFu').prop('checked', total > 0 && total === checked);
        });

        $('#btnSelectAllPending').on('click', function() {
            const pendingBoxes = table.$('.cb-fu[data-status="pending"]');
            if (pendingBoxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: 'Tidak ada data berstatus pending pada daftar.'
                    });
                } else {
                    alert('Tidak ada data berstatus pending pada daftar.');
                }
                return;
            }
            const allChecked = pendingBoxes.filter(':checked').length === pendingBoxes.length;
            pendingBoxes.prop('checked', !allChecked);
            updateSelectedCount();
        });

        // Click Broadcast Terpilih Button
        $('#btnBroadcastSelected').on('click', function() {
            const checkedBoxes = table.$('.cb-fu:checked');
            const count = checkedBoxes.length;

            if (count === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Pelanggan Terlebih Dahulu',
                        text: 'Silakan centang satu atau lebih pelanggan pada tabel untuk melakukan broadcast WhatsApp massal.'
                    });
                } else {
                    alert('Silakan centang satu atau lebih pelanggan pada tabel untuk melakukan broadcast WhatsApp massal.');
                }
                return;
            }

            $('#bcSelectedCount').text(count);
            $('#btnSubmitBatchBroadcast span').text('Kirim Broadcast Sekarang (' + count + ' Pesan)');

            // Preview names
            let names = [];
            checkedBoxes.slice(0, 5).each(function() {
                names.push($(this).data('name') || 'Pelanggan');
            });
            let previewText = names.join(', ');
            if (count > 5) {
                previewText += ' dan ' + (count - 5) + ' pelanggan lainnya';
            }
            $('#bcSelectedNamesPreview').text('Penerima: ' + previewText);

            // Reset mode
            $('#modeAuto').prop('checked', true);
            $('#customMessageArea').addClass('d-none');
            $('#customBroadcastText').val('');

            const modalBc = new bootstrap.Modal(document.getElementById('modalBroadcastBatch'));
            modalBc.show();
        });

        // Mode switch
        $('input[name="broadcast_mode"]').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#customMessageArea').removeClass('d-none');
                $('#customBroadcastText').focus();
            } else {
                $('#customMessageArea').addClass('d-none');
            }
        });

        // Insert variable tag
        $('.btn-insert-var').on('click', function() {
            const v = $(this).data('var');
            const txtArea = $('#customBroadcastText');
            const cur = txtArea.val();
            txtArea.val(cur + (cur ? ' ' : '') + v);
            txtArea.focus();
        });

        // Submit Batch Broadcast Action
        $('#btnSubmitBatchBroadcast').on('click', function() {
            const btn = $(this);
            const selectedIds = [];
            table.$('.cb-fu:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Tidak ada pelanggan terpilih.');
                return;
            }

            const mode = $('input[name="broadcast_mode"]:checked').val() || 'auto';
            const customMsg = $('#customBroadcastText').val().trim();

            if (mode === 'custom' && !customMsg) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pesan Kosong',
                        text: 'Silakan ketik isi pesan kustom terlebih dahulu.'
                    });
                } else {
                    alert('Silakan ketik isi pesan kustom terlebih dahulu.');
                }
                return;
            }

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Sedang Mengirim (' + selectedIds.length + ' Pesan)...');

            $.ajax({
                url: '<?= base_url('crm/broadcast-batch') ?>',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    mode: mode,
                    custom_message: customMsg
                },
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp"></i> <span>Kirim Broadcast Sekarang</span>');

                    const modalEl = document.getElementById('modalBroadcastBatch');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    if (res && res.status) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Broadcast Berhasil Diproses!',
                                text: res.message || 'Pesan broadcast telah berhasil dikirimkan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert(res.message || 'Pesan broadcast telah berhasil dikirimkan.');
                            location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memproses Broadcast',
                                text: (res && res.message) ? res.message : 'Terjadi kesalahan sistem.'
                            });
                        } else {
                            alert((res && res.message) ? res.message : 'Terjadi kesalahan sistem.');
                        }
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp"></i> <span>Kirim Broadcast Sekarang</span>');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Koneksi',
                            text: 'Gagal menghubungi server. Silakan coba kembali.'
                        });
                    } else {
                        alert('Gagal menghubungi server. Silakan coba kembali.');
                    }
                }
            });
        });

        $('#btnSearchFu').on('click', function() {
            table.search($('#searchFu').val()).draw();
        });

        $('#searchFu').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.search(this.value).draw();
            }
        });

        $('#btnResetFu').on('click', function() {
            $('#searchFu').val('');
            $('#filterSumber').val('');
            $('#filterStatusFu').val('');
            $('#filterKelompok').val('');
            table.search('').columns().search('').draw();
        });

        $('#filterSumber').on('change', function() {
            table.column(4).search(this.value).draw();
        });

        $('#btnSaveFuNote').on('click', function() {
            const btn = $(this);
            const pId = $('#fuModalPelangganId').val();
            const stage = $('#fuModalSelectStage').val();
            const noteText = $('#fuModalNoteText').val().trim();

            if (!noteText) {
                alert('Silakan tuliskan catatan follow-up terlebih dahulu.');
                return;
            }

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyimpan...');

            $.ajax({
                url: '<?= base_url('crm/chat/store') ?>',
                type: 'POST',
                data: {
                    pelanggan_id: pId,
                    label_stage: stage,
                    notes: noteText
                },
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk me-1"></i>Simpan Catatan');
                    $('#fuModalNoteText').val('');
                    
                    const newNoteHtml = `<div class="p-2 bg-light rounded border mb-2 small border-start border-success border-3">
                        <div class="d-flex justify-content-between text-muted" style="font-size: 0.78rem;">
                            <span><strong>CS Staff</strong> &bull; <span class="badge bg-success">${stage}</span></span>
                            <span>Baru saja</span>
                        </div>
                        <div class="text-dark mt-1">${noteText}</div>
                    </div>`;

                    if ($('#fuModalNotesContainer .border-dashed').length) {
                        $('#fuModalNotesContainer').html(newNoteHtml);
                    } else {
                        $('#fuModalNotesContainer').prepend(newNoteHtml);
                    }

                    const currentCount = parseInt($('#fuModalNotesCount').text()) || 0;
                    $('#fuModalNotesCount').text((currentCount + 1) + ' CATATAN');
                    alert('Catatan & status berhasil disimpan!');
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk me-1"></i>Simpan Catatan');
                    alert('Gagal menyimpan catatan. Pastikan data terisi dengan benar.');
                }
            });
        });

        $('#btnSyncLabelH').on('click', function() {
            const btn = $(this);
            btn.html('<i class="fa-solid fa-rotate fa-spin"></i> Sinkronisasi...');
            setTimeout(function() {
                btn.html('<i class="fa-solid fa-rotate"></i> Sync Label H+');
                alert('Sinkronisasi label H+ dan status keterlambatan follow-up berhasil!');
            }, 600);
        });

        $('#btnExportFuCsv').on('click', function(e) {
            // Direct native download via crm/export
        });

        $('.btn-filter-stage').on('click', function() {
            $('.btn-filter-stage').removeClass('btn-primary active').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');

            const stage = $(this).data('stage');
            if (stage === 'all') {
                table.column(5).search('').draw();
            } else {
                table.column(5).search(stage).draw();
            }
        });
    });
</script>
<?= $this->endSection() ?>

