<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $db = $this->db;

        // 1. Roles
        $roles = [
            ['name' => 'superadmin', 'display_name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh sistem dan konfigurasi'],
            ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Akses manajerial, otorisasi, dan pelaporan keuangan'],
            ['name' => 'kasir', 'display_name' => 'Kasir POS', 'description' => 'Operator kasir penjualan cepat dan penerimaan kas'],
            ['name' => 'admin_stok', 'display_name' => 'Admin Stok', 'description' => 'Pengelolaan inventori, stok opname, dan penerimaan PO'],
            ['name' => 'staff_cs', 'display_name' => 'Staff CS & CRM', 'description' => 'Layanan after-sales, chat WhatsApp, dan tindak lanjut CRM'],
        ];

        foreach ($roles as $role) {
            $existing = $db->table('roles')->where('name', $role['name'])->get()->getRow();
            if (!$existing) {
                $role['created_at'] = date('Y-m-d H:i:s');
                $role['updated_at'] = date('Y-m-d H:i:s');
                $db->table('roles')->insert($role);
            }
        }

        // Get role IDs
        $roleMap = [];
        $rows = $db->table('roles')->get()->getResultArray();
        foreach ($rows as $r) {
            $roleMap[$r['name']] = (int) $r['id'];
        }

        // 2. Users — password diambil dari env atau di-generate secara acak
        $defaultPwd = env('SEED_DEFAULT_PASSWORD', bin2hex(random_bytes(8)));
        $passwordHash = password_hash($defaultPwd, PASSWORD_BCRYPT);
        echo "Seeder: default password = {$defaultPwd}\n";
        $users = [
            [
                'username'      => 'admin',
                'email'         => 'admin@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['superadmin'],
                'status'        => 'active',
            ],
            [
                'username'      => 'superadmin',
                'email'         => 'superadmin@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['superadmin'],
                'status'        => 'active',
            ],
            [
                'username'      => 'manager',
                'email'         => 'manager@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['manager'],
                'status'        => 'active',
            ],
            [
                'username'      => 'manajer',
                'email'         => 'manajer@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['manager'],
                'status'        => 'active',
            ],
            [
                'username'      => 'kasir',
                'email'         => 'kasir@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['kasir'],
                'status'        => 'active',
            ],
            [
                'username'      => 'stok',
                'email'         => 'stok@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['admin_stok'],
                'status'        => 'active',
            ],
            [
                'username'      => 'cs',
                'email'         => 'cs@metafora.local',
                'password_hash' => $passwordHash,
                'role_id'       => $roleMap['staff_cs'],
                'status'        => 'active',
            ],
        ];

        foreach ($users as $user) {
            $existing = $db->table('users')->where('username', $user['username'])->get()->getRow();
            if (!$existing) {
                $user['created_at'] = date('Y-m-d H:i:s');
                $user['updated_at'] = date('Y-m-d H:i:s');
                $db->table('users')->insert($user);
            }
        }

        // 3. Satuan
        $satuanList = [
            ['kode_satuan' => 'PCS', 'nama_satuan' => 'Pieces / Buah'],
            ['kode_satuan' => 'BOX', 'nama_satuan' => 'Box / Dus'],
            ['kode_satuan' => 'PACK', 'nama_satuan' => 'Pack / Bungkus'],
            ['kode_satuan' => 'SLOP', 'nama_satuan' => 'Slop / Ikat'],
            ['kode_satuan' => 'KG', 'nama_satuan' => 'Kilogram'],
        ];
        foreach ($satuanList as $satuan) {
            $existing = $db->table('satuan')->where('kode_satuan', $satuan['kode_satuan'])->get()->getRow();
            if (!$existing) {
                $satuan['created_at'] = date('Y-m-d H:i:s');
                $satuan['updated_at'] = date('Y-m-d H:i:s');
                $db->table('satuan')->insert($satuan);
            }
        }

        // 4. Kategori Produk
        $kategoriList = [
            ['kode_kategori' => 'ELEKTRONIK', 'nama_kategori' => 'Elektronik & Gadget'],
            ['kode_kategori' => 'FASHION', 'nama_kategori' => 'Pakaian & Aksesoris'],
            ['kode_kategori' => 'FNB', 'nama_kategori' => 'Makanan & Minuman'],
            ['kode_kategori' => 'KESEHATAN', 'nama_kategori' => 'Kesehatan & Kecantikan'],
            ['kode_kategori' => 'UMUM', 'nama_kategori' => 'Kategori Umum'],
        ];
        foreach ($kategoriList as $kat) {
            $existing = $db->table('kategori_produk')->where('kode_kategori', $kat['kode_kategori'])->get()->getRow();
            if (!$existing) {
                $kat['created_at'] = date('Y-m-d H:i:s');
                $kat['updated_at'] = date('Y-m-d H:i:s');
                $db->table('kategori_produk')->insert($kat);
            }
        }

        // 5. Chart of Accounts (COA)
        $coaList = [
            ['kode_akun' => '1110', 'nama_akun' => 'Kas Tunai Toko', 'tipe' => 'aset', 'posisi_normal' => 'debit'],
            ['kode_akun' => '1120', 'nama_akun' => 'Bank BCA Operasional', 'tipe' => 'aset', 'posisi_normal' => 'debit'],
            ['kode_akun' => '1130', 'nama_akun' => 'Kas QRIS Settlement', 'tipe' => 'aset', 'posisi_normal' => 'debit'],
            ['kode_akun' => '1140', 'nama_akun' => 'Piutang Usaha', 'tipe' => 'aset', 'posisi_normal' => 'debit'],
            ['kode_akun' => '1150', 'nama_akun' => 'Persediaan Barang Dagang', 'tipe' => 'aset', 'posisi_normal' => 'debit'],
            ['kode_akun' => '2110', 'nama_akun' => 'Utang Usaha Supplier', 'tipe' => 'kewajiban', 'posisi_normal' => 'kredit'],
            ['kode_akun' => '3110', 'nama_akun' => 'Modal Pemilik', 'tipe' => 'ekuitas', 'posisi_normal' => 'kredit'],
            ['kode_akun' => '4110', 'nama_akun' => 'Pendapatan Penjualan', 'tipe' => 'pendapatan', 'posisi_normal' => 'kredit'],
            ['kode_akun' => '5110', 'nama_akun' => 'Harga Pokok Penjualan (HPP)', 'tipe' => 'beban', 'posisi_normal' => 'debit'],
            ['kode_akun' => '5210', 'nama_akun' => 'Beban Operasional & Ekspedisi', 'tipe' => 'beban', 'posisi_normal' => 'debit'],
        ];
        foreach ($coaList as $c) {
            $existing = $db->table('coa')->where('kode_akun', $c['kode_akun'])->get()->getRow();
            if (!$existing) {
                $c['created_at'] = date('Y-m-d H:i:s');
                $c['updated_at'] = date('Y-m-d H:i:s');
                $db->table('coa')->insert($c);
            }
        }

        // Map COA IDs
        $coaRows = $db->table('coa')->get()->getResultArray();
        $coaMap = [];
        foreach ($coaRows as $cr) {
            $coaMap[$cr['kode_akun']] = (int) $cr['id'];
        }

        // 6. Akun Kas & Bank
        $kasBankList = [
            [
                'kode_akun'      => 'KAS-KASIR',
                'nama_akun'      => 'Kas Kasir Tunai',
                'nomor_rekening' => null,
                'atas_nama'      => 'Kasir POS',
                'coa_id'         => $coaMap['1110'] ?? null,
                'saldo'          => 0.00,
                'tipe'           => 'kas',
                'status'         => 'active',
            ],
            [
                'kode_akun'      => 'BANK-BCA',
                'nama_akun'      => 'Bank BCA Operasional',
                'nomor_rekening' => '8012938102',
                'atas_nama'      => 'PT Metafora Teknologi Indonesia',
                'coa_id'         => $coaMap['1120'] ?? null,
                'saldo'          => 0.00,
                'tipe'           => 'bank',
                'status'         => 'active',
            ],
            [
                'kode_akun'      => 'KAS-QRIS',
                'nama_akun'      => 'Kas QRIS Dinamis/Statis',
                'nomor_rekening' => null,
                'atas_nama'      => 'Merchant Metafora',
                'coa_id'         => $coaMap['1130'] ?? null,
                'saldo'          => 0.00,
                'tipe'           => 'qris',
                'status'         => 'active',
            ],
            [
                'kode_akun'      => 'PETTY-CASH',
                'nama_akun'      => 'Kas Kecil (Petty Cash)',
                'nomor_rekening' => null,
                'atas_nama'      => 'Bendahara',
                'coa_id'         => $coaMap['1110'] ?? null,
                'saldo'          => 0.00,
                'tipe'           => 'petty_cash',
                'status'         => 'active',
            ],
        ];
        foreach ($kasBankList as $kb) {
            $existing = $db->table('akun_kas_bank')->where('kode_akun', $kb['kode_akun'])->get()->getRow();
            if (!$existing) {
                $kb['created_at'] = date('Y-m-d H:i:s');
                $kb['updated_at'] = date('Y-m-d H:i:s');
                $db->table('akun_kas_bank')->insert($kb);
            }
        }

        // 7. RFM Segments Matrix & Strategy
        $rfmSegments = [
            [
                'segment_name'       => 'Champions',
                'r_min'              => 4,
                'r_max'              => 5,
                'f_min'              => 4,
                'f_max'              => 5,
                'm_min'              => 4,
                'm_max'              => 5,
                'description'        => 'Pelanggan terbaik yang baru saja berbelanja, sering beli, dan nilai belanjanya sangat besar.',
                'recommended_action' => 'Berikan reward VIP, akses eksklusif produk baru, program referral, dan apresiasi personal dari manajemen.',
            ],
            [
                'segment_name'       => 'Loyal Customers',
                'r_min'              => 3,
                'r_max'              => 5,
                'f_min'              => 3,
                'f_max'              => 5,
                'm_min'              => 3,
                'm_max'              => 5,
                'description'        => 'Pelanggan setia yang berbelanja secara konsisten dengan nilai yang baik.',
                'recommended_action' => 'Upselling produk bernilai lebih tinggi, tawarkan voucher loyalitas berkala, dan minta ulasan produk.',
            ],
            [
                'segment_name'       => 'Potential Loyalists',
                'r_min'              => 4,
                'r_max'              => 5,
                'f_min'              => 2,
                'f_max'              => 3,
                'm_min'              => 2,
                'm_max'              => 3,
                'description'        => 'Pembeli baru yang berpotensi menjadi pelanggan setia karena transaksi berulang baru dimulai.',
                'recommended_action' => 'Tawarkan program membership dan diskon pada pembelian berikutnya untuk membentuk kebiasaan belanja.',
            ],
            [
                'segment_name'       => 'At Risk',
                'r_min'              => 1,
                'r_max'              => 2,
                'f_min'              => 3,
                'f_max'              => 5,
                'm_min'              => 3,
                'm_max'              => 5,
                'description'        => 'Pelanggan bernilai tinggi yang sudah lama tidak berbelanja. Berisiko pindah ke kompetitor.',
                'recommended_action' => 'Kirim kampanye reaktivasi personal WhatsApp dengan penawaran spesial "Kami Merindukan Anda" dan voucher diskon terbatas.',
            ],
            [
                'segment_name'       => 'Hibernating',
                'r_min'              => 1,
                'r_max'              => 2,
                'f_min'              => 1,
                'f_max'              => 2,
                'm_min'              => 1,
                'm_max'              => 2,
                'description'        => 'Pelanggan dengan frekuensi dan nilai rendah yang sudah lama tidak aktif.',
                'recommended_action' => 'Kirimkan katalog produk baru atau promo cuci stok untuk memancing ketertarikan kembali.',
            ],
            [
                'segment_name'       => 'Lost',
                'r_min'              => 1,
                'r_max'              => 1,
                'f_min'              => 1,
                'f_max'              => 1,
                'm_min'              => 1,
                'm_max'              => 1,
                'description'        => 'Pelanggan dengan skor terendah di semua metrik yang sudah tidak berinteraksi.',
                'recommended_action' => 'Survei kepuasan singkat via WhatsApp untuk memahami alasan berhenti, fokuskan sumber daya promosi ke segmen lain.',
            ],
        ];

        foreach ($rfmSegments as $seg) {
            $existing = $db->table('rfm_segments')->where('segment_name', $seg['segment_name'])->get()->getRow();
            if (!$existing) {
                $seg['created_at'] = date('Y-m-d H:i:s');
                $seg['updated_at'] = date('Y-m-d H:i:s');
                $db->table('rfm_segments')->insert($seg);
            }
        }

        // 8. Default Pelanggan Umum (Walk-in Customer / Retail)
        $pelangganUmum = [
            'kode_pelanggan' => 'CUST-0001',
            'nama_pelanggan' => 'Pelanggan Umum (Walk-in)',
            'no_telp'        => '081200000000',
            'tipe_pelanggan' => 'retail',
            'limit_kredit'   => 0.00,
            'tempo_hari'     => 0,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
        $existingCust = $db->table('pelanggan')->where('kode_pelanggan', $pelangganUmum['kode_pelanggan'])->get()->getRow();
        if (!$existingCust) {
            $db->table('pelanggan')->insert($pelangganUmum);
        }

        // 9. Default Supplier
        $supplierDefault = [
            'kode_supplier' => 'SUP-0001',
            'nama_supplier' => 'PT Sumber Distribusi Nusantara',
            'no_telp'       => '0215556789',
            'email'         => 'sales@sumberdistribusi.co.id',
            'alamat'        => 'Kawasan Industri Pulogadung Blok B No. 12',
            'mata_uang'     => 'IDR',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $existingSup = $db->table('supplier')->where('kode_supplier', $supplierDefault['kode_supplier'])->get()->getRow();
        if (!$existingSup) {
            $db->table('supplier')->insert($supplierDefault);
        }

        // 10. Sample Products matching Shopee/Tokopedia CSV
        $kategoriRow = $db->table('kategori_produk')->where('kode_kategori', 'KAT-ELK')->get()->getRow();
        $kategoriId = $kategoriRow ? (int) $kategoriRow->id : 1;
        $satuanRow = $db->table('satuan')->where('kode_satuan', 'PCS')->get()->getRow();
        $satuanId = $satuanRow ? (int) $satuanRow->id : 1;

        $sampleProducts = [
            [
                'kode_produk'       => 'PRD-0001',
                'barcode'           => '89910010001',
                'nama_produk'       => 'Laptop Pro 15-inch Intel i7 16GB SSD 512GB',
                'kategori_id'       => $kategoriId,
                'satuan_id'         => $satuanId,
                'harga_beli'        => '12500000.00',
                'harga_jual'        => '14900000.00',
                'harga_grosir'      => '14500000.00',
                'harga_distributor' => '14000000.00',
                'stok_minimum'      => 3,
                'status'            => 'active',
            ],
            [
                'kode_produk'       => 'PRD-0002',
                'barcode'           => '89910010002',
                'nama_produk'       => 'Printer Laserjet Enterprise Pro Monochrome',
                'kategori_id'       => $kategoriId,
                'satuan_id'         => $satuanId,
                'harga_beli'        => '3800000.00',
                'harga_jual'        => '4800000.00',
                'harga_grosir'      => '4700000.00',
                'harga_distributor' => '4500000.00',
                'stok_minimum'      => 5,
                'status'            => 'active',
            ],
            [
                'kode_produk'       => 'PRD-0007',
                'barcode'           => '89910010007',
                'nama_produk'       => 'RAM Corsair Vengeance DDR4 16GB 3200MHz',
                'kategori_id'       => $kategoriId,
                'satuan_id'         => $satuanId,
                'harga_beli'        => '800000.00',
                'harga_jual'        => '1050000.00',
                'harga_grosir'      => '1000000.00',
                'harga_distributor' => '950000.00',
                'stok_minimum'      => 10,
                'status'            => 'active',
            ],
            [
                'kode_produk'       => 'PRD-0011',
                'barcode'           => '89910010011',
                'nama_produk'       => 'Laptop Ideapad Gaming 3 AMD Ryzen 5',
                'kategori_id'       => $kategoriId,
                'satuan_id'         => $satuanId,
                'harga_beli'        => '9200000.00',
                'harga_jual'        => '11200000.00',
                'harga_grosir'      => '10900000.00',
                'harga_distributor' => '10500000.00',
                'stok_minimum'      => 4,
                'status'            => 'active',
            ],
        ];

        foreach ($sampleProducts as $p) {
            $existingP = $db->table('produk')->where('kode_produk', $p['kode_produk'])->get()->getRow();
            if (!$existingP) {
                $p['created_at'] = date('Y-m-d H:i:s');
                $p['updated_at'] = date('Y-m-d H:i:s');
                $db->table('produk')->insert($p);
                $pId = (int) $db->insertID();

                // Initialize stock with 50 units
                $db->table('stok')->insert([
                    'produk_id'    => $pId,
                    'qty'          => '50.000',
                    'reserved_qty' => '0.000',
                    'lokasi_rak'   => 'RAK-UTAMA',
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

                // Initial stock in log
                $db->table('stok_log')->insert([
                    'produk_id'         => $pId,
                    'tipe'              => 'in',
                    'qty'               => '50.000',
                    'saldo_akhir'       => '50.000',
                    'referensi_dokumen' => 'INITIAL-STOCK',
                    'keterangan'        => 'Saldo awal inventori sistem',
                    'created_at'        => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}

