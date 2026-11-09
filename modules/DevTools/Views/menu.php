<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Navigasi &amp; Menu Builder</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                Struktur Sidebar &amp; Top Menu
            </span>
        </div>
        <p class="text-muted small mb-0">
            Kustomisasi hierarki navigasi antarmuka, pengaturan sub-menu, dan pengelompokan fitur aplikasi.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalAddMenuNew">
            <i class="fa-solid fa-plus me-1"></i>Tambah Item Menu
        </button>
        <button type="button" class="btn btn-sm btn-light border fw-semibold px-3 text-secondary" data-bs-toggle="modal" data-bs-target="#modalAddMenuType">
            <i class="fa-solid fa-folder-plus text-primary me-1"></i>Tipe Menu Baru
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: MENU TYPE -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-layer-group text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark">Tipe Penempatan Menu</h6>
                </div>
                <span class="badge bg-light text-secondary border small">2 Tipe</span>
            </div>
            <div class="card-body p-3">
                <!-- ACTIVE: SIDE MENU -->
                <div class="p-3 rounded-3 mb-3 border border-primary bg-primary bg-opacity-10 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-bars-staggered text-primary"></i>
                        <div>
                            <span class="fw-bold text-primary small d-block">Sidebar Menu (Utama)</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Navigasi vertikal sisi kiri</span>
                        </div>
                    </div>
                    <span class="badge bg-primary text-white rounded-pill px-2 py-1 small">Aktif</span>
                </div>

                <!-- TOP MENU -->
                <div class="p-3 bg-white border rounded-3 d-flex justify-content-between align-items-center mb-4 shadow-xs" id="topMenuItemBox">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-window-maximize text-secondary"></i>
                        <div>
                            <span class="fw-semibold text-dark small d-block">Top Navigation Menu</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Header horizontal</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light border text-danger py-1 px-2" title="Hapus Tipe" onclick="Swal.fire({ title: 'Konfirmasi', text: 'Hapus menu type ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#C0392B', confirmButtonText: 'Hapus', cancelButtonText: 'Batal' }).then((r) => { if (r.isConfirmed) { document.getElementById('topMenuItemBox')?.remove(); } });">
                        <i class="fa-solid fa-trash-can small"></i>
                    </button>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-semibold py-2" data-bs-toggle="modal" data-bs-target="#modalAddMenuType">
                    <i class="fa-solid fa-plus me-1"></i>Tambah Tipe Menu
                </button>
            </div>
        </div>
    </div>

    <!-- RIGHT: MENU SIDE MENU TREE -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sitemap text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark">Hierarki Menu Sidebar (Aktif)</h6>
                </div>
                <div class="small text-muted">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>Klik dua kali untuk toggle status aktif
                </div>
            </div>
            <div class="card-body p-4">
                <!-- MODERN SOFT NOTICE -->
                <div class="alert alert-info bg-primary bg-opacity-10 border-0 text-dark small py-2 px-3 mb-4 rounded-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info text-primary fs-6"></i>
                    <div>
                        Struktur menu ini terhubung otomatis dengan sistem izin <strong>Access Matrix</strong>. Anda dapat mengklik dua kali item untuk menyembunyikan/menampilkan dari sidebar.
                    </div>
                </div>

                <!-- TREE LIST -->
                <div class="d-flex flex-column gap-2 small">
                    <!-- Dashboard -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-gauge text-primary me-1"></i>
                            <span class="fw-semibold text-dark">Dashboard Utama</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Root</span>
                    </div>

                    <!-- Master Data -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-database text-secondary me-1"></i>
                            <span class="fw-semibold text-dark">Master Data</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Folder</span>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-tag text-secondary small"></i>
                            <span>Pelanggan</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/master/pelanggan</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-truck-moving text-secondary small"></i>
                            <span>Supplier</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/master/supplier</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-box text-secondary small"></i>
                            <span>Produk</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/master/produk</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-tags text-secondary small"></i>
                            <span>Kategori Barang</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/master/kategori</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-scale-balanced text-secondary small"></i>
                            <span>Unit Barang (Satuan)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/master/satuan</code>
                    </div>

                    <!-- Manajemen Stok -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-boxes-stacked text-secondary me-1"></i>
                            <span class="fw-semibold text-dark">Manajemen Stok</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Folder</span>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-secondary small"></i>
                            <span>Dashboard Stok</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/stok</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-secondary small"></i>
                            <span>Stock Opname Fisik</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/stok/opname</code>
                    </div>

                    <!-- Pembelian -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-cart-shopping text-secondary me-1"></i>
                            <span class="fw-semibold text-dark">Pembelian</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Folder</span>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-lines text-secondary small"></i>
                            <span>Purchase Order (PO)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/pembelian</code>
                    </div>

                    <!-- Penjualan -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-bag-shopping text-secondary me-1"></i>
                            <span class="fw-semibold text-dark">Penjualan</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Folder</span>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-cash-register text-secondary small"></i>
                            <span>Kasir Cepat (POS)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/pos</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-invoice text-secondary small"></i>
                            <span>Transaksi Penjualan (SO)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/penjualan</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-shop text-secondary small"></i>
                            <span>Penjualan Marketplace</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/marketplace</code>
                    </div>

                    <!-- Keuangan & Kas -->
                    <div class="p-2 px-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-xs menu-tree-item mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-grip-vertical text-muted"></i>
                            <i class="fa-solid fa-money-bill-wave text-secondary me-1"></i>
                            <span class="fw-semibold text-dark">Keuangan &amp; Kas</span>
                        </div>
                        <span class="badge bg-light text-secondary border">Folder</span>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-secondary small"></i>
                            <span>Piutang Usaha (AR)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/keuangan/piutang</code>
                    </div>
                    <div class="ms-4 p-2 px-3 border-start border-2 border-primary ps-3 bg-light bg-opacity-50 rounded-2 d-flex justify-content-between align-items-center menu-tree-item">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-receipt text-secondary small"></i>
                            <span>Utang Usaha (AP)</span>
                        </div>
                        <code class="text-muted font-monospace" style="font-size: 0.78rem;">/keuangan/utang</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD MENU TYPE -->
<div class="modal fade" id="modalAddMenuType" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-folder-plus me-2"></i>Tambah Tipe Menu</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('dev/menu/type/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nama Tipe Menu <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Footer Menu" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Definisi &amp; Penempatan</label>
                        <textarea name="definition" class="form-control form-control-sm" rows="2" placeholder="Deskripsikan lokasi penempatan menu ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Tipe Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ADD MENU NEW -->
<div class="modal fade" id="modalAddMenuNew" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Item Menu Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('dev/menu/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Label Menu <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control form-control-sm" placeholder="e.g. Laporan Analisis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">URL / Route Path <span class="text-danger">*</span></label>
                        <input type="text" name="route" class="form-control form-control-sm font-monospace" placeholder="e.g. laporan/analisis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Menu Induk (Parent Menu)</label>
                        <select name="parent" class="form-select form-select-sm">
                            <option value="">-- Menu Utama (Root / Top Level) --</option>
                            <option value="dashboard">Dashboard</option>
                            <option value="master">Master Data</option>
                            <option value="penjualan">Penjualan</option>
                            <option value="pembelian">Pembelian</option>
                            <option value="keuangan">Keuangan & Kas</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Class Icon FontAwesome</label>
                        <input type="text" name="icon" class="form-control form-control-sm font-monospace" placeholder="fa-solid fa-chart-pie">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Item Menu
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
    // Double click to active/inactive
    document.querySelectorAll('.menu-tree-item').forEach(item => {
        item.style.cursor = 'pointer';
        item.title = 'Klik 2x untuk toggle status Aktif/Nonaktif';
        item.addEventListener('dblclick', function() {
            if (this.classList.contains('opacity-50')) {
                this.classList.remove('opacity-50', 'bg-light');
                this.style.textDecoration = 'none';
                this.style.borderColor = '#10b981';
            } else {
                this.classList.add('opacity-50', 'bg-light');
                this.style.textDecoration = 'line-through';
                this.style.borderColor = '#ef4444';
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
