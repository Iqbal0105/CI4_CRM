<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FAKTUR PENJUALAN - <?= esc($order['nomor_invoice']) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
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
        .badge-unpaid { background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; }
        .badge-paid { background-color: #dcfce7; color: #16a34a; border: 1px solid #86efac; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="header-title">METAFORA ENTERPRISE</div>
                <div style="color: #64748b; font-size: 11px; margin-top: 4px;">
                    Jl. Raya Bisnis Terpadu No. 12, Jawa Timur<br>
                    Email: finance@metafora.local | Telp: (031) 8899-7700
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;" class="text-end">
                <div style="font-size: 16px; font-weight: bold; color: #0f172a;">FAKTUR PENJUALAN (INVOICE)</div>
                <div style="font-family: monospace; font-size: 13px; font-weight: bold; color: #2D6A4F; margin: 4px 0;">
                    <?= esc($order['nomor_invoice']) ?>
                </div>
                <div style="font-size: 11px;">
                    Tanggal Invoice: <?= format_tanggal($order['tanggal_invoice'] ?? $order['tanggal_so']) ?><br>
                    Jatuh Tempo: <span style="color: #dc2626; font-weight: bold;"><?= format_tanggal($order['jatuh_tempo']) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <div style="height: 15px; border-bottom: 2px solid #1A2E23; margin-bottom: 15px;"></div>

    <table class="header-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-weight: bold; color: #64748b; font-size: 10px; text-transform: uppercase;">Ditagihkan Kepada:</div>
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px;"><?= esc($order['nama_pelanggan']) ?></div>
                <div style="color: #475569; margin-top: 2px;">
                    <?= nl2br(esc($order['alamat'] ?? 'Alamat tidak tercatat')) ?><br>
                    Kota: <?= esc($order['kota'] ?? '-') ?><br>
                    Telp: <?= esc($order['no_telp']) ?>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;" class="text-end">
                <div style="font-weight: bold; color: #64748b; font-size: 10px; text-transform: uppercase;">Status Pembayaran:</div>
                <div style="margin-top: 5px;">
                    <span class="badge <?= ($order['invoice_status'] ?? '') === 'paid' ? 'badge-paid' : 'badge-unpaid' ?>">
                        <?= strtoupper(esc($order['invoice_status'] ?? 'UNPAID')) ?>
                    </span>
                </div>
                <div style="margin-top: 6px; font-size: 11px; color: #475569;">
                    No. Sales Order: <strong style="font-family: monospace;"><?= esc($order['nomor_so']) ?></strong><br>
                    Metode Bayar: <span style="text-transform: capitalize;"><?= esc($order['metode_bayar']) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Deskripsi Produk</th>
                <th style="width: 15%;" class="text-center">Kuantitas</th>
                <th style="width: 17%;" class="text-end">Harga Satuan</th>
                <th style="width: 18%;" class="text-end">Total (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($items as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <strong style="color: #0f172a;"><?= esc($item['nama_produk']) ?></strong><br>
                        <span style="font-family: monospace; font-size: 10px; color: #64748b;">SKU: <?= esc($item['kode_produk']) ?></span>
                    </td>
                    <td class="text-center" style="font-family: monospace;">
                        <?= number_format((float) $item['qty'], 0) ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?>
                    </td>
                    <td class="text-end" style="font-family: monospace;"><?= format_rupiah($item['harga_satuan']) ?></td>
                    <td class="text-end" style="font-family: monospace; font-weight: bold;"><?= format_rupiah($item['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end">Subtotal:</td>
                <td class="text-end" style="font-family: monospace;"><?= format_rupiah($order['subtotal']) ?></td>
            </tr>
            <?php if ((float) $order['diskon_total'] > 0): ?>
                <tr>
                    <td colspan="4" class="text-end">Diskon Total:</td>
                    <td class="text-end" style="font-family: monospace; color: #dc2626;">-<?= format_rupiah($order['diskon_total']) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float) $order['ppn'] > 0): ?>
                <tr>
                    <td colspan="4" class="text-end">Pajak PPN:</td>
                    <td class="text-end" style="font-family: monospace;"><?= format_rupiah($order['ppn']) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float) $order['ongkir'] > 0): ?>
                <tr>
                    <td colspan="4" class="text-end">Ongkos Kirim:</td>
                    <td class="text-end" style="font-family: monospace;"><?= format_rupiah($order['ongkir']) ?></td>
                </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="4" class="text-end" style="font-size: 14px; color: #1A2E23;">TOTAL TAGIHAN:</td>
                <td class="text-end" style="font-family: monospace; font-size: 14px; color: #1A2E23;"><?= format_rupiah($order['total']) ?></td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-table" style="margin-top: 40px;">
        <tr>
            <td style="width: 60%; vertical-align: top; font-size: 11px;">
                <div style="font-weight: bold; margin-bottom: 4px;">Instruksi Pembayaran Bank:</div>
                <div>Bank BCA Operasional: <strong>8012938102</strong></div>
                <div>Atas Nama: <strong>PT Metafora Teknologi Indonesia</strong></div>
                <div style="color: #64748b; margin-top: 4px;">Harap cantumkan nomor invoice pada berita transfer.</div>
            </td>
            <td style="width: 40%; text-align: center; vertical-align: top;">
                <div>Hormat kami,</div>
                <div style="font-weight: bold; margin-top: 2px;">PT Metafora Enterprise</div>
                <div style="height: 50px;"></div>
                <div style="border-top: 1px solid #94a3b8; width: 80%; margin: 0 auto; font-weight: bold;">( Bagian Keuangan )</div>
            </td>
        </tr>
    </table>
</body>
</html>

