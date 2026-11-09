<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Manajemen Pengguna - <?= date('Ymd_His') ?></title>
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
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }

        .badge-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .badge-role {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            font-size: 9px;
            font-weight: normal;
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
                    Modul Pengaturan &gt; Manajemen Pengguna &amp; Otoritas Hak Akses<br>
                    Jl. Ringroad Utara, Sleman, D.I. Yogyakarta | Telp: (0274) 8899-7700
                </div>
            </td>
            <td style="width: 45%; vertical-align: middle;">
                <div class="report-title">DAFTAR PENGGUNA SISTEM</div>
                <div class="report-meta">
                    <strong>Dokumen:</strong> RPT-USER-<?= date('Ymd') ?>-<?= strtoupper(substr(md5(date('YmdHis')), 0, 4)) ?><br>
                    <strong>Waktu Cetak:</strong> <?= date('d/m/Y H:i:s') ?> WIB<br>
                    <strong>Operator:</strong> <?= esc($currentUser['username'] ?? 'admin') ?> (<?= esc($currentUser['role'] ?? 'Super Admin') ?>)
                </div>
            </td>
        </tr>
    </table>

    <?php
        $totalUsers = count($users);
        $activeCount = 0;
        $inactiveCount = 0;
        foreach ($users as $u) {
            if (($u['status'] ?? 'active') === 'active') {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
        }
    ?>

    <!-- SUMMARY METRICS ROW -->
    <table class="summary-table">
        <tr>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Total Akun Terdaftar</div>
                    <div class="summary-box-val" style="color: #1A2E23;"><?= $totalUsers ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Pengguna</span></div>
                    <div class="summary-box-desc">Seluruh akun pengguna dalam sistem</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Pengguna Aktif</div>
                    <div class="summary-box-val" style="color: #16a34a;"><?= $activeCount ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Akun</span></div>
                    <div class="summary-box-desc">Memiliki akses masuk aktif ke ERP</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-box">
                    <div class="summary-box-title">Pengguna Non-Aktif</div>
                    <div class="summary-box-val" style="color: #dc2626;"><?= $inactiveCount ?> <span style="font-size: 10px; font-weight: normal; color: #64748b;">Akun</span></div>
                    <div class="summary-box-desc">Akses login ditangguhkan / dinonaktifkan</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No.</th>
                <th style="width: 22%;">Username / Nama Akun</th>
                <th style="width: 26%;">Alamat Email</th>
                <th style="width: 21%;">Role / Hak Akses</th>
                <th class="text-center" style="width: 12%;">Status</th>
                <th class="text-center" style="width: 14%;">Terdaftar Pada</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #64748b;">
                        Tidak ada data pengguna yang ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $idx => $u): ?>
                    <?php
                        $st = strtolower((string)($u['status'] ?? 'active'));
                        $isActive = ($st === 'active');
                        $tgl = !empty($u['created_at']) ? date('d/m/Y H:i', strtotime($u['created_at'])) : '-';
                    ?>
                    <tr>
                        <td class="text-center font-mono"><?= $idx + 1 ?></td>
                        <td>
                            <strong style="color: #0f172a;"><?= esc($u['username']) ?></strong>
                            <div style="font-size: 8px; color: #94a3b8; font-family: 'Courier New', monospace;">ID: #<?= esc((string)$u['id']) ?></div>
                        </td>
                        <td class="font-mono" style="color: #334155;">
                            <?= esc($u['email'] ?: '-') ?>
                        </td>
                        <td>
                            <span class="badge badge-role">
                                <?= esc($u['role'] ?? 'General') ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $isActive ? 'Aktif' : 'Non-Aktif' ?>
                            </span>
                        </td>
                        <td class="text-center font-mono" style="font-size: 8.5px; color: #64748b;">
                            <?= esc($tgl) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

