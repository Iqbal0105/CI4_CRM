<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Sub-nav horizontal scroll on mobile */
    .crm-subnav-scroll {
        overflow-x: auto;
        flex-wrap: nowrap !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 2px;
    }
    .crm-subnav-scroll::-webkit-scrollbar {
        display: none;
    }
    .crm-subnav-scroll .btn {
        white-space: nowrap;
        flex-shrink: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- TOP BANNER CRM -->
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div>
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; Broadcast WA</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-bullhorn me-2"></i>Broadcast WhatsApp Massal
                </h3>
                <p class="text-white-50 small mb-0">Kirim pesan massal ke kelompok customer berdasarkan label H+ &amp; Cegah duplikasi pengiriman otomatis</p>
            </div>
            <div>
                <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-light fw-semibold text-primary d-inline-flex align-items-center gap-1 shadow-sm px-3">
                    <i class="fa-solid fa-book-open"></i> Journal Follow-Up
                </a>
            </div>
        </div>

        <!-- 7 SUB-NAVIGATION TABS (Horizontally scrollable on mobile) -->
        <div class="crm-subnav-scroll d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20">
            <a href="<?= base_url('crm/dashboard') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                1. Dashboard CRM
            </a>
            <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                2. Journal Follow-Up
            </a>
            <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; <i class="fa-brands fa-whatsapp me-1 text-success"></i>3. Broadcast WA
            </a>
            <a href="<?= base_url('crm/directory') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Customer Directory
            </a>
            <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Kinerja Tim CS
            </a>
            <a href="<?= base_url('crm/kelompok') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                6. Kelompok Follow-Up
            </a>
        </div>
    </div>
</div>

<!-- FORM KIRIM BROADCAST BARU -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Kirim Broadcast Baru</h6>
                <p class="text-muted small mb-0">Pilih target kategori label H+ dan filter customer penerima pesan broadcast</p>
            </div>
        </div>

        <form id="formFilterBroadcast" action="<?= base_url('crm/broadcast/send') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">
                        <i class="fa-solid fa-tag text-danger me-1"></i>Pilih Kategori Label H+ <span class="text-danger">*</span>
                    </label>
                    <select name="kategori_label" id="filterKategoriLabel" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="H+0">Konfirmasi Pengiriman (H+0)</option>
                        <option value="H+3">Cek Barang Sampai (H+3)</option>
                        <option value="H+7">Tanya Pengalaman (H+7)</option>
                        <option value="H+11">Evaluasi Produk (H+11)</option>
                        <option value="H+30">Tawarkan Repeat Order (H+30)</option>
                        <option value="H+100">Testing Kelompok (H+100)</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">
                        <i class="fa-solid fa-store me-1"></i>Filter Sumber Penjualan
                    </label>
                    <select name="sumber_penjualan" id="filterSumberPenjualan" class="form-select form-select-sm">
                        <option value="">Semua Sumber (Toko &amp; Online)</option>
                        <option value="toko">Toko Fisik (POS)</option>
                        <option value="shopee">Shopee</option>
                        <option value="tokopedia">Tokopedia</option>
                        <option value="tiktok">TikTok Shop</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">
                        <i class="fa-solid fa-chart-pie me-1"></i>Filter Segmen RFM
                    </label>
                    <select name="segmen_rfm" id="filterSegmenRfm" class="form-select form-select-sm">
                        <option value="">Semua Segmen Customer</option>
                        <option value="Champions">Champions (Skor 5-5-5)</option>
                        <option value="Loyal Customers">Loyal Customers</option>
                        <option value="Potential Loyalist">Potential Loyalist</option>
                        <option value="Need Attention">Need Attention</option>
                        <option value="At Risk">At Risk Customers</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1">
                        <i class="fa-solid fa-bolt me-1"></i>Aksi
                    </label>
                    <button type="submit" id="btnMuatPenerima" class="btn btn-primary btn-sm w-100 fw-semibold d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fa-solid fa-users"></i> Muat Penerima
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DAFTAR PENERIMA TERFILTER DARI PEMILIHAN FILTER -->
<div class="card border-0 shadow-sm mb-4" id="cardDaftarPenerima" style="border-radius: 10px; display: none;">
    <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-3 bg-success bg-opacity-10 text-success p-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Data Penerima Broadcast Terpilih</h6>
                <p class="text-muted small mb-0">Ditemukan <strong class="text-success" id="badgeJumlahDitemukan">0</strong> customer siap dikirimi pesan</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnToggleCheckAll" style="font-size: 0.775rem;">
                <i class="fa-regular fa-square-check me-1"></i>Pilih / Batalkan Semua
            </button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <!-- Tabel Data Penerima -->
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0" id="tablePenerimaBc">
                <thead class="table-light sticky-top">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <th class="ps-3" style="width: 40px;">
                            <input type="checkbox" id="cbMasterBc" checked title="Pilih Semua">
                        </th>
                        <th>CUSTOMER</th>
                        <th>NO. WHATSAPP</th>
                        <th>NO. PESANAN</th>
                        <th>SUMBER</th>
                        <th>KATEGORI LABEL</th>
                        <th>TANGGAL JADWAL</th>
                        <th>PRATINJAU PESAN</th>
                    </tr>
                </thead>
                <tbody id="tbodyPenerimaBc">
                    <!-- Dynamic Rows populated via filter -->
                </tbody>
            </table>
        </div>

        <!-- Bagian Pengaturan Pesan & Tombol Kirim di Bawah Data -->
        <div class="p-3 bg-light border-top">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-lg-7">
                    <label class="form-label small fw-semibold text-secondary mb-1">
                        <i class="fa-solid fa-message me-1 text-primary"></i>Mode Pesan WhatsApp:
                    </label>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <div class="form-check small mb-0">
                            <input class="form-check-input" type="radio" name="bc_msg_mode" id="bcModeAuto" value="auto" checked>
                            <label class="form-check-label fw-medium" for="bcModeAuto">Otomatis Sesuai Label H+</label>
                        </div>
                        <div class="form-check small mb-0">
                            <input class="form-check-input" type="radio" name="bc_msg_mode" id="bcModeCustom" value="custom">
                            <label class="form-check-label fw-medium" for="bcModeCustom">Pesan Kustom Seragam</label>
                        </div>
                    </div>
                    <div id="bcCustomTextAreaWrapper" class="d-none mt-2">
                        <textarea class="form-control form-control-sm mb-1" id="bcCustomMessage" rows="2" placeholder="Halo kak {nama}, terima kasih telah berbelanja di toko kami..."></textarea>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="text-muted small" style="font-size: 0.72rem;">Sisipkan:</span>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-tag-var" data-tag="{nama}" style="font-size: 0.72rem;">{nama}</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-tag-var" data-tag="{nomor_resi}" style="font-size: 0.72rem;">{nomor_resi}</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-tag-var" data-tag="{produk}" style="font-size: 0.72rem;">{produk}</button>
                        </div>
                    </div>
                </div>

                <!-- Tombol Kirim di Bawah Data -->
                <div class="col-12 col-lg-5 text-lg-end">
                    <div class="small text-muted mb-2">
                        Siap mengirim ke <strong class="text-dark" id="txtCountSelected">0</strong> pelanggan terpilih
                    </div>
                    <button type="button" class="btn btn-success btn-lg fw-bold px-4 py-2 d-inline-flex align-items-center gap-2 shadow" id="btnKirimBroadcastNow">
                        <i class="fa-brands fa-whatsapp fs-5"></i>
                        <span>Kirim</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LOG BROADCAST TERAKHIR -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Log Broadcast Terakhir
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <th class="ps-3 py-3">WAKTU KIRIM</th>
                        <th>PENGIRIM</th>
                        <th>CUSTOMER</th>
                        <th>NO. HP</th>
                        <th>KATEGORI LABEL</th>
                        <th>SUMBER ORDER</th>
                        <th class="pe-3 text-end">STATUS KIRIM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="ps-3 text-muted small">
                                    <?= !empty($log['sent_at']) ? date('d/m/Y H:i', strtotime($log['sent_at'])) : date('d/m/Y H:i', strtotime($log['created_at'] ?? 'now')) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-medium px-2 py-1">developer</span>
                                </td>
                                <td class="fw-semibold text-dark">
                                    <?= esc($log['customer_nama'] ?? '-') ?>
                                </td>
                                <td class="text-secondary font-monospace small">
                                    <?= esc($log['customer_phone'] ?? '-') ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1 bg-info bg-opacity-10 text-info fw-semibold">
                                        <?= esc($log['label_code'] ?? 'Konfirmasi Pengiriman') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $source = strtolower($log['source'] ?? 'toko');
                                        $bgClass = match($source) {
                                            'shopee' => 'background-color: #ee4d2d; color: white;',
                                            'tokopedia' => 'background-color: #03ac0e; color: white;',
                                            'tiktok' => 'background-color: #000000; color: white;',
                                            default => 'background-color: #1e293b; color: white;',
                                        };
                                    ?>
                                    <span class="badge rounded-pill px-2 py-1 fw-medium" style="<?= $bgClass ?>">
                                        <?= ucfirst(esc($source)) ?>
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <span class="badge rounded-pill px-3 py-1 bg-success bg-opacity-10 text-success fw-bold">
                                        <i class="fa-solid fa-check me-1"></i>Terkirim
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengiriman broadcast.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let currentRecipients = [];

    function updateSelectedCounter() {
        const checked = $('.cb-bc-item:checked').length;
        $('#txtCountSelected').text(checked);
        $('#btnKirimBroadcastNow span').text('Kirim');
        $('#btnKirimBroadcastNow').prop('disabled', checked === 0);
    }

    // Fungsi memuat data penerima sesuai filter
    function loadFilteredRecipients() {
        const kategori = $('#filterKategoriLabel').val();
        const sumber = $('#filterSumberPenjualan').val();
        const segmen = $('#filterSegmenRfm').val();

        $('#btnMuatPenerima').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Memuat...');

        $.getJSON('<?= base_url('crm/broadcast/filter') ?>', {
            kategori_label: kategori,
            sumber_penjualan: sumber,
            segmen_rfm: segmen
        }, function(res) {
            $('#btnMuatPenerima').prop('disabled', false).html('<i class="fa-solid fa-users me-1"></i> Muat Penerima');

            if (res && res.status) {
                currentRecipients = res.data || [];
                $('#badgeJumlahDitemukan').text(res.total);
                
                if (currentRecipients.length === 0) {
                    $('#tbodyPenerimaBc').html('<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fa-solid fa-circle-exclamation text-warning me-1"></i> Tidak ditemukan customer dengan status pending untuk filter yang dipilih.</td></tr>');
                    $('#cardDaftarPenerima').slideDown();
                    updateSelectedCounter();
                    return;
                }

                let html = '';
                currentRecipients.forEach(function(item) {
                    const source = (item.source || 'toko').toLowerCase();
                    let badgeBg = 'background-color: #1e293b; color: white;';
                    if (source === 'shopee') badgeBg = 'background-color: #ee4d2d; color: white;';
                    else if (source === 'tokopedia') badgeBg = 'background-color: #03ac0e; color: white;';
                    else if (source === 'tiktok') badgeBg = 'background-color: #000000; color: white;';

                    html += `<tr>
                        <td class="ps-3">
                            <input type="checkbox" class="cb-bc-item form-check-input" value="${item.id}" checked>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark small">${item.customer_nama}</div>
                        </td>
                        <td>
                            <span class="small font-monospace text-secondary">${item.customer_phone || '-'}</span>
                        </td>
                        <td>
                            <span class="small font-monospace text-muted">${item.nomor_so || '-'}</span>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2 py-1" style="${badgeBg}">${item.source ? item.source.toUpperCase() : 'TOKO'}</span>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle small">${item.label_code}</span>
                        </td>
                        <td class="small text-secondary font-monospace">${item.target_date}</td>
                        <td class="small text-muted" style="max-width: 250px;">
                            <div class="text-truncate" title="${item.preview_msg}">${item.preview_msg}</div>
                        </td>
                    </tr>`;
                });

                $('#tbodyPenerimaBc').html(html);
                $('#cbMasterBc').prop('checked', true);
                $('#cardDaftarPenerima').slideDown();
                updateSelectedCounter();

                // Scroll halus menuju tabel data penerima
                $('html, body').animate({
                    scrollTop: $("#cardDaftarPenerima").offset().top - 80
                }, 400);
            }
        }).fail(function() {
            $('#btnMuatPenerima').prop('disabled', false).html('<i class="fa-solid fa-users me-1"></i> Muat Penerima');
            alert('Gagal memuat data penerima broadcast.');
        });
    }

    // Submit form filter
    $('#formFilterBroadcast').on('submit', function(e) {
        e.preventDefault();
        loadFilteredRecipients();
    });

    // Otomatis muat saat pemilihan kategori/sumber berubah jika sudah dipilih kategori
    $('#filterKategoriLabel, #filterSumberPenjualan').on('change', function() {
        if ($('#filterKategoriLabel').val() !== '') {
            loadFilteredRecipients();
        }
    });

    // Checkbox master
    $('#cbMasterBc').on('change', function() {
        $('.cb-bc-item').prop('checked', this.checked);
        updateSelectedCounter();
    });

    // Tombol Pilih/Batalkan Semua
    $('#btnToggleCheckAll').on('click', function() {
        const allChecked = $('.cb-bc-item:checked').length === $('.cb-bc-item').length;
        $('.cb-bc-item').prop('checked', !allChecked);
        $('#cbMasterBc').prop('checked', !allChecked);
        updateSelectedCounter();
    });

    // Event saat checkbox satuan diubah
    $(document).on('change', '.cb-bc-item', function() {
        updateSelectedCounter();
        const total = $('.cb-bc-item').length;
        const checked = $('.cb-bc-item:checked').length;
        $('#cbMasterBc').prop('checked', total > 0 && total === checked);
    });

    // Radio mode pesan
    $('input[name="bc_msg_mode"]').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#bcCustomTextAreaWrapper').removeClass('d-none');
            $('#bcCustomMessage').focus();
        } else {
            $('#bcCustomTextAreaWrapper').addClass('d-none');
        }
    });

    // Sisipkan tag variabel ke textarea pesan kustom
    $('.btn-tag-var').on('click', function() {
        const tag = $(this).data('tag');
        const txt = $('#bcCustomMessage');
        const cur = txt.val();
        txt.val(cur + (cur ? ' ' : '') + tag);
        txt.focus();
    });

    // Eksekusi Tombol Kirim Broadcast di Bawah Data
    $('#btnKirimBroadcastNow').on('click', function() {
        const selectedIds = [];
        $('.cb-bc-item:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Penerima',
                    text: 'Silakan centang minimal 1 pelanggan pada daftar penerima untuk dikirimi broadcast.'
                });
            } else {
                alert('Silakan centang minimal 1 pelanggan pada daftar penerima untuk dikirimi broadcast.');
            }
            return;
        }

        const mode = $('input[name="bc_msg_mode"]:checked').val() || 'auto';
        const customMsg = $('#bcCustomMessage').val().trim();

        if (mode === 'custom' && !customMsg) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan Kosong',
                    text: 'Silakan isi pesan kustom broadcast terlebih dahulu.'
                });
            } else {
                alert('Silakan isi pesan kustom broadcast terlebih dahulu.');
            }
            return;
        }

        const proceedSend = () => {
            const btn = $('#btnKirimBroadcastNow');
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Mengirim (' + selectedIds.length + ' Pesan)...');

            $.ajax({
                url: '<?= base_url('crm/broadcast/send') ?>',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    mode: mode,
                    custom_message: customMsg
                },
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp fs-5"></i> <span>Kirim</span>');

                    if (res && res.status) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Broadcast Berhasil Terkirim!',
                                text: res.message || 'Pesan broadcast telah berhasil diproses.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert(res.message || 'Pesan broadcast telah berhasil diproses.');
                            location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim',
                                text: (res && res.message) ? res.message : 'Terjadi kendala saat mengirim pesan.'
                            });
                        } else {
                            alert((res && res.message) ? res.message : 'Terjadi kendala saat mengirim pesan.');
                        }
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-brands fa-whatsapp fs-5"></i> <span>Kirim</span>');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Koneksi',
                            text: 'Gagal terhubung ke server. Silakan coba kembali.'
                        });
                    } else {
                        alert('Gagal terhubung ke server. Silakan coba kembali.');
                    }
                }
            });
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Pengiriman Broadcast',
                text: `Kirim pesan WhatsApp broadcast ke ${selectedIds.length} pelanggan terpilih?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2D6A4F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `<i class="fa-brands fa-whatsapp me-1"></i> Ya, Kirim Sekarang!`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    proceedSend();
                }
            });
        } else {
            if (confirm(`Kirim pesan WhatsApp broadcast ke ${selectedIds.length} pelanggan terpilih?`)) {
                proceedSend();
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

