<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #<?= esc($order['nomor_so']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 76mm;
            margin: 0 auto;
            padding: 8px 4px;
            font-size: 11px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 7px 12px;
            background: #2D6A4F;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-family: sans-serif;
            text-decoration: none;
        }
        .btn-bt {
            background: #0284c7;
        }
        .btn-bt:hover {
            background: #0369a1;
        }
        .btn-usb {
            background: #16a34a;
        }
        .btn-usb:hover {
            background: #15803d;
        }
        .btn-help {
            background: #ea580c;
        }
        .btn-help:hover {
            background: #c2410c;
        }
        .btn-print:hover {
            opacity: 0.92;
        }
        .action-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 14px;
            padding: 8px;
            background: #f1f5f9;
            border-radius: 6px;
        }
        .help-box {
            display: none;
            width: 100%;
            background: #fff;
            border: 1px solid #fed7aa;
            border-radius: 6px;
            padding: 10px;
            margin-top: 8px;
            font-family: sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #334155;
            text-align: left;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print action-bar">
        <button type="button" class="btn-print btn-bt" id="btnBluetoothPrint" onclick="printViaBluetooth()">
            <i class="fa-brands fa-bluetooth-b"></i> Cetak Thermal Bluetooth
        </button>
        <button type="button" class="btn-print btn-usb" id="btnSerialPrint" onclick="printViaSerial()">
            <i class="fa-solid fa-plug"></i> Cetak USB / Serial
        </button>
        <button type="button" class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Cetak Browser
        </button>
        <button type="button" class="btn-print btn-help" onclick="toggleHelp()">
            <i class="fa-solid fa-circle-question"></i> Bantuan Pairing
        </button>
        <button type="button" class="btn-print" style="background:#64748b;" onclick="window.close()">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
        <div style="width: 100%; font-size: 11px; color: #475569; font-family: sans-serif; text-align: center; margin-top: 4px;" id="btStatusText">
            <i class="fa-solid fa-circle-info me-1"></i> Pilih Bluetooth BLE atau USB / Serial untuk mengirim data transaksi ke printer thermal.
        </div>
        <div class="help-box" id="helpBox">
            <div style="font-weight: bold; color: #c2410c; margin-bottom: 4px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Kenapa muncul "Tidak ditemukan perangkat yang kompatibel"?
            </div>
            <ol style="margin: 0; padding-left: 18px;">
                <li><strong>Printer Thermal Harus Hidup (ON):</strong> Pastikan daya baterai cukup dan lampu indikator biru/hijau menyala/berkedip.</li>
                <li><strong>Lepas Koneksi dari HP Kasir:</strong> Bluetooth printer portable hanya bisa terhubung ke 1 alat saja. Jika sebelumnya tersambung ke HP Android kasir, matikan Bluetooth di HP kasir tersebut agar printer dapat ditemukan laptop.</li>
                <li><strong>Bluetooth Komputer ON:</strong> Pastikan Bluetooth di Windows / Laptop dalam posisi AKTIF.</li>
                <li><strong>Gunakan Tombol "Cetak USB / Serial":</strong> Banyak printer thermal murah memakai <em>Bluetooth Classic (SPP)</em> atau kabel USB. Cukup klik tombol hijau <strong>"Cetak USB / Serial"</strong> di atas.</li>
                <li><strong>Cetak Browser:</strong> Anda juga bisa langsung klik <strong>"Cetak Browser"</strong> untuk langsung mencetak struk roll 58/80mm tanpa bluetooth.</li>
            </ol>
        </div>
    </div>

    <div class="text-center">
        <div class="fw-bold" style="font-size: 14px;">METAFORA STORE</div>
        <div>Jl. Raya Bisnis Terpadu No. 12</div>
        <div>Telp / WA: 0812-3456-7890</div>
    </div>

    <div class="dashed-line"></div>

    <div>
        <div>No. Trx : <?= esc($order['nomor_so']) ?></div>
        <div>No. Inv : <?= esc($order['nomor_invoice'] ?? '-') ?></div>
        <div>Waktu   : <?= format_datetime($order['created_at']) ?></div>
        <div>Kasir   : <?= esc($order['kasir_nama'] ?? 'Kasir') ?></div>
        <div>Cust    : <?= esc($order['nama_pelanggan']) ?></div>
    </div>

    <div class="dashed-line"></div>

    <table>
        <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="3" class="fw-bold"><?= esc($item['nama_produk']) ?></td>
            </tr>
            <tr>
                <td><?= number_format((float) $item['qty'], 0) ?> x <?= number_format((float) $item['harga_satuan'], 0, ',', '.') ?></td>
                <td></td>
                <td class="text-end font-monospace"><?= number_format((float) $item['subtotal'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="dashed-line"></div>

    <table>
        <tr>
            <td>Subtotal:</td>
            <td class="text-end"><?= number_format((float) $order['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php if ((float) $order['diskon_total'] > 0): ?>
            <tr>
                <td>Diskon:</td>
                <td class="text-end">-<?= number_format((float) $order['diskon_total'], 0, ',', '.') ?></td>
            </tr>
        <?php endif; ?>
        <?php if ((float) $order['ppn'] > 0): ?>
            <tr>
                <td>PPN:</td>
                <td class="text-end"><?= number_format((float) $order['ppn'], 0, ',', '.') ?></td>
            </tr>
        <?php endif; ?>
        <tr class="fw-bold">
            <td style="font-size: 13px;">TOTAL:</td>
            <td class="text-end" style="font-size: 13px;">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Bayar (<?= strtoupper(esc($order['metode_bayar'])) ?>):</td>
            <td class="text-end"><?= number_format((float) $order['bayar'], 0, ',', '.') ?></td>
        </tr>
        <tr class="fw-bold">
            <td>Kembalian:</td>
            <td class="text-end">Rp <?= number_format((float) $order['kembalian'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    <div class="text-center" style="margin-top: 8px;">
        <div>Terima kasih atas kunjungan Anda!</div>
        <div style="font-size: 10px; margin-top: 4px;">Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian.</div>
    </div>

    <script src="<?= base_url('js/bluetooth-thermal-printer.js') ?>"></script>
    <script>
        const receiptData = {
            order: <?= json_encode($order) ?>,
            items: <?= json_encode($items) ?>,
            store: {
                name: 'METAFORA STORE',
                address: 'Jl. Raya Bisnis Terpadu No. 12',
                phone: 'Telp/WA: 0812-3456-7890',
                footer: 'Terima kasih atas kunjungan Anda!',
                note: 'Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian.'
            }
        };

        function toggleHelp() {
            const box = document.getElementById('helpBox');
            box.style.display = box.style.display === 'block' ? 'none' : 'block';
        }

        async function printViaBluetooth() {
            const btn = document.getElementById('btnBluetoothPrint');
            const statusText = document.getElementById('btStatusText');
            
            try {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyandingkan...';
                statusText.innerHTML = '<span style="color:#0284c7;"><i class="fa-solid fa-satellite-dish fa-fade"></i> Memindai printer Bluetooth BLE... Pilih printer Anda di dialog browser.</span>';

                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'bluetooth') {
                    await window.posBluetoothPrinter.connectBluetooth();
                }

                statusText.innerHTML = '<span style="color:#0284c7;"><i class="fa-solid fa-spinner fa-spin"></i> Mengirim struk ke printer...</span>';
                await window.posBluetoothPrinter.printReceipt(receiptData.order, receiptData.items, receiptData.store);
                
                statusText.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-circle-check"></i> Struk transaksi #' + (receiptData.order.nomor_so || '') + ' berhasil dicetak via Bluetooth!</span>';
            } catch (err) {
                console.error('Bluetooth print error:', err);
                statusText.innerHTML = '<span style="color:#dc2626;"><i class="fa-solid fa-triangle-exclamation"></i> ' + (err.message || 'Gagal menyambung ke printer Bluetooth') + '</span>';
                document.getElementById('helpBox').style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-brands fa-bluetooth-b"></i> Cetak Thermal Bluetooth';
            }
        }

        async function printViaSerial() {
            const btn = document.getElementById('btnSerialPrint');
            const statusText = document.getElementById('btStatusText');
            
            try {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memilih Port...';
                statusText.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-plug"></i> Pilih port printer USB atau Bluetooth COM di popup...</span>';

                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'serial') {
                    await window.posBluetoothPrinter.connectSerial();
                }

                statusText.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-spinner fa-spin"></i> Mengirim struk ke port...</span>';
                await window.posBluetoothPrinter.printReceipt(receiptData.order, receiptData.items, receiptData.store);
                
                statusText.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-circle-check"></i> Struk transaksi berhasil dicetak via USB / Serial!</span>';
            } catch (err) {
                console.error('Serial print error:', err);
                statusText.innerHTML = '<span style="color:#dc2626;"><i class="fa-solid fa-triangle-exclamation"></i> ' + (err.message || 'Gagal membuka port USB/Serial') + '</span>';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-plug"></i> Cetak USB / Serial';
            }
        }
    </script>
</body>
</html>

