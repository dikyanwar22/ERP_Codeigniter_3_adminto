-- Adminto DB - CodeIgniter 3 - Port 3307
CREATE DATABASE IF NOT EXISTS adminto_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE adminto_db;

DROP TABLE IF EXISTS ci_akses;
DROP TABLE IF EXISTS ci_modul;
DROP TABLE IF EXISTS ci_users;
DROP TABLE IF EXISTS ci_jabatan;

-- Jabatan
CREATE TABLE ci_jabatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_jabatan VARCHAR(50) NOT NULL UNIQUE,
  keterangan TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO ci_jabatan (nama_jabatan, keterangan) VALUES
('Administrator','Akses penuh semua modul'),
('Manager','Akses manajemen penjualan & laporan'),
('Staff','Akses operasional harian'),
('HRD','Akses data karyawan & penggajian');

-- Users (akun)
CREATE TABLE ci_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jabatan_id INT NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=nonaktif tidak bisa login, 1=aktif',
  foto VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (jabatan_id) REFERENCES ci_jabatan(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Password: admin123 (bcrypt)
INSERT INTO ci_users (nik, email, password, nama, jabatan_id, status) VALUES
('1234567890123456','admin@adminto.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Dicky Anwar',1,1),
('1234567890123457','manager@adminto.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Manager User',2,1),
('1234567890123458','staff@adminto.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Staff User',3,1),
('1234567890123459','nonaktif@adminto.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','User Nonaktif',3,0);

-- Modul (hirarki 4 level)
CREATE TABLE ci_modul (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_modul VARCHAR(100) NOT NULL,
  icon VARCHAR(50) DEFAULT 'ri-apps-2-line',
  url VARCHAR(255) DEFAULT NULL,
  parent_id INT NOT NULL DEFAULT 0 COMMENT '0=top modul',
  level TINYINT NOT NULL DEFAULT 1 COMMENT '1=modul 2=menu 3=sub 4=sub-sub',
  urutan INT NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=hide 1=show',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed modul top level (level 1)
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Dashboard','ri-dashboard-line','dashboard',0,1,1,1),
('Master Data','ri-apps-2-line',NULL,0,1,2,1),
('Penjualan','ri-shopping-cart-line',NULL,0,1,3,1),
('Pembelian','ri-shopping-bag-3-line',NULL,0,1,4,1),
('Inventory','ri-archive-line','inventory',0,1,5,1),
('Keuangan','ri-money-dollar-circle-line',NULL,0,1,6,1),
('HRD','ri-team-line',NULL,0,1,7,1),
('Laporan','ri-bar-chart-box-line','laporan',0,1,8,1),
('Pengaturan','ri-tools-line',NULL,0,1,9,1);

-- Level 2 under Master Data (parent 2)
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Produk','ri-box-3-line',NULL,2,2,1,1),
('Supplier','ri-team-line','master/supplier',2,2,2,1),
('Pelanggan','ri-user-3-line',NULL,2,2,3,1),
('Pengaturan Master','ri-settings-3-line','master/pengaturan',2,2,4,1);

-- Level 3 under Produk
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Daftar Produk','ri-list-check','master/produk',10,3,1,1),
('Tambah Produk','ri-add-line','master/produk/tambah',10,3,2,1),
('Import Produk','ri-upload-2-line','master/produk/import',10,3,3,0),
('Kategori','ri-price-tag-3-line',NULL,10,3,4,1);

-- Level 4 under Kategori (id of Kategori = last inserted, need to get id)
-- Kategori id = 17 (since 10+4+? let's check auto increment: after 9 top, 10-13 are 4 rows, then 14-17 are 4 rows, so Kategori is 17)
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('List Kategori','ri-list-radio','master/kategori',17,4,1,1),
('Tambah Kategori','ri-add-box-line','master/kategori/tambah',17,4,2,1),
('Trash Kategori','ri-delete-bin-line','master/kategori/trash',17,4,3,0);

-- Pelanggan submenu (parent 12)
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Daftar Pelanggan','ri-contacts-line','master/pelanggan',12,3,1,1),
('Tambah Pelanggan','ri-user-add-line','master/pelanggan/tambah',12,3,2,1),
('Group Pelanggan','ri-group-line','master/pelanggan/group',12,3,3,1);

-- Penjualan level2 (parent 3)
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('POS Kasir','ri-store-2-line','penjualan/pos',3,2,1,1),
('Daftar Pesanan','ri-file-list-3-line',NULL,3,2,2,1),
('Invoice','ri-bill-line',NULL,3,2,3,1),
('Retur Penjualan','ri-refund-2-line','penjualan/retur',3,2,4,1);

INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Semua Pesanan','ri-list-check','penjualan/pesanan',24,3,1,1),
('Pending','ri-time-line','penjualan/pesanan/pending',24,3,2,1),
('Selesai','ri-check-line','penjualan/pesanan/selesai',24,3,3,1),
('Dibatalkan','ri-close-line','penjualan/pesanan/batal',24,3,4,1);

INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Buat Invoice','ri-add-line','penjualan/invoice/buat',25,3,1,1),
('Daftar Invoice','ri-bill-line','penjualan/invoice',25,3,2,1),
('Invoice Terhutang','ri-error-warning-line','penjualan/invoice/hutang',25,3,3,1);

-- Keuangan
INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Jurnal Umum','ri-book-2-line','keuangan/jurnal',6,2,1,1),
('Kas & Bank','ri-exchange-dollar-line',NULL,6,2,2,1),
('Neraca','ri-scales-3-line','keuangan/neraca',6,2,3,1),
('Laba Rugi','ri-bar-chart-line','keuangan/labarugi',6,2,4,1);

INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Mutasi Kas','ri-exchange-line','keuangan/kas/mutasi',33,3,1,1),
('Transfer Antar Kas','ri-transfer-line','keuangan/kas/transfer',33,3,2,1),
('Laporan Kas','ri-file-chart-line',NULL,33,3,3,1);

INSERT INTO ci_modul (nama_modul, icon, url, parent_id, level, urutan, status) VALUES
('Harian','ri-calendar-line','keuangan/kas/laporan/harian',36,4,1,1),
('Bulanan','ri-calendar-check-line','keuangan/kas/laporan/bulanan',36,4,2,1),
('Tahunan','ri-calendar-event-line','keuangan/kas/laporan/tahunan',36,4,3,1);

-- Akses (many-to-many jabatan <-> modul)
CREATE TABLE ci_akses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jabatan_id INT NOT NULL,
  modul_id INT NOT NULL,
  UNIQUE KEY uniq_jabatan_modul (jabatan_id, modul_id),
  FOREIGN KEY (jabatan_id) REFERENCES ci_jabatan(id) ON DELETE CASCADE,
  FOREIGN KEY (modul_id) REFERENCES ci_modul(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Administrator akses semua (show only)
INSERT INTO ci_akses (jabatan_id, modul_id) SELECT 1, id FROM ci_modul WHERE status=1;

-- Manager akses terbatas
INSERT INTO ci_akses (jabatan_id, modul_id) VALUES
(2,1),(2,2),(2,3),(2,5),(2,6),(2,8),(2,10),(2,14),(2,15),(2,23),(2,24),(2,28),(2,29),(2,30),(2,31),(2,32),(2,33),(2,34),(2,35);

-- Staff
INSERT INTO ci_akses (jabatan_id, modul_id) VALUES
(3,1),(3,2),(3,10),(3,14),(3,23),(3,24),(3,28);

-- HRD
INSERT INTO ci_akses (jabatan_id, modul_id) VALUES
(4,1),(4,7),(4,8);

