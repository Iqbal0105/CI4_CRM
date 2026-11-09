<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- TITLE & BREADCRUMBS -->
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0 d-inline-block">API Keys Management</h4>
        <span class="text-muted small ms-2 d-none d-md-inline">Kelola Token Akses &amp; Webhook Pihak Ketiga</span>
    </div>
    <div class="small text-muted">
        <span class="text-secondary"><i class="fa-solid fa-house me-1"></i>Beranda</span>
        <span class="mx-1">&gt;</span>
        <span class="text-secondary">Pengaturan &amp; Akses</span>
        <span class="mx-1">&gt;</span>
        <span class="text-dark fw-medium">API Keys</span>
    </div>
</div>

<div class="card shadow-sm border mb-4">
    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 py-3">
        <div class="fw-bold text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-key text-primary"></i>
            <span>Daftar Kunci API Terdaftar</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGenerateApiKey">
            <i class="fa-solid fa-plus"></i>
            <span>Generate New API Key</span>
        </button>
    </div>

    <!-- FILTER BAR -->
    <div class="card-body border-bottom bg-light bg-opacity-50 py-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" id="searchApiKeyInput" class="form-control" placeholder="Cari nama aplikasi atau token key...">
                    <button class="btn btn-primary" type="button" id="btnSearchApiKey"><i class="fa-solid fa-magnifying-glass me-1"></i>Cari</button>
                    <button class="btn btn-outline-secondary" type="button" id="btnResetApiKey" title="Reset"><i class="fa-solid fa-rotate-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableApiKeys">
                <thead class="table-light small fw-bold text-muted">
                    <tr>
                        <th class="ps-3 py-3">NAMA APLIKASI / INTEGRASI</th>
                        <th>API KEY TOKEN</th>
                        <th>HAK AKSES</th>
                        <th>CREATED AT</th>
                        <th class="text-center">STATUS</th>
                        <th class="pe-3 text-end" style="width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody id="apiKeysTableBody">
                    <tr id="emptyApiKeyRow">
                        <td colspan="6" class="text-center text-muted py-4 small">
                            <i class="fa-solid fa-key text-muted fs-4 d-block mb-2"></i>
                            Belum ada API Key yang dibuat. Klik tombol <strong>Generate New API Key</strong> di atas untuk membuat kunci integrasi baru.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL GENERATE API KEY -->
<div class="modal fade" id="modalGenerateApiKey" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="fa-solid fa-key me-2 text-primary"></i>Generate New API Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Aplikasi / Integrasi <span class="text-danger">*</span></label>
                    <input type="text" id="newAppTitle" class="form-control form-control-sm" placeholder="Contoh: Mobile Apps Kurir Logistik" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Hak Akses Scope <span class="text-danger">*</span></label>
                    <select id="newAppScope" class="form-select form-select-sm">
                        <option value="Read / Write">Read / Write (Penuh)</option>
                        <option value="Read Only">Read Only (Hanya Baca)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Generated Secret Token</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="newGeneratedToken" class="form-control font-monospace text-dark bg-light" value="metafora_sec_<?= bin2hex(random_bytes(9)) ?>" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="btnRegenToken" title="Generate Ulang"><i class="fa-solid fa-rotate"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm fw-semibold" id="btnSubmitNewApiKey">
                    <i class="fa-solid fa-check me-1"></i>Simpan &amp; Aktifkan Key
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const table = $('#tableApiKeys').DataTable({
            dom: 'rtip',
            pageLength: 10,
            language: {
                paginate: { previous: "Prev", next: "Next" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });

        $('#btnSearchApiKey').on('click', function() {
            table.search($('#searchApiKeyInput').val()).draw();
        });

        $('#searchApiKeyInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.search(this.value).draw();
            }
        });

        $('#btnResetApiKey').on('click', function() {
            $('#searchApiKeyInput').val('');
            table.search('').draw();
        });

        $('#btnRegenToken').on('click', function() {
            const rand = Math.random().toString(36).substring(2, 10) + Math.random().toString(36).substring(2, 10);
            $('#newGeneratedToken').val('metafora_sec_' + rand);
        });

        $('#btnSubmitNewApiKey').on('click', function() {
            const app = $('#newAppTitle').val().trim();
            const scope = $('#newAppScope').val();
            const token = $('#newGeneratedToken').val();

            if (!app) {
                alert('Nama aplikasi / integrasi wajib diisi.');
                return;
            }

            const badgeScope = scope.includes('Write') ? 'bg-success bg-opacity-10 text-success' : 'bg-info bg-opacity-10 text-info';
            const today = new Date().toISOString().slice(0, 16).replace('T', ' ');

            const newRow = `
                <tr>
                    <td class="ps-3 fw-semibold text-dark"><i class="fa-solid fa-cube text-primary me-2"></i>${app}</td>
                    <td><code class="bg-light p-1 rounded font-monospace text-dark">${token}</code></td>
                    <td><span class="badge ${badgeScope}">${scope}</span></td>
                    <td class="text-muted small">${today}</td>
                    <td class="text-center"><span class="badge bg-success rounded-pill px-2 py-1 key-status-badge" style="font-size: 0.78rem;">ACTIVE</span></td>
                    <td class="pe-3 text-end"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 small btn-revoke-key">Revoke</button></td>
                </tr>
            `;

            $('#emptyApiKeyRow').remove();
            $('#apiKeysTableBody').prepend(newRow);
            bootstrap.Modal.getInstance(document.getElementById('modalGenerateApiKey')).hide();
            $('#newAppTitle').val('');
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'API Key baru berhasil di-generate dan siap digunakan!' });
        });

        $(document).on('click', '.btn-revoke-key', function() {
            const btn = $(this);
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Revoke kunci API ini? Aplikasi terkait tidak akan dapat mengakses endpoint lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-ban me-1"></i> Revoke',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((r) => {
                if (r.isConfirmed) {
                    const tr = btn.closest('tr');
                    tr.find('.key-status-badge').removeClass('bg-success').addClass('bg-secondary').text('REVOKED');
                    btn.prop('disabled', true).text('Revoked');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

