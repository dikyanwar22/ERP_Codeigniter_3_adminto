<div class="d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold mb-1"><i class="ri-shopping-bag-3-line me-2 text-primary"></i>Pembelian - Purchase Order</h4>
    <small class="text-muted">Modul terintegrasi • CI3 Library Excel/PDF • Hanya jabatan dengan akses & status Show (MY_Controller)</small>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= base_url('pembelian/template') ?>" class="btn btn-outline-success btn-sm"><i class="ri-download-2-line me-1"></i> Template</a>
    <a href="<?= base_url('pembelian/export') ?>" class="btn btn-success btn-sm"><i class="ri-file-excel-line me-1"></i> Export Excel</a>
    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i class="ri-upload-2-line me-1"></i> Import Excel</button>
    <a href="<?= base_url('pembelian/create') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Buat PO</a>
  </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('pembelian/import') ?>" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header"><h6 class="modal-title fw-bold"><i class="ri-upload-2-line me-2"></i>Import dari Excel</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="alert alert-info small py-2"><i class="ri-information-line me-1"></i> Download <a href="<?= base_url('pembelian/template') ?>" class="fw-bold">Template</a> dulu. Isi baris 4 dst, jangan ubah header. Kode PO kosong = auto-generate.</div>
        <label class="form-label small fw-semibold">File Excel (.xlsx/.xls)</label>
        <input type="file" name="file" accept=".xlsx,.xls" class="form-control" required>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning btn-sm"><i class="ri-upload-line me-1"></i> Import</button></div>
    </form>
  </div>
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
            <th width="240">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- data di-load via AJAX endpoint pembelian/data (server-side) -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-light border small mt-3">
  <strong>Cara pakai:</strong> Menu ini muncul karena modul <code>Pembelian</code> (id 4, status Show) dan jabatan <strong><?= htmlspecialchars($user['jabatan_nama']) ?></strong> punya akses di <a href="<?= base_url('akses') ?>">Kelola Akses</a>. Jika modul di-hide atau akses dicabut, menu hilang & URL <code>/pembelian</code> akan 403 (dicek di <code>MY_Controller</code>).
</div>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
// DataTables server-side via endpoint - tidak load PHP loop (cepat, seperti topnav api/menu)
$(function(){
  $('#dtPembelian').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: '<?= base_url("pembelian/data") ?>',
      type: 'GET',
      dataType: 'json'
    },
    columns: [
      { data: 0 },
      { data: 1 },
      { data: 2 },
      { data: 3, className: 'text-end' },
      { data: 4 },
      { data: 5 },
      { data: 6, orderable: false, searchable: false }
    ],
    order: [[2,'desc']],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' }
  });
});
</script>
