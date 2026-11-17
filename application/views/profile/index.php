<div class="py-3">
  <h4 class="fw-bold mb-1">Profile Saya</h4>
  <p class="small text-muted mb-0">Kelola foto profile dan password akun Anda.</p>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 text-center p-4">
      <img src="<?= avatar_url($u->foto) ?>" id="avatarPreview" class="rounded-circle object-fit-cover mx-auto mb-3 border" width="110" height="110" alt="Foto Profile">
      <h5 class="fw-bold mb-1"><?= htmlspecialchars($u->nama) ?></h5>
      <p class="small text-muted mb-2"><?= htmlspecialchars($u->email) ?> • <?= htmlspecialchars($u->nik) ?></p>
      <div class="d-flex justify-content-center gap-1 flex-wrap">
        <span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($user['jabatan_nama']) ?></span>
        <span class="badge bg-<?= $u->status? 'success-subtle text-success':'danger-subtle text-danger' ?>"><?= $u->status? 'Aktif':'Nonaktif' ?></span>
      </div>

      <hr>

      <form method="post" action="<?= base_url('profile/update_avatar') ?>" enctype="multipart/form-data">
        <label class="form-label small fw-semibold text-start w-100">Ganti Foto Profile</label>
        <input type="file" name="foto" id="fotoInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/gif,image/webp" required>
        <div class="form-text text-start">JPG, PNG, GIF, atau WEBP. Maksimal 2 MB.</div>
        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2"><i class="ri-upload-2-line me-1"></i> Upload Foto</button>
      </form>

      <hr>
      <small class="text-muted">ID: <?= $u->id ?> • Terdaftar: <?= $u->created_at ?></small>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm border-0 mb-3">
      <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold">Informasi Akun</h6>
        <span class="badge bg-secondary-subtle text-secondary"><i class="ri-lock-line me-1"></i>Readonly</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Nama</label>
            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($u->nama) ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">NIK</label>
            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($u->nik) ?>" readonly>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($u->email) ?>" readonly>
          </div>
        </div>
        <div class="form-text mt-2"><i class="ri-information-line me-1"></i>Nama, NIK, dan Email tidak dapat diubah dari halaman ini. Hubungi admin jika perlu perubahan.</div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Ubah Password</h6></div>
      <div class="card-body">
        <form method="post" action="<?= base_url('profile/update_password') ?>" autocomplete="off">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Password Baru</label>
            <div class="input-group">
              <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" required autocomplete="new-password">
              <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" tabindex="-1" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Konfirmasi Password</label>
            <div class="input-group">
              <input type="password" name="passconf" id="passconf" class="form-control" placeholder="Ulangi password baru" required autocomplete="new-password">
              <button class="btn btn-outline-secondary toggle-password" type="button" data-target="passconf" tabindex="-1" aria-label="Tampilkan konfirmasi password"><i class="bi bi-eye"></i></button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary"><i class="ri-lock-password-line me-1"></i> Simpan Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  // Preview foto sebelum diupload
  var input = document.getElementById('fotoInput');
  var preview = document.getElementById('avatarPreview');
  if (input && preview) {
    input.addEventListener('change', function () {
      var file = this.files && this.files[0];
      if (!file) return;
      if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
      var url = URL.createObjectURL(file);
      preview.dataset.objectUrl = url;
      preview.src = url;
    });
  }

  // Toggle tampil/sembunyi password (icon mata)
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = document.getElementById(this.getAttribute('data-target'));
      if (!target) return;
      var show = target.type === 'password';
      target.type = show ? 'text' : 'password';
      var icon = this.querySelector('i');
      if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
      this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
  });
})();
</script>
