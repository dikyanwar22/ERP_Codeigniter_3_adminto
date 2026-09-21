<div class="py-3">
  <h4 class="fw-bold mb-1">Profile Saya</h4>
  <p class="small text-muted mb-0">Kelola data akun Anda. Login via Email/NIK.</p>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 text-center p-4">
      <img src="https://i.pravatar.cc/150?img=12" class="rounded-circle mx-auto mb-3" width="90" height="90" alt="">
      <h5 class="fw-bold mb-1"><?= htmlspecialchars($u->nama) ?></h5>
      <p class="small text-muted mb-2"><?= htmlspecialchars($u->email) ?> • <?= htmlspecialchars($u->nik) ?></p>
      <span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($user['jabatan_nama']) ?></span>
      <span class="badge bg-<?= $u->status? 'success-subtle text-success':'danger-subtle text-danger' ?>"><?= $u->status? 'Aktif':'Nonaktif' ?></span>
      <hr>
      <small class="text-muted">ID: <?= $u->id ?> • Terdaftar: <?= $u->created_at ?></small>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Edit Profile</h6></div>
      <div class="card-body">
        <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= set_value('nama',$u->nama) ?>" required>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">NIK</label>
              <input type="text" name="nik" class="form-control" value="<?= set_value('nik',$u->nik) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" value="<?= set_value('email',$u->email) ?>" required>
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label small fw-semibold">Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti">
          </div>
          <button type="submit" class="btn btn-primary mt-3"><i class="ri-save-line me-1"></i> Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>
