<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Adminto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<style>*{font-family:'Nunito',sans-serif} body{background:#f3f5f9; min-height:100vh; display:flex; align-items:center; justify-content:center} .card{border:none; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,0.08)} .logo{font-weight:800; letter-spacing:1px}</style>
</head>
<body>
<div class="container" style="max-width:460px;">
  <div class="text-center mb-4">
    <h3 class="logo"><i class="ri-dashboard-line text-primary"></i> ADMINTO</h3>
    <p class="text-muted small">CodeIgniter 3 • Port 3307 • PHP 8</p>
  </div>
  <div class="card p-4">
    <h5 class="fw-bold mb-1">Selamat Datang</h5>
    <p class="text-muted small mb-4">Login dengan Email atau NIK</p>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger small py-2"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success small py-2"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post" action="<?= base_url('login') ?>">
      <div class="mb-3">
        <label class="form-label small fw-semibold">Email / NIK</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="ri-user-line"></i></span>
          <input type="text" name="identity" class="form-control" placeholder="admin@adminto.com atau 1234567890123456" value="<?= set_value('identity') ?>" required>
        </div>
        <small class="text-muted" style="font-size:11px;">Contoh: admin@adminto.com / 1234567890123456 (pass: admin123)</small>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="ri-lock-line"></i></span>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="ri-login-box-line me-1"></i> Login</button>
    </form>
    <div class="text-center mt-3">
      <small class="text-muted">Belum punya akun? <a href="<?= base_url('akun/register') ?>">Daftar Akun</a></small><br>
      <small class="text-muted">Akun nonaktif (status 0) tidak bisa login. Coba: nonaktif@adminto.com</small>
    </div>
  </div>
  <p class="text-center small text-muted mt-3">Port 3307 • CI 3.1.13 • PHP <?= PHP_VERSION ?></p>
</div>
</body>
</html>
