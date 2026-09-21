<div class="py-3">
  <h4 class="fw-bold mb-1">Edit Akun: <?= htmlspecialchars($u->nama) ?></h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('akun') ?>">Akun</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label small fw-semibold">NIK</label>
          <input type="text" name="nik" class="form-control" value="<?= set_value('nik',$u->nik) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="<?= set_value('email',$u->email) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama</label>
          <input type="text" name="nama" class="form-control" value="<?= set_value('nama',$u->nama) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Jabatan</label>
          <select name="jabatan_id" class="form-select" required>
            <?php foreach($jabatan as $j): ?>
              <option value="<?= $j->id ?>" <?= $u->jabatan_id==$j->id?'selected':'' ?>><?= htmlspecialchars($j->nama_jabatan) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="1" <?= $u->status==1?'selected':'' ?>>Aktif (1)</option>
            <option value="0" <?= $u->status==0?'selected':'' ?>>Nonaktif (0)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Password Baru (kosongkan jika tidak ganti)</label>
          <input type="password" name="password" class="form-control" placeholder="••••••">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <small class="text-muted">Jika diisi, password akan di-hash ulang</small>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('akun') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>
