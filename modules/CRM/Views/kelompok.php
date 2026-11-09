<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .var-badge {
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease-in-out;
        background-color: #f8fafc;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        font-size: 0.78rem;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .var-badge:hover {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(45, 106, 79, 0.2);
    }
    .var-badge:active {
        transform: translateY(0);
        background-color: #1A2E23 !important;
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
<div class="card border-0 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 12px;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div>
                <div class="small text-white-50"><i class="fa-solid fa-users-gear me-1"></i>CRM &amp; After Sales &gt; Kelompok Follow-Up</div>
                <h3 class="fw-bold text-white mb-1 fs-4 fs-md-3">
                    <i class="fa-solid fa-shapes me-2"></i>Kelompok &amp; Template Follow-Up
                </h3>
                <p class="text-white-50 small mb-0">Atur kategori H+ custom dan template pesan WhatsApp untuk setiap tahapan follow-up</p>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3" onclick="resetCategoryForm(); document.getElementById('namaKategoriInput').focus();">
                    <i class="fa-solid fa-plus"></i> Tambah Kategori Baru
                </button>
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
            <a href="<?= base_url('crm/directory') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                4. Customer Directory
            </a>
            <a href="<?= base_url('crm/kinerja') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                5. Kinerja Tim CS
            </a>
            <a href="<?= base_url('crm/kelompok') ?>" class="btn btn-sm bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm">
                &#9679; 6. Kelompok Follow-Up
            </a>
        </div>
    </div>
</div>

<!-- MAIN TWO-COLUMN CONTENT -->
<div class="row g-4">
    <!-- LEFT: KATEGORI AKTIF -->
    <div class="col-12 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check text-primary"></i> Kategori Aktif (<?= count($categories) ?>)
            </h6>
        </div>

        <?php foreach ($categories as $cat): ?>
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; border-left: 4px solid #2D6A4F !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-0"><?= esc($cat['name']) ?></h6>
                            <span class="text-muted font-monospace" style="font-size: 0.78rem;"><?= esc($cat['code']) ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill px-2 py-1 bg-primary bg-opacity-10 text-primary fw-bold font-monospace">
                                <?= esc($cat['days']) ?>
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 fw-semibold" style="font-size: 0.75rem;" onclick='editCategory(<?= json_encode($cat, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" style="font-size: 0.75rem;" onclick="deleteCategory(<?= (int)$cat['id'] ?>, '<?= esc($cat['name']) ?>')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-2 text-secondary small font-monospace mb-2" style="white-space: pre-wrap; font-size: 0.8rem; border: 1px solid #e2e8f0;"><?= esc($cat['template']) ?></div>

                    <div class="text-muted small d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                        <span>Status: <span class="text-success fw-semibold">&#9679; <?= esc($cat['status']) ?></span> | Urutan: <?= esc($cat['order_index']) ?></span>
                        <a href="<?= base_url('crm') ?>" class="text-primary text-decoration-none fw-semibold">Gunakan di Journal &rarr;</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- RIGHT: FORM TAMBAH / EDIT KATEGORI BARU -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm sticky-lg-top" style="top: 80px; border-radius: 10px;">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center" id="formTitle">
                    <i class="fa-solid fa-plus-circle text-success me-2"></i> Tambah Kategori Baru
                </h6>
                <button type="button" id="btnCancelEdit" class="btn btn-sm btn-light border text-secondary d-none" onclick="resetCategoryForm()">
                    <i class="fa-solid fa-xmark me-1"></i> Batal
                </button>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('crm/kelompok/store') ?>" method="post" id="formKelompok">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="kategoriIdInput" value="">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary mb-1">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="namaKategoriInput" name="nama" class="form-control form-control-sm" placeholder="e.g. H+14 - Follow Up Lanjutan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary mb-1">
                            Kode Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="codeKategoriInput" name="code" class="form-control form-control-sm" placeholder="e.g. H_14_FOLLOWUP" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary mb-1">
                            Jumlah Hari (H+n) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="daysKategoriInput" name="days" class="form-control form-control-sm" value="0" required>
                        <div class="form-text small" style="font-size: 0.78rem;">H+0 = saat kirim, H+3 = 3 hari setelah kirim, dst.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary mb-1">Template Pesan WA</label>
                        <textarea id="templateInput" name="template" rows="4" class="form-control form-control-sm font-monospace" placeholder="Halo Kak {{nama_customer}}..."></textarea>
                        
                        <div class="mt-2">
                            <div class="text-muted small mb-1" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-hand-pointer me-1 text-primary"></i>Variabel tersedia (klik untuk menyisipkan):
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="var-badge font-monospace" onclick="insertVar('{{nama_customer}}')" title="Klik untuk menyisipkan {{nama_customer}}">{{nama_customer}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{no_pesanan}}')" title="Klik untuk menyisipkan {{no_pesanan}}">{{no_pesanan}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{nomor_resi}}')" title="Klik untuk menyisipkan {{nomor_resi}}">{{nomor_resi}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{produk}}')" title="Klik untuk menyisipkan {{produk}}">{{produk}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{tanggal_kirim}}')" title="Klik untuk menyisipkan {{tanggal_kirim}}">{{tanggal_kirim}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{sumber_penjualan}}')" title="Klik untuk menyisipkan {{sumber_penjualan}}">{{sumber_penjualan}}</span>
                                <span class="var-badge font-monospace" onclick="insertVar('{{kurir}}')" title="Klik untuk menyisipkan {{kurir}}">{{kurir}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary mb-1">Urutan Tampil (No. Urut)</label>
                            <input type="number" id="orderIndexInput" name="order_index" class="form-control form-control-sm" value="7">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                            <select id="statusSelect" name="status" class="form-select form-select-sm">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitForm" class="btn btn-primary btn-sm w-100 fw-semibold py-2">
                        <i class="fa-solid fa-save me-1"></i> Simpan Kategori &amp; Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function insertVar(varText) {
    const textarea = document.getElementById('templateInput');
    if (!textarea) return;

    // Focus ke textarea
    textarea.focus();

    const startPos = textarea.selectionStart;
    const endPos = textarea.selectionEnd;
    const currentVal = textarea.value;

    if (startPos !== undefined && endPos !== undefined) {
        // Sisipkan pada posisi kursor atau ganti teks yang diblok
        textarea.value = currentVal.substring(0, startPos) + varText + currentVal.substring(endPos);
        // Letakkan kursor tepat setelah variabel yang disisipkan
        const newPos = startPos + varText.length;
        textarea.selectionStart = textarea.selectionEnd = newPos;
    } else {
        // Fallback jika tidak terdeteksi kursor
        textarea.value += varText;
    }

    // Trigger input event
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
}

function editCategory(cat) {
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Kategori: ' + cat.name;
    document.getElementById('kategoriIdInput').value = cat.id;
    document.getElementById('namaKategoriInput').value = cat.name;
    document.getElementById('codeKategoriInput').value = cat.code;
    document.getElementById('daysKategoriInput').value = parseInt(String(cat.days).replace(/\D/g, '')) || 0;
    document.getElementById('templateInput').value = cat.template;
    document.getElementById('orderIndexInput').value = cat.order_index;
    document.getElementById('statusSelect').value = cat.status;
    document.getElementById('btnSubmitForm').innerHTML = '<i class="fa-solid fa-save me-1"></i> Perbarui Kategori &amp; Template';
    document.getElementById('btnCancelEdit').classList.remove('d-none');
    
    // Langsung fokus ke nama kategori
    document.getElementById('namaKategoriInput').focus();
}

function resetCategoryForm() {
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-plus-circle text-success me-2"></i>Tambah Kategori Baru';
    document.getElementById('kategoriIdInput').value = '';
    document.getElementById('namaKategoriInput').value = '';
    document.getElementById('codeKategoriInput').value = '';
    document.getElementById('daysKategoriInput').value = '0';
    document.getElementById('templateInput').value = '';
    document.getElementById('orderIndexInput').value = '7';
    document.getElementById('statusSelect').value = 'Aktif';
    document.getElementById('btnSubmitForm').innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan Kategori &amp; Template';
    document.getElementById('btnCancelEdit').classList.add('d-none');
}

function deleteCategory(id, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: `Yakin ingin menghapus kategori follow-up '${name}'?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C0392B',
            cancelButtonColor: '#8DA398',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteKelompok');
                form.action = '<?= base_url('crm/kelompok/delete') ?>/' + id;
                form.submit();
            }
        });
    } else {
        if (confirm(`Yakin ingin menghapus kategori follow-up '${name}'?`)) {
            const form = document.getElementById('formDeleteKelompok');
            form.action = '<?= base_url('crm/kelompok/delete') ?>/' + id;
            form.submit();
        }
    }
}
</script>

<!-- HIDDEN DELETE FORM -->
<form id="formDeleteKelompok" method="POST" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

