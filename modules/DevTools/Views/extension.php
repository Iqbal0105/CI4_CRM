<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Ekstensi &amp; Plugin Pengembang</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                <?= count($extensions) ?> Ekstensi Terpasang
            </span>
        </div>
        <p class="text-muted small mb-0">
            Kelola paket ekstensi fungsional, widget form editor, dan pustaka komponen pengembang.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddExtension">
            <i class="fa-solid fa-plus me-1"></i>Pasang Ekstensi Baru
        </button>
    </div>
</div>
<!-- EXTENSION REGISTER CARD -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-puzzle-piece fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Paket Ekstensi Sistem</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Status modul tambahan terinstal pada core Developer Suite</div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.73rem;">
                        <th class="ps-3 py-3" style="width: 40px;"><input type="checkbox" class="form-check-input"></th>
                        <th>Nama Ekstensi</th>
                        <th>Deskripsi &amp; Fungsionalitas</th>
                        <th class="text-center">Status</th>
                        <th class="pe-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($extensions as $ext): ?>
                        <tr>
                            <td class="ps-3"><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="fw-semibold text-dark small"><?= esc($ext['name']) ?></div>
                                <div class="text-muted" style="font-size: 0.78rem;">Pengembang: <strong class="text-secondary"><?= esc($ext['author']) ?></strong></div>
                            </td>
                            <td class="small text-secondary"><?= esc($ext['desc']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small fw-semibold">
                                    <i class="fa-solid fa-circle text-success me-1" style="font-size: 0.45rem;"></i>Aktif
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <button type="button" class="btn btn-sm btn-light border text-danger px-2 py-1 btn-toggle-ext" title="Nonaktifkan Ekstensi">
                                    <i class="fa-solid fa-power-off me-1"></i>Nonaktifkan
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Menampilkan <strong class="text-dark"><?= count($extensions) ?></strong> ekstensi terpasang
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD EXTENSION -->
<div class="modal fade" id="modalAddExtension" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-puzzle-piece me-2"></i>Pasang Ekstensi Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('dev/extension/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Ekstensi <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. cicool barcode generator" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Pembuat / Author</label>
                        <input type="text" name="author" class="form-control form-control-sm" placeholder="e.g. IDMETAFORA Team">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Deskripsi Kapabilitas</label>
                        <textarea name="desc" class="form-control form-control-sm" rows="2" placeholder="Jelaskan fungsi ekstensi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Pasang Ekstensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-toggle-ext').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.textContent.includes('Nonaktifkan')) {
                this.innerHTML = '<i class="fa-solid fa-power-off me-1"></i>Aktifkan';
                this.className = 'btn btn-sm btn-light border text-success px-2 py-1 btn-toggle-ext';
                const badge = this.closest('tr').querySelector('td:nth-child(4) .badge');
                if (badge) {
                    badge.className = 'badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 small fw-semibold';
                    badge.innerHTML = '<i class="fa-solid fa-circle text-secondary me-1" style="font-size: 0.45rem;"></i>Nonaktif';
                }
            } else {
                this.innerHTML = '<i class="fa-solid fa-power-off me-1"></i>Nonaktifkan';
                this.className = 'btn btn-sm btn-light border text-danger px-2 py-1 btn-toggle-ext';
                const badge = this.closest('tr').querySelector('td:nth-child(4) .badge');
                if (badge) {
                    badge.className = 'badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small fw-semibold';
                    badge.innerHTML = '<i class="fa-solid fa-circle text-success me-1" style="font-size: 0.45rem;"></i>Aktif';
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
