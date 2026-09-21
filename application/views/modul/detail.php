<div class="py-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb small mb-1">
      <li class="breadcrumb-item"><a href="<?= base_url('modul') ?>">Modul</a></li>
      <?php foreach($breadcrumb as $b): ?>
        <?php if($b->id == $modul->id): ?>
          <li class="breadcrumb-item active"><?= htmlspecialchars($b->nama_modul) ?></li>
        <?php else: ?>
          <li class="breadcrumb-item"><a href="<?= base_url('modul/detail/'.$b->id) ?>"><?= htmlspecialchars($b->nama_modul) ?></a></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ol>
  </nav>
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h4 class="fw-bold mb-1"><i class="<?= $modul->icon ?> me-2 text-primary"></i><?= htmlspecialchars($modul->nama_modul) ?></h4>
      <small class="text-muted">Level <?= $modul->level ?> • Parent ID <?= $modul->parent_id ?> • URL: <?= $modul->url ? '<code>'.htmlspecialchars($modul->url).'</code>' : '-' ?> • Status: <?= $modul->status? '<span class="badge bg-success">Show</span>' : '<span class="badge bg-secondary">Hide</span>' ?></small>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('modul') ?>" class="btn btn-light border btn-sm"><i class="ri-arrow-left-line me-1"></i> Kembali</a>
      <a href="<?= base_url('modul/create?parent_id='.$modul->id) ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Tambah Menu di "<?= htmlspecialchars($modul->nama_modul) ?>"</a>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">Menu terkait: "<?= htmlspecialchars($modul->nama_modul) ?>" (<?= count($children) ?> item)</h6>
    <span class="badge bg-light text-dark border"><?= $modul->level==1?'Menu (Level 2)':($modul->level==2?'Sub Menu (Level 3)':'Sub-Sub Menu (Level 4)') ?></span>
  </div>
  <div class="card-body">
    <?php if(empty($children)): ?>
      <div class="text-center py-5 text-muted">
        <i class="ri-inbox-line fs-1 d-block mb-2"></i>
        Belum ada menu di dalam "<?= htmlspecialchars($modul->nama_modul) ?>"<br>
        <a href="<?= base_url('modul/create?parent_id='.$modul->id) ?>" class="btn btn-sm btn-primary mt-2">Tambah Sekarang</a>
      </div>
    <?php else: ?>
    <div class="table-responsive">
      <table id="dtDetail" class="table table-hover table-striped table-bordered w-100">
        <thead class="bg-light">
          <tr>
            <th>ID</th>
            <th>Nama Menu</th>
            <th>Icon</th>
            <th>URL</th>
            <th>Level</th>
            <th>Tipe</th>
            <th>Urut</th>
            <th>Status</th>
            <th width="240">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($children as $c): ?>
          <tr class="<?= $c->status==0?'table-light text-muted':'' ?>">
            <td><?= $c->id ?></td>
            <td><i class="<?= $c->icon ?> me-1 text-primary"></i> <?= htmlspecialchars($c->nama_modul) ?> <?php if($c->child_count): ?><span class="badge bg-info-subtle text-info ms-1"><?= $c->child_count ?> sub</span><?php endif; ?></td>
            <td><code><?= htmlspecialchars($c->icon) ?></code></td>
            <td><?= $c->url ? '<code>'.htmlspecialchars($c->url).'</code>' : '-' ?></td>
            <td><span class="badge bg-primary-subtle text-primary">L<?= $c->level ?></span></td>
            <td>
              <?php if($c->tipe=='dropdown'): ?><span class="badge bg-warning-subtle text-warning"><i class="ri-list-radio me-1"></i>Dropdown</span>
              <?php else: ?><span class="badge bg-success-subtle text-success"><i class="ri-layout-line me-1"></i>Tunggal</span><?php endif; ?>
            </td>
            <td><?= $c->urutan ?></td>
            <td>
              <?php if($c->status==1): ?><span class="badge bg-success-subtle text-success">Show</span>
              <?php else: ?><span class="badge bg-secondary">Hide</span><?php endif; ?>
            </td>
            <td>
              <?php if($c->tipe == 'dropdown' && $c->level < 4): ?>
                <a href="<?= base_url('modul/detail/'.$c->id) ?>" class="btn btn-sm <?= $c->child_count ? 'btn-info' : 'btn-outline-info' ?>"><i class="ri-eye-line me-1"></i> Detail <?= $c->child_count ? '('.$c->child_count.')' : '' ?></a>
              <?php elseif($c->tipe == 'tunggal'): ?>
                <span class="btn btn-sm btn-light border disabled" title="Menu Tunggal tidak punya dropdown"><i class="ri-eye-off-line me-1"></i> Detail</span>
              <?php else: ?>
                <span class="btn btn-sm btn-light border disabled" title="Level 4 maksimal"><i class="ri-eye-off-line me-1"></i> Detail</span>
              <?php endif; ?>
              <a href="<?= base_url('modul/edit/'.$c->id) ?>" class="btn btn-sm btn-light border"><i class="ri-edit-line"></i></a>
              <a href="<?= base_url('modul/toggle/'.$c->id.'?from=modul/detail/'.$modul->id) ?>" class="btn btn-sm <?= $c->status? 'btn-warning':'btn-success' ?>"><i class="ri-eye-<?= $c->status? 'off':'on' ?>-line"></i></a>
              <a href="<?= base_url('modul/delete/'.$c->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus <?= addslashes($c->nama_modul) ?>?')"><i class="ri-delete-bin-line"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="alert alert-warning small mt-3">
  <strong>Hide/Show:</strong> Klik tombol <i class="ri-eye-off-line"></i>/<i class="ri-eye-on-line"></i> untuk toggle. Hanya <code>Show (1)</code> yang tampil di top menu dan bisa diakses (cek via <code>MY_Controller</code>).
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(function(){
  $('#dtDetail').DataTable({
    responsive: true,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
    order: [[5,'asc']]
  });
});
</script>
