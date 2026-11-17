<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?= htmlspecialchars($title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root { --ocean: #0f4c75; --ocean-2: #1b6ca8; }
  * { font-family: 'Nunito', sans-serif; }
  body { background: #f1f5f9; color: #23313d; }
  h1, h2, h3, h4, h5, .brand { font-family: 'Poppins', sans-serif; }
  .topbar { background: linear-gradient(120deg, #0f4c75 0%, #1b6ca8 100%); color: #fff; }
  .stat-card { border: 0; border-radius: 16px; box-shadow: 0 8px 22px rgba(15,76,117,.08); }
  .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
  .table-card { border: 0; border-radius: 16px; box-shadow: 0 10px 28px rgba(15,76,117,.08); overflow: hidden; }
  .table thead th { background: #f8fafc; font-size: 12px; letter-spacing: .03em; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
  .table td { vertical-align: middle; }
  .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12.5px; }
  .ua-cell { max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 11.5px; color: #64748b; }
  .ip-badge { background: #e0f2fe; color: #0369a1; font-weight: 700; padding: 4px 8px; border-radius: 8px; }
  .empty-state { padding: 60px 20px; text-align: center; color: #94a3b8; }
</style>
</head>
<body>

<header class="topbar py-4 mb-4">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill me-2"></i>Selection Result</h3>
        <div class="opacity-75 small">Lokasi &amp; IP pengunjung halaman Seafood Restaurant</div>
      </div>
      <div class="d-flex gap-2">
        <a href="<?= site_url('seafood-restaurant') ?>" class="btn btn-outline-light btn-sm rounded-pill" target="_blank" rel="noopener">
          <i class="bi bi-box-arrow-up-right me-1"></i>Buka Halaman
        </a>
        <button type="button" class="btn btn-light btn-sm rounded-pill fw-semibold" onclick="location.reload()">
          <i class="bi bi-arrow-clockwise me-1"></i>Refresh
        </button>
      </div>
    </div>
  </div>
</header>

<main class="container pb-5">

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($this->session->flashdata('success')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($this->session->flashdata('error')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
          <div>
            <div class="text-muted small">Total Kunjungan</div>
            <div class="h4 fw-bold mb-0"><?= (int) $total ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-4">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-calendar-check"></i></div>
          <div>
            <div class="text-muted small">Hari Ini</div>
            <div class="h4 fw-bold mb-0"><?= (int) $total_today ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-router"></i></div>
          <div>
            <div class="text-muted small">IP Unik</div>
            <div class="h4 fw-bold mb-0"><?= (int) $unique_ip ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card table-card">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div>
        <h6 class="fw-bold mb-0">Data Lokasi Pengunjung</h6>
        <small class="text-muted">Klik tombol <b>Maps</b> untuk membuka titik lokasi di Google Maps.</small>
      </div>
      <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="form-check form-switch mb-0">
          <input class="form-check-input" type="checkbox" role="switch" id="autoRefresh">
          <label class="form-check-label small text-muted" for="autoRefresh">Auto refresh 30 detik</label>
        </div>
        <?php if ((int) $total > 0): ?>
          <form method="post" action="<?= site_url('seafood-restaurant-result/delete_all') ?>" class="m-0" id="deleteAllForm">
            <button type="button" class="btn btn-sm btn-danger rounded-pill fw-semibold" id="deleteAllBtn">
              <i class="bi bi-trash3 me-1"></i>Hapus Semua
            </button>
          </form>
        <?php else: ?>
          <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" disabled>
            <i class="bi bi-trash3 me-1"></i>Hapus Semua
          </button>
        <?php endif; ?>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <div class="fs-1 mb-2">🛰️</div>
        <h6 class="fw-bold mb-1">Belum ada data lokasi</h6>
        <p class="small mb-0">Buka halaman <a href="<?= site_url('seafood-restaurant') ?>" target="_blank" rel="noopener">Seafood Restaurant</a> lalu izinkan akses lokasi di browser.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width:60px;">#</th>
              <th>Waktu</th>
              <th>IP Address</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Akurasi</th>
              <th>Perangkat</th>
              <th class="text-center" style="width:200px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = (($page - 1) * $limit) + 1; foreach ($rows as $r): ?>
              <?php
                $lat = $r->latitude !== null ? (float) $r->latitude : null;
                $lng = $r->longitude !== null ? (float) $r->longitude : null;
                $maps = ($lat !== null && $lng !== null)
                    ? 'https://www.google.com/maps?q='.rawurlencode($lat.','.$lng)
                    : null;
                $ua = (string) $r->user_agent;
              ?>
              <tr>
                <td class="text-muted"><?= $no++ ?></td>
                <td class="text-nowrap small"><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($r->created_at))) ?></td>
                <td><span class="ip-badge mono"><?= htmlspecialchars($r->ip_address ?: '-') ?></span></td>
                <td class="mono"><?= $lat !== null ? number_format($lat, 7, '.', '') : '-' ?></td>
                <td class="mono"><?= $lng !== null ? number_format($lng, 7, '.', '') : '-' ?></td>
                <td class="small text-muted"><?= $r->accuracy !== null ? number_format((float) $r->accuracy, 0, ',', '.').' m' : '-' ?></td>
                <td><div class="ua-cell" title="<?= htmlspecialchars($ua) ?>"><?= htmlspecialchars($ua ?: '-') ?></div></td>
                <td class="text-center">
                  <div class="d-inline-flex align-items-center justify-content-center gap-1">
                    <?php if ($maps): ?>
                      <a href="<?= $maps ?>" target="_blank" rel="noopener" class="btn btn-sm btn-primary rounded-pill">
                        <i class="bi bi-geo-alt-fill me-1"></i>Maps
                      </a>
                    <?php endif; ?>
                    <form method="post" action="<?= site_url('seafood-restaurant-result/delete/'.$r->id) ?>" class="d-inline m-0"
                          onsubmit="return confirm('Hapus data lokasi ini?');">
                      <input type="hidden" name="page" value="<?= (int) $page ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus data">
                        <i class="bi bi-trash me-1"></i>Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total_page > 1): ?>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
          <small class="text-muted">Halaman <?= (int) $page ?> dari <?= (int) $total_page ?> — total <?= (int) $total ?> data</small>
          <nav>
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= site_url('seafood-restaurant-result?page='.($page - 1)) ?>">Sebelumnya</a>
              </li>
              <?php
                $start = max(1, $page - 2);
                $end   = min($total_page, $page + 2);
                for ($i = $start; $i <= $end; $i++):
              ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                  <a class="page-link" href="<?= site_url('seafood-restaurant-result?page='.$i) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= site_url('seafood-restaurant-result?page='.($page + 1)) ?>">Berikutnya</a>
              </li>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <div class="alert alert-warning d-flex gap-2 align-items-start mt-4 mb-0 small" role="alert">
    <i class="bi bi-shield-exclamation fs-5"></i>
    <div>
      <b>Catatan privasi:</b> halaman ini menampilkan IP dan koordinat pengunjung tanpa login.
      Sebelum dipakai di production, lindungi halaman ini (mis. autentikasi/token rahasia) dan cantumkan kebijakan privasi
      pada halaman yang meminta izin lokasi.
    </div>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
  var toggle = document.getElementById('autoRefresh');
  if (toggle) {
    var timer = null;
    toggle.addEventListener('change', function () {
      if (this.checked) {
        timer = setInterval(function () { window.location.reload(); }, 30000);
      } else if (timer) {
        clearInterval(timer);
        timer = null;
      }
    });
  }

  // Konfirmasi hapus semua data
  var TOTAL = <?= (int) $total ?>;
  var btn = document.getElementById('deleteAllBtn');
  var form = document.getElementById('deleteAllForm');
  if (btn && form) {
    btn.addEventListener('click', function () {
      var ok = window.confirm(
        'Hapus SEMUA data lokasi pengunjung?\n\n' +
        'Total ' + TOTAL + ' data akan dihapus permanen dan tidak bisa dikembalikan.'
      );
      if (ok) form.submit();
    });
  }
})();
</script>

</body>
</html>
