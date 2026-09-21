<div class="d-flex justify-content-between align-items-center py-3">
  <h4 class="mb-0 fw-bold">Daftar Akun</h4>
  <a href="<?= base_url('akun/create') ?>" class="btn btn-primary btn-sm"><i class="ri-user-add-line me-1"></i> Tambah Akun</a>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-centered mb-0">
        <thead class="bg-light">
          <tr><th>#</th><th>NIK</th><th>Nama</th><th>Email</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php $no=1; foreach($users as $u): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code><?= htmlspecialchars($u->nik) ?></code></td>
            <td><?= htmlspecialchars($u->nama) ?></td>
            <td><?= htmlspecialchars($u->email) ?></td>
            <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($u->nama_jabatan) ?></span></td>
            <td>
              <?php if($u->status==1): ?><span class="badge bg-success-subtle text-success">Aktif (1)</span>
              <?php else: ?><span class="badge bg-danger-subtle text-danger">Nonaktif (0)</span><?php endif; ?>
            </td>
            <td>
              <a href="<?= base_url('akun/edit/'.$u->id) ?>" class="btn btn-sm btn-light border"><i class="ri-edit-line"></i></a>
              <a href="<?= base_url('akun/delete/'.$u->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus akun <?= htmlspecialchars($u->nama) ?>?')"><i class="ri-delete-bin-line"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-info small mt-3">
  <strong>Info:</strong> Akun status 0 tidak bisa login (email/NIK). Coba login dengan <code>nonaktif@adminto.com</code> atau NIK <code>1234567890123459</code> pass <code>admin123</code> akan ditolak.
</div>
