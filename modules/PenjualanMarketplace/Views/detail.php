<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('marketplace') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Penjualan Marketplace
    </a>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mt-2">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h3 class="fw-bold mb-0">Pesanan Marketplace #<?= esc($order['no_pesanan'] ?: ($order['nomor_resi'] ?: (string)$order['id'])) ?></h3>
                <?php
                    $source = strtolower((string)$order['source']);
                    $badgeClass = 'bg-primary';
                    $sourceIcon = 'fa-shop';
                    if ($source === 'shopee') {
                        $badgeClass = 'bg-danger';
                        $sourceIcon = 'fa-bag-shopping';
                    } elseif ($source === 'tiktok') {
                        $badgeClass = 'bg-dark';
                        $sourceIcon = 'fa-brands fa-tiktok';
                    } elseif ($source === 'tokopedia') {
                        $badgeClass = 'bg-success';
                        $sourceIcon = 'fa-bag-shopping';
                    } elseif ($source === 'lazada') {
                        $badgeClass = 'bg-info text-dark';
                        $sourceIcon = 'fa-bag-shopping';
                    }
                ?>
                <span class="badge <?= $badgeClass ?> rounded-pill px-2 py-1 text-uppercase" style="font-size: 0.78rem;">
                    <i class="fa-solid <?= $sourceIcon ?> me-1"></i><?= esc($order['source']) ?>
                </span>
                <?php
                    $st = strtolower((string)($order['status'] ?? ''));
                    $stBadge = 'bg-warning-subtle text-warning border-warning-subtle';
                    $stLabel = 'Perlu Dikirim';
                    if (in_array($st, ['completed', 'selesai'], true)) {
                        $stBadge = 'bg-success-subtle text-success border-success-subtle';
                        $stLabel = 'Selesai';
                    } elseif ($st === 'shipped') {
                        $stBadge = 'bg-primary-subtle text-primary border-primary-subtle';
                        $stLabel = 'Telah Dikirim';
                    } elseif (in_array($st, ['cancelled', 'batal'], true)) {
                        $stBadge = 'bg-danger-subtle text-danger border-danger-subtle';
                        $stLabel = 'Dibatalkan';
                    }
                ?>
                <span class="badge <?= $stBadge ?> border rounded-pill px-2 py-1" style="font-size: 0.78rem;">
                    <?= $stLabel ?>
                </span>
                <?php $isCod = !empty($order['is_cod']); ?>
                <?php if ($isCod): ?>
                    <span class="badge bg-danger rounded-pill px-2.5 py-1 text-white shadow-xs" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-hand-holding-dollar me-1"></i>COD (Bayar di Tempat)
                    </span>
                <?php else: ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-credit-card me-1"></i>Non-COD (Cashless)
                    </span>
                <?php endif; ?>
            </div>
            <div class="text-muted small mt-1">
                No. Resi: <strong class="font-monospace text-dark"><?= esc($order['nomor_resi'] ?? '-') ?></strong> &bull;
                Kurir: <strong><?= esc($order['kurir'] ?? '-') ?></strong> &bull;
                Penerima: <strong><?= esc($order['nama_penerima'] ?? '-') ?></strong>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <button type="button" class="btn btn-dark flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2 btn-print-resi-thermal" data-id="<?= $order['id'] ?>">
                <i class="fa-solid fa-barcode text-warning"></i>
                <span>Cetak Label Resi</span>
            </button>
            <?php
                $cleanPhone = preg_replace('/[^0-9]/', '', (string)($order['no_telepon'] ?? ''));
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
            ?>
            <?php if (!empty($cleanPhone)): ?>
                <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="btn btn-outline-success flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Chat Pembeli</span>
                </a>
            <?php endif; ?>
            <?php if (!empty($order['so_id'])): ?>
                <a href="<?= base_url('penjualan/detail/' . $order['so_id']) ?>" class="btn btn-outline-primary flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Lihat SO ERP</span>
                </a>
            <?php endif; ?>
            <a href="<?= base_url('marketplace/edit/' . $order['id']) ?>" class="btn btn-warning flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2 text-dark fw-semibold" title="Edit Pesanan Lengkap">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Pesanan</span>
            </a>
            <a href="<?= base_url('marketplace/delete/' . $order['id']) ?>" class="btn btn-outline-danger flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2 btn-action-delete" title="Hapus Pesanan Marketplace">
                <i class="fa-solid fa-trash"></i>
                <span>Hapus</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN -->
    <div class="col-12 col-lg-8">
        <!-- STATUS WORKFLOW CARD -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between py-3">
                <span class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrows-spin text-primary"></i>
                    Alur Status Pesanan Marketplace
                </span>
                <span class="badge bg-primary text-uppercase"><?= str_replace('_', ' ', esc($order['status'])) ?></span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('marketplace/update-status/' . $order['id']) ?>" method="POST" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                    <?= csrf_field() ?>
                    <label class="form-label mb-0 fw-semibold text-secondary small text-nowrap">Ubah Status:</label>
                    <select name="status" class="form-select form-select-sm" style="max-width: 100%;">
                        <option value="ready_to_ship" <?= $order['status'] === 'ready_to_ship' ? 'selected' : '' ?>>Perlu Dikirim / Siap Kemas (Ready to Ship)</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Telah Dikirim / Dalam Pengiriman (Shipped)</option>
                        <option value="completed" <?= in_array($order['status'], ['completed', 'selesai']) ? 'selected' : '' ?>>Selesai / Lunas (Completed)</option>
                        <option value="unpaid" <?= $order['status'] === 'unpaid' ? 'selected' : '' ?>>Belum Dibayar (Unpaid)</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan (Cancelled)</option>
                        <option value="returned" <?= $order['status'] === 'returned' ? 'selected' : '' ?>>Retur Pesanan (Returned)</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-3 text-nowrap">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Status
                    </button>
                </form>
                <div class="small text-muted mt-2">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> Perubahan status akan otomatis disinkronisasikan ke Sales Order ERP terkait (SO #<?= esc($order['nomor_so'] ?? '-') ?>).
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between py-3">
                <span class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-box-open text-primary"></i>
                    Rincian Barang Pesanan (<?= count($items) ?> Item)
                </span>
                <span class="badge bg-light text-dark border">
                    Total Qty: <?= number_format((float) array_sum(array_column($items, 'qty')), 0, ',', '.') ?>
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk Marketplace</th>
                                <th>Produk Terpetakan (Master ERP)</th>
                                <th class="text-center text-nowrap" style="width: 90px;">Qty</th>
                                <th class="text-end text-nowrap" style="min-width: 150px; width: 150px;">Harga Satuan</th>
                                <th class="text-end text-nowrap" style="min-width: 180px; width: 180px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= esc($item['nama_produk_marketplace']) ?></div>
                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <span class="badge bg-light text-secondary border font-monospace">SKU: <?= esc($item['sku'] ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['nama_produk'])): ?>
                                            <div class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></div>
                                            <small class="text-success font-monospace"><i class="fa-solid fa-check-circle me-1"></i><?= esc($item['kode_produk']) ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border">Belum Terpetakan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center font-monospace fw-semibold text-nowrap">
                                        <?= number_format((float) $item['qty'], 0, ',', '.') ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?>
                                    </td>
                                    <td class="text-end font-monospace text-nowrap" style="white-space: nowrap;"><?= format_rupiah($item['harga_satuan']) ?></td>
                                    <td class="text-end font-monospace fw-bold text-dark text-nowrap" style="white-space: nowrap;"><?= format_rupiah($item['subtotal']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end text-nowrap fw-semibold">Subtotal Produk:</td>
                                <td class="text-end font-monospace text-nowrap fw-semibold" style="white-space: nowrap;"><?= format_rupiah(array_sum(array_column($items, 'subtotal'))) ?></td>
                            </tr>
                            <?php if ((float) ($order['ongkir'] ?? 0) > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end text-nowrap">Ongkos Kirim:</td>
                                    <td class="text-end font-monospace text-nowrap" style="white-space: nowrap;"><?= format_rupiah($order['ongkir']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ((float) ($order['voucher'] ?? 0) > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end text-danger text-nowrap">Voucher Diskon Marketplace:</td>
                                    <td class="text-end font-monospace text-danger text-nowrap" style="white-space: nowrap;">-<?= format_rupiah($order['voucher']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ((float) ($order['biaya_layanan'] ?? 0) > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end text-nowrap">Biaya Layanan:</td>
                                    <td class="text-end font-monospace text-nowrap" style="white-space: nowrap;"><?= format_rupiah($order['biaya_layanan']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr class="table-active">
                                <td colspan="4" class="text-end fw-bold fs-6 text-nowrap align-middle">Total Pesanan:</td>
                                <td class="text-end font-monospace fw-bold fs-5 text-primary text-nowrap" style="white-space: nowrap;">
                                    <div><?= format_rupiah($order['total']) ?></div>
                                    <div class="small fw-normal mt-1" style="font-size: 0.75rem;">
                                        <?php if (!empty($order['is_cod'])): ?>
                                            <span class="badge bg-danger text-white"><i class="fa-solid fa-hand-holding-dollar me-1"></i>Metode COD (Kurir Tagih Tunai)</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fa-solid fa-credit-card me-1"></i>Non-COD (Lunas Online)</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- CRM FOLLOW UP SCHEDULES -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between py-3">
                <span class="d-flex align-items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-success fs-5"></i>
                    Jadwal Otomasi CRM & Follow-Up WhatsApp
                </span>
                <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.78rem;">
                    Buka Modul CRM
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($crmFollowups)): ?>
                    <div class="row g-2">
                        <?php foreach ($crmFollowups as $fup): ?>
                            <div class="col-12 col-md-4">
                                <div class="p-2 border rounded bg-light bg-opacity-50 h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold"><?= esc($fup['label_code']) ?></span>
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.7rem;"><?= esc($fup['target_date']) ?></span>
                                    </div>
                                    <div class="small text-muted text-truncate mb-1" title="<?= esc($fup['message_log']) ?>">
                                        <?= esc($fup['message_log']) ?>
                                    </div>
                                    <span class="badge bg-<?= $fup['status'] === 'sent' ? 'success' : 'warning text-dark' ?> rounded-pill" style="font-size: 0.68rem;">
                                        <?= strtoupper(esc($fup['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-muted small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i>
                        Pemberitahuan otomatis resi (H+0 kirim resi, H+3 pantau tiba, H+7 review/repeat order) aktif di sistem CRM.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: SIDEBAR INFO -->
    <div class="col-12 col-lg-4">
        <!-- PENGIRIMAN & EKSPEDISI -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2 py-3">
                <i class="fa-solid fa-truck-fast text-primary"></i>
                Informasi Ekspedisi & Resi
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom align-items-center">
                    <span class="text-muted">Metode Bayar:</span>
                    <?php if (!empty($order['is_cod'])): ?>
                        <span class="badge bg-danger text-white px-2 py-1 shadow-xs">
                            <i class="fa-solid fa-hand-holding-dollar me-1"></i>COD (Bayar di Tempat)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                            <i class="fa-solid fa-credit-card me-1"></i>Non-COD (Cashless)
                        </span>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Kurir / Ekspedisi:</span>
                    <span class="fw-bold text-dark text-uppercase"><?= esc($order['kurir'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">No. Resi / AWB:</span>
                    <span class="font-monospace fw-bold text-primary"><?= esc($order['nomor_resi'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">No. Pesanan:</span>
                    <span class="font-monospace fw-semibold text-dark"><?= esc($order['no_pesanan'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Platform:</span>
                    <span class="badge <?= $badgeClass ?> text-uppercase"><?= esc($order['source']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Tgl. Pesanan:</span>
                    <span><?= !empty($order['tanggal_pesanan']) ? format_tanggal($order['tanggal_pesanan']) : '-' ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Diimpor Pada:</span>
                    <span class="small text-secondary"><?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '-' ?></span>
                </div>
            </div>
        </div>

        <!-- TUJUAN PENERIMA -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between py-3">
                <span class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-location-dot text-danger"></i>
                    Data Penerima / Pembeli
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEditPelanggan" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data
                </button>
            </div>
            <div class="card-body">
                <div class="fw-bold text-dark fs-6"><?= esc($order['nama_penerima'] ?? '-') ?></div>
                <div class="text-muted small mt-1">
                    <i class="fa-solid fa-phone me-1"></i><?= esc($order['no_telepon'] ?? '-') ?>
                    <?php if (!empty($cleanPhone)): ?>
                        <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="badge bg-success text-white text-decoration-none ms-1">
                            <i class="fa-brands fa-whatsapp"></i> WA
                        </a>
                    <?php endif; ?>
                </div>
                <div class="mt-2 p-2 bg-light rounded small text-secondary">
                    <?= nl2br(esc($order['alamat_penerima'] ?? 'Alamat belum diatur')) ?>
                </div>
            </div>
        </div>

        <!-- INTEGRASI ERP -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2 py-3">
                <i class="fa-solid fa-cubes text-info"></i>
                Status Integrasi ERP &amp; Akuntansi
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted">Sales Order ERP:</span>
                    <?php if (!empty($order['nomor_so'])): ?>
                        <a href="<?= base_url('penjualan/detail/' . $order['so_id']) ?>" class="font-monospace fw-bold text-primary text-decoration-none">
                            #<?= esc($order['nomor_so']) ?> <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted">Faktur / Invoice:</span>
                    <span class="font-monospace fw-bold text-dark"><?= esc($order['nomor_invoice'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted">Status Invoice:</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle text-uppercase">
                        <?= esc($order['invoice_status'] ?? 'PAID') ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted">Pelanggan ERP:</span>
                    <span class="fw-semibold text-dark"><?= esc($order['nama_pelanggan'] ?? $order['nama_penerima'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Potong Stok:</span>
                    <span class="badge bg-success text-white"><i class="fa-solid fa-check me-1"></i>Terpotong</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA PELANGGAN / PENERIMA -->
<div class="modal fade" id="modalEditPelanggan" tabindex="-1" aria-labelledby="modalEditPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalEditPelangganLabel">
                    <i class="fa-solid fa-user-pen text-primary"></i>
                    Edit Data Pelanggan &amp; Penerima
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('marketplace/update-pelanggan/' . $order['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Penerima / Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penerima" class="form-control" value="<?= esc($order['nama_penerima'] ?? '') ?>" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label fw-semibold small text-secondary">No. Telepon / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="no_telepon" class="form-control font-monospace" value="<?= esc($order['no_telepon'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold small text-secondary">Kota / Wilayah</label>
                            <input type="text" name="kota" class="form-control" value="<?= esc($order['pelanggan_kota'] ?? '') ?>" placeholder="Nama Kota">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Alamat Lengkap Pengiriman</label>
                        <textarea name="alamat_penerima" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."><?= esc($order['alamat_penerima'] ?? '') ?></textarea>
                    </div>

                    <div class="p-3 bg-light rounded border">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="sync_master" id="checkSyncMaster" value="1" checked>
                            <label class="form-check-label fw-semibold text-dark small" for="checkSyncMaster">
                                Sinkronkan ke Master Pelanggan ERP &amp; Jadwal CRM
                            </label>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                Memperbarui nama, telepon, dan alamat pada kontak master pelanggan #<?= esc($order['kode_pelanggan'] ?? 'CUST') ?> serta jadwal follow-up WhatsApp.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Print Shipping Label Resi Thermal
        $(document).on('click', '.btn-print-resi-thermal', async function() {
            const orderId = $(this).data('id');
            const btn = $(this);
            const orig = btn.html();

            try {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memuat...');
                const res = await $.getJSON('<?= base_url('marketplace/resi-thermal-data/') ?>' + orderId);
                if (res.status !== 'success') {
                    throw new Error(res.message || 'Gagal memuat data resi marketplace.');
                }

                const ord = res.order;
                const items = res.items;
                const store = res.store;

                let itemsHtml = '';
                items.forEach((it, idx) => {
                    itemsHtml += `
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>${idx + 1}. ${it.nama_produk_marketplace || it.nama_produk || 'Produk'}</span>
                            <span class="fw-bold">x${it.qty || 1}</span>
                        </div>
                    `;
                });

                const total = Math.round(parseFloat(ord.total || 0)).toLocaleString('id-ID');

                const isCod = Boolean(parseInt(ord.is_cod, 10));
                const paymentLabel = isCod ? 'COD (Tagih Tunai Kurir)' : 'Non-COD / Lunas di Marketplace';
                const paymentBadgeClass = isCod ? 'text-danger fw-bold' : 'text-muted';

                const previewHtml = `
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 15px;">[ ${(ord.kurir || 'REGULER').toUpperCase()} ]</div>
                        <div class="small fw-semibold text-uppercase text-secondary">${(ord.source || 'MARKETPLACE')} ORDER</div>
                        <div class="mt-1"><span class="badge ${isCod ? 'bg-danger text-white' : 'bg-secondary text-white'}" style="font-size: 11px;">${isCod ? 'COD' : 'NON-COD'}</span></div>
                    </div>
                    <div class="double-line"></div>
                    <div class="text-center my-2 p-2 bg-light border rounded">
                        <div class="text-muted small">NO. RESI / AWB:</div>
                        <div class="fw-bold font-monospace" style="font-size: 16px; letter-spacing: 1px;">${ord.nomor_resi || '-'}</div>
                        <div class="small text-muted">No. Pesanan: ${ord.no_pesanan || ord.nomor_so || '-'}</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold text-uppercase text-dark">Kepada (Penerima):</div>
                        <div class="fw-bold fs-6">${ord.nama_penerima || 'Penerima'}</div>
                        <div>Telp: ${ord.no_telepon || '-'}</div>
                        <div class="mt-1">${ord.alamat_penerima || '-'}</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold text-uppercase text-dark">Dari (Pengirim):</div>
                        <div>${store.name} (${store.phone})</div>
                    </div>
                    <div class="dashed-line"></div>
                    <div class="small">
                        <div class="fw-bold mb-1">Rincian Barang (${items.length} item):</div>
                        ${itemsHtml}
                    </div>
                    <div class="dashed-line"></div>
                    <div class="text-center mt-2">
                        <div class="fw-bold fs-6">TOTAL: Rp ${total}</div>
                        <div class="small ${paymentBadgeClass}">${paymentLabel}</div>
                    </div>
                `;

                ThermalPrintHelper.showModal({
                    title: 'Label Resi ' + (ord.kurir || '') + ' #' + (ord.nomor_resi || ord.no_pesanan),
                    subtitle: 'Channel: ' + (ord.source || '').toUpperCase() + ' &bull; Penerima: ' + ord.nama_penerima,
                    previewHtml: previewHtml,
                    onPrintBluetooth: async () => {
                        await window.posBluetoothPrinter.printShippingLabel(ord, items, store);
                    },
                    onPrintSerial: async () => {
                        await window.posBluetoothPrinter.printShippingLabel(ord, items, store);
                    },
                    onPrintBrowser: () => {
                        const widthMm = window.posBluetoothPrinter.getPaperWidth() === 32 ? 58 : 80;
                        ThermalPrintHelper.printBrowserHtml(previewHtml, widthMm);
                    }
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Tidak dapat memuat label resi thermal.'
                });
            } finally {
                btn.prop('disabled', false).html(orig);
            }
        });
    });
</script>
<?= $this->endSection() ?>

