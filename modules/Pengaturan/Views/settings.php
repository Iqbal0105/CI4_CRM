<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">Settings &amp; Profil Perusahaan</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Konfigurasi Parameter ERP &amp; Integrasi</span>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Pengaturan &amp; Akses</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Settings</span>
    </div>
</div>
<form action="<?= base_url('pengaturan/settings') ?>" method="POST" id="formSettings">
    <?= csrf_field() ?>
    <div class="row g-4">
        <!-- 1. INFORMASI PERUSAHAAN -->
        <div class="col-12 col-lg-7">
            <div class="card border shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-building text-primary me-2"></i>Identitas Bisnis &amp; Legalitas</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Bisnis / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="IDMETAFORA Enterprise" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Slogan / Tagline Bisnis</label>
                        <input type="text" name="company_tagline" class="form-control" value="ERP, CRM &amp; Omnichannel Modern Platform">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="company_phone" class="form-control" value="081234567890" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Email Perusahaan <span class="text-danger">*</span></label>
                            <input type="email" name="company_email" class="form-control" value="contact@idmetafora.com" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">NPWP Perusahaan</label>
                            <input type="text" name="company_npwp" class="form-control font-monospace" value="01.234.567.8-901.000">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Rekening Bank Faktur</label>
                            <input type="text" name="company_bank" class="form-control" value="BCA 8820192831 a/n PT IDMETAFORA">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Kantor Pusat</label>
                        <textarea name="company_address" class="form-control" rows="3">Jl. Ringroad Utara, Condongcatur, Depok, Sleman, D.I. Yogyakarta 55283</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PARAMETER TRANSAKSI & PREFERENSI -->
        <div class="col-12 col-lg-5">
            <div class="card border shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders text-success me-2"></i>Parameter Sistem &amp; Transaksi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Mata Uang Utama</label>
                            <select name="default_currency" class="form-select">
                                <option value="IDR" selected>IDR - Rupiah Indonesia</option>
                                <option value="USD">USD - US Dollar</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tarif PPN Default (%)</label>
                            <input type="number" step="0.1" name="default_tax" class="form-control" value="11.0">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Batas Stok Minimum</label>
                            <input type="number" name="min_stock_alert" class="form-control" value="5">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jatuh Tempo SO (Hari)</label>
                            <input type="number" name="default_due_days" class="form-control" value="30">
                        </div>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="autoPrintReceipt" checked>
                        <label class="form-check-label small fw-semibold" for="autoPrintReceipt">Cetak struk kasir otomatis setelah checkout POS</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enableWaAlert" checked>
                        <label class="form-check-label small fw-semibold" for="enableWaAlert">Aktifkan pengingat otomatis (Follow-Up H+)</label>
                    </div>
                </div>
            </div>

            <!-- SIMPAN CARD -->
            <div class="card border shadow-sm bg-light" style="border-radius: 10px;">
                <div class="card-body p-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan Pengaturan
                    </button>
                    <p class="text-muted small mt-2 mb-0">Perubahan akan langsung diterapkan di seluruh modul ERP &amp; POS.</p>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

