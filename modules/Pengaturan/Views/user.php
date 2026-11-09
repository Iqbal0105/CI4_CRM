<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @media print {
        #sidebar,
        .topbar,
        .app-header,
        header,
        nav,
        .btn,
        .modal,
        .dataTables_filter,
        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate,
        .stat-card-clean,
        .row.g-3.mb-4,
        .d-flex.gap-2.flex-wrap,
        th:last-child,
        td:last-child,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history {
            display: none !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 10pt !important;
            padding: 10mm !important;
            margin: 0 !important;
        }

        .main-content, .content-wrapper, .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .card-header {
            background: none !important;
            padding-left: 0 !important;
            border-bottom: 2px solid #2D6A4F !important;
        }

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table th {
            background-color: #f1f5f9 !important;
            color: #000000 !important;
            border-top: 1px solid #cbd5e1 !important;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .table td {
            border-bottom: 1px solid #e2e8f0 !important;
        }
    }
</style>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Manajemen Pengguna</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small" id="userCountBadge">
                <?= count($users) ?> Akun Terdaftar
            </span>
        </div>
        <p class="text-muted small mb-0">
            Kelola akun pengguna, peranan hak akses, dan status operasional sistem secara terpusat.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('pengaturan/approval') ?>" class="btn btn-sm btn-outline-warning text-dark fw-semibold px-3 bg-white border shadow-xs">
            <i class="fa-solid fa-user-check text-warning me-1"></i>Approval Calon Customer
        </a>
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddUser">
            <i class="fa-solid fa-user-plus me-1"></i>Tambah Pengguna
        </button>
        <a href="<?= base_url('pengaturan/user/export') ?>" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-excel text-success me-1"></i>Export XLS
        </a>
        <a href="<?= base_url('pengaturan/user/export/pdf') ?>" target="_blank" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-pdf text-danger me-1"></i>Export PDF
        </a>
    </div>
</div>
<?php
$activeUserCount = 0;
$inactiveUserCount = 0;
foreach ($users as $u) {
    if (($u['status'] ?? 'active') === 'active') {
        $activeUserCount++;
    } else {
        $inactiveUserCount++;
    }
}
?>

<!-- 4 STAT METRIC CARDS -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #E8F0EB; color: #2D6A4F;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary small fw-semibold px-2 py-1 rounded-pill">Total</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.04em;">TOTAL PENGGUNA</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-dark font-sans"><?= count($users) ?></span>
                <span class="small text-muted">Akun</span>
            </div>
            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.75rem;">
                <i class="fa-solid fa-shield-halved text-primary me-1"></i>Terdaftar di database
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #ecfdf5; color: #059669;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success small fw-semibold px-2 py-1 rounded-pill">Aktif</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.04em;">PENGGUNA AKTIF</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-success font-sans"><?= $activeUserCount ?></span>
                <span class="small text-muted">Akun</span>
            </div>
            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.75rem;">
                <i class="fa-solid fa-circle-check text-success me-1"></i>Memiliki akses login penuh
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <span class="badge bg-danger bg-opacity-10 text-danger small fw-semibold px-2 py-1 rounded-pill">Nonaktif</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.04em;">NONAKTIF / SUSPEND</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-danger font-sans"><?= $inactiveUserCount ?></span>
                <span class="small text-muted">Akun</span>
            </div>
            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.75rem;">
                <i class="fa-solid fa-lock text-danger me-1"></i>Akses login sementara dibatasi
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-clean">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="stat-icon-box" style="background-color: #f5f3ff; color: #7c3aed;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <span class="badge bg-purple bg-opacity-10 text-purple small fw-semibold px-2 py-1 rounded-pill" style="color: #7c3aed; background: #f5f3ff;">Role</span>
            </div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.04em;">TOTAL GRUP &amp; PERAN</div>
            <div class="d-flex align-items-baseline gap-2 mt-1">
                <span class="fs-3 fw-bold text-dark font-sans"><?= count($roles) ?></span>
                <span class="small text-muted">Peran</span>
            </div>
            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.75rem;">
                <a href="<?= base_url('pengaturan/groups') ?>" class="text-decoration-none small text-secondary">
                    Kelola level hak akses <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CARD: DAFTAR PENGGUNA -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <!-- CARD HEADER & TOOLBAR -->
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-users-gear fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Akun Pengguna</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Daftar lengkap pengguna yang terotorisasi dalam sistem</div>
            </div>
        </div>

        <!-- BULK ACTIONS -->
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" id="bulkActionSelect" style="width: 140px;">
                <option value="">Aksi Massal...</option>
                <option value="delete">Hapus Terpilih</option>
            </select>
            <button type="button" class="btn btn-light border btn-sm fw-semibold" id="btnBulkApply">
                Terapkan
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="userSearchFilter" class="form-control border-start-0" placeholder="Cari nama, username, email...">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select form-select-sm" id="userRoleFilter">
                    <option value="all">Semua Peran / Role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= strtolower(esc($r['display_name'])) ?>"><?= esc($r['display_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select form-select-sm" id="userStatusFilter">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif (Active)</option>
                    <option value="inactive">Nonaktif (Inactive)</option>
                </select>
            </div>
            <div class="col-12 col-md-1 text-md-end">
                <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="btnUserReset" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="userTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.73rem;">
                        <th class="ps-3 py-3" style="width: 40px;"><input type="checkbox" id="selectAllUsers" class="form-check-input"></th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Peran / Group</th>
                        <th>Status</th>
                        <th class="pe-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php 
                        $avatarBgs = [
                            ['bg' => '#E8F0EB', 'color' => '#2D6A4F'],
                            ['bg' => '#ecfdf5', 'color' => '#059669'],
                            ['bg' => '#fffbeb', 'color' => '#d97706'],
                            ['bg' => '#f5f3ff', 'color' => '#7c3aed'],
                            ['bg' => '#fdf2f8', 'color' => '#db2777'],
                            ['bg' => '#f0fdfa', 'color' => '#0d9488'],
                        ];
                        ?>
                        <?php foreach ($users as $idx => $u): ?>
                            <?php 
                            $c = $avatarBgs[$idx % count($avatarBgs)];
                            $initial = strtoupper(substr($u['username'], 0, 1));
                            ?>
                            <tr data-user-id="<?= $u['id'] ?>" data-status="<?= esc($u['status'] ?? 'active') ?>" data-role="<?= strtolower(esc($u['display_name'] ?? $u['role_name'] ?? '')) ?>">
                                <td class="ps-3"><input type="checkbox" class="form-check-input user-check-item" value="<?= $u['id'] ?>"></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center" 
                                             style="width: 34px; height: 34px; font-size: 13px; background-color: <?= $c['bg'] ?>; color: <?= $c['color'] ?>;">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark small user-name"><?= esc($u['username']) ?></span>
                                            <div class="text-muted" style="font-size: 0.78rem;"><?= esc($u['email'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace text-secondary small user-username">@<?= esc($u['username']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small user-role"><?= esc($u['display_name'] ?? $u['role_name'] ?? 'Operational') ?></span>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                                        <input class="form-check-input btn-toggle-status cursor-pointer" type="checkbox" <?= ($u['status'] ?? 'active') === 'active' ? 'checked' : '' ?> id="statusUser<?= $u['id'] ?>" data-id="<?= $u['id'] ?>">
                                        <label class="form-check-label small status-label <?= ($u['status'] ?? 'active') === 'active' ? 'text-success fw-medium' : 'text-muted' ?>" for="statusUser<?= $u['id'] ?>">
                                            <?= ($u['status'] ?? 'active') === 'active' ? 'Active' : 'Not active' ?>
                                        </label>
                                    </div>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-light border text-primary p-1 px-2 btn-user-see"
                                            data-id="<?= $u['id'] ?>"
                                            data-username="<?= esc($u['username']) ?>"
                                            data-email="<?= esc($u['email'] ?? '') ?>"
                                            data-role="<?= esc($u['display_name'] ?? $u['role_name'] ?? 'General') ?>"
                                            data-status="<?= esc($u['status'] ?? 'active') ?>"
                                            data-created="<?= esc($u['created_at'] ?? '-') ?>"
                                            title="Lihat Detail Pengguna">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-secondary p-1 px-2 btn-user-edit"
                                            data-id="<?= $u['id'] ?>"
                                            data-username="<?= esc($u['username']) ?>"
                                            data-email="<?= esc($u['email'] ?? '') ?>"
                                            data-role-id="<?= $u['role_id'] ?? 1 ?>"
                                            data-status="<?= esc($u['status'] ?? 'active') ?>"
                                            title="Ubah Data Pengguna">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2 btn-user-delete"
                                            data-id="<?= $u['id'] ?>"
                                            data-username="<?= esc($u['username']) ?>"
                                            title="Hapus Pengguna">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small">
                                <i class="fa-solid fa-users text-secondary opacity-50 fs-2 d-block mb-2"></i>
                                Belum ada data pengguna yang terdaftar di sistem.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                Menampilkan <strong class="text-dark" id="displayCount"><?= count($users) ?></strong> akun pengguna terdaftar
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0)">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- MODAL ADD USER -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-user-plus me-2"></i>Tambah Pengguna Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('pengaturan/user/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control form-control-sm font-monospace" placeholder="e.g. staf_penjualan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Pengguna</label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="user@metafora.local">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Grup / Role Akses</label>
                        <select name="role_id" class="form-select form-select-sm">
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($r['display_name']) ?> (<?= esc($r['name']) ?>)</option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="1">Super Admin</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Status Operasional</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="active" selected>Aktif (Active)</option>
                            <option value="inactive">Nonaktif (Not active)</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Kata Sandi (Password)</label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="Masukkan password (min. 6 karakter)" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW USER -->
<div class="modal fade" id="modalViewUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-id-card me-2 text-primary"></i>Detail Akun Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 52px; height: 52px; font-size: 22px;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" id="viewUserUsername">-</h5>
                        <div class="text-muted small" id="viewUserEmail">-</div>
                    </div>
                </div>
                <div class="row g-3 small">
                    <div class="col-6">
                        <div class="text-muted">User ID:</div>
                        <div class="font-monospace fw-bold text-dark" id="viewUserId">-</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted">Role Group:</div>
                        <div class="fw-bold text-dark" id="viewUserRole">-</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted">Status:</div>
                        <div id="viewUserStatus">-</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted">Didaftarkan Pada:</div>
                        <div class="text-dark" id="viewUserCreated">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Ubah Data Pengguna</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditUser" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Username</label>
                        <input type="text" id="editUsername" class="form-control form-control-sm font-monospace bg-light" readonly>
                        <small class="text-muted" style="font-size: 0.78rem;">Username tidak dapat diubah.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Pengguna</label>
                        <input type="email" name="email" id="editEmail" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Grup / Role Akses</label>
                        <select name="role_id" id="editRoleId" class="form-select form-select-sm">
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($r['display_name']) ?> (<?= esc($r['name']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Status</label>
                        <select name="status" id="editStatus" class="form-select form-select-sm">
                            <option value="active">Aktif (Active)</option>
                            <option value="inactive">Nonaktif (Not active)</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Ganti Password (Kosongkan bila tidak diubah)</label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- HIDDEN DELETE FORM -->
<form id="formDeleteUser" method="POST" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#userTable tbody tr');
    const searchInput = document.getElementById('userSearchFilter');
    const roleSelect = document.getElementById('userRoleFilter');
    const statusSelect = document.getElementById('userStatusFilter');
    const resetBtn = document.getElementById('btnUserReset');
    const displayCountEl = document.getElementById('displayCount');

    // 1. Live Filter Function
    function applyUserFilter() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const roleFilter = roleSelect ? roleSelect.value : 'all';
        const statusFilter = statusSelect ? statusSelect.value : 'all';

        let count = 0;
        tableRows.forEach(row => {
            if (!row.dataset.userId) return;
            const text = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status;
            const rowRole = row.dataset.role || '';

            const matchQuery = !query || text.includes(query);
            const matchStatus = (statusFilter === 'all') || (rowStatus === statusFilter);
            const matchRole = (roleFilter === 'all') || rowRole.includes(roleFilter);

            if (matchQuery && matchStatus && matchRole) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        if (displayCountEl) displayCountEl.textContent = count;
    }

    if (searchInput) searchInput.addEventListener('input', applyUserFilter);
    if (roleSelect) roleSelect.addEventListener('change', applyUserFilter);
    if (statusSelect) statusSelect.addEventListener('change', applyUserFilter);
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (roleSelect) roleSelect.value = 'all';
            if (statusSelect) statusSelect.value = 'all';
            tableRows.forEach(r => r.style.display = '');
            if (displayCountEl) displayCountEl.textContent = tableRows.length;
        });
    }

    // 2. Select All Checkbox
    const selectAllCb = document.getElementById('selectAllUsers');
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            document.querySelectorAll('.user-check-item').forEach(cb => {
                cb.checked = selectAllCb.checked;
            });
        });
    }

    // 3. See Modal
    document.querySelectorAll('.btn-user-see').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewUserId').textContent = '#' + this.dataset.id;
            document.getElementById('viewUserUsername').textContent = this.dataset.username;
            document.getElementById('viewUserEmail').textContent = this.dataset.email || '-';
            document.getElementById('viewUserRole').textContent = this.dataset.role;
            document.getElementById('viewUserCreated').textContent = this.dataset.created;
            
            const statusEl = document.getElementById('viewUserStatus');
            if (this.dataset.status === 'active') {
                statusEl.innerHTML = '<span class="badge bg-success bg-opacity-10 text-success">Aktif</span>';
            } else {
                statusEl.innerHTML = '<span class="badge bg-secondary bg-opacity-10 text-secondary">Nonaktif</span>';
            }

            new bootstrap.Modal(document.getElementById('modalViewUser')).show();
        });
    });

    // 4. Edit Modal
    document.querySelectorAll('.btn-user-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('formEditUser');
            form.action = '<?= base_url('pengaturan/user/update') ?>/' + this.dataset.id;

            document.getElementById('editUsername').value = this.dataset.username;
            document.getElementById('editEmail').value = this.dataset.email;
            document.getElementById('editRoleId').value = this.dataset.roleId;
            document.getElementById('editStatus').value = this.dataset.status;

            new bootstrap.Modal(document.getElementById('modalEditUser')).show();
        });
    });

    // 5. Delete Action
    document.querySelectorAll('.btn-user-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const username = this.dataset.username;
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin menghapus pengguna '${username}'? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((r) => {
                if (r.isConfirmed) {
                    const form = document.getElementById('formDeleteUser');
                    form.action = '<?= base_url('pengaturan/user/delete') ?>/' + userId;
                    form.submit();
                }
            });
        });
    });

    // 6. Toggle Status Switch with AJAX & CSRF
    document.querySelectorAll('.btn-toggle-status').forEach(sw => {
        sw.addEventListener('change', function() {
            const userId = this.dataset.id;
            const label = this.closest('tr').querySelector('.status-label');
            const row = this.closest('tr');
            const isChecked = this.checked;

            fetch('<?= base_url('pengaturan/user/toggle-status') ?>/' + userId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const isNowActive = (data.new_status === 'active');
                    label.textContent = isNowActive ? 'Active' : 'Not active';
                    label.className = 'form-check-label small status-label ' + (isNowActive ? 'text-success fw-medium' : 'text-muted');
                    row.dataset.status = data.new_status;
                } else {
                    alert(data.message || 'Gagal mengubah status');
                    sw.checked = !isChecked;
                }
            })
            .catch(err => {
                label.textContent = isChecked ? 'Active' : 'Not active';
                label.className = 'form-check-label small status-label ' + (isChecked ? 'text-success fw-medium' : 'text-muted');
                row.dataset.status = isChecked ? 'active' : 'inactive';
            });
        });
    });

    // 7. Bulk Delete
    const btnBulk = document.getElementById('btnBulkApply');
    if (btnBulk) {
        btnBulk.addEventListener('click', function() {
            const action = document.getElementById('bulkActionSelect').value;
            const checked = Array.from(document.querySelectorAll('.user-check-item:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                alert('Pilih minimal 1 akun pengguna untuk menerapkan aksi bulk.');
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Hapus ${checked.length} pengguna yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0392B',
                    cancelButtonColor: '#8DA398',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) {
                        const form = document.getElementById('formDeleteUser');
                        form.action = '<?= base_url('pengaturan/user/delete') ?>/' + checked[0];
                        form.submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih tindakan massal yang valid terlebih dahulu.'
                });
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
