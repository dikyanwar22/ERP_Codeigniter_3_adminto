<div class="py-3">
  <h4 class="fw-bold mb-1">Tambah Akun</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('akun') ?>">Akun</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label small fw-semibold">NIK</label>
          <input type="text" name="nik" class="form-control" value="<?= set_value('nik') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama</label>
          <input type="text" name="nama" class="form-control" value="<?= set_value('nama') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Jabatan</label>
          <select name="jabatan_id" class="form-select" required>
            <option value="">-- Pilih --</option>
            <?php foreach($jabatan as $j): ?>
              <option value="<?= $j->id ?>" <?= set_select('jabatan_id',$j->id) ?>><?= htmlspecialchars($j->nama_jabatan) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="1" <?= set_select('status',1,true) ?>>Aktif (1) - bisa login</option>
            <option value="0" <?= set_select('status',0) ?>>Nonaktif (0) - tidak bisa login</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Konfirmasi Password</label>
          <input type="password" name="passconf" class="form-control" required>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Simpan</button>
        <a href="<?= base_url('akun') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>
