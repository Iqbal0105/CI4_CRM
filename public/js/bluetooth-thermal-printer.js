/**
 * Web Bluetooth & Web Serial ESC/POS Thermal Printer Driver
 * Supports 58mm (32 chars) & 80mm (48 chars) Bluetooth & USB Thermal Printers
 * IDMETAFORA ERP System
 */

class BluetoothThermalPrinter {
    constructor() {
        this.device = null;
        this.server = null;
        this.characteristic = null;
        this.serialPort = null;
        this.serialWriter = null;
        this.connectionType = null; // 'bluetooth' | 'serial' | null
        this.isConnected = false;
        this.deviceName = '';
        this.paperWidth = parseInt(localStorage.getItem('pos_thermal_width') || '32', 10); // 32 for 58mm, 48 for 80mm

        // Standard Bluetooth Thermal Printer GATT Services & Characteristics
        this.PRINTER_SERVICES = [
            '000018f0-0000-1000-8000-00805f9b34fb', // Standard POS Printer Service
            '0000ffe0-0000-1000-8000-00805f9b34fb', // HM-10 / CC2541 BLE (Very common in portable printers)
            '0000ff00-0000-1000-8000-00805f9b34fb', // Common POS BLE
            '0000fff0-0000-1000-8000-00805f9b34fb',
            '0000fee7-0000-1000-8000-00805f9b34fb', // Tencent / POS
            '49535343-fe7d-4ae5-8fa9-9fafd205e455', // ISSC Transparent
            'e7810a71-73ae-499d-8c15-faa9aef0c3f2', // Android POS Printer
            '6e400001-b5a3-f393-e0a9-e50e24dcca9e', // Nordic UART Service (Very popular in portable BLE printers)
            '0000ae00-0000-1000-8000-00805f9b34fb',
            '0000af00-0000-1000-8000-00805f9b34fb',
            'bef8d6c9-9c21-4c9e-b632-bd58c1009f9f', // Zebra / Generic BLE
            '000018f1-0000-1000-8000-00805f9b34fb',
            '000018f2-0000-1000-8000-00805f9b34fb'
        ];

        this.onStatusChange = null;
    }

    isBluetoothSupported() {
        return Boolean(navigator && navigator.bluetooth && navigator.bluetooth.requestDevice);
    }

    isSerialSupported() {
        return Boolean(navigator && navigator.serial && navigator.serial.requestPort);
    }

    isSupported() {
        return this.isBluetoothSupported() || this.isSerialSupported();
    }

    setPaperWidth(width) {
        this.paperWidth = parseInt(width, 10) === 48 ? 48 : 32;
        localStorage.setItem('pos_thermal_width', this.paperWidth.toString());
    }

    getPaperWidth() {
        return this.paperWidth;
    }

    /**
     * Connect via Web Bluetooth (BLE)
     */
    async connectBluetooth() {
        if (!this.isBluetoothSupported()) {
            throw new Error(
                'Web Bluetooth tidak didukung pada browser ini. Silakan gunakan Google Chrome atau Microsoft Edge di Laptop/PC/HP dan pastikan Bluetooth perangkat dalam kondisi aktif.'
            );
        }

        try {
            // Trigger browser native Web Bluetooth pairing popup
            this.device = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: this.PRINTER_SERVICES
            });

            if (!this.device) {
                throw new Error('Tidak ada printer Bluetooth yang dipilih.');
            }

            this.deviceName = this.device.name || 'Bluetooth Thermal Printer';

            this.device.addEventListener('gattserverdisconnected', () => {
                this.isConnected = false;
                this.server = null;
                this.characteristic = null;
                this.connectionType = null;
                if (typeof this.onStatusChange === 'function') {
                    this.onStatusChange(false, this.deviceName);
                }
            });

            // Connect to GATT
            this.server = await this.device.gatt.connect();

            // Search for primary service & writeable characteristic
            let writeChar = null;

            for (const serviceUuid of this.PRINTER_SERVICES) {
                try {
                    const service = await this.server.getPrimaryService(serviceUuid);
                    const characteristics = await service.getCharacteristics();
                    for (const char of characteristics) {
                        if (char.properties.write || char.properties.writeWithoutResponse) {
                            writeChar = char;
                            break;
                        }
                    }
                    if (writeChar) break;
                } catch (e) {
                    // Continue checking next service UUID
                }
            }

            // Fallback: If not found in known UUID list, scan all primary services
            if (!writeChar) {
                try {
                    const services = await this.server.getPrimaryServices();
                    for (const service of services) {
                        try {
                            const chars = await service.getCharacteristics();
                            for (const char of chars) {
                                if (char.properties.write || char.properties.writeWithoutResponse) {
                                    writeChar = char;
                                    break;
                                }
                            }
                            if (writeChar) break;
                        } catch (err) {}
                    }
                } catch (fallbackErr) {
                    console.warn('Fallback primary services scan error:', fallbackErr);
                }
            }

            if (!writeChar) {
                throw new Error('Karakteristik write untuk printer thermal tidak ditemukan pada perangkat ini.');
            }

            this.characteristic = writeChar;
            this.connectionType = 'bluetooth';
            this.isConnected = true;

            if (typeof this.onStatusChange === 'function') {
                this.onStatusChange(true, this.deviceName);
            }

            return this.deviceName;
        } catch (err) {
            this.isConnected = false;
            this.server = null;
            this.characteristic = null;
            this.connectionType = null;
            if (typeof this.onStatusChange === 'function') {
                this.onStatusChange(false, '');
            }
            throw err;
        }
    }

    /**
     * Connect via Web Serial (USB or Windows Bluetooth Outgoing COM Port)
     */
    async connectSerial(baudRate = 9600) {
        if (!this.isSerialSupported()) {
            throw new Error(
                'Web Serial API tidak didukung browser ini. Silakan gunakan Google Chrome, Microsoft Edge, atau Opera versi terbaru di PC / Laptop.'
            );
        }

        try {
            this.serialPort = await navigator.serial.requestPort();
            await this.serialPort.open({ baudRate: parseInt(baudRate, 10) || 9600 });
            this.serialWriter = this.serialPort.writable.getWriter();
            this.connectionType = 'serial';
            this.deviceName = 'USB / Serial Thermal Printer';
            this.isConnected = true;

            if (typeof this.onStatusChange === 'function') {
                this.onStatusChange(true, this.deviceName);
            }

            return this.deviceName;
        } catch (err) {
            this.isConnected = false;
            this.serialPort = null;
            this.serialWriter = null;
            this.connectionType = null;
            if (typeof this.onStatusChange === 'function') {
                this.onStatusChange(false, '');
            }
            throw err;
        }
    }

    // Default connect method (defaults to Bluetooth)
    async connect() {
        return this.connectBluetooth();
    }

    async disconnect() {
        if (this.connectionType === 'bluetooth' && this.device && this.device.gatt && this.device.gatt.connected) {
            await this.device.gatt.disconnect();
        } else if (this.connectionType === 'serial' && this.serialWriter) {
            try {
                this.serialWriter.releaseLock();
                if (this.serialPort) await this.serialPort.close();
            } catch (e) {
                console.warn('Error closing serial port:', e);
            }
        }
        this.isConnected = false;
        this.server = null;
        this.characteristic = null;
        this.serialPort = null;
        this.serialWriter = null;
        this.connectionType = null;
        if (typeof this.onStatusChange === 'function') {
            this.onStatusChange(false, '');
        }
    }

    async writeRaw(bytes) {
        if (!this.isConnected) {
            throw new Error('Printer belum tersambung. Silakan sambungkan melalui Bluetooth atau USB/Serial terlebih dahulu.');
        }

        if (this.connectionType === 'serial') {
            if (!this.serialWriter) {
                throw new Error('Serial writer tidak siap.');
            }
            await this.serialWriter.write(bytes);
            return;
        }

        // Bluetooth BLE chunked sending
        if (!this.characteristic) {
            throw new Error('Karakteristik Bluetooth tidak tersedia.');
        }

        const chunkSize = 60;
        for (let i = 0; i < bytes.length; i += chunkSize) {
            const chunk = bytes.slice(i, i + chunkSize);
            if (this.characteristic.writeValueWithoutResponse) {
                await this.characteristic.writeValueWithoutResponse(chunk);
            } else {
                await this.characteristic.writeValue(chunk);
            }
            await new Promise(resolve => setTimeout(resolve, 20));
        }
    }

    async printReceipt(order, items, store = null) {
        const builder = new EscPosBuilder(this.paperWidth);
        const receiptBytes = builder.buildReceipt(order, items, store);
        await this.writeRaw(receiptBytes);
    }

    async printShippingLabel(order, items, store = null) {
        const builder = new EscPosBuilder(this.paperWidth);
        const labelBytes = builder.buildShippingLabel(order, items, store);
        await this.writeRaw(labelBytes);
    }

    async printVoucher(type, data, store = null) {
        const builder = new EscPosBuilder(this.paperWidth);
        const voucherBytes = builder.buildVoucher(type, data, store);
        await this.writeRaw(voucherBytes);
    }

    async printRetur(type, retur, items, store = null) {
        const builder = new EscPosBuilder(this.paperWidth);
        const returBytes = builder.buildRetur(type, retur, items, store);
        await this.writeRaw(returBytes);
    }

    async testPrint() {
        const builder = new EscPosBuilder(this.paperWidth);
        builder.init()
            .alignCenter()
            .bold(true)
            .doubleHeight(true)
            .line('TEST PRINT THERMAL')
            .bold(false)
            .doubleHeight(false)
            .line('METAFORA ERP SYSTEM')
            .dashedLine()
            .alignLeft()
            .line('Koneksi  : ' + (this.connectionType === 'serial' ? 'USB / Serial COM' : 'Web Bluetooth BLE'))
            .line('Status   : Terhubung OK')
            .line('Perangkat: ' + (this.deviceName || 'Thermal Printer'))
            .line('Lebar    : ' + this.paperWidth + ' Karakter (' + (this.paperWidth === 32 ? '58mm' : '80mm') + ')')
            .line('Waktu    : ' + new Date().toLocaleString('id-ID'))
            .dashedLine()
            .alignCenter()
            .line('Koneksi Printer Siap Digunakan!')
            .feed(4)
            .cut();

        await this.writeRaw(builder.getBytes());
    }
}

/**
 * ESC/POS Byte Buffer Builder
 */
class EscPosBuilder {
    constructor(paperWidth = 32) {
        this.width = paperWidth; // 32 chars (58mm) or 48 chars (80mm)
        this.buffer = [];
    }

    raw(bytes) {
        for (let i = 0; i < bytes.length; i++) {
            this.buffer.push(bytes[i]);
        }
        return this;
    }

    text(str) {
        const encoder = new TextEncoder();
        const encoded = encoder.encode(str);
        return this.raw(encoded);
    }

    init() {
        return this.raw([0x1B, 0x40]); // ESC @
    }

    alignLeft() {
        return this.raw([0x1B, 0x61, 0x00]); // ESC a 0
    }

    alignCenter() {
        return this.raw([0x1B, 0x61, 0x01]); // ESC a 1
    }

    alignRight() {
        return this.raw([0x1B, 0x61, 0x02]); // ESC a 2
    }

    bold(enable = true) {
        return this.raw([0x1B, 0x45, enable ? 0x01 : 0x00]); // ESC E n
    }

    doubleHeight(enable = true) {
        return this.raw([0x1B, 0x21, enable ? 0x10 : 0x00]); // ESC ! 16
    }

    doubleWidth(enable = true) {
        return this.raw([0x1B, 0x21, enable ? 0x20 : 0x00]);
    }

    line(str = '') {
        return this.text(str + '\n');
    }

    dashedLine() {
        return this.line('-'.repeat(this.width));
    }

    doubleDashedLine() {
        return this.line('='.repeat(this.width));
    }

    twoColumns(left, right) {
        left = String(left || '');
        right = String(right || '');
        const spaceCount = this.width - left.length - right.length;
        if (spaceCount < 1) {
            const maxLeft = Math.max(1, this.width - right.length - 1);
            left = left.substring(0, maxLeft);
        }
        const spaces = ' '.repeat(Math.max(1, this.width - left.length - right.length));
        return this.line(left + spaces + right);
    }

    feed(lines = 3) {
        for (let i = 0; i < lines; i++) this.line('');
        return this;
    }

    cut() {
        return this.raw([0x1D, 0x56, 0x41, 0x03]); // GS V A 3 (Feed & Cut)
    }

    getBytes() {
        return new Uint8Array(this.buffer);
    }

    formatRupiah(num) {
        return Math.round(parseFloat(num) || 0).toLocaleString('id-ID');
    }

    // 1. STRUK PENJUALAN / POS / INVOICE
    buildReceipt(order, items, store = null) {
        const storeName = (store && store.name) || 'METAFORA STORE';
        const storeAddress = (store && store.address) || 'Jl. Raya Bisnis Terpadu No. 12';
        const storePhone = (store && store.phone) || 'Telp/WA: 0812-3456-7890';
        const storeFooter = (store && store.footer) || 'Terima kasih atas kunjungan Anda!';
        const storeNote = (store && store.note) || 'Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian.';

        this.init();

        // 1. HEADER TOKO
        this.alignCenter();
        this.bold(true);
        this.doubleHeight(true);
        this.line(storeName);
        this.bold(false);
        this.doubleHeight(false);
        if (storeAddress) this.line(storeAddress);
        if (storePhone) this.line(storePhone);

        // 2. DIVIDER
        this.dashedLine();

        // 3. INFORMASI TRANSAKSI
        this.alignLeft();
        const noTrx = order.nomor_so || order.no_so || '-';
        const noInv = order.nomor_invoice || order.no_invoice || '-';
        const waktu = order.created_at || new Date().toLocaleString('id-ID');
        const kasir = order.kasir_nama || order.kasir || 'Kasir';
        const cust  = order.nama_pelanggan || order.pelanggan || 'Pelanggan Umum';

        this.twoColumns('No. Trx', noTrx);
        if (noInv && noInv !== '-') this.twoColumns('No. Inv', noInv);
        this.twoColumns('Waktu', waktu);
        this.twoColumns('Kasir', kasir);
        this.twoColumns('Customer', cust);

        // 4. DIVIDER ITEM
        this.dashedLine();

        // 5. DAFTAR ITEM BELANJA
        if (Array.isArray(items)) {
            items.forEach(item => {
                const nama = item.nama_produk || item.nama || 'Produk';
                const qty  = Math.round(parseFloat(item.qty || 1));
                const harga = this.formatRupiah(item.harga_satuan || item.harga || 0);
                const subtotal = this.formatRupiah(item.subtotal || (qty * (parseFloat(item.harga_satuan) || 0)));

                this.bold(true);
                this.line(nama);
                this.bold(false);
                this.twoColumns(`  ${qty} x ${harga}`, subtotal);
            });
        }

        // 6. DIVIDER TOTAL
        this.dashedLine();

        // 7. TOTAL & PEMBAYARAN
        const subtotalOrder = parseFloat(order.subtotal || order.total || 0);
        const diskonTotal   = parseFloat(order.diskon_total || order.diskon || 0);
        const ppnTotal      = parseFloat(order.ppn || 0);
        const grandTotal    = parseFloat(order.total || 0);
        const nominalBayar  = parseFloat(order.bayar || order.nominal_bayar || grandTotal);
        const kembalian     = parseFloat(order.kembalian || (nominalBayar - grandTotal));
        const metodeBayar   = (order.metode_bayar || order.metode_pembayaran || 'TUNAI').toUpperCase();

        this.twoColumns('Subtotal', this.formatRupiah(subtotalOrder));
        if (diskonTotal > 0) {
            this.twoColumns('Diskon', '-' + this.formatRupiah(diskonTotal));
        }
        if (ppnTotal > 0) {
            this.twoColumns('PPN', this.formatRupiah(ppnTotal));
        }

        this.bold(true);
        this.twoColumns('TOTAL', 'Rp ' + this.formatRupiah(grandTotal));
        this.bold(false);

        this.twoColumns(`Bayar (${metodeBayar})`, this.formatRupiah(nominalBayar));
        this.bold(true);
        this.twoColumns('Kembalian', 'Rp ' + this.formatRupiah(kembalian));
        this.bold(false);

        // 8. DIVIDER FOOTER
        this.dashedLine();

        // 9. FOOTER PESAN
        this.alignCenter();
        this.line(storeFooter);
        if (storeNote) {
            this.line(storeNote);
        }

        this.feed(4);
        this.cut();
        return this.getBytes();
    }

    // 2. LABEL PENGIRIMAN / RESI MARKETPLACE
    buildShippingLabel(order, items, store = null) {
        const storeName = (store && store.name) || 'METAFORA OFFICIAL STORE';
        const storePhone = (store && store.phone) || '0812-3456-7890';
        const channel = (order.source || 'Marketplace').toUpperCase();
        const kurir = (order.kurir || 'REGULER').toUpperCase();
        const noResi = order.nomor_resi || '-';
        const noPesanan = order.no_pesanan || order.nomor_so || '-';

        this.init();

        // 1. HEADER EKSPEDISI & CHANNEL
        this.alignCenter();
        this.bold(true);
        this.doubleHeight(true);
        this.line(`[ ${kurir} ]`);
        this.doubleHeight(false);
        this.line(`${channel} ORDER`);
        this.bold(false);

        this.doubleDashedLine();

        // 2. NOMOR RESI BESAR
        this.alignCenter();
        this.line('NO. RESI:');
        this.bold(true);
        this.doubleHeight(true);
        this.line(noResi);
        this.bold(false);
        this.doubleHeight(false);
        this.line('No. Pesanan: ' + noPesanan);

        this.dashedLine();

        // 3. PENERIMA & ALAMAT
        this.alignLeft();
        this.bold(true);
        this.line('KEPADA (PENERIMA):');
        this.line(order.nama_penerima || 'Penerima');
        this.bold(false);
        if (order.no_telepon) this.line('Telp: ' + order.no_telepon);
        if (order.alamat_penerima) this.line(order.alamat_penerima);

        this.dashedLine();

        // 4. PENGIRIM
        this.bold(true);
        this.line('DARI (PENGIRIM):');
        this.line(storeName);
        this.bold(false);
        this.line('Telp: ' + storePhone);

        this.dashedLine();

        // 5. ISI PRODUK & QTY
        this.bold(true);
        this.line('RINCIAN BARANG:');
        this.bold(false);
        if (Array.isArray(items)) {
            items.forEach((item, idx) => {
                const nama = item.nama_produk_marketplace || item.nama_produk || 'Produk';
                const qty  = item.qty || 1;
                this.twoColumns(`${idx + 1}. ${nama}`, `Qty: ${qty}`);
            });
        }

        this.dashedLine();

        // 6. INFORMASI COD / NON-COD
        this.alignCenter();
        this.bold(true);
        const total = parseFloat(order.total || 0);
        this.line('TOTAL ORDER: Rp ' + this.formatRupiah(total));
        this.bold(false);
        this.line('Pesanan sudah dibayar via platform');

        this.feed(4);
        this.cut();
        return this.getBytes();
    }

    // 3. BUKTI KAS MASUK (BKM) & BUKTI KAS KELUAR (BKK)
    buildVoucher(type, data, store = null) {
        const isBKM = (type || '').toUpperCase() === 'BKM';
        const title = isBKM ? 'BUKTI KAS MASUK (BKM)' : 'BUKTI KAS KELUAR (BKK)';
        const noVoucher = isBKM ? (data.nomor_bkm || '-') : (data.nomor_bkk || '-');
        const pihak = isBKM ? (data.nama_pelanggan || 'Customer') : (data.nama_supplier || 'Vendor / Supplier');
        const labelPihak = isBKM ? 'Diterima Dari' : 'Dibayarkan Ke';
        const nominal = parseFloat(data.nominal || 0);
        const kasBank = data.nama_kas_bank || 'Kas / Bank';
        const petugas = data.petugas || data.dicatat_oleh || 'Finance';
        const metode = (data.metode_pembayaran || 'TRANSFER').toUpperCase();
        const tgl = data.tanggal_bayar || new Date().toISOString().slice(0, 10);

        this.init();

        this.alignCenter();
        this.bold(true);
        this.doubleHeight(true);
        this.line(title);
        this.bold(false);
        this.doubleHeight(false);
        this.line('METAFORA ERP KEUANGAN');

        this.doubleDashedLine();

        this.alignLeft();
        this.twoColumns('No. Bukti', noVoucher);
        this.twoColumns('Tanggal', tgl);
        this.twoColumns(labelPihak, pihak);
        this.twoColumns('Akun Kas/Bank', kasBank);
        this.twoColumns('Metode Bayar', metode);

        this.dashedLine();

        this.bold(true);
        this.twoColumns('JUMLAH DANA', 'Rp ' + this.formatRupiah(nominal));
        this.bold(false);

        if (data.catatan) {
            this.dashedLine();
            this.line('Catatan: ' + data.catatan);
        }

        this.dashedLine();

        this.alignCenter();
        this.line('Status: TERBUKUKAN & VALID');
        this.twoColumns('Petugas: ' + petugas, 'Otorisasi: VALID');

        this.feed(4);
        this.cut();
        return this.getBytes();
    }

    // 4. BUKTI RETUR PENJUALAN & PEMBELIAN
    buildRetur(type, retur, items, store = null) {
        const isPenjualan = (type || '').toLowerCase().includes('jual');
        const title = isPenjualan ? 'BUKTI RETUR PENJUALAN' : 'BUKTI RETUR PEMBELIAN';
        const pihak = isPenjualan ? (retur.nama_pelanggan || 'Pelanggan') : (retur.nama_supplier || 'Supplier');
        const noRef = isPenjualan ? (retur.nomor_so || '-') : (retur.nomor_po || '-');
        const total = parseFloat(retur.total || 0);

        this.init();

        this.alignCenter();
        this.bold(true);
        this.doubleHeight(true);
        this.line(title);
        this.bold(false);
        this.doubleHeight(false);
        this.line('METAFORA ERP SYSTEM');

        this.doubleDashedLine();

        this.alignLeft();
        this.twoColumns('No. Retur', retur.nomor_retur || '-');
        this.twoColumns('Ref No.', noRef);
        this.twoColumns('Pihak', pihak);
        this.twoColumns('Tanggal', retur.tanggal || new Date().toISOString().slice(0, 10));
        this.twoColumns('Tindak Lanjut', (retur.tindak_lanjut || '-').toUpperCase());
        if (retur.alasan) this.line('Alasan: ' + retur.alasan);

        this.dashedLine();

        this.bold(true);
        this.line('ITEM BARANG RETUR:');
        this.bold(false);
        if (Array.isArray(items)) {
            items.forEach((item, idx) => {
                const nama = item.nama_produk || 'Produk';
                const qty = item.qty || 1;
                const harga = this.formatRupiah(item.harga_satuan || 0);
                const subtotal = this.formatRupiah(item.subtotal || 0);
                this.bold(true);
                this.line(`${idx + 1}. ${nama}`);
                this.bold(false);
                this.twoColumns(`   ${qty} x ${harga}`, subtotal);
            });
        }

        this.dashedLine();

        this.bold(true);
        this.twoColumns('TOTAL RETUR', 'Rp ' + this.formatRupiah(total));
        this.bold(false);

        this.feed(4);
        this.cut();
        return this.getBytes();
    }
}

/**
 * Global Thermal Modal & Printer Controller Helper
 */
class ThermalPrintHelper {
    static formatRupiah(num) {
        return Math.round(parseFloat(num) || 0).toLocaleString('id-ID');
    }

    /**
     * Direct Thermal Browser Printing via Hidden Iframe (58mm or 80mm roll)
     */
    static printBrowserHtml(htmlContent, widthMm = 80) {
        let frame = document.getElementById('thermalPrintIframe');
        if (!frame) {
            frame = document.createElement('iframe');
            frame.id = 'thermalPrintIframe';
            frame.style.position = 'fixed';
            frame.style.right = '0';
            frame.style.bottom = '0';
            frame.style.width = '0';
            frame.style.height = '0';
            frame.style.border = '0';
            document.body.appendChild(frame);
        }

        const printableDoc = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Cetak Thermal</title>
                <style>
                    @page {
                        size: ${widthMm}mm auto;
                        margin: 0mm;
                    }
                    body {
                        font-family: 'Courier New', Courier, monospace;
                        width: ${widthMm - 4}mm;
                        margin: 0 auto;
                        padding: 6px 2px;
                        font-size: 11px;
                        color: #000;
                    }
                    .text-center { text-align: center; }
                    .text-end { text-align: right; }
                    .fw-bold { font-weight: bold; }
                    .dashed-line {
                        border-top: 1px dashed #000;
                        margin: 5px 0;
                    }
                    .double-line {
                        border-top: 2px solid #000;
                        margin: 5px 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    td {
                        padding: 2px 0;
                        vertical-align: top;
                    }
                </style>
            </head>
            <body>
                ${htmlContent}
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.focus();
                            window.print();
                        }, 250);
                    };
                </script>
            </body>
            </html>
        `;

        const frameDoc = frame.contentWindow || frame.contentDocument.document || frame.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write(printableDoc);
        frameDoc.document.close();
    }

    /**
     * Show Unified Thermal Print Modal
     */
    static showModal({ title, subtitle, previewHtml, onPrintBluetooth, onPrintSerial, onPrintBrowser }) {
        let modalEl = document.getElementById('globalThermalModal');
        if (!modalEl) {
            const modalHtml = `
                <div class="modal fade" id="globalThermalModal" tabindex="-1" aria-labelledby="globalThermalModalLabel" aria-hidden="true" style="z-index: 1090;">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content shadow-lg border-0 rounded-4">
                            <div class="modal-header bg-dark text-white py-3 px-4">
                                <div>
                                    <h6 class="modal-title fw-bold mb-0" id="globalThermalModalLabel">
                                        <i class="fa-solid fa-receipt me-2 text-warning"></i><span id="gtmTitle">Cetak Dokumen Thermal</span>
                                    </h6>
                                    <small class="text-white-50" id="gtmSubtitle">Pilih metode cetak (Bluetooth, USB/Serial, atau Browser)</small>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3 p-md-4 bg-light">
                                <!-- STATUS ALERT -->
                                <div id="gtmStatusAlert" class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3">
                                    <i class="fa-solid fa-circle-info fs-5 flex-shrink-0 text-info"></i>
                                    <div id="gtmStatusText">Siap mencetak. Pilih koneksi printer di bawah ini.</div>
                                </div>

                                <!-- RECEIPT PREVIEW CONTAINER -->
                                <div class="bg-white p-3 border rounded shadow-xs mb-3 font-monospace" style="max-height: 280px; overflow-y: auto; font-size: 11px; border: 1px dashed #cbd5e1 !important;" id="gtmPreviewArea">
                                    <!-- Dynamic Thermal Content -->
                                </div>

                                <!-- TROUBLESHOOTING ACCORDION FOR BLUETOOTH PAIRING -->
                                <div class="accordion mb-3" id="gtmAccordionTroubleshoot">
                                    <div class="accordion-item border-0 bg-white rounded shadow-xs">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-3 small fw-semibold text-danger bg-danger bg-opacity-10" type="button" data-bs-toggle="collapse" data-bs-target="#gtmCollapseHelp">
                                                <i class="fa-solid fa-circle-question me-2"></i> Muncul "Tidak ditemukan perangkat"? Cek Solusi Ini
                                            </button>
                                        </h2>
                                        <div id="gtmCollapseHelp" class="accordion-collapse collapse" data-bs-parent="#gtmAccordionTroubleshoot">
                                            <div class="accordion-body p-3 small text-secondary" style="font-size: 0.82rem; line-height: 1.5;">
                                                <ol class="ps-3 mb-2">
                                                    <li><strong>Printer Thermal ON:</strong> Pastikan printer menyala dan lampu indikator menyala/berkedip.</li>
                                                    <li><strong>Tidak Terhubung ke HP/Device Lain:</strong> Bluetooth printer portable hanya bisa terhubung ke 1 alat sekaligus. Putuskan Bluetooth di HP kasir/perangkat lain jika masih tersambung.</li>
                                                    <li><strong>Bluetooth Komputer ON:</strong> Pastikan Bluetooth di Windows / Laptop aktif.</li>
                                                    <li><strong>Gunakan USB / Serial COM:</strong> Jika printer bertipe <em>Bluetooth Classic (SPP)</em> atau dicolok via kabel USB, klik tombol <strong>"Cetak via USB / Serial"</strong> di bawah!</li>
                                                    <li><strong>Cetak Struk Browser:</strong> Solusi instan jika tanpa Bluetooth, langsung mencetak roll 58mm/80mm ke printer apapun.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAPER WIDTH SELECTOR -->
                                <div class="d-flex align-items-center justify-content-between mb-3 px-1 small">
                                    <span class="text-muted fw-semibold">Lebar Kertas Thermal:</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="gtmPaperRadio" id="gtmPaper58" value="32" autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="gtmPaper58">58mm (Mini)</label>
                                        <input type="radio" class="btn-check" name="gtmPaperRadio" id="gtmPaper80" value="48" autocomplete="off" checked>
                                        <label class="btn btn-outline-secondary" for="gtmPaper80">80mm (Standar)</label>
                                    </div>
                                </div>

                                <!-- ACTION BUTTONS -->
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" id="gtmBtnBluetooth">
                                        <i class="fa-brands fa-bluetooth-b fs-5"></i>
                                        <span>Cetak via Bluetooth Thermal</span>
                                    </button>
                                    <button type="button" class="btn btn-success fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" id="gtmBtnSerial">
                                        <i class="fa-solid fa-plug fs-5"></i>
                                        <span>Cetak via USB / Serial COM</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" id="gtmBtnBrowser">
                                        <i class="fa-solid fa-print fs-5"></i>
                                        <span>Cetak Struk Browser (Roll 58/80mm)</span>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer py-2 px-3 bg-white justify-content-between">
                                <span class="small text-muted" id="gtmDeviceStatus">Siap pairing</span>
                                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            modalEl = document.getElementById('globalThermalModal');
        }

        // Set Content
        document.getElementById('gtmTitle').textContent = title || 'Cetak Dokumen Thermal';
        document.getElementById('gtmSubtitle').textContent = subtitle || '';
        document.getElementById('gtmPreviewArea').innerHTML = previewHtml || '';
        
        const statusAlert = document.getElementById('gtmStatusAlert');
        const statusText = document.getElementById('gtmStatusText');
        const deviceStatus = document.getElementById('gtmDeviceStatus');
        const btnBt = document.getElementById('gtmBtnBluetooth');
        const btnSerial = document.getElementById('gtmBtnSerial');
        const btnBrowser = document.getElementById('gtmBtnBrowser');

        statusAlert.className = 'alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3';
        statusText.innerHTML = 'Pilih metode cetak (Bluetooth, USB/Serial, atau Browser).';
        deviceStatus.textContent = window.posBluetoothPrinter.isConnected ? ('Terhubung: ' + window.posBluetoothPrinter.deviceName) : 'Belum tersambung';

        // Paper width handlers
        const radio58 = document.getElementById('gtmPaper58');
        const radio80 = document.getElementById('gtmPaper80');
        const currentWidth = window.posBluetoothPrinter.getPaperWidth();
        if (currentWidth === 32) radio58.checked = true;
        else radio80.checked = true;

        radio58.onchange = () => window.posBluetoothPrinter.setPaperWidth(32);
        radio80.onchange = () => window.posBluetoothPrinter.setPaperWidth(48);

        // Bind Bluetooth action
        btnBt.onclick = async () => {
            try {
                btnBt.disabled = true;
                btnBt.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyandingkan Bluetooth...';
                statusAlert.className = 'alert alert-primary py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '<i class="fa-solid fa-satellite-dish fa-fade me-1"></i> Memindai printer... Pilih perangkat pada dialog browser yang muncul.';

                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'bluetooth') {
                    await window.posBluetoothPrinter.connectBluetooth();
                }

                statusText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Mengirim data dokumen ke printer thermal...';
                if (typeof onPrintBluetooth === 'function') {
                    await onPrintBluetooth();
                }

                statusAlert.className = 'alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '&#10003; Dokumen berhasil dicetak via Bluetooth!';
                deviceStatus.textContent = 'Terhubung: ' + window.posBluetoothPrinter.deviceName;
            } catch (err) {
                console.error('Bluetooth print error:', err);
                statusAlert.className = 'alert alert-danger py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '<strong>Gagal:</strong> ' + (err.message || 'Tidak dapat terhubung ke printer Bluetooth.');
                // Open help accordion automatically if pairing failed or device not found
                const helpCollapse = document.getElementById('gtmCollapseHelp');
                if (helpCollapse && !helpCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(helpCollapse, { toggle: true });
                }
            } finally {
                btnBt.disabled = false;
                btnBt.innerHTML = '<i class="fa-brands fa-bluetooth-b fs-5"></i> <span>Cetak via Bluetooth Thermal</span>';
            }
        };

        // Bind Serial action
        btnSerial.onclick = async () => {
            try {
                btnSerial.disabled = true;
                btnSerial.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memilih Port USB/Serial...';
                statusAlert.className = 'alert alert-primary py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '<i class="fa-solid fa-plug me-1"></i> Pilih Port Printer USB atau COM Bluetooth di popup...';

                if (!window.posBluetoothPrinter.isConnected || window.posBluetoothPrinter.connectionType !== 'serial') {
                    await window.posBluetoothPrinter.connectSerial();
                }

                statusText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Mengirim data ESC/POS ke port printer...';
                if (typeof onPrintSerial === 'function') {
                    await onPrintSerial();
                }

                statusAlert.className = 'alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '&#10003; Dokumen berhasil dicetak via USB / Serial COM!';
                deviceStatus.textContent = 'Terhubung: ' + window.posBluetoothPrinter.deviceName;
            } catch (err) {
                console.error('Serial print error:', err);
                statusAlert.className = 'alert alert-danger py-2 px-3 small d-flex align-items-center gap-2 mb-3';
                statusText.innerHTML = '<strong>Gagal:</strong> ' + (err.message || 'Tidak dapat membuka port USB/Serial.');
            } finally {
                btnSerial.disabled = false;
                btnSerial.innerHTML = '<i class="fa-solid fa-plug fs-5"></i> <span>Cetak via USB / Serial COM</span>';
            }
        };

        // Bind Browser action
        btnBrowser.onclick = () => {
            if (typeof onPrintBrowser === 'function') {
                onPrintBrowser();
            } else {
                const widthMm = window.posBluetoothPrinter.getPaperWidth() === 32 ? 58 : 80;
                ThermalPrintHelper.printBrowserHtml(previewHtml, widthMm);
            }
        };

        // Show Modal
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }
}

// Global Singleton Instance
window.posBluetoothPrinter = new BluetoothThermalPrinter();
window.ThermalPrintHelper = ThermalPrintHelper;
