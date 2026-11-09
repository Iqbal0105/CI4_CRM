<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Select2 Bootstrap 5 Custom Styling for SO */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #cbd5e1 !important;
        border-radius: 6px !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #2D6A4F !important;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15) !important;
    }
    /* Sizing & Anti-overlapping for Select2 Single (Produk & Pelanggan) */
    .select2-container--bootstrap-5 .select2-selection--single {
        position: relative !important;
        min-height: 38px !important;
        height: 38px !important;
        padding-left: 12px !important;
        padding-right: 50px !important; /* Ruang eksklusif untuk icon clear (X) dan panah (v) */
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
        max-width: 100% !important;
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
    /* Fixed table layout for SO items with anti-crushing min-width */
    #tableSoItems {
        width: 100% !important;
        min-width: 720px !important;
    }
    #tableSoItems th {
        white-space: nowrap !important;
    }
    #tableSoItems th.col-so-produk,
    #tableSoItems td.cell-so-produk {
        min-width: 220px !important;
    }
    #tableSoItems .input-qty {
        min-width: 65px !important;
        font-weight: 600;
        padding-left: 4px !important;
        padding-right: 4px !important;
    }
    #tableSoItems .select2-container {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        display: block !important;
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
    <a href="<?= base_url('penjualan') ?>" class="text-decoration-none text-secondary small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Sales Order
    </a>
    <h3 class="fw-bold mt-2 mb-1">Buat Sales Order (SO) Baru</h3>
    <p class="text-muted small mb-0">Pesanan penjualan regular, tempo pembayaran, dan cetak faktur otomatis.</p>
</div>

<form action="<?= base_url('penjualan/store') ?>" method="POST" id="formSO">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- HEADER SO -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold">Informasi Pelanggan & Pembayaran</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Pelanggan <span class="text-danger">*</span></label>
                        <select name="pelanggan_id" id="pelanggan_id" class="form-select select2" required>
                            <option value="">-- Cari / Pilih Pelanggan --</option>
                            <?php foreach ($pelanggan as $p): ?>
                                <option value="<?= $p['id'] ?>" data-kode="<?= esc($p['kode_pelanggan']) ?>" data-tempo="<?= esc($p['tempo_hari']) ?>" data-tipe="<?= esc($p['tipe_pelanggan']) ?>" data-phone="<?= esc($p['no_telp'] ?? '') ?>">
                                    <?= esc($p['nama_pelanggan']) ?><?= !empty($p['no_telp']) ? ' (' . esc($p['no_telp']) . ')' : '' ?> - <?= ucfirst(esc($p['tipe_pelanggan'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal SO <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_so" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode_bayar" id="metode_bayar" class="form-select">
                            <option value="tempo">Tempo (Piutang Usaha)</option>
                            <option value="tunai">Tunai / Langsung Lunas</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div class="mb-3" id="tempoGroup">
                        <label class="form-label fw-semibold">Tempo Pembayaran (Hari)</label>
                        <input type="number" name="tempo_hari" id="tempo_hari" class="form-control" value="30" min="0">
                        <small class="text-muted">Jatuh tempo akan dihitung dari tanggal SO.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Biaya Ongkir / Ekspedisi (Rp)</label>
                        <input type="text" name="ongkir" id="inputOngkir" class="form-control font-monospace input-ribuan" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Diskon Tambahan (Rp)</label>
                        <input type="text" name="diskon_total" id="inputDiskonTotal" class="form-control font-monospace input-ribuan" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pajak PPN (%)</label>
                        <select name="ppn_persen" id="selectPpn" class="form-select">
                            <option value="0">Tanpa PPN (0%)</option>
                            <option value="11">PPN 11%</option>
                            <option value="12">PPN 12%</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Pesanan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan pengiriman / PO pelanggan"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS SO -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <span class="fw-bold"><i class="fa-solid fa-boxes-packing text-primary me-2"></i>Rincian Produk Dipesan</span>
                    <button type="button" class="btn btn-primary btn-sm" id="btnTambahRow">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="tableSoItems">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-so-produk" style="width: 38%; min-width: 220px;">Produk <span class="text-danger">*</span></th>
                                    <th style="width: 12%; min-width: 80px;">Qty</th>
                                    <th style="width: 18%; min-width: 110px;">Harga Satuan</th>
                                    <th style="width: 14%; min-width: 90px;">Diskon Item</th>
                                    <th class="text-end" style="width: 18%; min-width: 110px;">Subtotal</th>
                                    <th style="width: 45px;"></th>
                                </tr>
                            </thead>
                            <tbody id="soItemRows">
                                <!-- Dynamic rows -->
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total Nilai Transaksi:</td>
                                    <td class="text-end font-monospace fw-bold fs-5 text-primary" id="totalNilaiSO">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-3 d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="<?= base_url('penjualan') ?>" class="btn btn-light px-4 w-100 w-sm-auto text-center">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold w-100 w-sm-auto">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Terbitkan Sales Order & Invoice
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
    let selectedTier = 'retail';

    function formatRibuan(val) {
        if (val === null || val === undefined || val === '') return '';
        if (typeof val === 'number') {
            val = Math.round(val);
        }
        let strVal = val.toString().trim();
        // Tangani jika string desimal bawaan database MySQL (misal "14900000.00")
        if (/\.00$/.test(strVal)) {
            strVal = strVal.replace(/\.00$/, '');
        }
        strVal = strVal.replace(/[^0-9]/g, '');
        if (!strVal) return '';
        return strVal.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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
        const phone = elem ? ($(elem).data('phone') || '') : '';
        const searchable = (data.text + ' ' + kode + ' ' + barcode + ' ' + phone).toLowerCase();
        const allMatch = terms.every(term => searchable.indexOf(term) > -1);
        return allMatch ? data : null;
    }

    function renderSoRow() {
        let options = '<option value="">-- Cari / Pilih Produk --</option>';
        productsData.forEach(p => {
            const retail = Math.round(parseFloat(p.harga_jual) || 0);
            const grosir = Math.round(parseFloat(p.harga_grosir) || retail);
            const distributor = Math.round(parseFloat(p.harga_distributor) || retail);
            options += `<option value="${p.id}" data-kode="${p.kode_produk}" data-barcode="${p.barcode || ''}" data-retail="${retail}" data-grosir="${grosir}" data-distributor="${distributor}">${p.nama_produk}</option>`;
        });

        const row = `
            <tr>
                <td class="cell-so-produk" style="max-width: 0; overflow: hidden;">
                    <select name="produk_id[]" class="form-select select-so-produk select2-so-produk" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.001" name="qty[]" class="form-control font-monospace input-so-qty" value="1" min="0.001" required>
                </td>
                <td>
                    <input type="text" name="harga_satuan[]" class="form-control font-monospace input-so-harga input-ribuan" value="0" required>
                </td>
                <td>
                    <input type="text" name="diskon_item[]" class="form-control font-monospace input-so-diskon input-ribuan" value="0">
                </td>
                <td class="text-end font-monospace fw-bold text-dark subtotal-so-cell">
                    Rp 0
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-so-row"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
        const $row = $(row);
        $('#soItemRows').append($row);

        // Inisialisasi Select2 dengan search bar untuk produk SO
        $row.find('.select2-so-produk').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Produk --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: () => "Produk tidak ditemukan",
                searching: () => "Mencari..."
            }
        });

        calculateSoTotals();
    }

    function calculateSoTotals() {
        let subtotal = 0;
        $('#soItemRows tr').each(function() {
            const qty = parseFloat($(this).find('.input-so-qty').val()) || 0;
            const hargaVal = ($(this).find('.input-so-harga').val() || '').toString().replace(/\./g, '');
            const harga = parseFloat(hargaVal) || 0;
            const diskonVal = ($(this).find('.input-so-diskon').val() || '').toString().replace(/\./g, '');
            const diskon = parseFloat(diskonVal) || 0;
            const lineTotal = Math.max(0, (qty * harga) - diskon);
            subtotal += lineTotal;
            $(this).find('.subtotal-so-cell').text('Rp ' + lineTotal.toLocaleString('id-ID'));
        });

        const diskonTotalVal = ($('#inputDiskonTotal').val() || '').toString().replace(/\./g, '');
        const diskonTotal = parseFloat(diskonTotalVal) || 0;
        const ppnPersen = parseFloat($('#selectPpn').val()) || 0;
        const ongkirVal = ($('#inputOngkir').val() || '').toString().replace(/\./g, '');
        const ongkir = parseFloat(ongkirVal) || 0;

        const afterDiskon = Math.max(0, subtotal - diskonTotal);
        const ppnNominal = (afterDiskon * ppnPersen) / 100;
        const grandTotal = afterDiskon + ppnNominal + ongkir;

        $('#totalNilaiSO').text('Rp ' + grandTotal.toLocaleString('id-ID'));
    }

    $(document).ready(function() {
        // Inisialisasi Select2 dengan search bar untuk Pelanggan di SO
        $('#pelanggan_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari / Pilih Pelanggan --',
            allowClear: true,
            matcher: select2SmartMatcher,
            language: {
                noResults: () => "Pelanggan tidak ditemukan",
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

        renderSoRow();

        $('#btnTambahRow').on('click', function() {
            renderSoRow();
        });

        $(document).on('click', '.btn-hapus-so-row', function() {
            if ($('#soItemRows tr').length > 1) {
                const $tr = $(this).closest('tr');
                $tr.find('.select2-so-produk').select2('destroy');
                $tr.remove();
                calculateSoTotals();
            } else {
                alert('Minimal harus ada 1 item barang dipesan.');
            }
        });

        $(document).on('change', '.select-so-produk', function() {
            const opt = $(this).find(':selected');
            let harga = parseFloat(opt.data('retail')) || 0;
            if (selectedTier === 'grosir') harga = parseFloat(opt.data('grosir')) || harga;
            if (selectedTier === 'distributor') harga = parseFloat(opt.data('distributor')) || harga;

            $(this).closest('tr').find('.input-so-harga').val(formatRibuan(harga));
            calculateSoTotals();
        });

        // Auto format pemisah ribuan titik saat input
        $(document).on('input', '.input-ribuan', function() {
            let cursor = this.selectionStart;
            let prevLen = this.value.length;
            let formatted = formatRibuan(this.value);
            this.value = formatted;
            let diff = formatted.length - prevLen;
            if (cursor !== null) {
                this.setSelectionRange(cursor + diff, cursor + diff);
            }
            calculateSoTotals();
        });

        // Format nilai awal jika ada
        $('.input-ribuan').each(function() {
            if (this.value && this.value !== '0') {
                this.value = formatRibuan(this.value);
            }
        });

        // Hapus titik sebelum submit agar diterima sebagai angka murni
        $('#formSO').on('submit', function() {
            $('.input-ribuan').each(function() {
                if (this.value) {
                    this.value = this.value.replace(/\./g, '');
                }
            });
        });

        $(document).on('input', '.input-so-qty', function() {
            calculateSoTotals();
        });

        $('#selectPpn').on('change', calculateSoTotals);

        $('#pelanggan_id').on('change', function() {
            const opt = $(this).find(':selected');
            const tempo = opt.data('tempo');
            if (tempo !== undefined && tempo !== '') $('#tempo_hari').val(tempo);
            selectedTier = opt.data('tipe') || 'retail';

            // Update item prices if tier changed
            $('.select-so-produk').trigger('change');
        });
    });
</script>
<?= $this->endSection() ?>

