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
        #bulkGroupAction,
        #btnGroupBulkApply,
        .card-header .d-flex.align-items-center:last-child,
        .card-body.border-bottom,
        #groupSearchFilter,
        #btnGroupReset,
        th:first-child,
        td:first-child,
        th:last-child,
        td:last-child,
        .d-flex.gap-2.flex-wrap,
        #debug-icon,
        #debug-bar,
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

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table th, .table td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px 10px !important;
        }

        .table thead {
            background-color: #1A2E23 !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Grup &amp; Peran Pengguna</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                <?= count($roles) ?> Peran Terdaftar
            </span>
        </div>
        <p class="text-muted small mb-0">
            Kelola struktur level kewenangan, hak akses departemen, dan penugasan peranan pengguna.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('pengaturan/access') ?>" class="btn btn-sm btn-outline-primary text-dark fw-semibold px-3 bg-white border shadow-xs">
            <i class="fa-solid fa-shield-halved text-primary me-1"></i>Matriks Hak Akses
        </a>
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddGroup">
            <i class="fa-solid fa-plus me-1"></i>Tambah Grup Baru
        </button>
        <a href="<?= base_url('pengaturan/groups/export') ?>" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-excel text-success me-1"></i>Export XLS
        </a>
        <a href="<?= base_url('pengaturan/groups/export/pdf') ?>" target="_blank" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-pdf text-danger me-1"></i>Export PDF
        </a>
    </div>
</div>
<!-- GROUP REGISTER CARD -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <!-- CARD HEADER & TOOLBAR -->
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-user-shield fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Peran &amp; Hak Akses</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Struktur otoritas sistem dan klasifikasi hak pengguna</div>
            </div>
        </div>

        <!-- BULK ACTIONS -->
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" id="bulkGroupAction" style="width: 140px;">
                <option value="">Aksi Massal...</option>
                <option value="delete">Hapus Terpilih</option>
            </select>
            <button type="button" class="btn btn-light border btn-sm fw-semibold" id="btnGroupBulkApply">
                Terapkan
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH ROW -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="groupSearchFilter" class="form-control border-start-0" placeholder="Cari nama grup, kode, atau definisi...">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnGroupReset" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="groupTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.73rem;">
                        <th class="ps-3 py-3" style="width: 40px;"><input type="checkbox" id="selectAllGroups" class="form-check-input"></th>
                        <th style="width: 70px;">ID</th>
                        <th>Nama Grup / Peran</th>
                        <th>Definisi &amp; Deskripsi Akses</th>
                        <th class="text-center">Jumlah Anggota</th>
                        <th class="pe-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $g): ?>
                            <tr>
                                <td class="ps-3"><input type="checkbox" class="form-check-input group-check-item" value="<?= $g['id'] ?>"></td>
                                <td class="font-monospace small text-muted">#<?= $g['id'] ?></td>
                                <td>
                                    <div class="fw-semibold text-dark small"><?= esc($g['display_name']) ?></div>
                                    <span class="font-monospace text-muted small" style="font-size: 0.78rem;">@<?= esc($g['name']) ?></span>
                                </td>
                                <td class="small text-secondary"><?= esc($g['description'] ?? '-') ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 small rounded-pill">
                                        <i class="fa-solid fa-user me-1"></i><?= (int) ($g['user_count'] ?? 0) ?> Anggota
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-light border text-primary p-1 px-2 btn-group-see"
                                            data-id="<?= $g['id'] ?>"
                                            data-name="<?= esc($g['name']) ?>"
                                            data-display="<?= esc($g['display_name']) ?>"
                                            data-desc="<?= esc($g['description'] ?? '-') ?>"
                                            data-count="<?= (int) ($g['user_count'] ?? 0) ?>"
                                            title="Lihat Detail Grup">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-secondary p-1 px-2 btn-group-edit"
                                            data-id="<?= $g['id'] ?>"
                                            data-name="<?= esc($g['name']) ?>"
                                            data-display="<?= esc($g['display_name']) ?>"
                                            data-desc="<?= esc($g['description'] ?? '') ?>"
                                            title="Ubah Detail Grup">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2 btn-group-delete"
                                            data-id="<?= $g['id'] ?>"
                                            data-name="<?= esc($g['display_name']) ?>"
                                            title="Hapus Grup">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small">Belum ada grup yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                Menampilkan <strong class="text-dark"><?= count($roles) ?></strong> grup / peran hak akses
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- MODAL ADD GROUP -->
<div class="modal fade" id="modalAddGroup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-user-shield me-2"></i>Tambah Grup Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('pengaturan/groups/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Grup / Peran <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm font-monospace" placeholder="e.g. sales_executive" required>
                        <small class="text-muted" style="font-size: 0.78rem;">Gunakan format huruf kecil dan garis bawah (snake_case).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Tampilan (Display Name) <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control form-control-sm" placeholder="e.g. Sales Executive" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Deskripsi &amp; Cakupan Wewenang</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Jelaskan wewenang, batasan, dan peran operasional grup ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Grup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW GROUP -->
<div class="modal fade" id="modalViewGroup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Detail Grup &amp; Peran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; font-size: 22px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" id="viewGroupDisplay">-</h5>
                        <code class="font-monospace text-primary small" id="viewGroupName">-</code>
                    </div>
                </div>
                <div class="row g-3 mb-3 small">
                    <div class="col-6">
                        <div class="text-muted">ID Grup:</div>
                        <div class="font-monospace fw-bold text-dark" id="viewGroupId">-</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted">Total Anggota:</div>
                        <div class="fw-bold text-primary" id="viewGroupCount">-</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small mb-1">Deskripsi &amp; Kewenangan:</label>
                    <div class="text-secondary small bg-light p-3 rounded border" id="viewGroupDesc">-</div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT GROUP -->
<div class="modal fade" id="modalEditGroup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Ubah Detail Grup</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditGroup" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kode Grup</label>
                        <input type="text" id="editGroupName" class="form-control form-control-sm font-monospace bg-light" readonly>
                        <small class="text-muted" style="font-size: 0.78rem;">Kode internal sistem tidak dapat diubah.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Tampilan (Display Name) <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" id="editGroupDisplay" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Deskripsi Kewenangan</label>
                        <textarea name="description" id="editGroupDesc" class="form-control form-control-sm" rows="3"></textarea>
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
<form id="formDeleteGroup" method="POST" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#groupTable tbody tr');
    const searchInput = document.getElementById('groupSearchFilter');
    const resetBtn = document.getElementById('btnGroupReset');

    // Filter
    function applyGroupFilter() {
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        tableRows.forEach(row => {
            const txt = row.textContent.toLowerCase();
            row.style.display = (!q || txt.includes(q)) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyGroupFilter);
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            tableRows.forEach(r => r.style.display = '');
        });
    }

    // Select All
    const selectAllCb = document.getElementById('selectAllGroups');
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            document.querySelectorAll('.group-check-item').forEach(cb => {
                cb.checked = selectAllCb.checked;
            });
        });
    }

    // See Modal
    document.querySelectorAll('.btn-group-see').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewGroupId').textContent = '#' + this.dataset.id;
            document.getElementById('viewGroupName').textContent = this.dataset.name;
            document.getElementById('viewGroupDisplay').textContent = this.dataset.display;
            document.getElementById('viewGroupDesc').textContent = this.dataset.desc;
            document.getElementById('viewGroupCount').textContent = this.dataset.count + ' Anggota';
            new bootstrap.Modal(document.getElementById('modalViewGroup')).show();
        });
    });

    // Edit Modal
    document.querySelectorAll('.btn-group-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('formEditGroup');
            form.action = '<?= base_url('pengaturan/groups/update') ?>/' + this.dataset.id;
            document.getElementById('editGroupName').value = this.dataset.name;
            document.getElementById('editGroupDisplay').value = this.dataset.display;
            document.getElementById('editGroupDesc').value = this.dataset.desc;
            new bootstrap.Modal(document.getElementById('modalEditGroup')).show();
        });
    });

    // Delete
    document.querySelectorAll('.btn-group-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin menghapus group '${name}'? Anggota group akan dialihkan ke group default.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((r) => {
                if (r.isConfirmed) {
                    const form = document.getElementById('formDeleteGroup');
                    form.action = '<?= base_url('pengaturan/groups/delete') ?>/' + id;
                    form.submit();
                }
            });
        });
    });

    // Bulk Delete
    const btnBulk = document.getElementById('btnGroupBulkApply');
    if (btnBulk) {
        btnBulk.addEventListener('click', function() {
            const action = document.getElementById('bulkGroupAction').value;
            const checked = Array.from(document.querySelectorAll('.group-check-item:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal 1 grup untuk menerapkan aksi.' });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Hapus ${checked.length} grup yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0392B',
                    cancelButtonColor: '#8DA398',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) {
                        const form = document.getElementById('formDeleteGroup');
                        form.action = '<?= base_url('pengaturan/groups/delete') ?>/' + checked[0];
                        form.submit();
                    }
                });
            } else {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tindakan massal yang valid terlebih dahulu.' });
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
