<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Profil Saya - Metafora ERP<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .profile-avatar-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    .profile-avatar-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    }
    .profile-avatar-initial {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .profile-avatar-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--accent-green, #2D6A4F);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    .profile-avatar-badge:hover, .profile-avatar-badge:active {
        transform: scale(1.1);
        background-color: #1b4332;
    }
    .profile-tab-btn {
        flex: 1 1 0;
        min-width: 0;
        padding: 0.75rem 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
        color: #64748b;
        border: none;
        background: transparent;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-align: center;
        white-space: nowrap;
    }
    .profile-tab-btn.active {
        color: var(--accent-green, #2D6A4F) !important;
        border-bottom: 2px solid var(--accent-green, #2D6A4F) !important;
        background: transparent;
    }
    .profile-tab-btn:hover {
        color: #1e293b;
    }
    .activity-timeline {
        position: relative;
        padding-left: 1.25rem;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background-color: #e2e8f0;
    }
    .activity-item {
        position: relative;
        margin-bottom: 0.85rem;
    }
    .activity-item:last-child {
        margin-bottom: 0;
    }
    .activity-dot {
        position: absolute;
        left: -1.25rem;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px #cbd5e1;
        background-color: #94a3b8;
    }
    .activity-dot.dot-primary { background-color: #3b82f6; box-shadow: 0 0 0 2px #bfdbfe; }
    .activity-dot.dot-success { background-color: #10b981; box-shadow: 0 0 0 2px #bbf7d0; }
    .activity-dot.dot-warning { background-color: #f59e0b; box-shadow: 0 0 0 2px #fde68a; }
    .activity-dot.dot-info    { background-color: #06b6d4; box-shadow: 0 0 0 2px #a5f3fc; }

    @media (min-width: 768px) {
        .profile-avatar-container {
            width: 115px;
            height: 115px;
        }
        .profile-avatar-img, .profile-avatar-initial {
            width: 115px;
            height: 115px;
            font-size: 2.6rem;
            border-width: 4px;
        }
        .profile-avatar-badge {
            width: 36px;
            height: 36px;
        }
        .profile-tab-btn {
            flex: 0 0 auto;
            padding: 0.85rem 1.25rem;
            font-size: 0.88rem;
        }
    }
</style>

<!-- TOP BANNER PROFIL PENGGUNA -->
<div class="card border-0 text-white mb-3 mb-md-4 shadow-sm" style="background: linear-gradient(135deg, #1A2E23 0%, #2D6A4F 100%); border-radius: 14px;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5 gap-md-3">
            <div>
                <div class="small text-white-50"><i class="fa-solid fa-user me-1"></i>Pengaturan Akun &gt; Profil Saya</div>
                <h3 class="fw-bold text-white mb-1 fs-5 fs-md-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-user"></i>
                    <span>Profil Pengguna</span>
                </h3>
                <p class="text-white-50 small mb-0 d-none d-sm-block">Kelola identitas personal, informasi kontak, dan keamanan kata sandi akun Anda.</p>
            </div>
            <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-between justify-content-sm-end pt-1 pt-sm-0 border-top border-white border-opacity-10 border-top-sm-0">
                <span class="badge bg-white text-success fw-bold px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-shield-halved me-1"></i> <?= esc($user['role_display_name'] ?? 'Super Admin') ?>
                </span>
                <span class="badge bg-success bg-opacity-25 text-white border border-white border-opacity-25 rounded-pill px-2.5 py-1 small d-sm-none">
                    &#9679; <?= ucfirst(esc($user['status'] ?? 'active')) ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- FLASH ALERTS -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-3 mb-md-4" role="alert" style="border-radius: 10px;">
        <i class="fa-solid fa-circle-check fs-5 text-success flex-shrink-0"></i>
        <div class="flex-grow-1 small"><?= session()->getFlashdata('success') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-3 mb-md-4" role="alert" style="border-radius: 10px;">
        <i class="fa-solid fa-triangle-exclamation fs-5 text-danger flex-shrink-0"></i>
        <div class="flex-grow-1 small"><?= session()->getFlashdata('error') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-3 g-md-4 mb-4">
    <!-- KOLOM KIRI: IDENTITY CARD (DI DESKTOP: TERMASUK ACTIVITY LOG) -->
    <div class="col-12 col-lg-4">
        <!-- Kartu Identitas Profil -->
        <div class="card border-0 shadow-sm text-center p-3 p-md-4 mb-3" style="border-radius: 14px;">
            <div class="profile-avatar-container mb-2.5 mb-md-3">
                <?php if (!empty($user['avatar']) && file_exists(FCPATH . 'uploads/avatars/' . $user['avatar'])): ?>
                    <img src="<?= base_url('uploads/avatars/' . esc($user['avatar'])) ?>" alt="<?= esc($user['username']) ?>" class="profile-avatar-img">
                <?php else: ?>
                    <div class="profile-avatar-initial">
                        <?= strtoupper(substr($user['username'] ?? 'A', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <label for="avatarInput" class="profile-avatar-badge" title="Ganti Foto Profil">
                    <i class="fa-solid fa-camera" style="font-size: 0.8rem;"></i>
                </label>
            </div>

            <!-- Form Hidden Upload Foto -->
            <form action="<?= base_url('profile/upload-avatar') ?>" method="POST" enctype="multipart/form-data" id="formUploadAvatar" class="d-none">
                <?= csrf_field() ?>
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp" onchange="document.getElementById('formUploadAvatar').submit();">
            </form>

            <h5 class="fw-bold text-dark mb-0 fs-5"><?= esc($user['username']) ?></h5>
            <div class="text-muted small mb-2 text-truncate"><?= esc($user['email']) ?></div>

            <div class="d-flex justify-content-center align-items-center gap-1.5 flex-wrap mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="font-size: 0.74rem;">
                    <i class="fa-solid fa-id-badge me-1"></i><?= esc($user['role_display_name'] ?? 'Super Admin') ?>
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.74rem;">
                    &#9679; <?= ucfirst(esc($user['status'] ?? 'active')) ?>
                </span>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-2 pt-2 border-top w-100">
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 flex-grow-1 flex-sm-grow-0 fw-semibold" onclick="document.getElementById('avatarInput').click();">
                    <i class="fa-solid fa-camera me-1"></i> Ganti Foto
                </button>
                <?php if (!empty($user['avatar'])): ?>
                    <form action="<?= base_url('profile/delete-avatar') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?');" class="m-0">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 fw-semibold" title="Hapus Foto">
                            <i class="fa-solid fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Metadata Akun -->
            <div class="mt-3 text-start bg-light bg-opacity-50 p-2.5 p-sm-3 rounded-3 border small">
                <div class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                    <span class="text-muted">User ID</span>
                    <span class="font-monospace fw-semibold text-dark">#<?= esc($user['id']) ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                    <span class="text-muted">No. WhatsApp</span>
                    <span class="font-monospace fw-semibold text-dark"><?= esc($user['phone'] ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                    <span class="text-muted">Bergabung Sejak</span>
                    <span class="text-dark"><?= !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' ?></span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Terakhir Update</span>
                    <span class="text-dark"><?= !empty($user['updated_at']) ? date('d/m/y H:i', strtotime($user['updated_at'])) : '-' ?></span>
                </div>
            </div>
        </div>

        <!-- Kartu Log Aktivitas Terakhir (DESKTOP VIEW: d-none d-lg-block) -->
        <div class="card border-0 shadow-sm p-3.5 d-none d-lg-block" style="border-radius: 14px;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0 fs-6">
                    <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Aktivitas Terakhir
                </h6>
                <span class="badge bg-light text-muted border" style="font-size: 0.72rem;"><?= count($activities) ?> Catatan</span>
            </div>

            <?php if (empty($activities)): ?>
                <div class="text-center py-4 text-muted small">
                    <i class="fa-solid fa-inbox text-muted opacity-50 fs-2 d-block mb-1"></i>
                    Belum ada riwayat aktivitas terbaru.
                </div>
            <?php else: ?>
                <div class="activity-timeline">
                    <?php foreach ($activities as $act): ?>
                        <?php 
                            $actAction = strtolower((string)$act['action']);
                            $dotClass = 'dot-primary';
                            if (str_contains($actAction, 'login') || str_contains($actAction, 'create')) $dotClass = 'dot-success';
                            if (str_contains($actAction, 'password') || str_contains($actAction, 'delete')) $dotClass = 'dot-warning';
                            if (str_contains($actAction, 'profile') || str_contains($actAction, 'avatar')) $dotClass = 'dot-info';
                        ?>
                        <div class="activity-item">
                            <div class="activity-dot <?= $dotClass ?>"></div>
                            <div class="fw-semibold text-dark small text-truncate" title="<?= esc($act['action']) ?>">
                                <?= ucwords(str_replace('_', ' ', esc($act['action']))) ?>
                            </div>
                            <div class="text-muted font-monospace" style="font-size: 0.72rem;">
                                <?= date('d M Y, H:i', strtotime($act['created_at'])) ?> &bull; <span class="badge bg-light text-secondary border py-0"><?= esc($act['table_name'] ?? 'system') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- KOLOM KANAN: TABS EDIT PROFILE & CHANGE PASSWORD -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-3 mb-lg-0" style="border-radius: 14px;">
            <!-- Nav Tabs Header (Responsive Equal Split on Mobile) -->
            <div class="card-header bg-white border-bottom p-0 px-2 px-md-3">
                <?php $activeTab = session()->getFlashdata('active_tab') ?? 'profile'; ?>
                <div class="d-flex align-items-center">
                    <button type="button" class="profile-tab-btn <?= $activeTab === 'profile' ? 'active' : '' ?>" id="tabBtnProfile" onclick="switchProfileTab('profile')">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Informasi Pribadi</span>
                    </button>
                    <button type="button" class="profile-tab-btn <?= $activeTab === 'security' ? 'active' : '' ?>" id="tabBtnSecurity" onclick="switchProfileTab('security')">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Kata Sandi</span>
                    </button>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                <!-- TAB 1: EDIT INFORMASI PRIBADI -->
                <div id="tabContentProfile" class="<?= $activeTab === 'profile' ? '' : 'd-none' ?>">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1 fs-5">Informasi Akun &amp; Kontak</h5>
                        <p class="text-muted small mb-0">Perbarui data identitas username, email resmi, dan nomor WhatsApp Anda.</p>
                    </div>

                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger py-2 px-3 small border-0 mb-3" style="border-radius: 8px;">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('profile/update') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-2.5 g-md-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-at"></i></span>
                                    <input type="text" name="username" class="form-control" value="<?= old('username', $user['username'] ?? '') ?>" required autocomplete="username">
                                </div>
                                <div class="form-text small text-muted" style="font-size: 0.72rem;">Digunakan saat masuk ke sistem ERP.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>" required autocomplete="email">
                                </div>
                                <div class="form-text small text-muted" style="font-size: 0.72rem;">Menerima notifikasi dan pengumuman sistem.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Nomor WhatsApp / Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success"><i class="fa-brands fa-whatsapp"></i></span>
                                    <input type="tel" name="phone" class="form-control" placeholder="Contoh: 081234567890" value="<?= old('phone', $user['phone'] ?? '') ?>">
                                </div>
                                <div class="form-text small text-muted" style="font-size: 0.72rem;">Untuk koordinasi operasional &amp; CRM internal.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Role Akses Sistem</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user-shield"></i></span>
                                    <input type="text" class="form-control bg-light" value="<?= esc($user['role_display_name'] ?? 'Super Admin') ?>" readonly disabled>
                                </div>
                                <div class="form-text small text-muted" style="font-size: 0.72rem;">Tingkat izin hanya dapat diubah oleh Administrator.</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 shadow-xs w-100 w-sm-auto">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: KEAMANAN & GANTI KATA SANDI -->
                <div id="tabContentSecurity" class="<?= $activeTab === 'security' ? '' : 'd-none' ?>">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1 fs-5">Ganti Kata Sandi Akun</h5>
                        <p class="text-muted small mb-0">Pastikan kata sandi baru Anda kuat, unik, dan dirahasiakan.</p>
                    </div>

                    <?php if ($pErrors = session()->getFlashdata('password_errors')): ?>
                        <div class="alert alert-danger py-2 px-3 small border-0 mb-3" style="border-radius: 8px;">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($pErrors as $pErr): ?>
                                    <li><?= esc($pErr) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('profile/change-password') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark mb-1">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="current_password" id="currentPasswordInput" class="form-control" placeholder="Masukkan kata sandi saat ini" required autocomplete="current-password">
                                <button class="btn btn-outline-secondary px-3" type="button" onclick="togglePassVisibility('currentPasswordInput', this)" aria-label="Toggle password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row g-2.5 g-md-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Kata Sandi Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-key"></i></span>
                                    <input type="password" name="new_password" id="newPasswordInput" class="form-control" placeholder="Minimal 6 karakter" required autocomplete="new-password" oninput="checkPassStrength(this.value)">
                                    <button class="btn btn-outline-secondary px-3" type="button" onclick="togglePassVisibility('newPasswordInput', this)" aria-label="Toggle password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar" id="passStrengthBar" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <div class="small text-muted mt-1" id="passStrengthText" style="font-size: 0.72rem;">Kekuatan kata sandi: -</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold text-dark mb-1">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-check-double"></i></span>
                                    <input type="password" name="confirm_password" id="confirmPasswordInput" class="form-control" placeholder="Ulangi kata sandi baru" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary px-3" type="button" onclick="togglePassVisibility('confirmPasswordInput', this)" aria-label="Toggle password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Petunjuk Keamanan -->
                        <div class="p-3 rounded-3 bg-light bg-opacity-75 border small mb-4">
                            <h6 class="fw-bold text-dark mb-1 small"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Rekomendasi Keamanan:</h6>
                            <ul class="text-muted mb-0 ps-3" style="font-size: 0.76rem;">
                                <li>Panjang kata sandi minimal 6 karakter (disarankan 8 karakter atau lebih).</li>
                                <li>Kombinasikan huruf besar, huruf kecil, angka, dan karakter khusus (!@#$%^&*).</li>
                                <li>Hindari menggunakan tanggal lahir atau kata-kata umum yang mudah ditebak.</li>
                            </ul>
                        </div>

                        <div class="pt-3 border-top d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="submit" class="btn btn-warning text-dark px-4 py-2.5 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 shadow-xs w-100 w-sm-auto">
                                <i class="fa-solid fa-key"></i>
                                <span>Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kartu Log Aktivitas Terakhir (MOBILE VIEW: d-block d-lg-none) -->
        <div class="card border-0 shadow-sm p-3 d-block d-lg-none" style="border-radius: 14px;">
            <div class="d-flex align-items-center justify-content-between cursor-pointer" data-bs-toggle="collapse" data-bs-target="#mobileActivityCollapse" aria-expanded="false" role="button">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0 fs-6">Aktivitas Terakhir</h6>
                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem;"><?= count($activities) ?></span>
                </div>
                <i class="fa-solid fa-chevron-down text-muted" id="mobileActivityIcon"></i>
            </div>

            <div class="collapse mt-3" id="mobileActivityCollapse">
                <?php if (empty($activities)): ?>
                    <div class="text-center py-3 text-muted small">
                        <i class="fa-solid fa-inbox text-muted opacity-50 fs-2 d-block mb-1"></i>
                        Belum ada riwayat aktivitas terbaru.
                    </div>
                <?php else: ?>
                    <div class="activity-timeline pt-1">
                        <?php foreach ($activities as $act): ?>
                            <?php 
                                $actAction = strtolower((string)$act['action']);
                                $dotClass = 'dot-primary';
                                if (str_contains($actAction, 'login') || str_contains($actAction, 'create')) $dotClass = 'dot-success';
                                if (str_contains($actAction, 'password') || str_contains($actAction, 'delete')) $dotClass = 'dot-warning';
                                if (str_contains($actAction, 'profile') || str_contains($actAction, 'avatar')) $dotClass = 'dot-info';
                            ?>
                            <div class="activity-item">
                                <div class="activity-dot <?= $dotClass ?>"></div>
                                <div class="fw-semibold text-dark small text-truncate" title="<?= esc($act['action']) ?>">
                                    <?= ucwords(str_replace('_', ' ', esc($act['action']))) ?>
                                </div>
                                <div class="text-muted font-monospace" style="font-size: 0.72rem;">
                                    <?= date('d M Y, H:i', strtotime($act['created_at'])) ?> &bull; <span class="badge bg-light text-secondary border py-0"><?= esc($act['table_name'] ?? 'system') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function switchProfileTab(tab) {
        if (tab === 'profile') {
            $('#tabBtnProfile').addClass('active');
            $('#tabBtnSecurity').removeClass('active');
            $('#tabContentProfile').removeClass('d-none');
            $('#tabContentSecurity').addClass('d-none');
        } else {
            $('#tabBtnSecurity').addClass('active');
            $('#tabBtnProfile').removeClass('active');
            $('#tabContentSecurity').removeClass('d-none');
            $('#tabContentProfile').addClass('d-none');
        }
    }

    function togglePassVisibility(inputId, btn) {
        const inp = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            inp.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkPassStrength(val) {
        const bar = document.getElementById('passStrengthBar');
        const text = document.getElementById('passStrengthText');

        if (!val) {
            bar.style.width = '0%';
            bar.className = 'progress-bar';
            text.textContent = 'Kekuatan kata sandi: -';
            return;
        }

        let score = 0;
        if (val.length >= 6) score += 25;
        if (val.length >= 8) score += 25;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 25;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score += 25;

        bar.style.width = score + '%';
        if (score <= 25) {
            bar.className = 'progress-bar bg-danger';
            text.textContent = 'Kekuatan kata sandi: Lemah';
        } else if (score <= 50) {
            bar.className = 'progress-bar bg-warning';
            text.textContent = 'Kekuatan kata sandi: Cukup';
        } else if (score <= 75) {
            bar.className = 'progress-bar bg-info';
            text.textContent = 'Kekuatan kata sandi: Baik';
        } else {
            bar.className = 'progress-bar bg-success';
            text.textContent = 'Kekuatan kata sandi: Sangat Kuat';
        }
    }

    // Toggle icon rotation on mobile activity collapse
    $('#mobileActivityCollapse').on('show.bs.collapse', function () {
        $('#mobileActivityIcon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }).on('hide.bs.collapse', function () {
        $('#mobileActivityIcon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
</script>
<?= $this->endSection() ?>
