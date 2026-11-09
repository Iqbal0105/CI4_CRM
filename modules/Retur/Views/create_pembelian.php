<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Select2 Bootstrap 5 Custom Styling for Retur Pembelian */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #cbd5e1 !important;
        border-radius: 6px !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #2D6A4F !important;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15) !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        position: relative !important;
        min-height: 38px !important;
        height: 38px !important;
        padding-left: 12px !important;
        padding-right: 50px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        font-size: 0.88rem !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0 !important;
        margin: 0 !important;
        line-height: normal !important;
        color: #1e293b !important;
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        flex: 1 1 auto !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 8px !important;
        width: 18px !important;
        height: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 28px !important;
        width: 18px !important;
        height: 18px !important;
        line-height: 18px !important;
        text-align: center !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 1.15rem !important;
        color: #94a3b8 !important;
        cursor: pointer !important;
        z-index: 2 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear:hover {
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
        font-size: 0.85rem !important;
        padding: 6px 10px !important;
    }
    .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('retur/pembelian') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Retur Pembelian
    </a>
    <h3 class="fw-bold mt-2 mb-1">Form Retur Pembelian ke Supplier</h3>
    <p class="text-muted small mb-0">Pengembalian barang pengadaan, pemotongan stok produk, dan pemotongan saldo utang.</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= base_url('retur/pembelian/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Purchase Order Terkait <span class="text-danger">*</span></label>
                        <select name="po_id" id="po_id" class="form-select select2" required>
                            <option value="">-- Pilih Purchase Order --</option>
                            <?php foreach ($pos as $p): ?>
                                <option value="<?= $p['id'] ?>">[<?= esc($p['nomor_po']) ?>] <?= esc($p['nama_supplier']) ?> (<?= format_tanggal($p['tanggal_po']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div id="poInfoContainer" class="alert alert-light border d-none py-2 px-3 mt-2 mb-0" style="border-radius: 8px;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 small">
                                <div>
                                    <i class="fa-solid fa-receipt text-danger me-1"></i>
                                    <span class="fw-bold" id="infoPoNomor">-</span>
                                </div>
                                <div>
                                    <i class="fa-solid fa-building text-secondary me-1"></i>
                                    <span class="fw-semibold text-dark" id="infoSupplier">-</span>
                                </div>
                                <div>
                                    <i class="fa-solid fa-calendar me-1 text-muted"></i>
                                    <span class="text-secondary" id="infoPoTanggal">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindak Lanjut Retur Pembelian <span class="text-danger">*</span></label>
                        <select name="tindak_lanjut" class="form-select" required>
                            <option value="potong_utang">Potong Saldo Utang Usaha ke Supplier</option>
                            <option value="refund_kas">Penerimaan Pengembalian Kas / Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Retur ke Supplier <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="2" placeholder="Barang tidak sesuai spesifikasi PO / kemasan rusak" required></textarea>
                    </div>

                    <hr class="my-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div>
                            <span class="fw-bold"><i class="fa-solid fa-boxes-packing text-danger me-2"></i>Item Barang yang Dikembalikan</span>
                            <small class="text-muted d-block" style="font-size: 0.8rem;">Item PO otomatis terisi saat Purchase Order dipilih. Anda dapat menyesuaikan jumlah retur atau menghapus item yang tidak diretur.</small>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="btnTambahItemReturBeli">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Item Manual
                        </button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle mb-0" id="tableItemReturBeli">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 260px;">Produk</th>
                                    <th style="width: 130px;">Qty Retur</th>
                                    <th style="width: 170px;">Harga Satuan (Rp)</th>
                                    <th style="width: 160px;" class="text-end">Subtotal (Rp)</th>
                                    <th style="width: 45px;"></th>
                                </tr>
                            </thead>
                            <tbody id="returBeliRows">
                                <tr id="rowPlaceholderBeli">
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-arrow-up me-1"></i> Silakan pilih <strong>Purchase Order</strong> di atas untuk memuat daftar produk otomatis.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end fw-bold">Total Nilai Retur:</th>
                                    <th class="text-end fw-bold text-danger font-monospace" id="totalNilaiReturBeli">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <a href="<?= base_url('retur/pembelian') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                        <button type="submit" class="btn btn-danger px-4 fw-bold w-100 w-sm-auto">
                            <i class="fa-solid fa-truck-arrow-right me-2"></i>Proses Retur Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatRupiah(number) {
        return Math.round(number).toLocaleString('id-ID');
    }

    function formatNumberQty(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return '1';
        return Number.isInteger(num) ? num.toString() : num.toString();
    }

    function updateTotalReturBeli() {
        let grandTotal = 0;
        const rows = $('#returBeliRows tr.item-retur-row');
        
        rows.each(function() {
            const qty = parseFloat($(this).find('.input-retur-beli-qty').val()) || 0;
            const harga = parseFloat($(this).find('.input-retur-beli-harga').val()) || 0;
            const subtotal = qty * harga;
            grandTotal += subtotal;
            $(this).find('.subtotal-retur-beli').text('Rp ' + formatRupiah(subtotal));
        });

        $('#totalNilaiReturBeli').text('Rp ' + formatRupiah(grandTotal));

        if (rows.length === 0) {
            if ($('#returBeliRows #emptyRowNoticeBeli').length === 0) {
                $('#returBeliRows').append(`
                    <tr id="emptyRowNoticeBeli">
                        <td colspan="5" class="text-center py-4 text-warning">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Belum ada item yang dipilih untuk diretur. Klik <strong>Tambah Item Manual</strong> jika ingin memasukkan produk.
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#emptyRowNoticeBeli').remove();
        }
    }

    function initSelect2ProdukBeli($element) {
        $element.select2({
            theme: 'bootstrap-5',
            ajax: {
                url: '<?= base_url('master/produk/search-ajax') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return {
                        results: data.map(i => ({
                            id: i.id,
                            text: '[' + i.kode_produk + '] ' + i.nama_produk,
                            harga: i.harga_beli
                        }))
                    };
                }
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            $(this).closest('tr').find('.input-retur-beli-harga').val(data.harga || 0);
            updateTotalReturBeli();
        });
    }

    function renderPoItemRow(item) {
        $('#rowPlaceholderBeli, #emptyRowNoticeBeli').remove();

        const qtyMax = parseFloat(item.qty_diterima || item.qty) || 1;
        const qtyFmt = formatNumberQty(qtyMax);
        const hargaFmt = parseFloat(item.harga_satuan) || 0;
        const subtotal = qtyMax * hargaFmt;
        const satuanNama = item.nama_satuan || 'Unit';

        const rowHtml = `
            <tr class="item-retur-row">
                <td>
                    <input type="hidden" name="produk_id[]" value="${item.produk_id}">
                    <div class="fw-semibold text-dark">${escapeHtml(item.nama_produk || 'Produk ID #' + item.produk_id)}</div>
                    <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                        <span class="badge bg-light text-secondary border font-monospace">${escapeHtml(item.kode_produk || '-')}</span>
                        <span>Diterima di PO: <strong class="text-dark font-monospace">${qtyFmt}</strong> ${escapeHtml(satuanNama)}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" name="qty[]" class="form-control font-monospace input-retur-beli-qty" 
                               value="${qtyFmt}" min="0.001" max="${qtyFmt}" required>
                        <span class="input-group-text small text-muted">${escapeHtml(satuanNama)}</span>
                    </div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Maks: ${qtyFmt}</small>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan[]" class="form-control font-monospace input-retur-beli-harga text-end" 
                               value="${hargaFmt}" required>
                    </div>
                </td>
                <td class="text-end font-monospace fw-semibold subtotal-retur-beli">
                    Rp ${formatRupiah(subtotal)}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-retur-beli" title="Keluarkan item dari retur">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;

        const $row = $(rowHtml);
        $('#returBeliRows').append($row);
    }

    function renderManualReturBeliRow() {
        $('#rowPlaceholderBeli, #emptyRowNoticeBeli').remove();

        const rowHtml = `
            <tr class="item-retur-row manual-row">
                <td>
                    <select name="produk_id[]" class="form-select select-ajax-produk-beli" required>
                        <option value="">Ketik nama atau scan SKU master...</option>
                    </select>
                    <div class="small text-muted mt-1"><i class="fa-solid fa-circle-plus me-1 text-danger"></i> Item tambahan manual</div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" name="qty[]" class="form-control font-monospace input-retur-beli-qty" 
                               value="1" min="0.001" required>
                        <span class="input-group-text small text-muted">Unit</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan[]" class="form-control font-monospace input-retur-beli-harga text-end" 
                               value="0" required>
                    </div>
                </td>
                <td class="text-end font-monospace fw-semibold subtotal-retur-beli">
                    Rp 0
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-retur-beli" title="Hapus baris item">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;

        const $row = $(rowHtml);
        $('#returBeliRows').append($row);
        initSelect2ProdukBeli($row.find('.select-ajax-produk-beli'));
        updateTotalReturBeli();
    }

    function select2SmartMatcher(params, data) {
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

    $(document).ready(function() {
        // Inisialisasi Select2 dengan Search Bar untuk Purchase Order
        $('#po_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Purchase Order --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: () => "Purchase Order tidak ditemukan",
                searching: () => "Mencari..."
            }
        });

        // Autofocus ke search bar saat dropdown Select2 dibuka
        $(document).on('select2:open', () => {
            const searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });

        // Handle PO change
        $('#po_id').on('change', function() {
            const poId = $(this).val();

            if (!poId) {
                $('#poInfoContainer').addClass('d-none');
                $('#returBeliRows').html(`
                    <tr id="rowPlaceholderBeli">
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-arrow-up me-1"></i> Silakan pilih <strong>Purchase Order</strong> di atas untuk memuat daftar produk otomatis.
                        </td>
                    </tr>
                `);
                updateTotalReturBeli();
                return;
            }

            // Show loading row
            $('#returBeliRows').html(`
                <tr>
                    <td colspan="5" class="text-center py-4 text-danger">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Mengambil rincian produk PO...
                    </td>
                </tr>
            `);

            $.ajax({
                url: '<?= base_url('retur/pembelian/po-items') ?>/' + poId,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.po) {
                        $('#infoPoNomor').text(res.po.nomor_po || '-');
                        $('#infoSupplier').text(res.po.nama_supplier || '-');
                        $('#infoPoTanggal').text(res.po.tanggal_po || '-');
                        $('#poInfoContainer').removeClass('d-none');

                        $('#returBeliRows').empty();
                        if (res.items && res.items.length > 0) {
                            res.items.forEach(function(item) {
                                renderPoItemRow(item);
                            });
                        } else {
                            $('#returBeliRows').html(`
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-warning">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Tidak ada rincian item tercatat pada PO ini. Silakan klik tombol <strong>Tambah Item Manual</strong>.
                                    </td>
                                </tr>
                            `);
                        }
                        updateTotalReturBeli();
                    } else {
                        $('#returBeliRows').html(`
                            <tr>
                                <td colspan="5" class="text-center py-3 text-danger">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> ${res.message || 'Gagal memuat rincian PO.'}
                                </td>
                            </tr>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error fetching PO items:', xhr.responseText || error);
                    $('#returBeliRows').html(`
                        <tr>
                            <td colspan="5" class="text-center py-3 text-danger">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal menghubungi server saat mengambil data PO.
                            </td>
                        </tr>
                    `);
                }
            });
        });

        if ($('#po_id').val()) {
            $('#po_id').trigger('change');
        }

        $('#btnTambahItemReturBeli').on('click', function() {
            renderManualReturBeliRow();
        });

        $(document).on('click', '.btn-hapus-retur-beli', function() {
            $(this).closest('tr').remove();
            updateTotalReturBeli();
        });

        $(document).on('input change', '.input-retur-beli-qty, .input-retur-beli-harga', function() {
            const $row = $(this).closest('tr');
            const qtyInput = $row.find('.input-retur-beli-qty');
            const maxQty = parseFloat(qtyInput.attr('max'));
            const currentQty = parseFloat(qtyInput.val()) || 0;

            if (!isNaN(maxQty) && currentQty > maxQty) {
                qtyInput.addClass('is-invalid');
            } else {
                qtyInput.removeClass('is-invalid');
            }

            updateTotalReturBeli();
        });
    });
</script>
<?= $this->endSection() ?>

