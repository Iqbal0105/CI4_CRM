<?php

declare(strict_types=1);

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        // 1. Omset Hari Ini
        $omsetToday = $db->table('sales_order')
            ->selectSum('total')
            ->where('tanggal_so', $today)
            ->where('status !=', 'cancelled')
            ->get()->getRow()->total ?? '0.00';

        // 2. Omset Bulan Ini
        $omsetMonth = $db->table('sales_order')
            ->selectSum('total')
            ->like('tanggal_so', $thisMonth, 'after')
            ->where('status !=', 'cancelled')
            ->get()->getRow()->total ?? '0.00';

        // 3. Total Transaksi Aktif / Hari Ini
        $trxCountToday = $db->table('sales_order')
            ->where('tanggal_so', $today)
            ->where('status !=', 'cancelled')
            ->countAllResults();

        $totalActiveOrders = $db->table('sales_order')
            ->where('status !=', 'cancelled')
            ->countAllResults();

        // 4. Stok Barang Tersedia Fisik
        $stokTersediaTotal = (float) ($db->table('stok')->selectSum('qty')->get()->getRow()->qty ?? 0);

        // 5. Total Barang Terjual (Akumulasi & Hari Ini)
        $totalBarangTerjual = (float) ($db->table('sales_order_item')->selectSum('qty')->get()->getRow()->qty ?? 0);

        $totalBarangTerjualHariIni = (float) ($db->table('sales_order_item soi')
            ->join('sales_order so', 'so.id = soi.so_id')
            ->where('so.tanggal_so', $today)
            ->where('so.status !=', 'cancelled')
            ->selectSum('soi.qty')
            ->get()->getRow()->qty ?? 0);

        // 6. Master Data Counts
        $totalPelanggan = $db->table('pelanggan')->countAllResults();
        $totalProduk    = $db->table('produk')->where('status', 'active')->countAllResults();
        $totalKategori  = $db->table('kategori_produk')->countAllResults();
        $totalSatuan    = $db->table('satuan')->countAllResults();

        // 7. Low Stock Items & Count
        $lowStockQuery = $db->table('produk p')
            ->select('p.id, p.kode_produk, p.nama_produk, p.stok_minimum, COALESCE(s.qty, 0) as fisik, COALESCE(s.reserved_qty, 0) as reserved, (COALESCE(s.qty, 0) - COALESCE(s.reserved_qty, 0)) as sisa_stok')
            ->join('stok s', 's.produk_id = p.id', 'left')
            ->where('p.status', 'active')
            ->where('(COALESCE(s.qty, 0) - COALESCE(s.reserved_qty, 0)) <= p.stok_minimum')
            ->orderBy('sisa_stok', 'ASC');

        $lowStockCount = $lowStockQuery->countAllResults(false);
        $lowStockItems = $lowStockQuery->limit(10)->get()->getResultArray();

        // 8. Total Piutang & Utang Berjalan
        $totalPiutang = $db->table('piutang_usaha')
            ->selectSum('sisa')
            ->where('status !=', 'lunas')
            ->get()->getRow()->sisa ?? '0.00';

        $totalUtang = $db->table('utang_usaha')
            ->selectSum('sisa')
            ->where('status !=', 'lunas')
            ->get()->getRow()->sisa ?? '0.00';

        // 9. Recent Sales Orders
        $recentOrders = $db->table('sales_order so')
            ->select('so.*, COALESCE(p.nama_pelanggan, "Pelanggan Umum") as nama_pelanggan')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->orderBy('so.id', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 10. Recent Retur Penjualan
        $recentReturns = [];
        if ($db->tableExists('retur_penjualan')) {
            $recentReturns = $db->table('retur_penjualan r')
                ->select('r.*, COALESCE(p.nama_pelanggan, "Pelanggan Umum") as nama_pelanggan')
                ->join('pelanggan p', 'p.id = r.pelanggan_id', 'left')
                ->orderBy('r.id', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        }

        // 11. Follow-up CRM Pending Count
        $pendingCrmCount = $db->table('crm_transaction_followup_labels')
            ->where('status', 'pending')
            ->where('target_date <=', $today)
            ->countAllResults();

        $data = [
            'title'                     => 'Dashboard Utama',
            'omsetToday'                => $omsetToday,
            'omsetMonth'                => $omsetMonth,
            'trxCountToday'             => $trxCountToday,
            'totalActiveOrders'         => $totalActiveOrders,
            'stokTersediaTotal'         => $stokTersediaTotal,
            'totalBarangTerjual'        => $totalBarangTerjual,
            'totalBarangTerjualHariIni' => $totalBarangTerjualHariIni,
            'totalPelanggan'            => $totalPelanggan,
            'totalProduk'               => $totalProduk,
            'totalKategori'             => $totalKategori,
            'totalSatuan'               => $totalSatuan,
            'lowStockCount'             => $lowStockCount,
            'lowStockItems'             => $lowStockItems,
            'totalPiutang'              => $totalPiutang,
            'totalUtang'                => $totalUtang,
            'recentOrders'              => $recentOrders,
            'recentReturns'             => $recentReturns,
            'pendingCrmCount'           => $pendingCrmCount,
        ];

        return view('Modules\Dashboard\Views\index', $data);
    }
}
