<?php
require_once __DIR__ . '/../modules/PenjualanMarketplace/Services/ResiParserService.php';

// Stub log_message for CLI test environment (not available outside CodeIgniter)
if (!function_exists('log_message')) {
    function log_message(string $level, string $message): void {}
}

use Modules\PenjualanMarketplace\Services\ResiParserService;

class SimpleTestRunner {
    private ResiParserService $parser;
    public int $passed = 0;
    public int $failed = 0;

    public function __construct() {
        $this->parser = new ResiParserService();
    }

    private function assertSame($expected, $actual, string $msg = '') {
        if ($expected !== $actual) {
            throw new Exception("Expected: " . var_export($expected, true) . ", Got: " . var_export($actual, true) . ". " . $msg);
        }
    }

    private function assertTrue($actual, string $msg = '') {
        if ($actual !== true) {
            throw new Exception("Expected true, Got: " . var_export($actual, true) . ". " . $msg);
        }
    }

    private function assertFalse($actual, string $msg = '') {
        if ($actual !== false) {
            throw new Exception("Expected false, Got: " . var_export($actual, true) . ". " . $msg);
        }
    }

    private function assertEquals($expected, $actual, string $msg = '') {
        if (abs((float)$expected - (float)$actual) > 0.001) {
            throw new Exception("Expected: $expected, Got: $actual. " . $msg);
        }
    }

    private function assertCount(int $expected, $countable, string $msg = '') {
        $count = count($countable);
        if ($count !== $expected) {
            throw new Exception("Expected count $expected, Got count $count. " . $msg);
        }
    }

    private function assertStringContainsString(string $needle, string $haystack, string $msg = '') {
        if (!str_contains($haystack, $needle)) {
            throw new Exception("Expected '$haystack' to contain '$needle'. " . $msg);
        }
    }

    private function assertStringNotContainsString(string $needle, string $haystack, string $msg = '') {
        if (str_contains($haystack, $needle)) {
            throw new Exception("Expected '$haystack' to NOT contain '$needle'. " . $msg);
        }
    }

    private function assertNotSame($expected, $actual, string $msg = '') {
        if ($expected === $actual) {
            throw new Exception("Expected not same as: " . var_export($expected, true) . ". " . $msg);
        }
    }

    private function assertGreaterThanOrEqual($expected, $actual, string $msg = '') {
        if ($actual < $expected) {
            throw new Exception("Expected $actual >= $expected. " . $msg);
        }
    }

    public function runAll() {
        $methods = get_class_methods($this);
        foreach ($methods as $m) {
            if (str_starts_with($m, 'test')) {
                try {
                    $this->$m();
                    $this->passed++;
                    echo "  \e[32m✔\e[0m {$m}\n";
                } catch (\Throwable $e) {
                    $this->failed++;
                    echo "  \e[31m✘\e[0m {$m}: " . $e->getMessage() . "\n";
                }
            }
        }
        echo "\nTotal Tests: " . ($this->passed + $this->failed) . " | Passed: {$this->passed} | Failed: {$this->failed}\n";
    }

    // --- TEST CASES ---

    public function testParsesSpxLabel(): void {
        $rawText = "Shopee Xpress Standard\nNo. Resi: SPXID0293847562\nPenerima: Ahmad Dahlan\nKota: Bandung";
        $result = $this->parser->parse($rawText);
        $this->assertSame('SPX Express', $result['courier']);
        $this->assertSame('SPXID0293847562', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Ahmad Dahlan', $result['recipient_name']);
    }

    public function testParsesJntLabel(): void {
        $rawText = "J&T Express EZ\nNo. Resi: JP12345678901\nKepada: Budi Prasetyo\nKota: Surabaya";
        $result = $this->parser->parse($rawText);
        $this->assertSame('J&T Express', $result['courier']);
        $this->assertSame('JP12345678901', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Budi Prasetyo', $result['recipient_name']);
    }

    public function testParsesJneLabel(): void {
        $rawText = "JNE REG\nNo Resi: JNE012345678901\nPenerima: Siti Aminah\nJakarta";
        $result = $this->parser->parse($rawText);
        $this->assertSame('JNE', $result['courier']);
        $this->assertSame('JNE012345678901', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Siti Aminah', $result['recipient_name']);
    }

    public function testParsesFullShopeeSpxLabelWithPhoneAddressAndSku(): void {
        $rawText = "
            Shopee Xpress Standard
            No. Resi: SPXID0482910392
            No. Pesanan: 241030ABCDEF12
            Penerima: Hendra Wijaya
            Telepon: 0812-3456-7890
            Alamat: Jl. Sudirman No. 45, Gondokusuman, Kota Yogyakarta
            SKU: PRD-0001
            Qty: 2
            COD: Rp 175.000
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('SPX Express', $result['courier']);
        $this->assertSame('SPXID0482910392', $result['tracking_number']);
        $this->assertSame('shopee', $result['source']);
        $this->assertSame('241030ABCDEF12', $result['no_pesanan']);
        $this->assertSame('Hendra Wijaya', $result['recipient_name']);
        $this->assertSame('081234567890', $result['recipient_phone']);
        $this->assertSame('PRD-0001', $result['sku']);
        $this->assertSame(2, $result['qty']);
        $this->assertEquals(175000.0, $result['total']);
        $this->assertGreaterThanOrEqual(0.9, $result['confidence']);
    }

    public function testParsesFullTikTokShopLabel(): void {
        $rawText = "
            TikTok Shop - J&T Express
            Tracking No: JP887766554433
            Order ID: 57819283746501928
            Kepada: Dewi Anggraeni
            WA: +62857-1122-3344
            Kota: Surabaya
            Total Tagihan: Rp 220.000
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('J&T Express', $result['courier']);
        $this->assertSame('JP887766554433', $result['tracking_number']);
        $this->assertSame('tiktok', $result['source']);
        $this->assertSame('57819283746501928', $result['no_pesanan']);
        $this->assertSame('Dewi Anggraeni', $result['recipient_name']);
        $this->assertSame('085711223344', $result['recipient_phone']);
        $this->assertEquals(220000.0, $result['total']);
    }

    public function testParsesRealShopeeSpxLabelWithIdTrackingAndMergedColumns(): void {
        $rawText = "
            Shopee STD SPX Express
            AL - 18 AJW-C-01 | No. Resi: ID25838628439GK
            Penerima: Ujang Sound Pengirim: Elen Purnama Sari
            Pangkalan Opak, Jalan H Ismail, RT.1/RW.1, 6282180944685
            Prajawinangun Kulon, Kaliwedi (Blok pangkalan KAB. BENGKULU SEL-
            opak), KALIWEDI, KAB. CIREBON, JAWA ATAN
            BARAT
            KAB. CIREBON | KALIWEDI
            CASHLESS Pemilik tidak perlu bayar ongkir ke Kurir
            Berat: 400 gr No. Pesanan: 2505220HM1MB0GK
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('SPX Express', $result['courier']);
        $this->assertSame('ID25838628439GK', $result['tracking_number']);
        $this->assertSame('shopee', $result['source']);
        $this->assertSame('2505220HM1MB0GK', $result['no_pesanan']);
        $this->assertSame('Ujang Sound', $result['recipient_name']);
        $this->assertSame('', $result['recipient_phone']);
        $this->assertSame('082180944685', $result['sender_phone']);
        $this->assertSame('Cirebon', $result['city']);
        $this->assertStringContainsString('Pangkalan Opak, Jalan H Ismail', $result['recipient_address']);
        $this->assertStringNotContainsString('Elen Purnama Sari', $result['recipient_name']);
        $this->assertGreaterThanOrEqual(0.9, $result['confidence']);
    }

    public function testParsesOcrNoisyTrackingNumberWithPipeOrOnePrefix(): void {
        $rawText = "
            Shopee Xpress
            No. Resi: |D25838628439GK
            Order ID: 2505220HM1MB0GK
            Penerima: Siti Aminah
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('SPX Express', $result['courier']);
        $this->assertSame('ID25838628439GK', $result['tracking_number']);
        $this->assertSame('Siti Aminah', $result['recipient_name']);
    }

    public function testParseBatchMultiLabelsFromMultiPagePdf(): void {
        $multiPageText = "
            --- PAGE 1 ---
            Shopee STD SPX Express
            No. Resi: ID25838628439GK
            Order ID: 2505220HM1MB0GK
            Penerima: Ujang Sound
            Kota: Cirebon

            --- PAGE 2 ---
            J&T Express EZ
            No. Resi: JP12345678901
            Order ID: 241102ABCD1234
            Penerima: Budi Santoso
            Kota: Yogyakarta
        ";
        $batch = $this->parser->parseBatch($multiPageText);
        $this->assertTrue($batch['is_batch']);
        $this->assertSame(2, $batch['count']);
        $this->assertSame('ID25838628439GK', $batch['items'][0]['tracking_number']);
        $this->assertSame('Ujang Sound', $batch['items'][0]['recipient_name']);
        $this->assertSame('JP12345678901', $batch['items'][1]['tracking_number']);
        $this->assertSame('Budi Santoso', $batch['items'][1]['recipient_name']);
    }

    public function testParsesProductTableWithSkuVariasiAndQtyFromShopee(): void {
        $rawText = "
            Shopee Xpress Standard
            No. Resi: SPXID0419283746
            Penerima: Santi Melati
            Alamat: Jl. Melati No. 15, RT 09/02, Ciawi, Bogor
            Telepon: 081398765432

            Nama Produk | SKU | Variasi | Qty
            KODE CO LIVE 1-50 CROP REWORK hiiyoustore | Link CO 1-50 | 20 | 1
            pesan: (2403264080DMO5)
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('Link CO 1-50', $result['sku']);
        $this->assertSame('KODE CO LIVE 1-50 CROP REWORK hiiyoustore', $result['product_name']);
        $this->assertSame('20', $result['variasi']);
        $this->assertSame(1, $result['qty']);
        $this->assertSame('2403264080DMO5', $result['no_pesanan']);
        $this->assertCount(1, $result['items']);
        $this->assertSame('Link CO 1-50', $result['items'][0]['sku']);
    }

    public function testParsesCodNominalAccurately(): void {
        $rawText1 = "Shopee Xpress\nNo. Resi: SPXID0123\nCOD: Rp41040\nPenerima: Siti";
        $r1 = $this->parser->parseFull($rawText1);
        $this->assertEquals(41040.0, $r1['total']);
        $this->assertTrue($r1['is_cod']);

        $rawText2 = "J&T Express\nNo. Resi: JP0987\nCOD: Rp 41.040,00\nPenerima: Budi";
        $r2 = $this->parser->parseFull($rawText2);
        $this->assertEquals(41040.0, $r2['total']);
        $this->assertTrue($r2['is_cod']);
    }

    public function testParsesAndIsolatesSenderDataSeparately(): void {
        $rawText = "
            Shopee Xpress Standard
            No. Resi: SPXID0419283746
            Penerima: Santi Melati Pengirim: hiiyou Store
            Jl. Melati No. 15, RT 09 87, 6285163646980
            Kecamatan Ciawi, KAB. BOGOR KOTA BANDUNG
            081398765432

            COD: Rp41040

            Nama Produk | SKU | Variasi | Qty
            KODE CO LIVE 1-50 CROP REWORK hiiyoustore | Link CO 1-50 | 20 | 1
            pesan: (2403264080DMO5)
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('hiiyou Store', $result['sender_name']);
        $this->assertSame('085163646980', $result['sender_phone']);
        $this->assertSame('Bandung', $result['sender_city']);
        $this->assertSame('Santi Melati', $result['recipient_name']);
        $this->assertSame('081398765432', $result['recipient_phone']);
        $this->assertNotSame('085163646980', $result['recipient_phone']);
        $this->assertSame('Bogor', $result['city']);
        $this->assertStringNotContainsString('hiiyou Store', $result['recipient_name']);
        $this->assertStringNotContainsString('6285163646980', $result['recipient_address']);
        $this->assertStringNotContainsString('KOTA BANDUNG', $result['recipient_address']);
    }

    public function testParsesRecipientWithoutPhoneLeavesPhoneEmpty(): void {
        $rawText = "
            Shopee Xpress Standard
            No. Resi: SPXID99887766
            Penerima: Hendra Wijaya
            Jl. Melati No. 5, KOTA BANDUNG
            Pengirim: Toko Sepatu Jaya
            08123456789
            Jakarta

            COD: Rp150000
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertSame('', $result['recipient_phone']);
        $this->assertSame('08123456789', $result['sender_phone']);
        $this->assertSame('Hendra Wijaya', $result['recipient_name']);
    }

    public function testParsesMultiProductLabelWithTotalQty(): void {
        $rawText = "
            Shopee Xpress Standard
            No. Resi: SPXID11223344
            Penerima: Rina Melati
            Jl. Kenanga No. 12, Surabaya
            087712345678

            CASHLESS

            Nama Produk | SKU | Variasi | Qty
            Kemeja Polos Formal | KMJ-01 | Putih L | 2
            Celana Chino Pria | CHN-02 | Khaki 32 | 1
            Kaos Polos Cotton | KOS-03 | Hitam XL | 3
            pesan: (241103ABCDEF12)
        ";
        $result = $this->parser->parseFull($rawText);
        $this->assertFalse($result['is_cod']);
        $this->assertCount(3, $result['items']);
        $this->assertSame(6, $result['qty']);
        $this->assertSame('KMJ-01', $result['items'][0]['sku']);
        $this->assertSame(2, $result['items'][0]['qty']);
        $this->assertSame('CHN-02', $result['items'][1]['sku']);
        $this->assertSame(1, $result['items'][1]['qty']);
        $this->assertSame('KOS-03', $result['items'][2]['sku']);
        $this->assertSame(3, $result['items'][2]['qty']);
    }

    public function testParsesUserImageCropSpxResiVariations(): void {
        // Direct OCR output from user image
        $raw1 = "No.F\nResi:\nSPXID042978248603";
        $r1 = $this->parser->parseFull($raw1);
        $this->assertSame('SPX Express', $r1['courier']);
        $this->assertSame('SPXID042978248603', $r1['tracking_number']);

        // Box border & space variation
        $raw2 = "No. Resi: [SPXID 042978248603]";
        $r2 = $this->parser->parseFull($raw2);
        $this->assertSame('SPX Express', $r2['courier']);
        $this->assertSame('SPXID042978248603', $r2['tracking_number']);

        // Letter O typo in digits
        $raw3 = "No. Resi: SPXID0429782486O3";
        $r3 = $this->parser->parseFull($raw3);
        $this->assertSame('SPX Express', $r3['courier']);
        $this->assertSame('SPXID042978248603', $r3['tracking_number']);

        // Underscore or hyphen
        $raw4 = "No. Resi: SPXID_042978248603";
        $r4 = $this->parser->parseFull($raw4);
        $this->assertSame('SPX Express', $r4['courier']);
        $this->assertSame('SPXID042978248603', $r4['tracking_number']);

        // Standalone tracking code without 'No. Resi' label
        $raw5 = "SPXID042978248603";
        $r5 = $this->parser->parseFull($raw5);
        $this->assertSame('SPX Express', $r5['courier']);
        $this->assertSame('SPXID042978248603', $r5['tracking_number']);
    }

    public function testClientJsOcrTextParsingOnUserImage(): void {
        // Simulating the text extracted by client-side JavaScript (Tesseract.js / BarcodeDetector) on user's SPX resi image
        $jsExtractedText = "No. Resi: SPXID042978248603\nSPX Express Standard\nShopee";
        $parsed = $this->parser->parseFull($jsExtractedText);
        $this->assertSame('SPX Express', $parsed['courier']);
        $this->assertSame('SPXID042978248603', $parsed['tracking_number']);
    }

    public function testRejectsSpxPenerimaOrInvalidKeywordsAsTrackingNumber(): void {
        // Text where SPX and Penerima appear near each other without digits
        $noisyText = "Shopee STD SPX\nPenerima: Aldi ardiansyah\nPengirim: hiiyou Store\nCOD: Rp41040";
        $parsed = $this->parser->parseFull($noisyText);
        $this->assertSame('SPX Express', $parsed['courier']);
        $this->assertNotSame('SPXPENERIMA', $parsed['tracking_number']);
        $this->assertSame('', $parsed['tracking_number']);
        $this->assertSame('Aldi ardiansyah', $parsed['recipient_name']);
    }
    public function testPurePhpGdCode128DecoderOnUserImage(): void {
        $imgPath = 'C:/Users/Lenovo/.gemini/antigravity/brain/500a4c5b-1c38-400d-b666-8a79ae0928bc/.user_uploaded/media_1794018053013_a3dd28d6.webp';
        if (!file_exists($imgPath)) {
            return;
        }

        $decodedBarcode = $this->parser->decodeCode128FromImage($imgPath);
        $this->assertSame('SPXID042978248603', $decodedBarcode, 'Direct Code 128 barcode decoding on user image');

        $extractedText = $this->parser->extractTextFromImage($imgPath);
        $this->assertStringContainsString('SPXID042978248603', $extractedText);
    }

    public function testExtractsBatasKirimAsTanggalPesanan(): void {
        $text = "Shopee STD SPX Express\nNo. Resi: SPXID042978248603\nPenerima: Aldi ardiansyah\nBerat: 400 gr   COD: Rp41040\nBatas Kirim: 28-03-2024\nNo. Pesanan: 2403264089DMQ5";
        $parsed = $this->parser->parseFull($text);

        $this->assertSame('2024-03-28', $parsed['tanggal'], 'Tanggal pesanan harus menggunakan batas kirim');
        $this->assertSame('2024-03-28', $parsed['batas_kirim']);
        $this->assertSame('SPXID042978248603', $parsed['tracking_number']);
        $this->assertSame(41040.0, $parsed['total']);
        $this->assertTrue($parsed['is_cod']);
    }

    public function testExtractsVariousBatasKirimFormats(): void {
        $variants = [
            "Batas Kirim: 28/03/2024" => "2024-03-28",
            "Batas Pengiriman: 05-04-2024" => "2024-04-05",
            "Kirim Sebelum: 12-11-2024" => "2024-11-12",
            "Ship by date: 2024-05-20" => "2024-05-20",
            "Batas Kirim: 15 Maret 2024" => "2024-03-15",
            "Batas Kirim:\n28-03-2024" => "2024-03-28",
            "Tanggal Pesanan: 10-02-2024" => "2024-02-10",
            "Batas Klrim: 28-03-2024" => "2024-03-28",
        ];

        foreach ($variants as $input => $expected) {
            $extracted = $this->parser->extractBatasKirim($input);
            $this->assertSame($expected, $extracted, "Format $input harus menghasilkan $expected");
        }

        // Test fallback to today's date when label has no date
        $noDateText = "Shopee STD SPX\nNo. Resi: SPXID123456789";
        $parsedNoDate = $this->parser->parseFull($noDateText);
        $this->assertSame(date('Y-m-d'), $parsedNoDate['tanggal']);
        $this->assertSame(null, $parsedNoDate['batas_kirim']);
    }

    public function testIgnoresHomeBoxInShopeeSpxLabel(): void {
        $rawText = "
            Shopee ECO SPX Express
            AG-8 SWO-C-03 Resi: SPXID03873198928B
            Penerima: Abrori Pengirim: MAHSHUN STORE
            [ HOME ] 6282257189117
            Ponpen Raudlatul Ulum, Jalan Pp Raudlatul Ulum, Suren, Ledokombo, LEDOKOMBO, KAB. JEMBER
            KAB. JEMBER, JAWA TIMUR
            KAB. JEMBER | LEDOKOMBO | Suren
            Berat: 4293 gr COD: Rp427816 COD Cek Dulu: Tidak
            Batas Kirim: 28-11-2023
            No. Pesanan: 231125GSSBFEUS
            # Nama Produk SKU Variasi Qty
            1 ALMUKHTAR Terjemah Kitab FATHUL QORIB Makna Perkata Bahasa Indonesia 9
        ";

        $parsed = $this->parser->parseFull($rawText);
        $this->assertSame('SPXID03873198928B', $parsed['tracking_number']);
        $this->assertSame('Abrori', $parsed['recipient_name']);
        $this->assertStringNotContainsString('HOME', $parsed['recipient_name']);
        $this->assertStringNotContainsString('HOME', $parsed['recipient_address']);
        $this->assertStringContainsString('Ponpen Raudlatul Ulum', $parsed['recipient_address']);
        $this->assertSame('Jember', $parsed['city']);
        $this->assertSame('2023-11-28', $parsed['tanggal']);
        $this->assertSame(427816.0, $parsed['total']);
        $this->assertTrue($parsed['is_cod']);
    }

    public function testIgnoresHomeWhenPenerimaOnSeparateLine(): void {
        $rawText = "
            Shopee ECO SPX Express
            Resi: SPXID03873198928B
            Penerima:
            HOME
            Abrori
            Ponpen Raudlatul Ulum, Suren, Ledokombo, Jember
        ";

        $parsed = $this->parser->parseFull($rawText);
        $this->assertSame('Abrori', $parsed['recipient_name'], 'Nama penerima tidak boleh membaca kotak HOME');
        $this->assertStringNotContainsString('HOME', $parsed['recipient_address']);
        $this->assertStringNotContainsString('Abrori', $parsed['recipient_address']);
        $this->assertStringContainsString('Ponpen Raudlatul Ulum', $parsed['recipient_address']);
    }

    public function testParsesStandaloneSkuVariasiAndQtyFromUserShopeeLabelCrop(): void {
        $rawText = "
            Shopee STD SPX Express
            No. Resi: SPXID042978248603
            SKU Variasi Qty
            Link CO 1-50 20 1
            Pesan: (2403264089DMQ5)
        ";

        $parsed = $this->parser->parseFull($rawText);
        $this->assertSame('Link CO 1-50', $parsed['sku']);
        $this->assertSame('20', $parsed['variasi']);
        $this->assertSame(1, $parsed['qty']);
        $this->assertCount(1, $parsed['items']);
        $this->assertSame('Link CO 1-50', $parsed['items'][0]['sku']);
        $this->assertSame('20', $parsed['items'][0]['variasi']);
        $this->assertSame(1, $parsed['items'][0]['qty']);
    }

    public function testParsesFullShopeeProductRowWithNumberedIndex(): void {
        $rawText = "
            Shopee STD SPX Express
            No. Resi: SPXID042978248603
            # Nama Produk SKU Variasi Qty
            1 KODE CO LIVE 1-50 CROP REWORK hiiyoustore Link CO 1-50 20 1
            Pesan: (2403264089DMQ5)
        ";

        $parsed = $this->parser->parseFull($rawText);
        $this->assertSame('Link CO 1-50', $parsed['sku']);
        $this->assertSame('20', $parsed['variasi']);
        $this->assertSame(1, $parsed['qty']);
        $this->assertSame('KODE CO LIVE 1-50 CROP REWORK hiiyoustore', $parsed['product_name']);
        $this->assertCount(1, $parsed['items']);
        $this->assertSame('Link CO 1-50', $parsed['items'][0]['sku']);
        $this->assertSame('20', $parsed['items'][0]['variasi']);
        $this->assertSame(1, $parsed['items'][0]['qty']);
    }

    public function testParsesShopeeInstantLabelWithOrderNumberTrackingAndMultiLineItems(): void {
        $rawText = "
            Shopee INSTANT SPX Express
            No. Pesanan: 240616614JWWV6 Kode Pengambilan: 09Z3
            Penerima: Fahmi Ilmawan Pengirim: Dina Qoyimah
            247 Patisserie, Jalan Karang Ploso No. 8, 6287838877288
            Gempol (247 patisserie), DEPOK, KAB. KAB. SLEMAN
            SLEMAN, DI YOGYAKARTA
            KAB. SLEMAN | DEPOK
            CASHLESS | Penjual tidak perlu bayar ongkir ke Kurir
            Berat: 200 gr COD:Rp0
            Batas Kirim:19-06-2024
            No. Pesanan: 240616614JWWV6
            # Nama Produk SKU Variasi Qty
            1 Setelan Anak Malioboro Jogja Rawis Riwis B pymanak-rawis- Putih Variasi Abu, 1
            arong Laki Perempuan Lucu Unik Nyaman 2t putihvariasiabu- L
            h 3th 4th 5th L
            2 Setelan Anak Malioboro Jogja Rawis Riwis B pymanak-rawis- Biru,M 1
            arong Laki Perempuan Lucu Unik Nyaman 2t biru-M
            h 3th 4th 5th
            Pesan: 240616614JWWV6
        ";

        $parsed = $this->parser->parseFull($rawText);

        $this->assertSame('SPX Instant', $parsed['courier']);
        $this->assertSame('240616614JWWV6', $parsed['tracking_number']);
        $this->assertSame('240616614JWWV6', $parsed['no_pesanan']);
        $this->assertSame('Fahmi Ilmawan', $parsed['recipient_name']);
        $this->assertSame('', $parsed['recipient_phone']);
        $this->assertSame('Dina Qoyimah', $parsed['sender_name']);
        $this->assertSame('087838877288', $parsed['sender_phone']);
        $this->assertSame('Kab. Sleman', $parsed['sender_city']);
        $this->assertSame(false, $parsed['is_cod']);
        $this->assertSame(0.0, $parsed['total']);
        $this->assertSame('2024-06-19', $parsed['batas_kirim']);
        $this->assertSame('2024-06-19', $parsed['tanggal']);

        $this->assertCount(2, $parsed['items']);
        // Item 1
        $this->assertSame('pymanak-rawis-putihvariasiabu-L', $parsed['items'][0]['sku']);
        $this->assertSame('Putih Variasi Abu, L', $parsed['items'][0]['variasi']);
        $this->assertSame(1, $parsed['items'][0]['qty']);
        $this->assertSame('Setelan Anak Malioboro Jogja Rawis Riwis Barong Laki Perempuan Lucu Unik Nyaman 2th 3th 4th 5th', $parsed['items'][0]['nama_produk']);

        // Item 2
        $this->assertSame('pymanak-rawis-biru-M', $parsed['items'][1]['sku']);
        $this->assertSame('Biru, M', $parsed['items'][1]['variasi']);
        $this->assertSame(1, $parsed['items'][1]['qty']);
        $this->assertSame('Setelan Anak Malioboro Jogja Rawis Riwis Barong Laki Perempuan Lucu Unik Nyaman 2th 3th 4th 5th', $parsed['items'][1]['nama_produk']);
    }

    public function testShopeeCashlessAndCodZeroParsedAsNonCod(): void {
        $rawText = "
            Shopee INSTANT
            No. Pesanan: 240616614JWWV6
            CASHLESS | Penjual tidak perlu bayar ongkir ke Kurir
            Berat: 200 gr COD: Rp0
        ";

        $parsed = $this->parser->parseFull($rawText);
        $this->assertSame(false, $parsed['is_cod']);
        $this->assertSame(0.0, $parsed['total']);
    }

    public function testParsesTikTokShopJntExpressCodLabel(): void {
        $rawText = "
            J&T EXPRESS (021) 80661888
            EZ
            Pengirim : Oui & Co (+62)81913131345
            DKI JAKARTA, JAKARTA

            Penerima : sarwendah (+62)85647643850
            JAWA TENGAH, SUKOHARJO, GROGOL-SUJ, nusupan RT 03/5

            COD : 51.500 0.200 KG BULANAN Ship : 23-04-2024
            TOTAL Biaya Jumlah : 1pcs, Barang : 40K
            15.500
            Sudah Termasuk PPN
            COD
            SOC-SUJ09-08
            JX0350605643
            Syarat dan ketentuan pengiriman dapat dilihat pada website www.jet.co.id
            TT Order Id : 576809899405051391

            In transit by: 24/04/2024 23:59
            Product Name SKU Seller SKU Qty
            Oui & Co - Link Gelang 40K 1
            Handmade
            Qty Total: 1

            Shop | tokopedia
            Order ID: 576809899405051391
            Package ID: 1153462933021492735
        ";

        $parsed = $this->parser->parseFull($rawText);

        $this->assertSame('J&T Express', $parsed['courier']);
        $this->assertSame('JX0350605643', $parsed['tracking_number']);
        $this->assertSame('tiktok', $parsed['source']);
        $this->assertSame('576809899405051391', $parsed['no_pesanan']);
        $this->assertSame('sarwendah', $parsed['recipient_name']);
        $this->assertSame('085647643850', $parsed['recipient_phone']);
        $this->assertSame('Sukoharjo', $parsed['city']);
        $this->assertSame('Oui & Co', $parsed['sender_name']);
        $this->assertSame('081913131345', $parsed['sender_phone']);
        $this->assertSame('Jakarta', $parsed['sender_city']);
        $this->assertSame(true, $parsed['is_cod']);
        $this->assertEquals(51500.0, $parsed['total']);
        $this->assertSame('40K', $parsed['sku']);
        $this->assertSame('Oui & Co - Link Gelang Handmade', $parsed['product_name']);
        $this->assertSame(1, $parsed['qty']);
        $this->assertCount(1, $parsed['items']);
        $this->assertSame('Oui & Co - Link Gelang Handmade', $parsed['items'][0]['nama_produk']);
        $this->assertSame('40K', $parsed['items'][0]['sku']);
        $this->assertSame(1, $parsed['items'][0]['qty']);
        $this->assertSame('2024-04-23', $parsed['tanggal']);
        $this->assertSame('2024-04-24', $parsed['batas_kirim']);
    }

    public function testParsesTikTokShopCod129376Label(): void {
        $rawText = "
            J&T EXPRESS (021) 80661888
            EZ
            Pengirim : enef apparel, (+62)87802302696
            JAWA BARAT, PURWAKARTA

            Penerima : Dwi khoerunisa, (+62)85559268496
            JAWA BARAT, PURWAKARTA, PURWAKARTA-PWK, Patokan nya rumah teh adah Rt rw 09/04

            COD : 129.376 1.000 KG BULANAN Ship : 24-03-2023
            TOTAL Biaya Jumlah : 1pcs, Barang : hijau
            IDR 9.376
            Sudah Termasuk PPN
            COD
            CKP-PWK10
            JX1661644970
            Syarat dan ketentuan pengiriman dapat dilihat pada website www.jet.co.id
            TT Order Id:577130113747225090

            In transit by: 31/03/2023 16:59:59
            Product Name SKU[Seller SKU] Qty
            jersey casual enefans series 1 Pria hijau 1
            TikTok Shop Total 1 Order ID: 577130113747225090
            Package ID: 1153175108031908354
        ";

        $parsed = $this->parser->parseFull($rawText);

        $this->assertSame('J&T Express', $parsed['courier']);
        $this->assertSame('JX1661644970', $parsed['tracking_number']);
        $this->assertSame('tiktok', $parsed['source']);
        $this->assertSame('577130113747225090', $parsed['no_pesanan']);
        $this->assertSame('Dwi khoerunisa', $parsed['recipient_name']);
        $this->assertSame('085559268496', $parsed['recipient_phone']);
        $this->assertSame('Purwakarta', $parsed['city']);
        $this->assertSame('enef apparel', $parsed['sender_name']);
        $this->assertSame('087802302696', $parsed['sender_phone']);
        $this->assertSame('Purwakarta', $parsed['sender_city']);
        $this->assertSame(true, $parsed['is_cod']);
        $this->assertEquals(129376.0, $parsed['total']);
        $this->assertSame('hijau', $parsed['sku']);
        $this->assertSame('jersey casual enefans series 1 Pria', $parsed['product_name']);
        $this->assertSame(1, $parsed['qty']);
        $this->assertCount(1, $parsed['items']);
        $this->assertSame('jersey casual enefans series 1 Pria', $parsed['items'][0]['nama_produk']);
        $this->assertSame('hijau', $parsed['items'][0]['sku']);
        $this->assertSame(1, $parsed['items'][0]['qty']);
        $this->assertEquals(129376.0, $parsed['items'][0]['harga_satuan']);
        $this->assertSame('2023-03-24', $parsed['tanggal']);
        $this->assertSame('2023-03-31', $parsed['batas_kirim']);
    }

    public function testParsesOcrVariationsOfCodNominal(): void {
        $variations = [
            'COD : 129.376',
            'COD: 129.376',
            'COD 129.376',
            'COD : 129 376',
            'CoD; 129.376',
            'COO: 129.376',
            'Con; 129.376',
            'C0D : 129.376',
            "COD\n129.376",
            "COD\n129 376",
            "COD : 129.376\nTOTAL Biaya\nIDR 9.376\nSudah Termasuk PPN\nCOD",
            "TOTAL Biaya IDR 9.376\nCOO: 129.376",
        ];

        foreach ($variations as $var) {
            $parsed = $this->parser->parseFull($var);
            $this->assertSame(true, $parsed['is_cod'], "Failed is_cod for: $var");
            $this->assertEquals(129376.0, $parsed['total'], "Failed total for: $var");
        }
    }

    public function testParsesMixedCouriersFromMultiPageBatch(): void {
        $batchText = "
--- PAGE 1 ---
No. Resi: SPXID048912345678
Shopee
Penerima: Wayan Sukarta, 081234567890
KABUPATEN
BADUNG
Pengirim: Nila Bariul Nikmah, 087812345678
KAB. TULUNGAGUNG
No. Pesanan: 241028ABCDEFGH
Baju Kaos Pria Hitam L 1

--- PAGE 2 ---
No. Resi: JP1234567890
J&T Express
Penerima: Siti Aminah, 085678901234
KOTA SURABAYA
Pengirim: Nila Bariul Nikmah, KAB. TULUNGAGUNG
COD: 150.000
Gamis Cantik Merah XL 1

--- PAGE 3 ---
No. Resi: JNE100234567890
JNE Express
Penerima: Made Suardana, 087712345678
KABUPATEN GIANYAR
Pengirim: Nila Bariul Nikmah, KAB. TULUNGAGUNG
No. Pesanan: 241029XYZWVUTS
Celana Chino Krem 32 2

--- PAGE 4 ---
No. Resi: 004123456789
SiCepat Express
Penerima: Ahmad Fauzi, 081987654321
KOTA BANDUNG
Pengirim: Nila Bariul Nikmah, KAB. TULUNGAGUNG
Jaket Hoodie Abu M 1

--- PAGE 5 ---
No. Resi: POS16987654321
Pos Indonesia
Penerima: Kadek Ariawan, 082134567890
KABUPATEN TABANAN
Pengirim: Nila Bariul Nikmah, KAB. TULUNGAGUNG
Kemeja Flanel Navy L 1
";

        $batch = $this->parser->parseBatch($batchText);
        $this->assertSame(true, $batch['is_batch']);
        $this->assertSame(5, $batch['count']);

        // Check couriers inferred accurately
        $this->assertSame('SPX Express', $batch['items'][0]['courier']);
        $this->assertSame('SPXID048912345678', $batch['items'][0]['tracking_number']);
        $this->assertSame('Badung', $batch['items'][0]['city']);
        $this->assertSame('Tulungagung', $batch['items'][0]['sender_city']);

        $this->assertSame('J&T Express', $batch['items'][1]['courier']);
        $this->assertSame('JP1234567890', $batch['items'][1]['tracking_number']);
        $this->assertSame(true, $batch['items'][1]['is_cod']);
        $this->assertEquals(150000.0, $batch['items'][1]['total']);

        $this->assertSame('JNE', $batch['items'][2]['courier']);
        $this->assertSame('JNE100234567890', $batch['items'][2]['tracking_number']);
        $this->assertSame('Gianyar', $batch['items'][2]['city']);

        $this->assertSame('SiCepat', $batch['items'][3]['courier']);
        $this->assertSame('004123456789', $batch['items'][3]['tracking_number']);
        $this->assertSame('Bandung', $batch['items'][3]['city']);

        $this->assertSame('Pos Indonesia', $batch['items'][4]['courier']);
        $this->assertSame('POS16987654321', $batch['items'][4]['tracking_number']);
        $this->assertSame('Tabanan', $batch['items'][4]['city']);
    }

    public function testExtractTextFromThermalPdfBatch() {
        $pdfPath = 'C:/Users/Lenovo/.gemini/antigravity/brain/500a4c5b-1c38-400d-b666-8a79ae0928bc/.user_uploaded/media_1794118300988_68c04717.pdf';
        if (!file_exists($pdfPath)) {
            return;
        }

        $text = $this->parser->extractTextFromPdf($pdfPath);
        $this->assertTrue(strlen($text) > 100, 'Extracted text should have content');

        $batch = $this->parser->parseBatch($text);
        $this->assertSame(true, $batch['is_batch']);
        $this->assertSame(25, $batch['count']);

        // Verify product names and SKUs are properly extracted without empty defaults
        $firstItem = $batch['items'][0];
        $this->assertSame('JT98239444', $firstItem['tracking_number']);
        $this->assertSame('J&T Express', $firstItem['courier']);
        $this->assertSame('Made Ferry', $firstItem['recipient_name']);
        $this->assertSame('PRD-RAW-005', $firstItem['sku']);
        $this->assertStringContainsString('Madagascar Vanilla', $firstItem['product_name']);
    }
}

$runner = new SimpleTestRunner();
$runner->runAll();
exit($runner->failed > 0 ? 1 : 0);
