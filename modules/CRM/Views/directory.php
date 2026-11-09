<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Mode Layar Biasa */
    .print-only-header {
        display: none;
    }

    /* Mode Cetak / Ekspor PDF */
    @media print {
        @page {
            size: landscape;
            margin: 8mm 10mm;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 8.5pt !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hilangkan elemen non-cetak */
        #sidebar,
        .topbar,
        .top-navbar,
        .sidebar-backdrop,
        .app-footer,
        .breadcrumb,
        .toast-container,
        .alert,
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history,
        .d-print-none,
        .btn,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate,
        th.col-aksi,
        td.col-aksi,
        th:last-child,
        td:last-child {
            display: none !important;
        }

        /* Reset wrapper layout agar tidak ada margin kiri atau pergeseran ke luar kertas */
        #main-content,
        .content-wrapper,
        .container-fluid,
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            left: 0 !important;
            transform: none !important;
            position: static !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card-header {
            display: none !important;
        }

        .card-body {
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }

        /* Atasi pemotongan tabel horizontal */
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Header / Kop Khusus Cetak */
        .print-only-header {
            display: block !important;
            margin-bottom: 5mm !important;
            padding-bottom: 3mm !important;
            border-bottom: 2px solid #1A2E23 !important;
        }

        #directoryTable {
            width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            margin: 0 !important;
            font-size: 8pt !important;
        }

        #directoryTable th,
        #directoryTable td {
            border: 1px solid #cbd5e1 !important;
            padding: 6px 7px !important;
            vertical-align: middle !important;
            white-space: normal !important;
            word-wrap: break-word !important;
        }

        #directoryTable thead th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 7.5pt !important;
            letter-spacing: 0.04em !important;
        }

        /* Warna badge tetap muncul di hasil PDF */
        .badge {
            border: 1px solid rgba(0, 0, 0, 0.15) !important;
            font-weight: 600 !important;
            padding: 2px 6px !important;
            font-size: 7.5pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        a[href]:after {
            content: none !important;
        }

        a {
            text-decoration: none !important;
            color: inherit !important;
        }
    }

    /* Tombol Aksi Responsive: Teks saat lebar, Ikon saja saat menyusut */
    .col-aksi-btn {
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
        border-radius: 6px !important;
        padding: 0.35rem 0.65rem !important;
        margin: 0 !important;
        transition: all 0.15s ease-in-out;
    }
    .col-aksi-btn i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: 0 !important;
        line-height: 1 !important;
        margin: 0 !important;
        text-align: center !important;
        font-size: 0.85rem !important;
    }
    .col-aksi-btn .btn-text {
        display: inline-block;
        line-height: 1;
        vertical-align: middle;
    }
    .col-aksi-th, .col-aksi {
        text-align: center !important;
        vertical-align: middle !important;
    }
    th.col-aksi-th, th.col-aksi {
        width: 130px;
        text-align: center !important;
    }
    /* Matikan icon sorting DataTables di kolom AKSI agar teks header selalu pas di tengah */
    #directoryTable thead th.col-aksi-th.sorting,
    #directoryTable thead th.col-aksi-th.sorting_asc,
    #directoryTable thead th.col-aksi-th.sorting_desc,
    #directoryTable thead th.col-aksi.sorting,
    #directoryTable thead th.col-aksi.sorting_asc,
    #directoryTable thead th.col-aksi.sorting_desc,
    #directoryTable thead th.col-aksi-th,
    #directoryTable thead th.col-aksi {
        padding-right: 8px !important;
        padding-left: 8px !important;
        text-align: center !important;
        background-image: none !important;
    }
    #directoryTable thead th.col-aksi-th:before,
    #directoryTable thead th.col-aksi-th:after,
    #directoryTable thead th.col-aksi:before,
    #directoryTable thead th.col-aksi:after {
        display: none !important;
        content: "" !important;
    }
    @media (max-width: 1280px) {
        .col-aksi-btn .btn-text {
            display: none !important;
        }
        .col-aksi-btn {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            max-width: 32px !important;
            max-height: 32px !important;
            padding: 0 !important;
            border-radius: 6px !important;
            gap: 0 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .col-aksi-btn i {
            margin: 0 !important;
            font-size: 0.88rem !important;
            line-height: 1 !important;
            vertical-align: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .col-aksi-th, .col-aksi {
            width: 85px !important;
            min-width: 85px !important;
            max-width: 95px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            text-align: center !important;
        }
    }
    @media (max-width: 767.98px) {
        .col-aksi-btn {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            max-width: 32px !important;
            max-height: 32px !important;
            border-radius: 6px !important;
        }
        .col-aksi-th, .col-aksi {
            width: 80px !important;
            min-width: 80px !important;
            max-width: 85px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
            text-align: center !important;
        }
    }
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

<!-- TOP BANNER CRM -->
<div class="card border-0 text-white mb-4 shadow-sm d-print-none" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div class="flex-grow-1">
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; Daftar Customer</div>
                <h3 class="fw-bold text-white mb-1">
                    <i class="fa-solid fa-address-book me-2"></i>Customer Directory CRM
                </h3>
                <p class="text-white-50 small mb-0">Daftar pelanggan otomatis dari seluruh sumber penjualan &mdash; Toko, Shopee, Tokopedia, TikTok Shop</p>
            </div>
            <div class="ms-auto flex-shrink-0 align-self-end align-self-md-center">
                <a href="<?= base_url('crm') ?>" class="btn btn-sm btn-light fw-semibold text-primary d-inline-flex align-items-center gap-1 shadow-sm px-3 text-nowrap">
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
            <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                <i class="fa-brands fa-whatsapp me-1"></i>3. Broadcast WA
            </a>
            <a href="<?= base_url('crm/directory') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 4. Customer Directory
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

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 10px;">
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="searchCustomer" class="form-control border-start-0" placeholder="Cari nama customer, nomor HP, atau nomor resi...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select id="filterSegment" class="form-select form-select-sm">
                    <option value="">-- Semua Segmen RFM --</option>
                    <option value="Champions">Champions</option>
                    <option value="Loyal Customers">Loyal Customers</option>
                    <option value="Potential Loyalist">Potential Loyalist</option>
                    <option value="Need Attention">Need Attention</option>
                    <option value="At Risk">At Risk</option>
                </select>
            </div>
            <div class="col-12 col-md-5 d-flex gap-1 justify-content-md-end flex-nowrap align-items-center overflow-x-auto pb-1 pb-md-0">
                <button type="button" class="btn btn-primary btn-sm px-2 fw-semibold text-nowrap">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
                <a href="<?= base_url('crm/directory') ?>" class="btn btn-light btn-sm px-2 text-secondary border text-nowrap">
                    <i class="fa-solid fa-rotate me-1"></i>Reset
                </a>
                <a href="<?= base_url('crm/directory/export') ?>" class="btn btn-success btn-sm px-2 fw-semibold text-nowrap">
                    <i class="fa-solid fa-file-csv me-1"></i>CSV
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm px-2 fw-semibold text-nowrap" onclick="printPdfDirectory()">
                    <i class="fa-solid fa-file-pdf me-1"></i>PDF
                </button>
                <a href="<?= base_url('crm') ?>" class="btn btn-warning btn-sm px-2 fw-semibold text-white text-nowrap">
                    <i class="fa-solid fa-book-open me-1"></i>Journal
                </a>
            </div>
        </div>
    </div>
</div>

<!-- KOP LAPORAN KHUSUS CETAK PDF -->
<div class="print-only-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1A2E23; font-size: 15pt; letter-spacing: -0.02em;">LAPORAN CUSTOMER DIRECTORY CRM</h4>
            <div style="font-size: 8.5pt; color: #4B6354;">
                PT Metafora Indonesia Edukasi &bull; Rekap Data Pelanggan Multi-Kanal (Toko, Shopee, Tokopedia, TikTok Shop)
            </div>
        </div>
        <div style="text-align: right; font-size: 8pt; color: #475569; line-height: 1.4;">
            <div><strong>Tanggal Cetak:</strong> <?= date('d/m/Y H:i') ?> WIB</div>
            <div><strong>Total Data:</strong> <?= count($customers) ?> Pelanggan Terdaftar</div>
        </div>
    </div>
</div>

<!-- DAFTAR CUSTOMER TABLE -->
<div class="card border-0 shadow-sm" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-3 d-print-none">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-users text-primary"></i> Daftar Customer (<?= count($customers) ?> total)
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="directoryTable">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <th class="ps-3 py-3" style="width: 40px;">#</th>
                        <th>CUSTOMER</th>
                        <th>NO. HP</th>
                        <th>SUMBER PENJUALAN</th>
                        <th>SEGMEN RFM</th>
                        <th class="text-center">TOTAL ORDER</th>
                        <th>TRANSAKSI TERAKHIR</th>
                        <th>PENDING FOLLOW-UP</th>
                        <th class="text-center d-print-none col-aksi col-aksi-th">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $idx => $c): ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($c['nama_pelanggan']) ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><?= esc($c['kode_pelanggan'] ?? 'CUST-'.str_pad((string)$c['id'], 4, '0', STR_PAD_LEFT)) ?></div>
                                </td>
                                <td>
                                    <span class="text-secondary small font-monospace d-inline-flex align-items-center gap-1">
                                        <i class="fa-solid fa-phone text-success small"></i> <?= esc($c['no_telp'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $source = strtolower($c['last_source'] ?? 'toko');
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
                                <td>
                                    <?php 
                                        $seg = $c['segment'] ?? 'Need Attention';
                                        $segStyle = match($seg) {
                                            'Champions' => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                                            'Loyal Customers' => 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;',
                                            default => 'background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;',
                                        };
                                        $icon = match($seg) {
                                            'Champions' => 'fa-trophy',
                                            'Loyal Customers' => 'fa-gem',
                                            default => 'fa-bell',
                                        };
                                    ?>
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="<?= $segStyle ?>">
                                        <i class="fa-solid <?= $icon ?>"></i> <?= esc($seg) ?>
                                    </span>
                                </td>
                                <td class="text-center font-monospace fw-bold text-dark">
                                    <?= esc($c['total_order']) ?>
                                </td>
                                <td class="small text-muted">
                                    <?= !empty($c['last_order_date']) ? date('d M Y', strtotime($c['last_order_date'])) : '-' ?>
                                </td>
                                <td>
                                    <?php if (($c['pending_count'] ?? 0) > 0): ?>
                                        <a href="<?= base_url('crm?search=' . urlencode($c['nama_pelanggan'])) ?>" class="badge rounded-pill px-2 py-1 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fw-bold text-decoration-none d-inline-flex align-items-center gap-1 shadow-sm" title="Klik untuk melihat <?= $c['pending_count'] ?> pending di Jurnal Follow-Up">
                                            <i class="fa-solid fa-clock"></i> <?= $c['pending_count'] ?> Pending
                                        </a>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-2 py-1 bg-success bg-opacity-10 text-success fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="fa-solid fa-check"></i> Done
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center d-print-none col-aksi col-aksi-th text-nowrap align-middle">
                                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                        <a href="<?= base_url('crm/customer_detail?id=' . $c['id']) ?>" class="btn btn-primary btn-sm rounded col-aksi-btn shadow-xs" title="Detail Customer">
                                            <i class="fa-solid fa-eye"></i><span class="btn-text ms-1">Detail</span>
                                        </a>
                                        <?php if (!empty($c['no_telp'])): ?>
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/\D/', '', $c['no_telp'])) ?>" target="_blank" class="btn btn-success btn-sm rounded col-aksi-btn shadow-xs" title="Chat WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i><span class="btn-text ms-1">Chat</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data pelanggan yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let directoryDataTable = null;

    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#directoryTable')) {
            $('#directoryTable').DataTable().destroy();
        }
        directoryDataTable = $('#directoryTable').DataTable({
            dom: 'rtip',
            pageLength: 25,
            order: [],
            columnDefs: [
                { orderable: false, targets: [0, 8] }
            ],
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#searchCustomer').on('keyup', function() {
            directoryDataTable.search(this.value).draw();
        });

        $('#filterSegment').on('change', function() {
            directoryDataTable.column(4).search(this.value).draw();
        });
    });

    function printPdfDirectory() {
        if (directoryDataTable) {
            const currentLen = directoryDataTable.page.len();
            // Tampilkan seluruh data untuk dicetak lengkap tanpa terpotong pagination
            directoryDataTable.page.len(-1).draw();

            setTimeout(function() {
                window.print();
                // Kembalikan ke pagination semula
                directoryDataTable.page.len(currentLen).draw();
            }, 300);
        } else {
            window.print();
        }
    }

    // Modal Detail Pelanggan dengan integrasi CRM & RFM
    $(document).on('click', '.btn-show-detail-crm', function() {
        const custId = $(this).data('id');
        const modalEl = document.getElementById('modalCustomerDetailCrmRfm');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        
        // Reset state ke mode loading
        $('#crmDetailLoading').removeClass('d-none');
        $('#crmDetailContent').addClass('d-none');
        modal.show();

        fetch('<?= base_url('crm/directory/detail') ?>/' + custId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                populateCustomerDetailModal(res.data);
                $('#crmDetailLoading').addClass('d-none');
                $('#crmDetailContent').removeClass('d-none');
            } else {
                alert(res.message || 'Gagal memuat detail pelanggan.');
                modal.hide();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menghubungi server.');
            modal.hide();
        });
    });

    function populateCustomerDetailModal(d) {
        const cust = d.customer || {};
        const rfm  = d.rfm || {};
        const crm  = d.crm || {};

        // Profil & Identitas
        $('#cDetailAvatar').text((cust.nama_pelanggan || 'C').charAt(0).toUpperCase());
        $('#cDetailNama').text(cust.nama_pelanggan || '-');
        $('#cDetailKode').text(cust.kode_pelanggan || '-');
        $('#cDetailPhone').text(cust.no_telp || '-');
        $('#cDetailEmail').text(cust.email || '-');
        $('#cDetailKota').text(cust.kota || '-');
        $('#cDetailAlamat').text(cust.alamat || '-');
        $('#cDetailSource').text(cust.last_source || 'Toko');
        $('#cDetailTipe').text(cust.tipe || 'Regular');

        // Link WhatsApp
        const cleanPhone = (cust.no_telp || '').replace(/\D/g, '');
        let waPhone = cleanPhone;
        if (waPhone.startsWith('0')) waPhone = '62' + waPhone.substring(1);
        if (waPhone) {
            $('#cDetailBtnWa').attr('href', 'https://wa.me/' + waPhone).removeClass('d-none');
        } else {
            $('#cDetailBtnWa').addClass('d-none');
        }

        // Link Jurnal Follow-Up
        $('#cDetailBtnJurnal').attr('href', '<?= base_url('crm?search=') ?>' + encodeURIComponent(cust.nama_pelanggan));

        // Badge Segmen RFM
        const seg = rfm.segment || 'Need Attention';
        const segStyles = {
            'Champions': { style: 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;', icon: 'fa-trophy' },
            'Loyal Customers': { style: 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;', icon: 'fa-gem' },
            'Potential Loyalist': { style: 'background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe;', icon: 'fa-star' },
            'New Customers': { style: 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;', icon: 'fa-user-plus' },
            'Big Spenders': { style: 'background-color: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe;', icon: 'fa-credit-card' },
            'Frequent Low Value': { style: 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;', icon: 'fa-basket-shopping' },
            'At Risk Customers': { style: 'background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;', icon: 'fa-triangle-exclamation' },
            "Can't Lose Them": { style: 'background-color: #fce7f3; color: #be185d; border: 1px solid #fbcfe8;', icon: 'fa-heart-crack' },
            'Hibernating': { style: 'background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;', icon: 'fa-moon' },
        };
        const sConf = segStyles[seg] || { style: 'background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;', icon: 'fa-bell' };
        $('#cDetailSegmentBadge')
            .attr('style', sConf.style + ' border-radius: 20px; font-size: 0.78rem;')
            .html(`<i class="fa-solid ${sConf.icon} me-1"></i>${seg}`);

        // Kartu Metrik RFM
        $('#cDetailRecency').text((rfm.recency_days ?? 0) + ' Hari');
        $('#cDetailRecencySub').text(`Order: ${rfm.last_order_date || '-'} (Skor R: ${rfm.score_r || 1}/5)`);
        $('#cDetailFrequency').text((rfm.total_orders ?? 0) + 'x Order');
        $('#cDetailFrequencySub').text(`Skor Frekuensi: ${rfm.score_f || 1}/5`);
        $('#cDetailMonetary').text(rfm.total_spend_fmt || 'Rp 0');
        $('#cDetailMonetarySub').text(`Skor Monetary: ${rfm.score_m || 1}/5`);
        $('#cDetailScoreRfm').html(`
            <span class="badge bg-success rounded px-2 py-1 font-monospace">${rfm.score_r || 1}</span>
            <span class="badge bg-warning rounded px-2 py-1 font-monospace">${rfm.score_f || 1}</span>
            <span class="badge bg-info rounded px-2 py-1 font-monospace">${rfm.score_m || 1}</span>
        `);
        $('#cDetailAov').text(rfm.aov_fmt || 'Rp 0');
        $('#cDetailGoal').text(rfm.goal || '-');
        $('#cDetailAction').text(rfm.action || '-');

        // Ringkasan CRM
        const pendingCount = crm.pending_count || 0;
        const doneCount = crm.done_count || 0;
        $('#cDetailBadgePending').text(pendingCount + ' Pending');
        $('#cDetailBadgeDone').text(doneCount + ' Selesai');

        // Tabel Jadwal Follow-Up CRM
        const labels = crm.followup_labels || [];
        let labelHtml = '';
        if (labels.length > 0) {
            labels.forEach(lbl => {
                const isPending = lbl.status === 'pending';
                const statusBadge = isPending 
                    ? '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold"><i class="fa-solid fa-clock me-1"></i>Pending</span>'
                    : '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold"><i class="fa-solid fa-check me-1"></i>Terkirim</span>';
                labelHtml += `
                    <tr>
                        <td class="fw-semibold text-dark">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1">${lbl.label_code || '-'}</span>
                            ${lbl.label_name || '-'}
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">${lbl.nomor_so || '-'}</div>
                            <div class="text-muted small">${lbl.product_name || '-'}</div>
                        </td>
                        <td>
                            <div class="text-dark">${lbl.target_date || '-'}</div>
                            <div class="text-muted small">${lbl.courier ? (lbl.courier + (lbl.tracking_number ? ' (' + lbl.tracking_number + ')' : '')) : '-'}</div>
                        </td>
                        <td class="text-center">${statusBadge}</td>
                    </tr>
                `;
            });
        } else {
            labelHtml = '<tr><td colspan="4" class="text-center py-3 text-muted">Belum ada jadwal follow-up untuk customer ini.</td></tr>';
        }
        $('#cDetailFollowupTbody').html(labelHtml);

        // Catatan CS CRM
        const notes = crm.notes || [];
        let notesHtml = '';
        if (notes.length > 0) {
            notes.forEach(nt => {
                notesHtml += `
                    <div class="p-2 mb-2 bg-light rounded border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark small"><i class="fa-solid fa-user-tie me-1 text-primary"></i>${nt.cs_name || 'Staff CS'}</span>
                            <span class="text-muted" style="font-size: 0.7rem;"><i class="fa-regular fa-clock me-1"></i>${nt.created_at || '-'}</span>
                        </div>
                        <div class="small text-secondary">${nt.notes || '-'}</div>
                        ${nt.status_respon ? `<div class="mt-1"><span class="badge bg-info bg-opacity-10 text-info" style="font-size: 0.68rem;">Respon: ${nt.status_respon}</span></div>` : ''}
                    </div>
                `;
            });
        } else {
            notesHtml = '<div class="text-center py-4 text-muted small">Belum ada catatan interaksi CS untuk pelanggan ini.</div>';
        }
        $('#cDetailNotesContainer').html(notesHtml);

        // Riwayat Pesanan Sales Order
        const orders = crm.sales_orders || [];
        let ordersHtml = '';
        if (orders.length > 0) {
            orders.forEach(so => {
                const totalFmt = 'Rp ' + Number(so.total || 0).toLocaleString('id-ID');
                ordersHtml += `
                    <tr>
                        <td class="ps-3 fw-bold font-monospace text-primary">${so.nomor_so || '-'}</td>
                        <td>${so.tanggal_so || '-'}</td>
                        <td><span class="badge bg-light text-dark border">${so.source || 'Toko'}</span></td>
                        <td class="text-muted small">${so.metode_bayar || 'Transfer'}</td>
                        <td class="text-end font-monospace fw-bold text-success">${totalFmt}</td>
                        <td class="pe-3 text-end"><span class="badge bg-success bg-opacity-10 text-success fw-bold">${so.status || 'Selesai'}</span></td>
                    </tr>
                `;
            });
        } else {
            ordersHtml = '<tr><td colspan="6" class="text-center py-3 text-muted">Belum ada riwayat pesanan sales order.</td></tr>';
        }
        $('#cDetailOrdersTbody').html(ordersHtml);
    }
</script>

<!-- MODAL DETAIL PELANGGAN (CRM & RFM) -->
<div class="modal fade" id="modalCustomerDetailCrmRfm" tabindex="-1" aria-labelledby="modalCustomerDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px; font-size: 18px;" id="cDetailAvatar">
                        C
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" id="cDetailNama">-</h5>
                            <span class="badge bg-white text-dark fw-bold px-2 py-1" style="font-size: 0.75rem;" id="cDetailKode">-</span>
                            <span class="badge px-3 py-1 fw-semibold" id="cDetailSegmentBadge">-</span>
                        </div>
                        <div class="text-white-50 small mt-1 d-flex align-items-center gap-3 flex-wrap">
                            <span><i class="fa-solid fa-phone me-1"></i><span id="cDetailPhone">-</span></span>
                            <span><i class="fa-solid fa-envelope me-1"></i><span id="cDetailEmail">-</span></span>
                            <span><i class="fa-solid fa-location-dot me-1"></i><span id="cDetailKota">-</span></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 bg-light">
                <!-- Loading State -->
                <div id="crmDetailLoading" class="text-center py-5">
                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Memuat data...</span>
                    </div>
                    <div class="text-muted fw-semibold mt-3">Mengambil profil, analisis RFM, &amp; riwayat CRM...</div>
                </div>

                <!-- Content State -->
                <div id="crmDetailContent" class="d-none">
                    <!-- Quick Actions Bar -->
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
                        <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap small text-muted">
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="fa-solid fa-shop me-1 text-primary"></i>Sumber: <strong class="text-dark" id="cDetailSource">-</strong></span>
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="fa-solid fa-id-badge me-1 text-info"></i>Tipe: <strong class="text-dark" id="cDetailTipe">-</strong></span>
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="fa-solid fa-map-pin me-1 text-danger"></i>Alamat: <span class="text-dark" id="cDetailAlamat">-</span></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="#" target="_blank" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3" id="cDetailBtnWa">
                                    <i class="fa-brands fa-whatsapp"></i> Chat WA
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3" id="cDetailBtnJurnal">
                                    <i class="fa-solid fa-clock-rotate-left"></i> Jurnal Follow-Up
                                </a>
                                <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-sm btn-outline-secondary fw-semibold d-inline-flex align-items-center gap-1 px-3">
                                    <i class="fa-solid fa-user-pen"></i> Master Pelanggan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 1: ANALISIS & METRIK RFM -->
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-primary"></i> Analisis &amp; Segmentasi RFM Pelanggan
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <!-- 4 Kartu Metrik RFM -->
                            <div class="row g-2 mb-3">
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-3 border text-center h-100 shadow-sm">
                                        <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Recency (R)</div>
                                        <div class="fs-4 fw-bold text-info font-monospace my-1" id="cDetailRecency">-</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;" id="cDetailRecencySub">-</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-3 border text-center h-100 shadow-sm">
                                        <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Frequency (F)</div>
                                        <div class="fs-4 fw-bold text-success font-monospace my-1" id="cDetailFrequency">-</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;" id="cDetailFrequencySub">-</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-3 border text-center h-100 shadow-sm">
                                        <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Monetary (M)</div>
                                        <div class="fs-4 fw-bold text-warning-emphasis font-monospace my-1 text-truncate" id="cDetailMonetary">-</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;" id="cDetailMonetarySub">-</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-3 border text-center h-100 shadow-sm">
                                        <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Skor RFM &amp; AOV</div>
                                        <div class="fs-5 fw-bold text-primary font-monospace my-1" id="cDetailScoreRfm">-</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">AOV: <strong class="text-dark" id="cDetailAov">-</strong></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Goal & Rekomendasi Aksi Promo RFM -->
                            <div class="p-3 bg-white border rounded-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-6 border-end-md">
                                        <div class="small fw-bold text-dark d-flex align-items-center gap-1 mb-1">
                                            <i class="fa-solid fa-bullseye text-danger"></i> Target Perilaku Pelanggan (Goal)
                                        </div>
                                        <p class="mb-0 text-secondary small" id="cDetailGoal">-</p>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="small fw-bold text-dark d-flex align-items-center gap-1 mb-1">
                                            <i class="fa-solid fa-wand-magic-sparkles text-success"></i> Rekomendasi Strategi Promo &amp; CRM (Action)
                                        </div>
                                        <p class="mb-0 text-secondary small" id="cDetailAction">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: STATUS & RIWAYAT AKTIVITAS CRM -->
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fa-solid fa-users-gear text-success"></i> Aktivitas Follow-Up CRM Pelanggan
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold" id="cDetailBadgePending">0 Pending</span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold" id="cDetailBadgeDone">0 Selesai</span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <!-- Kolom Jadwal Follow-Up Transaksi -->
                                <div class="col-12 col-lg-7">
                                    <div class="fw-semibold small text-dark mb-2 d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-list-check text-primary"></i> Jadwal Tahapan Follow-Up
                                    </div>
                                    <div class="table-responsive border rounded bg-white" style="max-height: 220px; overflow-y: auto;">
                                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.78rem;">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th>TAHAP / LABEL</th>
                                                    <th>ORDER / PRODUK</th>
                                                    <th>TARGET</th>
                                                    <th class="text-center">STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cDetailFollowupTbody">
                                                <!-- Dynamic rows -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Kolom Catatan Penanganan CS (Notes) -->
                                <div class="col-12 col-lg-5">
                                    <div class="fw-semibold small text-dark mb-2 d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-comment-dots text-info"></i> Catatan Penanganan CS
                                    </div>
                                    <div class="border rounded p-2 bg-white" style="max-height: 220px; overflow-y: auto;" id="cDetailNotesContainer">
                                        <!-- Dynamic notes -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: RIWAYAT PESANAN / SALES ORDER -->
                    <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fa-solid fa-receipt text-warning"></i> Riwayat Transaksi Sales Order (10 Terakhir)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3 py-2">NO. SO</th>
                                            <th>TANGGAL</th>
                                            <th>SUMBER</th>
                                            <th>METODE BAYAR</th>
                                            <th class="text-end">TOTAL BELANJA</th>
                                            <th class="pe-3 text-end">STATUS PESANAN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cDetailOrdersTbody">
                                        <!-- Dynamic orders -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                <span class="text-muted small" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>Data profil, analisis RFM &amp; riwayat CRM tersinkronisasi otomatis.
                </span>
                <button type="button" class="btn btn-secondary btn-sm fw-semibold px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</script>
<?= $this->endSection() ?>

