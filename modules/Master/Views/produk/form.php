<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('master/produk') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h3 class="fw-bold mt-2 mb-1"><?= esc($title) ?></h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <?php $isEdit = isset($produk); ?>
        <form action="<?= $isEdit ? base_url('master/produk/update/' . $produk['id']) : base_url('master/produk/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kode SKU Produk <span class="text-danger">*</span></label>
                    <input type="text" name="kode_produk" class="form-control text-uppercase font-monospace" value="<?= old('kode_produk', $produk['kode_produk'] ?? $autoKode ?? '') ?>" placeholder="CONTOH: PRD-001" required>
                    <small class="text-muted">Kode unik identifikasi produk dalam sistem.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Barcode / EAN-13</label>
                    <input type="text" name="barcode" class="form-control font-monospace" value="<?= old('barcode', $produk['barcode'] ?? $autoBarcode ?? '') ?>" placeholder="CONTOH: 89910010012">
                    <small class="text-muted">Kode barcode untuk pemindaian scanner kasir (POS).</small>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control" value="<?= old('nama_produk', $produk['nama_produk'] ?? '') ?>" placeholder="Nama lengkap produk" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori Produk</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= old('kategori_id', $produk['kategori_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Satuan Barang</label>
                    <select name="satuan_id" class="form-select">
                        <option value="">-- Pilih Satuan --</option>
                        <?php foreach ($satuan as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= old('satuan_id', $produk['satuan_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                                <?= esc($s['nama_satuan']) ?> (<?= esc($s['kode_satuan']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="my-3">
                <h5 class="fw-bold mb-2 text-primary">Struktur Harga (IDR) & Stok Minimum</h5>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Beli Pokok (HPP) <span class="text-danger">*</span></label>
                    <input type="text" inputmode="numeric" name="harga_beli" class="form-control font-monospace input-ribuan" value="<?= old('harga_beli') ? number_format((float) str_replace('.', '', (string) old('harga_beli')), 0, ',', '.') : (isset($produk['harga_beli']) ? number_format((float) $produk['harga_beli'], 0, ',', '.') : '0') ?>" placeholder="0" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Jual Retail <span class="text-danger">*</span></label>
                    <input type="text" inputmode="numeric" name="harga_jual" class="form-control font-monospace text-success fw-bold input-ribuan" value="<?= old('harga_jual') ? number_format((float) str_replace('.', '', (string) old('harga_jual')), 0, ',', '.') : (isset($produk['harga_jual']) ? number_format((float) $produk['harga_jual'], 0, ',', '.') : '0') ?>" placeholder="0" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Grosir (Tier 2)</label>
                    <input type="text" inputmode="numeric" name="harga_grosir" class="form-control font-monospace input-ribuan" value="<?= old('harga_grosir') ? number_format((float) str_replace('.', '', (string) old('harga_grosir')), 0, ',', '.') : (isset($produk['harga_grosir']) ? number_format((float) $produk['harga_grosir'], 0, ',', '.') : '') ?>" placeholder="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Distributor (Tier 3)</label>
                    <input type="text" inputmode="numeric" name="harga_distributor" class="form-control font-monospace input-ribuan" value="<?= old('harga_distributor') ? number_format((float) str_replace('.', '', (string) old('harga_distributor')), 0, ',', '.') : (isset($produk['harga_distributor']) ? number_format((float) $produk['harga_distributor'], 0, ',', '.') : '') ?>" placeholder="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Batas Stok Minimum (Low Stock Alert)</label>
                    <input type="number" name="stok_minimum" class="form-control" value="<?= old('stok_minimum', $produk['stok_minimum'] ?? '5') ?>" min="0">
                    <small class="text-muted">Notifikasi akan muncul di dashboard jika stok mencapai batas ini.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status Produk</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= old('status', $produk['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif (Dapat Ditransaksikan)</option>
                        <option value="inactive" <?= old('status', $produk['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <a href="<?= base_url('master/produk') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                <button type="submit" class="btn btn-primary px-4 w-100 w-sm-auto"><i class="fa-solid fa-floppy-disk me-2"></i>Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function formatRibuan(val) {
        if (!val) return '';
        let numStr = val.toString().replace(/[^0-9]/g, '');
        if (!numStr) return '';
        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).ready(function() {
        // Auto format pemisah ribuan titik saat input
        $(document).on('input', '.input-ribuan', function() {
            let cursor = this.selectionStart;
            let prevLen = this.value.length;
            let formatted = formatRibuan(this.value);
            this.value = formatted;
            let diff = formatted.length - prevLen;
            this.setSelectionRange(cursor + diff, cursor + diff);
        });

        // Format nilai awal jika ada
        $('.input-ribuan').each(function() {
            if (this.value && this.value !== '0') {
                this.value = formatRibuan(this.value);
            }
        });

        // Hapus titik sebelum submit agar diterima sebagai angka murni
        $('form').on('submit', function() {
            $('.input-ribuan').each(function() {
                if (this.value) {
                    this.value = this.value.replace(/\./g, '');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

