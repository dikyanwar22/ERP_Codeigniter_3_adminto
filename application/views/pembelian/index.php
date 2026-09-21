<div class="d-flex justify-content-between align-items-center py-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="ri-shopping-bag-3-line me-2 text-primary"></i>Pembelian - Purchase Order</h4>
    <small class="text-muted">Contoh modul terintegrasi • Hanya jabatan dengan akses & status Show bisa akses (MY_Controller)</small>
  </div>
  <a href="<?= base_url('pembelian/create') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Buat PO</a>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="table-responsive">
      <table id="dtPembelian" class="table table-hover table-striped table-bordered w-100">
        <thead class="bg-light">
          <tr>
            <th>Kode PO</th>
            <th>Supplier</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Pembuat</th>
            <th width="180">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($pembelian as $p): ?>
          <tr>
            <td><a href="<?= base_url('pembelian/detail/'.$p->id) ?>" class="fw-semibold text-primary"><?= htmlspecialchars($p->kode_po) ?></a></td>
            <td><?= htmlspecialchars($p->supplier) ?></td>
            <td><?= date('d/m/Y', strtotime($p->tanggal)) ?></td>
            <td class="text-end">Rp <?= number_format($p->total,0,',','.') ?></td>
            <td>
              <?php if($p->status=='draft'): ?><span class="badge bg-secondary">Draft</span>
              <?php elseif($p->status=='proses'): ?><span class="badge bg-warning-subtle text-warning">Proses</span>
              <?php elseif($p->status=='selesai'): ?><span class="badge bg-success-subtle text-success">Selesai</span>
              <?php else: ?><span class="badge bg-danger-subtle text-danger">Batal</span><?php endif; ?>
            </td>
            <td><small><?= htmlspecialchars($p->pembuat) ?></small></td>
            <td>
              <a href="<?= base_url('pembelian/detail/'.$p->id) ?>" class="btn btn-sm btn-info"><i class="ri-eye-line"></i></a>
              <a href="<?= base_url('pembelian/edit/'.$p->id) ?>" class="btn btn-sm btn-light border"><i class="ri-edit-line"></i></a>
              <a href="<?= base_url('pembelian/delete/'.$p->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus PO <?= $p->kode_po ?>?')"><i class="ri-delete-bin-line"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-light border small mt-3">
  <strong>Cara pakai:</strong> Menu ini muncul karena modul <code>Pembelian</code> (id 4, status Show) dan jabatan <strong><?= htmlspecialchars($user['jabatan_nama']) ?></strong> punya akses di <a href="<?= base_url('akses') ?>">Kelola Akses</a>. Jika modul di-hide atau akses dicabut, menu hilang & URL <code>/pembelian</code> akan 403 (dicek di <code>MY_Controller</code>).
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(function(){ $('#dtPembelian').DataTable({ responsive:true, language:{url:'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'}, order:[[2,'desc']] }); });
</script>
