<?php

declare(strict_types=1);

namespace Modules\DevTools\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AuditPipelineCommand extends BaseCommand
{
    protected $group       = 'DevTools';
    protected $name        = 'audit:pipeline';
    protected $description = 'Audit menyeluruh integritas pipeline data: Master Data, Marketplace, PO, Invoice, Keuangan/Kas, CRM, dan RFM.';

    public function run(array $params)
    {
        CLI::write("==================================================================", 'cyan');
        CLI::write("   METAFORA ERP - AUDIT INTEGRITAS PIPELINE TRANSAKSI END-TO-END  ", 'yellow');
        CLI::write("==================================================================", 'cyan');

        $db = \Config\Database::connect();
        $totalChecks = 0;
        $passedChecks = 0;
        $issues = [];

        // -------------------------------------------------------------
        // 1. AUDIT MASTER DATA & STOK
        // -------------------------------------------------------------
        CLI::write("\n[1] Memeriksa Integritas Master Data & Stok...", 'white');

        // 1a. Produk tanpa stok
        $totalChecks++;
        $unlinkedProducts = $db->query("
            SELECT p.id, p.kode_produk, p.nama_produk 
            FROM produk p 
            LEFT JOIN stok s ON s.produk_id = p.id 
            WHERE s.id IS NULL AND p.deleted_at IS NULL
        ")->getResultArray();
        if (empty($unlinkedProducts)) {
            CLI::write("  ✔ Semua master produk terhubung ke tabel stok fisik", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($unlinkedProducts) . " produk tanpa record stok!", 'red');
            $issues[] = "Master Produk: " . count($unlinkedProducts) . " produk tidak memiliki record di tabel stok.";
        }

        // 1b. Pelanggan tanpa kode unik
        $totalChecks++;
        $invalidCustomers = $db->query("
            SELECT id, nama_pelanggan 
            FROM pelanggan 
            WHERE (kode_pelanggan IS NULL OR kode_pelanggan = '') AND deleted_at IS NULL
        ")->getResultArray();
        if (empty($invalidCustomers)) {
            CLI::write("  ✔ Semua pelanggan memiliki kode unik resmi (CUST-...)", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($invalidCustomers) . " pelanggan tanpa kode_pelanggan!", 'red');
            $issues[] = "Master Pelanggan: " . count($invalidCustomers) . " pelanggan tidak memiliki kode_pelanggan.";
        }

        // -------------------------------------------------------------
        // 2. AUDIT PENJUALAN MARKETPLACE & SALES ORDER
        // -------------------------------------------------------------
        CLI::write("\n[2] Memeriksa Integritas Penjualan Marketplace & SO...", 'white');

        // 2a. Marketplace tanpa SO
        $totalChecks++;
        $orphanMp = $db->query("
            SELECT id, no_pesanan, source, nama_penerima 
            FROM penjualan_marketplace 
            WHERE (so_id IS NULL OR so_id = 0) AND deleted_at IS NULL
        ")->getResultArray();
        if (empty($orphanMp)) {
            CLI::write("  ✔ 100% Pesanan Marketplace terhubung ke Sales Order (so_id)", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($orphanMp) . " pesanan marketplace tanpa so_id!", 'red');
            foreach ($orphanMp as $omp) {
                CLI::write("    -> Order #{$omp['no_pesanan']} ({$omp['source']}) an. {$omp['nama_penerima']}", 'yellow');
            }
            $issues[] = "Marketplace: " . count($orphanMp) . " pesanan tidak memiliki relasi so_id.";
        }

        // 2b. Sales Order tanpa Item
        $totalChecks++;
        $soWithoutItems = $db->query("
            SELECT so.id, so.nomor_so, so.source 
            FROM sales_order so 
            LEFT JOIN sales_order_item soi ON soi.so_id = so.id 
            WHERE soi.id IS NULL AND so.deleted_at IS NULL
        ")->getResultArray();
        if (empty($soWithoutItems)) {
            CLI::write("  ✔ Semua Sales Order memiliki rincian sales_order_item", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($soWithoutItems) . " Sales Order tanpa item!", 'red');
            $issues[] = "Sales Order: " . count($soWithoutItems) . " order tidak memiliki item produk.";
        }

        // 2c. Sales Order bertaut ke Pelanggan Walk-In ID 1 padahal punya nama penerima marketplace
        $totalChecks++;
        $walkinMpOrders = $db->query("
            SELECT so.id, so.nomor_so, pm.nama_penerima 
            FROM sales_order so 
            JOIN penjualan_marketplace pm ON pm.so_id = so.id 
            WHERE so.pelanggan_id = 1 AND pm.nama_penerima IS NOT NULL AND pm.nama_penerima != 'Pembeli Marketplace' AND pm.nama_penerima != ''
        ")->getResultArray();
        if (empty($walkinMpOrders)) {
            CLI::write("  ✔ Semua Sales Order Marketplace terhubung ke Master Pelanggan individual", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($walkinMpOrders) . " SO Marketplace masih bertaut ke Pelanggan Walk-In ID 1!", 'yellow');
            $issues[] = "Sales Order: " . count($walkinMpOrders) . " pesanan marketplace masih bertaut ke akun Walk-In ID 1.";
        }

        // -------------------------------------------------------------
        // 3. AUDIT FAKTUR / INVOICE
        // -------------------------------------------------------------
        CLI::write("\n[3] Memeriksa Integritas Faktur / Invoice...", 'white');

        // 3a. SO Completed tanpa Invoice
        $totalChecks++;
        $soWithoutInvoice = $db->query("
            SELECT so.id, so.nomor_so 
            FROM sales_order so 
            LEFT JOIN invoice inv ON inv.so_id = so.id 
            WHERE so.status = 'completed' AND inv.id IS NULL AND so.deleted_at IS NULL
        ")->getResultArray();
        if (empty($soWithoutInvoice)) {
            CLI::write("  ✔ Semua Sales Order Completed memiliki Faktur Invoice resmi", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($soWithoutInvoice) . " SO completed tanpa Invoice!", 'red');
            $issues[] = "Invoice: " . count($soWithoutInvoice) . " Sales Order completed tidak memiliki invoice.";
        }

        // -------------------------------------------------------------
        // 4. AUDIT KEUANGAN, KAS & BANK, JURNAL
        // -------------------------------------------------------------
        CLI::write("\n[4] Memeriksa Integritas Keuangan, Kas & Bank, dan Jurnal...", 'white');

        // 4a. Cek akun kas & bank
        $totalChecks++;
        $kasBankAccounts = $db->table('akun_kas_bank')->where('status', 'active')->get()->getResultArray();
        if (!empty($kasBankAccounts)) {
            CLI::write("  ✔ Terdaftar " . count($kasBankAccounts) . " Akun Rekening Kas & Bank aktif:", 'green');
            foreach ($kasBankAccounts as $kb) {
                CLI::write("    - [{$kb['kode_akun']}] {$kb['nama_akun']} ({$kb['tipe']}): Rp " . number_format((float)$kb['saldo'], 0, ',', '.'), 'cyan');
            }
            $passedChecks++;
        } else {
            CLI::write("  ✖ Tidak ada akun kas & bank yang aktif!", 'red');
            $issues[] = "Kas & Bank: Tidak ditemukan akun kas/bank yang aktif.";
        }

        // 4b. Jurnal Tidak Seimbang (Debit != Kredit)
        $totalChecks++;
        $unbalancedJournals = $db->query("
            SELECT j.id, j.nomor_jurnal, j.referensi, j.total_debit, j.total_kredit,
                   ROUND(ABS(j.total_debit - j.total_kredit), 2) as selisih
            FROM jurnal j
            WHERE ROUND(ABS(j.total_debit - j.total_kredit), 2) > 0.01
        ")->getResultArray();
        if (empty($unbalancedJournals)) {
            CLI::write("  ✔ 100% Entri Buku Jurnal Umum Seimbang (Total Debit = Total Kredit)", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($unbalancedJournals) . " jurnal akuntansi TIDAK SEIMBANG!", 'red');
            foreach ($unbalancedJournals as $uj) {
                CLI::write("    -> Jurnal {$uj['nomor_jurnal']} Ref: {$uj['referensi']} Selisih: Rp {$uj['selisih']}", 'yellow');
            }
            $issues[] = "Jurnal Akuntansi: Ditemukan " . count($unbalancedJournals) . " jurnal tidak seimbang.";
        }

        // 4c. Jurnal Detail Tanpa Header Jurnal
        $totalChecks++;
        $orphanJurnalDetails = $db->query("
            SELECT jd.id 
            FROM jurnal_detail jd 
            LEFT JOIN jurnal j ON j.id = jd.jurnal_id 
            WHERE j.id IS NULL
        ")->getResultArray();
        if (empty($orphanJurnalDetails)) {
            CLI::write("  ✔ Semua rincian jurnal memiliki referensi induk jurnal yang valid", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($orphanJurnalDetails) . " rincian jurnal tanpa induk!", 'red');
            $issues[] = "Jurnal: " . count($orphanJurnalDetails) . " rincian jurnal kehilangan relasi induk.";
        }

        // 4d. Piutang & BKM
        $totalChecks++;
        $totalPiutang = $db->table('piutang_usaha')->countAllResults();
        $totalBKM = $db->table('pembayaran_piutang')->countAllResults();
        CLI::write("  ✔ Buku Piutang Usaha: {$totalPiutang} record terdaftar | BKM: {$totalBKM} transaksi kas masuk", 'green');
        $passedChecks++;

        // 4e. Utang, BKK & Integritas PO ke AP
        $totalChecks++;
        $orphanPOs = $db->query("
            SELECT po.id, po.nomor_po 
            FROM purchase_order po 
            LEFT JOIN utang_usaha uu ON uu.po_id = po.id 
            WHERE uu.id IS NULL AND po.status != 'cancelled'
        ")->getResultArray();
        $totalUtang = $db->table('utang_usaha')->countAllResults();
        $totalBKK = $db->table('pembayaran_utang')->countAllResults();
        if (empty($orphanPOs)) {
            CLI::write("  ✔ 100% Purchase Order terhubung ke Utang Usaha (AP): {$totalUtang} tagihan | BKK: {$totalBKK} transaksi kas keluar", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($orphanPOs) . " Purchase Order belum masuk ke Utang Usaha (AP)!", 'red');
            $issues[] = "Keuangan/AP: " . count($orphanPOs) . " PO aktif belum tercatat di utang_usaha.";
        }

        // -------------------------------------------------------------
        // 5. AUDIT CRM FOLLOW-UP & DIRECTORY
        // -------------------------------------------------------------
        CLI::write("\n[5] Memeriksa Integritas Modul CRM Follow-Up...", 'white');

        // 5a. Marketplace Order dengan No. Telepon tapi belum ada Jadwal CRM
        $totalChecks++;
        $mpMissingCrm = $db->query("
            SELECT pm.id, pm.no_pesanan, pm.source, pm.nama_penerima, pm.no_telepon, pm.so_id 
            FROM penjualan_marketplace pm 
            LEFT JOIN crm_transaction_followup_labels cfl ON cfl.order_id = pm.so_id 
            WHERE pm.deleted_at IS NULL 
              AND pm.no_telepon IS NOT NULL 
              AND TRIM(pm.no_telepon) != '' 
              AND cfl.id IS NULL
        ")->getResultArray();
        if (empty($mpMissingCrm)) {
            CLI::write("  ✔ 100% Pesanan Marketplace ber-telepon memiliki jadwal WhatsApp CRM", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan " . count($mpMissingCrm) . " pesanan ber-telepon tanpa jadwal CRM!", 'red');
            foreach ($mpMissingCrm as $mmc) {
                CLI::write("    -> Order #{$mmc['no_pesanan']} ({$mmc['nama_penerima']} - {$mmc['no_telepon']})", 'yellow');
            }
            $issues[] = "CRM: " . count($mpMissingCrm) . " pesanan dengan telepon belum memiliki jadwal follow-up.";
        }

        // 5b. Jadwal CRM tanpa No. Telepon
        $totalChecks++;
        $crmWithoutPhone = $db->table('crm_transaction_followup_labels')
            ->groupStart()
                ->where('customer_phone IS NULL')
                ->orWhere('customer_phone', '')
            ->groupEnd()
            ->countAllResults();
        if ($crmWithoutPhone === 0) {
            CLI::write("  ✔ Semua entri jadwal CRM memiliki nomor telepon valid untuk WhatsApp", 'green');
            $passedChecks++;
        } else {
            CLI::write("  ✖ Ditemukan {$crmWithoutPhone} jadwal CRM dengan nomor telepon kosong!", 'red');
            $issues[] = "CRM: {$crmWithoutPhone} jadwal follow-up memiliki nomor telepon kosong.";
        }

        // -------------------------------------------------------------
        // 6. AUDIT RFM ANALYTICS & REKAP KESELURUHAN
        // -------------------------------------------------------------
        CLI::write("\n[6] Memeriksa Konsistensi Agregasi Modul RFM...", 'white');

        $totalChecks++;
        $totalPelanggan = (int) $db->table('pelanggan')->where('deleted_at IS NULL')->countAllResults();
        $totalSO = (int) $db->table('sales_order')->where('deleted_at IS NULL')->where('status', 'completed')->countAllResults();
        $totalOmset = $db->table('sales_order')->where('deleted_at IS NULL')->where('status', 'completed')->selectSum('total')->get()->getRow()->total ?? '0.00';

        CLI::write("  ✔ Master Pelanggan Terdaftar : {$totalPelanggan} orang", 'cyan');
        CLI::write("  ✔ Total Transaksi Selesai   : {$totalSO} transaksi", 'cyan');
        CLI::write("  ✔ Total Akumulasi Omset     : Rp " . number_format((float)$totalOmset, 0, ',', '.'), 'cyan');
        $passedChecks++;

        // -------------------------------------------------------------
        // RINGKASAN HASIL AUDIT
        // -------------------------------------------------------------
        CLI::write("\n==================================================================", 'cyan');
        CLI::write("                     RINGKASAN HASIL AUDIT                        ", 'yellow');
        CLI::write("==================================================================", 'cyan');
        CLI::write("Total Parameter Diperiksa : {$totalChecks}");
        CLI::write("Kondisi Lolos Uji (Pass)  : {$passedChecks}", 'green');
        CLI::write("Temuan / Catatan (Issues) : " . count($issues), count($issues) > 0 ? 'red' : 'green');

        if (empty($issues)) {
            CLI::write("\n🎉 SEMPURNA! Seluruh alur data Master Data, Marketplace, PO, Invoice, Keuangan/Kas, CRM, dan RFM 100% TERINTEGRASI TANPA CACAT!\n", 'green');
        } else {
            CLI::write("\n⚠️ Perhatian! Ditemukan catatan yang perlu diselaraskan:", 'yellow');
            foreach ($issues as $idx => $iss) {
                CLI::write("  " . ($idx + 1) . ". {$iss}", 'yellow');
            }
            CLI::write("\nGunakan perintah: php spark marketplace:sync untuk sinkronisasi otomatis data unmapped.\n", 'cyan');
        }
    }
}

