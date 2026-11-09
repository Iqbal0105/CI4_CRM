<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Akun Kas & Bank</h3>
        <p class="text-muted small mb-0">Kelola kas fisik kasir, kas QRIS, rekening bank operasional, dan petty cash.</p>
    </div>
    <button type="button" class="btn btn-primary flex-fill flex-sm-grow-0 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalKasBank">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Akun Kas/Bank</span>
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe</th>
                        <th>Nomor Rekening & Atas Nama</th>
                        <th>Akun COA Terhubung</th>
                        <th class="text-end">Saldo Riil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $acc): ?>
                        <tr>
                            <td><span class="font-monospace fw-bold text-dark"><?= esc($acc['kode_akun']) ?></span></td>
                            <td><div class="fw-semibold text-dark"><?= esc($acc['nama_akun']) ?></div></td>
                            <td>
                                <?php
                                $badge = match ($acc['tipe']) {
                                    'kas'        => 'bg-success-subtle text-success border border-success-subtle',
                                    'bank'       => 'bg-primary-subtle text-primary border border-primary-subtle',
                                    'qris'       => 'bg-warning-subtle text-warning border border-warning-subtle',
                                    'petty_cash' => 'bg-secondary-subtle text-secondary',
                                    default      => 'bg-light text-dark',
                                };
                                ?>
                                <span class="badge <?= $badge ?> text-uppercase"><?= str_replace('_', ' ', esc($acc['tipe'])) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($acc['nomor_rekening'])): ?>
                                    <div class="font-monospace fw-semibold"><?= esc($acc['nomor_rekening']) ?></div>
                                    <small class="text-muted">a/n <?= esc($acc['atas_nama'] ?? '-') ?></small>
                                <?php else: ?>
                                    <span class="text-muted small">Kas Tunai / Internal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">[<?= esc($acc['coa_kode'] ?? '-') ?>] <?= esc($acc['coa_nama'] ?? '-') ?></span>
                            </td>
                            <td class="text-end font-monospace fw-bold fs-6 text-success"><?= format_rupiah($acc['saldo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KAS BANK -->
<div class="modal fade" id="modalKasBank" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('keuangan/kas-bank/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Akun Kas / Bank Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Akun <span class="text-danger">*</span></label>
                        <input type="text" name="kode_akun" class="form-control text-uppercase font-monospace" placeholder="BANK-MANDIRI" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Akun <span class="text-danger">*</span></label>
                        <input type="text" name="nama_akun" class="form-control" placeholder="Bank Mandiri Operasional" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Akun</label>
                        <select name="tipe" class="form-select" required>
                            <option value="bank">Bank (Rekening Perusahaan)</option>
                            <option value="kas">Kas (Kasir Toko)</option>
                            <option value="qris">QRIS (Merchant Settlement)</option>
                            <option value="petty_cash">Kas Kecil (Petty Cash)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control font-monospace" placeholder="1400019283019">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Atas Nama Rekening</label>
                        <input type="text" name="atas_nama" class="form-control" placeholder="PT Metafora Enterprise">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hubungkan ke Akun COA</label>
                        <select name="coa_id" class="form-select">
                            <option value="">-- Pilih Akun COA --</option>
                            <?php foreach ($coaList as $c): ?>
                                <option value="<?= $c['id'] ?>">[<?= esc($c['kode_akun']) ?>] <?= esc($c['nama_akun']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Saldo Awal (Rp)</label>
                        <input type="number" step="0.01" name="saldo" class="form-control font-monospace" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

