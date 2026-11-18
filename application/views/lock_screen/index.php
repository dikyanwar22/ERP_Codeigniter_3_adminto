<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'Nunito',sans-serif}
body{background:#f1f3f6; min-height:100vh; overflow:hidden;}
.lock-wrapper{min-height:100vh; display:flex;}
.left-panel{
  background: linear-gradient(135deg, #2a3142 0%, #1e2433 50%, #727cf5 100%);
  color:#fff; position:relative; overflow:hidden;
  display:flex; flex-direction:column; justify-content:space-between; padding:48px;
}
.left-panel::before{
  content:''; position:absolute; top:-80px; right:-80px; width:300px; height:300px;
  background: rgba(114,124,245,0.15); border-radius:50%;
}
.left-panel::after{
  content:''; position:absolute; bottom:-60px; left:-60px; width:250px; height:250px;
  background: rgba(255,255,255,0.05); border-radius:50%;
}
.brand{font-family:'Poppins',sans-serif; font-weight:700; letter-spacing:1px; font-size:22px;}
.feature-icon{width:44px;height:44px;background:rgba(255,255,255,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.right-panel{background:#fff; display:flex; align-items:center; justify-content:center; padding:40px;}
.lock-card{width:100%; max-width:420px;}
.avatar-lock{
  width:76px; height:76px; border-radius:50%; object-fit:cover; border:4px solid #fff;
  box-shadow:0 8px 24px rgba(42,49,66,.16);
}
.avatar-fallback{
  width:76px; height:76px; border-radius:50%; display:flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg,#727cf5,#4b55d6); color:#fff; font-size:26px; font-weight:800;
  border:4px solid #fff; box-shadow:0 8px 24px rgba(42,49,66,.16);
}
.lock-badge{
  position:absolute; right:0; bottom:0; width:30px; height:30px; border-radius:50%;
  background:#2a3142; color:#fff; display:flex; align-items:center; justify-content:center;
  border:3px solid #fff; font-size:13px;
}
.form-control{padding:12px 14px; border-radius:10px; border:1px solid #e3e8ef; background:#f8f9fb;}
.form-control:focus{background:#fff; border-color:#727cf5; box-shadow:0 0 0 3px rgba(114,124,245,0.12);}
.input-group-text{background:#f8f9fb; border:1px solid #e3e8ef; border-right:none; border-radius:10px 0 0 10px;}
.input-group .form-control{border-left:none; border-radius:0 10px 10px 0;}
.btn-unlock{background:#727cf5; border:none; border-radius:10px; padding:12px; box-shadow:0 6px 16px rgba(114,124,245,.3);}
.btn-unlock:hover{background:#5a66f0;}
.btn-unlock:disabled{opacity:.7; cursor:wait;}
@media (max-width: 991px){ .left-panel{display:none !important;} }
</style>
</head>
<body>
<div class="lock-wrapper">
  <!-- Left: Branding (sama seperti halaman login) -->
  <div class="col-lg-6 left-panel d-none d-lg-flex">
    <div style="position:relative; z-index:1;">
      <div class="brand mb-5"><i class="ri-dashboard-line me-2"></i>ADMINTO <span class="fw-light opacity-75">ERP</span></div>
      <h1 class="fw-bold mb-3" style="line-height:1.2; font-size:32px;">Sesi Anda<br>Terkunci</h1>
      <p class="opacity-75 mb-5" style="font-size:15px; line-height:1.6;">
        Demi keamanan, seluruh menu dinonaktifkan sementara. Masukkan kata sandi akun Anda untuk melanjutkan.
      </p>
      <div class="d-flex flex-column gap-4">
        <div class="d-flex gap-3 align-items-start">
          <div class="feature-icon"><i class="ri-lock-2-line fs-5"></i></div>
          <div>
            <h6 class="mb-1 fw-bold" style="font-size:14px;">Akses Dibatasi</h6>
            <small class="opacity-75" style="font-size:13px;">Tidak ada menu yang bisa dibuka sebelum dibuka kuncinya</small>
          </div>
        </div>
        <div class="d-flex gap-3 align-items-start">
          <div class="feature-icon"><i class="ri-shield-user-line fs-5"></i></div>
          <div>
            <h6 class="mb-1 fw-bold" style="font-size:14px;">Verifikasi Pemilik Sesi</h6>
            <small class="opacity-75" style="font-size:13px;">Hanya kata sandi akun Anda yang dapat membuka layar ini</small>
          </div>
        </div>
      </div>
    </div>
    <div style="position:relative; z-index:1;" class="mt-auto">
      <div class="d-flex justify-content-between align-items-center mt-4">
        <small class="opacity-50" style="font-size:11px;">© <?= date('Y') ?> ERP SYSTEM</small>
        <small class="opacity-50" style="font-size:11px;">PHP <?= PHP_VERSION ?></small>
      </div>
    </div>
  </div>

  <!-- Right: Form buka kunci -->
  <div class="col-lg-6 right-panel">
    <div class="lock-card">
      <div class="text-center d-lg-none mb-4">
        <h3 class="fw-bold mb-0" style="font-family:'Poppins',sans-serif;"><i class="ri-dashboard-line text-primary"></i> ADMINTO <span class="fw-light">ERP</span></h3>
      </div>

      <div class="text-center mb-4">
        <div class="position-relative d-inline-block" style="margin-bottom:14px;">
          <?php if (!empty($lock_user['foto'])): ?>
            <img src="<?= avatar_url($lock_user['foto']) ?>" alt="avatar" class="avatar-lock">
          <?php else: ?>
            <div class="avatar-fallback"><?= strtoupper(mb_substr(isset($lock_user['nama']) ? $lock_user['nama'] : '?', 0, 1)) ?></div>
          <?php endif; ?>
          <span class="lock-badge"><i class="ri-lock-line"></i></span>
        </div>
        <h4 class="fw-bold mb-1" style="font-size:22px;"><?= htmlspecialchars(isset($lock_user['nama']) ? $lock_user['nama'] : 'Pengguna') ?></h4>
        <small class="text-muted"><?= htmlspecialchars(isset($lock_user['jabatan_nama']) ? $lock_user['jabatan_nama'] : '-') ?></small>
      </div>

      <?php $lockError = $this->session->flashdata('lock_error'); ?>
      <?php if ($lockError): ?>
        <div class="alert d-flex align-items-center gap-2 small py-2 px-3 mb-3" style="border-radius:10px; border:none; background:#fef2f2; color:#991b1b;">
          <i class="ri-error-warning-line fs-5"></i> <span><?= htmlspecialchars($lockError) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('lock_screen/unlock') ?>" id="unlockForm">
        <div class="mb-3">
          <label class="form-label small fw-semibold" for="lockPassword">Kata Sandi <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="ri-lock-2-line text-muted"></i></span>
            <input type="password" name="password" id="lockPassword" class="form-control"
                   placeholder="Masukkan kata sandi akun" required autocomplete="current-password" autofocus>
            <button class="btn btn-light border" type="button" id="toggleLockPass"
                    style="border-left:none; border-radius:0 10px 10px 0;" aria-label="Tampilkan kata sandi">
              <i class="ri-eye-line"></i>
            </button>
          </div>
          <div class="form-text small text-muted" style="font-size:11px;">Gunakan kata sandi yang sama saat login.</div>
        </div>

        <button type="submit" class="btn btn-unlock w-100 fw-bold text-white" id="unlockBtn">
          <i class="ri-lock-unlock-line me-1"></i> Buka Kunci
        </button>
      </form>

      <p class="text-center small text-muted mt-4 mb-0" style="font-size:11px;">
        <i class="ri-information-line me-1"></i> Halaman tidak bisa ditinggalkan sebelum layar terbuka.
      </p>
    </div>
  </div>
</div>

<script>
(function () {
  'use strict';

  // Tampilkan / sembunyikan kata sandi
  var toggle = document.getElementById('toggleLockPass');
  if (toggle) {
    toggle.addEventListener('click', function () {
      var input = document.getElementById('lockPassword');
      var icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ri-eye-off-line';
      } else {
        input.type = 'password';
        icon.className = 'ri-eye-line';
      }
      input.focus();
    });
  }

  // Cegah tombol Back meninggalkan layar kunci.
  // Server juga memaksa redirect ke /lock_screen untuk setiap halaman lain.
  try {
    history.pushState({ locked: true }, '', location.href);
    window.addEventListener('popstate', function () {
      history.pushState({ locked: true }, '', location.href);
    });
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) location.reload();
    });
  } catch (err) { /* browser tidak mendukung History API */ }

  var form = document.getElementById('unlockForm');
  if (form) {
    form.addEventListener('submit', function () {
      var btn = document.getElementById('unlockBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa...';
      }
    });
  }

  window.addEventListener('load', function () {
    var input = document.getElementById('lockPassword');
    if (input) input.focus();
  });
})();
</script>
</body>
</html>
