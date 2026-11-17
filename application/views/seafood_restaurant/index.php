<!DOCTYPE html>
<html lang="id">
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
    height: 150px; display: flex; align-items: center; justify-content: center;
    font-size: 64px; background: linear-gradient(135deg, #e8f4fb 0%, #cfe9f7 100%);
  }
  .menu-card .price { color: var(--ocean-2); font-weight: 800; }
  .tag-pill { background: #ffe9e9; color: #d64545; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; }
  .section-title::after {
    content: ''; display: block; width: 64px; height: 4px; border-radius: 4px;
    background: var(--coral); margin-top: 10px;
  }
  .info-card { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15,76,117,.08); }
  footer.site-footer { background: #082b43; color: #a9c6da; }
  .loc-pill { font-size: 12px; border-radius: 999px; padding: 6px 12px; }
</style>
</head>
<body>

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
          <div class="menu-thumb"><img width="200" height="200" src="<?= $m['gambar'] ?>"></div>
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
      <div class="d-flex flex-column align-items-start align-items-md-end gap-2">
        <span id="locStatus" class="loc-pill bg-secondary-subtle text-secondary"><i class="bi bi-geo-alt me-1"></i>Menyiapkan lokasi…</span>
      </div>
    </div>
  </div>
</footer>

<script>
(function () {
  var SAVE_URL = <?= json_encode(site_url('seafood-restaurant/save_location'), JSON_UNESCAPED_SLASHES) ?>;
  var el = document.getElementById('locStatus');

  function setStatus(text, icon, cls) {
    if (!el) return;
    el.className = 'loc-pill ' + cls;
    el.innerHTML = '<i class="bi ' + icon + ' me-1"></i>' + text;
  }

  if (!('geolocation' in navigator)) {
    setStatus('Browser tidak mendukung lokasi', 'bi-geo-alt', 'bg-secondary-subtle text-secondary');
    return;
  }

  setStatus('Meminta izin lokasi…', 'bi-hourglass-split', 'bg-info-subtle text-info');

  navigator.geolocation.getCurrentPosition(function (pos) {
    var fd = new FormData();
    fd.append('latitude', pos.coords.latitude);
    fd.append('longitude', pos.coords.longitude);
    fd.append('accuracy', pos.coords.accuracy || '');

    setStatus('Menyimpan lokasi…', 'bi-arrow-repeat', 'bg-info-subtle text-info');

    fetch(SAVE_URL, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
      if (d && d.status) {
        setStatus('Lokasi kunjungan tercatat', 'bi-check-circle', 'bg-success-subtle text-success');
      } else {
        setStatus('Gagal menyimpan lokasi', 'bi-exclamation-triangle', 'bg-warning-subtle text-warning');
      }
    })
    .catch(function () {
      setStatus('Gagal mengirim lokasi', 'bi-exclamation-triangle', 'bg-warning-subtle text-warning');
    });
  }, function (err) {
    if (err && err.code === 1) {
      setStatus('Izin lokasi ditolak', 'bi-geo-alt', 'bg-secondary-subtle text-secondary');
    } else {
      setStatus('Lokasi tidak tersedia', 'bi-geo-alt', 'bg-secondary-subtle text-secondary');
    }
  }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
})();
</script>

</body>
</html>
