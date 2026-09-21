<div class="d-flex justify-content-between align-items-center py-3">
  <div>
    <h4 class="fw-bold mb-1">Kelola Modul</h4>
    <small class="text-muted">Semua modul (level 1) dalam DataTables • Klik Detail untuk lihat menu terkait</small>
  </div>
  <a href="<?= base_url('modul/create') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Tambah Modul</a>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="table-responsive">
      <table id="dtModul" class="table table-hover table-striped table-bordered w-100">
        <thead class="bg-light">
          <tr>
            <th width="50">ID</th>
            <th>Nama Modul</th>
            <th>Icon</th>
            <th>URL</th>
            <th>Tipe</th>
            <th>Urut</th>
            <th>Status</th>
            <th width="240">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($modul_top as $m): ?>
            <?php $hasChild = $this->Modul_model->has_children($m->id); $cnt = $this->Modul_model->count_children($m->id); ?>
          <tr>
            <td><?= $m->id ?></td>
            <td><i class="<?= $m->icon ?> me-1 text-primary"></i> <strong><?= htmlspecialchars($m->nama_modul) ?></strong> <?php if($hasChild): ?><span class="badge bg-info-subtle text-info ms-1"><?= $cnt ?> menu</span><?php endif; ?></td>
            <td><code><?= htmlspecialchars($m->icon) ?></code></td>
            <td><?= $m->url ? '<code>'.htmlspecialchars($m->url).'</code>' : '<span class="text-muted">-</span>' ?></td>
            <td>
              <?php if($m->tipe=='dropdown'): ?><span class="badge bg-warning-subtle text-warning">Dropdown</span>
              <?php else: ?><span class="badge bg-success-subtle text-success">Tunggal</span><?php endif; ?>
            </td>
            <td><?= $m->urutan ?></td>
            <td>
              <?php if($m->status==1): ?><span class="badge bg-success-subtle text-success">Show (1)</span>
              <?php else: ?><span class="badge bg-secondary">Hide (0)</span><?php endif; ?>
            </td>
            <td>
              <?php if($m->tipe=='dropdown'): ?>
                <a href="<?= base_url('modul/detail/'.$m->id) ?>" class="btn btn-sm <?= $hasChild ? 'btn-info' : 'btn-outline-info' ?>"><i class="ri-eye-line me-1"></i> Detail <?= $hasChild ? '('.$cnt.')' : '' ?></a>
              <?php endif; ?>
              <a href="<?= base_url('modul/edit/'.$m->id) ?>" class="btn btn-sm btn-light border"><i class="ri-edit-line"></i></a>
              <a href="<?= base_url('modul/toggle/'.$m->id.'?from=modul') ?>" class="btn btn-sm <?= $m->status? 'btn-warning':'btn-success' ?>" title="Toggle hide/show">
                <i class="ri-eye-<?= $m->status? 'off':'on' ?>-line"></i>
              </a>
              <a href="<?= base_url('modul/delete/'.$m->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus modul <?= addslashes($m->nama_modul) ?>?')"><i class="ri-delete-bin-line"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-info small mt-3">
  <strong>Cara pakai:</strong> Klik <span class="badge bg-info">Detail</span> pada modul untuk melihat DataTables menu yang berkaitan. Jika menu tersebut adalah submenu (level 2/3) dan memiliki sub-sub lagi, tombol Detail akan muncul lagi untuk melihat sub-sub menu.
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(function(){
  $('#dtModul').DataTable({
    responsive: true,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
    order: [[5,'asc']]
  });
});
</script>
