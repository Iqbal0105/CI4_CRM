<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('penjualan') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Sales Order
    </a>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mt-2">
        <div>
            <h3 class="fw-bold mb-1">Sales Order #<?= esc($order['nomor_so']) ?></h3>
            <div class="text-muted small">
                Invoice: <strong class="font-monospace text-primary"><?= esc($order['nomor_invoice'] ?? '-') ?></strong> &bull; 
                Pelanggan: <strong><?= esc($order['nama_pelanggan']) ?></strong>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <button type="button" class="btn btn-dark flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2" id="btnCetakThermalSO" data-id="<?= $order['id'] ?>">
                <i class="fa-solid fa-receipt text-warning"></i>
                <span>Cetak Thermal</span>
            </button>
            <a href="<?= base_url('penjualan/invoice-pdf/' . $order['id']) ?>" target="_blank" class="btn btn-outline-primary flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Cetak Invoice A4</span>
            </a>
            <a href="<?= base_url('penjualan/surat-jalan-pdf/' . $order['id']) ?>" target="_blank" class="btn btn-outline-success flex-fill flex-md-grow-0 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-truck"></i>
                <span>Cetak Surat Jalan</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ITEMS & SHIPPING FLOW -->
    <div class="col-12 col-lg-8">
        <!-- STATUS WORKFLOW CARD -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                <span>Alur Pengiriman & Pemenuhan Pesanan</span>
                <span class="badge bg-primary text-uppercase"><?= str_replace('_', ' ', esc($order['status'])) ?></span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('penjualan/update-status/' . $order['id']) ?>" method="POST" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                    <?= csrf_field() ?>
                    <label class="form-label mb-0 fw-semibold text-secondary small">Ubah Status:</label>
                    <select name="status" class="form-select form-select-sm" style="max-width: 100%;">
                        <option value="draft" <?= $order['status'] === 'draft' ? 'selected' : '' ?>>Draft (Konsep)</option>
                        <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed (Dikonfirmasi)</option>
                        <option value="packing" <?= $order['status'] === 'packing' ? 'selected' : '' ?>>Packing (Dikemas)</option>
                        <option value="ready_to_ship" <?= $order['status'] === 'ready_to_ship' ? 'selected' : '' ?>>Ready to Ship (Siap Kirim)</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped (Telah Dikirim / Potong Stok)</option>
                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered (Telah Diterima)</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed (Selesai)</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled (Batal)</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-3 text-nowrap">
                        Perbarui Alur
                    </button>
                </form>
                <div class="small text-muted mt-2">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> Saat status diubah ke <strong>Shipped</strong>, reservasi stok akan dilepas dan stok fisik produk otomatis terpotong secara permanen.
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Rincian Barang Pesanan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center" style="width: 110px;">Qty</th>
                                <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                <th class="text-end" style="width: 120px;">Diskon</th>
                                <th class="text-end" style="width: 140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></div>
                                        <small class="text-muted font-monospace"><?= esc($item['kode_produk']) ?></small>
                                    </td>
                                    <td class="text-center font-monospace fw-semibold"><?= number_format((float) $item['qty'], 0, ',', '.') ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?></td>
                                    <td class="text-end font-monospace"><?= format_rupiah($item['harga_satuan']) ?></td>
                                    <td class="text-end font-monospace text-muted"><?= format_rupiah($item['diskon']) ?></td>
                                    <td class="text-end font-monospace fw-bold text-dark"><?= format_rupiah($item['subtotal']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end">Subtotal:</td>
                                <td class="text-end font-monospace"><?= format_rupiah($order['subtotal']) ?></td>
                            </tr>
                            <?php if ((float) $order['diskon_total'] > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end">Diskon Total:</td>
                                    <td class="text-end font-monospace text-danger">-<?= format_rupiah($order['diskon_total']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ((float) $order['ppn'] > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end">PPN:</td>
                                    <td class="text-end font-monospace"><?= format_rupiah($order['ppn']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ((float) $order['ongkir'] > 0): ?>
                                <tr>
                                    <td colspan="4" class="text-end">Ongkos Kirim:</td>
                                    <td class="text-end font-monospace"><?= format_rupiah($order['ongkir']) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="4" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="text-end font-monospace fw-bold fs-5 text-primary"><?= format_rupiah($order['total']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR INFO -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold">Informasi Penagihan (Invoice)</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">No. Invoice:</span>
                    <span class="font-monospace fw-bold text-dark"><?= esc($order['nomor_invoice'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Status Invoice:</span>
                    <span class="badge <?= ($order['invoice_status'] ?? '') === 'paid' ? 'bg-success' : 'bg-danger' ?> text-uppercase">
                        <?= esc($order['invoice_status'] ?? 'unpaid') ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Tanggal SO:</span>
                    <span><?= format_tanggal($order['tanggal_so']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Jatuh Tempo:</span>
                    <span class="text-danger fw-semibold"><?= format_tanggal($order['jatuh_tempo']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Metode Bayar:</span>
                    <span class="text-capitalize fw-semibold"><?= esc($order['metode_bayar']) ?></span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Tujuan Pengiriman</div>
            <div class="card-body">
                <div class="fw-semibold text-dark"><?= esc($order['nama_pelanggan']) ?></div>
                <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i><?= esc($order['no_telp']) ?></div>
                <p class="small text-muted mt-2 mb-0"><?= nl2br(esc($order['alamat'] ?? 'Alamat belum diatur')) ?></p>
                <div class="small text-muted"><?= esc($order['kota'] ?? '') ?></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#btnCetakThermalSO').on('click', async function() {
        const soId = $(this).data('id');
        const btn = $(this);
        const orig = btn.html();

        try {
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memuat...');
            const res = await $.getJSON('<?= base_url('penjualan/thermal-data/') ?>' + soId);
            if (res.status !== 'success') {
                throw new Error(res.message || 'Gagal memuat data transaksi');
            }

            const ord = res.order;
            const items = res.items;
            const store = res.store;

            let itemsHtml = '';
            items.forEach(it => {
                const sub = Math.round(parseFloat(it.subtotal || (it.qty * it.harga_satuan))).toLocaleString('id-ID');
                itemsHtml += `
                    <tr>
                        <td colspan="3" class="fw-bold">${it.nama_produk || 'Produk'}</td>
                    </tr>
                    <tr>
                        <td>${parseFloat(it.qty)} x ${Math.round(parseFloat(it.harga_satuan)).toLocaleString('id-ID')}</td>
                        <td></td>
                        <td class="text-end font-monospace">${sub}</td>
                    </tr>
                `;
            });

            const grand = Math.round(parseFloat(ord.total || 0)).toLocaleString('id-ID');
            const subtotal = Math.round(parseFloat(ord.subtotal || ord.total || 0)).toLocaleString('id-ID');
            const bayar = Math.round(parseFloat(ord.bayar || ord.total || 0)).toLocaleString('id-ID');
            const kembalian = Math.round(parseFloat(ord.kembalian || 0)).toLocaleString('id-ID');

            const previewHtml = `
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 13px;">${store.name}</div>
                    <div>${store.address}</div>
                    <div>${store.phone}</div>
                </div>
                <div class="dashed-line"></div>
                <div>No. Trx : ${ord.nomor_so || '-'}</div>
                <div>No. Inv : ${ord.nomor_invoice || '-'}</div>
                <div>Waktu   : ${ord.created_at || new Date().toLocaleString('id-ID')}</div>
                <div>Cust    : ${ord.nama_pelanggan || 'Pelanggan'}</div>
                <div>Metode  : ${(ord.metode_bayar || 'Tunai').toUpperCase()}</div>
                <div class="dashed-line"></div>
                <table>${itemsHtml}</table>
                <div class="dashed-line"></div>
                <table>
                    <tr><td>Subtotal:</td><td class="text-end">Rp ${subtotal}</td></tr>
                    <tr class="fw-bold"><td>TOTAL:</td><td class="text-end">Rp ${grand}</td></tr>
                    <tr><td>Bayar:</td><td class="text-end">Rp ${bayar}</td></tr>
                    <tr class="fw-bold"><td>Kembalian:</td><td class="text-end">Rp ${kembalian}</td></tr>
                </table>
                <div class="dashed-line"></div>
                <div class="text-center" style="margin-top: 6px;">
                    <div>${store.footer}</div>
                </div>
            `;

            ThermalPrintHelper.showModal({
                title: 'Cetak Thermal #' + ord.nomor_so,
                subtitle: 'Pelanggan: ' + (ord.nama_pelanggan || 'Umum') + ' &bull; Total: Rp ' + grand,
                previewHtml: previewHtml,
                onPrintBluetooth: async () => {
                    await window.posBluetoothPrinter.printReceipt(ord, items, store);
                },
                onPrintSerial: async () => {
                    await window.posBluetoothPrinter.printReceipt(ord, items, store);
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
                text: err.message || 'Tidak dapat memuat data cetak thermal.'
            });
        } finally {
            btn.prop('disabled', false).html(orig);
        }
    });
</script>
<?= $this->endSection() ?>

