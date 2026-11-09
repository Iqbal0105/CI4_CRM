<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PURCHASE ORDER - <?= esc($po['nomor_po']) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 24px;
        }
        .header-table, .content-table, .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #1A2E23;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .border-bottom { border-bottom: 2px solid #1A2E23; }
        .content-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            padding: 8px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            font-size: 11px;
        }
        .content-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-row td {
            font-weight: bold;
            border-top: 2px solid #cbd5e1;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-received { background-color: #dcfce7; color: #16a34a; border: 1px solid #86efac; }
        .badge-approved { background-color: #dbeafe; color: #2D6A4F; border: 1px solid #93c5fd; }
        .badge-partial { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }

        /* Sembunyikan CodeIgniter Debugbar */
        #debug-icon,
        #debug-bar,
        .toolbar,
        [id^="ci-debugbar"],
        #ci-history {
            display: none !important;
        }

        @page {
            size: portrait;
            margin: 0;
        }
        @media print {
            .no-print,
            #debug-icon,
            #debug-bar,
            .toolbar,
            [id^="ci-debugbar"],
            #ci-history {
                display: none !important;
            }
            a[href]:after {
                content: none !important;
            }
            body {
                padding: 12mm 15mm !important;
                background: #fff !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background-color: #1A2E23; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Dokumen PO
        </button>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="header-title">IDMETAFORA ENTERPRISE</div>
                <div style="color: #64748b; font-size: 11px; margin-top: 4px;">
                    Jl. Ringroad Utara, Sleman, D.I. Yogyakarta<br>
                    Email: purchasing@idmetafora.com | Telp: (0274) 8899-7700
                </div>
            </td>
            <td style="width: 45%; vertical-align: top; text-align: right;">
                <h2 style="margin: 0; color: #1e293b; font-size: 22px;">PURCHASE ORDER</h2>
                <div style="font-weight: bold; font-size: 14px; color: #dc2626; margin-top: 4px; font-family: monospace;">
                    #<?= esc($po['nomor_po']) ?>
                </div>
                <div style="margin-top: 4px; font-size: 11px;">
                    Status: <span class="badge badge-<?= esc($po['status']) ?>"><?= strtoupper(esc($po['status'])) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 2px solid #e2e8f0; margin: 15px 0;">

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase;">VENDOR / SUPPLIER:</div>
                <div style="font-weight: bold; font-size: 14px; margin-top: 3px; color: #0f172a;"><?= esc($po['nama_supplier']) ?></div>
                <div style="font-size: 11px; color: #475569; margin-top: 2px;">
                    Kode: <?= esc($po['kode_supplier'] ?? '-') ?><br>
                    Telp: <?= esc($po['supplier_telp'] ?? '-') ?><br>
                    Alamat: <?= esc($po['supplier_alamat'] ?? '-') ?>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right;">
                <table style="margin-left: auto; font-size: 11px;">
                    <tr>
                        <td style="color: #64748b; text-align: right; padding-right: 10px;">Tanggal PO:</td>
                        <td style="font-weight: bold;"><?= date('d/m/Y', strtotime($po['tanggal_po'])) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; text-align: right; padding-right: 10px;">Termin:</td>
                        <td style="font-weight: bold; text-transform: capitalize;"><?= esc($po['termin']) ?></td>
                    </tr>
                    <?php if ($po['termin'] === 'tempo'): ?>
                    <tr>
                        <td style="color: #64748b; text-align: right; padding-right: 10px;">Jatuh Tempo:</td>
                        <td style="font-weight: bold; color: #dc2626;"><?= date('d/m/Y', strtotime($po['jatuh_tempo'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="color: #64748b; text-align: right; padding-right: 10px;">Mata Uang:</td>
                        <td style="font-weight: bold; font-family: monospace;"><?= esc($po['mata_uang']) ?></td>
                    </tr>
                    <?php if ($po['mata_uang'] !== 'IDR'): ?>
                    <tr>
                        <td style="color: #64748b; text-align: right; padding-right: 10px;">Nilai Kurs:</td>
                        <td style="font-weight: bold; font-family: monospace;">Rp <?= number_format((float) $po['nilai_kurs'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <table class="content-table" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">NO</th>
                <th>KODE & NAMA BARANG</th>
                <th class="text-center" style="width: 90px;">QTY PESAN</th>
                <th class="text-center" style="width: 90px;">QTY DITERIMA</th>
                <th class="text-end" style="width: 130px;">HARGA SATUAN</th>
                <th class="text-end" style="width: 140px;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($items as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;"><?= esc($item['nama_produk']) ?></div>
                        <div style="font-size: 10px; color: #64748b; font-family: monospace;"><?= esc($item['kode_produk']) ?></div>
                    </td>
                    <td class="text-center font-monospace"><?= number_format((float) $item['qty'], 0, ',', '.') ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?></td>
                    <td class="text-center font-monospace"><?= number_format((float) $item['qty_diterima'], 0, ',', '.') ?></td>
                    <td class="text-end font-monospace"><?= format_valas($item['harga_satuan'], $po['mata_uang']) ?></td>
                    <td class="text-end font-monospace" style="font-weight: bold;"><?= format_valas($item['subtotal'], $po['mata_uang']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <?php if ($po['mata_uang'] !== 'IDR'): ?>
            <tr class="total-row">
                <td colspan="5" class="text-end" style="padding-top: 8px; color: #64748b;">TOTAL VALAS (<?= esc($po['mata_uang']) ?>):</td>
                <td class="text-end font-monospace" style="padding-top: 8px; font-size: 13px; color: #0f172a;">
                    <?= format_valas($po['total'], $po['mata_uang']) ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="5" class="text-end" style="padding-top: 10px;">TOTAL PURCHASE ORDER (IDR):</td>
                <td class="text-end font-monospace" style="padding-top: 10px; font-size: 14px; color: #1A2E23;">
                    <?= format_rupiah($po['total_idr']) ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php if (!empty($po['catatan'])): ?>
        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; margin-bottom: 25px; font-size: 11px;">
            <strong>Catatan Pengiriman / Instruksi Khusus:</strong><br>
            <?= nl2br(esc($po['catatan'])) ?>
        </div>
    <?php endif; ?>

    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: bottom;">
                <div style="margin-bottom: 60px; font-size: 11px; color: #64748b;">Dipesan Oleh (Purchasing):</div>
                <div style="font-weight: bold; text-decoration: underline;">Developer</div>
                <div style="font-size: 10px; color: #94a3b8;">Staff Purchasing</div>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: bottom;">
                <div style="margin-bottom: 60px; font-size: 11px; color: #64748b;">Disetujui Oleh:</div>
                <div style="font-weight: bold; text-decoration: underline;">Manager Operasional</div>
                <div style="font-size: 10px; color: #94a3b8;">IDMETAFORA Enterprise</div>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: bottom;">
                <div style="margin-bottom: 60px; font-size: 11px; color: #64748b;">Dikonfirmasi Supplier:</div>
                <div style="font-weight: bold; text-decoration: underline;"><?= esc($po['nama_supplier']) ?></div>
                <div style="font-size: 10px; color: #94a3b8;">Vendor / Pihak Kedua</div>
            </td>
        </tr>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const debugIcon = document.getElementById("debug-icon");
            if (debugIcon) debugIcon.remove();
            const debugBar = document.getElementById("debug-bar");
            if (debugBar) debugBar.remove();
        });
    </script>
</body>
</html>

