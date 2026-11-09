<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Notification Center Specific Styling */
    .notif-card-item {
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.15s ease;
    }
    .notif-card-item:hover {
        background-color: #f8fafc !important;
    }
    .notif-card-item.is-unread {
        border-left: 4px solid #2D6A4F !important;
        background-color: #f4fbf7 !important;
    }
    .notif-card-item.is-unread:hover {
        background-color: #edf7f1 !important;
    }
    .stat-card-clean {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-clean:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06);
    }
    .stat-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
</style>

<div class="container-fluid px-0">
    <!-- TOP FOREST GREEN BANNER -->
    <div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                <div class="flex-grow-1">
                    <div class="small text-white-50"><i class="fa-solid fa-bell me-1"></i>Sistem ERP &gt; Pusat Notifikasi &amp; Alert</div>
                    <h3 class="fw-bold text-white mb-1 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-bell"></i>
                        <span>Pusat Notifikasi &amp; Alert Sistem</span>
                    </h3>
                    <p class="text-white-50 small mb-0">Pantau peringatan stok menipis, piutang jatuh tempo, approval calon pelanggan, CRM follow-up, dan log transaksi operasional.</p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto flex-shrink-0 align-self-end align-self-md-center text-nowrap">
                    <button type="button" class="btn btn-sm btn-light text-success fw-bold rounded-pill shadow-sm px-3 d-inline-flex align-items-center gap-2" id="btnMarkAllReadHeader">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Tandai Semua Dibaca</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill shadow-sm px-3 d-inline-flex align-items-center gap-2" id="btnClearAllHeader">
                        <i class="fa-regular fa-trash-can"></i>
                        <span>Bersihkan Semua</span>
                    </button>
                </div>
            </div>

            <!-- SUB-NAVIGATION / CATEGORY PILLS -->
            <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
                <?php 
                    $currStatusParam = ($status !== 'all') ? '&status=' . urlencode($status) : '';
                    $currSearchParam = (!empty($search)) ? '&q=' . urlencode($search) : '';
                ?>
                <a href="<?= base_url('notifications?category=all' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'all' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    Semua <span class="badge <?= $category === 'all' ? 'bg-success text-white' : 'bg-white bg-opacity-25 text-white' ?> ms-1" id="badgeBannerTotal"><?= number_format($totalAll) ?></span>
                </a>
                <a href="<?= base_url('notifications?category=stok' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'stok' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    <i class="fa-solid fa-box-open me-1"></i>Stok
                </a>
                <a href="<?= base_url('notifications?category=keuangan' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'keuangan' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i>Keuangan
                </a>
                <a href="<?= base_url('notifications?category=approval' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'approval' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    <i class="fa-solid fa-user-check me-1"></i>Approval
                </a>
                <a href="<?= base_url('notifications?category=crm' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'crm' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    <i class="fa-solid fa-calendar-check me-1"></i>CRM
                </a>
                <a href="<?= base_url('notifications?category=penjualan' . $currStatusParam . $currSearchParam) ?>" class="btn btn-sm <?= $category === 'penjualan' ? 'bg-white text-dark fw-bold shadow-sm' : 'btn-outline-light' ?> rounded-pill px-3 py-1">
                    <i class="fa-solid fa-cart-shopping me-1"></i>Penjualan
                </a>
            </div>
        </div>
    </div>

    <!-- 4 STAT CARDS ROW -->
    <div class="row g-2 g-sm-3 mb-4">
        <!-- 1. Total Notifikasi -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="stat-card-clean d-flex align-items-center justify-content-between p-3 h-100 shadow-sm">
                <div class="pe-1 min-w-0 flex-grow-1">
                    <span class="text-muted text-uppercase d-block text-truncate" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;">TOTAL NOTIFIKASI</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0 fs-4" id="statTotalVal"><?= number_format($totalAll) ?></h3>
                    <small class="text-muted d-none d-sm-block text-truncate" style="font-size: 0.75rem;">Semua riwayat sistem</small>
                </div>
                <div class="stat-icon-box badge-soft-info flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-bell fs-5"></i>
                </div>
            </div>
        </div>

        <!-- 2. Belum Dibaca -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="stat-card-clean d-flex align-items-center justify-content-between p-3 h-100 shadow-sm">
                <div class="pe-1 min-w-0 flex-grow-1">
                    <span class="text-muted text-uppercase d-block text-truncate" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;">BELUM DIBACA</span>
                    <h3 class="fw-bold text-warning mt-1 mb-0 fs-4" id="statUnreadVal"><?= number_format($unreadCount) ?></h3>
                    <small class="text-muted d-none d-sm-block text-truncate" style="font-size: 0.75rem;">Perlu ditinjau / aksi</small>
                </div>
                <div class="stat-icon-box badge-soft-warning flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-envelope-open-text fs-5"></i>
                </div>
            </div>
        </div>

        <!-- 3. Kritis / Warning -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="stat-card-clean d-flex align-items-center justify-content-between p-3 h-100 shadow-sm">
                <div class="pe-1 min-w-0 flex-grow-1">
                    <span class="text-muted text-uppercase d-block text-truncate" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;">PERINGATAN KRITIS</span>
                    <h3 class="fw-bold text-danger mt-1 mb-0 fs-4" id="statCriticalVal"><?= number_format($criticalCount) ?></h3>
                    <small class="text-muted d-none d-sm-block text-truncate" style="font-size: 0.75rem;">Stok &amp; piutang jatuh tempo</small>
                </div>
                <div class="stat-icon-box badge-soft-danger flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                </div>
            </div>
        </div>

        <!-- 4. Sudah Ditinjau -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="stat-card-clean d-flex align-items-center justify-content-between p-3 h-100 shadow-sm">
                <div class="pe-1 min-w-0 flex-grow-1">
                    <span class="text-muted text-uppercase d-block text-truncate" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;">SUDAH DITINJAU</span>
                    <h3 class="fw-bold text-success mt-1 mb-0 fs-4" id="statReadVal"><?= number_format($readCount) ?></h3>
                    <small class="text-muted d-none d-sm-block text-truncate" style="font-size: 0.75rem;">Telah selesai diproses</small>
                </div>
                <div class="stat-icon-box badge-soft-success flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="<?= base_url('notifications') ?>" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Cari notifikasi atau pesan..." value="<?= esc($search) ?>">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select form-select-sm">
                        <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>-- Semua Kategori --</option>
                        <option value="stok" <?= $category === 'stok' ? 'selected' : '' ?>>Manajemen Stok</option>
                        <option value="keuangan" <?= $category === 'keuangan' ? 'selected' : '' ?>>Keuangan &amp; Kas (AR/AP)</option>
                        <option value="approval" <?= $category === 'approval' ? 'selected' : '' ?>>Approval Calon Customer</option>
                        <option value="crm" <?= $category === 'crm' ? 'selected' : '' ?>>CRM &amp; Follow-Up</option>
                        <option value="penjualan" <?= $category === 'penjualan' ? 'selected' : '' ?>>Penjualan &amp; Marketplace</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>-- Semua Status --</option>
                        <option value="unread" <?= $status === 'unread' ? 'selected' : '' ?>>Belum Dibaca</option>
                        <option value="read" <?= $status === 'read' ? 'selected' : '' ?>>Sudah Dibaca</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <?php if ($category !== 'all' || $status !== 'all' || !empty($search)): ?>
                        <a href="<?= base_url('notifications') ?>" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- NOTIFICATIONS LIST CONTAINER -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check text-success"></i>
                <span>Daftar Pemberitahuan Sistem</span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-2 py-1" id="badgeListCount">
                    <?= count($notifications) ?> Notifikasi Ditampilkan
                </span>
            </div>
        </div>

        <div class="list-group list-group-flush" id="notificationsListGroup">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5" id="emptyStateBox">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle mb-3" style="width: 70px; height: 70px;">
                        <i class="fa-regular fa-bell-slash fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Tidak ada notifikasi yang ditemukan</h6>
                    <p class="text-muted small mb-0">Semua tugas dan peringatan operasional berjalan lancar.</p>
                    <?php if ($category !== 'all' || $status !== 'all' || !empty($search)): ?>
                        <div class="mt-3">
                            <a href="<?= base_url('notifications') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fa-solid fa-rotate-right me-1"></i> Reset Filter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <?php 
                        $badgeClass = match($n['type']) {
                            'danger'  => 'badge-soft-danger border border-danger-subtle',
                            'warning' => 'badge-soft-warning border border-warning-subtle',
                            'success' => 'badge-soft-success border border-success-subtle',
                            'primary' => 'badge-soft-primary border border-primary-subtle',
                            default   => 'badge-soft-info border border-info-subtle',
                        };

                        $iconBgClass = match($n['type']) {
                            'danger'  => 'badge-soft-danger',
                            'warning' => 'badge-soft-warning',
                            'success' => 'badge-soft-success',
                            'primary' => 'badge-soft-primary',
                            default   => 'badge-soft-info',
                        };
                    ?>
                    <div class="list-group-item p-3 notif-card-item <?= $n['is_read'] ? 'bg-white' : 'is-unread' ?>" id="notif-item-<?= $n['id'] ?>" data-id="<?= $n['id'] ?>" data-is-read="<?= $n['is_read'] ? '1' : '0' ?>" data-type="<?= esc($n['type']) ?>">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Icon Box -->
                            <div class="stat-icon-box <?= $iconBgClass ?> flex-shrink-0" style="width: 44px; height: 44px;">
                                <i class="<?= esc($n['icon']) ?> fs-5"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge <?= $badgeClass ?> text-uppercase px-2 py-1" style="font-size: 0.68rem; letter-spacing: 0.5px; border-radius: 6px;">
                                            <?= esc($n['category']) ?>
                                        </span>
                                        <h6 class="mb-0 fw-bold notif-item-title <?= $n['is_read'] ? 'text-dark' : 'text-success' ?>" style="font-size: 0.95rem;">
                                            <?= esc($n['title']) ?>
                                        </h6>
                                        <?php if (!$n['is_read']): ?>
                                            <span class="badge bg-warning text-dark fw-bold badge-baru" style="font-size: 0.65rem; border-radius: 50rem;">BARU</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted d-flex align-items-center gap-1 flex-shrink-0">
                                        <i class="fa-regular fa-clock" style="font-size: 0.75rem;"></i>
                                        <span><?= esc($n['time_ago']) ?></span>
                                    </small>
                                </div>

                                <p class="text-secondary small mb-2 text-break" style="line-height: 1.45;"><?= esc($n['message']) ?></p>

                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <?php if (!empty($n['url']) && $n['url'] !== '#'): ?>
                                        <a href="<?= esc($n['url']) ?>" class="btn btn-sm btn-primary py-1 px-3 d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; border-radius: 6px;">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Buka Halaman</span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!$n['is_read']): ?>
                                        <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-mark-read d-inline-flex align-items-center gap-1" data-id="<?= $n['id'] ?>" style="font-size: 0.8rem; border-radius: 6px;" title="Tandai telah dibaca">
                                            <i class="fa-solid fa-check text-success"></i>
                                            <span>Tandai Dibaca</span>
                                        </button>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-sm btn-link text-muted p-1 ms-auto btn-delete-notif" data-id="<?= $n['id'] ?>" title="Hapus Notifikasi">
                                        <i class="fa-regular fa-trash-can text-danger opacity-75"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // CSRF protection setup for AJAX
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    function updateCsrf(newHash) {
        if (newHash) {
            csrfHash = newHash;
        }
    }

    // Helper to refresh topbar notification badge
    function syncTopbarBadge(count, label) {
        const badge = $('#notifBadge');
        const countTxt = $('#notifDropdownCount');
        const sideCount = $('.sidebar-notif-count');

        if (count > 0) {
            badge.text(label).show();
            countTxt.text(count + ' Baru');
            sideCount.text(label).show();
        } else {
            badge.text('0').hide();
            countTxt.text('0 Baru');
            sideCount.text('0').hide();
        }
    }

    // Mark single notification as read
    $(document).on('click', '.btn-mark-read', function(e) {
        e.preventDefault();
        const btn = $(this);
        const notifId = btn.data('id');
        const itemRow = $('#notif-item-transform, #notif-item-' + notifId);

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?= base_url('notifications/mark-read') ?>/' + notifId,
            method: 'POST',
            data: { [csrfName]: csrfHash },
            dataType: 'json',
            success: function(res) {
                if (res.csrf) updateCsrf(res.csrf);
                if (res.success) {
                    // Update Item Visual
                    itemRow.removeClass('is-unread').addClass('bg-white');
                    itemRow.find('.notif-item-title').removeClass('text-success').addClass('text-dark');
                    itemRow.find('.badge-baru').fadeOut(200, function() { $(this).remove(); });
                    btn.fadeOut(200, function() { $(this).remove(); });
                    itemRow.attr('data-is-read', '1');

                    // Update Stat Cards Counters
                    let unreadVal = parseInt($('#statUnreadVal').text().replace(/,/g, '')) || 0;
                    let readVal = parseInt($('#statReadVal').text().replace(/,/g, '')) || 0;

                    if (unreadVal > 0) {
                        unreadVal--;
                        $('#statUnreadVal').text(unreadVal.toLocaleString());
                    }
                    readVal++;
                    $('#statReadVal').text(readVal.toLocaleString());

                    // If item was critical, decrement critical stat
                    if (['danger', 'warning'].includes(itemRow.data('type'))) {
                        let critVal = parseInt($('#statCriticalVal').text().replace(/,/g, '')) || 0;
                        if (critVal > 0) {
                            critVal--;
                            $('#statCriticalVal').text(critVal.toLocaleString());
                        }
                    }

                    // Sync Topbar
                    syncTopbarBadge(res.unread_count, res.unread_label);

                    if (window.notifySuccess) {
                        notifySuccess(res.message);
                    }
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check text-success"></i> <span>Tandai Dibaca</span>');
                if (window.notifyError) {
                    notifyError('Gagal menandai notifikasi dibaca.');
                }
            }
        });
    });

    // Delete single notification
    $(document).on('click', '.btn-delete-notif', function(e) {
        e.preventDefault();
        const btn = $(this);
        const notifId = btn.data('id');
        const itemRow = $('#notif-item-' + notifId);
        const wasUnread = itemRow.attr('data-is-read') === '0';

        Swal.fire({
            title: 'Hapus notifikasi?',
            text: 'Pemberitahuan ini akan dihapus dari riwayat.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('notifications/delete') ?>/' + notifId,
                    method: 'POST',
                    data: { [csrfName]: csrfHash },
                    dataType: 'json',
                    success: function(res) {
                        if (res.csrf) updateCsrf(res.csrf);
                        if (res.success) {
                            itemRow.slideUp(250, function() {
                                $(this).remove();

                                // Check if list is empty
                                const remaining = $('.notif-card-item').length;
                                $('#badgeListCount').text(remaining + ' Notifikasi Ditampilkan');
                                if (remaining === 0) {
                                    $('#notificationsListGroup').html(`
                                        <div class="text-center py-5">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle mb-3" style="width: 70px; height: 70px;">
                                                <i class="fa-regular fa-bell-slash fs-2"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">Tidak ada notifikasi yang ditemukan</h6>
                                            <p class="text-muted small mb-0">Semua tugas dan peringatan operasional berjalan lancar.</p>
                                        </div>
                                    `);
                                }
                            });

                            // Decrement Total stat
                            let totalVal = parseInt($('#statTotalVal').text().replace(/,/g, '')) || 0;
                            if (totalVal > 0) {
                                totalVal--;
                                $('#statTotalVal').text(totalVal.toLocaleString());
                                $('#badgeBannerTotal').text(totalVal.toLocaleString());
                            }

                            // If it was unread, decrement unread stat
                            if (wasUnread) {
                                let unreadVal = parseInt($('#statUnreadVal').text().replace(/,/g, '')) || 0;
                                if (unreadVal > 0) {
                                    unreadVal--;
                                    $('#statUnreadVal').text(unreadVal.toLocaleString());
                                }
                            } else {
                                let readVal = parseInt($('#statReadVal').text().replace(/,/g, '')) || 0;
                                if (readVal > 0) {
                                    readVal--;
                                    $('#statReadVal').text(readVal.toLocaleString());
                                }
                            }

                            syncTopbarBadge(res.unread_count, res.unread_label);

                            if (window.notifySuccess) {
                                notifySuccess(res.message);
                            }
                        }
                    },
                    error: function() {
                        if (window.notifyError) {
                            notifyError('Gagal menghapus notifikasi.');
                        }
                    }
                });
            }
        });
    });

    // Mark All Read Header Action
    $('#btnMarkAllReadHeader').on('click', function(e) {
        e.preventDefault();
        const unreadRows = $('.notif-card-item.is-unread');
        if (unreadRows.length === 0) {
            if (window.notifyInfo) {
                notifyInfo('Semua notifikasi sudah dalam status dibaca.');
            }
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: '<?= base_url('notifications/mark-all-read') ?>',
            method: 'POST',
            data: { [csrfName]: csrfHash },
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check-double"></i> <span>Tandai Semua Dibaca</span>');
                if (res.csrf) updateCsrf(res.csrf);
                if (res.success) {
                    // Update all unread cards
                    $('.notif-card-item.is-unread').removeClass('is-unread').addClass('bg-white');
                    $('.notif-item-title').removeClass('text-success').addClass('text-dark');
                    $('.badge-baru').remove();
                    $('.btn-mark-read').remove();
                    $('.notif-card-item').attr('data-is-read', '1');

                    // Recalculate stats
                    let unreadVal = parseInt($('#statUnreadVal').text().replace(/,/g, '')) || 0;
                    let readVal = parseInt($('#statReadVal').text().replace(/,/g, '')) || 0;
                    readVal += unreadVal;

                    $('#statUnreadVal').text('0');
                    $('#statReadVal').text(readVal.toLocaleString());
                    $('#statCriticalVal').text('0');

                    syncTopbarBadge(0, '0');

                    if (window.notifySuccess) {
                        notifySuccess(res.message);
                    }
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check-double"></i> <span>Tandai Semua Dibaca</span>');
                if (window.notifyError) {
                    notifyError('Gagal menandai seluruh notifikasi.');
                }
            }
        });
    });

    // Clear All Notifications Header Action
    $('#btnClearAllHeader').on('click', function(e) {
        e.preventDefault();
        const items = $('.notif-card-item');
        if (items.length === 0) {
            if (window.notifyInfo) {
                notifyInfo('Tidak ada notifikasi yang tersimpan.');
            }
            return;
        }

        Swal.fire({
            title: 'Bersihkan Semua Notifikasi?',
            text: 'Seluruh riwayat pemberitahuan akan dihapus permanen dari sistem.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Bersihkan Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $('#btnClearAllHeader');
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Membersihkan...');

                $.ajax({
                    url: '<?= base_url('notifications/clear-all') ?>',
                    method: 'POST',
                    data: { [csrfName]: csrfHash },
                    dataType: 'json',
                    success: function(res) {
                        btn.prop('disabled', false).html('<i class="fa-regular fa-trash-can"></i> <span>Bersihkan Semua</span>');
                        if (res.csrf) updateCsrf(res.csrf);
                        if (res.success) {
                            $('#notificationsListGroup').html(`
                                <div class="text-center py-5">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle mb-3" style="width: 70px; height: 70px;">
                                        <i class="fa-regular fa-bell-slash fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark">Tidak ada notifikasi yang ditemukan</h6>
                                    <p class="text-muted small mb-0">Semua tugas dan peringatan operasional berjalan lancar.</p>
                                </div>
                            `);

                            $('#badgeListCount').text('0 Notifikasi Ditampilkan');
                            $('#statTotalVal').text('0');
                            $('#statUnreadVal').text('0');
                            $('#statCriticalVal').text('0');
                            $('#statReadVal').text('0');
                            $('#badgeBannerTotal').text('0');

                            syncTopbarBadge(0, '0');

                            if (window.notifySuccess) {
                                notifySuccess(res.message);
                            }
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<i class="fa-regular fa-trash-can"></i> <span>Bersihkan Semua</span>');
                        if (window.notifyError) {
                            notifyError('Gagal membersihkan riwayat notifikasi.');
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
