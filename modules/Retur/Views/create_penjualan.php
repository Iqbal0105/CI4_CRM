<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Select2 Bootstrap 5 Custom Styling for Retur Penjualan */
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
    <a href="<?= base_url('retur/penjualan') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Retur Penjualan
    </a>
    <h3 class="fw-bold mt-2 mb-1">Form Retur Penjualan</h3>
    <p class="text-muted small mb-0">Pengembalian barang dari pelanggan, penyesuaian stok, dan jurnal otomatis.</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= base_url('retur/penjualan/store') ?>" method="POST" id="formRetur">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Sales Order / Faktur Terkait <span class="text-danger">*</span></label>
                        <select name="so_id" id="so_id" class="form-select select2" required>
                            <option value="">-- Cari / Pilih Sales Order / Faktur --</option>
                            <?php foreach ($orders as $o): ?>
                                <option value="<?= $o['id'] ?>">[<?= esc($o['nomor_so']) ?><?= !empty($o['nomor_invoice']) ? ' &bull; Faktur: ' . esc($o['nomor_invoice']) : '' ?>] <?= esc($o['nama_pelanggan'] ?? 'Pelanggan Walk-In') ?> (<?= format_tanggal($o['tanggal_so']) ?><?= !empty($o['total']) ? ' &bull; Rp ' . number_format((float)$o['total'], 0, ',', '.') : '' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div id="soInfoContainer" class="alert alert-light border d-none py-2 px-3 mt-2 mb-0" style="border-radius: 8px;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 small">
                                <div>
                                    <i class="fa-solid fa-receipt text-primary me-1"></i>
                                    <span class="fw-bold" id="infoSoNomor">-</span>
                                    <span class="text-muted ms-1" id="infoInvNomor"></span>
                                </div>
                                <div>
                                    <i class="fa-solid fa-user text-secondary me-1"></i>
                                    <span class="fw-semibold text-dark" id="infoPelanggan">-</span>
                                </div>
                                <div>
                                    <i class="fa-solid fa-calendar me-1 text-muted"></i>
                                    <span class="text-secondary" id="infoTanggal">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tindak Lanjut Retur <span class="text-danger">*</span></label>
                            <select name="tindak_lanjut" class="form-select" required>
                                <option value="potong_piutang">Potong Piutang Usaha Pelanggan</option>
                                <option value="refund">Pengembalian Dana Kas (Refund)</option>
                                <option value="ganti_barang">Ganti Barang Baru</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kondisi Barang yang Diterima <span class="text-danger">*</span></label>
                            <select name="kondisi_barang" class="form-select" required>
                                <option value="baik">Kondisi Baik (Masuk Kembali ke Stok Aktif)</option>
                                <option value="rusak">Kondisi Rusak / Cacat (Stok Rusak)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Retur <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="2" placeholder="Barang salah ukuran / cacat produksi pabrik" required></textarea>
                    </div>

                    <hr class="my-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div>
                            <span class="fw-bold"><i class="fa-solid fa-boxes-packing text-primary me-2"></i>Item Barang yang Diretur</span>
                            <small class="text-muted d-block" style="font-size: 0.8rem;">Item faktur otomatis terisi saat Sales Order dipilih. Anda dapat menyesuaikan jumlah retur atau menghapus item yang tidak ingin diretur.</small>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahItemRetur">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Item Manual
                        </button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle mb-0" id="tableItemRetur">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 260px;">Produk</th>
                                    <th style="width: 130px;">Qty Retur</th>
                                    <th style="width: 170px;">Harga Satuan (Rp)</th>
                                    <th style="width: 160px;" class="text-end">Subtotal (Rp)</th>
                                    <th style="width: 45px;"></th>
                                </tr>
                            </thead>
                            <tbody id="returItemRows">
                                <tr id="rowPlaceholder">
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-arrow-up me-1"></i> Silakan pilih <strong>Sales Order / Faktur</strong> di atas untuk memuat daftar produk otomatis.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end fw-bold">Total Nilai Retur:</th>
                                    <th class="text-end fw-bold text-primary font-monospace" id="totalNilaiRetur">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <a href="<?= base_url('retur/penjualan') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold w-100 w-sm-auto">
                            <i class="fa-solid fa-arrow-rotate-left me-2"></i>Proses Retur Penjualan
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

    function updateTotalRetur() {
        let grandTotal = 0;
        const rows = $('#returItemRows tr.item-retur-row');
        
        rows.each(function() {
            const qty = parseFloat($(this).find('.input-retur-qty').val()) || 0;
            const harga = parseFloat($(this).find('.input-retur-harga').val()) || 0;
            const subtotal = qty * harga;
            grandTotal += subtotal;
            $(this).find('.subtotal-retur').text('Rp ' + formatRupiah(subtotal));
        });

        $('#totalNilaiRetur').text('Rp ' + formatRupiah(grandTotal));

        if (rows.length === 0) {
            if ($('#returItemRows #emptyRowNotice').length === 0) {
                $('#returItemRows').append(`
                    <tr id="emptyRowNotice">
                        <td colspan="5" class="text-center py-4 text-warning">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Belum ada item yang dipilih untuk diretur. Klik <strong>Tambah Item Manual</strong> jika ingin memasukkan produk.
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#emptyRowNotice').remove();
        }
    }

    function initSelect2Produk($element) {
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
                            harga: i.harga_jual
                        }))
                    };
                }
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            $(this).closest('tr').find('.input-retur-harga').val(data.harga || 0);
            updateTotalRetur();
        });
    }

    function renderOrderItemRow(item) {
        $('#rowPlaceholder, #emptyRowNotice').remove();

        const qtyFmt = formatNumberQty(item.qty);
        const hargaFmt = parseFloat(item.harga_satuan) || 0;
        const subtotal = (parseFloat(item.qty) || 0) * hargaFmt;
        const satuanNama = item.nama_satuan || 'Unit';

        let produkHtml = '';
        if (item.produk_id && parseInt(item.produk_id) > 0) {
            produkHtml = `
                <input type="hidden" name="produk_id[]" value="${item.produk_id}">
                <div class="fw-semibold text-dark">${escapeHtml(item.nama_produk || 'Produk ID #' + item.produk_id)}</div>
                <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-light text-secondary border font-monospace">${escapeHtml(item.kode_produk || '-')}</span>
                    <span>Dibeli di Faktur: <strong class="text-dark font-monospace">${qtyFmt}</strong> ${escapeHtml(satuanNama)}</span>
                </div>
            `;
        } else {
            produkHtml = `
                <div class="fw-semibold text-dark">${escapeHtml(item.nama_produk || item.kode_produk || '-')}</div>
                <div class="small text-warning mb-1"><i class="fa-solid fa-link-slash me-1"></i> Pilih produk master:</div>
                <select name="produk_id[]" class="form-select select-ajax-produk" required>
                    <option value="">Cari master produk...</option>
                </select>
            `;
        }

        const rowHtml = `
            <tr class="item-retur-row">
                <td>${produkHtml}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" name="qty[]" class="form-control font-monospace input-retur-qty" 
                               value="${qtyFmt}" min="0.001" max="${qtyFmt}" required>
                        <span class="input-group-text small text-muted">${escapeHtml(satuanNama)}</span>
                    </div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Maks: ${qtyFmt}</small>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan[]" class="form-control font-monospace input-retur-harga text-end" 
                               value="${hargaFmt}" required>
                    </div>
                </td>
                <td class="text-end font-monospace fw-semibold subtotal-retur">
                    Rp ${formatRupiah(subtotal)}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-retur" title="Keluarkan item dari retur">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;

        const $row = $(rowHtml);
        $('#returItemRows').append($row);

        if (!item.produk_id || parseInt(item.produk_id) <= 0) {
            initSelect2Produk($row.find('.select-ajax-produk'));
        }
    }

    function renderManualReturRow() {
        $('#rowPlaceholder, #emptyRowNotice').remove();

        const rowHtml = `
            <tr class="item-retur-row manual-row">
                <td>
                    <select name="produk_id[]" class="form-select select-ajax-produk" required>
                        <option value="">Ketik nama atau scan SKU master...</option>
                    </select>
                    <div class="small text-muted mt-1"><i class="fa-solid fa-circle-plus me-1 text-primary"></i> Item tambahan manual</div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" name="qty[]" class="form-control font-monospace input-retur-qty" 
                               value="1" min="0.001" required>
                        <span class="input-group-text small text-muted">Unit</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan[]" class="form-control font-monospace input-retur-harga text-end" 
                               value="0" required>
                    </div>
                </td>
                <td class="text-end font-monospace fw-semibold subtotal-retur">
                    Rp 0
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-retur" title="Hapus baris item">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;

        const $row = $(rowHtml);
        $('#returItemRows').append($row);
        initSelect2Produk($row.find('.select-ajax-produk'));
        updateTotalRetur();
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
        // Inisialisasi Select2 dengan Search Bar untuk Sales Order / Faktur
        $('#so_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Sales Order / Faktur --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: () => "Sales Order / Faktur tidak ditemukan",
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

        // Handle SO / Faktur change
        $('#so_id').on('change', function() {
            const soId = $(this).val();

            if (!soId) {
                $('#soInfoContainer').addClass('d-none');
                $('#returItemRows').html(`
                    <tr id="rowPlaceholder">
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-arrow-up me-1"></i> Silakan pilih <strong>Sales Order / Faktur</strong> di atas untuk memuat daftar produk otomatis.
                        </td>
                    </tr>
                `);
                updateTotalRetur();
                return;
            }

            // Show loading row
            $('#returItemRows').html(`
                <tr>
                    <td colspan="5" class="text-center py-4 text-primary">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Mengambil rincian produk faktur...
                    </td>
                </tr>
            `);

            $.ajax({
                url: '<?= base_url('retur/penjualan/order-items') ?>/' + soId,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.order) {
                        // Populate Info Badge
                        $('#infoSoNomor').text(res.order.nomor_so || '-');
                        if (res.order.nomor_invoice) {
                            $('#infoInvNomor').text('&bull; Faktur: ' + res.order.nomor_invoice);
                        } else {
                            $('#infoInvNomor').text('');
                        }
                        $('#infoPelanggan').text(res.order.nama_pelanggan || 'Pelanggan Walk-In');
                        $('#infoTanggal').text(res.order.tanggal_so || '-');
                        $('#soInfoContainer').removeClass('d-none');

                        // Populate Items
                        $('#returItemRows').empty();
                        if (res.items && res.items.length > 0) {
                            res.items.forEach(function(item) {
                                renderOrderItemRow(item);
                            });
                        } else {
                            $('#returItemRows').html(`
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-warning">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Tidak ada rincian item tercatat pada Sales Order / Faktur ini. Silakan klik tombol <strong>Tambah Item Manual</strong>.
                                    </td>
                                </tr>
                            `);
                        }
                        updateTotalRetur();
                    } else {
                        $('#returItemRows').html(`
                            <tr>
                                <td colspan="5" class="text-center py-3 text-danger">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> ${res.message || 'Gagal memuat rincian pesanan.'}
                                </td>
                            </tr>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error fetching order items:', xhr.responseText || error);
                    $('#returItemRows').html(`
                        <tr>
                            <td colspan="5" class="text-center py-3 text-danger">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal menghubungi server saat mengambil data faktur.
                            </td>
                        </tr>
                    `);
                }
            });
        });

        // Trigger change if SO is already selected on load (e.g. back navigation or preselection)
        if ($('#so_id').val()) {
            $('#so_id').trigger('change');
        }

        // Add manual item
        $('#btnTambahItemRetur').on('click', function() {
            renderManualReturRow();
        });

        // Delete row
        $(document).on('click', '.btn-hapus-retur', function() {
            $(this).closest('tr').remove();
            updateTotalRetur();
        });

        // Recalculate totals on change of qty or price
        $(document).on('input change', '.input-retur-qty, .input-retur-harga', function() {
            const $row = $(this).closest('tr');
            const qtyInput = $row.find('.input-retur-qty');
            const maxQty = parseFloat(qtyInput.attr('max'));
            const currentQty = parseFloat(qtyInput.val()) || 0;

            if (!isNaN(maxQty) && currentQty > maxQty) {
                qtyInput.addClass('is-invalid');
            } else {
                qtyInput.removeClass('is-invalid');
            }

            updateTotalRetur();
        });

        // Prevent submit if no items
        $('#formRetur').on('submit', function(e) {
            const itemCount = $('#returItemRows tr.item-retur-row').length;
            if (itemCount === 0) {
                e.preventDefault();
                alert('Pilih Sales Order atau tambahkan minimal 1 item barang yang akan diretur.');
                return false;
            }

            let hasInvalidQty = false;
            $('#returItemRows tr.item-retur-row').each(function() {
                const qtyInput = $(this).find('.input-retur-qty');
                const maxQty = parseFloat(qtyInput.attr('max'));
                const currentQty = parseFloat(qtyInput.val()) || 0;
                if (!isNaN(maxQty) && currentQty > maxQty) {
                    hasInvalidQty = true;
                }
            });

            if (hasInvalidQty) {
                if (!confirm('Ada jumlah retur yang melebihi jumlah pembelian pada faktur. Apakah Anda yakin ingin melanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>

