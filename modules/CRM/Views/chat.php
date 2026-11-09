<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">2-Way Chat Logging & Layanan CS WhatsApp</h3>
        <p class="text-muted small mb-0">Riwayat percakapan pelanggan dan pengelompokan label status CS (New, Follow-up, Closed, Repeat Order).</p>
    </div>
    <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
        <a href="<?= base_url('crm') ?>" class="btn btn-outline-secondary flex-fill flex-sm-grow-0 text-center">
            <i class="fa-solid fa-arrow-left me-1"></i> Antrean Broadcast
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- FORM BALAS CEPAT -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-paper-plane text-success me-2"></i>Kirim Balasan / Catat Chat
            </div>
            <div class="card-body">
                <form action="<?= base_url('crm/chat/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor WhatsApp Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control font-monospace" placeholder="08xxxxxxxxxx / 628xxxxxxxxxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label Status Tiket</label>
                        <select name="label" class="form-select">
                            <option value="New">New (Percakapan Baru)</option>
                            <option value="Follow-up" selected>Follow-up (Sedang Ditangani)</option>
                            <option value="Repeat Order">Repeat Order (Pemesanan Ulang)</option>
                            <option value="Closed">Closed (Selesai)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pesan WhatsApp / Catatan CS <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control" rows="4" placeholder="Ketik balasan untuk pelanggan..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        <i class="fa-brands fa-whatsapp me-2"></i>Kirim Pesan ke Pelanggan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- LOG CHAT HISTORY -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
                <span class="fw-bold"><i class="fa-solid fa-comments text-primary me-2"></i>Riwayat Percakapan Pelanggan</span>
                <div class="d-flex flex-wrap gap-1">
                    <a href="<?= base_url('crm/chat') ?>" class="btn btn-sm <?= empty($label) ? 'btn-dark' : 'btn-outline-secondary' ?>">Semua</a>
                    <a href="<?= base_url('crm/chat?label=New') ?>" class="btn btn-sm <?= $label === 'New' ? 'btn-primary' : 'btn-outline-primary' ?>">New</a>
                    <a href="<?= base_url('crm/chat?label=Follow-up') ?>" class="btn btn-sm <?= $label === 'Follow-up' ? 'btn-warning' : 'btn-outline-warning text-dark' ?>">Follow-up</a>
                    <a href="<?= base_url('crm/chat?label=Repeat Order') ?>" class="btn btn-sm <?= $label === 'Repeat Order' ? 'btn-success' : 'btn-outline-success' ?>">Repeat Order</a>
                    <a href="<?= base_url('crm/chat?label=Closed') ?>" class="btn btn-sm <?= $label === 'Closed' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Closed</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 140px;">Waktu</th>
                                <th style="width: 160px;">Pelanggan / Nomor</th>
                                <th>Arah & Pesan</th>
                                <th class="text-center" style="width: 130px;">Status Label</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($notes)): ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada riwayat pesan atau catatan chat.</td></tr>
                            <?php else: ?>
                                <?php foreach ($notes as $note): ?>
                                    <tr>
                                        <td class="small text-muted font-monospace"><?= format_datetime($note['created_at']) ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?= esc($note['nama_pelanggan'] ?? 'Pelanggan') ?></div>
                                            <div class="small font-monospace text-success"><i class="fa-brands fa-whatsapp me-1"></i><?= esc($note['customer_phone'] ?? '-') ?></div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <?php if ($note['direction'] === 'inbound'): ?>
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="fa-solid fa-arrow-down me-1"></i>Masuk (Inbound)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-arrow-up me-1"></i>Keluar (CS: <?= esc($note['cs_nama'] ?? 'Staff') ?>)</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-dark"><?= nl2br(esc($note['catatan'])) ?></div>
                                        </td>
                                        <td class="text-center">
                                            <form action="<?= base_url('crm/chat/update-label/' . $note['id']) ?>" method="POST">
                                                <?= csrf_field() ?>
                                                <select name="label" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="New" <?= $note['label'] === 'New' ? 'selected' : '' ?>>New</option>
                                                    <option value="Follow-up" <?= $note['label'] === 'Follow-up' ? 'selected' : '' ?>>Follow-up</option>
                                                    <option value="Repeat Order" <?= $note['label'] === 'Repeat Order' ? 'selected' : '' ?>>Repeat Order</option>
                                                    <option value="Closed" <?= $note['label'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

