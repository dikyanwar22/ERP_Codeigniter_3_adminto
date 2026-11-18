<!DOCTYPE html>
<html lang="id" class="geo-locked">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> — Ocean Catch</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<style>
  :root { --ocean: #0f4c75; --ocean-2: #1b6ca8; --sand: #f7f3ea; --coral: #ff6b6b; }
  * { font-family: 'Nunito', sans-serif; }
  body { background: var(--sand); color: #23313d; }
  h1, h2, h3, h4, .brand { font-family: 'Poppins', sans-serif; }
  .hero {
    background: radial-gradient(circle at 20% 20%, #2c8fbf 0%, #0f4c75 45%, #082b43 100%);
    color: #fff; position: relative; overflow: hidden; padding: 72px 0 96px;
  }
  .hero::before, .hero::after {
    content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,.06);
  }
  .hero::before { width: 420px; height: 420px; top: -180px; right: -120px; }
  .hero::after { width: 300px; height: 300px; bottom: -160px; left: -80px; }
  .hero .badge-loc { background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28); }
  .hero .emoji { font-size: 64px; filter: drop-shadow(0 12px 18px rgba(0,0,0,.25)); }
  .wave { position: absolute; bottom: -1px; left: 0; width: 100%; height: 60px; }
  .menu-card {
    border: 0; border-radius: 20px; overflow: hidden; height: 100%;
    box-shadow: 0 12px 30px rgba(15,76,117,.09); transition: transform .2s ease, box-shadow .2s ease;
    background: #fff;
  }
  .menu-card:hover { transform: translateY(-5px); box-shadow: 0 18px 38px rgba(15,76,117,.16); }
  .menu-thumb {
    position: relative; width: 100%; aspect-ratio: 4 / 3; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    font-size: 64px; background: linear-gradient(135deg, #e8f4fb 0%, #cfe9f7 100%);
  }
  .menu-thumb img {
    display: block; width: 100%; height: 100%; max-width: 100%;
    object-fit: cover; object-position: center;
  }
  @media (max-width: 575.98px) {
    .menu-thumb { aspect-ratio: 16 / 10; }
  }
  .menu-card .price { color: var(--ocean-2); font-weight: 800; }
  .tag-pill { background: #ffe9e9; color: #d64545; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; }
  .section-title::after {
    content: ''; display: block; width: 64px; height: 4px; border-radius: 4px;
    background: var(--coral); margin-top: 10px;
  }
  .info-card { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15,76,117,.08); }
  footer.site-footer { background: #082b43; color: #a9c6da; }

  html.geo-locked, html.geo-locked body { overflow: hidden !important; height: 100% !important; }
  html.geo-locked body > *:not(#geoBlocker):not(.fab-stack) { display: none !important; }
  #geoBlocker {
    position: fixed; inset: 0; z-index: 2147483000; display: flex;
    align-items: center; justify-content: center; padding: 24px; text-align: center;
    background: radial-gradient(circle at 20% 20%, #2c8fbf 0%, #0f4c75 45%, #082b43 100%);
    color: #fff;
  }
  html:not(.geo-locked) #geoBlocker { display: none !important; }
  #geoBlocker .lock-icon {
    width: 84px; height: 84px; margin: 0 auto 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; font-size: 38px;
    background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28);
  }
  #geoBlocker h1 { font-size: 22px; font-weight: 800; margin-bottom: 10px; }
  #geoBlocker p { font-size: 14px; line-height: 1.65; color: rgba(255,255,255,.82); margin-bottom: 0; }
  #geoBlocker .hint {
    max-width: 460px; margin: 14px auto 0; font-size: 12px; text-align: left;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
    border-radius: 14px; padding: 12px 14px; color: rgba(255,255,255,.85);
  }
  #geoBlocker .hint b { color: #fff; }
</style>
</head>
<body>

<!-- Gerbang akses: tampil (dan menutupi seluruh halaman) sampai lokasi berhasil diambil -->
<div id="geoBlocker" role="alertdialog" aria-modal="true" aria-labelledby="geoBlockerTitle">
  <div style="max-width:520px;">
    <div class="lock-icon">🔒</div>
    <h1 id="geoBlockerTitle">Akses Ditolak</h1>
    <p>Halaman ini hanya bisa dibuka jika <b>izin lokasi aktif</b>. Saat ini akses lokasi diblokir atau tidak tersedia, sehingga halaman tidak dapat diakses.</p>
    <div class="hint">
      <b>Cara mengaktifkan:</b><br>
      • <b>Laptop/Chrome:</b> klik ikon 🔒 / pengaturan di kiri address bar → <b>Location</b> → <b>Allow</b><br>
      • <b>Android/Chrome:</b> menu ⋮ → <b>Settings</b> → <b>Site settings</b> → <b>Location</b> → izinkan situs ini<br>
      • <b>iPhone/Safari:</b> <b>Settings</b> → <b>Safari</b> → <b>Location</b> → <b>Allow</b><br>
      Setelah diaktifkan, muat ulang halaman ini.
    </div>
    <noscript>
      <div class="hint mt-3"><b>JavaScript nonaktif.</b> Aktifkan JavaScript dan izin lokasi untuk membuka halaman ini.</div>
    </noscript>
    <button type="button" class="btn btn-warning fw-bold rounded-pill px-4 mt-4" onclick="location.reload()">
      <i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang Halaman
    </button>
  </div>
</div>

<header class="hero">
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <span class="badge badge-loc rounded-pill mb-3 px-3 py-2"><i class="bi bi-geo-alt-fill me-1"></i> Fresh from the ocean, daily</span>
        <h1 class="display-5 fw-bold mb-3">Ocean Catch <span class="text-warning">Seafood Restaurant</span></h1>
        <p class="lead mb-4 opacity-75">Hidangan laut segar pilihan, dibakar dan dimasak dengan bumbu khas Nusantara. Cocok untuk makan keluarga maupun santai bersama teman.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="#menu" class="btn btn-warning fw-bold px-4 py-2 rounded-pill"><i class="bi bi-egg-fried me-2"></i>Lihat Menu</a>
          <a href="tel:+6281234567890" class="btn btn-outline-light px-4 py-2 rounded-pill"><i class="bi bi-telephone me-2"></i>Reservasi</a>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <div class="emoji">🦞🦐🦀</div>
      </div>
    </div>
  </div>
  <svg class="wave" viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path fill="#f7f3ea" d="M0,32 C240,72 480,0 720,24 C960,48 1200,64 1440,28 L1440,60 L0,60 Z"></path>
  </svg>
</header>

<main class="container py-5" id="menu">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
    <div>
      <h2 class="fw-bold mb-0 section-title">Menu Andalan</h2>
      <p class="text-muted mb-0 mt-3">Pilih favorit Anda — semua dimasak fresh sesuai pesanan.</p>
    </div>
    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Bahan segar setiap hari</span>
  </div>

  <div class="row g-4">
    <?php foreach ($menu as $m): ?>
      <div class="col-sm-6 col-lg-3">
        <div class="card menu-card">
          <div class="menu-thumb">
            <img src="<?= htmlspecialchars($m['gambar']) ?>"
                 alt="<?= htmlspecialchars($m['nama']) ?>"
                 loading="lazy" decoding="async" referrerpolicy="no-referrer"
                 onerror="this.style.display='none'">
          </div>
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h5 class="fw-bold mb-0" style="font-size:16px;"><?= htmlspecialchars($m['nama']) ?></h5>
              <?php if (!empty($m['tag'])): ?>
                <span class="tag-pill ms-2 text-nowrap"><?= htmlspecialchars($m['tag']) ?></span>
              <?php endif; ?>
            </div>
            <p class="text-muted small flex-grow-1 mt-2"><?= htmlspecialchars($m['desc']) ?></p>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <span class="price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span>
              <span class="text-muted small">/ porsi</span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-4 mt-4">
    <div class="col-md-4">
      <div class="card info-card h-100 p-4">
        <div class="fs-3 mb-2">🕒</div>
        <h6 class="fw-bold">Jam Buka</h6>
        <p class="text-muted small mb-0">Senin – Jumat: 11.00 – 22.00<br>Sabtu – Minggu: 10.00 – 23.00</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card info-card h-100 p-4">
        <div class="fs-3 mb-2">📍</div>
        <h6 class="fw-bold">Lokasi</h6>
        <p class="text-muted small mb-0">Jl Pelabuhan Perikanan, Pegambiran Lemahwungkuk<br>Kota Cirebon, Jawa Barat</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card info-card h-100 p-4">
        <div class="fs-3 mb-2">📞</div>
        <h6 class="fw-bold">Reservasi</h6>
        <p class="text-muted small mb-0">+62 812-3456-7890<br>hello.customer@gmail.com</p>
      </div>
    </div>
  </div>
</main>

<footer class="site-footer py-4">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <div class="brand text-white fw-bold">Ocean Catch</div>
        <small>© <?= date('Y') ?> Ocean Catch Seafood Restaurant.</small>
      </div>
    </div>
  </div>
</footer>

<script>
/**
 * Gerbang akses berbasis izin lokasi.
 * - Halaman di-render dalam kondisi TERKUNCI (html.geo-locked) dan baru dibuka
 *   setelah posisi berhasil didapatkan.
 * - Izin diblokir / lokasi dimatikan / GPS gagal / tidak didukung -> halaman tetap terkunci
 *   (hanya layar "Akses Ditolak" + tombol muat ulang), konten tidak bisa diakses.
 * - Tidak ada status/alert apa pun; pengecekan berjalan otomatis.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var SAVE_URL = <?= json_encode(site_url('seafood-restaurant/save_location'), JSON_UNESCAPED_SLASHES) ?>;
  var TIMEOUT = 10000;      // batas waktu permintaan lokasi (ms)
  var asking = false;

  function lock() {
    root.classList.add('geo-locked');
  }

  function unlock() {
    root.classList.remove('geo-locked');
  }

  function save(lat, lng, acc) {
    try {
      if (!window.fetch || !window.FormData) return;
      var fd = new FormData();
      fd.append('latitude', lat);
      fd.append('longitude', lng);
      fd.append('accuracy', acc == null ? '' : acc);

      fetch(SAVE_URL, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      }).then(function () {}, function () {});
    } catch (e) {
      /* diam saja: kegagalan menyimpan tidak membuka/menutup akses */
    }
  }

  function ask() {
    if (asking) return;
    asking = true;

    if (!navigator.geolocation) {
      lock();
      return;
    }

    try {
      navigator.geolocation.getCurrentPosition(function (pos) {
        asking = false;
        save(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
        unlock();
      }, function () {
        // ditolak / lokasi mati / timeout / tidak tersedia -> tetap terkunci
        asking = false;
        lock();
      }, {
        enableHighAccuracy: true,
        timeout: TIMEOUT,
        maximumAge: 30000
      });
    } catch (e) {
      asking = false;
      lock();
    }
  }

  function start() {
    // Permission API: deteksi blokir permanen tanpa memicu prompt berulang
    if (navigator.permissions && navigator.permissions.query) {
      navigator.permissions.query({ name: 'geolocation' }).then(function (status) {
        if (status.state === 'denied') {
          lock();
          return;
        }
        ask();
        status.onchange = function () {
          if (status.state === 'denied') lock();
        };
      }, function () {
        ask();
      });
    } else {
      ask();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
</script>

<?php $this->load->view('templates/floating_controls', array('show_chat' => false)); ?>
</body>
</html>
