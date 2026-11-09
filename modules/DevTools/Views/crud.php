<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">CRUD Builder &amp; Generator</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                <?= count($crudList) ?> Modul Terdaftar
            </span>
        </div>
        <p class="text-muted small mb-0">
            Otomasi pembuatan modul database, skema tabel, entitas controller, dan form generator.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddCrud">
            <i class="fa-solid fa-plus me-1"></i>Tambah Modul CRUD
        </button>
        <a href="<?= base_url('dev/crud/export') ?>" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary">
            <i class="fa-solid fa-file-excel text-success me-1"></i>Export XLS
        </a>
        <button type="button" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary" onclick="window.print()">
            <i class="fa-solid fa-file-pdf text-danger me-1"></i>Export PDF
        </button>
    </div>
</div>
<!-- CRUD REGISTER CARD -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <!-- CARD HEADER & TOOLBAR -->
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-table-cells-large fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Modul CRUD Aktif</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Skema tabel database yang terintegrasi ke dalam sistem</div>
            </div>
        </div>

        <!-- BULK ACTIONS -->
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" id="bulkCrudAction" style="width: 140px;">
                <option value="">Aksi Massal...</option>
                <option value="delete">Hapus Terpilih</option>
            </select>
            <button type="button" class="btn btn-light border btn-sm fw-semibold" id="btnBulkCrudApply">
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
                    <input type="text" id="crudSearchInput" class="form-control border-start-0" placeholder="Cari nama modul, subject, atau tabel database...">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnCrudReset" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="crudTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.73rem;">
                        <th class="ps-3 py-3" style="width: 40px;"><input type="checkbox" id="selectAllCrud" class="form-check-input"></th>
                        <th>Judul Modul</th>
                        <th>Subjek Bisnis</th>
                        <th>Tabel Database</th>
                        <th class="pe-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($crudList as $c): ?>
                        <tr>
                            <td class="ps-3"><input type="checkbox" class="form-check-input crud-check-item" value="<?= $c['id'] ?? 1 ?>"></td>
                            <td>
                                <div class="fw-semibold text-dark small"><?= esc($c['title']) ?></div>
                            </td>
                            <td class="small text-secondary"><?= esc($c['subject']) ?></td>
                            <td>
                                <code class="font-monospace text-primary small bg-light px-2 py-1 rounded border">
                                    <?= esc($c['table']) ?>
                                </code>
                                <?php if (!empty($c['update'])): ?>
                                    <span class="badge bg-warning bg-opacity-15 text-warning small font-monospace ms-1" style="font-size: 0.78rem;">
                                        <i class="fa-solid fa-clock-rotate-left me-1"></i><?= esc($c['update']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="btn btn-sm btn-light border text-primary p-1 px-2 btn-crud-see"
                                        data-id="<?= $c['id'] ?? 1 ?>"
                                        data-title="<?= esc($c['title']) ?>"
                                        data-subject="<?= esc($c['subject']) ?>"
                                        data-table="<?= esc($c['table']) ?>"
                                        title="Lihat Skema Modul">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border text-secondary p-1 px-2 btn-crud-edit"
                                        data-id="<?= $c['id'] ?? 1 ?>"
                                        data-title="<?= esc($c['title']) ?>"
                                        data-subject="<?= esc($c['subject']) ?>"
                                        data-table="<?= esc($c['table']) ?>"
                                        title="Ubah Konfigurasi Modul">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2 btn-crud-delete"
                                        data-id="<?= $c['id'] ?? 1 ?>"
                                        data-title="<?= esc($c['title']) ?>"
                                        title="Hapus Modul">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                Menampilkan <strong class="text-dark"><?= count($crudList) ?></strong> entitas modul database
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

<!-- MODAL ADD CRUD -->
<div class="modal fade" id="modalAddCrud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-table-list me-2"></i>Tambah Modul CRUD Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('dev/crud/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Judul Modul (Title) <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Master Kurir Ekspedisi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Subjek Bisnis (Subject) <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control form-control-sm" placeholder="Kurir Ekspedisi" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Nama Tabel Database <span class="text-danger">*</span></label>
                        <input type="text" name="table" class="form-control form-control-sm font-monospace" placeholder="kurir_ekspedisi" required>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Generate Modul
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW CRUD -->
<div class="modal fade" id="modalViewCrud" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-table-cells me-2 text-primary"></i>Detail Skema Modul CRUD</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Judul Modul:</label>
                    <div class="fw-bold fs-6 text-dark" id="viewCrudTitle">-</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Subjek Bisnis:</label>
                    <div class="fw-semibold text-secondary" id="viewCrudSubject">-</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Tabel Database:</label>
                    <div><code class="bg-light p-2 rounded font-monospace text-primary fs-6 border d-block" id="viewCrudTable">-</code></div>
                </div>
                <div class="p-3 bg-light rounded small text-muted border">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>Controller, Model, Migration, dan View terkompilasi aktif dalam sistem ERP.
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT CRUD -->
<div class="modal fade" id="modalEditCrud" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Ubah Konfigurasi CRUD</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditCrud" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Judul Modul</label>
                        <input type="text" name="title" id="editCrudTitle" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Subjek Bisnis</label>
                        <input type="text" name="subject" id="editCrudSubject" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Nama Tabel</label>
                        <input type="text" name="table" id="editCrudTable" class="form-control form-control-sm font-monospace bg-light" readonly>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- HIDDEN DELETE CRUD FORM -->
<form id="formDeleteCrud" method="POST" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#crudTable tbody tr');
    const searchInput = document.getElementById('crudSearchInput');
    const resetBtn = document.getElementById('btnCrudReset');

    function applyCrudFilter() {
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        tableRows.forEach(row => {
            const txt = row.textContent.toLowerCase();
            row.style.display = (!q || txt.includes(q)) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyCrudFilter);
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            tableRows.forEach(r => r.style.display = '');
        });
    }

    // Select All
    const selectAllCb = document.getElementById('selectAllCrud');
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            document.querySelectorAll('.crud-check-item').forEach(cb => {
                cb.checked = selectAllCb.checked;
            });
        });
    }

    // See Modal
    document.querySelectorAll('.btn-crud-see').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewCrudTitle').textContent = this.dataset.title;
            document.getElementById('viewCrudSubject').textContent = this.dataset.subject;
            document.getElementById('viewCrudTable').textContent = this.dataset.table;
            new bootstrap.Modal(document.getElementById('modalViewCrud')).show();
        });
    });

    // Edit Modal
    document.querySelectorAll('.btn-crud-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('formEditCrud');
            form.action = '<?= base_url('dev/crud/update') ?>/' + this.dataset.id;
            document.getElementById('editCrudTitle').value = this.dataset.title;
            document.getElementById('editCrudSubject').value = this.dataset.subject;
            document.getElementById('editCrudTable').value = this.dataset.table;
            new bootstrap.Modal(document.getElementById('modalEditCrud')).show();
        });
    });

    // Delete
    document.querySelectorAll('.btn-crud-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            Swal.fire({
                title: 'Konfirmasi',
                text: `Hapus modul CRUD '${title}'?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((r) => {
                if (r.isConfirmed) {
                    const form = document.getElementById('formDeleteCrud');
                    form.action = '<?= base_url('dev/crud/delete') ?>/' + id;
                    form.submit();
                }
            });
        });
    });

    // Bulk Delete
    const btnBulk = document.getElementById('btnBulkCrudApply');
    if (btnBulk) {
        btnBulk.addEventListener('click', function() {
            const action = document.getElementById('bulkCrudAction').value;
            const checked = Array.from(document.querySelectorAll('.crud-check-item:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal 1 modul CRUD untuk menerapkan aksi.' });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Hapus ${checked.length} modul CRUD yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0392B',
                    cancelButtonColor: '#8DA398',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) {
                        const form = document.getElementById('formDeleteCrud');
                        form.action = '<?= base_url('dev/crud/delete') ?>/' + checked[0];
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
