<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Select2 Pelanggan POS */
    .input-group:has(#selectPelanggan) .select2-container--bootstrap-5,
    .input-group > .select2-container--bootstrap-5 {
        flex: 1 1 auto !important;
        width: 1% !important;
    }
    .input-group:has(#selectPelanggan) .select2-container--bootstrap-5 .select2-selection,
    .input-group > .select2-container--bootstrap-5 .select2-selection {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-color: #cbd5e1 !important;
        height: 31px !important;
        min-height: 31px !important;
        position: relative !important;
        padding-left: 8px !important;
        padding-right: 44px !important; /* Ruang eksklusif untuk icon clear dan arrow */
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        font-size: 0.84rem !important;
        display: flex !important;
        align-items: center !important;
    }
    .input-group > .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .input-group > .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #2D6A4F !important;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15) !important;
    }
    .input-group > .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0 !important;
        margin: 0 !important;
        line-height: normal !important;
        color: #1e293b !important;
        font-weight: 500 !important;
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        flex: 1 1 auto !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    .input-group > .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 4px !important;
        width: 16px !important;
        height: 16px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
    }
    .input-group > .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 22px !important;
        width: 16px !important;
        height: 16px !important;
        line-height: 16px !important;
        text-align: center !important;
        margin: 0 !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1rem !important;
        color: #94a3b8 !important;
        cursor: pointer !important;
        z-index: 2 !important;
    }
    .input-group > .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear:hover {
        color: #ef4444 !important;
    }
    .select2-dropdown .select2-search--dropdown {
        padding: 6px 8px !important;
    }
    .select2-dropdown .select2-search__field {
        border-radius: 4px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 6px 10px !important;
        font-size: 0.85rem !important;
    }
    .select2-dropdown .select2-search__field:focus {
        border-color: #2D6A4F !important;
        box-shadow: 0 0 0 2px rgba(45, 106, 79, 0.15) !important;
        outline: none !important;
    }
    .select2-results__option {
        font-size: 0.84rem !important;
        padding: 6px 10px !important;
    }
    .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
    }
    .pos-screen {
        margin-top: 0;
    }
    .grand-total-display {
        font-size: 2rem;
        font-weight: 800;
        color: #059669;
        font-family: monospace;
        letter-spacing: -0.02em;
    }
    .kembalian-display {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2D6A4F;
        font-family: monospace;
    }
    .pos-product-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #E8E4DC);
        border-radius: var(--radius-md, 10px);
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        user-select: none;
        overflow: hidden;
    }
    .product-card-col {
        container-type: inline-size;
    }
    @container (max-width: 250px) {
        .btn-pos-add-text {
            display: none !important;
        }
        .btn-pos-add {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
    }
    .btn-pos-add.btn-icon-only .btn-pos-add-text {
        display: none !important;
    }
    .btn-pos-add.btn-icon-only {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    .pos-product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(45, 106, 79, 0.12);
        border-color: #2D6A4F;
    }
    .pos-product-card:active {
        transform: scale(0.98);
    }
    .pos-product-card.out-of-stock {
        opacity: 0.65;
        background: #fbfbfb;
        cursor: not-allowed;
    }
    .category-pill {
        cursor: pointer;
        white-space: nowrap;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .category-pill.active {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    .product-grid-container {
        max-height: 640px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(45, 106, 79, 0.3) transparent;
        padding-right: 4px;
    }
    .product-grid-container::-webkit-scrollbar {
        width: 5px;
    }
    .product-grid-container::-webkit-scrollbar-thumb {
        background: rgba(45, 106, 79, 0.3);
        border-radius: 4px;
    }
    .cart-table-container {
        max-height: 250px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .input-row-qty::-webkit-outer-spin-button,
    .input-row-qty::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .input-row-qty {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <h4 class="fw-bold text-dark mb-0 d-inline-block">
            <i class="fa-solid fa-bolt text-warning me-1"></i>Penjualan Cepat (POS)
        </h4>
        <span class="text-muted small ms-1 d-none d-md-inline">Kasir Penjualan Langsung Lunas</span>
        <span class="badge bg-success rounded-pill px-2 py-1 small">&#10003; Auto-Faktur &amp; Potong Stok</span>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Penjualan</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">Penjualan Cepat (POS)</span>
    </div>
</div>

<!-- QUICK STATUS STRIP -->
<div class="card shadow-sm border mb-3">
    <div class="card-body p-2 d-flex flex-wrap align-items-center justify-content-between gap-2 bg-light bg-opacity-50">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge bg-success px-3 py-2 fw-semibold"><i class="fa-solid fa-bolt me-1"></i>POS DIRECT</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-check me-1"></i>Auto-Faktur</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-check me-1"></i>Potong Stok Otomatis</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-check me-1"></i>Kas BKM Masuk</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Web Bluetooth Thermal Printer Controller Widget -->
            <div class="dropdown">
                <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 dropdown-toggle py-1 px-2" id="btnBluetoothStatus" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.775rem;">
                    <i class="fa-brands fa-bluetooth-b text-primary"></i>
                    <span id="labelBluetoothName">Printer Bluetooth: Siap</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="min-width: 270px; font-size: 0.82rem;">
                    <li class="dropdown-header text-uppercase fw-bold pb-1" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-print me-1 text-primary"></i>Web Bluetooth Thermal
                    </li>
                    <li>
                        <button type="button" class="dropdown-item rounded py-1 px-2 d-flex align-items-center gap-2" id="btnActionConnectBluetooth">
                            <i class="fa-brands fa-bluetooth-b text-primary"></i>
                            <span>Sandingkan Bluetooth (BLE)...</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item rounded py-1 px-2 d-flex align-items-center gap-2" id="btnActionConnectSerial">
                            <i class="fa-solid fa-plug text-success"></i>
                            <span>Hubungkan USB / Serial COM...</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item rounded py-1 px-2 d-flex align-items-center gap-2" id="btnActionTestPrint">
                            <i class="fa-solid fa-receipt text-warning"></i>
                            <span>Uji Cetak (Test Print)</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item rounded py-1 px-2 d-flex align-items-center gap-2 text-danger" id="btnActionBtHelp">
                            <i class="fa-solid fa-circle-question"></i>
                            <span>Solusi Jika Perangkat Tidak Ketemu</span>
                        </button>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li class="px-2 py-1">
                        <label class="form-label mb-1 text-muted" style="font-size: 0.72rem;">Lebar Kertas Thermal:</label>
                        <select class="form-select form-select-sm" id="selectThermalPaperWidth" style="font-size: 0.78rem;">
                            <option value="32" selected>58 mm (32 Karakter / Mini Portable)</option>
                            <option value="48">80 mm (48 Karakter / Kasir Standar)</option>
                        </select>
                    </li>
                    <li id="liDisconnectBt" style="display:none;"><hr class="dropdown-divider my-1"></li>
                    <li id="liDisconnectBtBtn" style="display:none;">
                        <button type="button" class="dropdown-item rounded py-1 px-2 text-danger d-flex align-items-center gap-2" id="btnActionDisconnectBluetooth">
                            <i class="fa-solid fa-unlink"></i>
                            <span>Putuskan Koneksi</span>
                        </button>
                    </li>
                </ul>
            </div>
            <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.775rem;">
                <i class="fa-solid fa-list me-1"></i>Daftar Penjualan
            </a>
        </div>
    </div>
</div>

<!-- MAIN POS INTERFACE: 2-COLUMN SPLIT -->
<div class="row g-3">
    <!-- LEFT PANEL: KATALOG PRODUK SIAP JUAL -->
    <div class="col-12 col-xl-7 col-lg-6">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark small">
                        <i class="fa-solid fa-boxes-stacked text-success me-1"></i>Katalog Produk Siap Jual
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle small" id="totalProdukBadge">
                        <?= count($produk ?? []) ?> Produk
                    </span>
                </div>
                <div class="text-muted small d-none d-sm-block">
                    Klik produk untuk tambah ke keranjang
                </div>
            </div>

            <div class="card-body p-3">
                <!-- SEARCH & FILTER BAR -->
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="text" id="searchProdukCatalog" class="form-control border-start-0" placeholder="Ketik nama produk, kode SKU, atau scan barcode (F2)..." autocomplete="off" autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="btnClearSearch" title="Hapus Pencarian">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <!-- CATEGORY PILLS -->
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-1 overflow-x-auto pb-1" style="scrollbar-width: none;">
                            <button type="button" class="btn btn-xs rounded-pill px-3 py-1 btn-outline-success category-pill active" data-category="all">
                                Semua (<?= count($produk ?? []) ?>)
                            </button>
                            <?php foreach ($kategori as $k): ?>
                                <?php
                                    $catCount = 0;
                                    if (!empty($produk)) {
                                        foreach ($produk as $pr) {
                                            if ((int)($pr['kategori_id'] ?? 0) === (int)$k['id']) {
                                                $catCount++;
                                            }
                                        }
                                    }
                                ?>
                                <button type="button" class="btn btn-xs rounded-pill px-3 py-1 btn-outline-secondary category-pill" data-category="<?= $k['id'] ?>">
                                    <?= esc($k['nama_kategori']) ?> (<?= $catCount ?>)
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- PRODUCT CARDS GRID -->
                <div class="product-grid-container" id="productGridContainer">
                    <?php if (empty($produk)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fs-1 mb-2 text-secondary opacity-50 d-block"></i>
                            <div class="fw-semibold">Belum ada produk di database</div>
                            <p class="small text-muted mb-3">Silakan input data produk terlebih dahulu di modul Master Produk.</p>
                            <a href="<?= base_url('master/produk/create') ?>" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-plus me-1"></i>Tambah Produk Baru
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row g-2" id="productGrid">
                            <?php foreach ($produk as $p): ?>
                                <?php
                                    $stokTersedia = (float)(($p['stok_fisik'] ?? 0) - ($p['stok_reserved'] ?? 0));
                                    $isOutOfStock = $stokTersedia <= 0;
                                ?>
                                <div class="col-6 col-sm-6 col-md-4 col-xl-4 product-card-col"
                                     data-kategori="<?= esc($p['kategori_id'] ?? '') ?>"
                                     data-search="<?= strtolower(esc($p['nama_produk'] . ' ' . $p['kode_produk'] . ' ' . ($p['barcode'] ?? '') . ' ' . ($p['nama_kategori'] ?? ''))) ?>">
                                    <div class="pos-product-card p-2 p-sm-3 <?= $isOutOfStock ? 'out-of-stock' : '' ?>"
                                         data-id="<?= $p['id'] ?>"
                                         data-kode="<?= esc($p['kode_produk']) ?>"
                                         data-barcode="<?= esc($p['barcode'] ?? '') ?>"
                                         data-nama="<?= esc($p['nama_produk']) ?>"
                                         data-harga="<?= (float)$p['harga_jual'] ?>"
                                         data-satuan="<?= esc($p['nama_satuan'] ?? 'PCS') ?>"
                                         data-stok="<?= $stokTersedia ?>">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                                <span class="badge bg-light text-secondary border text-truncate" style="font-size: 0.68rem; flex: 1 1 auto; min-width: 0; text-align: left;" title="<?= esc($p['nama_kategori'] ?? 'Umum') ?>">
                                                    <?= esc($p['nama_kategori'] ?? 'Umum') ?>
                                                </span>
                                                <?php if ($isOutOfStock): ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle flex-shrink-0 text-nowrap" style="font-size: 0.68rem;">
                                                        Habis
                                                    </span>
                                                <?php elseif ($stokTersedia <= ($p['stok_minimum'] ?? 5)): ?>
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle flex-shrink-0 text-nowrap" style="font-size: 0.68rem;">
                                                        Sisa <?= number_format($stokTersedia, 0, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle flex-shrink-0 text-nowrap" style="font-size: 0.68rem;">
                                                        Stok <?= number_format($stokTersedia, 0, ',', '.') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="fw-bold text-dark small lh-sm mb-1 text-truncate-2" style="min-height: 2.2em;" title="<?= esc($p['nama_produk']) ?>">
                                                <?= esc($p['nama_produk']) ?>
                                            </div>
                                            <div class="small text-muted font-monospace" style="font-size: 0.72rem;">
                                                <?= esc($p['kode_produk']) ?>
                                            </div>
                                        </div>

                                        <div class="mt-2 pt-2 border-top">
                                            <div class="d-flex align-items-center justify-content-between gap-1 pos-card-footer">
                                                <div class="fw-bold font-monospace text-success text-nowrap product-price" style="font-size: 0.88rem;">
                                                    Rp&nbsp;<?= number_format((float)$p['harga_jual'], 0, ',', '.') ?>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 text-nowrap flex-shrink-0 btn-pos-add" style="font-size: 0.72rem; min-height: 25px; display: inline-flex; align-items: center; justify-content: center;" title="Tambah ke keranjang" <?= $isOutOfStock ? 'disabled' : '' ?>>
                                                    <i class="fa-solid fa-plus"></i><span class="btn-pos-add-text ms-1">Tambah</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="productNotFound" class="text-center py-5 text-muted d-none">
                            <i class="fa-solid fa-magnifying-glass fs-2 mb-2 text-secondary opacity-50 d-block"></i>
                            <div class="fw-semibold">Produk tidak ditemukan</div>
                            <p class="small text-muted mb-2">Tidak ada produk yang cocok dengan kata kunci pencarian.</p>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetFilter">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reset Filter
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: TRANSAKSI, KERANJANG & CHECKOUT -->
    <div class="col-12 col-xl-5 col-lg-6">
        <!-- 1. PELANGGAN & TRANSAKSI -->
        <div class="card shadow-sm border mb-3">
            <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between">
                <div class="fw-bold text-dark small">
                    <i class="fa-solid fa-user text-primary me-2"></i>Pelanggan &amp; Transaksi
                </div>
                <div class="text-muted small">
                    No. SO: <strong class="font-monospace text-primary" id="currentSoNumberDisplay"><?= esc($nextTrxNumber ?? 'Otomatis') ?></strong>
                </div>
            </div>
            <div class="card-body p-2 p-sm-3">
                <div class="row g-2">
                    <div class="col-12 col-sm-7">
                        <label class="form-label small fw-semibold mb-1">Pelanggan <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <select id="selectPelanggan" class="form-select select2">
                                <option value="1">-- Pelanggan Umum (Walk-in) --</option>
                                <?php foreach ($pelanggan as $p): ?>
                                    <?php if ($p['id'] != 1): ?>
                                        <option value="<?= $p['id'] ?>" data-kode="<?= esc($p['kode_pelanggan']) ?>" data-phone="<?= esc($p['no_telp'] ?? '') ?>">[<?= esc($p['kode_pelanggan']) ?>] <?= esc($p['nama_pelanggan']) ?><?= !empty($p['no_telp']) ? ' (' . esc($p['no_telp']) . ')' : '' ?> - <?= ucfirst(esc($p['tipe_pelanggan'])) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <a href="<?= base_url('master/pelanggan/create') ?>" class="btn btn-outline-primary" title="Tambah Pelanggan">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label class="form-label small fw-semibold mb-1">Tanggal</label>
                        <input type="date" id="tanggalPenjualan" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. KERANJANG ITEM PESANAN -->
        <div class="card shadow-sm border mb-3">
            <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark small">
                        <i class="fa-solid fa-cart-shopping text-primary me-1"></i>Keranjang
                    </span>
                    <span class="badge bg-primary rounded-pill px-2 py-0" id="itemCountBadge" style="font-size: 0.72rem;">0 Item</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" id="btnKosongkanCart" title="Kosongkan Keranjang" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-trash me-1"></i>Reset
                </button>
            </div>

            <!-- CART TABLE -->
            <div class="card-body p-0">
                <div class="cart-table-container">
                    <table class="table align-middle mb-0" id="cartTable" style="font-size: 0.82rem;">
                        <thead class="table-light sticky-top" style="font-size: 0.72rem;">
                            <tr class="text-muted text-uppercase">
                                <th>PRODUK</th>
                                <th class="text-center" style="width: 105px; min-width: 100px;">QTY</th>
                                <th class="text-end" style="width: 100px;">HARGA</th>
                                <th class="text-end" style="width: 105px;">SUBTOTAL</th>
                                <th class="text-center" style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                            <tr id="emptyCartRow">
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-cart-shopping fs-4 mb-1 d-block text-secondary opacity-50"></i>
                                    Keranjang masih kosong.<br>
                                    <small class="text-muted">Klik produk pada katalog di sebelah kiri.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CART FOOTER STRIP -->
            <div class="card-footer bg-white py-2 d-flex align-items-center justify-content-between">
                <span class="text-muted small">Subtotal Keranjang:</span>
                <span class="fw-bold font-monospace text-dark" id="subtotalDisplay" style="font-size: 1.05rem;">Rp 0</span>
            </div>
        </div>

        <!-- 3. TERMINAL PEMBAYARAN & CHECKOUT -->
        <div class="card shadow-sm border mb-3">
            <div class="card-header text-white py-2 d-flex align-items-center justify-content-between" style="background-color: #059669;">
                <div class="fw-bold small">
                    <i class="fa-solid fa-cash-register me-2"></i>Terminal Pembayaran Kasir
                </div>
                <span class="badge bg-white text-success fw-bold" style="font-size: 0.7rem;">LUNAS DIRECT</span>
            </div>

            <div class="card-body p-3">
                <!-- TOTAL DISPLAY -->
                <div class="p-3 rounded border text-center mb-3" style="background-color: #f0fdf4; border-color: #bbf7d0 !important;">
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">TOTAL HARUS DIBAYAR:</div>
                    <div class="grand-total-display my-1" id="grandTotalDisplay">Rp 0</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                        Subtotal: <span id="summarySubtotal">Rp 0</span> &bull; Diskon: <span id="summaryDiskon">0%</span> &bull; PPN: <span id="summaryPajak">0%</span>
                    </div>
                </div>

                <!-- DISKON, PPN & METODE -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">Diskon Nota (%)</label>
                        <input type="number" id="diskonPersenInput" class="form-control form-control-sm font-monospace text-center" value="0" min="0" max="100">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">Pajak PPN (%)</label>
                        <select id="ppnSelect" class="form-select form-select-sm">
                            <option value="0" selected>0% (Non-PPN)</option>
                            <option value="11">11%</option>
                            <option value="12">12%</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">Metode Bayar <span class="text-danger">*</span></label>
                        <select id="metodeBayarSelect" class="form-select form-select-sm">
                            <option value="tunai" selected>💵 Tunai (Cash)</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="debit">💳 Kartu Debit</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">Akun Kas / Bank <span class="text-danger">*</span></label>
                        <select id="kasBankSelect" class="form-select form-select-sm">
                            <option value="1" selected>Cash Kasir (IDR)</option>
                            <option value="2">Bank BCA Operasional</option>
                            <option value="3">Kas QRIS</option>
                        </select>
                    </div>
                </div>

                <!-- CASH INPUT & CHANGE -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.78rem;">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                    <input type="text" id="uangDiterimaInput" class="form-control font-monospace fw-bold text-success text-end input-ribuan" placeholder="0" style="font-size: 1.15rem;">
                </div>

                <div class="p-2 rounded bg-light border text-center mb-3">
                    <span class="text-muted small text-uppercase" style="font-size: 0.7rem;">Kembalian:</span>
                    <div class="kembalian-display my-0" id="kembalianDisplay">Rp 0</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success py-2 px-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" id="btnKonfirmasiCheckout">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                        <span>BAYAR &amp; CETAK STRUK (F4)</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" id="btnCheckoutBluetoothDirect" style="font-size: 0.85rem;">
                        <i class="fa-brands fa-bluetooth-b text-primary fs-5"></i>
                        <span>Bayar &amp; Cetak Bluetooth Thermal</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL TRANSAKSI BERHASIL & CETAK THERMAL BLUETOOTH       -->
<!-- ======================================================== -->
<div class="modal fade" id="modalPosSuccess" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalPosSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0 text-white" id="modalPosSuccessLabel">
                    <i class="fa-solid fa-circle-check me-2"></i>Transaksi Kasir Berhasil!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 bg-white text-center">
                <div class="mb-3">
                    <div class="text-muted small text-uppercase fw-semibold">Kembalian Pelanggan:</div>
                    <div class="display-6 fw-bold text-success my-1 font-monospace" id="modalSuccessKembalian">Rp 0</div>
                </div>

                <div class="p-3 bg-light rounded text-start small mb-3 border">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">No. Transaksi (SO):</span>
                        <strong class="font-monospace text-dark" id="modalSuccessNoSo">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">No. Invoice:</span>
                        <strong class="font-monospace text-primary" id="modalSuccessNoInv">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Belanja:</span>
                        <strong class="text-dark" id="modalSuccessTotal">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Uang Diterima:</span>
                        <strong class="text-dark" id="modalSuccessBayar">Rp 0</strong>
                    </div>
                </div>

                <!-- Bluetooth Status Indicator inside Modal -->
                <div class="alert alert-info py-2 px-3 small d-flex align-items-center justify-content-between mb-3 text-start">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-brands fa-bluetooth-b text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold text-dark" id="modalBtStatusText">Printer Bluetooth Siap Digunakan</div>
                            <small class="text-muted">Cetak langsung via Web Bluetooth tanpa membuka dialog print browser.</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnModalPrintBluetooth">
                        <i class="fa-brands fa-bluetooth-b fs-5"></i>
                        <span>Cetak via Bluetooth Thermal</span>
                    </button>
                    <button type="button" class="btn btn-success py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnModalPrintSerial">
                        <i class="fa-solid fa-plug fs-5"></i>
                        <span>Cetak via USB / Serial COM</span>
                    </button>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-1" id="btnModalPrintBrowser">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak Browser</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-dark w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-1" id="btnModalNewTransaction" data-bs-dismiss="modal">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span>Transaksi Baru (Enter)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/bluetooth-thermal-printer.js') ?>"></script>
<script>
    let cart = []; // [ { id, kode, nama, harga, satuan, stok, qty, diskon_pct, subtotal } ]
    let grandTotal = 0;
    let lastTransactionData = null;

    // Web Bluetooth Printer Setup & UI State Sync
    $(document).ready(function() {
        if (!window.posBluetoothPrinter.isSupported()) {
            $('#labelBluetoothName').html('<i class="fa-brands fa-bluetooth-b text-secondary"></i> Bluetooth Tidak Didukung');
            $('#btnBluetoothStatus').addClass('disabled text-muted');
        }

        window.posBluetoothPrinter.onStatusChange = function(connected, deviceName) {
            if (connected) {
                $('#labelBluetoothName').html('<i class="fa-brands fa-bluetooth-b text-success"></i> ' + (deviceName || 'Thermal Printer'));
                $('#btnBluetoothStatus').removeClass('btn-outline-primary btn-outline-danger').addClass('btn-outline-success');
                $('#liDisconnectBt, #liDisconnectBtBtn').show();
                $('#modalBtStatusText').html('Terhubung ke: <strong class="text-success">' + (deviceName || 'Thermal') + '</strong>');
            } else {
                $('#labelBluetoothName').html('<i class="fa-brands fa-bluetooth-b text-primary"></i> Printer Bluetooth: Siap');
                $('#btnBluetoothStatus').removeClass('btn-outline-success btn-outline-danger').addClass('btn-outline-primary');
                $('#liDisconnectBt, #liDisconnectBtBtn').hide();
                $('#modalBtStatusText').text('Printer Bluetooth Siap Digunakan');
            }
        };

        // Connect button (opens browser native pairing modal as in screenshot)
        $('#btnActionConnectBluetooth').on('click', async function(e) {
            e.preventDefault();
            try {
                const name = await window.posBluetoothPrinter.connectBluetooth();
                Swal.fire({
                    icon: 'success',
                    title: 'Printer Terhubung!',
                    text: 'Perangkat "' + name + '" siap mencetak struk thermal.',
                    timer: 2500,
                    showConfirmButton: false
                });
            } catch (err) {
                showBluetoothTroubleshootAlert(err.message);
            }
        });

        // Connect USB / Serial COM Port
        $('#btnActionConnectSerial').on('click', async function(e) {
            e.preventDefault();
            try {
                const name = await window.posBluetoothPrinter.connectSerial();
                Swal.fire({
                    icon: 'success',
                    title: 'Port USB/Serial Terhubung!',
                    text: 'Perangkat "' + name + '" siap mencetak struk thermal.',
                    timer: 2500,
                    showConfirmButton: false
                });
            } catch (err) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Port USB/Serial',
                    text: err.message || 'Gagal membuka port printer USB/Serial.'
                });
            }
        });

        // Help dialog on pairing
        function showBluetoothTroubleshootAlert(customMsg = null) {
            Swal.fire({
                icon: 'info',
                title: 'Panduan Pairing Bluetooth Printer',
                html: `
                    <div class="text-start small" style="line-height: 1.6;">
                        <p class="mb-2 text-danger fw-semibold">
                            ${customMsg || 'Jika muncul "Tidak ditemukan perangkat yang kompatibel":'}
                        </p>
                        <ol class="ps-3 mb-2">
                            <li><strong>Printer Thermal Hidup:</strong> Pastikan printer ON dan lampu indikator biru/hijau menyala.</li>
                            <li><strong>Lepas Koneksi HP Lain:</strong> Printer thermal portable hanya mendukung 1 koneksi aktif. Jika sebelumnya terkoneksi ke HP kasir, matikan Bluetooth di HP terlebih dahulu.</li>
                            <li><strong>Bluetooth Komputer ON:</strong> Pastikan Bluetooth di Windows / Laptop dalam posisi aktif.</li>
                            <li><strong>Gunakan USB / Serial COM:</strong> Jika printer bertipe <em>Bluetooth Classic (SPP)</em> atau kabel USB, gunakan tombol <strong>"Hubungkan USB / Serial COM"</strong>.</li>
                        </ol>
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#2D6A4F'
            });
        }

        $('#btnActionBtHelp').on('click', function(e) {
            e.preventDefault();
            showBluetoothTroubleshootAlert();
        });

        // Test Print
        $('#btnActionTestPrint').on('click', async function(e) {
            e.preventDefault();
            try {
                await window.posBluetoothPrinter.testPrint();
                Swal.fire({
                    icon: 'success',
                    title: 'Uji Cetak Terkirim!',
                    text: 'Struk uji coba berhasil dikirim ke printer thermal.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } catch (err) {
                if (err.name !== 'NotFoundError') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Uji Cetak',
                        text: err.message || 'Pastikan printer Bluetooth dalam kondisi aktif.'
                    });
                }
            }
        });

        // Paper Width Setup
        $('#selectThermalPaperWidth').val(window.posBluetoothPrinter.getPaperWidth());
        $('#selectThermalPaperWidth').on('change', function() {
            window.posBluetoothPrinter.setPaperWidth(this.value);
            Toast.fire({ icon: 'info', title: 'Format kertas: ' + (this.value === '48' ? '80 mm' : '58 mm') });
        });

        // Disconnect
        $('#btnActionDisconnectBluetooth').on('click', async function(e) {
            e.preventDefault();
            await window.posBluetoothPrinter.disconnect();
            Toast.fire({ icon: 'info', title: 'Koneksi Bluetooth diputuskan.' });
        });
    });

    function renderCart() {
        if (cart.length === 0) {
            $('#cartItems').html(`
                <tr id="emptyCartRow">
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-cart-shopping fs-4 mb-1 d-block text-secondary opacity-50"></i>
                        Keranjang masih kosong.<br>
                        <small class="text-muted">Klik produk pada katalog di sebelah kiri.</small>
                    </td>
                </tr>
            `);
            $('#itemCountBadge').text('0 Item');
            $('#subtotalDisplay').text('Rp 0');
            $('#summarySubtotal').text('Rp 0');
            $('#grandTotalDisplay').text('Rp 0');
            grandTotal = 0;
            calculateChange();
            return;
        }

        let html = '';
        let subtotal = 0;
        let totalQty = 0;

        cart.forEach((item, index) => {
            const diskonAmt = (item.harga * (item.diskon_pct || 0)) / 100;
            const priceAfterDisc = item.harga - diskonAmt;
            const lineTotal = priceAfterDisc * item.qty;
            subtotal += lineTotal;
            totalQty += item.qty;

            html += `
                <tr>
                    <td class="ps-2">
                        <div class="fw-semibold text-dark text-truncate" style="max-width: 130px;" title="${item.nama}">${item.nama}</div>
                        <small class="text-muted font-monospace">${item.kode}</small>
                    </td>
                    <td class="text-center px-1" style="white-space: nowrap; width: 105px;">
                        <div class="input-group input-group-sm flex-nowrap justify-content-center mx-auto" style="width: 86px;">
                            <button class="btn btn-outline-secondary btn-qty-minus py-0 px-1" data-index="${index}" type="button" style="font-size: 0.75rem; width: 25px; height: 26px; line-height: 1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">-</button>
                            <input type="number" step="1" class="form-control text-center font-monospace input-row-qty p-0" style="width: 36px; min-width: 36px; max-width: 36px; font-size: 0.82rem; height: 26px;" value="${item.qty}" min="1" data-index="${index}">
                            <button class="btn btn-outline-secondary btn-qty-plus py-0 px-1" data-index="${index}" type="button" style="font-size: 0.75rem; width: 25px; height: 26px; line-height: 1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">+</button>
                        </div>
                    </td>
                    <td class="text-end font-monospace small">Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td class="text-end font-monospace fw-bold text-dark small">Rp ${lineTotal.toLocaleString('id-ID')}</td>
                    <td class="text-center pe-2">
                        <button class="btn btn-link text-danger p-0 btn-remove-item" data-index="${index}" title="Hapus"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>
            `;
        });

        $('#cartItems').html(html);
        $('#itemCountBadge').text(`${cart.length} Item (${totalQty} pcs)`);
        $('#subtotalDisplay').text('Rp ' + subtotal.toLocaleString('id-ID'));
        $('#summarySubtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));

        const diskonPersen = parseFloat($('#diskonPersenInput').val()) || 0;
        const ppnPersen = parseFloat($('#ppnSelect').val()) || 0;

        $('#summaryDiskon').text(diskonPersen + '%');
        $('#summaryPajak').text(ppnPersen + '%');

        const diskonTotal = (subtotal * diskonPersen) / 100;
        const afterDiskon = Math.max(0, subtotal - diskonTotal);
        const ppnNominal = (afterDiskon * ppnPersen) / 100;
        grandTotal = afterDiskon + ppnNominal;

        $('#grandTotalDisplay').text('Rp ' + grandTotal.toLocaleString('id-ID'));
        calculateChange();
    }

    function formatRibuan(val) {
        if (!val && val !== 0) return '';
        let numStr = val.toString().replace(/[^0-9]/g, '');
        if (!numStr) return '';
        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function calculateChange() {
        const rawBayar = ($('#uangDiterimaInput').val() || '').toString().replace(/\./g, '').trim();
        const bayar = parseFloat(rawBayar) || 0;

        if (cart.length === 0 || grandTotal === 0 || rawBayar === '' || bayar === 0) {
            $('#kembalianDisplay').removeClass('text-danger text-success').text('Rp 0');
            return;
        }

        const kembali = bayar - grandTotal;
        if (kembali >= 0) {
            $('#kembalianDisplay').removeClass('text-danger').addClass('text-success').text('Rp ' + kembali.toLocaleString('id-ID'));
        } else {
            $('#kembalianDisplay').removeClass('text-success').addClass('text-danger').text('- Rp ' + Math.abs(kembali).toLocaleString('id-ID'));
        }
    }

    function addProductToCart(product) {
        const existingIndex = cart.findIndex(i => parseInt(i.id) === parseInt(product.id));
        if (existingIndex > -1) {
            cart[existingIndex].qty += 1;
        } else {
            cart.push({
                id: parseInt(product.id),
                kode: product.kode,
                nama: product.nama,
                harga: parseFloat(product.harga),
                satuan: product.satuan || 'PCS',
                stok: parseFloat(product.stok) || 0,
                qty: 1,
                diskon_pct: 0
            });
        }
        renderCart();
    }

    $(document).ready(function() {
        // Inisialisasi Select2 dengan Search Bar untuk Pelanggan POS
        function select2PelangganMatcher(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }
            if (typeof data.text === 'undefined') {
                return null;
            }
            const terms = params.term.toLowerCase().split(/\s+/).filter(t => t.length > 0);
            const text = data.text.toLowerCase();
            const allMatch = terms.every(term => text.indexOf(term) > -1);
            return allMatch ? data : null;
        }

        $('#selectPelanggan').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Pelanggan --',
            matcher: select2PelangganMatcher,
            language: {
                noResults: function() {
                    return "Pelanggan tidak ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });

        // Autofocus ke search bar saat dropdown Select2 dibuka
        $(document).on('select2:open', () => {
            const searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });

        // Klik Kartu Produk untuk menambah ke keranjang
        $(document).on('click', '.pos-product-card', function(e) {
            const $card = $(this);
            const stok = parseFloat($card.data('stok')) || 0;
            if (stok <= 0) {
                alert('Stok produk ini sedang habis!');
                return;
            }

            const product = {
                id: $card.data('id'),
                kode: $card.data('kode'),
                nama: $card.data('nama'),
                harga: $card.data('harga'),
                satuan: $card.data('satuan'),
                stok: stok
            };

            addProductToCart(product);

            // Efek visual klik
            $card.addClass('border-success bg-success bg-opacity-10');
            setTimeout(() => {
                $card.removeClass('border-success bg-success bg-opacity-10');
            }, 180);
        });

        // Filter Kategori
        $('.category-pill').on('click', function() {
            $('.category-pill').removeClass('active btn-outline-success').addClass('btn-outline-secondary');
            $(this).addClass('active btn-outline-success').removeClass('btn-outline-secondary');

            const selectedCat = $(this).data('category');
            filterCatalog();
        });

        // Live Search Input di Katalog
        $('#searchProdukCatalog').on('input', function() {
            filterCatalog();
        });

        $('#btnClearSearch').on('click', function() {
            $('#searchProdukCatalog').val('');
            filterCatalog();
            $('#searchProdukCatalog').focus();
        });

        $('#btnResetFilter').on('click', function() {
            $('#searchProdukCatalog').val('');
            $('.category-pill[data-category="all"]').click();
        });

        // Handle Enter di Search: Jika ada 1 hasil pencarian yang cocok, langsung masukkan ke keranjang
        $('#searchProdukCatalog').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const $visibleCards = $('.product-card-col:not(.d-none) .pos-product-card:not(.out-of-stock)');
                if ($visibleCards.length > 0) {
                    $visibleCards.first().click();
                    $('#searchProdukCatalog').val('');
                    filterCatalog();
                } else {
                    // Coba cari via AJAX jika tidak ada di render lokal
                    const keyword = $(this).val().trim();
                    if (keyword) {
                        $.get('<?= base_url('master/produk/search-ajax') ?>', { q: keyword }, function(res) {
                            if (res && res.length > 0) {
                                addProductToCart({
                                    id: res[0].id,
                                    kode: res[0].kode_produk,
                                    nama: res[0].nama_produk,
                                    harga: res[0].harga_jual,
                                    satuan: res[0].nama_satuan,
                                    stok: res[0].stok_tersedia
                                });
                                $('#searchProdukCatalog').val('');
                                filterCatalog();
                            } else {
                                alert('Produk tidak ditemukan!');
                            }
                        }, 'json');
                    }
                }
            }
        });

        function filterCatalog() {
            const query = $('#searchProdukCatalog').val().toLowerCase().trim();
            const activeCategory = $('.category-pill.active').data('category');
            let visibleCount = 0;

            $('.product-card-col').each(function() {
                const itemCat = $(this).data('kategori');
                const itemSearch = $(this).data('search');

                const matchesCat = (activeCategory === 'all' || String(itemCat) === String(activeCategory));
                const matchesQuery = !query || itemSearch.indexOf(query) !== -1;

                if (matchesCat && matchesQuery) {
                    $(this).removeClass('d-none');
                    visibleCount++;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (visibleCount === 0) {
                $('#productNotFound').removeClass('d-none');
            } else {
                $('#productNotFound').addClass('d-none');
            }
            adjustCardButtons();
        }

        function adjustCardButtons() {
            $('.pos-card-footer').each(function() {
                const $footer = $(this);
                const $btn = $footer.find('.btn-pos-add');
                const $btnText = $footer.find('.btn-pos-add-text');

                // Reset dulu untuk mengukur apakah muat
                $btn.removeClass('btn-icon-only');
                $btnText.removeClass('d-none');

                // Jika terpotong/overflow (scrollWidth > clientWidth), ubah menjadi icon saja
                if ($footer[0].scrollWidth > $footer[0].clientWidth + 1) {
                    $btn.addClass('btn-icon-only');
                    $btnText.addClass('d-none');
                }
            });
        }

        adjustCardButtons();
        $(window).on('resize', function() {
            adjustCardButtons();
        });

        // Cart Actions: QTY Steppers
        $(document).on('click', '.btn-qty-plus', function() {
            const idx = $(this).data('index');
            cart[idx].qty += 1;
            renderCart();
        });

        $(document).on('click', '.btn-qty-minus', function() {
            const idx = $(this).data('index');
            if (cart[idx].qty > 1) {
                cart[idx].qty -= 1;
            } else {
                cart.splice(idx, 1);
            }
            renderCart();
        });

        $(document).on('keypress', '.input-row-qty', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });

        $(document).on('change', '.input-row-qty', function() {
            const idx = $(this).data('index');
            const val = parseInt($(this).val(), 10) || 1;
            cart[idx].qty = Math.max(1, val);
            renderCart();
        });

        $(document).on('click', '.btn-remove-item', function() {
            const idx = $(this).data('index');
            cart.splice(idx, 1);
            renderCart();
        });

        $('#btnKosongkanCart').on('click', function() {
            if (cart.length > 0) {
                Swal.fire({
                    title: 'Kosongkan Keranjang?',
                    text: 'Seluruh item transaksi yang telah dipilih akan dihapus dari keranjang belanja.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C0392B',
                    cancelButtonColor: '#8DA398',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Kosongkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) {
                        cart = [];
                        $('#uangDiterimaInput').val('');
                        $('#diskonPersenInput').val('0');
                        renderCart();
                        calculateChange();
                    }
                });
            }
        });

        $('#diskonPersenInput, #ppnSelect').on('input change', function() {
            renderCart();
        });

        $('#uangDiterimaInput').on('input', function() {
            let cursor = this.selectionStart;
            let prevLen = this.value.length;
            let formatted = formatRibuan(this.value);
            this.value = formatted;
            let diff = formatted.length - prevLen;
            if (cursor !== null) {
                this.setSelectionRange(cursor + diff, cursor + diff);
            }
            calculateChange();
        });

        // Hotkeys
        $(document).on('keydown', function(e) {
            if (e.key === 'F2') {
                e.preventDefault();
                $('#searchProdukCatalog').focus().select();
            } else if (e.key === 'F4') {
                e.preventDefault();
                $('#btnKonfirmasiCheckout').click();
            } else if (e.key === 'Enter' && $('#modalPosSuccess').hasClass('show')) {
                e.preventDefault();
                $('#btnModalNewTransaction').click();
            }
        });

        // Universal POS Checkout Engine
        function processPosCheckout(autoPrintBluetooth = false) {
            if (cart.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Silakan pilih produk terlebih dahulu sebelum checkout.' });
                return;
            }

            const rawBayar = ($('#uangDiterimaInput').val() || '').toString().replace(/\./g, '');
            const bayar = parseFloat(rawBayar) || 0;
            if (bayar < grandTotal) {
                Swal.fire({ icon: 'warning', title: 'Pembayaran Kurang', text: 'Nominal uang diterima kurang dari total belanja!' });
                $('#uangDiterimaInput').focus();
                return;
            }

            const payload = {
                pelanggan_id: $('#selectPelanggan').val(),
                items: cart.map(i => ({
                    produk_id: i.id,
                    qty: i.qty,
                    diskon: (i.harga * (i.diskon_pct || 0)) / 100
                })),
                diskon_nota: (grandTotal * (parseFloat($('#diskonPersenInput').val()) || 0)) / 100,
                metode_bayar: $('#metodeBayarSelect').val(),
                nominal_bayar: bayar,
                bayar: bayar,
                catatan: 'Transaksi Kasir POS'
            };

            const targetBtn = autoPrintBluetooth ? $('#btnCheckoutBluetoothDirect') : $('#btnKonfirmasiCheckout');
            const originalHtml = targetBtn.html();
            targetBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Memproses...');

            $.ajax({
                url: '<?= base_url('pos/checkout') ?>',
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                success: async function(res) {
                    targetBtn.prop('disabled', false).html(originalHtml);
                    if (res && (res.success || res.status === 'success')) {
                        const soId = res.so_id || (res.data ? res.data.so_id : null);
                        
                        // Fetch detailed receipt info for printing
                        try {
                            const strukRes = await $.getJSON('<?= base_url('pos/struk-json/') ?>' + soId);
                            if (strukRes && strukRes.status === 'success') {
                                lastTransactionData = strukRes;
                            }
                        } catch (e) {
                            // Fallback using local cart & checkout response data
                            lastTransactionData = {
                                order: {
                                    id: soId,
                                    nomor_so: res.data ? res.data.nomor_so : ('TRX-' + soId),
                                    nomor_invoice: res.data ? res.data.nomor_invoice : ('INV-' + soId),
                                    total: grandTotal,
                                    bayar: bayar,
                                    kembalian: (bayar - grandTotal),
                                    metode_bayar: $('#metodeBayarSelect').val(),
                                    nama_pelanggan: $('#selectPelanggan option:selected').text(),
                                    kasir_nama: '<?= esc(session()->get('user')['username'] ?? 'Kasir') ?>',
                                    created_at: new Date().toLocaleString('id-ID')
                                },
                                items: [...cart],
                                store: {
                                    name: 'METAFORA STORE',
                                    address: 'Jl. Raya Bisnis Terpadu No. 12',
                                    phone: 'Telp/WA: 0812-3456-7890',
                                    footer: 'Terima kasih atas kunjungan Anda!'
                                }
                            };
                        }

                        // Populate Success Modal
                        const ord = lastTransactionData.order;
                        $('#modalSuccessKembalian').text('Rp ' + Math.round(parseFloat(ord.kembalian || 0)).toLocaleString('id-ID'));
                        $('#modalSuccessNoSo').text(ord.nomor_so || '-');
                        $('#modalSuccessNoInv').text(ord.nomor_invoice || '-');
                        $('#modalSuccessTotal').text('Rp ' + Math.round(parseFloat(ord.total || 0)).toLocaleString('id-ID'));
                        $('#modalSuccessBayar').text('Rp ' + Math.round(parseFloat(ord.bayar || 0)).toLocaleString('id-ID'));

                        // Update Next SO Number in UI
                        if (res.data && res.data.so_id) {
                            const nextId = parseInt(res.data.so_id) + 1;
                            const now = new Date();
                            const ym = now.getFullYear().toString() + String(now.getMonth() + 1).padStart(2, '0');
                            const nextTrx = 'TRX-' + ym + '-' + String(nextId).padStart(4, '0');
                            $('#currentSoNumberDisplay').text(nextTrx);
                        }

                        // Show Success Modal
                        const successModal = new bootstrap.Modal(document.getElementById('modalPosSuccess'));
                        successModal.show();

                        // Auto print to Bluetooth if requested
                        if (autoPrintBluetooth) {
                            $('#btnModalPrintBluetooth').click();
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Transaksi Gagal', text: res.message || 'Gagal memproses transaksi kasir.' });
                    }
                },
                error: function(err) {
                    targetBtn.prop('disabled', false).html(originalHtml);
                    let msg = 'Terjadi kesalahan koneksi server.';
                    if (err.responseJSON && err.responseJSON.message) {
                        msg = err.responseJSON.message;
                    }
                    Swal.fire({ icon: 'error', title: 'Error Server', text: msg });
                }
            });
        }

        // Checkout Button Click Handlers
        $('#btnKonfirmasiCheckout').on('click', function() {
            processPosCheckout(false);
        });

        $('#btnCheckoutBluetoothDirect').on('click', function() {
            processPosCheckout(true);
        });

        // Modal Action: Print Bluetooth
        $('#btnModalPrintBluetooth').on('click', async function() {
            if (!lastTransactionData) return;
            const btn = $(this);
            const orig = btn.html();
            try {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Menyambungkan Bluetooth...');
                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'bluetooth') {
                    await window.posBluetoothPrinter.connectBluetooth();
                }
                btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Mencetak Struk...');
                await window.posBluetoothPrinter.printReceipt(
                    lastTransactionData.order,
                    lastTransactionData.items,
                    lastTransactionData.store
                );
                Toast.fire({ icon: 'success', title: 'Struk berhasil dicetak ke printer thermal!' });
            } catch (err) {
                showBluetoothTroubleshootAlert(err.message);
            } finally {
                btn.prop('disabled', false).html(orig);
            }
        });

        // Modal Action: Print Serial / USB
        $('#btnModalPrintSerial').on('click', async function() {
            if (!lastTransactionData) return;
            const btn = $(this);
            const orig = btn.html();
            try {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Membuka Port USB/Serial...');
                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'serial') {
                    await window.posBluetoothPrinter.connectSerial();
                }
                btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Mencetak Struk...');
                await window.posBluetoothPrinter.printReceipt(
                    lastTransactionData.order,
                    lastTransactionData.items,
                    lastTransactionData.store
                );
                Toast.fire({ icon: 'success', title: 'Struk berhasil dicetak via USB / Serial!' });
            } catch (err) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Printer USB/Serial',
                    text: err.message || 'Gagal mencetak ke printer USB/Serial.'
                });
            } finally {
                btn.prop('disabled', false).html(orig);
            }
        });

        // Modal Action: Print Browser
        $('#btnModalPrintBrowser').on('click', function() {
            if (lastTransactionData && lastTransactionData.order && lastTransactionData.order.id) {
                window.open('<?= base_url('pos/struk/') ?>' + lastTransactionData.order.id, '_blank', 'width=420,height=600');
            }
        });

        function resetPosNewTransaction() {
            cart = [];
            $('#uangDiterimaInput').val('');
            $('#diskonPersenInput').val('0');
            $('#selectPelanggan').val('1').trigger('change');
            $('#metodeBayarSelect').val('tunai');
            renderCart();
            calculateChange();
            $('#searchProdukCatalog').val('').focus();
        }

        // Modal Action: Transaksi Baru
        $('#btnModalNewTransaction').on('click', function() {
            resetPosNewTransaction();
        });

        // Reset state jika modal sukses ditutup
        $('#modalPosSuccess').on('hidden.bs.modal', function() {
            resetPosNewTransaction();
        });
    });
</script>
<?= $this->endSection() ?>
