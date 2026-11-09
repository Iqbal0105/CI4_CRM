<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Bukti Kas Masuk') ?></title>
    <style>
        @page {
            size: A5 landscape;
            margin: 8mm 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: <?= !empty($isPdf) ? '0' : '15px' ?>;
            background: #ffffff;
        }
        .voucher-box {
            border: 2px solid #0f172a;
            padding: 14px 18px;
            background: #ffffff;
            box-sizing: border-box;
        }
        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .header-sub {
            font-size: 9.5px;
            color: #475569;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 15px;
            color: #15803d;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .doc-title .doc-no {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            font-size: 12px;
            color: #0f172a;
        }
        .divider-thick {
            border-top: 2px solid #0f172a;
            margin: 8px 0 12px 0;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .content-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .label-col {
            width: 130px;
            font-weight: 600;
            color: #334155;
        }
        .colon-col {
            width: 10px;
            text-align: center;
        }
        .value-col {
            color: #0f172a;
        }
        .terbilang-box {
            background-color: #f8fafc;
            border: 1px dashed #94a3b8;
            padding: 5px 8px;
            font-style: italic;
            font-weight: bold;
            color: #1e293b;
            border-radius: 4px;
        }
        .nominal-badge {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            display: inline-block;
            border-radius: 4px;
        }
        .sign-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
            text-align: center;
            font-size: 10px;
        }
        .sign-table td {
            vertical-align: top;
            padding: 0 10px;
        }
        .sign-space {
            height: 48px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .print-btn-bar {
            position: fixed;
            bottom: 15px;
            right: 15px;
            z-index: 9999;
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 30px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.18);
            display: flex;
            gap: 8px;
        }
        .btn-action {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }
        .btn-print { background: #15803d; color: #ffffff; }
        .btn-close-tab { background: #64748b; color: #ffffff; }
        @media print {
            .print-btn-bar { display: none !important; }
            body { padding: 0 !important; }
            .voucher-box { border: 2px solid #000000 !important; }
        }
    </style>
</head>
<body>

<?php if (empty($isPdf)): ?>
<div class="print-btn-bar">
    <button type="button" class="btn-action btn-print" onclick="window.print()">
        &#128438; Cetak Dokumen
    </button>
    <a href="<?= base_url('keuangan/bkm-pdf/' . $bkm['id']) ?>" class="btn-action" style="background-color: #dc2626; color: white;" target="_blank">
        &#128196; Unduh PDF
    </a>
    <button type="button" class="btn-action btn-close-tab" onclick="window.close()">
        &#10005; Tutup
    </button>
</div>
<?php endif; ?>

<div class="voucher-box">
    <!-- HEADER -->
    <table class="table-layout">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="header-title">IDMETAFORA ENTERPRISE ERP</div>
                <div class="header-sub">PT METAFORA TEKNOLOGI INFORMATIKA</div>
                <div class="header-sub">Divisi Perbendaharaan &amp; Kas Keuangan (Treasury)</div>
            </td>
            <td style="width: 40%; vertical-align: top;" class="doc-title">
                <h2>BUKTI KAS MASUK</h2>
                <div class="doc-no">NO: <?= esc($bkm['nomor_bkm'] ?? '-') ?></div>
                <div style="font-size: 10px; color: #475569; margin-top: 2px;">
                    Tanggal: <strong><?= !empty($bkm['tanggal_bayar']) ? date('d/m/Y', strtotime($bkm['tanggal_bayar'])) : date('d/m/Y') ?></strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider-thick"></div>

    <!-- CONTENT DATA -->
    <table class="content-table">
        <tr>
            <td class="label-col">Diterima Dari</td>
            <td class="colon-col">:</td>
            <td class="value-col">
                <strong><?= esc($bkm['nama_pelanggan'] ?? 'Pelanggan Walk-In / Umum') ?></strong>
                <?php if (!empty($bkm['kode_pelanggan'])): ?>
                    <span style="color: #64748b; font-size: 10px;">(Kode: <?= esc($bkm['kode_pelanggan']) ?>)</span>
                <?php endif; ?>
                <?php if (!empty($bkm['no_telp'])): ?>
                    <span style="color: #64748b; font-size: 10px;">&bull; Telp: <?= esc($bkm['no_telp']) ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="label-col">Uang Sejumlah</td>
            <td class="colon-col">:</td>
            <td class="value-col">
                <div class="terbilang-box">
                    *** <?= terbilang($bkm['nominal'] ?? 0) ?> ***
                </div>
            </td>
        </tr>
        <tr>
            <td class="label-col">Untuk Pembayaran</td>
            <td class="colon-col">:</td>
            <td class="value-col">
                Pelunasan / Angsuran Piutang Usaha
                <?php if (!empty($bkm['nomor_piutang'])): ?>
                    No. <strong><?= esc($bkm['nomor_piutang']) ?></strong>
                <?php endif; ?>
                <?php if (!empty($bkm['nomor_so'])): ?>
                    (Ref Sales Order: <?= esc($bkm['nomor_so']) ?>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="label-col">Disetor Melalui</td>
            <td class="colon-col">:</td>
            <td class="value-col">
                <strong><?= esc($bkm['nama_kas_bank'] ?? 'Kas Utama') ?></strong>
                &bull; Metode: <span style="text-transform: uppercase; font-weight: bold; color: #0369a1;"><?= esc($bkm['metode_pembayaran'] ?? 'TUNAI') ?></span>
            </td>
        </tr>
        <?php if (!empty($bkm['catatan'])): ?>
        <tr>
            <td class="label-col">Catatan</td>
            <td class="colon-col">:</td>
            <td class="value-col" style="color: #475569;">
                <?= nl2br(esc($bkm['catatan'])) ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="label-col" style="padding-top: 8px;">JUMLAH NOMINAL</td>
            <td class="colon-col" style="padding-top: 8px;">:</td>
            <td class="value-col" style="padding-top: 6px;">
                <div class="nominal-badge">
                    Rp <?= number_format((float) ($bkm['nominal'] ?? 0), 0, ',', '.') ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- SIGNATURES -->
    <table class="sign-table">
        <tr>
            <td style="width: 33%;">
                <div>Diterima / Disiapkan Oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-name"><?= esc($bkm['petugas'] ?? 'Kasir / Finance') ?></div>
                <div style="color: #64748b;">Kasir Keuangan</div>
            </td>
            <td style="width: 34%;">
                <div>Mengetahui / Menyetujui,</div>
                <div class="sign-space"></div>
                <div class="sign-name">Finance Manager</div>
                <div style="color: #64748b;">Accounting &amp; Finance</div>
            </td>
            <td style="width: 33%;">
                <div>Disetor Oleh (Penyetor),</div>
                <div class="sign-space"></div>
                <div class="sign-name"><?= esc($bkm['nama_pelanggan'] ?? 'Pelanggan') ?></div>
                <div style="color: #64748b;">Pihak Penyetor</div>
            </td>
        </tr>
    </table>
</div>

<?php if (empty($isPdf)): ?>
<script>
    window.addEventListener('load', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.print();
        }
    });
</script>
<?php endif; ?>

</body>
</html>

