<!-- MODAL IMPORT RFM EXCEL -->
<div class="modal fade" id="modalImportRfmExcel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-file-excel me-2 text-success"></i>Sinkronisasi File Excel / Transaksi RFM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportRfmExcelShared" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="text-muted small">Unggah berkas spreadsheet transaksi penjualan marketplace (Shopee, TikTok, Tokopedia) atau template RFM (<code>.xlsx</code> / <code>.csv</code>) untuk menyelaraskan data transaksi dan segmentasi pelanggan.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih File Spreadsheet (.xlsx / .csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" id="fileRfmExcelShared" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="p-3 bg-light rounded small text-muted">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>Sistem akan otomatis mengimpor pesanan, mendaftarkan pelanggan baru, dan menghitung ulang nilai Recency, Frequency, Monetary (RFM).
                    </div>
                    <div id="rfmImportAlertShared" class="mt-3" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" id="btnSyncRfmSubmitShared">
                        <i class="fa-solid fa-rotate me-1"></i>Proses Sinkronisasi Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('formImportRfmExcelShared');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var fileInput = document.getElementById('fileRfmExcelShared');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Silakan pilih file spreadsheet (.xlsx / .csv) terlebih dahulu.');
                return;
            }

            var btn = document.getElementById('btnSyncRfmSubmitShared');
            var originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyinkronkan...';
            btn.disabled = true;

            var alertBox = document.getElementById('rfmImportAlertShared');
            alertBox.style.display = 'none';
            alertBox.className = 'mt-3';
            alertBox.innerHTML = '';

            var formData = new FormData(form);

            fetch('<?= base_url('rfm/import-excel') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) {
                return res.json().then(function(data) {
                    return { ok: res.ok, data: data };
                });
            })
            .then(function(result) {
                if (result.ok && result.data.status === 'success') {
                    btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>Berhasil!';
                    alertBox.className = 'mt-3 alert alert-success small mb-0';
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i>' + result.data.message;
                    alertBox.style.display = 'block';
                    setTimeout(function() {
                        window.location.reload();
                    }, 1400);
                } else {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    alertBox.className = 'mt-3 alert alert-danger small mb-0';
                    alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i>' + (result.data.message || 'Gagal memproses file.');
                    alertBox.style.display = 'block';
                }
            })
            .catch(function(err) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                alertBox.className = 'mt-3 alert alert-danger small mb-0';
                alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i>Terjadi kesalahan komunikasi dengan server.';
                alertBox.style.display = 'block';
            });
        });
    }
});
</script>

