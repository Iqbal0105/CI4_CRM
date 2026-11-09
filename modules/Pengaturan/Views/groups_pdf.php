<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Grup &amp; Peran Pengguna - <?= date('Ymd_His') ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        /* HEADER SECTION */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #2D6A4F;
            padding-bottom: 10px;
        }

        .company-title {
            font-size: 16px;
            font-weight: bold;
            color: #1A2E23;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .company-subtitle {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #2D6A4F;
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-meta {
            font-size: 9px;
            color: #64748b;
            text-align: right;
            margin-top: 3px;
        }

        /* METRIC SUMMARY BOXES */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 14px;
        }

        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: left;
        }

        .summary-box-title {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-box-val {
            font-size: 13px;
            font-weight: bold;
            margin-top: 2px;
        }

        .summary-box-desc {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 1px;
        }

        /* DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .data-table th {
            background-color: #1A2E23;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 7px 8px;
            border: 1px solid #1A2E23;
            text-align: left;
        }

        .data-table th.text-center { text-align: center; }
        .data-table th.text-end { text-align: right; }

        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .font-mono { font-family: "Courier New", Courier, monospace; }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .badge-member {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-empty {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .code-tag {
            font-family: "Courier New", Courier, monospace;
            font-size: 8.5px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                <div class="company-title">IDMETAFORA ENTERPRISE ERP</div>
                <div class="company-subtitle">
                    Modul Pengaturan &gt; Struktur Grup, Peran &amp; Hak Akses Pengguna<br>
                    Jl. Ringroad Utara, Sleman, D.I. Yogyakarta | Telp: (0274) 8899-7700
                </div>
            </td>
            <td style="width: 45%; vertical-align: middle;">
                <div class="report-title">STRUKTUR PERAN &amp; HAK AKSES</div>
                <div class="report-meta">
                    <strong>Dokumen:</strong> RPT-ROLE-<?= date('Ymd') ?>-<?= strtoupper(substr(md5(date('YmdHis')), 0, 4)) ?><br>
                    <strong>Waktu Cetak:</strong> <?= date('d/m/Y H:i:s') ?> WIB<br>
                    <strong>Operator:</strong> <?= esc($currentUser['username'] ?? 'admin') ?> (<?= esc($currentUser['role'] ?? 'Super Admin') ?>)
                </div>
            </td>
        </tr>
    </table>

    <?php
        $totalRoles = count($roles);
        $totalMembers = 0;
        $activeRoles = 0;
        foreach ($roles as $r) {
            $cnt = (int)($r['user_count'] ?? 0);
            $totalMembers += $cnt;
            if ($cnt > 0) {
                $activeRoles++;
            }
        }
    ?>

    <!-- SUMMARY METRICS ROW -->
    <table class="summary-table">
        <tr>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Total Grup / Peran</div>
                    <div class="summary-box-val" style="color: #1A2E23;"><?= $totalRoles ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Peran</span></div>
                    <div class="summary-box-desc">Struktur kewenangan terdaftar dalam sistem</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Pengguna Terpetakan</div>
                    <div class="summary-box-val" style="color: #0369a1;"><?= $totalMembers ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Anggota</span></div>
                    <div class="summary-box-desc">Total seluruh penugasan user ke grup</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Peran Aktif / Terisi</div>
                    <div class="summary-box-val" style="color: #16a34a;"><?= $activeRoles ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Peran</span></div>
                    <div class="summary-box-desc">Memiliki minimal 1 pengguna yang aktif bertugas</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">No</th>
                <th style="width: 10%;" class="text-center">ID</th>
                <th style="width: 28%;">Nama Grup / Peran</th>
                <th style="width: 38%;">Definisi &amp; Deskripsi Akses</th>
                <th style="width: 18%;" class="text-center">Jumlah Anggota</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($roles)): ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #94a3b8; font-style: italic;">
                        Tidak ada data grup / peran yang ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($roles as $idx => $g): ?>
                    <?php 
                        $memberCount = (int)($g['user_count'] ?? 0);
                    ?>
                    <tr>
                        <td class="text-center font-mono"><?= $idx + 1 ?></td>
                        <td class="text-center font-mono" style="color: #64748b;">#<?= esc((string)$g['id']) ?></td>
                        <td>
                            <strong style="color: #0f172a; font-size: 10px;"><?= esc($g['display_name']) ?></strong>
                            <div class="code-tag">@<?= esc($g['name']) ?></div>
                        </td>
                        <td style="color: #334155; font-size: 9.5px;">
                            <?= esc($g['description'] ?? '-') ?>
                        </td>
                        <td class="text-center">
                            <?php if ($memberCount > 0): ?>
                                <span class="badge badge-member">
                                    <?= $memberCount ?> Anggota
                                </span>
                            <?php else: ?>
                                <span class="badge badge-empty">
                                    0 Anggota
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

