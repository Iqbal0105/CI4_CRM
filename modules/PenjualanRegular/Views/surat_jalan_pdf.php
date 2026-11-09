<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SURAT JALAN - <?= esc($order['nomor_so']) ?></title>
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
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
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
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="header-title">METAFORA LOGISTIK & DISTRIBUSI</div>
                <div style="color: #64748b; font-size: 11px; margin-top: 4px;">
                    Jl. Raya Bisnis Terpadu No. 12, Area Distribusi Blok B<br>
                    Telp Operasional: (031) 8899-7701
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;" class="text-end">
                <div style="font-size: 16px; font-weight: bold; color: #1A2E23;">SURAT JALAN PENGIRIMAN</div>
                <div style="font-family: monospace; font-size: 13px; font-weight: bold; color: #0f172a; margin: 4px 0;">
                    DO-<?= esc($order['nomor_so']) ?>
                </div>
                <div style="font-size: 11px;">
                    Tanggal Kirim: <?= format_tanggal($order['tanggal_so']) ?><br>
                    Ref SO: <span style="font-family: monospace; font-weight: bold;"><?= esc($order['nomor_so']) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <div style="height: 15px; border-bottom: 2px solid #0f172a; margin-bottom: 15px;"></div>

    <table class="header-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 100%; vertical-align: top;">
                <div style="font-weight: bold; color: #64748b; font-size: 10px; text-transform: uppercase;">Alamat Penerima / Tujuan:</div>
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px;"><?= esc($order['nama_pelanggan']) ?></div>
                <div style="color: #475569; margin-top: 2px;">
                    <?= nl2br(esc($order['alamat'] ?? 'Alamat tidak tercatat')) ?><br>
                    Kota: <?= esc($order['kota'] ?? '-') ?> | Telp: <?= esc($order['no_telp']) ?>
                </div>
            </td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">No</th>
                <th style="width: 25%;">Kode SKU</th>
                <th style="width: 47%;">Nama Produk & Deskripsi</th>
                <th style="width: 20%;" class="text-center">Kuantitas Kirim</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($items as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td style="font-family: monospace; font-weight: bold;"><?= esc($item['kode_produk']) ?></td>
                    <td><?= esc($item['nama_produk']) ?></td>
                    <td class="text-center" style="font-family: monospace; font-weight: bold; font-size: 13px;">
                        <?= number_format((float) $item['qty'], 0) ?> <?= esc($item['nama_satuan'] ?? 'PCS') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 11px; color: #64748b;">
        Catatan: Harap periksa keutuhan barang dan segel sebelum menandatangani surat jalan ini.
    </div>

    <table class="footer-table" style="margin-top: 40px;">
        <tr>
            <td style="width: 33%; text-align: center;">
                <div>Diterima Oleh,</div>
                <div style="height: 50px;"></div>
                <div style="border-top: 1px solid #94a3b8; width: 80%; margin: 0 auto;">( Pelanggan / Cap )</div>
            </td>
            <td style="width: 33%; text-align: center;">
                <div>Petugas Driver / Ekspedisi,</div>
                <div style="height: 50px;"></div>
                <div style="border-top: 1px solid #94a3b8; width: 80%; margin: 0 auto;">( Kurir )</div>
            </td>
            <td style="width: 33%; text-align: center;">
                <div>Bagian Pengiriman,</div>
                <div style="height: 50px;"></div>
                <div style="border-top: 1px solid #94a3b8; width: 80%; margin: 0 auto;">( Petugas Pengiriman )</div>
            </td>
        </tr>
    </table>
</body>
</html>

