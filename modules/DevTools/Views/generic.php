<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">
            <?= esc($title) ?> <small class="text-muted fs-6 fw-normal">Developer Suite</small>
        </h3>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span> &gt;
        <span class="text-dark fw-medium"><?= esc($title) ?></span>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-body p-5 text-center">
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; font-size: 28px;">
            <i class="fa-solid fa-code"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2"><?= esc($title) ?> Active</h5>
        <p class="text-muted small mx-auto mb-4" style="max-width: 500px;">
            Modul <?= esc($title) ?> telah siap digunakan dan terintegrasi penuh ke dalam sistem IDMETAFORA Developer Suite.
        </p>
        <a href="<?= base_url('dev/crud') ?>" class="btn btn-primary btn-sm px-4 fw-semibold">
            <i class="fa-solid fa-table me-1"></i>Buka CRUD Builder
        </a>
    </div>
</div>

<?= $this->endSection() ?>

