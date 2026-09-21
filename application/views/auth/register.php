<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun - Adminto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>*{font-family:'Nunito',sans-serif} body{background:#f3f5f9; min-height:100vh; padding:30px 0} .card{border:none; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,0.08)}</style>
</head>
<body>
<div class="container" style="max-width:560px;">
  <div class="text-center mb-4">
    <h3 class="fw-bold"><i class="ri-dashboard-line text-primary"></i> ADMINTO</h3>
    <p class="text-muted small">Daftar Akun Baru</p>
  </div>
  <div class="card p-4">
    <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger small"><?= $this->session->flashdata('error') ?></div><?php endif; ?>
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label small fw-semibold">NIK (16-20 digit)</label>
          <input type="text" name="nik" class="form-control" value="<?= set_value('nik') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
        </div>
        <div class="col-12">
          <label class="form-label small fw-semibold">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= set_value('nama') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Jabatan</label>
          <select name="jabatan_id" class="form-select" required>
            <option value="">-- Pilih Jabatan --</option>
            <?php foreach($jabatan as $j): ?>
              <option value="<?= $j->id ?>" <?= set_select('jabatan_id',$j->id) ?>><?= htmlspecialchars($j->nama_jabatan) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label small fw-semibold">Konfirmasi Password</label>
          <input type="password" name="passconf" class="form-control" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-4"><i class="ri-user-add-line me-1"></i> Daftar</button>
    </form>
    <div class="text-center mt-3"><small>Sudah punya akun? <a href="<?= base_url('login') ?>">Login</a></small></div>
  </div>
</div>
</body>
</html>
