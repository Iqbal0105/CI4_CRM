<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Katalog Izin (Permissions)</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                <?= count($permList) ?> Izin Terdefinisi
            </span>
        </div>
        <p class="text-muted small mb-0">
            Daftar permission granular untuk mengendalikan visibilitas menu, tombol aksi, dan endpoint API.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('pengaturan/access') ?>" class="btn btn-sm btn-outline-primary text-dark fw-semibold px-3 bg-white border shadow-xs">
            <i class="fa-solid fa-shield-halved text-primary me-1"></i>Matriks Hak Akses
        </a>
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddPermission">
            <i class="fa-solid fa-plus me-1"></i>Tambah Izin Baru
        </button>
        <a href="<?= base_url('pengaturan/permission/export') ?>" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-excel text-success me-1"></i>Export XLS
        </a>
        <button type="button" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary" onclick="window.print()">
            <i class="fa-solid fa-file-pdf text-danger me-1"></i>Export PDF
        </button>
    </div>
</div>
<!-- PERMISSION REGISTER CARD -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <!-- CARD HEADER & TOOLBAR -->
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-key fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Hak Akses Sistem</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Otoritas fungsional modul, form CRUD, dan tombol operasional</div>
            </div>
        </div>

        <!-- BULK ACTIONS -->
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" id="bulkPermAction" style="width: 140px;">
                <option value="">Aksi Massal...</option>
                <option value="delete">Hapus Terpilih</option>
            </select>
            <button type="button" class="btn btn-light border btn-sm fw-semibold" id="btnBulkPermApply">
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
                    <input type="text" id="permSearchInput" class="form-control border-start-0" placeholder="Cari key izin, nama modul, atau definisi scope...">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnPermReset" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="permTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.73rem;">
                        <th class="ps-3 py-3" style="width: 40px;"><input type="checkbox" id="selectAllPerm" class="form-check-input"></th>
                        <th style="width: 80px;">ID</th>
                        <th>Permission Key (Internal)</th>
                        <th>Definisi / Cakupan Otoritas</th>
                        <th class="pe-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($permList)): ?>
                        <?php foreach ($permList as $p): ?>
                            <tr>
                                <td class="ps-3"><input type="checkbox" class="form-check-input perm-check-item" value="<?= $p['id'] ?>"></td>
                                <td class="font-monospace small text-muted">#<?= $p['id'] ?></td>
                                <td>
                                    <code class="font-monospace text-primary fw-semibold small bg-light px-2 py-1 rounded border">
                                        <?= esc($p['name']) ?>
                                    </code>
                                </td>
                                <td class="small text-secondary"><?= esc($p['def']) ?></td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-light border text-primary p-1 px-2 btn-perm-see"
                                            data-id="<?= $p['id'] ?>"
                                            data-name="<?= esc($p['name']) ?>"
                                            data-def="<?= esc($p['def']) ?>"
                                            data-module="<?= esc($p['module'] ?? 'custom') ?>"
                                            title="Lihat Detail Izin">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-secondary p-1 px-2 btn-perm-edit"
                                            data-id="<?= $p['id'] ?>"
                                            data-name="<?= esc($p['name']) ?>"
                                            data-def="<?= esc($p['def']) ?>"
                                            data-module="<?= esc($p['module'] ?? 'custom') ?>"
                                            title="Ubah Definisi Izin">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2 btn-perm-delete"
                                            data-id="<?= $p['id'] ?>"
                                            data-name="<?= esc($p['name']) ?>"
                                            title="Hapus Izin">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small">Belum ada permission terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                Menampilkan <strong class="text-dark"><?= count($permList) ?></strong> permission hak akses
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

<!-- MODAL ADD PERMISSION -->
<div class="modal fade" id="modalAddPermission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-shield-halved me-2"></i>Tambah Izin Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('pengaturan/permission/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kunci Izin (Permission Key) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm font-monospace" placeholder="e.g. retur_penjualan_approve" required>
                        <small class="text-muted" style="font-size: 0.78rem;">Gunakan format huruf kecil dengan pemisah garis bawah (snake_case).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Modul Terkait</label>
                        <select class="form-select form-select-sm" name="module">
                            <option value="menu">Navigasi / Menu</option>
                            <option value="master">Master Data &amp; Stok</option>
                            <option value="penjualan">Penjualan &amp; POS</option>
                            <option value="crm">CRM &amp; RFM</option>
                            <option value="pembelian">Pembelian</option>
                            <option value="keuangan">Keuangan &amp; Kas</option>
                            <option value="custom" selected>Izin Tambahan / Kustom</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Definisi &amp; Cakupan Wewenang</label>
                        <textarea name="definition" class="form-control form-control-sm" rows="3" placeholder="Deskripsikan aksi apa saja yang diizinkan oleh key ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Izin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW PERMISSION -->
<div class="modal fade" id="modalViewPermission" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Detail Kunci Izin</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Permission ID:</label>
                    <div class="font-monospace fw-bold text-muted" id="viewPermId">-</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Permission Key:</label>
                    <div><code class="bg-light p-2 rounded font-monospace text-primary fs-6 border d-block" id="viewPermName">-</code></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Definisi / Scope:</label>
                    <div class="fw-semibold text-dark" id="viewPermDef">-</div>
                </div>
                <div class="p-3 bg-light rounded small text-muted border">
                    <i class="fa-solid fa-lock text-success me-1"></i>Hak akses ini terlindungi dalam filter antarmuka dan REST API auth guard.
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT PERMISSION -->
<div class="modal fade" id="modalEditPermission" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Ubah Definisi Izin</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPerm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Permission Key</label>
                        <input type="text" name="name" id="editPermName" class="form-control form-control-sm font-monospace" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Modul Terkait</label>
                        <select class="form-select form-select-sm" name="module" id="editPermModule">
                            <option value="menu">Navigasi / Menu</option>
                            <option value="master">Master Data &amp; Stok</option>
                            <option value="penjualan">Penjualan &amp; POS</option>
                            <option value="crm">CRM &amp; RFM</option>
                            <option value="pembelian">Pembelian</option>
                            <option value="keuangan">Keuangan &amp; Kas</option>
                            <option value="custom">Izin Tambahan / Kustom</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Definisi / Deskripsi Wewenang</label>
                        <textarea name="definition" id="editPermDef" class="form-control form-control-sm" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- HIDDEN DELETE PERM FORM -->
<form id="formDeletePerm" method="POST" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#permTable tbody tr');
    const searchInput = document.getElementById('permSearchInput');
    const resetBtn = document.getElementById('btnPermReset');

    function applyPermFilter() {
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        tableRows.forEach(row => {
            const txt = row.textContent.toLowerCase();
            row.style.display = (!q || txt.includes(q)) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyPermFilter);
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            tableRows.forEach(r => r.style.display = '');
        });
    }

    // Select All
    const selectAllCb = document.getElementById('selectAllPerm');
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            document.querySelectorAll('.perm-check-item').forEach(cb => {
                cb.checked = selectAllCb.checked;
            });
        });
    }

    // See Modal
    document.querySelectorAll('.btn-perm-see').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewPermId').textContent = '#' + this.dataset.id;
            document.getElementById('viewPermName').textContent = this.dataset.name;
            document.getElementById('viewPermDef').textContent = this.dataset.def;
            new bootstrap.Modal(document.getElementById('modalViewPermission')).show();
        });
    });

    // Edit Modal
    document.querySelectorAll('.btn-perm-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('formEditPerm');
            form.action = '<?= base_url('pengaturan/permission/update') ?>/' + this.dataset.id;
            document.getElementById('editPermName').value = this.dataset.name;
            document.getElementById('editPermDef').value = this.dataset.def;
            if (document.getElementById('editPermModule')) {
                document.getElementById('editPermModule').value = this.dataset.module || 'custom';
            }
            new bootstrap.Modal(document.getElementById('modalEditPermission')).show();
        });
    });

    // Delete
    document.querySelectorAll('.btn-perm-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin menghapus permission '${name}'?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((r) => {
                if (r.isConfirmed) {
                    const form = document.getElementById('formDeletePerm');
                    form.action = '<?= base_url('pengaturan/permission/delete') ?>/' + id;
                    form.submit();
                }
            });
        });
    });

    // Bulk Delete
    const btnBulk = document.getElementById('btnBulkPermApply');
    if (btnBulk) {
        btnBulk.addEventListener('click', function() {
            const action = document.getElementById('bulkPermAction').value;
            const checked = Array.from(document.querySelectorAll('.perm-check-item:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal 1 permission untuk menerapkan aksi.' });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Hapus ${checked.length} permission yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0392B',
                    cancelButtonColor: '#8DA398',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) {
                        const form = document.getElementById('formDeletePerm');
                        form.action = '<?= base_url('pengaturan/permission/delete') ?>/' + checked[0];
                        form.submit();
                    }
                });
            } else {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tindakan massal yang valid.' });
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
