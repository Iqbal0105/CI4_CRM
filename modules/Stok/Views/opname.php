<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">
            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Semua Stok Produk
        </h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Pembaruan Massal / Bulk Stock Editor</span>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Stok</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Edit Semua Stok</span>
    </div>
</div>

<form action="<?= base_url('stok/opname/store') ?>" method="POST" id="formBulkStok">
    <?= csrf_field() ?>

    <div class="card shadow-sm border mb-4">
        <!-- CARD HEADER -->
        <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-list-check fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark">Daftar Seluruh Produk &amp; Stok Fisik</div>
                    <div class="text-muted small">Total <?= count($items) ?> item produk tercatat</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" id="btnResetBulk">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset Input</span>
                </button>
                <a href="<?= base_url('stok') ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Stok</span>
                </a>
            </div>
        </div>

        <!-- FILTER & QUICK TABS ROW -->
        <div class="card-body border-bottom bg-light bg-opacity-50 py-3">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="filterKeyword" class="form-control" placeholder="Cari kode atau nama produk...">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-tab active" data-filter="all">
                        Semua <span class="badge bg-white text-primary ms-1"><?= count($items) ?></span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 btn-tab" data-filter="changed">
                        Diubah <span class="badge bg-secondary text-white ms-1" id="badgeChanged">0</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 btn-tab" data-filter="habis">
                        Stok Habis <span class="badge bg-danger text-white ms-1"><?= count($habisItems) ?></span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1 btn-tab" data-filter="menipis">
                        Menipis (&le; 5) <span class="badge bg-warning text-dark ms-1"><?= count($menipisItems) ?></span>
                    </button>
                </div>
                <div class="col-12 col-md-2 text-md-end text-muted small">
                    Menampilkan <strong id="visibleCount"><?= count($items) ?></strong> item
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableBulk">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th style="width: 45px;">#</th>
                            <th style="width: 140px;">KODE PRODUK</th>
                            <th>NAMA PRODUK &amp; KATEGORI</th>
                            <th class="text-center" style="width: 100px;">SATUAN</th>
                            <th class="text-center" style="width: 130px;">STOK SAAT INI</th>
                            <th class="text-center" style="width: 150px;">STOK BARU <span class="text-success">*</span></th>
                            <th class="text-center" style="width: 100px;">SELISIH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($items as $item): ?>
                            <?php 
                                $fisik = (float) $item['fisik'];
                                $reserved = (float) $item['reserved'];
                                $tersedia = $fisik - $reserved;
                                $isHabis = $tersedia <= 0;
                                $isMenipis = !$isHabis && ($tersedia <= 5 || $tersedia <= (int)$item['stok_minimum']);
                            ?>
                            <tr class="bulk-row" data-id="<?= $item['id'] ?>" data-habis="<?= $isHabis ? '1' : '0' ?>" data-menipis="<?= $isMenipis ? '1' : '0' ?>">
                                <td class="text-muted small"><?= $no++ ?></td>
                                <td>
                                    <span class="badge px-2 py-1 font-monospace" style="background-color: #0284c7; color: white; font-size: 0.78rem;">
                                        <?= esc($item['kode_produk']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></div>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-tag me-1" style="font-size: 0.75rem;"></i><?= esc($item['nama_kategori'] ?? 'Umum') ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                                        <?= strtolower(esc($item['nama_satuan'] ?? 'pcs')) ?>
                                    </span>
                                </td>
                                <td class="text-center font-monospace fw-bold <?= $isHabis ? 'text-danger' : 'text-dark' ?>">
                                    <?= number_format($tersedia, 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <input type="number" 
                                           step="1" 
                                           name="stok[<?= $item['id'] ?>]" 
                                           class="form-control form-control-sm text-center fw-bold font-monospace input-stok-baru" 
                                           style="border-color: #38bdf8;"
                                           data-initial="<?= (int) $tersedia ?>" 
                                           value="<?= (int) $tersedia ?>">
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-secondary border font-monospace badge-selisih">0</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD FOOTER ACTION -->
        <div class="card-footer bg-white d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 py-3">
            <div class="text-muted small">
                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                Ubah nilai pada kolom <strong>STOK BARU</strong> lalu klik simpan untuk memperbarui secara otomatis ke kartu inventori.
            </div>
            <button type="submit" class="btn btn-success px-4 py-2 fw-semibold shadow-sm">
                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Pembaruan Stok Massal
            </button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        function recalculateRow($row) {
            const $input = $row.find('.input-stok-baru');
            const initial = parseFloat($input.data('initial')) || 0;
            const current = parseFloat($input.val()) || 0;
            const diff = current - initial;
            const $badge = $row.find('.badge-selisih');

            if (diff === 0) {
                $badge.removeClass('bg-success bg-danger text-white').addClass('bg-light text-secondary border').text('0');
                $row.removeClass('row-changed');
            } else if (diff > 0) {
                $badge.removeClass('bg-light text-secondary border bg-danger').addClass('bg-success text-white').text('+' + diff);
                $row.addClass('row-changed');
            } else {
                $badge.removeClass('bg-light text-secondary border bg-success').addClass('bg-danger text-white').text(diff);
                $row.addClass('row-changed');
            }

            // Update Diubah count
            const changedCount = $('.row-changed').length;
            $('#badgeChanged').text(changedCount);
        }

        $('.input-stok-baru').on('input change', function() {
            recalculateRow($(this).closest('tr'));
        });

        $('#btnResetBulk').on('click', function() {
            $('.input-stok-baru').each(function() {
                $(this).val($(this).data('initial'));
                recalculateRow($(this).closest('tr'));
            });
        });

        // Search Filter
        $('#filterKeyword').on('keyup', function() {
            const kw = $(this).val().toLowerCase();
            $('.bulk-row').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(kw) > -1);
            });
            $('#visibleCount').text($('.bulk-row:visible').length);
        });

        // Quick Tabs Filter
        $('.btn-tab').on('click', function() {
            $('.btn-tab').removeClass('btn-primary btn-outline-secondary btn-outline-danger btn-outline-warning text-white active')
                .addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary text-white active');

            const filter = $(this).data('filter');
            $('.bulk-row').each(function() {
                if (filter === 'all') {
                    $(this).show();
                } else if (filter === 'changed') {
                    $(this).toggle($(this).hasClass('row-changed'));
                } else if (filter === 'habis') {
                    $(this).toggle($(this).data('habis') == 1);
                } else if (filter === 'menipis') {
                    $(this).toggle($(this).data('menipis') == 1);
                }
            });
            $('#visibleCount').text($('.bulk-row:visible').length);
        });
    });
</script>
<?= $this->endSection() ?>

