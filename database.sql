-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for adminto_db
CREATE DATABASE IF NOT EXISTS `adminto_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `adminto_db`;

-- Dumping structure for table adminto_db.ci_akses
CREATE TABLE IF NOT EXISTS `ci_akses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jabatan_id` int NOT NULL,
  `modul_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_jabatan_modul` (`jabatan_id`,`modul_id`),
  KEY `modul_id` (`modul_id`),
  CONSTRAINT `ci_akses_ibfk_1` FOREIGN KEY (`jabatan_id`) REFERENCES `ci_jabatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ci_akses_ibfk_2` FOREIGN KEY (`modul_id`) REFERENCES `ci_modul` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=598 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adminto_db.ci_akses: ~71 rows (approximately)
INSERT INTO `ci_akses` (`id`, `jabatan_id`, `modul_id`) VALUES
	(550, 1, 1),
	(551, 1, 2),
	(564, 1, 3),
	(579, 1, 4),
	(585, 1, 5),
	(586, 1, 6),
	(594, 1, 7),
	(595, 1, 8),
	(596, 1, 9),
	(552, 1, 10),
	(558, 1, 11),
	(559, 1, 12),
	(563, 1, 13),
	(553, 1, 14),
	(554, 1, 15),
	(555, 1, 17),
	(556, 1, 18),
	(557, 1, 19),
	(560, 1, 21),
	(561, 1, 22),
	(562, 1, 23),
	(565, 1, 24),
	(570, 1, 25),
	(577, 1, 26),
	(578, 1, 27),
	(566, 1, 28),
	(567, 1, 29),
	(568, 1, 30),
	(569, 1, 31),
	(571, 1, 32),
	(572, 1, 33),
	(576, 1, 34),
	(587, 1, 35),
	(588, 1, 36),
	(592, 1, 37),
	(593, 1, 38),
	(573, 1, 39),
	(574, 1, 40),
	(575, 1, 41),
	(589, 1, 42),
	(590, 1, 43),
	(591, 1, 44),
	(580, 1, 55),
	(581, 1, 56),
	(582, 1, 57),
	(597, 1, 64),
	(583, 1, 65),
	(584, 1, 66),
	(64, 2, 1),
	(65, 2, 2),
	(66, 2, 3),
	(67, 2, 5),
	(68, 2, 6),
	(69, 2, 8),
	(70, 2, 10),
	(71, 2, 14),
	(72, 2, 15),
	(73, 2, 23),
	(74, 2, 24),
	(75, 2, 28),
	(76, 2, 29),
	(77, 2, 30),
	(78, 2, 31),
	(79, 2, 32),
	(80, 2, 33),
	(81, 2, 34),
	(82, 2, 35),
	(495, 2, 55),
	(497, 2, 56),
	(499, 2, 57),
	(83, 3, 1),
	(84, 3, 2),
	(85, 3, 10),
	(86, 3, 14),
	(87, 3, 23),
	(88, 3, 24),
	(89, 3, 28),
	(90, 4, 1),
	(91, 4, 7),
	(92, 4, 8);

-- Dumping structure for table adminto_db.ci_jabatan
CREATE TABLE IF NOT EXISTS `ci_jabatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_jabatan` (`nama_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adminto_db.ci_jabatan: ~4 rows (approximately)
INSERT INTO `ci_jabatan` (`id`, `nama_jabatan`, `keterangan`, `created_at`) VALUES
	(1, 'Administrator', 'Akses penuh semua modul', '2026-09-21 12:59:12'),
	(2, 'Manager', 'Akses manajemen penjualan & laporan', '2026-09-21 12:59:12'),
	(3, 'Staff', 'Akses operasional harian', '2026-09-21 12:59:12'),
	(4, 'HRD', 'Akses data karyawan & penggajian', '2026-09-21 12:59:12');

-- Dumping structure for table adminto_db.ci_modul
CREATE TABLE IF NOT EXISTS `ci_modul` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'ri-apps-2-line',
  `url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '0=top modul',
  `level` tinyint NOT NULL DEFAULT '1' COMMENT '1=modul 2=menu 3=sub 4=sub-sub',
  `urutan` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=hide 1=show',
  `tipe` enum('tunggal','dropdown') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tunggal',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adminto_db.ci_modul: ~44 rows (approximately)
INSERT INTO `ci_modul` (`id`, `nama_modul`, `icon`, `url`, `parent_id`, `level`, `urutan`, `status`, `tipe`, `created_at`) VALUES
	(1, 'Dashboard', 'ri-dashboard-line', 'dashboard', 0, 1, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(2, 'Master Data', 'ri-apps-2-line', NULL, 0, 1, 2, 1, 'dropdown', '2026-09-21 12:59:12'),
	(3, 'Penjualan', 'ri-shopping-cart-line', NULL, 0, 1, 3, 1, 'dropdown', '2026-09-21 12:59:12'),
	(4, 'Pembelian', 'ri-shopping-bag-3-line', NULL, 0, 1, 4, 1, 'dropdown', '2026-09-21 12:59:12'),
	(5, 'Inventory', 'ri-archive-line', 'inventory', 0, 1, 5, 1, 'tunggal', '2026-09-21 12:59:12'),
	(6, 'Keuangan', 'ri-money-dollar-circle-line', NULL, 0, 1, 6, 1, 'dropdown', '2026-09-21 12:59:12'),
	(7, 'HRD', 'ri-team-line', NULL, 0, 1, 7, 1, 'dropdown', '2026-09-21 12:59:12'),
	(8, 'Laporan', 'ri-bar-chart-box-line', 'laporan', 0, 1, 8, 1, 'tunggal', '2026-09-21 12:59:12'),
	(9, 'Pengaturan', 'ri-tools-line', NULL, 0, 1, 9, 1, 'dropdown', '2026-09-21 12:59:12'),
	(10, 'Produk', 'ri-box-3-line', NULL, 2, 2, 1, 1, 'dropdown', '2026-09-21 12:59:12'),
	(11, 'Supplier', 'ri-team-line', 'master/supplier', 2, 2, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(12, 'Pelanggan', 'ri-user-3-line', NULL, 2, 2, 3, 1, 'dropdown', '2026-09-21 12:59:12'),
	(13, 'Pengaturan Master', 'ri-settings-3-line', 'master/pengaturan', 2, 2, 4, 1, 'tunggal', '2026-09-21 12:59:12'),
	(14, 'Daftar Produk', 'ri-list-check', 'master/produk', 10, 3, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(15, 'Tambah Produk', 'ri-add-line', 'master/produk/tambah', 10, 3, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(16, 'Import Produk', 'ri-upload-2-line', 'master/produk/import', 10, 3, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(17, 'Kategori', 'ri-price-tag-3-line', NULL, 10, 3, 4, 1, 'dropdown', '2026-09-21 12:59:12'),
	(18, 'List Kategori', 'ri-list-radio', 'master/kategori', 17, 4, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(19, 'Tambah Kategori', 'ri-add-box-line', 'master/kategori/tambah', 17, 4, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(20, 'Trash Kategori', 'ri-delete-bin-line', 'master/kategori/trash', 17, 4, 3, 0, 'tunggal', '2026-09-21 12:59:12'),
	(21, 'Daftar Pelanggan', 'ri-contacts-line', 'master/pelanggan', 12, 3, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(22, 'Tambah Pelanggan', 'ri-user-add-line', 'master/pelanggan/tambah', 12, 3, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(23, 'Group Pelanggan', 'ri-group-line', 'master/pelanggan/group', 12, 3, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(24, 'POS Kasir', 'ri-store-2-line', 'penjualan/pos', 3, 2, 1, 1, 'dropdown', '2026-09-21 12:59:12'),
	(25, 'Daftar Pesanan', 'ri-file-list-3-line', NULL, 3, 2, 2, 1, 'dropdown', '2026-09-21 12:59:12'),
	(26, 'Invoice', 'ri-bill-line', NULL, 3, 2, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(27, 'Retur Penjualan', 'ri-refund-2-line', 'penjualan/retur', 3, 2, 4, 1, 'tunggal', '2026-09-21 12:59:12'),
	(28, 'Semua Pesanan', 'ri-list-check', 'penjualan/pesanan', 24, 3, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(29, 'Pending', 'ri-time-line', 'penjualan/pesanan/pending', 24, 3, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(30, 'Selesai', 'ri-check-line', 'penjualan/pesanan/selesai', 24, 3, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(31, 'Dibatalkan', 'ri-close-line', 'penjualan/pesanan/batal', 24, 3, 4, 1, 'tunggal', '2026-09-21 12:59:12'),
	(32, 'Buat Invoice', 'ri-add-line', 'penjualan/invoice/buat', 25, 3, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(33, 'Daftar Invoice', 'ri-bill-line', 'penjualan/invoice', 25, 3, 2, 1, 'dropdown', '2026-09-21 12:59:12'),
	(34, 'Invoice Terhutang', 'ri-error-warning-line', 'penjualan/invoice/hutang', 25, 3, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(35, 'Jurnal Umum', 'ri-book-2-line', 'keuangan/jurnal', 6, 2, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(36, 'Kas & Bank', 'ri-exchange-dollar-line', NULL, 6, 2, 2, 1, 'dropdown', '2026-09-21 12:59:12'),
	(37, 'Neraca', 'ri-scales-3-line', 'keuangan/neraca', 6, 2, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(38, 'Laba Rugi', 'ri-bar-chart-line', 'keuangan/labarugi', 6, 2, 4, 1, 'tunggal', '2026-09-21 12:59:12'),
	(39, 'Mutasi Kas', 'ri-exchange-line', 'keuangan/kas/mutasi', 33, 3, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(40, 'Transfer Antar Kas', 'ri-transfer-line', 'keuangan/kas/transfer', 33, 3, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(41, 'Laporan Kas', 'ri-file-chart-line', NULL, 33, 3, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(42, 'Harian', 'ri-calendar-line', 'keuangan/kas/laporan/harian', 36, 4, 1, 1, 'tunggal', '2026-09-21 12:59:12'),
	(43, 'Bulanan', 'ri-calendar-check-line', 'keuangan/kas/laporan/bulanan', 36, 4, 2, 1, 'tunggal', '2026-09-21 12:59:12'),
	(44, 'Tahunan', 'ri-calendar-event-line', 'keuangan/kas/laporan/tahunan', 36, 4, 3, 1, 'tunggal', '2026-09-21 12:59:12'),
	(55, 'Purchase Order', 'ri-shopping-basket-line', 'pembelian', 4, 2, 1, 1, 'tunggal', '2026-09-21 13:49:15'),
	(56, 'Penerimaan Barang', 'ri-truck-line', 'pembelian/penerimaan', 4, 2, 2, 1, 'tunggal', '2026-09-21 13:49:15'),
	(57, 'Retur Pembelian', 'ri-refund-line', NULL, 4, 2, 3, 1, 'dropdown', '2026-09-21 13:49:15'),
	(64, 'Kelola Modul', 'ri-apps-2-line', 'modul', 9, 2, 1, 1, 'tunggal', '2026-09-21 14:16:18'),
	(65, 'menu 1', 'ri-apps-2-line', 'contoh', 57, 3, 1, 1, 'tunggal', '2026-09-21 14:53:22'),
	(66, 'lap 1', 'ri-apps-2-line', 'lap1', 57, 3, 2, 1, 'tunggal', '2026-09-21 15:26:36'),
	(67, 'laporan', 'ri-apps-2-line', 'pembelian/laporan', 4, 2, 0, 1, 'tunggal', '2026-09-21 15:39:18'),
	(68, 'laporan mingguan', 'ri-apps-2-line', 'keuangan/laporan_mingguan', 6, 2, 3, 1, 'tunggal', '2026-09-21 15:39:45');

-- Dumping structure for table adminto_db.ci_pembelian
CREATE TABLE IF NOT EXISTS `ci_pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_po` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `supplier` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','proses','selesai','batal') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_po` (`kode_po`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `ci_pembelian_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `ci_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adminto_db.ci_pembelian: ~3 rows (approximately)
INSERT INTO `ci_pembelian` (`id`, `kode_po`, `supplier`, `tanggal`, `total`, `status`, `keterangan`, `created_by`, `created_at`) VALUES
	(1, 'PO-2026-001', 'PT Sumber Makmur', '2026-09-15', 12500000.00, 'proses', 'Pembelian bahan baku', 1, '2026-09-21 13:47:45'),
	(2, 'PO-2026-002', 'CV Jaya Abadi', '2026-09-16', 8700000.00, 'draft', 'Pesanan rutin', 1, '2026-09-21 13:47:45'),
	(3, 'PO-2026-003', 'PT Global Niaga', '2026-09-18', 23000000.00, 'selesai', 'Sudah diterima', 1, '2026-09-21 13:47:45');

-- Dumping structure for table adminto_db.ci_users
CREATE TABLE IF NOT EXISTS `ci_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan_id` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=nonaktif tidak bisa login, 1=aktif',
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `email` (`email`),
  KEY `jabatan_id` (`jabatan_id`),
  CONSTRAINT `ci_users_ibfk_1` FOREIGN KEY (`jabatan_id`) REFERENCES `ci_jabatan` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adminto_db.ci_users: ~4 rows (approximately)
INSERT INTO `ci_users` (`id`, `nik`, `email`, `password`, `nama`, `jabatan_id`, `status`, `foto`, `created_at`, `updated_at`) VALUES
	(1, '1234567890123456', 'admin@adminto.com', '$2y$10$ZNUB9DqjIfxDUDFhOdX9eOAUNnGefU9oz.1Tcujf4bxnzMUdhP2bq', 'Dicky Anwar', 1, 1, NULL, '2026-09-21 12:59:12', '2026-09-21 09:30:51'),
	(2, '1234567890123457', 'manager@adminto.com', '$2y$10$NH0fS1Cybohb8f/2PuSYdepXI0oIWGnmKY4h5IFmwdoYwahUSgFvO', 'Manager User', 2, 1, NULL, '2026-09-21 12:59:12', '2026-09-21 14:20:56'),
	(3, '1234567890123458', 'staff@adminto.com', '$2y$10$NH0fS1Cybohb8f/2PuSYdepXI0oIWGnmKY4h5IFmwdoYwahUSgFvO', 'Staff User', 3, 1, NULL, '2026-09-21 12:59:12', '2026-09-21 14:20:57'),
	(4, '1234567890123459', 'nonaktif@adminto.com', '$2y$10$NH0fS1Cybohb8f/2PuSYdepXI0oIWGnmKY4h5IFmwdoYwahUSgFvO', 'User Nonaktif', 3, 0, NULL, '2026-09-21 12:59:12', '2026-09-21 14:20:58');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
