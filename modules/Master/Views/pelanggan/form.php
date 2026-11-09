<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('master/pelanggan') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Pelanggan
    </a>
    <h3 class="fw-bold mt-2 mb-1"><?= esc($title) ?></h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <?php $isEdit = isset($pelanggan); ?>
        <form action="<?= $isEdit ? base_url('master/pelanggan/update/' . $pelanggan['id']) : base_url('master/pelanggan/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kode Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_pelanggan" class="form-control text-uppercase font-monospace" value="<?= old('kode_pelanggan', $pelanggan['kode_pelanggan'] ?? $autoKode ?? '') ?>" placeholder="CONTOH: CUST-0002" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap Pelanggan / Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pelanggan" class="form-control" value="<?= old('nama_pelanggan', $pelanggan['nama_pelanggan'] ?? '') ?>" placeholder="Nama pelanggan" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor WhatsApp / Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control font-monospace" value="<?= old('no_telp', $pelanggan['no_telp'] ?? '') ?>" placeholder="08xxxxxxxxxx / 628xxxxxxxxxx" required>
                    <small class="text-muted">Nomor ini digunakan untuk otomatisasi pesan after-sales CRM.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email (Opsional)</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email', $pelanggan['email'] ?? '') ?>" placeholder="email@contoh.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kota</label>
                    <input type="text" name="kota" class="form-control" value="<?= old('kota', $pelanggan['kota'] ?? '') ?>" placeholder="Contoh: Surabaya, Jakarta, Bandung">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipe Pelanggan (Tiering Harga)</label>
                    <select name="tipe_pelanggan" class="form-select">
                        <option value="retail" <?= old('tipe_pelanggan', $pelanggan['tipe_pelanggan'] ?? 'retail') === 'retail' ? 'selected' : '' ?>>Retail (Konsumen Akhir)</option>
                        <option value="grosir" <?= old('tipe_pelanggan', $pelanggan['tipe_pelanggan'] ?? '') === 'grosir' ? 'selected' : '' ?>>Grosir (Harga Tier 2)</option>
                        <option value="distributor" <?= old('tipe_pelanggan', $pelanggan['tipe_pelanggan'] ?? '') === 'distributor' ? 'selected' : '' ?>>Distributor (Harga Tier 3)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Limit Piutang (Kredit)</label>
                    <input type="text" inputmode="numeric" name="limit_kredit" class="form-control font-monospace input-ribuan" value="<?= old('limit_kredit') ? number_format((float) str_replace('.', '', (string) old('limit_kredit')), 0, ',', '.') : (isset($pelanggan['limit_kredit']) ? number_format((float) $pelanggan['limit_kredit'], 0, ',', '.') : '0') ?>" placeholder="0">
                    <small class="text-muted">Batas maksimal piutang belum terbayar yang diizinkan.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tempo Pembayaran Default (Hari)</label>
                    <input type="number" name="tempo_hari" class="form-control" value="<?= old('tempo_hari', $pelanggan['tempo_hari'] ?? '0') ?>" min="0">
                    <small class="text-muted">Jumlah hari sebelum faktur jatuh tempo (0 = Tunai langsung).</small>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat jalan, nomor, kecamatan, kode pos"><?= old('alamat', $pelanggan['alamat'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                <button type="submit" class="btn btn-primary px-4 w-100 w-sm-auto"><i class="fa-solid fa-floppy-disk me-2"></i>Simpan Pelanggan</button>
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

