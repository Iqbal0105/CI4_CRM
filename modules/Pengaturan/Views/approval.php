<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">
            Approval Calon Customer <small class="text-muted fs-6 fw-normal">Kelola Izin &amp; Aktivasi Akun Calon Customer</small>
        </h3>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Dashboard</span> &gt;
        <span class="text-secondary">User</span> &gt;
        <span class="text-dark fw-medium">Approval Calon Customer</span>
    </div>
</div>
<!-- 3 STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3 cursor-pointer stat-filter-card" data-filter="pending" style="border-radius: 10px; border-left: 4px solid #f59e0b !important; cursor: pointer;">
            <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning fs-4">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <div class="fs-2 fw-bold text-dark font-monospace"><?= $pendingCount ?></div>
                <div class="text-muted small fw-semibold">Menunggu Persetujuan</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3 cursor-pointer stat-filter-card" data-filter="approved" style="border-radius: 10px; border-left: 4px solid #10b981 !important; cursor: pointer;">
            <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success fs-4">
                <i class="fa-regular fa-circle-check"></i>
            </div>
            <div>
                <div class="fs-2 fw-bold text-dark font-monospace"><?= $approvedCount ?></div>
                <div class="text-muted small fw-semibold">Telah Disetujui (Aktif)</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3 cursor-pointer stat-filter-card" data-filter="rejected" style="border-radius: 10px; border-left: 4px solid #ef4444 !important; cursor: pointer;">
            <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger fs-4">
                <i class="fa-regular fa-circle-xmark"></i>
            </div>
            <div>
                <div class="fs-2 fw-bold text-dark font-monospace"><?= $rejectedCount ?></div>
                <div class="text-muted small fw-semibold">Ditolak</div>
            </div>
        </div>
    </div>
</div>

<!-- APPROVAL TABLE & FILTER -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex gap-2 flex-wrap" id="approvalTabs">
            <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 btn-filter-tab" data-target="pending">
                <i class="fa-solid fa-hourglass-half me-1"></i>Menunggu Approval (<?= $pendingCount ?>)
            </button>
            <button type="button" class="btn btn-sm btn-light border text-secondary px-3 btn-filter-tab" data-target="approved">
                <i class="fa-solid fa-check me-1"></i>Disetujui (<?= $approvedCount ?>)
            </button>
            <button type="button" class="btn btn-sm btn-light border text-secondary px-3 btn-filter-tab" data-target="rejected">
                <i class="fa-solid fa-xmark me-1"></i>Ditolak (<?= $rejectedCount ?>)
            </button>
            <button type="button" class="btn btn-sm btn-light border text-secondary px-3 btn-filter-tab" data-target="all">
                Semua Data (<?= count($applicants) ?>)
            </button>
        </div>
        <div>
            <input type="text" class="form-control form-control-sm" id="applicantSearchInput" placeholder="Cari nama, toko, WA..." style="width: 220px;">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="applicantTable">
                <thead style="background-color: #0f172a; color: white;">
                    <tr class="text-uppercase small fw-bold" style="font-size: 0.75rem;">
                        <th class="ps-3 py-3" style="width: 50px;">NO</th>
                        <th>CALON CUSTOMER &amp; BISNIS</th>
                        <th>KONTAK LANGSUNG</th>
                        <th>USERNAME LOGIN</th>
                        <th>WAKTU DAFTAR</th>
                        <th>STATUS</th>
                        <th class="pe-3 text-end">AKSI APPROVAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applicants)): ?>
                        <?php foreach ($applicants as $idx => $a): ?>
                            <tr class="applicant-row" data-status="<?= $a['status'] ?>" style="<?= $a['status'] !== 'pending' ? 'display: none;' : '' ?>">
                                <td class="ps-3 font-monospace small text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark small"><?= esc($a['nama']) ?></div>
                                    <div class="text-muted small" style="font-size: 0.75rem;"><i class="fa-solid fa-shop me-1 text-primary"></i><?= esc($a['bisnis']) ?></div>
                                    <?php if (!empty($a['catatan'])): ?>
                                        <div class="text-secondary small fst-italic" style="font-size: 0.78rem;"><?= esc($a['catatan']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $a['no_wa']) ?>" target="_blank" class="text-success text-decoration-none small fw-semibold"><i class="fa-brands fa-whatsapp me-1"></i><?= esc($a['no_wa']) ?></a></div>
                                    <div class="text-muted small" style="font-size: 0.78rem;"><?= esc($a['email']) ?></div>
                                </td>
                                <td>
                                    <span class="font-monospace text-primary bg-light px-2 py-1 rounded small"><?= esc($a['username']) ?></span>
                                </td>
                                <td>
                                    <span class="small text-secondary font-monospace"><?= esc($a['waktu_daftar']) ?></span>
                                </td>
                                <td>
                                    <?php if ($a['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fa-regular fa-clock me-1"></i>Pending</span>
                                    <?php elseif ($a['status'] === 'approved'): ?>
                                        <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-3 text-end">
                                    <?php if ($a['status'] === 'pending'): ?>
                                        <div class="d-inline-flex gap-1">
                                            <form action="<?= base_url('pengaturan/approval/approve/' . $a['id']) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success py-1 px-2 fw-semibold" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-check me-1"></i>Setujui
                                                </button>
                                            </form>
                                            <form action="<?= base_url('pengaturan/approval/reject/' . $a['id']) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 fw-semibold" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-xmark me-1"></i>Tolak
                                                </button>
                                            </form>
                                        </div>
                                    <?php elseif ($a['status'] === 'approved'): ?>
                                        <form action="<?= base_url('pengaturan/approval/reject/' . $a['id']) ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                                                <i class="fa-solid fa-ban me-1"></i>Nonaktifkan
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?= base_url('pengaturan/approval/approve/' . $a['id']) ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success py-1 px-2" style="font-size: 0.78rem;">
                                                <i class="fa-solid fa-rotate-right me-1"></i>Setujui Ulang
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr id="emptyRow" style="display: <?= $pendingCount === 0 ? '' : 'none' ?>;">
                        <td colspan="7" class="py-5 text-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 22px;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="fw-semibold text-secondary small" id="emptyMessage">Tidak ada data calon customer dengan status yang dipilih.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentTab = 'pending';
    const rows = document.querySelectorAll('.applicant-row');
    const emptyRow = document.getElementById('emptyRow');
    const searchInput = document.getElementById('applicantSearchInput');
    const tabButtons = document.querySelectorAll('.btn-filter-tab');

    function filterRows() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(r => {
            const status = r.dataset.status;
            const text = r.textContent.toLowerCase();

            const matchTab = (currentTab === 'all') || (status === currentTab);
            const matchQuery = !query || text.includes(query);

            if (matchTab && matchQuery) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            emptyRow.style.display = '';
            document.getElementById('emptyMessage').textContent = query
                ? 'Tidak ada data calon customer yang cocok dengan pencarian "' + query + '".'
                : 'Tidak ada data calon customer dengan filter yang dipilih.';
        } else {
            emptyRow.style.display = 'none';
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            tabButtons.forEach(b => {
                b.className = 'btn btn-sm btn-light border text-secondary px-3 btn-filter-tab';
            });
            this.className = 'btn btn-sm btn-primary fw-semibold px-3 btn-filter-tab';
            currentTab = this.dataset.target;
            filterRows();
        });
    });

    document.querySelectorAll('.stat-filter-card').forEach(card => {
        card.addEventListener('click', function() {
            const targetFilter = this.dataset.filter;
            const correspondingBtn = document.querySelector(`.btn-filter-tab[data-target="${targetFilter}"]`);
            if (correspondingBtn) correspondingBtn.click();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', filterRows);
    }

    // Initial check
    filterRows();
});
</script>
<?= $this->endSection() ?>
