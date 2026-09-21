<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - ERP System | Adminto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'Nunito',sans-serif}
body{background:#f1f3f6; min-height:100vh;}
.login-wrapper{min-height:100vh; display:flex;}
.left-panel{
  background: linear-gradient(135deg, #2a3142 0%, #1e2433 50%, #727cf5 100%);
  color:#fff;
  position:relative;
  overflow:hidden;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  padding:48px;
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
.feature{ display:flex; gap:14px; align-items:flex-start; }
.feature-icon{ width:44px; height:44px; background:rgba(255,255,255,0.12); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.right-panel{ background:#fff; display:flex; align-items:center; justify-content:center; padding:40px; }
.login-card{ width:100%; max-width:420px; }
.form-control{ padding:12px 14px; border-radius:10px; border:1px solid #e3e8ef; background:#f8f9fb; }
.form-control:focus{ background:#fff; border-color:#727cf5; box-shadow:0 0 0 3px rgba(114,124,245,0.12); }
.input-group-text{ background:#f8f9fb; border:1px solid #e3e8ef; border-right:none; border-radius:10px 0 0 10px; }
.input-group .form-control{ border-left:none; border-radius:0 10px 10px 0; }
.btn-primary{ background:#727cf5; border:none; border-radius:10px; padding:12px; box-shadow:0 6px 16px rgba(114,124,245,0.3); }
.btn-primary:hover{ background:#5a66f0; }
.divider{ display:flex; align-items:center; gap:12px; color:#9aa3b5; font-size:12px; }
.divider::before, .divider::after{ content:''; flex:1; height:1px; background:#e9ecef; }
@media (max-width: 991px){
  .left-panel{ display:none !important; }
}
</style>
</head>
<body>
<div class="login-wrapper">
  <!-- Left: Branding ERP -->
  <div class="col-lg-6 left-panel d-none d-lg-flex">
    <div style="position:relative; z-index:1;">
      <div class="brand mb-5"><i class="ri-dashboard-line me-2"></i>ADMINTO <span class="fw-light opacity-75">ERP</span></div>
      <h1 class="fw-bold mb-3" style="line-height:1.2; font-size:32px;">Kelola Bisnis<br>Lebih Terintegrasi</h1>
      <p class="opacity-75 mb-5" style="font-size:15px; line-height:1.6;">ERP System terpadu untuk mengelola penjualan, pembelian, inventory, keuangan & HRD dalam satu platform.</p>
      
      <div class="d-flex flex-column gap-4">
        <div class="feature">
          <div class="feature-icon"><i class="ri-shield-check-line fs-5"></i></div>
          <div>
            <h6 class="mb-1 fw-bold" style="font-size:14px;">Akses Berbasis Jabatan</h6>
            <small class="opacity-75" style="font-size:13px;">Kontrol modul per jabatan, hanya Show yang tampil</small>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ri-apps-2-line fs-5"></i></div>
          <div>
            <h6 class="mb-1 fw-bold" style="font-size:14px;">Modul Dinamis 4 Level</h6>
            <small class="opacity-75" style="font-size:13px;">Kelola hingga sub-sub menu via DataTables</small>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon"><i class="ri-device-line fs-5"></i></div>
          <div>
            <h6 class="mb-1 fw-bold" style="font-size:14px;">Responsive & Modern</h6>
            <small class="opacity-75" style="font-size:13px;">Top menu horizontal scroll + bottom bar mobile</small>
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

  <!-- Right: Login Form -->
  <div class="col-lg-6 right-panel">
    <div class="login-card">
      <div class="text-center d-lg-none mb-4">
        <h3 class="fw-bold" style="font-family:'Poppins',sans-serif;"><i class="ri-dashboard-line text-primary"></i> ADMINTO <span class="fw-light">ERP</span></h3>
      </div>

      <div class="mb-4">
        <h4 class="fw-bold mb-1" style="font-size:22px;">ERP System</h4>
      </div>

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 small py-2 px-3" style="border-radius:10px; border:none; background:#fef2f2; color:#991b1b;">
          <i class="ri-error-warning-line fs-5"></i> <span><?= $this->session->flashdata('error') ?></span>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center gap-2 small py-2 px-3" style="border-radius:10px; border:none; background:#f0fdf4; color:#166534;">
          <i class="ri-checkbox-circle-line fs-5"></i> <span><?= $this->session->flashdata('success') ?></span>
        </div>
      <?php endif; ?>
      <?= validation_errors('<div class="alert alert-warning small py-2 px-3" style="border-radius:10px;">','</div>') ?>

      <form method="post" action="<?= base_url('login') ?>">
        <div class="mb-3">
          <label class="form-label small fw-semibold">Email / NIK <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line text-muted"></i></span>
            <input type="text" name="identity" class="form-control" placeholder="email / NIK" value="<?= set_value('identity') ?>" required autofocus>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <label class="form-label small fw-semibold mb-0">Password <span class="text-danger">*</span></label>
            <a href="#" class="small text-decoration-none" style="font-size:12px; color:#727cf5;" onclick="alert('Hubungi Administrator untuk reset password');return false;">Lupa password?</a>
          </div>
          <div class="input-group mt-1">
            <span class="input-group-text"><i class="ri-lock-2-line text-muted"></i></span>
            <input type="password" name="password" id="passInput" class="form-control" placeholder="Masukkan password" required>
            <button class="btn btn-light border" type="button" id="togglePass" style="border-left:none; border-radius:0 10px 10px 0;"><i class="ri-eye-line"></i></button>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="ri-login-box-line me-1"></i> Login</button>

        <div class="divider my-4">atau</div>

        <div class="d-grid gap-2">
          <a href="<?= base_url('akun/register') ?>" class="btn btn-light border w-100" style="border-radius:10px; padding:11px;"><i class="ri-user-add-line me-1"></i> Daftar Akun Baru</a>
        </div>
      </form>

      <p class="text-center small text-muted mt-4 mb-0" style="font-size:11px;">
        Dengan masuk, Anda menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> ERP System
      </p>
    </div>
  </div>
</div>

<script>
document.getElementById('togglePass')?.addEventListener('click', function(){
  const i = document.getElementById('passInput');
  const icon = this.querySelector('i');
  if(i.type==='password'){ i.type='text'; icon.className='ri-eye-off-line'; }
  else { i.type='password'; icon.className='ri-eye-line'; }
});
</script>
</body>
</html>
