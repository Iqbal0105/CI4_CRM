<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('pembelian/detail/' . $po['id']) ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Detail PO
    </a>
    <h3 class="fw-bold mt-2 mb-1">Verifikasi Penerimaan Barang</h3>
    <div class="text-muted small">
        Dokumen PO: <strong class="font-monospace text-primary">#<?= esc($po['nomor_po']) ?></strong> &bull; 
        Supplier: <strong><?= esc($po['nama_supplier']) ?></strong>
    </div>
</div>

<div class="alert alert-info d-flex align-items-center gap-2">
    <i class="fa-solid fa-circle-info fs-5"></i>
    <div>
        Barang yang diterima akan otomatis <strong>menambah stok fisik</strong>, mencatat mutasi masuk pada <strong>kartu stok</strong>, dan mengunci <strong>HPP produk</strong> secara real-time.
    </div>
</div>

<form action="<?= base_url('pembelian/process-penerimaan/' . $po['id']) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">Hitung Fisik Barang Masuk</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item Produk</th>
                            <th class="text-center" style="width: 120px;">Qty Dipesan</th>
                            <th class="text-center" style="width: 120px;">Sudah Diterima</th>
                            <th class="text-center" style="width: 120px;">Sisa Tunggakan</th>
                            <th class="text-center" style="width: 180px;">Qty Diterima Sekarang <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $pesan = (float) $item['qty'];
                            $sudah = (float) $item['qty_diterima'];
                            $sisa = $pesan - $sudah;
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></div>
                                    <small class="text-muted font-monospace"><?= esc($item['kode_produk']) ?></small>
                                </td>
                                <td class="text-center font-monospace"><?= number_format($pesan, 0, ',', '.') ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?></td>
                                <td class="text-center font-monospace text-success fw-bold"><?= number_format($sudah, 0, ',', '.') ?></td>
                                <td class="text-center font-monospace text-danger fw-bold"><?= number_format($sisa, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if ($sisa > 0): ?>
                                        <input type="number" step="1" name="qty_terima[<?= $item['id'] ?>]" class="form-control text-center font-monospace fw-bold text-primary" value="<?= (int) $sisa ?>" max="<?= (int) $sisa ?>" min="0">
                                    <?php else: ?>
                                        <span class="badge bg-success">Lengkap</span>
                                        <input type="hidden" name="qty_terima[<?= $item['id'] ?>]" value="0">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white p-3 d-flex flex-column flex-sm-row justify-content-end gap-2">
            <a href="<?= base_url('pembelian/detail/' . $po['id']) ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
            <button type="submit" class="btn btn-success px-4 fw-semibold w-100 w-sm-auto">
                <i class="fa-solid fa-check-double me-2"></i>Konfirmasi Penerimaan Fisik
            </button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

