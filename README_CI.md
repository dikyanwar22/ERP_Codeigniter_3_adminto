# Adminto - CodeIgniter 3.1.13 (PHP 8) Port 3307

Integrasi template Adminto Top Menu (Bootstrap 5) dengan CI3.

## Setup
- **CI Version:** 3.1.13 (support PHP 8.2)
- **PHP:** 8.2.12 (tested)
- **DB Port:** 3307 (hostname `127.0.0.1:3307`)
- **Database:** `adminto_db` (import `adminto_db.sql`)

## Database
```bash
mysql -h 127.0.0.1 -P 3307 -u root -e "CREATE DATABASE adminto_db"
mysql -h 127.0.0.1 -P 3307 -u root adminto_db < adminto_db.sql
```
Tables: `ci_jabatan`, `ci_users`, `ci_modul` (4 level), `ci_akses`

## Konfigurasi CI
- `application/config/database.php:77` -> `hostname = 127.0.0.1:3307`, `database = adminto_db`
- `application/config/config.php` -> `base_url` auto, `encryption_key` set, `sess_save_path = APPPATH/cache/sessions`
- `application/config/autoload.php` -> `database, session, form_validation` + helper `url,form,text`
- `application/config/routes.php` -> `default_controller = auth`

## Fitur
### 1. Auth - Login Email/NIK + Status
- `GET /login` → `Auth::login`
- **Bisa login pakai `email` ATAU `nik`** (query `WHERE email=? OR nik=?`)
- Cek `status`: 0=nonaktif → tidak bisa login (flash error), 1=aktif → lanjut `password_verify`
- `GET /logout` → destroy session
- `GET /auth/check_session` → JSON `{logged_in: bool}` (dipakai auto-logout)
- **Auto-logout**: saat session habis, scroll mouse / geser layar memicu pengecekan; jika tidak login → redirect ke `/login` (±1,5 detik)
- `MY_Controller` → cek `logged_in` di session, redirect ke login jika belum

**Akun Demo (pass: `admin123`):**
- `admin@adminto.com` / `1234567890123456` (Administrator, aktif)
- `manager@adminto.com` / `1234567890123457` (Manager, aktif)
- `staff@adminto.com` / `1234567890123458` (Staff, aktif)
- `nonaktif@adminto.com` / `1234567890123459` (Staff, **nonaktif 0** → login ditolak)

### 2. Daftar Akun & Profile
- `GET /akun` → list `ci_users` + jabatan (MY_Controller)
- `GET /akun/create` / `POST` → tambah akun (validasi NIK/Email unique, password hash)
- `GET /akun/register` → publik register tanpa login (status default 1)
- `GET /akun/edit/{id}` → edit, `akun/delete/{id}`
- `GET /profile` → profil sendiri: upload avatar (`assets/public/avatar/`), ubah password (dengan konfirmasi). Nama/NIK/Email readonly.

### 3. CRUD Modul (4 Level) - Hide/Show
- `GET /modul` → tree admin (semua status) + tabel flat
- `GET /modul/create` → form: `nama_modul, icon, url, parent_id, urutan, status`
  - `parent_id=0` → level 1 (Modul top)
  - Jika parent level 1 → level 2 (Menu), dst max 4
  - Validasi circular, max level
- `GET /modul/edit/{id}` → update + recursive `update_children_level`
- `GET /modul/toggle/{id}` → flip status 1↔0
- `GET /modul/delete/{id}` → cek has_child
- **Hanya `status=1` yang muncul di top menu** → `Modul_model::get_menu_for_jabatan` filter `status=1` + join `ci_akses`
- `MY_Controller` juga cek `status==0` → 403

### 4. Akses per Jabatan (Checkbox)
- `GET /akses?jabatan_id=1` → pilih jabatan, tampil tree modul Show saja
- Checkbox `modul_ids[]` per modul (hanya show)
- `POST /akses` → `Akses_model::save_akses` (delete + insert dalam transaksi, hanya status 1)
- `ci_akses` = many-to-many jabatan↔modul

## Menjalankan
**PHP Built-in (untuk test):**
```bash
php -S 127.0.0.1:8081 -t C:\Users\dicky.anwar\Pictures\buku\adminto
# buka http://127.0.0.1:8081/index.php/login
```
**Laragon/XAMPP:**
- Pindahkan folder ke `C:\laragon\www\adminto` atau `C:\xampp\htdocs\adminto`
- Buka `http://localhost/adminto/index.php/login`
- Atau set `base_url` ke `http://localhost/adminto/`

## Template
- `application/views/templates/header.php` → top menu dinamis via `$menus` dari `MY_Controller::render`
- `application/views/templates/footer.php` → bottom bar mobile (BACK REFRESH HOME PROFILE LOGOUT + FAB)
  + memuat `templates/floating_controls.php` dengan `show_chat = true` → **back-to-top + chatbox tampil di semua view sistem**
- `application/views/templates/floating_controls.php` → komponen bersama: tombol back-to-top (muncul
  hanya setelah scroll > 200px, klik → smooth scroll ke atas) dan chatbox "Pesan Masuk"
  (opsional via `$show_chat`). Opsi `$above_bottom_bar` menaikkan posisi di atas mobile bottom bar.
  jQuery chat memakai jQuery yang sudah dimuat di `header.php`.
- `assets/css/style.css`, `assets/js/main.js` tetap dipakai (scroll horizontal, submenu klik, dll)

## Halaman Publik (tanpa login)
### Seafood Restaurant — `GET /seafood-restaurant`
- Landing page menu seafood (`application/views/seafood_restaurant/index.php`)
- Saat halaman dibuka, browser meminta izin lokasi lalu `POST /seafood-restaurant/save_location`
  (`latitude`, `longitude`, `accuracy`) → simpan + `ip_address`, `user_agent`, `page`, `created_at`
- Respons: `201 {"status":true}` / `422` jika koordinat tidak valid / `405` jika bukan POST
- **Gerbang akses (wajib izin lokasi):** halaman di-render dengan `<html class="geo-locked">`, konten
  disembunyikan dan ditutup layar "Akses Ditolak" sampai posisi berhasil didapatkan.
  Izin ditolak / diblokir permanen (`permissions.query` → `denied`), lokasi/GPS dimatikan, timeout 10 detik,
  atau browser tidak mendukung → halaman **tetap terkunci** (hanya tombol muat ulang).
  Berhasil dapat posisi → `geo-locked` dilepas, konten tampil, koordinat dikirim ke `save_location`.
  Tidak ada alert/status ke pengunjung; pengecekan otomatis saat `DOMContentLoaded`.
- **Tombol mengambang (kanan bawah):** back-to-top tetap ada di halaman ini lewat
  `templates/floating_controls.php` (`show_chat = false`), namun hanya muncul setelah halaman
  digulir (> 200px) dan otomatis hilang saat kembali ke atas.
- **Chatbox "Pesan Masuk" tidak lagi ada di halaman publik.** Sekarang dimuat dari
  `templates/footer.php` (via `floating_controls` dengan `show_chat = true`), sehingga tampil di semua
  view sistem yang memakai `MY_Controller::render()`. Fitur: tertutup default, total belum dibaca +
  ikon lonceng, pencarian (filter client-side), avatar + nama user, waktu `date('Y-m-d H:i:s')`,
  isi pesan dipotong `...` (maks 70 karakter), list scroll vertikal,
  klik pesan → tanda belum dibaca hilang & badge berkurang.
- **Data dummy (JSON) — `GET /api/chat-dummy`** → `application/controllers/Chat_dummy.php`
  (tanpa login, tanpa tabel DB). Chatbox memanggil AJAX jQuery ke endpoint ini.

### Seafood Restaurant Result — `GET /seafood-restaurant-result`
- Controller: `application/controllers/Seafood_restaurant_result.php`
- View: `application/views/seafood_restaurant_result/index.php`
- Menampilkan statistik (total, hari ini, IP unik) + tabel IP, latitude, longitude, akurasi, perangkat
- Tombol **Maps** → `https://www.google.com/maps?q=<lat>,<lng>`
- Tombol **Hapus** per row → `POST /seafood-restaurant-result/delete/{id}` (wajib POST, GET hanya redirect)
  → redirect kembali ke halaman (mempertahankan `page`) + flash `success`/`error`
- Tombol **Hapus Semua** → `POST /seafood-restaurant-result/delete_all` (wajib POST, GET hanya redirect)
  → konfirmasi jumlah data, `TRUNCATE` tabel (auto increment reset), flash `success`/`error`
- Pagination 50/halaman, auto-refresh opsional 30 detik

**Catatan penamaan:** URL memakai tanda hubung (`seafood-restaurant-result`), sedangkan file controller/view
memakai garis bawah (`Seafood_restaurant_result.php` / folder `seafood_restaurant_result/`) sesuai konvensi CI3.
Penghubungnya adalah `application/config/routes.php`.

### Tabel `ci_visitor_locations`
`id, ip_address, latitude(10,7), longitude(10,7), accuracy(10,2), user_agent, page, created_at`
→ migrasi: `database_visitor_locations.sql`

**Catatan:** geolocation browser hanya berjalan di HTTPS atau `localhost`. Halaman `/seafood-restaurant-result`
menampilkan IP + koordinat tanpa login — tambahkan proteksi sebelum production.

## Struktur Penting
```
application/core/MY_Controller.php
application/models/User_model.php, Modul_model.php, Akses_model.php, Jabatan_model.php
application/controllers/Auth.php, Dashboard.php, Akun.php, Profile.php, Modul.php, Akses.php
application/views/auth/login.php, auth/register.php, dashboard/index.php, akun/*, modul/*, akses/index.php, profile/index.php, templates/header.php, templates/footer.php
```

## Catatan PHP 8
- `index.php:69` error_reporting di-set `E_ALL & ~E_DEPRECATED` untuk hilangkan `Creation of dynamic property` warning CI3 di PHP 8.2
- Session path `application/cache/sessions` harus writable

