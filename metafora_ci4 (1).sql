-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2026 at 08:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metafora_ci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_kas_bank`
--

CREATE TABLE `akun_kas_bank` (
  `id` int UNSIGNED NOT NULL,
  `kode_akun` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akun` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_rekening` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coa_id` int UNSIGNED DEFAULT NULL,
  `saldo` decimal(18,2) NOT NULL DEFAULT '0.00',
  `tipe` enum('kas','bank','qris','petty_cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kas',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `akun_kas_bank`
--

INSERT INTO `akun_kas_bank` (`id`, `kode_akun`, `nama_akun`, `nomor_rekening`, `atas_nama`, `coa_id`, `saldo`, `tipe`, `status`, `created_at`, `updated_at`) VALUES
(1, 'KAS-KASIR', 'Kas Kasir Tunai', NULL, 'Kasir POS', 1, 53893776.32, 'kas', 'active', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(2, 'BANK-BCA', 'Bank BCA Operasional', '8012938102', 'PT Metafora Teknologi Indonesia', 2, 34702500.00, 'bank', 'active', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, 'KAS-QRIS', 'Kas QRIS Dinamis/Statis', NULL, 'Merchant Metafora', 3, 0.00, 'qris', 'active', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, 'PETTY-CASH', 'Kas Kecil (Petty Cash)', NULL, 'Bendahara', 1, 0.00, 'petty_cash', 'active', '2026-10-30 14:52:11', '2026-10-30 14:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-30 15:28:51'),
(2, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 09:48:59'),
(3, 1, 'logout', 'users', '1', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 12:34:30'),
(4, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 12:57:34'),
(5, 1, 'logout', 'users', '1', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 13:02:48'),
(6, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 13:04:32'),
(7, 1, 'checkout_pos', 'sales_order', '2', '', '{\"nomor_so\":\"TRX-202610-0001\",\"total\":\"11200000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-10-31 14:57:44'),
(8, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 01:09:38'),
(9, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', '', '2026-11-01 01:38:52'),
(10, 1, 'api_sync', 'kurs', '1', '', '{\"mata_uang\":\"USD\",\"nilai_kurs\":\"18132.701322\",\"source\":\"open.er-api.com\"}', '127.0.0.1', '', '2026-11-01 01:38:53'),
(11, 1, 'api_sync', 'kurs', '2', '', '{\"mata_uang\":\"SGD\",\"nilai_kurs\":\"14180.105806\",\"source\":\"open.er-api.com\"}', '127.0.0.1', '', '2026-11-01 01:38:54'),
(12, 1, 'api_sync', 'kurs', '3', '', '{\"mata_uang\":\"CNY\",\"nilai_kurs\":\"2724.795640\",\"source\":\"open.er-api.com\"}', '127.0.0.1', '', '2026-11-01 01:38:54'),
(13, 1, 'api_sync', 'kurs', '4', '', '{\"mata_uang\":\"EUR\",\"nilai_kurs\":\"20236.620500\",\"source\":\"open.er-api.com\"}', '127.0.0.1', '', '2026-11-01 01:38:54'),
(14, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', '', '2026-11-01 02:01:48'),
(15, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', '', '2026-11-01 02:06:02'),
(16, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', '', '2026-11-01 02:11:23'),
(17, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 04:02:44'),
(18, 1, 'logout', 'users', '1', '', '', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 04:03:16'),
(19, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 04:03:23'),
(20, 1, 'logout', 'users', '1', '', '', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 04:05:01'),
(21, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 04:06:04'),
(22, 1, 'login', 'users', '1', '', '{\"ip\":\"127.0.0.1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 08:42:48'),
(23, 1, 'checkout_pos', 'sales_order', '3', '', '{\"nomor_so\":\"TRX-202611-0001\",\"total\":\"4800000.00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-01 08:57:07'),
(24, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-02 08:18:14'),
(25, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-02 08:28:25'),
(26, 1, 'logout', 'users', '1', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-02 10:30:16'),
(27, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-02 03:58:53'),
(28, 9, 'login', 'users', '9', '', '{\"ip\":\"::1\",\"source\":\"approved_applicant\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:01:35'),
(29, 9, 'logout', 'users', '9', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:01:57'),
(30, 10, 'login', 'users', '10', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:03:01'),
(31, 10, 'create', 'produk', '5', '', '{\"kode_produk\":\"PRD-0010\",\"barcode\":null,\"nama_produk\":\"kopi\",\"kategori_id\":\"3\",\"satuan_id\":\"5\",\"harga_beli\":\"100000.00\",\"harga_jual\":\"120000.00\",\"harga_grosir\":\"115000.00\",\"harga_distributor\":\"110000.00\",\"stok_minimum\":5,\"status\":\"active\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:09:41'),
(32, 10, 'price_change', 'produk', '2', '{\"harga_beli\":\"3800000.00\",\"harga_jual\":\"4800000.00\",\"harga_grosir\":\"4700000.00\"}', '{\"harga_beli\":\"3800000.00\",\"harga_jual\":\"4900000.00\",\"harga_grosir\":\"4700000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:10:20'),
(33, 10, 'update', 'produk', '5', '{\"id\":\"5\",\"kode_produk\":\"PRD-0010\",\"barcode\":null,\"nama_produk\":\"kopi\",\"kategori_id\":\"3\",\"satuan_id\":\"5\",\"harga_beli\":\"100000.00\",\"harga_jual\":\"120000.00\",\"harga_grosir\":\"115000.00\",\"harga_distributor\":\"110000.00\",\"stok_minimum\":\"5\",\"status\":\"active\",\"created_at\":\"2026-11-02 04:09:41\",\"updated_at\":\"2026-11-02 04:09:41\",\"deleted_at\":null}', '{\"kode_produk\":\"PRD-0010\",\"barcode\":null,\"nama_produk\":\"KOPI GULA AREN\",\"kategori_id\":\"3\",\"satuan_id\":\"5\",\"harga_beli\":\"100000.00\",\"harga_jual\":\"120000.00\",\"harga_grosir\":\"115000.00\",\"harga_distributor\":\"110000.00\",\"stok_minimum\":24,\"status\":\"active\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:10:43'),
(34, 10, 'create', 'pelanggan', '2', '', '{\"kode_pelanggan\":\"CUST-02\",\"nama_pelanggan\":\"axoy\",\"no_telp\":\"012345678909\",\"email\":\"axoy@gmail.com\",\"alamat\":\"Solo\",\"kota\":\"Solo\",\"tipe_pelanggan\":\"retail\",\"limit_kredit\":\"1000000.00\",\"tempo_hari\":0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:15:49'),
(35, 10, 'create', 'pelanggan', '3', '', '{\"kode_pelanggan\":\"CUST-03\",\"nama_pelanggan\":\"aku\",\"no_telp\":\"098765432134\",\"email\":\"aku@gmail.com\",\"alamat\":\"Jogja\",\"kota\":\"Jogja\",\"tipe_pelanggan\":\"grosir\",\"limit_kredit\":\"10000000.00\",\"tempo_hari\":0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:19:35'),
(36, 10, 'create', 'pelanggan', '4', '', '{\"kode_pelanggan\":\"CUST-04\",\"nama_pelanggan\":\"kita\",\"no_telp\":\"012345678978\",\"email\":\"kita@gmail.com\",\"alamat\":\"Surabaya\",\"kota\":\"Surabaya\",\"tipe_pelanggan\":\"distributor\",\"limit_kredit\":\"100000000.00\",\"tempo_hari\":0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 04:20:22'),
(37, 10, 'create', 'supplier', '2', '', '{\"kode_supplier\":\"SUP-261102-39\",\"nama_supplier\":\"aaaa\",\"no_telp\":\"0987654321\",\"email\":\"aaaa@gmail.com\",\"alamat\":\"solo\",\"mata_uang\":\"USD\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 05:51:51'),
(38, 10, 'create', 'supplier', '3', '', '{\"kode_supplier\":\"SUP-261102-81\",\"nama_supplier\":\"aaak\",\"no_telp\":\"0987654320987\",\"email\":\"aaak@gmail.com\",\"alamat\":\"Singapura\",\"mata_uang\":\"SGD\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 05:53:47'),
(39, 10, 'delete', 'supplier', '3', '{\"id\":\"3\",\"kode_supplier\":\"SUP-261102-81\",\"nama_supplier\":\"aaak\",\"no_telp\":\"0987654320987\",\"email\":\"aaak@gmail.com\",\"alamat\":\"Singapura\",\"mata_uang\":\"SGD\",\"created_at\":\"2026-11-02 05:53:47\",\"updated_at\":\"2026-11-02 05:53:47\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 05:54:07'),
(40, 10, 'delete', 'supplier', '2', '{\"id\":\"2\",\"kode_supplier\":\"SUP-261102-39\",\"nama_supplier\":\"aaaa\",\"no_telp\":\"0987654321\",\"email\":\"aaaa@gmail.com\",\"alamat\":\"solo\",\"mata_uang\":\"USD\",\"created_at\":\"2026-11-02 05:51:51\",\"updated_at\":\"2026-11-02 05:51:51\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 06:13:41'),
(41, 10, 'create', 'supplier', '4', '', '{\"kode_supplier\":\"SUP-261102-89\",\"nama_supplier\":\"aaaa\",\"no_telp\":\"098765432134\",\"email\":\"aaaa@gmail.com\",\"alamat\":\"Amerika\",\"mata_uang\":\"USD\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 06:58:03'),
(42, 10, 'create', 'purchase_order', '2', '', '{\"nomor_po\":\"PO-202611-0001\",\"supplier_id\":4,\"tanggal_po\":\"2026-11-02\",\"termin\":\"tunai\",\"tempo_hari\":30,\"jatuh_tempo\":null,\"mata_uang\":\"USD\",\"nilai_kurs\":18132.701322,\"subtotal\":\"110.20\",\"diskon\":\"0.00\",\"pajak\":\"0.00\",\"total\":\"110.20\",\"total_idr\":\"1998223.68\",\"status\":\"approved\",\"catatan\":\"\",\"user_id\":10,\"created_at\":\"2026-11-02 07:06:00\",\"updated_at\":\"2026-11-02 07:06:00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:06:00'),
(43, 10, 'receive', 'purchase_order', '2', '', '{\"status\":\"received\",\"nilai_terima\":\"1998223.60\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:06:25'),
(44, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:20:18'),
(45, 1, 'price_change', 'produk', '5', '{\"harga_beli\":\"99911.18\",\"harga_jual\":\"120000.00\",\"harga_grosir\":\"115000.00\"}', '{\"harga_beli\":\"99911.00\",\"harga_jual\":\"120000.00\",\"harga_grosir\":\"115000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:43:39'),
(46, 1, 'stock_adjustment', 'stok', '5', '{\"qty_lama\":\"20.000\"}', '{\"qty_baru\":\"22\",\"alasan\":\"ada yg ketemu\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:44:59'),
(47, 1, 'stock_adjustment', 'stok', '3', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"47\",\"alasan\":\"Pembaruan massal \\/ Bulk Stock Editor\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 07:47:39'),
(48, 1, 'checkout_pos', 'sales_order', '4', '', '{\"nomor_so\":\"TRX-202611-0002\",\"total\":\"17000000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 08:11:40'),
(49, 1, 'checkout_pos', 'sales_order', '5', '', '{\"nomor_so\":\"TRX-202611-0003\",\"total\":\"16220000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 08:13:50'),
(50, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-02 08:23:25'),
(51, 1, 'checkout_pos', 'sales_order', '6', '', '{\"nomor_so\":\"TRX-202611-0004\",\"total\":\"12250000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 08:25:03'),
(52, 1, 'checkout_pos', 'sales_order', '7', '', '{\"nomor_so\":\"TRX-202611-0005\",\"total\":\"12250000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 08:25:41'),
(53, 1, 'checkout_pos', 'sales_order', '8', '', '{\"nomor_so\":\"TRX-202611-0006\",\"total\":\"1050000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 08:27:27'),
(54, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:13:10'),
(55, 1, 'create', 'sales_order', '9', '', '{\"nomor_so\":\"SO-202611-0001\",\"tanggal_so\":\"2026-11-03\",\"source\":\"manual\",\"pelanggan_id\":4,\"subtotal\":\"15950000.00\",\"diskon_total\":\"200000.00\",\"ppn\":\"1732500.00\",\"ongkir\":\"1000000.00\",\"total\":\"18482500.00\",\"bayar\":\"0.00\",\"kembalian\":\"0.00\",\"metode_bayar\":\"tempo\",\"status\":\"draft\",\"catatan\":\"untuk pengirimannya cepat kak\",\"user_id\":1,\"created_at\":\"2026-11-03 01:15:08\",\"updated_at\":\"2026-11-03 01:15:08\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:15:08'),
(56, 1, 'update_status', 'sales_order', '9', '{\"status_lama\":\"draft\"}', '{\"status_baru\":\"confirmed\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:15:28'),
(57, 1, 'bayar_piutang', 'piutang_usaha', '1', '{\"sisa_lama\":\"18482500.00\"}', '{\"sisa_baru\":\"0.00\",\"bkm\":\"BKM-202611-0007\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:17:03'),
(58, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:30:27'),
(59, 1, 'import_marketplace', 'penjualan_marketplace', '2', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP101A1\",\"total\":\"304000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(60, 1, 'import_marketplace', 'penjualan_marketplace', '3', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP102B2\",\"total\":\"148000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(61, 1, 'import_marketplace', 'penjualan_marketplace', '4', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP103C3\",\"total\":\"366000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(62, 1, 'import_marketplace', 'penjualan_marketplace', '5', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP104D4\",\"total\":\"214000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(63, 1, 'import_marketplace', 'penjualan_marketplace', '6', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP105E5\",\"total\":\"348000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(64, 1, 'import_marketplace', 'penjualan_marketplace', '7', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP106F6\",\"total\":\"280000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(65, 1, 'import_marketplace', 'penjualan_marketplace', '8', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP107G7\",\"total\":\"225000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(66, 1, 'import_marketplace', 'penjualan_marketplace', '9', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP108H8\",\"total\":\"346000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(67, 1, 'import_marketplace', 'penjualan_marketplace', '10', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP109I9\",\"total\":\"286000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(68, 1, 'import_marketplace', 'penjualan_marketplace', '11', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP110J0\",\"total\":\"612000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-03 01:33:18'),
(69, 1, 'import_resi_scraping', 'penjualan_marketplace', '12', '', '{\"resi\":\"SPXID03873198928B\",\"kurir\":\"SPX Express\",\"order\":\"231125GSSBFEUS\",\"pelanggan\":\"Abrori\",\"rfm\":\"Lost\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:40:43'),
(70, 1, 'cascade_delete', 'penjualan_marketplace', '11', '{\"id\":\"11\",\"no_pesanan\":\"261028SHP110J0\",\"source\":\"shopee\",\"nomor_resi\":\"JT0828110010\",\"kurir\":\"J&T Cargo\",\"nama_penerima\":\"Andi Muhammad Yusuf\",\"no_telepon\":\"081911010010\",\"alamat_penerima\":\"Jl. Ratulangi No. 55, Maricaya, Mamajang\",\"total\":\"612000.00\",\"ongkir\":\"42000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 17:15:00\",\"so_id\":\"19\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:42:08'),
(71, 1, 'cascade_delete', 'penjualan_marketplace', '10', '{\"id\":\"10\",\"no_pesanan\":\"261028SHP109I9\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID028109009\",\"kurir\":\"SPX Standard\",\"nama_penerima\":\"Putri Maharani\",\"no_telepon\":\"082299910009\",\"alamat_penerima\":\"Jl. BSD Grand Boulevard, Cluster Foresta Blok B No. 3\",\"total\":\"286000.00\",\"ongkir\":\"16000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 16:05:40\",\"so_id\":\"18\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:42:21'),
(72, 1, 'cascade_delete', 'penjualan_marketplace', '2', '{\"id\":\"2\",\"no_pesanan\":\"261028SHP101A1\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID028101001\",\"kurir\":\"SPX Standard\",\"nama_penerima\":\"Dimas Surya Pratama\",\"no_telepon\":\"081234510001\",\"alamat_penerima\":\"Jl. Manyar Kertoarjo V No. 24, Mulyorejo\",\"total\":\"304000.00\",\"ongkir\":\"14000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 08:30:15\",\"so_id\":\"10\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:42:34'),
(73, 1, 'cascade_delete', 'penjualan_marketplace', '3', '{\"id\":\"3\",\"no_pesanan\":\"261028SHP102B2\",\"source\":\"shopee\",\"nomor_resi\":\"JT0828102002\",\"kurir\":\"J&T Express\",\"nama_penerima\":\"Anisa Rahmawati\",\"no_telepon\":\"085711210002\",\"alamat_penerima\":\"Jl. Dago Asri No. 15, Dago, Coblong\",\"total\":\"148000.00\",\"ongkir\":\"18000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 09:12:00\",\"so_id\":\"11\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:42:49'),
(74, 1, 'cascade_delete', 'penjualan_marketplace', '4', '{\"id\":\"4\",\"no_pesanan\":\"261028SHP103C3\",\"source\":\"shopee\",\"nomor_resi\":\"004928103003\",\"kurir\":\"SiCepat REG\",\"nama_penerima\":\"Bambang Gunawan\",\"no_telepon\":\"081399310003\",\"alamat_penerima\":\"Jl. Tebet Barat Dalam Raya No. 42, Tebet\",\"total\":\"366000.00\",\"ongkir\":\"16000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 10:05:10\",\"so_id\":\"12\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:45:31'),
(75, 1, 'cascade_delete', 'penjualan_marketplace', '9', '{\"id\":\"9\",\"no_pesanan\":\"261028SHP108H8\",\"source\":\"shopee\",\"nomor_resi\":\"004928108008\",\"kurir\":\"SiCepat REG\",\"nama_penerima\":\"I Made Suardana\",\"no_telepon\":\"085244810008\",\"alamat_penerima\":\"Jl. Sunset Road No. 89, Seminyak, Kuta\",\"total\":\"346000.00\",\"ongkir\":\"26000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 15:20:15\",\"so_id\":\"17\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 01:45:43'),
(76, 1, 'cascade_delete', 'penjualan_marketplace', '5', '{\"id\":\"5\",\"no_pesanan\":\"261028SHP104D4\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID028104004\",\"kurir\":\"SPX Hemat\",\"nama_penerima\":\"Yohanes Christian\",\"no_telepon\":\"082177410004\",\"alamat_penerima\":\"Jl. Gejayan No. 8, Condongcatur, Depok\",\"total\":\"214000.00\",\"ongkir\":\"22000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 11:15:30\",\"so_id\":\"13\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:06:55'),
(77, 1, 'cascade_delete', 'penjualan_marketplace', '6', '{\"id\":\"6\",\"no_pesanan\":\"261028SHP105E5\",\"source\":\"shopee\",\"nomor_resi\":\"JT0828105005\",\"kurir\":\"J&T Cargo\",\"nama_penerima\":\"Tri Wahyuni\",\"no_telepon\":\"087855510005\",\"alamat_penerima\":\"Jl. Ijen No. 70, Oro-oro Dowo, Klojen\",\"total\":\"348000.00\",\"ongkir\":\"20000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 12:20:10\",\"so_id\":\"14\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:07:08'),
(78, 1, 'cascade_delete', 'penjualan_marketplace', '8', '{\"id\":\"8\",\"no_pesanan\":\"261028SHP107G7\",\"source\":\"shopee\",\"nomor_resi\":\"JT0828107007\",\"kurir\":\"J&T Express\",\"nama_penerima\":\"Dewi Anggraini\",\"no_telepon\":\"081188710007\",\"alamat_penerima\":\"Jl. Pemuda No. 138, Sekayu, Semarang Tengah\",\"total\":\"225000.00\",\"ongkir\":\"15000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 14:10:20\",\"so_id\":\"16\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:07:17'),
(79, 1, 'cascade_delete', 'penjualan_marketplace', '7', '{\"id\":\"7\",\"no_pesanan\":\"261028SHP106F6\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID028106006\",\"kurir\":\"SPX Instant\",\"nama_penerima\":\"Farhan Ramadhan\",\"no_telepon\":\"089633610006\",\"alamat_penerima\":\"Jl. Ahmad Yani No. 110, Gayungan\",\"total\":\"280000.00\",\"ongkir\":\"10000.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-10-28 13:40:00\",\"so_id\":\"15\",\"created_at\":\"2026-11-03 01:33:18\",\"updated_at\":\"2026-11-03 01:33:18\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:07:30'),
(80, 1, 'import_marketplace', 'penjualan_marketplace', '40', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP101A1\",\"total\":\"304000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(81, 1, 'import_marketplace', 'penjualan_marketplace', '41', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP102B2\",\"total\":\"148000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(82, 1, 'import_marketplace', 'penjualan_marketplace', '42', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP103C3\",\"total\":\"366000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(83, 1, 'import_marketplace', 'penjualan_marketplace', '43', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP104D4\",\"total\":\"214000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(84, 1, 'import_marketplace', 'penjualan_marketplace', '44', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP105E5\",\"total\":\"348000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(85, 1, 'import_marketplace', 'penjualan_marketplace', '45', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP106F6\",\"total\":\"280000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(86, 1, 'import_marketplace', 'penjualan_marketplace', '46', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP107G7\",\"total\":\"225000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(87, 1, 'import_marketplace', 'penjualan_marketplace', '47', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP108H8\",\"total\":\"346000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(88, 1, 'import_marketplace', 'penjualan_marketplace', '48', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP109I9\",\"total\":\"286000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(89, 1, 'import_marketplace', 'penjualan_marketplace', '49', '', '{\"source\":\"shopee\",\"no_pesanan\":\"261028SHP110J0\",\"total\":\"612000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:50:04'),
(90, 1, 'import_marketplace', 'penjualan_marketplace', '50', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70001\",\"total\":\"320000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(91, 1, 'import_marketplace', 'penjualan_marketplace', '51', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70002\",\"total\":\"375000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(92, 1, 'import_marketplace', 'penjualan_marketplace', '52', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70003\",\"total\":\"404000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(93, 1, 'import_marketplace', 'penjualan_marketplace', '53', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70004\",\"total\":\"189000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(94, 1, 'import_marketplace', 'penjualan_marketplace', '54', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70005\",\"total\":\"435000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(95, 1, 'import_marketplace', 'penjualan_marketplace', '55', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70006\",\"total\":\"435000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(96, 1, 'import_marketplace', 'penjualan_marketplace', '56', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70007\",\"total\":\"350000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(97, 1, 'import_marketplace', 'penjualan_marketplace', '57', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70008\",\"total\":\"240000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(98, 1, 'import_marketplace', 'penjualan_marketplace', '58', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70009\",\"total\":\"270000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(99, 1, 'import_marketplace', 'penjualan_marketplace', '59', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70010\",\"total\":\"365000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 02:51:02'),
(100, NULL, 'import_marketplace', 'penjualan_marketplace', '62', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472001\",\"total\":\"320000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(101, NULL, 'import_marketplace', 'penjualan_marketplace', '63', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472002\",\"total\":\"375000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(102, NULL, 'import_marketplace', 'penjualan_marketplace', '64', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472003\",\"total\":\"404000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(103, NULL, 'import_marketplace', 'penjualan_marketplace', '65', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472004\",\"total\":\"189000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(104, NULL, 'import_marketplace', 'penjualan_marketplace', '66', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472005\",\"total\":\"435000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(105, NULL, 'import_marketplace', 'penjualan_marketplace', '67', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472006\",\"total\":\"435000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(106, NULL, 'import_marketplace', 'penjualan_marketplace', '68', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472007\",\"total\":\"350000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(107, NULL, 'import_marketplace', 'penjualan_marketplace', '69', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472008\",\"total\":\"240000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(108, NULL, 'import_marketplace', 'penjualan_marketplace', '70', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472009\",\"total\":\"270000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(109, NULL, 'import_marketplace', 'penjualan_marketplace', '71', '', '{\"source\":\"tiktok\",\"no_pesanan\":\"57929381029472010\",\"total\":\"365000.00\"}', '0.0.0.0', '', '2026-11-03 03:16:28'),
(110, 1, 'retur_penjualan', 'retur_penjualan', '2', '', '{\"nomor_retur\":\"RET_JUAL-202611-0001\",\"so_id\":8,\"pelanggan_id\":\"3\",\"tanggal\":\"2026-11-03\",\"tindak_lanjut\":\"ganti_barang\",\"kondisi_barang\":\"baik\",\"total\":\"1050000.00\",\"status\":\"completed\",\"alasan\":\"salah kirim barang\",\"user_id\":1,\"created_at\":\"2026-11-03 03:34:32\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 03:34:32'),
(111, 1, 'import_marketplace', 'penjualan_marketplace', '72', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP101A1\",\"total\":\"304000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:51'),
(112, 1, 'import_marketplace', 'penjualan_marketplace', '73', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP102B2\",\"total\":\"148000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:51'),
(113, 1, 'import_marketplace', 'penjualan_marketplace', '74', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP103C3\",\"total\":\"366000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:51'),
(114, 1, 'import_marketplace', 'penjualan_marketplace', '75', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP104D4\",\"total\":\"214000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:51'),
(115, 1, 'import_marketplace', 'penjualan_marketplace', '76', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP105E5\",\"total\":\"348000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(116, 1, 'import_marketplace', 'penjualan_marketplace', '77', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP106F6\",\"total\":\"280000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(117, 1, 'import_marketplace', 'penjualan_marketplace', '78', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP107G7\",\"total\":\"225000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(118, 1, 'import_marketplace', 'penjualan_marketplace', '79', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP108H8\",\"total\":\"346000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(119, 1, 'import_marketplace', 'penjualan_marketplace', '80', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP109I9\",\"total\":\"286000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(120, 1, 'import_marketplace', 'penjualan_marketplace', '81', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"261028SHP110J0\",\"total\":\"612000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:39:52'),
(121, 1, 'import_marketplace', 'penjualan_marketplace', '82', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70001\",\"total\":\"320000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(122, 1, 'import_marketplace', 'penjualan_marketplace', '83', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70002\",\"total\":\"375000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(123, 1, 'import_marketplace', 'penjualan_marketplace', '84', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70003\",\"total\":\"404000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(124, 1, 'import_marketplace', 'penjualan_marketplace', '85', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70004\",\"total\":\"189000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(125, 1, 'import_marketplace', 'penjualan_marketplace', '86', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70005\",\"total\":\"435000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(126, 1, 'import_marketplace', 'penjualan_marketplace', '87', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70006\",\"total\":\"435000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(127, 1, 'import_marketplace', 'penjualan_marketplace', '88', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70007\",\"total\":\"350000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(128, 1, 'import_marketplace', 'penjualan_marketplace', '89', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70008\",\"total\":\"240000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(129, 1, 'import_marketplace', 'penjualan_marketplace', '90', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70009\",\"total\":\"270000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(130, 1, 'import_marketplace', 'penjualan_marketplace', '91', '', '{\"source\":\"tokopedia\",\"no_pesanan\":\"INV\\/20261029\\/MPL\\/70010\",\"total\":\"365000.00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 05:40:22'),
(131, 1, 'retur_penjualan', 'retur_penjualan', '3', '', '{\"nomor_retur\":\"RET_JUAL-202611-0002\",\"so_id\":5,\"pelanggan_id\":\"2\",\"tanggal\":\"2026-11-03\",\"tindak_lanjut\":\"ganti_barang\",\"kondisi_barang\":\"baik\",\"total\":\"16220000.00\",\"status\":\"completed\",\"alasan\":\"salah barang\",\"user_id\":1,\"created_at\":\"2026-11-03 06:00:39\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 06:00:39'),
(132, 1, 'create', 'sales_order', '100', '', '{\"nomor_so\":\"SO-202611-0063\",\"tanggal_so\":\"2026-11-03\",\"source\":\"manual\",\"pelanggan_id\":17,\"subtotal\":\"28000.00\",\"diskon_total\":\"0.00\",\"ppn\":\"0.00\",\"ongkir\":\"0.00\",\"total\":\"28000.00\",\"bayar\":\"0.00\",\"kembalian\":\"0.00\",\"metode_bayar\":\"tempo\",\"status\":\"draft\",\"catatan\":\"\",\"user_id\":1,\"created_at\":\"2026-11-03 06:08:00\",\"updated_at\":\"2026-11-03 06:08:00\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 06:08:00'),
(133, 1, 'bayar_piutang', 'piutang_usaha', '2', '{\"sisa_lama\":\"28000.00\"}', '{\"sisa_baru\":\"0.00\",\"bkm\":\"BKM-202611-0008\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 06:08:19'),
(134, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-04 10:29:14'),
(135, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-04 14:09:19'),
(136, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-05 01:16:45'),
(137, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-06 01:50:33'),
(138, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-06 12:34:34'),
(139, 1, 'login', 'users', '1', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 01:24:32'),
(140, 1, 'update_role_permissions', 'role_permissions', '4', NULL, '{\"role_id\":4,\"permissions_count\":20,\"permissions\":[\"menu_dashboard\",\"master_produk\",\"master_pelanggan\",\"master_supplier\",\"master_kategori\",\"master_satuan\",\"stok_dashboard\",\"stok_opname\",\"stok_export\",\"po_list\",\"po_create\",\"po_receive\",\"retur_pembelian\",\"stok_opname_approve\",\"utang_usaha_export\",\"menu_pembelian\",\"menu_purchase_order_(po)\",\"retur_pembelian_export\",\"menu_transaksi_penjualan\",\"menu_manajemen_stok\"]}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 01:29:31'),
(141, 10, 'login', 'users', '10', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-07 01:31:36'),
(142, NULL, 'import_resi_scraping', 'penjualan_marketplace', '93', '', '{\"resi\":\"SPXTEST1794020493762\",\"kurir\":\"SPX Express\",\"order\":\"TEST-PESANAN-1794020493762\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"New Customers\"}', '0.0.0.0', '', '2026-11-07 03:01:33'),
(143, NULL, 'import_resi_scraping', 'penjualan_marketplace', '94', '', '{\"resi\":\"SPXTEST1794020580702\",\"kurir\":\"SPX Express\",\"order\":\"TEST-PESANAN-1794020580702\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalist\"}', '0.0.0.0', '', '2026-11-07 03:03:01'),
(144, NULL, 'import_resi_scraping', 'penjualan_marketplace', '95', '', '{\"resi\":\"SPXTEST1794020640681\",\"kurir\":\"SPX Express\",\"order\":\"TEST-PESANAN-1794020640681\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalist\"}', '0.0.0.0', '', '2026-11-07 03:04:00'),
(145, 1, 'import_resi_scraping', 'penjualan_marketplace', '96', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"New Customers\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:10:38'),
(146, 10, 'logout', 'users', '10', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-07 03:19:45'),
(147, 11, 'login', 'users', '11', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-07 03:19:56'),
(148, 11, 'logout', 'users', '11', '', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-07 03:22:23'),
(149, 12, 'login', 'users', '12', '', '{\"ip\":\"::1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-07 03:22:31'),
(150, 1, 'update_role_permissions', 'role_permissions', '6', NULL, '{\"role_id\":6,\"permissions_count\":10,\"permissions\":[\"menu_dashboard\",\"penjualan_invoice\",\"ar_piutang\",\"bkm_masuk\",\"ap_utang\",\"bkk_keluar\",\"gl_buku_besar\",\"keuangan_jurnal_posting\",\"menu_utang_usaha_(ap)\",\"menu_pengeluaran_kas_(bkk)\"]}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:23:27'),
(151, 1, 'stock_adjustment', 'stok', '29', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"51.000\",\"alasan\":\"Reversal \\/ Pembatalan Pesanan Marketplace #2403264089DMQ5\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:49:37'),
(152, 1, 'cascade_delete', 'penjualan_marketplace', '96', '{\"id\":\"96\",\"no_pesanan\":\"2403264089DMQ5\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"nama_penerima\":\"Aldi ardiansyah\",\"no_telepon\":\"\",\"alamat_penerima\":\"[1], Jalan Jamika Gg., RT.2\\/RW.6, Suka Asih, Bojongloa Kaler (Gang, dunia mobil), BOJONGLOA KALER, KOTA, BOJONGLOA KALER JAMIKA\",\"total\":\"41040.00\",\"ongkir\":\"0.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2026-11-07 03:10:38\",\"so_id\":\"105\",\"created_at\":\"2026-11-07 03:10:38\",\"updated_at\":\"2026-11-07 03:10:38\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:49:37'),
(153, 1, 'import_resi_scraping', 'penjualan_marketplace', '97', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:50:02'),
(154, 1, 'import_resi_scraping', 'penjualan_marketplace', '98', '', '{\"resi\":\"SPXID034362850519\",\"kurir\":\"SPX Express\",\"order\":\"230916G4NPPKN9\",\"pelanggan\":\"Solekan\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 03:53:40'),
(155, 1, 'stock_adjustment', 'stok', '30', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"51.000\",\"alasan\":\"Reversal \\/ Pembatalan Pesanan Marketplace #230916G4NPPKN9\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:07:19');
INSERT INTO `audit_trail` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(156, 1, 'cascade_delete', 'penjualan_marketplace', '98', '{\"id\":\"98\",\"no_pesanan\":\"230916G4NPPKN9\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID034362850519\",\"kurir\":\"SPX Express\",\"nama_penerima\":\"Solekan\",\"no_telepon\":\"\",\"alamat_penerima\":\"Dsn blokagung Rt\\/Rw 003\\/003, kel\\/desa, KARANGDORO,kecamatan, TEGALSARI,sebelah utara KKT SRI LESTARI, TEGALSARI, KAB. BANYUWANGI, JAWA\",\"total\":\"26896.00\",\"ongkir\":\"0.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2023-09-19 03:53:39\",\"so_id\":\"107\",\"created_at\":\"2026-11-07 03:53:39\",\"updated_at\":\"2026-11-07 03:53:39\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:07:19'),
(157, 1, 'stock_adjustment', 'stok', '29', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"51.000\",\"alasan\":\"Reversal \\/ Pembatalan Pesanan Marketplace #2403264089DMQ5\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:07:26'),
(158, 1, 'cascade_delete', 'penjualan_marketplace', '97', '{\"id\":\"97\",\"no_pesanan\":\"2403264089DMQ5\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"nama_penerima\":\"Aldi ardiansyah\",\"no_telepon\":\"\",\"alamat_penerima\":\"[1], Jalan Jamika Gg., RT.2\\/RW.6, Suka Asih, Bojongloa Kaler (Gang, dunia mobil), BOJONGLOA KALER, KOTA, BOJONGLOA KALER JAMIKA\",\"total\":\"41040.00\",\"ongkir\":\"0.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2024-03-28 03:50:02\",\"so_id\":\"106\",\"created_at\":\"2026-11-07 03:50:02\",\"updated_at\":\"2026-11-07 03:50:02\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:07:26'),
(159, 1, 'import_resi_scraping', 'penjualan_marketplace', '99', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:08:09'),
(160, 1, 'import_resi_scraping', 'penjualan_marketplace', '100', '', '{\"resi\":\"240616614JWWV6\",\"kurir\":\"SPX Express\",\"order\":\"240616614JWWV6\",\"pelanggan\":\"Fahmi limawan\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 04:11:04'),
(161, 1, 'import_resi_scraping', 'penjualan_marketplace', '101', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:15:22'),
(162, 1, 'stock_adjustment', 'stok', '34', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"51.000\",\"alasan\":\"Reversal \\/ Pembatalan Pesanan Marketplace #2403264089DMQ5\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:20:12'),
(163, 1, 'cascade_delete', 'penjualan_marketplace', '101', '{\"id\":\"101\",\"no_pesanan\":\"2403264089DMQ5\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"nama_penerima\":\"Aldi ardiansyah\",\"no_telepon\":\"\",\"alamat_penerima\":\"[1], Jalan Jamika Gg., RT.2\\/RW.6, Suka Asih, Bojongloa Kaler (Gang, dunia mobil), BOJONGLOA KALER, KOTA, BOJONGLOA KALER JAMIKA\",\"total\":\"41040.00\",\"ongkir\":\"0.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2024-03-28 05:15:22\",\"so_id\":\"110\",\"created_at\":\"2026-11-07 05:15:22\",\"updated_at\":\"2026-11-07 05:15:22\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:20:12'),
(164, 1, 'import_resi_scraping', 'penjualan_marketplace', '102', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:20:32'),
(165, 1, 'stock_adjustment', 'stok', '34', '{\"qty_lama\":\"50.000\"}', '{\"qty_baru\":\"51.000\",\"alasan\":\"Reversal \\/ Pembatalan Pesanan Marketplace #2403264089DMQ5\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:20:46'),
(166, 1, 'cascade_delete', 'penjualan_marketplace', '102', '{\"id\":\"102\",\"no_pesanan\":\"2403264089DMQ5\",\"source\":\"shopee\",\"nomor_resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"nama_penerima\":\"Aldi ardiansyah\",\"no_telepon\":\"\",\"alamat_penerima\":\"[1], Jalan Jamika Gg., RT.2\\/RW.6, Suka Asih, Bojongloa Kaler (Gang, dunia mobil), BOJONGLOA KALER, KOTA, BOJONGLOA KALER JAMIKA\",\"total\":\"41040.00\",\"ongkir\":\"0.00\",\"voucher\":\"0.00\",\"biaya_layanan\":\"0.00\",\"status\":\"completed\",\"tanggal_pesanan\":\"2024-03-28 05:20:32\",\"so_id\":\"111\",\"created_at\":\"2026-11-07 05:20:32\",\"updated_at\":\"2026-11-07 05:20:32\",\"deleted_at\":null}', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:20:46'),
(167, 1, 'import_resi_scraping', 'penjualan_marketplace', '103', '', '{\"resi\":\"SPXID042978248603\",\"kurir\":\"SPX Express\",\"order\":\"2403264089DMQ5\",\"pelanggan\":\"Aldi ardiansyah\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:53:40'),
(168, 1, 'import_resi_scraping', 'penjualan_marketplace', '104', '', '{\"resi\":\"240616614JWWV6\",\"kurir\":\"SPX Instant\",\"order\":\"240616614JWWV6\",\"pelanggan\":\"Fahmi limawan\",\"rfm\":\"Potential Loyalists\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 OPR/138.0.0.0', '2026-11-07 05:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `calon_customer`
--

CREATE TABLE `calon_customer` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bisnis` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_wa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_daftar` datetime NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calon_customer`
--

INSERT INTO `calon_customer` (`id`, `nama`, `bisnis`, `no_wa`, `email`, `username`, `password_hash`, `waktu_daftar`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'Rian Firmansyah', 'Toko Rian Komputer & Gadget', '081298761234', 'rian@gadgethub.id', 'rian_demo', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-30 09:15:00', 'rejected', 'Ingin mencoba POS Kasir Cepat & Manajemen Stok Multi Cabang', '2026-10-31 10:42:47', '2026-11-01 04:04:48'),
(2, 'Siti Nurhaliza', 'Butik Cantika Mode', '085711223344', 'cantika@modefashion.com', 'cantika_demo', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-30 11:40:00', 'rejected', 'Kebutuhan CRM after sales WA & analisis retensi pelanggan', '2026-10-31 10:42:47', '2026-11-01 04:04:45'),
(3, 'Hendro Wibowo', 'Distributor Sembako Makmur', '082133445566', 'hendro@makmurjaya.co.id', 'hendro_dist', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-29 14:20:00', 'approved', 'Aktivasi uji coba 14 hari disetujui.', '2026-10-31 10:42:47', NULL),
(4, 'Mega Lestari', 'Apotek Sehat Barokah', '087855667788', 'mega@sehatbarokah.id', 'mega_apotek', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-28 10:05:00', 'approved', 'Aktivasi uji coba 14 hari disetujui.', '2026-10-31 10:42:47', NULL),
(5, 'Doni Prasetyo', 'Kafe Aroma Nusantara', '089612345678', 'doni@aromanusantara.com', 'doni_kafe', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-27 16:30:00', 'approved', 'Aktivasi uji coba 14 hari disetujui.', '2026-10-31 10:42:47', NULL),
(6, 'Bambang Sudibyo', 'Bengkel Motor Berkah', '081399887766', 'bambang@motorberkah.co.id', 'bambang_bengkel', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-26 13:10:00', 'approved', 'Aktivasi uji coba 14 hari disetujui.', '2026-10-31 10:42:47', NULL),
(7, 'Agus Salim', 'Toko Bangunan Sentosa', '085288776655', 'agus@sentosabangunan.com', 'agus_sentosa', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-25 08:45:00', 'approved', 'Aktivasi uji coba 14 hari disetujui.', '2026-10-31 10:42:47', NULL),
(8, 'Anonymous Test', 'Spam Registrasi', '080000000000', 'fake@test.invalid', 'fake_user', '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', '2026-10-24 22:00:00', 'rejected', 'Data kontak WhatsApp dan identitas bisnis tidak valid.', '2026-10-31 10:42:47', NULL),
(9, 'acoy', 'acoy', '012903910', 'acoy@gmail.com', 'acoy', '$2y$10$Ng6IMviDPj1XpMOxMLY5JefG.m/HfxaOiukXQfxB2UAQ6k39OuHom', '2026-11-01 04:05:56', 'approved', 'Paket Lengkap ERP, POS & CRM Enterprise', '2026-11-01 04:05:56', '2026-11-01 04:06:15'),
(10, 'axoy', 'axoy', '09876543109', 'axoy@gmail.com', 'axoy', '$2y$10$q9lJQDMIHa38i2QIXl4Igev/z.wHkTjegEgEcbJfB0hWAmAHxgbJy', '2026-11-02 04:02:37', 'approved', 'Paket Lengkap ERP, POS & CRM Enterprise', '2026-11-02 04:02:37', '2026-11-02 04:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `coa`
--

CREATE TABLE `coa` (
  `id` int UNSIGNED NOT NULL,
  `kode_akun` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akun` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('aset','kewajiban','ekuitas','pendapatan','beban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi_normal` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coa`
--

INSERT INTO `coa` (`id`, `kode_akun`, `nama_akun`, `tipe`, `posisi_normal`, `created_at`, `updated_at`) VALUES
(1, '1110', 'Kas Tunai Toko', 'aset', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(2, '1120', 'Bank BCA Operasional', 'aset', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, '1130', 'Kas QRIS Settlement', 'aset', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, '1140', 'Piutang Usaha', 'aset', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(5, '1150', 'Persediaan Barang Dagang', 'aset', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(6, '2110', 'Utang Usaha Supplier', 'kewajiban', 'kredit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(7, '3110', 'Modal Pemilik', 'ekuitas', 'kredit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(8, '4110', 'Pendapatan Penjualan', 'pendapatan', 'kredit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(9, '5110', 'Harga Pokok Penjualan (HPP)', 'beban', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(10, '5210', 'Beban Operasional & Ekspedisi', 'beban', 'debit', '2026-10-30 14:52:11', '2026-10-30 14:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `crm_followup_categories`
--

CREATE TABLE `crm_followup_categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `days` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'H+0',
  `template` text COLLATE utf8mb4_general_ci,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Aktif',
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crm_followup_categories`
--

INSERT INTO `crm_followup_categories` (`id`, `name`, `code`, `days`, `template`, `status`, `order_index`, `created_at`, `updated_at`) VALUES
(1, 'Konfirmasi Pengiriman', 'KONFIRMASI_KIRIM', 'H+0', 'Halo Kak {{nama_customer}} 🙏\n\nPesanan Kakak dengan nomor {{no_pesanan}} sedang dalam proses pengiriman ya.\n\nNo. Resi: {{nomor_resi}}\nEkspedisi: {{kurir}}', 'Aktif', 0, '2026-11-07 06:16:50', NULL),
(2, 'Cek Barang Sampai', 'H_3_CEK_SAMPAI', 'H+3', 'Halo Kak {{nama_customer}} 😊\n\nApakah pesanan Kakak ({{no_pesanan}}) sudah diterima dengan baik?\n\nProduk: {{produk}}\nDikirim tanggal: {{tanggal_kirim}}', 'Aktif', 1, '2026-11-07 06:16:50', NULL),
(3, 'Tanya Pengalaman', 'H_7_TANYA_PENGALAMAN', 'H+7', 'Halo Kak {{nama_customer}} ✨\n\nBagaimana pengalaman Kakak memakai produk {{produk}} kami? Jika ada kendala teknis atau saran, silakan balas pesan ini ya kak!', 'Aktif', 2, '2026-11-07 06:16:50', NULL),
(4, 'Evaluasi Produk', 'H_11_EVALUASI', 'H+11', 'Halo Kak {{nama_customer}}! Kami sedang mengevaluasi kualitas pesanan {{no_pesanan}}. Ada voucher khusus untuk Kakak belanja berikutnya!', 'Aktif', 3, '2026-11-07 06:16:50', NULL),
(5, 'Tawarkan Repeat Order', 'H_30_REPEAT_ORDER', 'H+30', 'Halo Kak {{nama_customer}} 🎁\n\nStok produk {{produk}} Kakak mungkin sudah mulai menipis? Yuk restock sekarang dan dapatkan diskon member 10%!', 'Aktif', 4, '2026-11-07 06:16:50', NULL),
(6, 'Reaktivasi Pelanggan Lama', 'H_100_REAKTIVASI', 'H+100', 'Halo Kak {{nama_customer}}, kami rindu kehadiran Kakak di Metafora! Cek koleksi produk terbaru kami hari ini.', 'Aktif', 5, '2026-11-07 06:16:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `crm_followup_notes`
--

CREATE TABLE `crm_followup_notes` (
  `id` int UNSIGNED NOT NULL,
  `pelanggan_id` int UNSIGNED DEFAULT NULL,
  `customer_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `label` enum('New','Follow-up','Closed','Repeat Order') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` enum('inbound','outbound') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outbound',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_followup_notes`
--

INSERT INTO `crm_followup_notes` (`id`, `pelanggan_id`, `customer_phone`, `user_id`, `label`, `catatan`, `direction`, `created_at`) VALUES
(1, 5, '082257189117', 1, 'Follow-up', 'Resi SPX Express #SPXID03873198928B berhasil diimpor & diverifikasi untuk pesanan 231125GSSBFEUS. Follow-up H+0, H+3, H+7 aktif.', 'outbound', '2026-11-03 01:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `crm_transaction_followup_labels`
--

CREATE TABLE `crm_transaction_followup_labels` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `order_source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_code` enum('H+0','H+3','H+7') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_date` date NOT NULL,
  `status` enum('pending','sent','failed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `sent_at` datetime DEFAULT NULL,
  `message_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_transaction_followup_labels`
--

INSERT INTO `crm_transaction_followup_labels` (`id`, `order_id`, `user_id`, `order_source`, `customer_nama`, `customer_phone`, `label_code`, `target_date`, `status`, `sent_at`, `message_log`, `retry_count`, `created_at`, `updated_at`) VALUES
(124, 80, 1, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+0', '2026-11-03', 'sent', '2026-11-06 02:13:52', 'Halo kak Dimas Surya Pratama, terima kasih telah berbelanja di Metafora! Pesanan Anda #SPXID028101001 (Kopi Arabika Aceh Gayo Washed Grade 1 250g) sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!', 0, '2026-11-03 05:39:51', '2026-11-06 02:13:52'),
(125, 80, NULL, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Dimas Surya Pratama, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(126, 80, NULL, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Dimas Surya Pratama, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(127, 81, 1, 'tokopedia', 'Anisa Rahmawati', '6285711210002', 'H+0', '2026-11-03', 'sent', '2026-11-06 02:25:11', 'Halo kak Anisa Rahmawati, terima kasih telah berbelanja di Metafora! Pesanan Anda #JT0828102002 (Sirup Gourmet Salted Caramel 750ml Artisan Glass) sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!', 0, '2026-11-03 05:39:51', '2026-11-06 02:25:11'),
(128, 81, NULL, 'tokopedia', 'Anisa Rahmawati', '6285711210002', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Anisa Rahmawati, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(129, 81, NULL, 'tokopedia', 'Anisa Rahmawati', '6285711210002', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Anisa Rahmawati, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(130, 82, 1, 'tokopedia', 'Bambang Gunawan', '6281399310003', 'H+0', '2026-11-03', 'sent', '2026-11-08 16:53:56', 'Halo Kak Bambang Gunawan, pesanan #82 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:51', '2026-11-08 16:53:56'),
(131, 82, NULL, 'tokopedia', 'Bambang Gunawan', '6281399310003', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Bambang Gunawan, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(132, 82, NULL, 'tokopedia', 'Bambang Gunawan', '6281399310003', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Bambang Gunawan, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(133, 83, 1, 'tokopedia', 'Yohanes Christian', '6282177410004', 'H+0', '2026-11-03', 'sent', '2026-11-09 03:30:35', 'Halo Kak Yohanes Christian, pesanan #83 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:51', '2026-11-09 03:30:35'),
(134, 83, NULL, 'tokopedia', 'Yohanes Christian', '6282177410004', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Yohanes Christian, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(135, 83, NULL, 'tokopedia', 'Yohanes Christian', '6282177410004', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Yohanes Christian, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(136, 84, 1, 'tokopedia', 'Tri Wahyuni', '6287855510005', 'H+0', '2026-11-03', 'sent', '2026-11-06 02:31:17', 'Halo Kak Tri Wahyuni, pesanan #84 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:51', '2026-11-06 02:31:17'),
(137, 84, NULL, 'tokopedia', 'Tri Wahyuni', '6287855510005', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Tri Wahyuni, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(138, 84, NULL, 'tokopedia', 'Tri Wahyuni', '6287855510005', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Tri Wahyuni, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(139, 85, 1, 'tokopedia', 'Farhan Ramadhan', '6289633610006', 'H+0', '2026-11-03', 'sent', '2026-11-06 02:24:50', 'Halo Kak Farhan Ramadhan, pesanan #85 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:52', '2026-11-06 02:24:50'),
(140, 85, 1, 'tokopedia', 'Farhan Ramadhan', '6289633610006', 'H+3', '2026-11-06', 'sent', '2026-11-08 16:54:09', 'Halo Kak Farhan Ramadhan, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:52', '2026-11-08 16:54:09'),
(141, 85, NULL, 'tokopedia', 'Farhan Ramadhan', '6289633610006', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Farhan Ramadhan, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(142, 86, 1, 'tokopedia', 'Dewi Anggraini', '6281188710007', 'H+0', '2026-11-03', 'sent', '2026-11-09 08:21:31', 'Halo Kak Dewi Anggraini, pesanan #86 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:52', '2026-11-09 08:21:31'),
(143, 86, NULL, 'tokopedia', 'Dewi Anggraini', '6281188710007', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Dewi Anggraini, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(144, 86, NULL, 'tokopedia', 'Dewi Anggraini', '6281188710007', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Dewi Anggraini, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(145, 87, NULL, 'tokopedia', 'I Made Suardana', '6285244810008', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak I Made Suardana, pesanan #87 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(146, 87, NULL, 'tokopedia', 'I Made Suardana', '6285244810008', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak I Made Suardana, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(147, 87, NULL, 'tokopedia', 'I Made Suardana', '6285244810008', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak I Made Suardana, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(148, 88, NULL, 'tokopedia', 'Putri Maharani', '6282299910009', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Putri Maharani, pesanan #88 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(149, 88, NULL, 'tokopedia', 'Putri Maharani', '6282299910009', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Putri Maharani, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(150, 88, NULL, 'tokopedia', 'Putri Maharani', '6282299910009', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Putri Maharani, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(151, 89, NULL, 'tokopedia', 'Andi Muhammad Yusuf', '6281911010010', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Andi Muhammad Yusuf, pesanan #89 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(152, 89, NULL, 'tokopedia', 'Andi Muhammad Yusuf', '6281911010010', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Andi Muhammad Yusuf, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(153, 89, NULL, 'tokopedia', 'Andi Muhammad Yusuf', '6281911010010', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Andi Muhammad Yusuf, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(154, 90, NULL, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Dimas Surya Pratama, pesanan #90 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(155, 90, NULL, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Dimas Surya Pratama, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(156, 90, NULL, 'tokopedia', 'Dimas Surya Pratama', '6281234510001', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Dimas Surya Pratama, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(157, 91, NULL, 'tokopedia', 'Siti Nurhaliza', '6281234510002', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Siti Nurhaliza, pesanan #91 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(158, 91, NULL, 'tokopedia', 'Siti Nurhaliza', '6281234510002', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Siti Nurhaliza, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(159, 91, NULL, 'tokopedia', 'Siti Nurhaliza', '6281234510002', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Siti Nurhaliza, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(160, 92, NULL, 'tokopedia', 'Hendra Setiawan', '6281234510003', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Hendra Setiawan, pesanan #92 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(161, 92, NULL, 'tokopedia', 'Hendra Setiawan', '6281234510003', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Hendra Setiawan, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(162, 92, NULL, 'tokopedia', 'Hendra Setiawan', '6281234510003', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Hendra Setiawan, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(163, 93, NULL, 'tokopedia', 'Ratna Dewi Wulandari', '6281234510004', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Ratna Dewi Wulandari, pesanan #93 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(164, 93, NULL, 'tokopedia', 'Ratna Dewi Wulandari', '6281234510004', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Ratna Dewi Wulandari, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(165, 93, NULL, 'tokopedia', 'Ratna Dewi Wulandari', '6281234510004', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Ratna Dewi Wulandari, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(166, 94, NULL, 'tokopedia', 'Ahmad Syukri Lubis', '6281234510005', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Ahmad Syukri Lubis, pesanan #94 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(167, 94, NULL, 'tokopedia', 'Ahmad Syukri Lubis', '6281234510005', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Ahmad Syukri Lubis, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(168, 94, NULL, 'tokopedia', 'Ahmad Syukri Lubis', '6281234510005', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Ahmad Syukri Lubis, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(169, 95, NULL, 'tokopedia', 'Rian Pratama Kusuma', '6281234510006', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Rian Pratama Kusuma, pesanan #95 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(170, 95, NULL, 'tokopedia', 'Rian Pratama Kusuma', '6281234510006', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Rian Pratama Kusuma, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(171, 95, NULL, 'tokopedia', 'Rian Pratama Kusuma', '6281234510006', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Rian Pratama Kusuma, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(172, 96, NULL, 'tokopedia', 'Eka Putri Lestari', '6281234510007', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Eka Putri Lestari, pesanan #96 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(173, 96, NULL, 'tokopedia', 'Eka Putri Lestari', '6281234510007', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Eka Putri Lestari, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(174, 96, NULL, 'tokopedia', 'Eka Putri Lestari', '6281234510007', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Eka Putri Lestari, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(175, 97, NULL, 'tokopedia', 'Farhan Ramadhan', '6281234510008', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Farhan Ramadhan, pesanan #97 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(176, 97, NULL, 'tokopedia', 'Farhan Ramadhan', '6281234510008', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Farhan Ramadhan, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(177, 97, NULL, 'tokopedia', 'Farhan Ramadhan', '6281234510008', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Farhan Ramadhan, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(178, 98, NULL, 'tokopedia', 'I Ketut Suardana', '6281234510009', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak I Ketut Suardana, pesanan #98 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(179, 98, NULL, 'tokopedia', 'I Ketut Suardana', '6281234510009', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak I Ketut Suardana, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(180, 98, NULL, 'tokopedia', 'I Ketut Suardana', '6281234510009', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak I Ketut Suardana, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(181, 99, NULL, 'tokopedia', 'Andi Nurul Aisyah', '6281234510010', 'H+0', '2026-11-03', 'pending', NULL, 'Halo Kak Andi Nurul Aisyah, pesanan #99 telah dikirim via Ekspedisi dengan No. Resi: -. Terima kasih telah berbelanja di Metafora Enterprise!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(182, 99, NULL, 'tokopedia', 'Andi Nurul Aisyah', '6281234510010', 'H+3', '2026-11-06', 'pending', NULL, 'Halo Kak Andi Nurul Aisyah, paket No. Resi - diperkirakan sudah tiba. Apakah barang sudah diterima dalam kondisi baik dan lengkap?', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(183, 99, NULL, 'tokopedia', 'Andi Nurul Aisyah', '6281234510010', 'H+7', '2026-11-10', 'pending', NULL, 'Halo Kak Andi Nurul Aisyah, bagaimana pengalaman menggunakan produk kami? Jangan ragu konsultasi ya, dapatkan voucher diskon untuk repeat order berikutnya!', 0, '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(301, 128, 1, 'shopee', 'Siti Rahmawati', '6281298765432', 'H+0', '2026-11-01', 'sent', NULL, NULL, 0, '2026-11-01 14:56:04', '2026-11-01 14:56:04'),
(302, 128, 1, 'shopee', 'Siti Rahmawati', '6281298765432', 'H+3', '2026-11-04', 'sent', NULL, NULL, 0, '2026-11-01 14:56:04', '2026-11-01 14:56:04'),
(303, 128, 1, 'shopee', 'Siti Rahmawati', '6281298765432', 'H+7', '2026-11-08', 'sent', NULL, NULL, 0, '2026-11-01 14:56:04', '2026-11-01 14:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `devtools_crud`
--

CREATE TABLE `devtools_crud` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_status` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devtools_crud`
--

INSERT INTO `devtools_crud` (`id`, `title`, `subject`, `table_name`, `sync_status`, `created_at`, `updated_at`) VALUES
(1, 'Voucher Kas Masuk (BKM)', 'Voucher Kas Masuk (BKM)', 'pembayaran_piutang', '', '2026-10-31 10:42:47', NULL),
(2, 'Voucher Kas Keluar (BKK)', 'Kas Keluar', 'pembayaran_utang', '', '2026-10-31 10:42:47', NULL),
(3, 'Ledger Piutang Usaha (AR)', 'Ledger Piutang Usaha (AR)', 'piutang_usaha', '', '2026-10-31 10:42:47', NULL),
(4, 'Ledger Utang Usaha (AP)', 'Utang Usaha', 'utang_usaha', 'new 3 field need to update', '2026-10-31 10:42:47', NULL),
(5, 'Stock Opname Fisik', 'Stock Opname Fisik', 'stock_opname', '', '2026-10-31 10:42:47', NULL),
(6, 'Retur Penjualan Customer', 'Retur Penjualan Customer', 'retur_penjualan', '', '2026-10-31 10:42:47', NULL),
(7, 'Faktur Penjualan (Invoice)', 'Faktur Penjualan (Invoice)', 'invoice', 'new 1 field need to update', '2026-10-31 10:42:47', NULL),
(8, 'Sales Order (SO)', 'Sales Order (SO)', 'sales_order', 'new 44 field need to update', '2026-10-31 10:42:47', NULL),
(9, 'Retur Pembelian Supplier', 'Retur Pembelian Supplier', 'retur_pembelian', 'new 1 field need to update', '2026-10-31 10:42:47', NULL),
(10, 'Purchase Order (PO)', 'Pesanan Pembelian', 'purchase_order', '', '2026-10-31 10:42:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `devtools_extension`
--

CREATE TABLE `devtools_extension` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDMETAFORA',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devtools_extension`
--

INSERT INTO `devtools_extension` (`id`, `name`, `author`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cicool component bundle', 'cicool', 'cicool default component', 'active', '2026-10-31 10:42:47', NULL),
(2, 'cicool editor', 'cicool', 'cicool default editor', 'active', '2026-10-31 10:42:47', NULL),
(3, 'cicool page form builder', 'cicool', 'cicool form builder page element widget editor', 'active', '2026-10-31 10:42:47', NULL),
(4, 'cicool page form builder (element)', 'cicool', 'cicool form builder page element widget editor', 'active', '2026-10-31 10:42:47', NULL),
(5, 'cicool medium font', 'cicool', 'cicool medium font editor', 'active', '2026-10-31 10:42:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int UNSIGNED NOT NULL,
  `nomor_invoice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_id` int UNSIGNED NOT NULL,
  `pelanggan_id` int UNSIGNED DEFAULT NULL,
  `tanggal_invoice` date NOT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `total` decimal(18,2) NOT NULL,
  `status` enum('paid','unpaid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `nomor_invoice`, `so_id`, `pelanggan_id`, `tanggal_invoice`, `jatuh_tempo`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'INV-202610-0001', 1, 1, '2026-10-30', NULL, 3500000.00, 'paid', '2026-10-31 10:34:22', NULL),
(2, 'INV-202610-0002', 2, 1, '2026-10-31', '2026-10-31', 11200000.00, 'paid', '2026-10-31 14:57:44', '2026-10-31 14:57:44'),
(3, 'INV-202611-0001', 3, 1, '2026-11-01', '2026-11-01', 4800000.00, 'paid', '2026-11-01 08:57:07', '2026-11-01 08:57:07'),
(4, 'INV-202611-0002', 4, 1, '2026-11-02', '2026-11-02', 17000000.00, 'paid', '2026-11-02 08:11:40', '2026-11-02 08:11:40'),
(5, 'INV-202611-0003', 5, 2, '2026-11-02', '2026-11-02', 16220000.00, 'paid', '2026-11-02 08:13:50', '2026-11-02 08:13:50'),
(6, 'INV-202611-0004', 6, 1, '2026-11-02', '2026-11-02', 12250000.00, 'paid', '2026-11-02 08:25:03', '2026-11-02 08:25:03'),
(7, 'INV-202611-0005', 7, 1, '2026-11-02', '2026-11-02', 12250000.00, 'paid', '2026-11-02 08:25:41', '2026-11-02 08:25:41'),
(8, 'INV-202611-0006', 8, 3, '2026-11-02', '2026-11-02', 1050000.00, 'paid', '2026-11-02 08:27:27', '2026-11-02 08:27:27'),
(9, 'INV-202611-0007', 9, 4, '2026-11-03', '2026-12-03', 18482500.00, 'paid', '2026-11-03 01:15:08', '2026-11-03 01:17:03'),
(51, 'INV-202611-0049', 80, 7, '2026-10-28', '2026-10-28', 304000.00, 'paid', '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(52, 'INV-202611-0050', 81, 8, '2026-10-28', '2026-10-28', 148000.00, 'paid', '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(53, 'INV-202611-0051', 82, 9, '2026-10-28', '2026-10-28', 366000.00, 'paid', '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(54, 'INV-202611-0052', 83, 10, '2026-10-28', '2026-10-28', 214000.00, 'paid', '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(55, 'INV-202611-0053', 84, 11, '2026-10-28', '2026-10-28', 348000.00, 'paid', '2026-11-03 05:39:51', '2026-11-03 05:39:51'),
(56, 'INV-202611-0054', 85, 12, '2026-10-28', '2026-10-28', 280000.00, 'paid', '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(57, 'INV-202611-0055', 86, 13, '2026-10-28', '2026-10-28', 225000.00, 'paid', '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(58, 'INV-202611-0056', 87, 14, '2026-10-28', '2026-10-28', 346000.00, 'paid', '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(59, 'INV-202611-0057', 88, 15, '2026-10-28', '2026-10-28', 286000.00, 'paid', '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(60, 'INV-202611-0058', 89, 16, '2026-10-28', '2026-10-28', 612000.00, 'paid', '2026-11-03 05:39:52', '2026-11-03 05:39:52'),
(61, 'INV-202611-0059', 90, 7, '2026-10-29', '2026-10-29', 320000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(62, 'INV-202611-0060', 91, 17, '2026-10-29', '2026-10-29', 375000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(63, 'INV-202611-0061', 92, 18, '2026-10-29', '2026-10-29', 404000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(64, 'INV-202611-0062', 93, 19, '2026-10-29', '2026-10-29', 189000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(65, 'INV-202611-0063', 94, 20, '2026-10-29', '2026-10-29', 435000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(66, 'INV-202611-0064', 95, 21, '2026-10-29', '2026-10-29', 435000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(67, 'INV-202611-0065', 96, 22, '2026-10-29', '2026-10-29', 350000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(68, 'INV-202611-0066', 97, 23, '2026-10-29', '2026-10-29', 240000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(69, 'INV-202611-0067', 98, 24, '2026-10-29', '2026-10-29', 270000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(70, 'INV-202611-0068', 99, 25, '2026-10-29', '2026-10-29', 365000.00, 'paid', '2026-11-03 05:40:22', '2026-11-03 05:40:22'),
(71, 'INV-202611-0069', 100, 17, '2026-11-03', '2026-12-03', 28000.00, 'paid', '2026-11-03 06:08:00', '2026-11-03 06:08:19'),
(129, 'INV-202611-0096', 128, 6, '2026-11-01', '2026-11-01', 371000.00, 'paid', '2026-11-01 14:56:04', '2026-11-01 14:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `id` int UNSIGNED NOT NULL,
  `nomor_jurnal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `referensi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_debit` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_kredit` decimal(18,2) NOT NULL DEFAULT '0.00',
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurnal`
--

INSERT INTO `jurnal` (`id`, `nomor_jurnal`, `tanggal`, `referensi`, `keterangan`, `total_debit`, `total_kredit`, `user_id`, `created_at`) VALUES
(1, 'JRN-202610-0001', '2026-10-31', 'TRX-202610-0001', 'Penjualan Kasir POS #TRX-202610-0001 (BKM-202610-0001)', 11200000.00, 11200000.00, 1, '2026-10-31 14:57:44'),
(2, 'JRN-202611-0001', '2026-11-01', 'TRX-202611-0001', 'Penjualan Kasir POS #TRX-202611-0001 (BKM-202611-0001)', 4800000.00, 4800000.00, 1, '2026-11-01 08:57:07'),
(3, 'JRN-202611-0002', '2026-11-02', 'PO-202611-0001', 'Penerimaan PO #PO-202611-0001 (Tunai)', 1998223.60, 1998223.60, 10, '2026-11-02 07:06:25'),
(4, 'JRN-202611-0003', '2026-11-02', 'TRX-202611-0002', 'Penjualan Kasir POS #TRX-202611-0002 (BKM-202611-0002)', 17000000.00, 17000000.00, 1, '2026-11-02 08:11:40'),
(5, 'JRN-202611-0004', '2026-11-02', 'TRX-202611-0003', 'Penjualan Kasir POS #TRX-202611-0003 (BKM-202611-0003)', 16220000.00, 16220000.00, 1, '2026-11-02 08:13:50'),
(6, 'JRN-202611-0005', '2026-11-02', 'TRX-202611-0004', 'Penjualan Kasir POS #TRX-202611-0004 (BKM-202611-0004)', 12250000.00, 12250000.00, 1, '2026-11-02 08:25:03'),
(7, 'JRN-202611-0006', '2026-11-02', 'TRX-202611-0005', 'Penjualan Kasir POS #TRX-202611-0005 (BKM-202611-0005)', 12250000.00, 12250000.00, 1, '2026-11-02 08:25:41'),
(8, 'JRN-202611-0007', '2026-11-02', 'TRX-202611-0006', 'Penjualan Kasir POS #TRX-202611-0006 (BKM-202611-0006)', 1050000.00, 1050000.00, 1, '2026-11-02 08:27:27'),
(9, 'JRN-202611-0008', '2026-11-03', 'BKM-202611-0007', 'Bukti Kas Masuk #BKM-202611-0007 - Pembayaran Piutang #PIU-202611-0001', 18482500.00, 18482500.00, 1, '2026-11-03 01:17:03'),
(51, 'JRN-202610-0042', '2026-10-28', 'SO-202611-0043', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0043 (261028SHP101A1)', 304000.00, 304000.00, 1, '2026-11-03 05:39:51'),
(52, 'JRN-202610-0043', '2026-10-28', 'SO-202611-0044', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0044 (261028SHP102B2)', 148000.00, 148000.00, 1, '2026-11-03 05:39:51'),
(53, 'JRN-202610-0044', '2026-10-28', 'SO-202611-0045', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0045 (261028SHP103C3)', 366000.00, 366000.00, 1, '2026-11-03 05:39:51'),
(54, 'JRN-202610-0045', '2026-10-28', 'SO-202611-0046', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0046 (261028SHP104D4)', 214000.00, 214000.00, 1, '2026-11-03 05:39:51'),
(55, 'JRN-202610-0046', '2026-10-28', 'SO-202611-0047', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0047 (261028SHP105E5)', 348000.00, 348000.00, 1, '2026-11-03 05:39:51'),
(56, 'JRN-202610-0047', '2026-10-28', 'SO-202611-0048', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0048 (261028SHP106F6)', 280000.00, 280000.00, 1, '2026-11-03 05:39:52'),
(57, 'JRN-202610-0048', '2026-10-28', 'SO-202611-0049', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0049 (261028SHP107G7)', 225000.00, 225000.00, 1, '2026-11-03 05:39:52'),
(58, 'JRN-202610-0049', '2026-10-28', 'SO-202611-0050', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0050 (261028SHP108H8)', 346000.00, 346000.00, 1, '2026-11-03 05:39:52'),
(59, 'JRN-202610-0050', '2026-10-28', 'SO-202611-0051', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0051 (261028SHP109I9)', 286000.00, 286000.00, 1, '2026-11-03 05:39:52'),
(60, 'JRN-202610-0051', '2026-10-28', 'SO-202611-0052', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0052 (261028SHP110J0)', 612000.00, 612000.00, 1, '2026-11-03 05:39:52'),
(61, 'JRN-202610-0052', '2026-10-29', 'SO-202611-0053', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0053 (INV/20261029/MPL/70001)', 320000.00, 320000.00, 1, '2026-11-03 05:40:22'),
(62, 'JRN-202610-0053', '2026-10-29', 'SO-202611-0054', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0054 (INV/20261029/MPL/70002)', 375000.00, 375000.00, 1, '2026-11-03 05:40:22'),
(63, 'JRN-202610-0054', '2026-10-29', 'SO-202611-0055', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0055 (INV/20261029/MPL/70003)', 404000.00, 404000.00, 1, '2026-11-03 05:40:22'),
(64, 'JRN-202610-0055', '2026-10-29', 'SO-202611-0056', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0056 (INV/20261029/MPL/70004)', 189000.00, 189000.00, 1, '2026-11-03 05:40:22'),
(65, 'JRN-202610-0056', '2026-10-29', 'SO-202611-0057', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0057 (INV/20261029/MPL/70005)', 435000.00, 435000.00, 1, '2026-11-03 05:40:22'),
(66, 'JRN-202610-0057', '2026-10-29', 'SO-202611-0058', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0058 (INV/20261029/MPL/70006)', 435000.00, 435000.00, 1, '2026-11-03 05:40:22'),
(67, 'JRN-202610-0058', '2026-10-29', 'SO-202611-0059', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0059 (INV/20261029/MPL/70007)', 350000.00, 350000.00, 1, '2026-11-03 05:40:22'),
(68, 'JRN-202610-0059', '2026-10-29', 'SO-202611-0060', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0060 (INV/20261029/MPL/70008)', 240000.00, 240000.00, 1, '2026-11-03 05:40:22'),
(69, 'JRN-202610-0060', '2026-10-29', 'SO-202611-0061', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0061 (INV/20261029/MPL/70009)', 270000.00, 270000.00, 1, '2026-11-03 05:40:22'),
(70, 'JRN-202610-0061', '2026-10-29', 'SO-202611-0062', 'Penjualan Marketplace TOKOPEDIA #SO-202611-0062 (INV/20261029/MPL/70010)', 365000.00, 365000.00, 1, '2026-11-03 05:40:22'),
(71, 'JRN-202611-0010', '2026-11-03', 'BKM-202611-0008', 'Bukti Kas Masuk #BKM-202611-0008 - Pembayaran Piutang #PIU-202611-0002', 28000.00, 28000.00, 1, '2026-11-03 06:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_detail`
--

CREATE TABLE `jurnal_detail` (
  `id` int UNSIGNED NOT NULL,
  `jurnal_id` int UNSIGNED NOT NULL,
  `coa_id` int UNSIGNED NOT NULL,
  `debit` decimal(18,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(18,2) NOT NULL DEFAULT '0.00',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurnal_detail`
--

INSERT INTO `jurnal_detail` (`id`, `jurnal_id`, `coa_id`, `debit`, `kredit`, `memo`) VALUES
(1, 1, 1, 11200000.00, 0.00, 'Penerimaan Kasir POS'),
(2, 1, 8, 0.00, 11200000.00, 'Pendapatan Penjualan POS'),
(3, 2, 1, 4800000.00, 0.00, 'Penerimaan Kasir POS'),
(4, 2, 8, 0.00, 4800000.00, 'Pendapatan Penjualan POS'),
(5, 3, 5, 1998223.60, 0.00, 'Penerimaan PO #PO-202611-0001 (Tunai)'),
(6, 3, 1, 0.00, 1998223.60, 'Penerimaan PO #PO-202611-0001 (Tunai)'),
(7, 4, 1, 17000000.00, 0.00, 'Penerimaan Kasir POS'),
(8, 4, 8, 0.00, 17000000.00, 'Pendapatan Penjualan POS'),
(9, 5, 2, 16220000.00, 0.00, 'Penerimaan Kasir POS'),
(10, 5, 8, 0.00, 16220000.00, 'Pendapatan Penjualan POS'),
(11, 6, 1, 12250000.00, 0.00, 'Penerimaan Kasir POS'),
(12, 6, 8, 0.00, 12250000.00, 'Pendapatan Penjualan POS'),
(13, 7, 1, 12250000.00, 0.00, 'Penerimaan Kasir POS'),
(14, 7, 8, 0.00, 12250000.00, 'Pendapatan Penjualan POS'),
(15, 8, 1, 1050000.00, 0.00, 'Penerimaan Kasir POS'),
(16, 8, 8, 0.00, 1050000.00, 'Pendapatan Penjualan POS'),
(17, 9, 2, 18482500.00, 0.00, 'Bukti Kas Masuk #BKM-202611-0007 - Pembayaran Piutang #PIU-202611-0001'),
(18, 9, 4, 0.00, 18482500.00, 'Bukti Kas Masuk #BKM-202611-0007 - Pembayaran Piutang #PIU-202611-0001'),
(101, 51, 4, 304000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(102, 51, 8, 0.00, 304000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(103, 52, 4, 148000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(104, 52, 8, 0.00, 148000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(105, 53, 4, 366000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(106, 53, 8, 0.00, 366000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(107, 54, 4, 214000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(108, 54, 8, 0.00, 214000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(109, 55, 4, 348000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(110, 55, 8, 0.00, 348000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(111, 56, 4, 280000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(112, 56, 8, 0.00, 280000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(113, 57, 4, 225000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(114, 57, 8, 0.00, 225000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(115, 58, 4, 346000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(116, 58, 8, 0.00, 346000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(117, 59, 4, 286000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(118, 59, 8, 0.00, 286000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(119, 60, 4, 612000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(120, 60, 8, 0.00, 612000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(121, 61, 4, 320000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(122, 61, 8, 0.00, 320000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(123, 62, 4, 375000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(124, 62, 8, 0.00, 375000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(125, 63, 4, 404000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(126, 63, 8, 0.00, 404000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(127, 64, 4, 189000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(128, 64, 8, 0.00, 189000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(129, 65, 4, 435000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(130, 65, 8, 0.00, 435000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(131, 66, 4, 435000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(132, 66, 8, 0.00, 435000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(133, 67, 4, 350000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(134, 67, 8, 0.00, 350000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(135, 68, 4, 240000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(136, 68, 8, 0.00, 240000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(137, 69, 4, 270000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(138, 69, 8, 0.00, 270000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(139, 70, 4, 365000.00, 0.00, 'Piutang Settlement Marketplace TOKOPEDIA'),
(140, 70, 8, 0.00, 365000.00, 'Pendapatan Penjualan Marketplace TOKOPEDIA'),
(141, 71, 1, 28000.00, 0.00, 'Bukti Kas Masuk #BKM-202611-0008 - Pembayaran Piutang #PIU-202611-0002'),
(142, 71, 4, 0.00, 28000.00, 'Bukti Kas Masuk #BKM-202611-0008 - Pembayaran Piutang #PIU-202611-0002');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id`, `kode_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'ELEKTRONIK', 'Elektronik & Gadget', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(2, 'FASHION', 'Pakaian & Aksesoris', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, 'FNB', 'Makanan & Minuman', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, 'KESEHATAN', 'Kesehatan & Kecantikan', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(5, 'UMUM', 'Kategori Umum', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(6, 'MAKANAN', 'makanan', '2026-11-02 05:54:37', '2026-11-02 05:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `kurs`
--

CREATE TABLE `kurs` (
  `id` int UNSIGNED NOT NULL,
  `mata_uang` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_kurs` decimal(18,6) NOT NULL DEFAULT '1.000000',
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kurs`
--

INSERT INTO `kurs` (`id`, `mata_uang`, `nilai_kurs`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'USD', 18132.701322, '2026-11-01', '2026-11-01 01:38:53', '2026-11-01 01:38:53'),
(2, 'SGD', 14180.105806, '2026-11-01', '2026-11-01 01:38:54', '2026-11-01 01:38:54'),
(3, 'CNY', 2724.795640, '2026-11-01', '2026-11-01 01:38:54', '2026-11-01 01:38:54'),
(4, 'EUR', 20236.620500, '2026-11-01', '2026-11-01 01:38:54', '2026-11-01 01:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(10, '2026-10-30-000001', 'App\\Database\\Migrations\\CreateAuthAndRbacTables', 'default', 'App', 1793346692, 1),
(11, '2026-10-30-000002', 'App\\Database\\Migrations\\CreateMasterDataTables', 'default', 'App', 1793346692, 1),
(12, '2026-10-30-000003', 'App\\Database\\Migrations\\CreateStokTables', 'default', 'App', 1793346692, 1),
(13, '2026-10-30-000004', 'App\\Database\\Migrations\\CreateKeuanganAndCoaTables', 'default', 'App', 1793346692, 1),
(14, '2026-10-30-000005', 'App\\Database\\Migrations\\CreatePembelianAndUtangTables', 'default', 'App', 1793346693, 1),
(15, '2026-10-30-000006', 'App\\Database\\Migrations\\CreatePenjualanAndPiutangTables', 'default', 'App', 1793346693, 1),
(16, '2026-10-30-000007', 'App\\Database\\Migrations\\CreateMarketplaceTables', 'default', 'App', 1793346693, 1),
(17, '2026-10-30-000008', 'App\\Database\\Migrations\\CreateReturTables', 'default', 'App', 1793346693, 1),
(18, '2026-10-30-000009', 'App\\Database\\Migrations\\CreateCrmAndRfmTables', 'default', 'App', 1793346693, 1),
(19, '2026-10-30-000001', 'App\\Database\\Migrations\\CreateAuthAndRbacTables', 'tests', 'App', 1793348247, 2),
(20, '2026-10-30-000002', 'App\\Database\\Migrations\\CreateMasterDataTables', 'tests', 'App', 1793348247, 2),
(21, '2026-10-30-000003', 'App\\Database\\Migrations\\CreateStokTables', 'tests', 'App', 1793348247, 2),
(22, '2026-10-30-000004', 'App\\Database\\Migrations\\CreateKeuanganAndCoaTables', 'tests', 'App', 1793348247, 2),
(23, '2026-10-30-000005', 'App\\Database\\Migrations\\CreatePembelianAndUtangTables', 'tests', 'App', 1793348247, 2),
(24, '2026-10-30-000006', 'App\\Database\\Migrations\\CreatePenjualanAndPiutangTables', 'tests', 'App', 1793348247, 2),
(25, '2026-10-30-000007', 'App\\Database\\Migrations\\CreateMarketplaceTables', 'tests', 'App', 1793348247, 2),
(26, '2026-10-30-000008', 'App\\Database\\Migrations\\CreateReturTables', 'tests', 'App', 1793348247, 2),
(27, '2026-10-30-000009', 'App\\Database\\Migrations\\CreateCrmAndRfmTables', 'tests', 'App', 1793348247, 2),
(28, '2026-10-31-000010', 'App\\Database\\Migrations\\CreateNotificationsTable', 'default', 'App', 1793416947, 3),
(29, '2026-10-31-000011', 'App\\Database\\Migrations\\CreateEnterpriseTables', 'default', 'App', 1793418139, 4),
(30, '2026-10-31-000012', 'App\\Database\\Migrations\\AddPasswordToCalonCustomer', 'default', 'App', 1793418990, 5);

-- --------------------------------------------------------

--
-- Table structure for table `nomor_urut`
--

CREATE TABLE `nomor_urut` (
  `id` int UNSIGNED NOT NULL,
  `kode_dokumen` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_terakhir` int UNSIGNED NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nomor_urut`
--

INSERT INTO `nomor_urut` (`id`, `kode_dokumen`, `periode`, `nomor_terakhir`, `updated_at`) VALUES
(1, 'TRX', '202610', 1, '2026-10-31 14:57:44'),
(2, 'INV', '202610', 2, '2026-10-31 14:57:44'),
(3, 'BKM', '202610', 1, '2026-10-31 14:57:44'),
(4, 'JRN', '202610', 81, '2026-11-09 07:52:46'),
(5, 'TRX', '202611', 7, '2026-11-09 02:22:51'),
(6, 'INV', '202611', 171, '2026-11-09 08:45:43'),
(7, 'BKM', '202611', 9, '2026-11-09 02:22:51'),
(8, 'JRN', '202611', 78, '2026-11-09 08:45:43'),
(9, 'PO', '202611', 2, '2026-11-09 02:19:37'),
(11, 'SO', '202611', 164, '2026-11-09 08:45:43'),
(12, 'PIU', '202611', 3, '2026-11-09 02:24:23'),
(13, 'CUST', '202611', 64, '2026-11-09 08:45:43'),
(14, 'RET_JUAL', '202611', 2, '2026-11-03 06:00:39'),
(16, 'PRD', '202611', 7, '2026-11-09 07:51:46'),
(17, 'JRN', '202403', 8, '2026-11-09 07:24:38'),
(18, 'JRN', '202309', 1, '2026-11-07 03:53:40'),
(19, 'JRN', '202406', 3, '2026-11-08 06:02:47'),
(20, 'JRN', '202303', 3, '2026-11-09 07:36:06'),
(21, 'JRN', '202404', 1, '2026-11-08 05:40:56'),
(22, 'AP', '202611', 3, '2026-11-09 03:39:14'),
(23, 'BKK', '202611', 4, '2026-11-09 05:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sistem',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fa-solid fa-bell',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `category`, `type`, `icon`, `url`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(43, 9, 'Aktivitas: Selamat datang di Portal MetaforaCRM, acoy (acoy)!', 'Perubahan data berhasil dilakukan pada modul Sistem (02/11/2026 04:01)', 'sistem', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/dashboard', 0, NULL, '2026-11-02 04:01:36', '2026-11-02 04:01:36'),
(47, 10, 'Aktivitas: Selamat datang kembali, axoy!', 'Perubahan data berhasil dilakukan pada modul Sistem (02/11/2026 04:03)', 'sistem', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/dashboard', 1, '2026-11-02 06:37:03', '2026-11-02 04:03:02', '2026-11-02 04:03:02'),
(52, 10, 'Aktivitas: Produk berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:09)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/produk', 1, '2026-11-02 06:37:03', '2026-11-02 04:09:41', '2026-11-02 04:09:41'),
(53, 10, 'Peringatan Stok Kritis: kopi', 'Stok produk tersisa 0 unit (batas minimum: 5). Segera buat Purchase Order (PO).', 'stok', 'danger', 'fa-solid fa-triangle-exclamation', 'http://localhost:8080/stok', 1, '2026-11-02 06:37:03', '2026-11-02 04:09:43', '2026-11-02 04:09:43'),
(54, 10, 'Aktivitas: Data produk berhasil diperbarui.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:10)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/produk', 1, '2026-11-02 06:37:03', '2026-11-02 04:10:20', '2026-11-02 04:10:20'),
(55, 10, 'Aktivitas: Data produk berhasil diperbarui.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:10)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/produk', 1, '2026-11-02 06:37:03', '2026-11-02 04:10:44', '2026-11-02 04:10:44'),
(56, 10, 'Peringatan Stok Kritis: KOPI GULA AREN', 'Stok produk tersisa 0 unit (batas minimum: 24). Segera buat Purchase Order (PO).', 'stok', 'danger', 'fa-solid fa-triangle-exclamation', 'http://localhost:8080/stok', 1, '2026-11-02 06:37:03', '2026-11-02 04:10:45', '2026-11-02 04:10:45'),
(57, 10, 'Aktivitas: Pelanggan berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:15)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/pelanggan', 1, '2026-11-02 06:37:03', '2026-11-02 04:15:50', '2026-11-02 04:15:50'),
(58, 10, 'Aktivitas: Pelanggan berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:19)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/pelanggan', 1, '2026-11-02 06:37:03', '2026-11-02 04:19:35', '2026-11-02 04:19:35'),
(59, 10, 'Aktivitas: Pelanggan berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 04:20)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/pelanggan', 1, '2026-11-02 06:37:03', '2026-11-02 04:20:23', '2026-11-02 04:20:23'),
(60, 10, 'Aktivitas: Supplier berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 05:51)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/supplier', 1, '2026-11-02 06:37:03', '2026-11-02 05:51:52', '2026-11-02 05:51:52'),
(61, 10, 'Aktivitas: Supplier berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 05:53)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/supplier', 1, '2026-11-02 06:37:03', '2026-11-02 05:53:48', '2026-11-02 05:53:48'),
(62, 10, 'Aktivitas: Supplier berhasil dihapus.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 05:54)', 'stok', 'danger', 'fa-solid fa-trash-can', 'http://localhost:8080/master/supplier', 1, '2026-11-02 06:37:03', '2026-11-02 05:54:07', '2026-11-02 05:54:07'),
(63, 10, 'Aktivitas: Kategori produk berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 05:54)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/kategori', 1, '2026-11-02 06:37:03', '2026-11-02 05:54:38', '2026-11-02 05:54:38'),
(64, 10, 'Aktivitas: Supplier berhasil dihapus.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 06:13)', 'stok', 'danger', 'fa-solid fa-trash-can', 'http://localhost:8080/master/supplier', 1, '2026-11-02 06:37:03', '2026-11-02 06:13:43', '2026-11-02 06:13:43'),
(65, 10, 'Aktivitas: Satuan barang berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 06:14)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/satuan', 1, '2026-11-02 06:37:03', '2026-11-02 06:14:52', '2026-11-02 06:14:52'),
(66, 10, 'Aktivitas: Satuan barang berhasil diperbarui.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 06:15)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/satuan', 1, '2026-11-02 06:37:03', '2026-11-02 06:15:10', '2026-11-02 06:15:10'),
(67, 10, 'Aktivitas: Satuan barang berhasil diperbarui.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 06:16)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/satuan', 1, '2026-11-02 06:37:03', '2026-11-02 06:16:51', '2026-11-02 06:16:51'),
(68, 10, 'Aktivitas: Supplier berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Stok (02/11/2026 06:58)', 'stok', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/master/supplier', 0, NULL, '2026-11-02 06:58:04', '2026-11-02 06:58:04'),
(69, 10, 'Aktivitas: Purchase Order berhasil dibuat.', 'Perubahan data berhasil dilakukan pada modul Penjualan (02/11/2026 07:06)', 'penjualan', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/pembelian/detail/2', 0, NULL, '2026-11-02 07:06:01', '2026-11-02 07:06:01'),
(70, 10, 'Aktivitas: Penerimaan barang berhasil diproses. Stok fisik dan kartu stok telah dimutasi.', 'Perubahan data berhasil dilakukan pada modul Penjualan (02/11/2026 07:06)', 'penjualan', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/pembelian/detail/2', 0, NULL, '2026-11-02 07:06:26', '2026-11-02 07:06:26'),
(235, 10, 'Aktivitas: Selamat datang kembali, axoy!', 'Perubahan data berhasil dilakukan pada modul Sistem (07/11/2026 01:31)', 'sistem', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/dashboard', 0, NULL, '2026-11-07 01:31:37', '2026-11-07 01:31:37'),
(243, 1, 'Jadwal Follow-Up CRM: Andi Nurul Aisyah', 'Jadwal follow-up after sales (H+7) untuk pelanggan Andi Nurul Aisyah siap diproses.', 'crm', 'primary', 'fa-solid fa-calendar-check', 'http://localhost:8080/crm', 1, '2026-11-07 03:20:42', '2026-11-07 03:16:49', '2026-11-07 03:16:49'),
(245, 1, 'Transaksi Penjualan: SO-202611-0063', 'Pesanan penjualan baru dicatat senilai Rp 28.000 (Status: DRAFT).', 'penjualan', 'success', 'fa-solid fa-cart-shopping', 'http://localhost:8080/penjualan', 1, '2026-11-07 03:20:42', '2026-11-03 06:08:00', '2026-11-07 03:16:49'),
(247, 1, 'Pesanan Tokopedia: SO-202611-0062', 'Pesanan marketplace [Resi: TKP02910109910] via Ninja Xpress an. Andi Nurul Aisyah senilai Rp 365.000 telah tercatat di ERP SO, Stok, Invoice & Jurnal.', 'penjualan', 'success', 'fa-solid fa-truck-fast', 'http://localhost:8080/marketplace', 1, '2026-11-07 03:20:42', '2026-11-03 05:40:22', '2026-11-07 03:16:49'),
(248, 1, 'Pesanan Tokopedia: SO-202611-0061', 'Pesanan marketplace [Resi: TKP0291009999] via SPX Express Standard an. I Ketut Suardana senilai Rp 270.000 telah tercatat di ERP SO, Stok, Invoice & Jurnal.', 'penjualan', 'success', 'fa-solid fa-truck-fast', 'http://localhost:8080/marketplace', 1, '2026-11-07 03:20:42', '2026-11-03 05:40:22', '2026-11-07 03:16:49'),
(249, 1, 'Pesanan Tokopedia: SO-202611-0060', 'Pesanan marketplace [Resi: TKP0291008998] via SiCepat REG an. Farhan Ramadhan senilai Rp 240.000 telah tercatat di ERP SO, Stok, Invoice & Jurnal.', 'penjualan', 'success', 'fa-solid fa-truck-fast', 'http://localhost:8080/marketplace', 1, '2026-11-07 03:20:42', '2026-11-03 05:40:22', '2026-11-07 03:16:49'),
(250, 1, 'Pesanan Tokopedia: SO-202611-0059', 'Pesanan marketplace [Resi: TKP0291007997] via Anteraja Reguler an. Eka Putri Lestari senilai Rp 350.000 telah tercatat di ERP SO, Stok, Invoice & Jurnal.', 'penjualan', 'success', 'fa-solid fa-truck-fast', 'http://localhost:8080/marketplace', 1, '2026-11-07 03:20:42', '2026-11-03 05:40:22', '2026-11-07 03:16:49'),
(251, 1, 'Aktivitas: User \'aku\' berhasil ditambahkan ke dalam sistem!', 'Perubahan data berhasil dilakukan pada modul Approval (07/11/2026 03:19)', 'approval', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/pengaturan/user', 1, '2026-11-07 03:20:42', '2026-11-07 03:19:36', '2026-11-07 03:19:36'),
(252, 11, 'Aktivitas: Selamat datang kembali, aku!', 'Perubahan data berhasil dilakukan pada modul Sistem (07/11/2026 03:19)', 'sistem', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/dashboard', 0, NULL, '2026-11-07 03:19:57', '2026-11-07 03:19:57'),
(253, 1, 'Aktivitas: Grup / Role baru \'Keuangan\' berhasil ditambahkan.', 'Perubahan data berhasil dilakukan pada modul Approval (07/11/2026 03:21)', 'approval', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/pengaturan/groups', 1, '2026-11-08 16:55:12', '2026-11-07 03:21:28', '2026-11-07 03:21:28'),
(254, 1, 'Aktivitas: User \'kita\' berhasil ditambahkan ke dalam sistem!', 'Perubahan data berhasil dilakukan pada modul Approval (07/11/2026 03:22)', 'approval', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/pengaturan/user', 1, '2026-11-08 16:55:12', '2026-11-07 03:22:12', '2026-11-07 03:22:12'),
(255, 12, 'Aktivitas: Selamat datang kembali, kita!', 'Perubahan data berhasil dilakukan pada modul Sistem (07/11/2026 03:22)', 'sistem', 'warning', 'fa-solid fa-pen-to-square', 'http://localhost:8080/dashboard', 0, NULL, '2026-11-07 03:22:33', '2026-11-07 03:22:33'),
(257, 1, 'Transaksi Penjualan: SO-202611-0062', 'Pesanan penjualan baru dicatat senilai Rp 365.000 (Status: COMPLETED).', 'penjualan', 'success', 'fa-solid fa-cart-shopping', 'http://localhost:8080/penjualan', 1, '2026-11-08 16:55:12', '2026-11-03 05:40:22', '2026-11-07 03:49:40'),
(258, 1, 'Pesanan Tokopedia: SO-202611-0058', 'Pesanan marketplace [Resi: TKP0291006996] via J&T Express an. Rian Pratama Kusuma senilai Rp 435.000 telah tercatat di ERP SO, Stok, Invoice & Jurnal.', 'penjualan', 'success', 'fa-solid fa-truck-fast', 'http://localhost:8080/marketplace', 1, '2026-11-08 16:55:12', '2026-11-03 05:40:22', '2026-11-07 03:49:40'),
(334, 1, 'Transaksi Penjualan: SO-202611-0090', 'Pesanan penjualan baru dicatat senilai Rp 371.000 (Status: COMPLETED).', 'penjualan', 'success', 'fa-solid fa-cart-shopping', 'http://localhost:8080/penjualan', 1, '2026-11-08 16:55:12', '2026-11-01 02:09:16', '2026-11-08 15:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int UNSIGNED NOT NULL,
  `kode_pelanggan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_pelanggan` enum('retail','grosir','distributor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'retail',
  `limit_kredit` decimal(18,2) NOT NULL DEFAULT '0.00',
  `tempo_hari` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `kode_pelanggan`, `nama_pelanggan`, `no_telp`, `email`, `alamat`, `kota`, `tipe_pelanggan`, `limit_kredit`, `tempo_hari`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CUST-0001', 'Pelanggan Umum (Walk-in)', '081200000000', 'pelanggan.umum@gmail.com', NULL, NULL, 'retail', 0.00, 0, '2026-10-30 14:52:11', '2026-10-30 14:52:11', NULL),
(2, 'CUST-02', 'axoy', '012345678909', 'axoy@gmail.com', 'Solo', 'Solo', 'retail', 1000000.00, 0, '2026-11-02 04:15:49', '2026-11-02 04:15:49', NULL),
(3, 'CUST-03', 'aku', '098765432134', 'aku@gmail.com', 'Jogja', 'Jogja', 'grosir', 10000000.00, 0, '2026-11-02 04:19:35', '2026-11-02 04:19:35', NULL),
(4, 'CUST-04', 'kita', '012345678978', 'kita@gmail.com', 'Surabaya', 'Surabaya', 'distributor', 100000000.00, 0, '2026-11-02 04:20:22', '2026-11-02 04:20:22', NULL),
(5, 'CUST-202611-0001', 'Abrori', '082257189117', 'abrori@gmail.com', 'HOME, Ponpen Raudlatul Ulum, Jalan Pp Raudlatul KAB. JEMBER, Ulum, Suren, Ledokombo, LEDOKOMBO, KAB. JEMBER, JAWA TIMUR', 'Jember', 'retail', 0.00, 0, '2026-11-03 01:40:42', '2026-11-03 01:40:42', NULL),
(6, 'CUST-202611-0002', 'Siti Rahmawati', '081298765432', 'siti.rahmawati@gmail.com', 'Jl. Melati No. 45 RT 02/05, Kebayoran Baru, Jakarta Selatan 12150', NULL, 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 03:51:37', NULL),
(7, 'CUST-202611-0003', 'Dimas Surya Pratama', '081234510001', 'dimas.surya@gmail.com', 'Jl. Manyar Kertoarjo V No. 24, Mulyorejo', 'KOTA SURABAYA', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:51', NULL),
(8, 'CUST-202611-0004', 'Anisa Rahmawati', '085711210002', 'anisa.rahmawati@gmail.com', 'Jl. Dago Asri No. 15, Dago, Coblong', 'KOTA BANDUNG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:51', NULL),
(9, 'CUST-202611-0005', 'Bambang Gunawan', '081399310003', 'bambang.gunawan@gmail.com', 'Jl. Tebet Barat Dalam Raya No. 42, Tebet', 'KOTA JAKARTA SELATAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:51', NULL),
(10, 'CUST-202611-0006', 'Yohanes Christian', '082177410004', 'yohanes.christian@gmail.com', 'Jl. Gejayan No. 8, Condongcatur, Depok', 'KABUPATEN SLEMAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:51', NULL),
(11, 'CUST-202611-0007', 'Tri Wahyuni', '087855510005', 'tri.wahyuni@gmail.com', 'Jl. Ijen No. 70, Oro-oro Dowo, Klojen', 'KOTA MALANG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:51', NULL),
(12, 'CUST-202611-0008', 'Farhan Ramadhan', '089633610006', 'farhan.ramadhan@gmail.com', 'Jl. Ahmad Yani No. 110, Gayungan', 'KOTA SURABAYA', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:52', NULL),
(13, 'CUST-202611-0009', 'Dewi Anggraini', '081188710007', 'dewi.anggraini@gmail.com', 'Jl. Pemuda No. 138, Sekayu, Semarang Tengah', 'KOTA SEMARANG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:52', NULL),
(14, 'CUST-202611-0010', 'I Made Suardana', '085244810008', 'i.made@gmail.com', 'Jl. Sunset Road No. 89, Seminyak, Kuta', 'KABUPATEN BADUNG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:52', NULL),
(15, 'CUST-202611-0011', 'Putri Maharani', '082299910009', 'putri.maharani@gmail.com', 'Jl. BSD Grand Boulevard, Cluster Foresta Blok B No. 3', 'KOTA TANGERANG SELATAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:52', NULL),
(16, 'CUST-202611-0012', 'Andi Muhammad Yusuf', '081911010010', 'andi.muhammad@gmail.com', 'Jl. Ratulangi No. 55, Maricaya, Mamajang', 'KOTA MAKASSAR', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:39:52', NULL),
(17, 'CUST-202611-0013', 'Siti Nurhaliza', '081234510002', 'siti.nurhaliza@gmail.com', 'Jl. Buah Batu No. 112, Lengkong', 'KOTA BANDUNG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(18, 'CUST-202611-0014', 'Hendra Setiawan', '081234510003', 'hendra.setiawan@gmail.com', 'Jl. Tebet Barat Raya No. 45, Tebet', 'KOTA JAKARTA SELATAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(19, 'CUST-202611-0015', 'Ratna Dewi Wulandari', '081234510004', 'ratna.dewi@gmail.com', 'Jl. Kaliurang KM 7.5 No. 18, Condongcatur', 'KAB. SLEMAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(20, 'CUST-202611-0016', 'Ahmad Syukri Lubis', '081234510005', 'ahmad.syukri@gmail.com', 'Jl. Ringroad No. 88, Medan Selayang', 'KOTA MEDAN', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(21, 'CUST-202611-0017', 'Rian Pratama Kusuma', '081234510006', 'rian.pratama@gmail.com', 'Jl. Pandanaran No. 56, Semarang Tengah', 'KOTA SEMARANG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(22, 'CUST-202611-0018', 'Eka Putri Lestari', '081234510007', 'eka.putri@gmail.com', 'Jl. Ijen No. 34, Klojen', 'KOTA MALANG', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(23, 'CUST-202611-0019', 'Farhan Ramadhan', '081234510008', 'farhan.ramadhan@gmail.com', 'Jl. Harapan Indah Raya Blok AB No. 12', 'KOTA BEKASI', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(24, 'CUST-202611-0020', 'I Ketut Suardana', '081234510009', 'i.ketut@gmail.com', 'Jl. Bypass Ngurah Rai No. 77, Sanur', 'KOTA DENPASAR', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL),
(25, 'CUST-202611-0021', 'Andi Nurul Aisyah', '081234510010', 'andi.nurul@gmail.com', 'Jl. Boulevard Panakkukang Mas No. 15', 'KOTA MAKASSAR', 'retail', 0.00, 0, '2026-11-03 03:51:37', '2026-11-03 05:40:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_piutang`
--

CREATE TABLE `pembayaran_piutang` (
  `id` int UNSIGNED NOT NULL,
  `nomor_bkm` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `piutang_id` int UNSIGNED DEFAULT NULL,
  `so_id` int UNSIGNED DEFAULT NULL,
  `pelanggan_id` int UNSIGNED DEFAULT NULL,
  `akun_kas_bank_id` int UNSIGNED NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `metode_pembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(18,2) NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran_piutang`
--

INSERT INTO `pembayaran_piutang` (`id`, `nomor_bkm`, `piutang_id`, `so_id`, `pelanggan_id`, `akun_kas_bank_id`, `tanggal_bayar`, `metode_pembayaran`, `nominal`, `catatan`, `user_id`, `created_at`) VALUES
(1, 'BKM-202610-0001', NULL, 2, 1, 1, '2026-10-31', 'tunai', 11200000.00, 'Penerimaan POS #TRX-202610-0001', 1, '2026-10-31 14:57:44'),
(2, 'BKM-202611-0001', NULL, 3, 1, 1, '2026-11-01', 'tunai', 4800000.00, 'Penerimaan POS #TRX-202611-0001', 1, '2026-11-01 08:57:07'),
(3, 'BKM-202611-0002', NULL, 4, 1, 1, '2026-11-02', 'tunai', 17000000.00, 'Penerimaan POS #TRX-202611-0002', 1, '2026-11-02 08:11:40'),
(4, 'BKM-202611-0003', NULL, 5, 2, 2, '2026-11-02', 'transfer', 16220000.00, 'Penerimaan POS #TRX-202611-0003', 1, '2026-11-02 08:13:50'),
(5, 'BKM-202611-0004', NULL, 6, 1, 1, '2026-11-02', 'tunai', 12250000.00, 'Penerimaan POS #TRX-202611-0004', 1, '2026-11-02 08:25:03'),
(6, 'BKM-202611-0005', NULL, 7, 1, 1, '2026-11-02', 'tunai', 12250000.00, 'Penerimaan POS #TRX-202611-0005', 1, '2026-11-02 08:25:41'),
(7, 'BKM-202611-0006', NULL, 8, 3, 1, '2026-11-02', 'tunai', 1050000.00, 'Penerimaan POS #TRX-202611-0006', 1, '2026-11-02 08:27:27'),
(8, 'BKM-202611-0007', 1, 9, 4, 2, '2026-11-03', 'transfer', 18482500.00, 'BCA# REF 1234567890', 1, '2026-11-03 01:17:03'),
(9, 'BKM-202611-0008', 2, 100, 17, 1, '2026-11-03', 'transfer', 28000.00, '', 1, '2026-11-03 06:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_utang`
--

CREATE TABLE `pembayaran_utang` (
  `id` int UNSIGNED NOT NULL,
  `nomor_bkk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utang_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `akun_kas_bank_id` int UNSIGNED NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `metode_pembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(18,2) NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran_utang`
--

INSERT INTO `pembayaran_utang` (`id`, `nomor_bkk`, `utang_id`, `supplier_id`, `akun_kas_bank_id`, `tanggal_bayar`, `metode_pembayaran`, `nominal`, `catatan`, `user_id`, `created_at`) VALUES
(2, 'BKK-202611-0001', 1, 1, 1, '2026-11-01', 'transfer', 2500000.00, 'Pelunasan faktur pembelian bahan baku vendor', 1, '2026-11-01 02:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_marketplace`
--

CREATE TABLE `penjualan_marketplace` (
  `id` int UNSIGNED NOT NULL,
  `no_pesanan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` enum('shopee','tiktok','tokopedia','lazada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_resi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kurir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_penerima` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_penerima` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `is_cod` tinyint(1) NOT NULL DEFAULT '0',
  `ongkir` decimal(18,2) NOT NULL DEFAULT '0.00',
  `voucher` decimal(18,2) NOT NULL DEFAULT '0.00',
  `biaya_layanan` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('unpaid','ready_to_ship','shipped','completed','cancelled','returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ready_to_ship',
  `tanggal_pesanan` datetime DEFAULT NULL,
  `so_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan_marketplace`
--

INSERT INTO `penjualan_marketplace` (`id`, `no_pesanan`, `source`, `nomor_resi`, `kurir`, `nama_penerima`, `no_telepon`, `alamat_penerima`, `total`, `is_cod`, `ongkir`, `voucher`, `biaya_layanan`, `status`, `tanggal_pesanan`, `so_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '241101SHOPEE8892', 'shopee', 'SPXID04829104821', 'Shopee Xpress Standard', 'Siti Rahmawati', '081298765432', 'Jl. Melati No. 45 RT 02/05, Kebayoran Baru, Jakarta Selatan 12150', 371000.00, 0, 20000.00, 15000.00, 1000.00, 'completed', '2026-11-01 14:56:04', 128, '2026-11-01 02:09:16', '2026-11-08 14:56:58', NULL),
(72, '261028SHP101A1', 'tokopedia', 'SPXID028101001', 'SPX Standard', 'Dimas Surya Pratama', '081234510001', 'Jl. Manyar Kertoarjo V No. 24, Mulyorejo, KOTA SURABAYA, JAWA TIMUR', 304000.00, 0, 14000.00, 0.00, 0.00, 'completed', '2026-10-28 08:30:15', 80, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(73, '261028SHP102B2', 'tokopedia', 'JT0828102002', 'J&T Express', 'Anisa Rahmawati', '085711210002', 'Jl. Dago Asri No. 15, Dago, Coblong, KOTA BANDUNG, JAWA BARAT', 148000.00, 0, 18000.00, 0.00, 0.00, 'completed', '2026-10-28 09:12:00', 81, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(74, '261028SHP103C3', 'tokopedia', '004928103003', 'SiCepat REG', 'Bambang Gunawan', '081399310003', 'Jl. Tebet Barat Dalam Raya No. 42, Tebet, KOTA JAKARTA SELATAN, DKI JAKARTA', 366000.00, 0, 16000.00, 0.00, 0.00, 'completed', '2026-10-28 10:05:10', 82, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(75, '261028SHP104D4', 'tokopedia', 'SPXID028104004', 'SPX Hemat', 'Yohanes Christian', '082177410004', 'Jl. Gejayan No. 8, Condongcatur, Depok, KABUPATEN SLEMAN, D.I. YOGYAKARTA', 214000.00, 0, 22000.00, 0.00, 0.00, 'completed', '2026-10-28 11:15:30', 83, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(76, '261028SHP105E5', 'tokopedia', 'JT0828105005', 'J&T Cargo', 'Tri Wahyuni', '087855510005', 'Jl. Ijen No. 70, Oro-oro Dowo, Klojen, KOTA MALANG, JAWA TIMUR', 348000.00, 0, 20000.00, 0.00, 0.00, 'completed', '2026-10-28 12:20:10', 84, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(77, '261028SHP106F6', 'tokopedia', 'SPXID028106006', 'SPX Instant', 'Farhan Ramadhan', '089633610006', 'Jl. Ahmad Yani No. 110, Gayungan, KOTA SURABAYA, JAWA TIMUR', 280000.00, 0, 10000.00, 0.00, 0.00, 'completed', '2026-10-28 13:40:00', 85, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(78, '261028SHP107G7', 'tokopedia', 'JT0828107007', 'J&T Express', 'Dewi Anggraini', '081188710007', 'Jl. Pemuda No. 138, Sekayu, Semarang Tengah, KOTA SEMARANG, JAWA TENGAH', 225000.00, 0, 15000.00, 0.00, 0.00, 'completed', '2026-10-28 14:10:20', 86, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(79, '261028SHP108H8', 'tokopedia', '004928108008', 'SiCepat REG', 'I Made Suardana', '085244810008', 'Jl. Sunset Road No. 89, Seminyak, Kuta, KABUPATEN BADUNG, BALI', 346000.00, 0, 26000.00, 0.00, 0.00, 'completed', '2026-10-28 15:20:15', 87, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(80, '261028SHP109I9', 'tokopedia', 'SPXID028109009', 'SPX Standard', 'Putri Maharani', '082299910009', 'Jl. BSD Grand Boulevard, Cluster Foresta Blok B No. 3, KOTA TANGERANG SELATAN, BANTEN', 286000.00, 0, 16000.00, 0.00, 0.00, 'completed', '2026-10-28 16:05:40', 88, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(81, '261028SHP110J0', 'tokopedia', 'JT0828110010', 'J&T Cargo', 'Andi Muhammad Yusuf', '081911010010', 'Jl. Ratulangi No. 55, Maricaya, Mamajang, KOTA MAKASSAR, SULAWESI SELATAN', 612000.00, 0, 42000.00, 0.00, 0.00, 'completed', '2026-10-28 17:15:00', 89, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(82, 'INV/20261029/MPL/70001', 'tokopedia', 'TKP0291001991', 'SPX Express Standard', 'Dimas Surya Pratama', '081234510001', 'Jl. Manyar Kertoarjo V No. 24, Mulyorejo, KOTA SURABAYA, JAWA TIMUR', 320000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 90, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(83, 'INV/20261029/MPL/70002', 'tokopedia', 'TKP0291002992', 'J&T Express', 'Siti Nurhaliza', '081234510002', 'Jl. Buah Batu No. 112, Lengkong, KOTA BANDUNG, JAWA BARAT', 375000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 91, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(84, 'INV/20261029/MPL/70003', 'tokopedia', 'TKP0291003993', 'SiCepat REG', 'Hendra Setiawan', '081234510003', 'Jl. Tebet Barat Raya No. 45, Tebet, KOTA JAKARTA SELATAN, DKI JAKARTA', 404000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 92, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(85, 'INV/20261029/MPL/70004', 'tokopedia', 'TKP0291004994', 'SPX Express Standard', 'Ratna Dewi Wulandari', '081234510004', 'Jl. Kaliurang KM 7.5 No. 18, Condongcatur, KAB. SLEMAN, DI YOGYAKARTA', 189000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 93, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(86, 'INV/20261029/MPL/70005', 'tokopedia', 'TKP0291005995', 'JNE Reguler', 'Ahmad Syukri Lubis', '081234510005', 'Jl. Ringroad No. 88, Medan Selayang, KOTA MEDAN, SUMATERA UTARA', 435000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 94, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(87, 'INV/20261029/MPL/70006', 'tokopedia', 'TKP0291006996', 'J&T Express', 'Rian Pratama Kusuma', '081234510006', 'Jl. Pandanaran No. 56, Semarang Tengah, KOTA SEMARANG, JAWA TENGAH', 435000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 95, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(88, 'INV/20261029/MPL/70007', 'tokopedia', 'TKP0291007997', 'Anteraja Reguler', 'Eka Putri Lestari', '081234510007', 'Jl. Ijen No. 34, Klojen, KOTA MALANG, JAWA TIMUR', 350000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 96, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(89, 'INV/20261029/MPL/70008', 'tokopedia', 'TKP0291008998', 'SiCepat REG', 'Farhan Ramadhan', '081234510008', 'Jl. Harapan Indah Raya Blok AB No. 12, KOTA BEKASI, JAWA BARAT', 240000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 97, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(90, 'INV/20261029/MPL/70009', 'tokopedia', 'TKP0291009999', 'SPX Express Standard', 'I Ketut Suardana', '081234510009', 'Jl. Bypass Ngurah Rai No. 77, Sanur, KOTA DENPASAR, BALI', 270000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 98, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(91, 'INV/20261029/MPL/70010', 'tokopedia', 'TKP02910109910', 'Ninja Xpress', 'Andi Nurul Aisyah', '081234510010', 'Jl. Boulevard Panakkukang Mas No. 15, KOTA MAKASSAR, SULAWESI SELATAN', 365000.00, 0, 0.00, 0.00, 0.00, 'completed', '2026-10-29 08:30:00', 99, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_marketplace_item`
--

CREATE TABLE `penjualan_marketplace_item` (
  `id` int UNSIGNED NOT NULL,
  `penjualan_marketplace_id` int UNSIGNED NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `produk_id` int UNSIGNED DEFAULT NULL,
  `nama_produk_marketplace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `harga_satuan` decimal(18,2) NOT NULL,
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan_marketplace_item`
--

INSERT INTO `penjualan_marketplace_item` (`id`, `penjualan_marketplace_id`, `sku`, `produk_id`, `nama_produk_marketplace`, `qty`, `harga_satuan`, `subtotal`) VALUES
(65, 72, 'PRD-0012', 6, 'Kopi Arabika Aceh Gayo Washed Grade 1 250g', 2.000, 145000.00, 290000.00),
(66, 73, 'PRD-0013', 7, 'Sirup Gourmet Salted Caramel 750ml Artisan Glass', 1.000, 130000.00, 130000.00),
(67, 74, 'PRD-0014', 8, 'Pure Dark Cocoa Chocolate Powder 1kg Export', 3.000, 120000.00, 360000.00),
(68, 75, 'PRD-0015', 9, 'Paper Cup Hot 8oz Double Wall Embossed (Isi 50)', 6.000, 32000.00, 192000.00),
(69, 76, 'PRD-0016', 10, 'Kopi Robusta Flores Manggarai Fine Robusta 1kg', 4.000, 82000.00, 328000.00),
(70, 77, 'PRD-0017', 11, 'Sirup Classic Hazelnut 750ml Flavouring Syrup', 2.000, 135000.00, 270000.00),
(71, 78, 'PRD-0018', 12, 'Matcha Green Tea Powder Premium Uji Grade 500g', 1.000, 210000.00, 210000.00),
(72, 79, 'PRD-0019', 13, 'Kopi Arabika Bali Kintamani Natural Anaerob 250g', 2.000, 160000.00, 320000.00),
(73, 80, 'PRD-0020', 14, 'Sirup French Vanilla Gourmet 750ml', 2.000, 135000.00, 270000.00),
(74, 81, 'PRD-0021', 15, 'Kopi Arabika Toraja Sapan Grade 1 Semi Washed 1kg', 2.000, 285000.00, 570000.00),
(75, 82, 'PRD-0012', 6, 'Kopi Arabika Gayo Single Origin Grade 1', 1.000, 185000.00, 185000.00),
(76, 82, 'PRD-0022', 16, 'Kopi Robusta Java Dampit Grade 1', 1.000, 135000.00, 135000.00),
(77, 83, 'PRD-0023', 17, 'Sirup Salted Caramel Gourmet 750ml', 2.000, 125000.00, 250000.00),
(78, 83, 'PRD-0013', 7, 'Sirup Premium Madagascar Vanilla 750ml', 1.000, 125000.00, 125000.00),
(79, 84, 'PRD-0024', 18, 'Ceremonial Matcha Powder Uji Japan 1kg', 1.000, 320000.00, 320000.00),
(80, 84, 'PRD-0025', 19, 'Susu UHT Plain Full Cream 1L', 3.000, 28000.00, 84000.00),
(81, 85, 'PRD-0014', 8, 'Pure Cocoa Dark Chocolate Powder 1kg', 1.000, 145000.00, 145000.00),
(82, 85, 'PRD-0026', 20, 'Gula Pasir Rafinasi Premium Murni 1kg', 2.000, 22000.00, 44000.00),
(83, 86, 'PRD-0020', 14, 'House Blend Espresso Roast Coffee', 2.000, 155000.00, 310000.00),
(84, 86, 'PRD-0027', 21, 'Sirup Roasted Hazelnut Artisanal 750ml', 1.000, 125000.00, 125000.00),
(85, 87, 'PRD-0028', 22, 'Signature Cold Brew Coffee Concentrate 250ml', 2.000, 145000.00, 290000.00),
(86, 87, 'PRD-0029', 23, 'Premium RTD Matcha Latte Bottle 250ml', 1.000, 145000.00, 145000.00),
(87, 88, 'PRD-0030', 24, 'Drip Bag Coffee Artisanal Gift Box', 1.000, 165000.00, 165000.00),
(88, 88, 'PRD-0012', 6, 'Kopi Arabika Gayo Single Origin Grade 1', 1.000, 185000.00, 185000.00),
(89, 89, 'PRD-0031', 25, 'Paper Cup 12oz Hot Embossed Double Wall', 2.000, 85000.00, 170000.00),
(90, 89, 'PRD-0032', 26, 'Sedotan Kertas Kraft Ramah Lingkungan 6mm', 2.000, 35000.00, 70000.00),
(91, 90, 'PRD-0022', 16, 'Kopi Robusta Java Dampit Grade 1', 1.000, 135000.00, 135000.00),
(92, 90, 'PRD-0033', 27, 'Standing Pouch Matte Black One-Way Valve', 3.000, 45000.00, 135000.00),
(93, 91, 'PRD-0020', 14, 'House Blend Espresso Roast Coffee', 1.000, 155000.00, 155000.00),
(94, 91, 'PRD-0023', 17, 'Sirup Salted Caramel Gourmet 750ml', 1.000, 125000.00, 125000.00),
(95, 91, 'PRD-0031', 25, 'Paper Cup 12oz Hot Embossed Double Wall', 1.000, 85000.00, 85000.00),
(136, 1, 'PRD-0001', 1, 'Kopi Arabika Premium 250gr', 2.000, 182500.00, 365000.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'custom',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `definition`, `module`, `created_at`, `updated_at`) VALUES
(701, 'keuangan_jurnal_posting', 'Posting Transaksi ke Buku Besar (GL)', 'custom', '2026-10-31 10:42:47', NULL),
(702, 'rfm_segmentasi_export', 'Export Data Matriks & Kohort RFM', 'custom', '2026-10-31 10:42:47', NULL),
(703, 'stok_opname_approve', 'Otorisasi Hasil Penyesuaian Stok Opname', 'custom', '2026-10-31 10:42:47', NULL),
(704, 'pos_penjualan_kasir', 'Akses Kasir Cepat POS & Cetak Struk', 'custom', '2026-10-31 10:42:47', NULL),
(705, 'utang_usaha_delete', 'Hapus utang usaha', 'custom', '2026-10-31 10:42:47', NULL),
(706, 'utang_usaha_export', 'Export utang usaha', 'custom', '2026-10-31 10:42:47', NULL),
(707, 'menu_pembelian', 'Menu Pembelian', 'custom', '2026-10-31 10:42:47', NULL),
(708, 'menu_purchase_order_(po)', 'Menu Purchase Order (PO)', 'custom', '2026-10-31 10:42:47', NULL),
(709, 'menu_utang_usaha_(ap)', 'Menu Utang Usaha (AP)', 'custom', '2026-10-31 10:42:47', NULL),
(710, 'menu_pengeluaran_kas_(bkk)', 'Menu Pengeluaran Kas (BKK)', 'custom', '2026-10-31 10:42:47', NULL),
(711, 'retur_pembelian_export', 'Retur Pembelian Export', 'custom', '2026-10-31 10:42:47', NULL),
(712, 'menu_transaksi_penjualan', 'Menu Transaksi Penjualan', 'custom', '2026-10-31 10:42:47', NULL),
(713, 'menu_manajemen_stok', 'Menu Manajemen Stok', 'custom', '2026-10-31 10:42:47', NULL),
(714, 'crm_dashboard', 'CRM Dashboard', 'crm', '2026-10-31 10:42:47', NULL),
(715, 'menu_dashboard', 'Menu Dashboard', 'menu', '2026-11-07 01:27:28', NULL),
(716, 'menu_crud', 'Menu CRUD Builder', 'menu', '2026-11-07 01:27:28', NULL),
(717, 'menu_api', 'Menu API Builder', 'menu', '2026-11-07 01:27:28', NULL),
(718, 'menu_page', 'Menu Page Builder', 'menu', '2026-11-07 01:27:28', NULL),
(719, 'menu_form', 'Menu Form Builder', 'menu', '2026-11-07 01:27:28', NULL),
(720, 'menu_menu', 'Menu Menu', 'menu', '2026-11-07 01:27:28', NULL),
(721, 'menu_auth', 'Menu Auth', 'menu', '2026-11-07 01:27:28', NULL),
(722, 'menu_user', 'Menu User', 'menu', '2026-11-07 01:27:28', NULL),
(723, 'menu_group', 'Menu Group', 'menu', '2026-11-07 01:27:28', NULL),
(724, 'menu_access', 'Menu Access Matrix', 'menu', '2026-11-07 01:27:28', NULL),
(725, 'menu_permission', 'Menu Permission', 'menu', '2026-11-07 01:27:28', NULL),
(726, 'menu_docs_api', 'Menu API Docs', 'menu', '2026-11-07 01:27:28', NULL),
(727, 'menu_docs_web', 'Menu Web Docs', 'menu', '2026-11-07 01:27:28', NULL),
(728, 'master_produk', 'Master Produk', 'master', '2026-11-07 01:27:28', NULL),
(729, 'master_pelanggan', 'Master Pelanggan', 'master', '2026-11-07 01:27:28', NULL),
(730, 'master_supplier', 'Master Supplier', 'master', '2026-11-07 01:27:28', NULL),
(731, 'master_kategori', 'Kategori Barang', 'master', '2026-11-07 01:27:28', NULL),
(732, 'master_satuan', 'Unit & Satuan', 'master', '2026-11-07 01:27:28', NULL),
(733, 'stok_dashboard', 'Dashboard Stok', 'master', '2026-11-07 01:27:28', NULL),
(734, 'stok_opname', 'Stock Opname Fisik', 'master', '2026-11-07 01:27:28', NULL),
(735, 'stok_export', 'Export Stok Excel', 'master', '2026-11-07 01:27:28', NULL),
(736, 'pos_kasir', 'Kasir Cepat (POS)', 'penjualan', '2026-11-07 01:27:28', NULL),
(737, 'penjualan_transaksi', 'Transaksi Penjualan', 'penjualan', '2026-11-07 01:27:28', NULL),
(738, 'penjualan_marketplace', 'Penjualan Marketplace', 'penjualan', '2026-11-07 01:27:28', NULL),
(739, 'penjualan_invoice', 'Cetak Invoice & Resi', 'penjualan', '2026-11-07 01:27:28', NULL),
(740, 'retur_penjualan', 'Retur Penjualan', 'penjualan', '2026-11-07 01:27:28', NULL),
(741, 'crm_followup', 'Journal Follow-Up H+', 'crm', '2026-11-07 01:27:28', NULL),
(742, 'rfm_analisis', 'Analisis Matriks & Kohort RFM', 'crm', '2026-11-07 01:27:28', NULL),
(743, 'po_list', 'Purchase Order (PO)', 'pembelian', '2026-11-07 01:27:28', NULL),
(744, 'po_create', 'Buat PO Baru', 'pembelian', '2026-11-07 01:27:28', NULL),
(745, 'po_receive', 'Penerimaan Barang PO', 'pembelian', '2026-11-07 01:27:28', NULL),
(746, 'retur_pembelian', 'Retur Pembelian', 'pembelian', '2026-11-07 01:27:28', NULL),
(747, 'ar_piutang', 'Piutang Usaha (AR)', 'keuangan', '2026-11-07 01:27:28', NULL),
(748, 'bkm_masuk', 'Penerimaan Kas (BKM)', 'keuangan', '2026-11-07 01:27:28', NULL),
(749, 'ap_utang', 'Utang Usaha (AP)', 'keuangan', '2026-11-07 01:27:28', NULL),
(750, 'bkk_keluar', 'Pengeluaran Kas (BKK)', 'keuangan', '2026-11-07 01:27:28', NULL),
(751, 'gl_buku_besar', 'Buku Besar (GL)', 'keuangan', '2026-11-07 01:27:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `piutang_usaha`
--

CREATE TABLE `piutang_usaha` (
  `id` int UNSIGNED NOT NULL,
  `nomor_piutang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_id` int UNSIGNED NOT NULL,
  `pelanggan_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `sisa` decimal(18,2) NOT NULL,
  `status` enum('belum','sebagian','lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `piutang_usaha`
--

INSERT INTO `piutang_usaha` (`id`, `nomor_piutang`, `so_id`, `pelanggan_id`, `tanggal`, `jatuh_tempo`, `total`, `sisa`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PIU-202611-0001', 9, 4, '2026-11-03', '2026-12-03', 18482500.00, 0.00, 'lunas', '2026-11-03 01:15:08', '2026-11-03 01:17:03'),
(2, 'PIU-202611-0002', 100, 17, '2026-11-03', '2026-12-03', 28000.00, 0.00, 'lunas', '2026-11-03 06:08:00', '2026-11-03 06:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int UNSIGNED NOT NULL,
  `kode_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` int UNSIGNED DEFAULT NULL,
  `satuan_id` int UNSIGNED DEFAULT NULL,
  `harga_beli` decimal(18,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(18,2) NOT NULL DEFAULT '0.00',
  `harga_grosir` decimal(18,2) NOT NULL DEFAULT '0.00',
  `harga_distributor` decimal(18,2) NOT NULL DEFAULT '0.00',
  `stok_minimum` int NOT NULL DEFAULT '5',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kode_produk`, `barcode`, `nama_produk`, `kategori_id`, `satuan_id`, `harga_beli`, `harga_jual`, `harga_grosir`, `harga_distributor`, `stok_minimum`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PRD-0001', '89910010001', 'Laptop Pro 15-inch Intel i7 16GB SSD 512GB', 1, 1, 12500000.00, 14900000.00, 14500000.00, 14000000.00, 3, 'active', '2026-10-30 15:46:37', '2026-10-30 15:46:37', NULL),
(2, 'PRD-0002', '89910010002', 'Printer Laserjet Enterprise Pro Monochrome', 1, 1, 3800000.00, 4900000.00, 4700000.00, 4500000.00, 5, 'active', '2026-10-30 15:46:37', '2026-11-02 04:10:20', NULL),
(3, 'PRD-0007', '89910010007', 'RAM Corsair Vengeance DDR4 16GB 3200MHz', 1, 1, 800000.00, 1050000.00, 1000000.00, 950000.00, 10, 'active', '2026-10-30 15:46:37', '2026-10-30 15:46:37', NULL),
(4, 'PRD-0011', '89910010011', 'Laptop Ideapad Gaming 3 AMD Ryzen 5', 1, 1, 9200000.00, 11200000.00, 10900000.00, 10500000.00, 4, 'active', '2026-10-30 15:46:37', '2026-10-30 15:46:37', NULL),
(5, 'PRD-0010', NULL, 'KOPI GULA AREN', 3, 5, 99911.00, 120000.00, 115000.00, 110000.00, 10, 'active', '2026-11-02 04:09:41', '2026-11-02 07:43:39', NULL),
(6, 'PRD-0012', 'PRD-0012', 'Kopi Arabika Aceh Gayo Washed Grade 1 250g', 3, 1, 101500.00, 145000.00, 145000.00, 145000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(7, 'PRD-0013', 'PRD-0013', 'Sirup Gourmet Salted Caramel 750ml Artisan Glass', 3, 1, 91000.00, 130000.00, 130000.00, 130000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(8, 'PRD-0014', 'PRD-0014', 'Pure Dark Cocoa Chocolate Powder 1kg Export', 3, 1, 84000.00, 120000.00, 120000.00, 120000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(9, 'PRD-0015', 'PRD-0015', 'Paper Cup Hot 8oz Double Wall Embossed (Isi 50)', 5, 3, 22400.00, 32000.00, 32000.00, 32000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(10, 'PRD-0016', 'PRD-0016', 'Kopi Robusta Flores Manggarai Fine Robusta 1kg', 3, 1, 57400.00, 82000.00, 82000.00, 82000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(11, 'PRD-0017', 'PRD-0017', 'Sirup Classic Hazelnut 750ml Flavouring Syrup', 3, 1, 94500.00, 135000.00, 135000.00, 135000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(12, 'PRD-0018', 'PRD-0018', 'Matcha Green Tea Powder Premium Uji Grade 500g', 3, 1, 147000.00, 210000.00, 210000.00, 210000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(13, 'PRD-0019', 'PRD-0019', 'Kopi Arabika Bali Kintamani Natural Anaerob 250g', 3, 1, 112000.00, 160000.00, 160000.00, 160000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(14, 'PRD-0020', 'PRD-0020', 'Sirup French Vanilla Gourmet 750ml', 3, 1, 94500.00, 135000.00, 135000.00, 135000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(15, 'PRD-0021', 'PRD-0021', 'Kopi Arabika Toraja Sapan Grade 1 Semi Washed 1kg', 3, 1, 199500.00, 285000.00, 285000.00, 285000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(16, 'PRD-0022', 'PRD-0022', 'Kopi Robusta Java Dampit Grade 1', 3, 1, 94500.00, 135000.00, 135000.00, 135000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(17, 'PRD-0023', 'PRD-0023', 'Sirup Salted Caramel Gourmet 750ml', 3, 1, 87500.00, 125000.00, 125000.00, 125000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(18, 'PRD-0024', 'PRD-0024', 'Ceremonial Matcha Powder Uji Japan 1kg', 5, 1, 224000.00, 320000.00, 320000.00, 320000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(19, 'PRD-0025', 'PRD-0025', 'Susu UHT Plain Full Cream 1L', 3, 1, 19600.00, 28000.00, 28000.00, 28000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(20, 'PRD-0026', 'PRD-0026', 'Gula Pasir Rafinasi Premium Murni 1kg', 5, 1, 15400.00, 22000.00, 22000.00, 22000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(21, 'PRD-0027', 'PRD-0027', 'Sirup Roasted Hazelnut Artisanal 750ml', 3, 1, 87500.00, 125000.00, 125000.00, 125000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(22, 'PRD-0028', 'PRD-0028', 'Signature Cold Brew Coffee Concentrate 250ml', 3, 1, 101500.00, 145000.00, 145000.00, 145000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(23, 'PRD-0029', 'PRD-0029', 'Premium RTD Matcha Latte Bottle 250ml', 5, 1, 101500.00, 145000.00, 145000.00, 145000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(24, 'PRD-0030', 'PRD-0030', 'Drip Bag Coffee Artisanal Gift Box', 3, 2, 115500.00, 165000.00, 165000.00, 165000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(25, 'PRD-0031', 'PRD-0031', 'Paper Cup 12oz Hot Embossed Double Wall', 5, 1, 59500.00, 85000.00, 85000.00, 85000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(26, 'PRD-0032', 'PRD-0032', 'Sedotan Kertas Kraft Ramah Lingkungan 6mm', 2, 1, 24500.00, 35000.00, 35000.00, 35000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL),
(27, 'PRD-0033', 'PRD-0033', 'Standing Pouch Matte Black One-Way Valve', 5, 1, 31500.00, 45000.00, 45000.00, 45000.00, 5, 'active', '2026-11-03 03:51:37', '2026-11-03 11:23:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int UNSIGNED NOT NULL,
  `nomor_po` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `tanggal_po` date NOT NULL,
  `termin` enum('tunai','tempo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `tempo_hari` int NOT NULL DEFAULT '0',
  `jatuh_tempo` date DEFAULT NULL,
  `mata_uang` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `nilai_kurs` decimal(18,6) NOT NULL DEFAULT '1.000000',
  `subtotal` decimal(18,2) NOT NULL DEFAULT '0.00',
  `diskon` decimal(18,2) NOT NULL DEFAULT '0.00',
  `pajak` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_idr` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','received','partial','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `nomor_po`, `supplier_id`, `tanggal_po`, `termin`, `tempo_hari`, `jatuh_tempo`, `mata_uang`, `nilai_kurs`, `subtotal`, `diskon`, `pajak`, `total`, `total_idr`, `status`, `catatan`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'PO-202610-0001', 1, '2026-10-30', 'tunai', 0, NULL, 'IDR', 1.000000, 5000000.00, 0.00, 0.00, 5000000.00, 5000000.00, 'draft', NULL, NULL, '2026-10-31 10:32:08', NULL),
(2, 'PO-202611-0001', 4, '2026-11-02', 'tunai', 30, NULL, 'USD', 18132.701322, 110.20, 0.00, 0.00, 110.20, 1998223.68, 'received', '', 10, '2026-11-02 07:06:00', '2026-11-02 07:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item`
--

CREATE TABLE `purchase_order_item` (
  `id` int UNSIGNED NOT NULL,
  `po_id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `qty_diterima` decimal(14,3) NOT NULL DEFAULT '0.000',
  `harga_satuan` decimal(18,2) NOT NULL,
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_item`
--

INSERT INTO `purchase_order_item` (`id`, `po_id`, `produk_id`, `qty`, `qty_diterima`, `harga_satuan`, `subtotal`) VALUES
(1, 2, 5, 20.000, 20.000, 5.51, 110.20);

-- --------------------------------------------------------

--
-- Table structure for table `retur_pembelian`
--

CREATE TABLE `retur_pembelian` (
  `id` int UNSIGNED NOT NULL,
  `nomor_retur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `tindak_lanjut` enum('potong_utang','refund_kas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `alasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retur_pembelian`
--

INSERT INTO `retur_pembelian` (`id`, `nomor_retur`, `po_id`, `supplier_id`, `tanggal`, `tindak_lanjut`, `total`, `status`, `alasan`, `user_id`, `created_at`) VALUES
(1, 'RTB-202611-0001', 1, 1, '2026-11-01', 'potong_utang', 350000.00, 'completed', 'Cacat fisik pada part dari vendor', 1, '2026-11-01 02:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `retur_pembelian_item`
--

CREATE TABLE `retur_pembelian_item` (
  `id` int UNSIGNED NOT NULL,
  `retur_id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `harga_satuan` decimal(18,2) NOT NULL,
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retur_pembelian_item`
--

INSERT INTO `retur_pembelian_item` (`id`, `retur_id`, `produk_id`, `qty`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 2.000, 175000.00, 350000.00);

-- --------------------------------------------------------

--
-- Table structure for table `retur_penjualan`
--

CREATE TABLE `retur_penjualan` (
  `id` int UNSIGNED NOT NULL,
  `nomor_retur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_id` int UNSIGNED NOT NULL,
  `pelanggan_id` int UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `tindak_lanjut` enum('ganti_barang','potong_piutang','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kondisi_barang` enum('baik','rusak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `alasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retur_penjualan`
--

INSERT INTO `retur_penjualan` (`id`, `nomor_retur`, `so_id`, `pelanggan_id`, `tanggal`, `tindak_lanjut`, `kondisi_barang`, `total`, `status`, `alasan`, `user_id`, `created_at`) VALUES
(1, 'RTJ-202611-0001', 1, 1, '2026-11-01', 'ganti_barang', 'rusak', 150000.00, 'completed', 'Kemasan produk bocor saat pengiriman kurir', 1, '2026-11-01 02:09:33'),
(2, 'RET_JUAL-202611-0001', 8, 3, '2026-11-03', 'ganti_barang', 'baik', 1050000.00, 'completed', 'salah kirim barang', 1, '2026-11-03 03:34:32'),
(3, 'RET_JUAL-202611-0002', 5, 2, '2026-11-03', 'ganti_barang', 'baik', 16220000.00, 'completed', 'salah barang', 1, '2026-11-03 06:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `retur_penjualan_item`
--

CREATE TABLE `retur_penjualan_item` (
  `id` int UNSIGNED NOT NULL,
  `retur_id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `harga_satuan` decimal(18,2) NOT NULL,
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retur_penjualan_item`
--

INSERT INTO `retur_penjualan_item` (`id`, `retur_id`, `produk_id`, `qty`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 1.000, 150000.00, 150000.00),
(2, 2, 3, 1.000, 1050000.00, 1050000.00),
(3, 3, 5, 1.000, 120000.00, 120000.00),
(4, 3, 4, 1.000, 11200000.00, 11200000.00),
(5, 3, 2, 1.000, 4900000.00, 4900000.00);

-- --------------------------------------------------------

--
-- Table structure for table `rfm_segments`
--

CREATE TABLE `rfm_segments` (
  `id` int UNSIGNED NOT NULL,
  `segment_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `r_min` int NOT NULL,
  `r_max` int NOT NULL,
  `f_min` int NOT NULL,
  `f_max` int NOT NULL,
  `m_min` int NOT NULL,
  `m_max` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommended_action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfm_segments`
--

INSERT INTO `rfm_segments` (`id`, `segment_name`, `r_min`, `r_max`, `f_min`, `f_max`, `m_min`, `m_max`, `description`, `recommended_action`, `created_at`, `updated_at`) VALUES
(1, 'Champions', 4, 5, 4, 5, 4, 5, 'Pelanggan terbaik yang baru saja berbelanja, sering beli, dan nilai belanjanya sangat besar.', 'Berikan reward VIP, akses eksklusif produk baru, program referral, dan apresiasi personal dari manajemen.', '2026-10-30 14:52:11', '2026-11-06 04:32:02'),
(2, 'Loyal Customers', 3, 5, 3, 5, 3, 5, 'Pelanggan setia yang berbelanja secara konsisten dengan nilai yang baik.', 'Upselling produk bernilai lebih tinggi, tawarkan voucher loyalitas berkala, dan minta ulasan produk.', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, 'Potential Loyalists', 4, 5, 2, 3, 2, 3, 'Pembeli baru yang berpotensi menjadi pelanggan setia karena transaksi berulang baru dimulai.', 'Tawarkan program membership dan diskon pada pembelian berikutnya untuk membentuk kebiasaan belanja.', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, 'At Risk', 1, 2, 3, 5, 3, 5, 'Pelanggan bernilai tinggi yang sudah lama tidak berbelanja. Berisiko pindah ke kompetitor.', 'Kirim kampanye reaktivasi personal WhatsApp dengan penawaran spesial \"Kami Merindukan Anda\" dan voucher diskon terbatas.', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(5, 'Hibernating', 1, 2, 1, 2, 1, 2, 'Pelanggan dengan frekuensi dan nilai rendah yang sudah lama tidak aktif.', 'Kirimkan katalog produk baru atau diskon cuci stok untuk memancing ketertarikan kembali.', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(6, 'Lost', 1, 1, 1, 1, 1, 1, 'Pelanggan dengan skor terendah di semua metrik yang sudah tidak berinteraksi.', 'Survei kepuasan singkat via WhatsApp untuk memahami alasan berhenti, fokuskan sumber daya promosi ke segmen lain.', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(7, 'New Customers', 4, 5, 1, 1, 1, 3, 'Bina jadi pembeli ulang', 'Reminder, konsultasi awal, dan edukasi cara konsumsi', '2026-11-06 04:31:31', '2026-11-06 04:31:31'),
(8, 'Big Spenders', 3, 5, 1, 3, 4, 5, 'Pertahankan nilai belanja', 'Berikan penawaran VIP, diskon eksklusif, dan layanan personal', '2026-11-06 04:31:31', '2026-11-06 04:31:31'),
(9, 'Frequent Low Value', 2, 4, 3, 5, 1, 2, 'Dorong naik kelas belanja', 'Upsell produk premium, bundling hemat', '2026-11-06 04:31:31', '2026-11-06 04:31:31'),
(10, 'Need Attention', 2, 3, 1, 2, 2, 3, 'Kenalkan kembali brand & manfaat', 'Kirim ulang value produk + testimoni relevan', '2026-11-06 04:31:31', '2026-11-06 04:31:31'),
(11, 'Can\'t Lose Them', 1, 2, 4, 5, 4, 5, 'Win back segera', 'Kontak langsung, penawaran istimewa, eskalasi ke manajemen', '2026-11-06 04:31:31', '2026-11-06 04:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Super Admin', 'Akses penuh ke seluruh sistem dan konfigurasi', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(2, 'manager', 'Manager', 'Akses manajerial, otorisasi, dan pelaporan keuangan', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, 'kasir', 'Kasir POS', 'Operator kasir penjualan cepat dan penerimaan kas', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, 'admin_stok', 'Admin Stok', 'Pengelolaan inventori, stok opname, dan penerimaan PO', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(5, 'staff_cs', 'Staff CS & CRM', 'Layanan after-sales, chat WhatsApp, dan tindak lanjut CRM', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(6, 'Keuangan', 'Keuangan', 'bagian keuangan', '2026-11-07 03:21:27', '2026-11-07 03:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
(1, 1, 701, '2026-11-07 01:27:28'),
(2, 1, 702, '2026-11-07 01:27:28'),
(3, 1, 703, '2026-11-07 01:27:28'),
(4, 1, 704, '2026-11-07 01:27:28'),
(5, 1, 705, '2026-11-07 01:27:28'),
(6, 1, 706, '2026-11-07 01:27:28'),
(7, 1, 707, '2026-11-07 01:27:28'),
(8, 1, 708, '2026-11-07 01:27:28'),
(9, 1, 709, '2026-11-07 01:27:28'),
(10, 1, 710, '2026-11-07 01:27:28'),
(11, 1, 711, '2026-11-07 01:27:28'),
(12, 1, 712, '2026-11-07 01:27:28'),
(13, 1, 713, '2026-11-07 01:27:28'),
(14, 1, 714, '2026-11-07 01:27:28'),
(15, 1, 715, '2026-11-07 01:27:28'),
(16, 1, 716, '2026-11-07 01:27:28'),
(17, 1, 717, '2026-11-07 01:27:28'),
(18, 1, 718, '2026-11-07 01:27:28'),
(19, 1, 719, '2026-11-07 01:27:28'),
(20, 1, 720, '2026-11-07 01:27:28'),
(21, 1, 721, '2026-11-07 01:27:28'),
(22, 1, 722, '2026-11-07 01:27:28'),
(23, 1, 723, '2026-11-07 01:27:28'),
(24, 1, 724, '2026-11-07 01:27:28'),
(25, 1, 725, '2026-11-07 01:27:28'),
(26, 1, 726, '2026-11-07 01:27:28'),
(27, 1, 727, '2026-11-07 01:27:28'),
(28, 1, 728, '2026-11-07 01:27:28'),
(29, 1, 729, '2026-11-07 01:27:28'),
(30, 1, 730, '2026-11-07 01:27:28'),
(31, 1, 731, '2026-11-07 01:27:28'),
(32, 1, 732, '2026-11-07 01:27:28'),
(33, 1, 733, '2026-11-07 01:27:28'),
(34, 1, 734, '2026-11-07 01:27:28'),
(35, 1, 735, '2026-11-07 01:27:28'),
(36, 1, 736, '2026-11-07 01:27:28'),
(37, 1, 737, '2026-11-07 01:27:28'),
(38, 1, 738, '2026-11-07 01:27:28'),
(39, 1, 739, '2026-11-07 01:27:28'),
(40, 1, 740, '2026-11-07 01:27:28'),
(41, 1, 741, '2026-11-07 01:27:28'),
(42, 1, 742, '2026-11-07 01:27:28'),
(43, 1, 743, '2026-11-07 01:27:28'),
(44, 1, 744, '2026-11-07 01:27:28'),
(45, 1, 745, '2026-11-07 01:27:28'),
(46, 1, 746, '2026-11-07 01:27:28'),
(47, 1, 747, '2026-11-07 01:27:28'),
(48, 1, 748, '2026-11-07 01:27:28'),
(49, 1, 749, '2026-11-07 01:27:28'),
(50, 1, 750, '2026-11-07 01:27:28'),
(51, 1, 751, '2026-11-07 01:27:28'),
(52, 2, 701, '2026-11-07 01:27:28'),
(53, 2, 702, '2026-11-07 01:27:28'),
(54, 2, 703, '2026-11-07 01:27:28'),
(55, 2, 704, '2026-11-07 01:27:28'),
(56, 2, 705, '2026-11-07 01:27:28'),
(57, 2, 706, '2026-11-07 01:27:28'),
(58, 2, 707, '2026-11-07 01:27:28'),
(59, 2, 708, '2026-11-07 01:27:28'),
(60, 2, 709, '2026-11-07 01:27:28'),
(61, 2, 710, '2026-11-07 01:27:28'),
(62, 2, 711, '2026-11-07 01:27:28'),
(63, 2, 712, '2026-11-07 01:27:28'),
(64, 2, 713, '2026-11-07 01:27:28'),
(65, 2, 714, '2026-11-07 01:27:28'),
(66, 2, 715, '2026-11-07 01:27:28'),
(67, 2, 720, '2026-11-07 01:27:28'),
(68, 2, 721, '2026-11-07 01:27:28'),
(69, 2, 722, '2026-11-07 01:27:28'),
(70, 2, 723, '2026-11-07 01:27:28'),
(71, 2, 724, '2026-11-07 01:27:28'),
(72, 2, 725, '2026-11-07 01:27:28'),
(73, 2, 727, '2026-11-07 01:27:28'),
(74, 2, 728, '2026-11-07 01:27:28'),
(75, 2, 729, '2026-11-07 01:27:28'),
(76, 2, 730, '2026-11-07 01:27:28'),
(77, 2, 731, '2026-11-07 01:27:28'),
(78, 2, 732, '2026-11-07 01:27:28'),
(79, 2, 733, '2026-11-07 01:27:28'),
(80, 2, 734, '2026-11-07 01:27:28'),
(81, 2, 735, '2026-11-07 01:27:28'),
(82, 2, 736, '2026-11-07 01:27:28'),
(83, 2, 737, '2026-11-07 01:27:28'),
(84, 2, 738, '2026-11-07 01:27:28'),
(85, 2, 739, '2026-11-07 01:27:28'),
(86, 2, 740, '2026-11-07 01:27:28'),
(87, 2, 741, '2026-11-07 01:27:28'),
(88, 2, 742, '2026-11-07 01:27:28'),
(89, 2, 743, '2026-11-07 01:27:28'),
(90, 2, 744, '2026-11-07 01:27:28'),
(91, 2, 745, '2026-11-07 01:27:28'),
(92, 2, 746, '2026-11-07 01:27:28'),
(93, 2, 747, '2026-11-07 01:27:28'),
(94, 2, 748, '2026-11-07 01:27:28'),
(95, 2, 749, '2026-11-07 01:27:28'),
(96, 2, 750, '2026-11-07 01:27:28'),
(97, 2, 751, '2026-11-07 01:27:28'),
(98, 3, 715, '2026-11-07 01:27:28'),
(99, 3, 728, '2026-11-07 01:27:28'),
(100, 3, 729, '2026-11-07 01:27:28'),
(101, 3, 736, '2026-11-07 01:27:28'),
(102, 3, 737, '2026-11-07 01:27:28'),
(103, 3, 739, '2026-11-07 01:27:28'),
(155, 5, 715, '2026-11-07 01:27:28'),
(156, 5, 728, '2026-11-07 01:27:28'),
(157, 5, 729, '2026-11-07 01:27:28'),
(158, 5, 714, '2026-11-07 01:27:28'),
(159, 5, 741, '2026-11-07 01:27:28'),
(160, 5, 742, '2026-11-07 01:27:28'),
(161, 4, 715, '2026-11-07 01:29:31'),
(162, 4, 728, '2026-11-07 01:29:31'),
(163, 4, 729, '2026-11-07 01:29:31'),
(164, 4, 730, '2026-11-07 01:29:31'),
(165, 4, 731, '2026-11-07 01:29:31'),
(166, 4, 732, '2026-11-07 01:29:31'),
(167, 4, 733, '2026-11-07 01:29:31'),
(168, 4, 734, '2026-11-07 01:29:31'),
(169, 4, 735, '2026-11-07 01:29:31'),
(170, 4, 743, '2026-11-07 01:29:31'),
(171, 4, 744, '2026-11-07 01:29:31'),
(172, 4, 745, '2026-11-07 01:29:31'),
(173, 4, 746, '2026-11-07 01:29:31'),
(174, 4, 703, '2026-11-07 01:29:31'),
(175, 4, 706, '2026-11-07 01:29:31'),
(176, 4, 707, '2026-11-07 01:29:31'),
(177, 4, 708, '2026-11-07 01:29:31'),
(178, 4, 711, '2026-11-07 01:29:31'),
(179, 4, 712, '2026-11-07 01:29:31'),
(180, 4, 713, '2026-11-07 01:29:31'),
(181, 6, 715, '2026-11-07 03:23:27'),
(182, 6, 739, '2026-11-07 03:23:27'),
(183, 6, 747, '2026-11-07 03:23:27'),
(184, 6, 748, '2026-11-07 03:23:27'),
(185, 6, 749, '2026-11-07 03:23:27'),
(186, 6, 750, '2026-11-07 03:23:27'),
(187, 6, 751, '2026-11-07 03:23:27'),
(188, 6, 701, '2026-11-07 03:23:27'),
(189, 6, 709, '2026-11-07 03:23:27'),
(190, 6, 710, '2026-11-07 03:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `id` int UNSIGNED NOT NULL,
  `nomor_so` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_so` date NOT NULL,
  `source` enum('pos','manual','shopee','tiktok','tokopedia','lazada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `pelanggan_id` int UNSIGNED DEFAULT NULL,
  `subtotal` decimal(18,2) NOT NULL DEFAULT '0.00',
  `diskon_total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `ppn` decimal(18,2) NOT NULL DEFAULT '0.00',
  `ongkir` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `bayar` decimal(18,2) NOT NULL DEFAULT '0.00',
  `kembalian` decimal(18,2) NOT NULL DEFAULT '0.00',
  `metode_bayar` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `status` enum('draft','confirmed','packing','ready_to_ship','shipped','delivered','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`id`, `nomor_so`, `tanggal_so`, `source`, `pelanggan_id`, `subtotal`, `diskon_total`, `ppn`, `ongkir`, `total`, `bayar`, `kembalian`, `metode_bayar`, `status`, `catatan`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SO-202610-0001', '2026-10-30', 'pos', 1, 3500000.00, 0.00, 0.00, 0.00, 3500000.00, 3500000.00, 0.00, 'tunai', 'completed', NULL, NULL, '2026-10-31 10:34:22', NULL, NULL),
(2, 'TRX-202610-0001', '2026-10-31', 'pos', 1, 11200000.00, 0.00, 0.00, 0.00, 11200000.00, 11200000.00, 0.00, 'tunai', 'completed', '', 1, '2026-10-31 14:57:44', '2026-10-31 14:57:44', NULL),
(3, 'TRX-202611-0001', '2026-11-01', 'pos', 1, 4800000.00, 0.00, 0.00, 0.00, 4800000.00, 4800000.00, 0.00, 'tunai', 'completed', 'Transaksi Kasir POS', 1, '2026-11-01 08:57:07', '2026-11-01 08:57:07', NULL),
(4, 'TRX-202611-0002', '2026-11-02', 'pos', 1, 17000000.00, 0.00, 0.00, 0.00, 17000000.00, 17000000.00, 0.00, 'tunai', 'completed', 'Transaksi Kasir POS', 1, '2026-11-02 08:11:40', '2026-11-02 08:11:40', NULL),
(5, 'TRX-202611-0003', '2026-11-02', 'pos', 2, 16220000.00, 0.00, 0.00, 0.00, 16220000.00, 16220000.00, 0.00, 'transfer', 'completed', 'Transaksi Kasir POS', 1, '2026-11-02 08:13:50', '2026-11-02 08:13:50', NULL),
(6, 'TRX-202611-0004', '2026-11-02', 'pos', 1, 12250000.00, 0.00, 0.00, 0.00, 12250000.00, 13600000.00, 1350000.00, 'tunai', 'completed', 'Transaksi Kasir POS', 1, '2026-11-02 08:25:03', '2026-11-02 08:25:03', NULL),
(7, 'TRX-202611-0005', '2026-11-02', 'pos', 1, 12250000.00, 0.00, 0.00, 0.00, 12250000.00, 13800000.00, 1550000.00, 'tunai', 'completed', 'Transaksi Kasir POS', 1, '2026-11-02 08:25:41', '2026-11-02 08:25:41', NULL),
(8, 'TRX-202611-0006', '2026-11-02', 'pos', 3, 1050000.00, 0.00, 0.00, 0.00, 1050000.00, 1050000.00, 0.00, 'tunai', 'completed', 'Transaksi Kasir POS', 1, '2026-11-02 08:27:27', '2026-11-02 08:27:27', NULL),
(9, 'SO-202611-0001', '2026-11-03', 'manual', 4, 15950000.00, 200000.00, 1732500.00, 1000000.00, 18482500.00, 0.00, 0.00, 'tempo', 'confirmed', 'untuk pengirimannya cepat kak', 1, '2026-11-03 01:15:08', '2026-11-03 01:15:28', NULL),
(80, 'SO-202611-0043', '2026-10-28', 'tokopedia', 7, 304000.00, 0.00, 0.00, 14000.00, 304000.00, 304000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP101A1', 1, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(81, 'SO-202611-0044', '2026-10-28', 'tokopedia', 8, 148000.00, 0.00, 0.00, 18000.00, 148000.00, 148000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP102B2', 1, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(82, 'SO-202611-0045', '2026-10-28', 'tokopedia', 9, 366000.00, 0.00, 0.00, 16000.00, 366000.00, 366000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP103C3', 1, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(83, 'SO-202611-0046', '2026-10-28', 'tokopedia', 10, 214000.00, 0.00, 0.00, 22000.00, 214000.00, 214000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP104D4', 1, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(84, 'SO-202611-0047', '2026-10-28', 'tokopedia', 11, 348000.00, 0.00, 0.00, 20000.00, 348000.00, 348000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP105E5', 1, '2026-11-03 05:39:51', '2026-11-03 05:39:51', NULL),
(85, 'SO-202611-0048', '2026-10-28', 'tokopedia', 12, 280000.00, 0.00, 0.00, 10000.00, 280000.00, 280000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP106F6', 1, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(86, 'SO-202611-0049', '2026-10-28', 'tokopedia', 13, 225000.00, 0.00, 0.00, 15000.00, 225000.00, 225000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP107G7', 1, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(87, 'SO-202611-0050', '2026-10-28', 'tokopedia', 14, 346000.00, 0.00, 0.00, 26000.00, 346000.00, 346000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP108H8', 1, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(88, 'SO-202611-0051', '2026-10-28', 'tokopedia', 15, 286000.00, 0.00, 0.00, 16000.00, 286000.00, 286000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP109I9', 1, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(89, 'SO-202611-0052', '2026-10-28', 'tokopedia', 16, 612000.00, 0.00, 0.00, 42000.00, 612000.00, 612000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #261028SHP110J0', 1, '2026-11-03 05:39:52', '2026-11-03 05:39:52', NULL),
(90, 'SO-202611-0053', '2026-10-29', 'tokopedia', 7, 320000.00, 0.00, 0.00, 0.00, 320000.00, 320000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70001', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(91, 'SO-202611-0054', '2026-10-29', 'tokopedia', 17, 375000.00, 0.00, 0.00, 0.00, 375000.00, 375000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70002', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(92, 'SO-202611-0055', '2026-10-29', 'tokopedia', 18, 404000.00, 0.00, 0.00, 0.00, 404000.00, 404000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70003', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(93, 'SO-202611-0056', '2026-10-29', 'tokopedia', 19, 189000.00, 0.00, 0.00, 0.00, 189000.00, 189000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70004', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(94, 'SO-202611-0057', '2026-10-29', 'tokopedia', 20, 435000.00, 0.00, 0.00, 0.00, 435000.00, 435000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70005', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(95, 'SO-202611-0058', '2026-10-29', 'tokopedia', 21, 435000.00, 0.00, 0.00, 0.00, 435000.00, 435000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70006', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(96, 'SO-202611-0059', '2026-10-29', 'tokopedia', 22, 350000.00, 0.00, 0.00, 0.00, 350000.00, 350000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70007', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(97, 'SO-202611-0060', '2026-10-29', 'tokopedia', 23, 240000.00, 0.00, 0.00, 0.00, 240000.00, 240000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70008', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(98, 'SO-202611-0061', '2026-10-29', 'tokopedia', 24, 270000.00, 0.00, 0.00, 0.00, 270000.00, 270000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70009', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(99, 'SO-202611-0062', '2026-10-29', 'tokopedia', 25, 365000.00, 0.00, 0.00, 0.00, 365000.00, 365000.00, 0.00, 'tokopedia_escrow', 'completed', 'Pesanan Omnichannel tokopedia #INV/20261029/MPL/70010', 1, '2026-11-03 05:40:22', '2026-11-03 05:40:22', NULL),
(100, 'SO-202611-0063', '2026-11-03', 'manual', 17, 28000.00, 0.00, 0.00, 0.00, 28000.00, 0.00, 0.00, 'tempo', 'draft', '', 1, '2026-11-03 06:08:00', '2026-11-03 06:08:00', NULL),
(128, 'SO-202611-0090', '2026-11-01', 'shopee', 6, 371000.00, 15000.00, 0.00, 20000.00, 371000.00, 371000.00, 0.00, 'shopee_escrow', 'completed', 'Pesanan Marketplace Shopee Xpress Standard [SPXID04829104821] - Pesanan #241101SHOPEE8892', NULL, '2026-11-01 02:09:16', '2026-11-08 15:17:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_item`
--

CREATE TABLE `sales_order_item` (
  `id` int UNSIGNED NOT NULL,
  `so_id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `harga_satuan` decimal(18,2) NOT NULL,
  `diskon` decimal(18,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_order_item`
--

INSERT INTO `sales_order_item` (`id`, `so_id`, `produk_id`, `qty`, `harga_satuan`, `diskon`, `subtotal`) VALUES
(1, 1, 1, 1.000, 3500000.00, 0.00, 3500000.00),
(2, 2, 4, 1.000, 11200000.00, 0.00, 11200000.00),
(3, 3, 2, 1.000, 4800000.00, 0.00, 4800000.00),
(4, 4, 3, 2.000, 1050000.00, 0.00, 2100000.00),
(5, 4, 1, 1.000, 14900000.00, 0.00, 14900000.00),
(6, 5, 5, 1.000, 120000.00, 0.00, 120000.00),
(7, 5, 4, 1.000, 11200000.00, 0.00, 11200000.00),
(8, 5, 2, 1.000, 4900000.00, 0.00, 4900000.00),
(9, 6, 3, 1.000, 1050000.00, 0.00, 1050000.00),
(10, 6, 4, 1.000, 11200000.00, 0.00, 11200000.00),
(11, 7, 3, 1.000, 1050000.00, 0.00, 1050000.00),
(12, 7, 4, 1.000, 11200000.00, 0.00, 11200000.00),
(13, 8, 3, 1.000, 1050000.00, 0.00, 1050000.00),
(14, 9, 4, 1.000, 10500000.00, 0.00, 10500000.00),
(15, 9, 3, 1.000, 950000.00, 0.00, 950000.00),
(16, 9, 2, 1.000, 4500000.00, 0.00, 4500000.00),
(70, 80, 6, 2.000, 145000.00, 0.00, 290000.00),
(71, 81, 7, 1.000, 130000.00, 0.00, 130000.00),
(72, 82, 8, 3.000, 120000.00, 0.00, 360000.00),
(73, 83, 9, 6.000, 32000.00, 0.00, 192000.00),
(74, 84, 10, 4.000, 82000.00, 0.00, 328000.00),
(75, 85, 11, 2.000, 135000.00, 0.00, 270000.00),
(76, 86, 12, 1.000, 210000.00, 0.00, 210000.00),
(77, 87, 13, 2.000, 160000.00, 0.00, 320000.00),
(78, 88, 14, 2.000, 135000.00, 0.00, 270000.00),
(79, 89, 15, 2.000, 285000.00, 0.00, 570000.00),
(80, 90, 6, 1.000, 185000.00, 0.00, 185000.00),
(81, 90, 16, 1.000, 135000.00, 0.00, 135000.00),
(82, 91, 17, 2.000, 125000.00, 0.00, 250000.00),
(83, 91, 7, 1.000, 125000.00, 0.00, 125000.00),
(84, 92, 18, 1.000, 320000.00, 0.00, 320000.00),
(85, 92, 19, 3.000, 28000.00, 0.00, 84000.00),
(86, 93, 8, 1.000, 145000.00, 0.00, 145000.00),
(87, 93, 20, 2.000, 22000.00, 0.00, 44000.00),
(88, 94, 14, 2.000, 155000.00, 0.00, 310000.00),
(89, 94, 21, 1.000, 125000.00, 0.00, 125000.00),
(90, 95, 22, 2.000, 145000.00, 0.00, 290000.00),
(91, 95, 23, 1.000, 145000.00, 0.00, 145000.00),
(92, 96, 24, 1.000, 165000.00, 0.00, 165000.00),
(93, 96, 6, 1.000, 185000.00, 0.00, 185000.00),
(94, 97, 25, 2.000, 85000.00, 0.00, 170000.00),
(95, 97, 26, 2.000, 35000.00, 0.00, 70000.00),
(96, 98, 16, 1.000, 135000.00, 0.00, 135000.00),
(97, 98, 27, 3.000, 45000.00, 0.00, 135000.00),
(98, 99, 14, 1.000, 155000.00, 0.00, 155000.00),
(99, 99, 17, 1.000, 125000.00, 0.00, 125000.00),
(100, 99, 25, 1.000, 85000.00, 0.00, 85000.00),
(101, 100, 19, 1.000, 28000.00, 0.00, 28000.00),
(142, 128, 1, 2.000, 182500.00, 0.00, 365000.00);

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id` int UNSIGNED NOT NULL,
  `kode_satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id`, `kode_satuan`, `nama_satuan`, `created_at`, `updated_at`) VALUES
(1, 'PCS', 'Pieces / Buah', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(2, 'BOX', 'Box / Dus', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(3, 'PACK', 'Pack / Bungkus', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(4, 'SLOP', 'Slop / Ikat', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(5, 'KG', 'Kilogram', '2026-10-30 14:52:11', '2026-10-30 14:52:11'),
(6, 'ROLL', 'Roll', '2026-11-02 06:14:51', '2026-11-02 06:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `qty` decimal(14,3) NOT NULL DEFAULT '0.000',
  `reserved_qty` decimal(14,3) NOT NULL DEFAULT '0.000',
  `lokasi_rak` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GUDANG-UTAMA',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id`, `produk_id`, `qty`, `reserved_qty`, `lokasi_rak`, `updated_at`) VALUES
(1, 1, 48.000, 1.000, 'RAK-UTAMA', '2026-11-09 08:29:39'),
(2, 2, 48.000, 1.000, 'RAK-UTAMA', '2026-11-09 08:29:39'),
(3, 3, 41.000, 2.000, 'RAK-UTAMA', '2026-11-09 08:29:39'),
(4, 4, 46.000, 1.000, 'RAK-UTAMA', '2026-11-09 08:29:39'),
(5, 5, 22.000, 0.000, 'RAK-UTAMA', '2026-11-03 06:00:39'),
(6, 6, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(7, 7, 50.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(8, 8, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(9, 9, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(10, 10, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(11, 11, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(12, 12, 50.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(13, 13, 50.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(14, 14, 54.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(15, 15, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 08:45:43'),
(16, 16, 53.000, 0.000, 'RAK-UTAMA', '2026-11-08 23:16:02'),
(17, 17, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(18, 18, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(19, 19, 52.000, 1.000, 'RAK-UTAMA', '2026-11-09 08:29:39'),
(20, 20, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(21, 21, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(22, 22, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(23, 23, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(24, 24, 51.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(25, 25, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(26, 26, 52.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46'),
(27, 27, 53.000, 0.000, 'RAK-UTAMA', '2026-11-09 07:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `stok_log`
--

CREATE TABLE `stok_log` (
  `id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `tipe` enum('in','out','adj') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `saldo_akhir` decimal(14,3) NOT NULL,
  `referensi_dokumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_id` int UNSIGNED DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_log`
--

INSERT INTO `stok_log` (`id`, `produk_id`, `tipe`, `qty`, `saldo_akhir`, `referensi_dokumen`, `operator_id`, `keterangan`, `created_at`) VALUES
(1, 1, 'in', 50.000, 50.000, 'INITIAL-STOCK', NULL, 'Saldo awal inventori sistem', '2026-10-30 15:46:37'),
(2, 2, 'in', 50.000, 50.000, 'INITIAL-STOCK', NULL, 'Saldo awal inventori sistem', '2026-10-30 15:46:37'),
(3, 3, 'in', 50.000, 50.000, 'INITIAL-STOCK', NULL, 'Saldo awal inventori sistem', '2026-10-30 15:46:37'),
(4, 4, 'in', 50.000, 50.000, 'INITIAL-STOCK', NULL, 'Saldo awal inventori sistem', '2026-10-30 15:46:37'),
(5, 4, 'out', 1.000, 49.000, 'TRX-202610-0001', 1, 'Penjualan Kasir POS #TRX-202610-0001', '2026-10-31 14:57:44'),
(6, 2, 'out', 1.000, 49.000, 'TRX-202611-0001', 1, 'Penjualan Kasir POS #TRX-202611-0001', '2026-11-01 08:57:07'),
(7, 5, 'in', 20.000, 20.000, 'PO-202611-0001', 10, 'Penerimaan Barang PO #PO-202611-0001', '2026-11-02 07:06:25'),
(8, 5, 'adj', 2.000, 22.000, 'OPNAME-20261102-074459', 1, 'Stock Opname / Penyesuaian: ada yg ketemu', '2026-11-02 07:44:59'),
(9, 3, 'adj', -3.000, 47.000, 'OPNAME-BULK-20261102-074739', 1, 'Stock Opname / Penyesuaian: Pembaruan massal / Bulk Stock Editor', '2026-11-02 07:47:39'),
(10, 3, 'out', 2.000, 45.000, 'TRX-202611-0002', 1, 'Penjualan Kasir POS #TRX-202611-0002', '2026-11-02 08:11:40'),
(11, 1, 'out', 1.000, 49.000, 'TRX-202611-0002', 1, 'Penjualan Kasir POS #TRX-202611-0002', '2026-11-02 08:11:40'),
(12, 5, 'out', 1.000, 21.000, 'TRX-202611-0003', 1, 'Penjualan Kasir POS #TRX-202611-0003', '2026-11-02 08:13:50'),
(13, 4, 'out', 1.000, 48.000, 'TRX-202611-0003', 1, 'Penjualan Kasir POS #TRX-202611-0003', '2026-11-02 08:13:50'),
(14, 2, 'out', 1.000, 48.000, 'TRX-202611-0003', 1, 'Penjualan Kasir POS #TRX-202611-0003', '2026-11-02 08:13:50'),
(15, 3, 'out', 1.000, 44.000, 'TRX-202611-0004', 1, 'Penjualan Kasir POS #TRX-202611-0004', '2026-11-02 08:25:03'),
(16, 4, 'out', 1.000, 47.000, 'TRX-202611-0004', 1, 'Penjualan Kasir POS #TRX-202611-0004', '2026-11-02 08:25:03'),
(17, 3, 'out', 1.000, 43.000, 'TRX-202611-0005', 1, 'Penjualan Kasir POS #TRX-202611-0005', '2026-11-02 08:25:41'),
(18, 4, 'out', 1.000, 46.000, 'TRX-202611-0005', 1, 'Penjualan Kasir POS #TRX-202611-0005', '2026-11-02 08:25:41'),
(19, 3, 'out', 1.000, 42.000, 'TRX-202611-0006', 1, 'Penjualan Kasir POS #TRX-202611-0006', '2026-11-02 08:27:27'),
(20, 1, 'out', 1.000, 48.000, '231125GSSBFEUS', 1, 'Pengiriman Resi SPX Express [SPXID03873198928B] - Laptop Pro 15-inch Intel i7 16GB SSD 512GB', '2026-11-03 01:40:42'),
(21, 3, 'in', 1.000, 43.000, 'RET_JUAL-202611-0001', 1, 'Retur Penjualan #RET_JUAL-202611-0001', '2026-11-03 03:34:32'),
(22, 6, 'in', 52.000, 52.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-001)', '2026-11-03 03:51:37'),
(23, 7, 'in', 51.000, 51.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-006)', '2026-11-03 03:51:37'),
(24, 8, 'in', 53.000, 53.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-008)', '2026-11-03 03:51:37'),
(25, 9, 'in', 56.000, 56.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-PCK-002)', '2026-11-03 03:51:37'),
(26, 10, 'in', 54.000, 54.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-015)', '2026-11-03 03:51:37'),
(27, 11, 'in', 52.000, 52.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-007)', '2026-11-03 03:51:37'),
(28, 12, 'in', 51.000, 51.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-012)', '2026-11-03 03:51:37'),
(29, 13, 'in', 52.000, 52.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-016)', '2026-11-03 03:51:37'),
(30, 14, 'in', 52.000, 52.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-005)', '2026-11-03 03:51:37'),
(31, 15, 'in', 52.000, 52.000, 'INIT-shopee', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-003)', '2026-11-03 03:51:37'),
(32, 16, 'in', 51.000, 51.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-002)', '2026-11-03 03:51:37'),
(33, 17, 'in', 52.000, 52.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-086)', '2026-11-03 03:51:37'),
(34, 18, 'in', 51.000, 51.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-010)', '2026-11-03 03:51:37'),
(35, 19, 'in', 53.000, 53.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-083)', '2026-11-03 03:51:37'),
(36, 20, 'in', 52.000, 52.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-004)', '2026-11-03 03:51:37'),
(37, 21, 'in', 51.000, 51.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-RAW-887)', '2026-11-03 03:51:37'),
(38, 22, 'in', 52.000, 52.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-FNS-001)', '2026-11-03 03:51:37'),
(39, 23, 'in', 51.000, 51.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-FNS-064)', '2026-11-03 03:51:37'),
(40, 24, 'in', 51.000, 51.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-FOS-003)', '2026-11-03 03:51:37'),
(41, 25, 'in', 52.000, 52.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-PCK-012)', '2026-11-03 03:51:37'),
(42, 26, 'in', 52.000, 52.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-PCK-006)', '2026-11-03 03:51:37'),
(43, 27, 'in', 53.000, 53.000, 'INIT-tokopedia', NULL, 'Stok Awal Produk Baru dari Marketplace (PRD-FCC-006)', '2026-11-03 03:51:37'),
(44, 1, 'adj', 1.000, 49.000, '231125GSSBFEUS', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #231125GSSBFEUS', '2026-11-03 11:50:21'),
(45, 6, 'adj', 2.000, 54.000, '261028SHP101A1', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP101A1', '2026-11-03 11:50:21'),
(46, 7, 'adj', 1.000, 52.000, '261028SHP102B2', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP102B2', '2026-11-03 11:50:21'),
(47, 8, 'adj', 3.000, 56.000, '261028SHP103C3', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP103C3', '2026-11-03 11:50:21'),
(48, 9, 'adj', 6.000, 62.000, '261028SHP104D4', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP104D4', '2026-11-03 11:50:21'),
(49, 10, 'adj', 4.000, 58.000, '261028SHP105E5', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP105E5', '2026-11-03 11:50:21'),
(50, 11, 'adj', 2.000, 54.000, '261028SHP106F6', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP106F6', '2026-11-03 11:50:21'),
(51, 12, 'adj', 1.000, 52.000, '261028SHP107G7', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP107G7', '2026-11-03 11:50:21'),
(52, 13, 'adj', 2.000, 54.000, '261028SHP108H8', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP108H8', '2026-11-03 11:50:21'),
(53, 14, 'adj', 2.000, 54.000, '261028SHP109I9', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP109I9', '2026-11-03 11:50:21'),
(54, 15, 'adj', 2.000, 54.000, '261028SHP110J0', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #261028SHP110J0', '2026-11-03 11:50:21'),
(55, 6, 'adj', 1.000, 55.000, 'INV/20261029/MPL/70001', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70001', '2026-11-03 11:50:21'),
(56, 16, 'adj', 1.000, 52.000, 'INV/20261029/MPL/70001', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70001', '2026-11-03 11:50:21'),
(57, 17, 'adj', 2.000, 54.000, 'INV/20261029/MPL/70002', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70002', '2026-11-03 11:50:21'),
(58, 7, 'adj', 1.000, 53.000, 'INV/20261029/MPL/70002', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70002', '2026-11-03 11:50:21'),
(59, 18, 'adj', 1.000, 52.000, 'INV/20261029/MPL/70003', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70003', '2026-11-03 11:50:21'),
(60, 19, 'adj', 3.000, 56.000, 'INV/20261029/MPL/70003', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70003', '2026-11-03 11:50:21'),
(61, 8, 'adj', 1.000, 57.000, 'INV/20261029/MPL/70004', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70004', '2026-11-03 11:50:21'),
(62, 20, 'adj', 2.000, 54.000, 'INV/20261029/MPL/70004', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70004', '2026-11-03 11:50:21'),
(63, 14, 'adj', 2.000, 56.000, 'INV/20261029/MPL/70005', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70005', '2026-11-03 11:50:21'),
(64, 21, 'adj', 1.000, 52.000, 'INV/20261029/MPL/70005', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70005', '2026-11-03 11:50:21'),
(65, 22, 'adj', 2.000, 54.000, 'INV/20261029/MPL/70006', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70006', '2026-11-03 11:50:21'),
(66, 23, 'adj', 1.000, 52.000, 'INV/20261029/MPL/70006', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70006', '2026-11-03 11:50:21'),
(67, 24, 'adj', 1.000, 52.000, 'INV/20261029/MPL/70007', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70007', '2026-11-03 11:50:21'),
(68, 6, 'adj', 1.000, 56.000, 'INV/20261029/MPL/70007', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70007', '2026-11-03 11:50:21'),
(69, 25, 'adj', 2.000, 54.000, 'INV/20261029/MPL/70008', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70008', '2026-11-03 11:50:21'),
(70, 26, 'adj', 2.000, 54.000, 'INV/20261029/MPL/70008', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70008', '2026-11-03 11:50:21'),
(71, 16, 'adj', 1.000, 53.000, 'INV/20261029/MPL/70009', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70009', '2026-11-03 11:50:21'),
(72, 27, 'adj', 3.000, 56.000, 'INV/20261029/MPL/70009', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70009', '2026-11-03 11:50:21'),
(73, 14, 'adj', 1.000, 57.000, 'INV/20261029/MPL/70010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70010', '2026-11-03 11:50:21'),
(74, 17, 'adj', 1.000, 55.000, 'INV/20261029/MPL/70010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70010', '2026-11-03 11:50:21'),
(75, 25, 'adj', 1.000, 55.000, 'INV/20261029/MPL/70010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #INV/20261029/MPL/70010', '2026-11-03 11:50:21'),
(76, 6, 'adj', 1.000, 57.000, '57929381029472001', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472001', '2026-11-03 11:50:21'),
(77, 16, 'adj', 1.000, 54.000, '57929381029472001', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472001', '2026-11-03 11:50:21'),
(78, 17, 'adj', 2.000, 57.000, '57929381029472002', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472002', '2026-11-03 11:50:21'),
(79, 7, 'adj', 1.000, 54.000, '57929381029472002', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472002', '2026-11-03 11:50:21'),
(80, 18, 'adj', 1.000, 53.000, '57929381029472003', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472003', '2026-11-03 11:50:21'),
(81, 19, 'adj', 3.000, 59.000, '57929381029472003', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472003', '2026-11-03 11:50:21'),
(82, 8, 'adj', 1.000, 58.000, '57929381029472004', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472004', '2026-11-03 11:50:21'),
(83, 20, 'adj', 2.000, 56.000, '57929381029472004', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472004', '2026-11-03 11:50:21'),
(84, 14, 'adj', 2.000, 59.000, '57929381029472005', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472005', '2026-11-03 11:50:21'),
(85, 21, 'adj', 1.000, 53.000, '57929381029472005', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472005', '2026-11-03 11:50:21'),
(86, 22, 'adj', 2.000, 56.000, '57929381029472006', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472006', '2026-11-03 11:50:21'),
(87, 23, 'adj', 1.000, 53.000, '57929381029472006', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472006', '2026-11-03 11:50:21'),
(88, 24, 'adj', 1.000, 53.000, '57929381029472007', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472007', '2026-11-03 11:50:21'),
(89, 6, 'adj', 1.000, 58.000, '57929381029472007', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472007', '2026-11-03 11:50:21'),
(90, 25, 'adj', 2.000, 57.000, '57929381029472008', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472008', '2026-11-03 11:50:21'),
(91, 26, 'adj', 2.000, 56.000, '57929381029472008', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472008', '2026-11-03 11:50:21'),
(92, 16, 'adj', 1.000, 55.000, '57929381029472009', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472009', '2026-11-03 11:50:21'),
(93, 27, 'adj', 3.000, 59.000, '57929381029472009', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472009', '2026-11-03 11:50:21'),
(94, 14, 'adj', 1.000, 60.000, '57929381029472010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472010', '2026-11-03 11:50:21'),
(95, 17, 'adj', 1.000, 58.000, '57929381029472010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472010', '2026-11-03 11:50:21'),
(96, 25, 'adj', 1.000, 58.000, '57929381029472010', 1, 'Stock Opname / Penyesuaian: Reversal / Pembatalan Pesanan Marketplace #57929381029472010', '2026-11-03 11:50:21'),
(97, 6, 'out', 2.000, 56.000, '261028SHP101A1', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP101A1', '2026-11-03 05:39:51'),
(98, 7, 'out', 1.000, 53.000, '261028SHP102B2', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP102B2', '2026-11-03 05:39:51'),
(99, 8, 'out', 3.000, 55.000, '261028SHP103C3', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP103C3', '2026-11-03 05:39:51'),
(100, 9, 'out', 6.000, 56.000, '261028SHP104D4', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP104D4', '2026-11-03 05:39:51'),
(101, 10, 'out', 4.000, 54.000, '261028SHP105E5', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP105E5', '2026-11-03 05:39:51'),
(102, 11, 'out', 2.000, 52.000, '261028SHP106F6', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP106F6', '2026-11-03 05:39:52'),
(103, 12, 'out', 1.000, 51.000, '261028SHP107G7', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP107G7', '2026-11-03 05:39:52'),
(104, 13, 'out', 2.000, 52.000, '261028SHP108H8', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP108H8', '2026-11-03 05:39:52'),
(105, 14, 'out', 2.000, 58.000, '261028SHP109I9', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP109I9', '2026-11-03 05:39:52'),
(106, 15, 'out', 2.000, 52.000, '261028SHP110J0', 1, 'Sinkronisasi Pesanan tokopedia #261028SHP110J0', '2026-11-03 05:39:52'),
(107, 6, 'out', 1.000, 55.000, 'INV/20261029/MPL/70001', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70001', '2026-11-03 05:40:22'),
(108, 16, 'out', 1.000, 54.000, 'INV/20261029/MPL/70001', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70001', '2026-11-03 05:40:22'),
(109, 17, 'out', 2.000, 56.000, 'INV/20261029/MPL/70002', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70002', '2026-11-03 05:40:22'),
(110, 7, 'out', 1.000, 52.000, 'INV/20261029/MPL/70002', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70002', '2026-11-03 05:40:22'),
(111, 18, 'out', 1.000, 52.000, 'INV/20261029/MPL/70003', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70003', '2026-11-03 05:40:22'),
(112, 19, 'out', 3.000, 56.000, 'INV/20261029/MPL/70003', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70003', '2026-11-03 05:40:22'),
(113, 8, 'out', 1.000, 54.000, 'INV/20261029/MPL/70004', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70004', '2026-11-03 05:40:22'),
(114, 20, 'out', 2.000, 54.000, 'INV/20261029/MPL/70004', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70004', '2026-11-03 05:40:22'),
(115, 14, 'out', 2.000, 56.000, 'INV/20261029/MPL/70005', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70005', '2026-11-03 05:40:22'),
(116, 21, 'out', 1.000, 52.000, 'INV/20261029/MPL/70005', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70005', '2026-11-03 05:40:22'),
(117, 22, 'out', 2.000, 54.000, 'INV/20261029/MPL/70006', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70006', '2026-11-03 05:40:22'),
(118, 23, 'out', 1.000, 52.000, 'INV/20261029/MPL/70006', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70006', '2026-11-03 05:40:22'),
(119, 24, 'out', 1.000, 52.000, 'INV/20261029/MPL/70007', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70007', '2026-11-03 05:40:22'),
(120, 6, 'out', 1.000, 54.000, 'INV/20261029/MPL/70007', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70007', '2026-11-03 05:40:22'),
(121, 25, 'out', 2.000, 56.000, 'INV/20261029/MPL/70008', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70008', '2026-11-03 05:40:22'),
(122, 26, 'out', 2.000, 54.000, 'INV/20261029/MPL/70008', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70008', '2026-11-03 05:40:22'),
(123, 16, 'out', 1.000, 53.000, 'INV/20261029/MPL/70009', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70009', '2026-11-03 05:40:22'),
(124, 27, 'out', 3.000, 56.000, 'INV/20261029/MPL/70009', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70009', '2026-11-03 05:40:22'),
(125, 14, 'out', 1.000, 55.000, 'INV/20261029/MPL/70010', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70010', '2026-11-03 05:40:22'),
(126, 17, 'out', 1.000, 55.000, 'INV/20261029/MPL/70010', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70010', '2026-11-03 05:40:22'),
(127, 25, 'out', 1.000, 55.000, 'INV/20261029/MPL/70010', 1, 'Sinkronisasi Pesanan tokopedia #INV/20261029/MPL/70010', '2026-11-03 05:40:22'),
(128, 5, 'in', 1.000, 22.000, 'RET_JUAL-202611-0002', 1, 'Retur Penjualan #RET_JUAL-202611-0002', '2026-11-03 06:00:39'),
(129, 4, 'in', 1.000, 47.000, 'RET_JUAL-202611-0002', 1, 'Retur Penjualan #RET_JUAL-202611-0002', '2026-11-03 06:00:39'),
(130, 2, 'in', 1.000, 49.000, 'RET_JUAL-202611-0002', 1, 'Retur Penjualan #RET_JUAL-202611-0002', '2026-11-03 06:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int UNSIGNED NOT NULL,
  `kode_supplier` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mata_uang` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `kode_supplier`, `nama_supplier`, `no_telp`, `email`, `alamat`, `mata_uang`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SUP-0001', 'PT Sumber Distribusi Nusantara', '0215556789', 'sales@sumberdistribusi.co.id', 'Kawasan Industri Pulogadung Blok B No. 12', 'IDR', '2026-10-30 15:46:37', '2026-10-30 15:46:37', NULL),
(2, 'SUP-261102-39', 'aaaa', '0987654321', 'aaaa@gmail.com', 'solo', 'USD', '2026-11-02 05:51:51', '2026-11-02 06:13:41', '2026-11-02 06:13:41'),
(3, 'SUP-261102-81', 'aaak', '0987654320987', 'aaak@gmail.com', 'Singapura', 'SGD', '2026-11-02 05:53:47', '2026-11-02 05:54:07', '2026-11-02 05:54:07'),
(4, 'SUP-261102-89', 'aaaa', '098765432134', 'aaaa@gmail.com', 'Amerika', 'USD', '2026-11-02 06:58:03', '2026-11-02 06:58:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `avatar`, `password_hash`, `role_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@temandev', NULL, NULL, '$2y$10$Bl.qsupGzjjLdoJApeDEpOi7Vow.QToQ5HyBEwrFISSeKA/lo8wXy', 1, 'active', '2026-10-30 14:52:11', '2026-11-02 08:49:27', NULL),
(6, 'superadmin', 'superadmin@temandev', NULL, NULL, '$2y$10$aCmIizTmp6YkwCqqsobP2.//piA8mOj0zKgU2y.XjreTfvwP5Kuty', 1, 'active', '2026-10-30 15:45:30', '2026-11-02 08:49:17', NULL),
(9, 'acoy', 'acoy@gmail.com', NULL, NULL, '$2y$10$Ng6IMviDPj1XpMOxMLY5JefG.m/HfxaOiukXQfxB2UAQ6k39OuHom', 2, 'active', '2026-11-02 04:01:35', NULL, NULL),
(10, 'axoy', 'axoy@gmail.com', NULL, NULL, '$2y$10$EmVb7sROcYsFdQaJ6Yd5Z.X7GZ0fOlPNzaOdoQimI5QF73d9Cg93K', 3, 'active', '2026-11-02 04:02:52', '2026-11-07 01:30:47', NULL),
(11, 'aku', 'aku@gmail.com', '0987654321', NULL, '$2y$10$6/IOhcEs1Y8urIocC.5c3.cLSxhY9Dp.xGUBa8mh7kg0tvoMBnF.i', 5, 'active', '2026-11-07 03:19:34', '2026-11-09 08:15:08', NULL),
(12, 'kita', 'kita@gmail.com', NULL, NULL, '$2y$10$rjIJSeWA5C7mk1r.vvrUC.VDSdnfwgQsUG8DE..eghuvhWAZHTE5O', 6, 'active', '2026-11-07 03:22:11', '2026-11-07 03:22:11', NULL),
(14, 'hendro_dist', 'hendro@makmurjaya.co.id', NULL, NULL, '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', 2, 'active', '2026-10-31 10:42:47', NULL, NULL),
(15, 'mega_apotek', 'mega@sehatbarokah.id', NULL, NULL, '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', 2, 'active', '2026-10-31 10:42:47', NULL, NULL),
(16, 'doni_kafe', 'doni@aromanusantara.com', NULL, NULL, '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', 2, 'active', '2026-10-31 10:42:47', NULL, NULL),
(17, 'bambang_bengkel', 'bambang@motorberkah.co.id', NULL, NULL, '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', 2, 'active', '2026-10-31 10:42:47', NULL, NULL),
(18, 'agus_sentosa', 'agus@sentosabangunan.com', NULL, NULL, '$2y$10$PU23Y0wTQ5I3wiB2X4xwk.dFrELN8YCbMD1zbITc/mKWkfeoGeCIS', 2, 'active', '2026-10-31 10:42:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utang_usaha`
--

CREATE TABLE `utang_usaha` (
  `id` int UNSIGNED NOT NULL,
  `nomor_utang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `sisa` decimal(18,2) NOT NULL,
  `status` enum('belum','sebagian','lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utang_usaha`
--

INSERT INTO `utang_usaha` (`id`, `nomor_utang`, `po_id`, `supplier_id`, `tanggal`, `jatuh_tempo`, `total`, `sisa`, `status`, `created_at`, `updated_at`) VALUES
(1, 'AP-202611-0001', 1, 1, '2026-10-30', '2026-12-01', 5000000.00, 2500000.00, 'sebagian', '2026-11-01 02:09:33', '2026-11-09 05:34:38'),
(2, 'AP-202611-0002', 2, 4, '2026-11-02', '2026-12-02', 1998223.68, 1998223.68, 'belum', '2026-11-02 07:06:00', '2026-11-09 05:34:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_kas_bank`
--
ALTER TABLE `akun_kas_bank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_akun` (`kode_akun`),
  ADD KEY `akun_kas_bank_coa_id_foreign` (`coa_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `table_name_record_id` (`table_name`,`record_id`);

--
-- Indexes for table `calon_customer`
--
ALTER TABLE `calon_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `coa`
--
ALTER TABLE `coa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_akun` (`kode_akun`);

--
-- Indexes for table `crm_followup_categories`
--
ALTER TABLE `crm_followup_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `crm_followup_notes`
--
ALTER TABLE `crm_followup_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crm_followup_notes_pelanggan_id_foreign` (`pelanggan_id`),
  ADD KEY `customer_phone` (`customer_phone`);

--
-- Indexes for table `crm_transaction_followup_labels`
--
ALTER TABLE `crm_transaction_followup_labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_date_status` (`target_date`,`status`),
  ADD KEY `order_source_order_id` (`order_source`,`order_id`);

--
-- Indexes for table `devtools_crud`
--
ALTER TABLE `devtools_crud`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devtools_extension`
--
ALTER TABLE `devtools_extension`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_invoice` (`nomor_invoice`),
  ADD KEY `invoice_so_id_foreign` (`so_id`),
  ADD KEY `invoice_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_jurnal` (`nomor_jurnal`),
  ADD KEY `tanggal` (`tanggal`),
  ADD KEY `referensi` (`referensi`);

--
-- Indexes for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_detail_jurnal_id_foreign` (`jurnal_id`),
  ADD KEY `jurnal_detail_coa_id_foreign` (`coa_id`);

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`);

--
-- Indexes for table `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_uang_tanggal` (`mata_uang`,`tanggal`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nomor_urut`
--
ALTER TABLE `nomor_urut`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_dokumen_periode` (`kode_dokumen`,`periode`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pelanggan` (`kode_pelanggan`),
  ADD KEY `no_telp` (`no_telp`);

--
-- Indexes for table `pembayaran_piutang`
--
ALTER TABLE `pembayaran_piutang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_bkm` (`nomor_bkm`),
  ADD KEY `pembayaran_piutang_piutang_id_foreign` (`piutang_id`),
  ADD KEY `pembayaran_piutang_so_id_foreign` (`so_id`),
  ADD KEY `pembayaran_piutang_pelanggan_id_foreign` (`pelanggan_id`),
  ADD KEY `pembayaran_piutang_akun_kas_bank_id_foreign` (`akun_kas_bank_id`);

--
-- Indexes for table `pembayaran_utang`
--
ALTER TABLE `pembayaran_utang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_bkk` (`nomor_bkk`),
  ADD KEY `pembayaran_utang_utang_id_foreign` (`utang_id`),
  ADD KEY `pembayaran_utang_supplier_id_foreign` (`supplier_id`),
  ADD KEY `pembayaran_utang_akun_kas_bank_id_foreign` (`akun_kas_bank_id`);

--
-- Indexes for table `penjualan_marketplace`
--
ALTER TABLE `penjualan_marketplace`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_marketplace_source_pesanan` (`source`,`no_pesanan`),
  ADD KEY `penjualan_marketplace_so_id_foreign` (`so_id`),
  ADD KEY `nomor_resi` (`nomor_resi`);

--
-- Indexes for table `penjualan_marketplace_item`
--
ALTER TABLE `penjualan_marketplace_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_marketplace_item_penjualan_marketplace_id_foreign` (`penjualan_marketplace_id`),
  ADD KEY `penjualan_marketplace_item_produk_id_foreign` (`produk_id`),
  ADD KEY `sku` (`sku`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `piutang_usaha`
--
ALTER TABLE `piutang_usaha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_piutang` (`nomor_piutang`),
  ADD KEY `piutang_usaha_so_id_foreign` (`so_id`),
  ADD KEY `piutang_usaha_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`),
  ADD KEY `produk_kategori_id_foreign` (`kategori_id`),
  ADD KEY `produk_satuan_id_foreign` (`satuan_id`),
  ADD KEY `barcode` (`barcode`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_po` (`nomor_po`),
  ADD KEY `supplier_id_status` (`supplier_id`,`status`),
  ADD KEY `tanggal_po` (`tanggal_po`);

--
-- Indexes for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_item_po_id_foreign` (`po_id`),
  ADD KEY `purchase_order_item_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `retur_pembelian`
--
ALTER TABLE `retur_pembelian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_retur` (`nomor_retur`),
  ADD KEY `retur_pembelian_po_id_foreign` (`po_id`),
  ADD KEY `retur_pembelian_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `retur_pembelian_item`
--
ALTER TABLE `retur_pembelian_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retur_pembelian_item_retur_id_foreign` (`retur_id`),
  ADD KEY `retur_pembelian_item_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `retur_penjualan`
--
ALTER TABLE `retur_penjualan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_retur` (`nomor_retur`),
  ADD KEY `retur_penjualan_so_id_foreign` (`so_id`),
  ADD KEY `retur_penjualan_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `retur_penjualan_item`
--
ALTER TABLE `retur_penjualan_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retur_penjualan_item_retur_id_foreign` (`retur_id`),
  ADD KEY `retur_penjualan_item_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `rfm_segments`
--
ALTER TABLE `rfm_segments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `segment_name` (`segment_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id_permission_id` (`role_id`,`permission_id`);

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_so` (`nomor_so`),
  ADD KEY `idx_so_source_status_tanggal` (`source`,`status`,`tanggal_so`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `sales_order_item`
--
ALTER TABLE `sales_order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_item_so_id_foreign` (`so_id`),
  ADD KEY `sales_order_item_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_satuan` (`kode_satuan`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produk_id` (`produk_id`);

--
-- Indexes for table `stok_log`
--
ALTER TABLE `stok_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id_created_at` (`produk_id`,`created_at`),
  ADD KEY `referensi_dokumen` (`referensi_dokumen`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_supplier` (`kode_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `utang_usaha`
--
ALTER TABLE `utang_usaha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_utang` (`nomor_utang`),
  ADD KEY `utang_usaha_po_id_foreign` (`po_id`),
  ADD KEY `utang_usaha_supplier_id_foreign` (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun_kas_bank`
--
ALTER TABLE `akun_kas_bank`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT for table `calon_customer`
--
ALTER TABLE `calon_customer`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `coa`
--
ALTER TABLE `coa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `crm_followup_categories`
--
ALTER TABLE `crm_followup_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `crm_followup_notes`
--
ALTER TABLE `crm_followup_notes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `crm_transaction_followup_labels`
--
ALTER TABLE `crm_transaction_followup_labels`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=436;

--
-- AUTO_INCREMENT for table `devtools_crud`
--
ALTER TABLE `devtools_crud`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `devtools_extension`
--
ALTER TABLE `devtools_extension`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kurs`
--
ALTER TABLE `kurs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `nomor_urut`
--
ALTER TABLE `nomor_urut`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=480;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `pembayaran_piutang`
--
ALTER TABLE `pembayaran_piutang`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran_utang`
--
ALTER TABLE `pembayaran_utang`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `penjualan_marketplace`
--
ALTER TABLE `penjualan_marketplace`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `penjualan_marketplace_item`
--
ALTER TABLE `penjualan_marketplace_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=752;

--
-- AUTO_INCREMENT for table `piutang_usaha`
--
ALTER TABLE `piutang_usaha`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `retur_pembelian`
--
ALTER TABLE `retur_pembelian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `retur_pembelian_item`
--
ALTER TABLE `retur_pembelian_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `retur_penjualan`
--
ALTER TABLE `retur_penjualan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `retur_penjualan_item`
--
ALTER TABLE `retur_penjualan_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rfm_segments`
--
ALTER TABLE `rfm_segments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `sales_order_item`
--
ALTER TABLE `sales_order_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `stok_log`
--
ALTER TABLE `stok_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `utang_usaha`
--
ALTER TABLE `utang_usaha`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun_kas_bank`
--
ALTER TABLE `akun_kas_bank`
  ADD CONSTRAINT `akun_kas_bank_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `crm_followup_notes`
--
ALTER TABLE `crm_followup_notes`
  ADD CONSTRAINT `crm_followup_notes_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `invoice_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD CONSTRAINT `jurnal_detail_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `jurnal_detail_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran_piutang`
--
ALTER TABLE `pembayaran_piutang`
  ADD CONSTRAINT `pembayaran_piutang_akun_kas_bank_id_foreign` FOREIGN KEY (`akun_kas_bank_id`) REFERENCES `akun_kas_bank` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pembayaran_piutang_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pembayaran_piutang_piutang_id_foreign` FOREIGN KEY (`piutang_id`) REFERENCES `piutang_usaha` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pembayaran_piutang_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `pembayaran_utang`
--
ALTER TABLE `pembayaran_utang`
  ADD CONSTRAINT `pembayaran_utang_akun_kas_bank_id_foreign` FOREIGN KEY (`akun_kas_bank_id`) REFERENCES `akun_kas_bank` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pembayaran_utang_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pembayaran_utang_utang_id_foreign` FOREIGN KEY (`utang_id`) REFERENCES `utang_usaha` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `penjualan_marketplace`
--
ALTER TABLE `penjualan_marketplace`
  ADD CONSTRAINT `penjualan_marketplace_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `penjualan_marketplace_item`
--
ALTER TABLE `penjualan_marketplace_item`
  ADD CONSTRAINT `penjualan_marketplace_item_penjualan_marketplace_id_foreign` FOREIGN KEY (`penjualan_marketplace_id`) REFERENCES `penjualan_marketplace` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_marketplace_item_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `piutang_usaha`
--
ALTER TABLE `piutang_usaha`
  ADD CONSTRAINT `piutang_usaha_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `piutang_usaha_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `produk_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  ADD CONSTRAINT `purchase_order_item_po_id_foreign` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_item_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `retur_pembelian`
--
ALTER TABLE `retur_pembelian`
  ADD CONSTRAINT `retur_pembelian_po_id_foreign` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `retur_pembelian_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `retur_pembelian_item`
--
ALTER TABLE `retur_pembelian_item`
  ADD CONSTRAINT `retur_pembelian_item_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `retur_pembelian_item_retur_id_foreign` FOREIGN KEY (`retur_id`) REFERENCES `retur_pembelian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `retur_penjualan`
--
ALTER TABLE `retur_penjualan`
  ADD CONSTRAINT `retur_penjualan_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `retur_penjualan_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `retur_penjualan_item`
--
ALTER TABLE `retur_penjualan_item`
  ADD CONSTRAINT `retur_penjualan_item_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `retur_penjualan_item_retur_id_foreign` FOREIGN KEY (`retur_id`) REFERENCES `retur_penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD CONSTRAINT `sales_order_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sales_order_item`
--
ALTER TABLE `sales_order_item`
  ADD CONSTRAINT `sales_order_item_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sales_order_item_so_id_foreign` FOREIGN KEY (`so_id`) REFERENCES `sales_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `stok_log`
--
ALTER TABLE `stok_log`
  ADD CONSTRAINT `stok_log_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `utang_usaha`
--
ALTER TABLE `utang_usaha`
  ADD CONSTRAINT `utang_usaha_po_id_foreign` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `utang_usaha_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
