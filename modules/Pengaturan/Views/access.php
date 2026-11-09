<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TOP HEADER -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0">Matriks Hak Akses (Access Matrix)</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small">
                Konfigurasi Otoritas Role
            </span>
        </div>
        <p class="text-muted small mb-0">
            Atur visibilitas fitur, akses menu, dan wewenang operasional untuk setiap grup pengguna sistem.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('pengaturan/permission') ?>" class="btn btn-sm btn-outline-primary text-dark fw-semibold px-3 bg-white border shadow-xs">
            <i class="fa-solid fa-key text-primary me-1"></i>Katalog Permission
        </a>
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 shadow-xs btn-save-access" onclick="submitAccessForm(this);">
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Perubahan
        </button>
    </div>
</div>

<!-- ACCESS REGISTER CARD -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
    <!-- CARD HEADER & TOOLBAR -->
    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-shield-halved fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Matriks Otoritas Hak Akses</h6>
                <div class="text-muted small" style="font-size: 0.75rem;">Pilih peran target di bawah untuk mengaktifkan atau menonaktifkan modul</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-light border fw-semibold px-3" data-bs-toggle="modal" data-bs-target="#modalAddPermission">
                <i class="fa-solid fa-plus me-1 text-primary"></i>Tambah Izin Baru
            </button>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- ROLE SELECTION PILLS -->
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <span class="small fw-bold text-dark text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.04em;">PILIH PERAN / GRUP PENGGUNA:</span>
                <span class="small text-muted">
                    Sedang mengatur: <strong class="text-primary font-monospace" id="activeRoleLabel"><?= esc($activeRole['display_name'] ?? 'Super Admin') ?></strong>
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap" id="roleTabs">
                <?php foreach ($roles as $r): ?>
                    <?php $isActive = ((int) $r['id'] === (int) $activeRoleId); ?>
                    <button type="button" 
                            class="btn btn-sm <?= $isActive ? 'btn-primary' : 'btn-light border text-secondary' ?> role-tab fw-semibold px-3 py-1"
                            style="border-radius: 20px;"
                            data-role-id="<?= $r['id'] ?>"
                            data-role-name="<?= esc($r['display_name']) ?>">
                        <?= esc($r['display_name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- SEARCH & MARK ALL TOOLBAR -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-light rounded-3 mb-4 border">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="markAll">
                <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="markAll">Pilih / Hapus Semua Izin (Check All)</label>
            </div>
            <div class="input-group input-group-sm" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control border-start-0" id="filterAccessInput" placeholder="Saring izin akses...">
            </div>
        </div>

        <form id="formSaveAccess" action="<?= base_url('pengaturan/access/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="role_id" id="selectedRoleIdInput" value="<?= esc($activeRoleId) ?>">
            <input type="hidden" name="role_name" id="selectedRoleInput" value="<?= esc($activeRole['display_name'] ?? 'Super Admin') ?>">

            <!-- CLEAN MODERN PERMISSION PANELS -->
            <div class="row g-3" id="accessPanelGrid">
                <!-- 1. Menu & Navigasi -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border shadow-xs h-100" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-menu">
                                <span>Navigasi &amp; Menu</span>
                            </label>
                            <i class="fa-solid fa-bars text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-menu">
                            <?php if (!empty($groupedPermissions['menu']['items'])): ?>
                                <?php foreach ($groupedPermissions['menu']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin menu.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 2. Master & Stok -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border shadow-xs h-100" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-stok">
                                <span>Master &amp; Stok</span>
                            </label>
                            <i class="fa-solid fa-boxes-stacked text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-stok">
                            <?php if (!empty($groupedPermissions['master']['items'])): ?>
                                <?php foreach ($groupedPermissions['master']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin master data.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 3. Penjualan & POS + CRM & RFM -->
                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-3">
                    <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-penjualan">
                                <span>Penjualan &amp; POS</span>
                            </label>
                            <i class="fa-solid fa-cart-shopping text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-penjualan">
                            <?php if (!empty($groupedPermissions['penjualan']['items'])): ?>
                                <?php foreach ($groupedPermissions['penjualan']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin penjualan.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-crm">
                                <span>CRM &amp; RFM</span>
                            </label>
                            <i class="fa-solid fa-users-viewfinder text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-crm">
                            <?php if (!empty($groupedPermissions['crm']['items'])): ?>
                                <?php foreach ($groupedPermissions['crm']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin CRM &amp; RFM.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 4. Pembelian & Keuangan -->
                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-3">
                    <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-pembelian">
                                <span>Pembelian</span>
                            </label>
                            <i class="fa-solid fa-truck-ramp-box text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-pembelian">
                            <?php if (!empty($groupedPermissions['pembelian']['items'])): ?>
                                <?php foreach ($groupedPermissions['pembelian']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin pembelian.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-keuangan">
                                <span>Keuangan &amp; Kas</span>
                            </label>
                            <i class="fa-solid fa-calculator text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-column gap-2 text-secondary grp-keuangan">
                            <?php if (!empty($groupedPermissions['keuangan']['items'])): ?>
                                <?php foreach ($groupedPermissions['keuangan']['items'] as $item): ?>
                                    <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                    <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label">
                                        <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                        <span><?= esc($item['definition']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Belum ada izin keuangan.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 5. Izin Tambahan / Kustom (Jika ada) -->
                <?php if (!empty($groupedPermissions['custom']['items'])): ?>
                <div class="col-12 mt-2">
                    <div class="card border shadow-xs" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-2 px-3 bg-light border-bottom fw-bold small d-flex justify-content-between align-items-center text-dark">
                            <label class="mb-0 cursor-pointer d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input check-group-parent mt-0" data-target="grp-custom">
                                <span>Izin Tambahan &amp; Kustom</span>
                            </label>
                            <i class="fa-solid fa-shield-halved text-secondary small"></i>
                        </div>
                        <div class="p-3 bg-white small d-flex flex-wrap gap-3 text-secondary grp-custom">
                            <?php foreach ($groupedPermissions['custom']['items'] as $item): ?>
                                <?php $checked = in_array($item['name'], $currentRolePermissions, true); ?>
                                <label class="cursor-pointer d-flex align-items-center gap-2 mb-0 perm-label" style="min-width: 220px;">
                                    <input type="checkbox" name="permissions[]" value="<?= esc($item['name']) ?>" <?= $checked ? 'checked' : '' ?> class="form-check-input perm-item-checkbox mt-0">
                                    <span><?= esc($item['definition']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- CARD FOOTER -->
    <div class="card-footer bg-light py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="small text-muted">
            <i class="fa-solid fa-circle-info me-1 text-primary"></i>Hak akses akan segera berlaku bagi seluruh akun dalam role yang bersangkutan.
        </span>
        <button type="button" class="btn btn-sm btn-primary fw-semibold px-4 shadow-xs btn-save-access" onclick="submitAccessForm(this);">
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Hak Akses
        </button>
    </div>
</div>

<!-- MODAL ADD PERMISSION -->
<div class="modal fade" id="modalAddPermission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold mb-0"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Izin Akses Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('pengaturan/permission/store') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect_to" value="pengaturan/access">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kunci Izin (Permission Key) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm font-monospace" placeholder="contoh: inventory_adjust_approve" required>
                        <div class="text-muted" style="font-size: 0.72rem;">Gunakan format huruf kecil dan garis bawah (snake_case).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Modul Terkait</label>
                        <select class="form-select form-select-sm" name="module">
                            <option value="menu">Navigasi / Menu</option>
                            <option value="master">Master Data &amp; Stok</option>
                            <option value="penjualan">Penjualan &amp; POS</option>
                            <option value="crm">CRM &amp; RFM</option>
                            <option value="pembelian">Pembelian</option>
                            <option value="keuangan">Keuangan &amp; Kas</option>
                            <option value="custom" selected>Izin Tambahan / Kustom</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Definisi Wewenang</label>
                        <textarea name="definition" class="form-control form-control-sm" rows="3" placeholder="Deskripsi wewenang dan cakupan tugas izin ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i>Simpan Izin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Dynamic Role Permissions Mapping
let rolePermissionsMap = <?= json_encode($rolePermissionsMap ?? []) ?>;

// Function to refresh state of parent checkboxes & mark all
function updateParentCheckboxes() {
    document.querySelectorAll('.check-group-parent').forEach(parentCb => {
        const grpClass = parentCb.dataset.target;
        const childBoxes = document.querySelectorAll('.' + grpClass + ' .perm-item-checkbox');
        if (childBoxes.length > 0) {
            const checkedCount = Array.from(childBoxes).filter(c => c.checked).length;
            parentCb.checked = (checkedCount === childBoxes.length);
            parentCb.indeterminate = (checkedCount > 0 && checkedCount < childBoxes.length);
        }
    });

    // Update Mark All
    const allBoxes = document.querySelectorAll('.perm-item-checkbox');
    const markAllCb = document.getElementById('markAll');
    if (markAllCb && allBoxes.length > 0) {
        const allCheckedCount = Array.from(allBoxes).filter(c => c.checked).length;
        markAllCb.checked = (allCheckedCount === allBoxes.length);
        markAllCb.indeterminate = (allCheckedCount > 0 && allCheckedCount < allBoxes.length);
    }
}

// Function to switch role
function selectRole(roleId, roleName) {
    const selectedRoleIdInput = document.getElementById('selectedRoleIdInput');
    const selectedRoleInput = document.getElementById('selectedRoleInput');
    const activeRoleLabel = document.getElementById('activeRoleLabel');

    if (selectedRoleIdInput) selectedRoleIdInput.value = roleId;
    if (selectedRoleInput) selectedRoleInput.value = roleName;
    if (activeRoleLabel) activeRoleLabel.textContent = roleName;

    // Update URL query without full page reload
    try {
        const url = new URL(window.location.href);
        url.searchParams.set('role_id', roleId);
        window.history.replaceState(null, '', url.toString());
    } catch (e) {
        console.warn(e);
    }

    // Load active permissions from map
    const activePerms = rolePermissionsMap[roleId] || [];
    document.querySelectorAll('.perm-item-checkbox').forEach(cb => {
        cb.checked = activePerms.includes(cb.value);
    });

    updateParentCheckboxes();
}

// Function to submit form with AJAX or standard fallback
function submitAccessForm(btnElement) {
    const form = document.getElementById('formSaveAccess');
    if (!form) return;

    const roleId = document.getElementById('selectedRoleIdInput').value;
    const roleName = document.getElementById('selectedRoleInput').value;

    const allSaveBtns = document.querySelectorAll('.btn-save-access');
    allSaveBtns.forEach(b => {
        b.disabled = true;
        b.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyimpan...';
    });

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        allSaveBtns.forEach(b => {
            b.disabled = false;
            b.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i>' + (b.innerText.includes('Perubahan') ? 'Simpan Perubahan' : 'Simpan Hak Akses');
        });

        if (data.status === 'success') {
            // Update in-memory mapping so switching tabs stays synced
            rolePermissionsMap[roleId] = data.permissions || [];

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disimpan!',
                    text: data.message || `Konfigurasi hak akses untuk role '${roleName}' berhasil disimpan.`,
                    timer: 2500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                alert(data.message || `Konfigurasi hak akses untuk role '${roleName}' berhasil disimpan.`);
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: data.message || 'Terjadi kendala saat menyimpan hak akses.'
                });
            } else {
                alert(data.message || 'Gagal menyimpan hak akses.');
            }
        }
    })
    .catch(err => {
        console.warn('AJAX save failed, falling back to form submit:', err);
        // Fallback to native form submission
        form.submit();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Initial checkbox calculation
    updateParentCheckboxes();

    // 2. Role Tabs Click Event
    const roleTabs = document.querySelectorAll('.role-tab');
    roleTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            roleTabs.forEach(t => {
                t.className = 'btn btn-sm btn-light border text-secondary role-tab fw-semibold px-3 py-1';
            });
            this.className = 'btn btn-sm btn-primary role-tab fw-semibold px-3 py-1';

            const roleId = this.dataset.roleId;
            const roleName = this.dataset.roleName;
            selectRole(roleId, roleName);
        });
    });

    // 3. Mark All Toggle
    const markAllCb = document.getElementById('markAll');
    if (markAllCb) {
        markAllCb.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.perm-item-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            document.querySelectorAll('.check-group-parent').forEach(pcb => {
                pcb.checked = isChecked;
                pcb.indeterminate = false;
            });
        });
    }

    // 4. Parent Category Checkboxes Toggle
    document.querySelectorAll('.check-group-parent').forEach(parentCb => {
        parentCb.addEventListener('change', function() {
            const grpClass = this.dataset.target;
            const isChecked = this.checked;
            document.querySelectorAll('.' + grpClass + ' .perm-item-checkbox').forEach(childCb => {
                childCb.checked = isChecked;
            });
            updateParentCheckboxes();
        });
    });

    // 5. Individual Child Checkbox Change
    document.querySelectorAll('.perm-item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateParentCheckboxes);
    });

    // 6. Filter / Search Real-time
    const filterInput = document.getElementById('filterAccessInput');
    if (filterInput) {
        filterInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.perm-label').forEach(lbl => {
                const txt = lbl.textContent.toLowerCase();
                lbl.style.setProperty('display', (!q || txt.includes(q)) ? 'flex' : 'none', 'important');
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
