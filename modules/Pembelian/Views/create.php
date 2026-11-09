<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Select2 Bootstrap 5 Custom Styling for PO */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #cbd5e1 !important;
        border-radius: 6px !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #2D6A4F !important;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15) !important;
    }
    /* Sizing & Anti-overlapping for Select2 Single (PO Supplier & Table Produk) */
    .select2-container--bootstrap-5 .select2-selection--single {
        position: relative !important;
        padding-left: 10px !important;
        padding-right: 48px !important; /* Ruang eksklusif untuk icon clear (X) dan panah (v) */
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        display: flex !important;
        align-items: center !important;
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
        right: 6px !important;
        width: 16px !important;
        height: 16px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 26px !important;
        width: 16px !important;
        height: 16px !important;
        line-height: 16px !important;
        text-align: center !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 1.1rem !important;
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
    /* Anti-crushing & proper proportions for PO item rows */
    #tableItems {
        width: 100% !important;
        min-width: 680px !important;
    }
    #tableItems th.col-po-produk,
    #tableItems td.cell-po-produk {
        min-width: 200px !important;
    }
    #tableItems .input-qty {
        width: 100% !important;
        min-width: 65px !important;
        font-weight: 600;
        padding-left: 4px !important;
        padding-right: 4px !important;
    }
    #tableItems .subtotal-cell {
        white-space: nowrap !important;
    }
    #tableItems th {
        white-space: nowrap !important;
    }
    #tableItems .select2-container {
        width: 100% !important;
        min-width: 200px !important;
        display: block !important;
    }
    #tableItems .select2-container--bootstrap-5 .select2-selection--single {
        min-height: 31px !important;
        height: 31px !important;
        padding-left: 8px !important;
        padding-right: 44px !important;
        font-size: 0.84rem !important;
    }
    #tableItems .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        right: 4px !important;
    }
    #tableItems .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        right: 22px !important;
        font-size: 1rem !important;
    }
    /* Select2 search bar in dropdown */
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
<div class="mb-4">
    <a href="<?= base_url('pembelian') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar PO
    </a>
    <h3 class="fw-bold mt-2 mb-1">Buat Purchase Order Baru</h3>
    <p class="text-muted small mb-0">Pesanan pengadaan stok barang ke supplier resmi dengan kalkulasi kurs valas presisi.</p>
</div>

<form action="<?= base_url('pembelian/store') ?>" method="POST" id="formPO">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- HEADER PO -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-file-invoice text-success me-2"></i>Informasi Dokumen</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace" id="badgeMataUang">IDR</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">-- Cari / Pilih Supplier --</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id'] ?>" data-kode="<?= esc($s['kode_supplier']) ?>" data-currency="<?= esc($s['mata_uang']) ?>" <?= (old('supplier_id') == $s['id'] || $supplierId == $s['id']) ? 'selected' : '' ?>>
                                    <?= esc($s['nama_supplier']) ?> (<?= esc($s['mata_uang']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal PO <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_po" class="form-control" value="<?= old('tanggal_po', date('Y-m-d')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Termin Pembayaran <span class="text-danger">*</span></label>
                        <select name="termin" id="termin" class="form-select">
                            <option value="tunai" <?= old('termin') === 'tunai' ? 'selected' : '' ?>>Tunai (Cash on Delivery)</option>
                            <option value="tempo" <?= old('termin') === 'tempo' ? 'selected' : '' ?>>Tempo (Utang Usaha)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="tempoGroup" style="display: none;">
                        <label class="form-label fw-semibold">Tempo Pembayaran (Hari)</label>
                        <input type="number" name="tempo_hari" class="form-control" value="<?= old('tempo_hari', 30) ?>" min="1">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-5">
                            <label class="form-label fw-semibold">Mata Uang</label>
                            <input type="text" name="mata_uang" id="mata_uang" class="form-control font-monospace text-uppercase fw-bold text-center bg-light" value="<?= old('mata_uang', 'IDR') ?>" readonly>
                        </div>
                        <div class="col-7">
                            <label class="form-label fw-semibold">Nilai Kurs ke IDR <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">Rp</span>
                                <input type="number" step="0.000001" min="0.000001" name="nilai_kurs" id="nilai_kurs" class="form-control font-monospace" value="<?= old('nilai_kurs', '1.000000') ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted" id="kursInfoText">
                            <i class="fa-solid fa-circle-info text-info me-1"></i>Transaksi mata uang Rupiah (IDR).
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Instruksi pengiriman / pengemasan"><?= old('catatan') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS PO -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="fw-bold"><i class="fa-solid fa-list-check text-success me-2"></i>Daftar Barang yang Dipesan</span>
                    <button type="button" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1" id="btnTambahItem">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Item Barang</span>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="tableItems">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-po-produk text-nowrap" style="min-width: 200px;">Produk <span class="text-danger">*</span></th>
                                    <th style="width: 95px; min-width: 95px;" class="text-center text-nowrap">Qty</th>
                                    <th style="width: 180px; min-width: 170px;" class="text-end text-nowrap">Harga Satuan (<span class="currency-code">IDR</span>)</th>
                                    <th style="width: 170px; min-width: 160px;" class="text-end text-nowrap">Subtotal (<span class="currency-code">IDR</span>)</th>
                                    <th style="width: 48px; min-width: 48px;" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RINGKASAN KALKULASI & TOTALS -->
                <div class="card-body bg-light bg-opacity-50 border-top p-3">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="text-muted small">Subtotal Barang:</span>
                                <span class="font-monospace fw-bold text-dark" id="dispSubtotalValas">0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="text-muted small">Diskon / Potongan:</span>
                                <div class="input-group input-group-sm w-50">
                                    <span class="input-group-text currency-sym font-monospace">Rp</span>
                                    <input type="number" step="0.01" min="0" name="diskon" id="inputDiskon" class="form-control text-end font-monospace" value="0.00">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="text-muted small">Pajak Tambahan:</span>
                                <div class="input-group input-group-sm w-50">
                                    <span class="input-group-text currency-sym font-monospace">Rp</span>
                                    <input type="number" step="0.01" min="0" name="pajak" id="inputPajak" class="form-control text-end font-monospace" value="0.00">
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="fw-bold text-dark">Total Valas (<span class="currency-code">IDR</span>):</span>
                                <span class="font-monospace fw-bold fs-6 text-dark" id="dispTotalValas">0.00</span>
                            </div>
                            <div class="p-2 rounded bg-success-subtle border border-success-subtle mt-2" id="boxKonversiIDR">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success small">Total Bersih (IDR):</span>
                                    <span class="font-monospace fw-bold fs-5 text-success" id="dispTotalIDR">Rp 0</span>
                                </div>
                                <div class="text-end small text-muted font-monospace mt-1" id="dispFormulaKurs" style="font-size: 0.72rem;">
                                    Total Valas × Kurs
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white p-3 d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="<?= base_url('pembelian') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                    <button type="submit" class="btn btn-success px-4 fw-semibold w-100 w-sm-auto shadow-sm">
                        <i class="fa-solid fa-paper-plane me-2"></i>Simpan Purchase Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const productsData = <?= json_encode($products) ?>;
    const latestRates = <?= json_encode($latestRates) ?>;

    const currencySymbols = {
        'IDR': 'Rp',
        'USD': '$',
        'SGD': 'S$',
        'CNY': '¥',
        'EUR': '€'
    };

    function getCurrencySymbol(curr) {
        return currencySymbols[curr] || curr;
    }

    /**
     * Smart search matcher for Select2 (supports multi-term matching in any order)
     */
    function select2SmartMatcher(params, data) {
        if ($.trim(params.term) === '') {
            return data;
        }
        if (typeof data.text === 'undefined') {
            return null;
        }
        const terms = params.term.toLowerCase().split(/\s+/).filter(t => t.length > 0);
        const elem = data.element;
        const kode = elem ? ($(elem).data('kode') || '') : '';
        const barcode = elem ? ($(elem).data('barcode') || '') : '';
        const searchable = (data.text + ' ' + kode + ' ' + barcode).toLowerCase();
        const allMatch = terms.every(term => searchable.indexOf(term) > -1);
        return allMatch ? data : null;
    }

    function renderRow(selectedProdukId = '') {
        let options = '<option value="">-- Cari / Pilih Produk --</option>';
        productsData.forEach(p => {
            const sel = (p.id == selectedProdukId) ? 'selected' : '';
            options += `<option value="${p.id}" data-kode="${p.kode_produk}" data-barcode="${p.barcode || ''}" data-harga="${p.harga_beli}" ${sel}>${p.nama_produk}</option>`;
        });

        const curr = $('#mata_uang').val() || 'IDR';
        const sym = getCurrencySymbol(curr);

        const row = `
            <tr>
                <td class="cell-po-produk" style="min-width: 200px;">
                    <select name="produk_id[]" class="form-select form-select-sm select-produk select2-item-produk" required>
                        ${options}
                    </select>
                </td>
                <td style="width: 95px; min-width: 95px;" class="text-center">
                    <input type="number" step="1" name="qty[]" class="form-control form-control-sm text-center font-monospace input-qty" value="1" min="1" required style="width: 100%; min-width: 65px;">
                </td>
                <td style="width: 180px; min-width: 170px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text currency-sym font-monospace px-2">${sym}</span>
                        <input type="number" step="0.01" name="harga_satuan[]" class="form-control font-monospace input-harga" value="0.00" required>
                    </div>
                </td>
                <td class="text-end font-monospace fw-bold text-dark subtotal-cell text-nowrap" style="width: 170px; min-width: 160px;">
                    <span class="subtotal-valas text-nowrap">${sym} 0.00</span>
                    <small class="text-muted d-block subtotal-idr text-nowrap" style="font-size: 0.72rem;"></small>
                </td>
                <td class="text-center" style="width: 48px; min-width: 48px;">
                    <button type="button" class="btn btn-outline-danger btn-xs btn-hapus-row" title="Hapus Baris"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
        const $newRow = $(row);
        $('#itemRows').append($newRow);

        // Inisialisasi Select2 dengan search bar untuk produk
        const $selectProduk = $newRow.find('.select2-item-produk');
        $selectProduk.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Produk --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: function() {
                    return "Produk tidak ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });

        if (selectedProdukId) {
            $selectProduk.trigger('change');
        }

        calculateTotals();
    }

    function calculateTotals() {
        const curr = $('#mata_uang').val() || 'IDR';
        const sym = getCurrencySymbol(curr);
        const kurs = parseFloat($('#nilai_kurs').val()) || 1.0;

        let subtotalValas = 0;
        $('#itemRows tr').each(function() {
            const qty = parseInt($(this).find('.input-qty').val(), 10) || 0;
            const harga = parseFloat($(this).find('.input-harga').val()) || 0;
            const lineSubtotal = qty * harga;
            subtotalValas += lineSubtotal;

            $(this).find('.currency-sym').text(sym);
            $(this).find('.subtotal-valas').text(sym + ' ' + lineSubtotal.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            if (curr !== 'IDR') {
                const lineIdr = lineSubtotal * kurs;
                $(this).find('.subtotal-idr').text('≈ Rp ' + Math.round(lineIdr).toLocaleString('id-ID'));
            } else {
                $(this).find('.subtotal-idr').text('');
            }
        });

        const diskon = parseFloat($('#inputDiskon').val()) || 0;
        const pajak = parseFloat($('#inputPajak').val()) || 0;
        let totalValas = subtotalValas - diskon + pajak;
        if (totalValas < 0) totalValas = 0;

        const totalIdr = Math.round(totalValas * kurs);

        $('.currency-code').text(curr);
        $('.currency-sym').text(sym);
        $('#badgeMataUang').text(curr);

        $('#dispSubtotalValas').text(sym + ' ' + subtotalValas.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#dispTotalValas').text(sym + ' ' + totalValas.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#dispTotalIDR').text('Rp ' + totalIdr.toLocaleString('id-ID'));

        if (curr !== 'IDR') {
            $('#dispFormulaKurs').text(sym + ' ' + totalValas.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' × Rp ' + kurs.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#kursInfoText').html('<i class="fa-solid fa-circle-check text-success me-1"></i>Kurs aktif: 1 ' + curr + ' = Rp ' + kurs.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        } else {
            $('#dispFormulaKurs').text('Mata uang dasar (IDR 1:1)');
            $('#kursInfoText').html('<i class="fa-solid fa-circle-info text-info me-1"></i>Transaksi mata uang Rupiah (IDR).');
        }
    }

    $(document).ready(function() {
        // Inisialisasi Select2 dengan search bar untuk Supplier
        $('#supplier_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Supplier --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: function() {
                    return "Supplier tidak ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });

        // Autofocus ke search bar setiap kali dropdown Select2 dibuka
        $(document).on('select2:open', () => {
            const searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });

        renderRow(<?= json_encode($produkId ?? '') ?>);

        $('#btnTambahItem').on('click', function() {
            renderRow();
        });

        $(document).on('click', '.btn-hapus-row', function() {
            if ($('#itemRows tr').length > 1) {
                const $tr = $(this).closest('tr');
                $tr.find('.select2-item-produk').select2('destroy');
                $tr.remove();
                calculateTotals();
            } else {
                alert('Minimal harus ada 1 item pesanan.');
            }
        });

        $(document).on('change', '.select-produk', function() {
            const selectedOpt = $(this).find(':selected');
            const harga = selectedOpt.data('harga') || 0;
            const curr = $('#mata_uang').val() || 'IDR';
            const kurs = parseFloat($('#nilai_kurs').val()) || 1.0;

            let finalHarga = harga;
            if (curr !== 'IDR' && kurs > 0) {
                finalHarga = (harga / kurs).toFixed(2);
            }
            $(this).closest('tr').find('.input-harga').val(finalHarga);
            calculateTotals();
        });

        $(document).on('input', '.input-qty, .input-harga, #inputDiskon, #inputPajak, #nilai_kurs', function() {
            calculateTotals();
        });

        $('#termin').on('change', function() {
            if ($(this).val() === 'tempo') {
                $('#tempoGroup').show();
            } else {
                $('#tempoGroup').hide();
            }
        });

        $('#supplier_id').on('change', function() {
            const currency = $(this).find(':selected').data('currency') || 'IDR';
            $('#mata_uang').val(currency);

            if (currency === 'IDR') {
                $('#nilai_kurs').val('1.000000').prop('readonly', true);
            } else {
                $('#nilai_kurs').prop('readonly', false);
                const rate = latestRates[currency] || 1.0;
                $('#nilai_kurs').val(rate);
            }
            calculateTotals();
        });

        if ($('#supplier_id').val()) {
            $('#supplier_id').trigger('change');
        }
    });
</script>
<?= $this->endSection() ?>
