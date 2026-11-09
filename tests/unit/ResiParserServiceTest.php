<?php

declare(strict_types=1);

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Modules\PenjualanMarketplace\Services\ResiParserService;

final class ResiParserServiceTest extends CIUnitTestCase
{
    private ResiParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ResiParserService();
    }

    public function testParsesSpxLabel(): void
    {
        $rawText = "Shopee Xpress Standard\nNo. Resi: SPXID0293847562\nPenerima: Ahmad Dahlan\nKota: Bandung";
        $result = $this->parser->parse($rawText);

        $this->assertSame('SPX Express', $result['courier']);
        $this->assertSame('SPXID0293847562', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Ahmad Dahlan', $result['recipient_name']);
    }

    public function testParsesJntLabel(): void
    {
        $rawText = "J&T Express EZ\nNo. Resi: JP12345678901\nKepada: Budi Prasetyo\nKota: Surabaya";
        $result = $this->parser->parse($rawText);

        $this->assertSame('J&T Express', $result['courier']);
        $this->assertSame('JP12345678901', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Budi Prasetyo', $result['recipient_name']);
    }

    public function testParsesJneLabel(): void
    {
        $rawText = "JNE REG\nNo Resi: JNE012345678901\nPenerima: Siti Aminah\nJakarta";
        $result = $this->parser->parse($rawText);

        $this->assertSame('JNE', $result['courier']);
        $this->assertSame('JNE012345678901', $result['tracking_number']);
        $this->assertGreaterThanOrEqual(0.8, $result['confidence']);
        $this->assertSame('Siti Aminah', $result['recipient_name']);
    }

    public function testParsesFullShopeeSpxLabelWithPhoneAddressAndSku(): void
    {
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

    public function testParsesFullTikTokShopLabel(): void
    {
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

    public function testParsesRealShopeeSpxLabelWithIdTrackingAndMergedColumns(): void
    {
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

    public function testParsesOcrNoisyTrackingNumberWithPipeOrOnePrefix(): void
    {
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

    public function testParseBatchMultiLabelsFromMultiPagePdf(): void
    {
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

    public function testParsesProductTableWithSkuVariasiAndQtyFromShopee(): void
    {
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

    public function testParsesCodNominalAccurately(): void
    {
        $rawText1 = "Shopee Xpress\nNo. Resi: SPXID0123\nCOD: Rp41040\nPenerima: Siti";
        $r1 = $this->parser->parseFull($rawText1);
        $this->assertEquals(41040.0, $r1['total']);
        $this->assertTrue($r1['is_cod']);

        $rawText2 = "J&T Express\nNo. Resi: JP0987\nCOD: Rp 41.040,00\nPenerima: Budi";
        $r2 = $this->parser->parseFull($rawText2);
        $this->assertEquals(41040.0, $r2['total']);
        $this->assertTrue($r2['is_cod']);
    }

    public function testParsesAndIsolatesSenderDataSeparately(): void
    {
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

    public function testParsesRecipientWithoutPhoneLeavesPhoneEmpty(): void
    {
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

    public function testParsesMultiProductLabelWithTotalQty(): void
    {
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

    public function testParsesUserImageCropSpxResiVariations(): void
    {
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

    public function testServerSideImageOcrOnUserImage(): void
    {
        $imgPath = 'C:/Users/Lenovo/.gemini/antigravity/brain/500a4c5b-1c38-400d-b666-8a79ae0928bc/.user_uploaded/media_1794014768170_cb8b3915.png';
        if (file_exists($imgPath)) {
            $extracted = $this->parser->extractTextFromImage($imgPath);
            $this->assertTrue(strlen($extracted) > 5, 'Server OCR extracted non-empty text');
            $parsed = $this->parser->parseFull($extracted);
            $this->assertSame('SPX Express', $parsed['courier']);
            $this->assertSame('SPXID042978248603', $parsed['tracking_number']);
        }
    }
}


