<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <a href="<?= base_url('marketplace/detail/' . $order['id']) ?>" class="text-decoration-none text-secondary small">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Detail Pesanan
            </a>
            <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                <h3 class="fw-bold mb-0">Edit Pesanan Marketplace #<?= esc($order['no_pesanan'] ?: ($order['nomor_resi'] ?: (string)$order['id'])) ?></h3>
                <?php
                    $source = strtolower((string)$order['source']);
                    $badgeClass = 'bg-primary';
                    if ($source === 'shopee') $badgeClass = 'bg-danger';
                    elseif ($source === 'tiktok') $badgeClass = 'bg-dark';
                    elseif ($source === 'tokopedia') $badgeClass = 'bg-success';
                    elseif ($source === 'lazada') $badgeClass = 'bg-info text-dark';
                ?>
                <span class="badge <?= $badgeClass ?> text-uppercase rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                    <?= esc($order['source']) ?>
                </span>
                <?php if (!empty($order['is_cod'])): ?>
                    <span class="badge bg-danger rounded-pill px-2 py-1 text-white" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-hand-holding-dollar me-1"></i>COD
                    </span>
                <?php else: ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-credit-card me-1"></i>Non-COD
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-muted small mb-0 mt-1">Perubahan data pesanan akan otomatis disinkronisasikan ke Sales Order ERP, Invoice, Stok Fisik, Master Pelanggan, dan CRM.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('marketplace/detail/' . $order['id']) ?>" class="btn btn-outline-secondary btn-sm px-3">
                <i class="fa-solid fa-xmark me-1"></i> Batal
            </a>
            <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" onclick="$('#formEditMarketplace').submit();">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
            </button>
        </div>
    </div>
</div>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation fs-5 text-danger mt-1"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form id="formEditMarketplace" action="<?= base_url('marketplace/update/' . $order['id']) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- LEFT COLUMN: EKSPEDISI & DATA PENERIMA -->
        <div class="col-12 col-lg-4">
            <!-- 1. INFORMASI PENGIRIMAN & PLATFORM -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-truck-fast text-primary"></i>
                    Informasi Pengiriman &amp; Channel
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Channel Platform <span class="text-danger">*</span></label>
                        <select name="source" id="fieldSource" class="form-select form-select-sm" required>
                            <option value="shopee" <?= $order['source'] === 'shopee' ? 'selected' : '' ?>>Shopee</option>
                            <option value="tiktok" <?= $order['source'] === 'tiktok' ? 'selected' : '' ?>>TikTok Shop</option>
                            <option value="tokopedia" <?= $order['source'] === 'tokopedia' ? 'selected' : '' ?>>Tokopedia</option>
                            <option value="lazada" <?= $order['source'] === 'lazada' ? 'selected' : '' ?>>Lazada</option>
                            <option value="manual" <?= $order['source'] === 'manual' ? 'selected' : '' ?>>Manual / Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">No. Pesanan Marketplace <span class="text-danger">*</span></label>
                        <input type="text" name="no_pesanan" class="form-control form-control-sm font-monospace fw-bold" value="<?= esc($order['no_pesanan']) ?>" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">No. Resi (AWB)</label>
                            <input type="text" name="nomor_resi" class="form-control form-control-sm font-monospace text-primary fw-bold" value="<?= esc($order['nomor_resi'] ?? '') ?>" placeholder="SPXID... / JP...">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">Kurir / Ekspedisi</label>
                            <input type="text" name="kurir" class="form-control form-control-sm" list="listKurir" value="<?= esc($order['kurir'] ?? '') ?>" placeholder="Pilih / Ketik">
                            <datalist id="listKurir">
                                <option value="SPX Express"></option>
                                <option value="SPX Standard"></option>
                                <option value="J&T Express"></option>
                                <option value="SiCepat"></option>
                                <option value="JNE"></option>
                                <option value="Ninja Xpress"></option>
                                <option value="ID Express"></option>
                                <option value="Anteraja"></option>
                                <option value="Pos Indonesia"></option>
                                <option value="Lion Parcel"></option>
                            </datalist>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold text-secondary">Tanggal Pesanan</label>
                            <input type="date" name="tanggal_pesanan" class="form-control form-control-sm" value="<?= !empty($order['tanggal_pesanan']) ? date('Y-m-d', strtotime($order['tanggal_pesanan'])) : date('Y-m-d') ?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold text-secondary">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="ready_to_ship" <?= $order['status'] === 'ready_to_ship' ? 'selected' : '' ?>>Perlu Dikirim</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Telah Dikirim</option>
                                <option value="completed" <?= in_array($order['status'], ['completed', 'selesai']) ? 'selected' : '' ?>>Selesai</option>
                                <option value="unpaid" <?= $order['status'] === 'unpaid' ? 'selected' : '' ?>>Belum Bayar</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                <option value="returned" <?= $order['status'] === 'returned' ? 'selected' : '' ?>>Retur</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-2.5 bg-light rounded border">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_cod" id="checkIsCod" value="1" <?= !empty($order['is_cod']) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold small text-dark" for="checkIsCod">
                                Metode Pembayaran COD (Bayar di Tempat)
                            </label>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                Jika aktif, kurir menagih uang tunai sesuai total pesanan. Jika non-aktif, pembayaran lunas online via marketplace escrow.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. DATA PENERIMA / PEMBELI -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-tag text-danger"></i>
                    Data Pembeli &amp; Penerima
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Penerima / Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penerima" class="form-control form-control-sm fw-semibold" value="<?= esc($order['nama_penerima'] ?? '') ?>" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold text-secondary">No. Telepon / WhatsApp</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="no_telepon" class="form-control font-monospace" value="<?= esc($order['no_telepon'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold text-secondary">Kota / Wilayah</label>
                            <input type="text" name="kota" class="form-control form-control-sm" value="<?= esc($order['pelanggan_kota'] ?? '') ?>" placeholder="Nama Kota">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-secondary">Alamat Lengkap Pengiriman</label>
                        <textarea name="alamat_penerima" class="form-control form-control-sm" rows="3" placeholder="Alamat lengkap penerima..."><?= esc($order['alamat_penerima'] ?? '') ?></textarea>
                        <div class="small text-muted mt-1" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-arrows-rotate text-success me-1"></i>Perubahan penerima akan otomatis menyinkronkan data kontak Master Pelanggan ERP &amp; jadwal pesan WhatsApp CRM.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: TABEL ITEM PRODUK & TOTAL BIAYA -->
        <div class="col-12 col-lg-8">
            <!-- 3. TABEL ITEM PRODUK DINAMIS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fw-bold d-flex align-items-center gap-2 text-dark">
                        <i class="fa-solid fa-boxes-stacked text-primary"></i>
                        Rincian Barang Pesanan &amp; Pemetaan Master ERP
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-success fw-semibold" id="btnAddRowItem">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Baris Produk
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableEditItems">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th style="width: 250px;">Produk Master ERP</th>
                                    <th>Nama Produk Marketplace / Variasi</th>
                                    <th style="width: 130px;">SKU</th>
                                    <th class="text-center" style="width: 80px;">Qty</th>
                                    <th class="text-end" style="width: 130px;">Harga Satuan</th>
                                    <th class="text-end" style="width: 140px;">Subtotal</th>
                                    <th class="text-center" style="width: 45px;"><i class="fa-solid fa-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody id="tableEditItemsBody">
                                <?php foreach ($items as $idx => $it): ?>
                                    <tr class="item-row" data-index="<?= $idx ?>">
                                        <td>
                                            <select name="items[<?= $idx ?>][produk_id]" class="form-select form-select-sm select-master-produk">
                                                <option value="">-- Buat / Mapping Baru --</option>
                                                <?php foreach ($products as $p): ?>
                                                    <option value="<?= $p['id'] ?>"
                                                        data-kode="<?= esc($p['kode_produk']) ?>"
                                                        data-nama="<?= esc($p['nama_produk']) ?>"
                                                        data-harga="<?= (float)$p['harga_jual'] ?>"
                                                        <?= (!empty($it['produk_id']) && (int)$it['produk_id'] === (int)$p['id']) ? 'selected' : '' ?>>
                                                        [<?= esc($p['kode_produk']) ?>] <?= esc($p['nama_produk']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[<?= $idx ?>][nama_produk_marketplace]" class="form-control form-control-sm field-item-nama" value="<?= esc($it['nama_produk_marketplace'] ?? ($it['nama_produk'] ?? '')) ?>" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[<?= $idx ?>][sku]" class="form-control form-control-sm font-monospace field-item-sku" value="<?= esc($it['sku'] ?? '') ?>" placeholder="SKU...">
                                        </td>
                                        <td>
                                            <input type="number" name="items[<?= $idx ?>][qty]" class="form-control form-control-sm text-center field-item-qty" min="1" step="1" value="<?= (int)($it['qty'] ?? 1) ?>" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[<?= $idx ?>][harga_satuan]" class="form-control form-control-sm text-end field-item-harga" min="0" step="100" value="<?= round((float)($it['harga_satuan'] ?? 0)) ?>" required>
                                        </td>
                                        <td class="text-end font-monospace fw-bold text-dark small text-nowrap field-item-subtotal-text">
                                            Rp <?= number_format((float)($it['subtotal'] ?? 0), 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-link text-danger p-0 btn-remove-row" title="Hapus Baris">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. RINCIAN BIAYA & TOTAL -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calculator text-success"></i>
                    Kalkulasi Biaya &amp; Total Pesanan
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Ongkos Kirim (Rp)</label>
                            <input type="number" name="ongkir" id="fieldOngkir" class="form-control form-control-sm text-end font-monospace" min="0" step="500" value="<?= round((float)($order['ongkir'] ?? 0)) ?>">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Voucher Diskon Marketplace (Rp)</label>
                            <input type="number" name="voucher" id="fieldVoucher" class="form-control form-control-sm text-end font-monospace text-danger" min="0" step="500" value="<?= round((float)($order['voucher'] ?? 0)) ?>">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Biaya Layanan (Rp)</label>
                            <input type="number" name="biaya_layanan" id="fieldBiayaLayanan" class="form-control form-control-sm text-end font-monospace" min="0" step="500" value="<?= round((float)($order['biaya_layanan'] ?? 0)) ?>">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded border mt-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                        <div>
                            <div class="text-muted small">Subtotal Produk: <strong id="textSubtotalProduk" class="font-monospace text-dark">Rp 0</strong></div>
                            <div class="small text-secondary mt-0.5">Total pesanan dihitung otomatis dari (Subtotal + Ongkir + Biaya - Voucher).</div>
                        </div>
                        <div class="text-sm-end">
                            <label class="form-label small fw-bold text-dark mb-1">TOTAL PESANAN AKHIR (RP)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                                <input type="number" name="total" id="fieldTotal" class="form-control form-control-lg font-monospace fw-bold text-primary text-end" min="0" step="100" value="<?= round((float)($order['total'] ?? 0)) ?>" required style="max-width: 220px; font-size: 1.25rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2">
                    <div class="small text-muted">
                        <i class="fa-solid fa-circle-check text-success me-1"></i>
                        Penyesuaian stok lama dan baru diproses secara otomatis via audit log.
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('marketplace/detail/' . $order['id']) ?>" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let rowIndex = <?= count($items) ?>;

    // Helper to format Rupiah
    function formatRupiah(num) {
        return 'Rp ' + Math.round(num || 0).toLocaleString('id-ID');
    }

    // Recalculate row subtotal and grand total
    function recalculateTotals() {
        let subtotalProduk = 0;

        $('#tableEditItemsBody tr.item-row').each(function() {
            const row = $(this);
            const qty = parseFloat(row.find('.field-item-qty').val()) || 1;
            const harga = parseFloat(row.find('.field-item-harga').val()) || 0;
            const sub = qty * harga;

            subtotalProduk += sub;
            row.find('.field-item-subtotal-text').text(formatRupiah(sub));
        });

        $('#textSubtotalProduk').text(formatRupiah(subtotalProduk));

        const ongkir = parseFloat($('#fieldOngkir').val()) || 0;
        const voucher = parseFloat($('#fieldVoucher').val()) || 0;
        const biayaLayanan = parseFloat($('#fieldBiayaLayanan').val()) || 0;

        const isCod = $('#checkIsCod').is(':checked');
        let grandTotal = subtotalProduk + ongkir + biayaLayanan - voucher;
        if (grandTotal < 0) grandTotal = 0;

        // If not COD, auto update total field
        if (!isCod) {
            $('#fieldTotal').val(Math.round(grandTotal));
        } else {
            // If COD and total was empty or 0, set default
            const currentTotal = parseFloat($('#fieldTotal').val()) || 0;
            if (currentTotal <= 0) {
                $('#fieldTotal').val(Math.round(grandTotal));
            }
        }
    }

    // Master Product Select change listener
    $(document).on('change', '.select-master-produk', function() {
        const sel = $(this);
        const opt = sel.find('option:selected');
        const row = sel.closest('tr');

        if (opt.val()) {
            const kode = opt.data('kode') || '';
            const nama = opt.data('nama') || '';
            const harga = parseFloat(opt.data('harga')) || 0;

            if (kode) row.find('.field-item-sku').val(kode);
            if (nama && !row.find('.field-item-nama').val()) row.find('.field-item-nama').val(nama);
            if (harga > 0) row.find('.field-item-harga').val(Math.round(harga));

            recalculateTotals();
        }
    });

    // Inputs change listener for real-time recalculation
    $(document).on('input change', '.field-item-qty, .field-item-harga, #fieldOngkir, #fieldVoucher, #fieldBiayaLayanan', function() {
        recalculateTotals();
    });

    // Toggle COD switch
    $('#checkIsCod').on('change', function() {
        recalculateTotals();
    });

    // Remove row
    $(document).on('click', '.btn-remove-row', function() {
        if ($('#tableEditItemsBody tr.item-row').length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Pesanan minimal harus memiliki 1 baris produk.'
            });
            return;
        }

        $(this).closest('tr').remove();
        recalculateTotals();
    });

    // Add row item
    $('#btnAddRowItem').on('click', function() {
        const idx = rowIndex++;
        const masterOptions = `
            <option value="">-- Buat / Mapping Baru --</option>
            <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>" data-kode="<?= esc($p['kode_produk']) ?>" data-nama="<?= esc($p['nama_produk']) ?>" data-harga="<?= (float)$p['harga_jual'] ?>">
                    [<?= esc($p['kode_produk']) ?>] <?= esc($p['nama_produk']) ?>
                </option>
            <?php endforeach; ?>
        `;

        const newRowHtml = `
            <tr class="item-row" data-index="${idx}">
                <td>
                    <select name="items[${idx}][produk_id]" class="form-select form-select-sm select-master-produk">
                        ${masterOptions}
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${idx}][nama_produk_marketplace]" class="form-control form-control-sm field-item-nama" placeholder="Nama Produk..." required>
                </td>
                <td>
                    <input type="text" name="items[${idx}][sku]" class="form-control form-control-sm font-monospace field-item-sku" placeholder="SKU...">
                </td>
                <td>
                    <input type="number" name="items[${idx}][qty]" class="form-control form-control-sm text-center field-item-qty" min="1" step="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="items[${idx}][harga_satuan]" class="form-control form-control-sm text-end field-item-harga" min="0" step="100" value="0" required>
                </td>
                <td class="text-end font-monospace fw-bold text-dark small text-nowrap field-item-subtotal-text">
                    Rp 0
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0 btn-remove-row" title="Hapus Baris">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#tableEditItemsBody').append(newRowHtml);
    });

    // Initial calculation on load
    recalculateTotals();
});
</script>
<?= $this->endSection() ?>

