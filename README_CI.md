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
- `GET /profile` → edit profil sendiri (nama, nik, email, password opsional)

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
- `assets/css/style.css`, `assets/js/main.js` tetap dipakai (scroll horizontal, submenu klik, dll)

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

