<div class="py-3">
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('pembelian') ?>">Pembelian</a></li><li class="breadcrumb-item active"><?= htmlspecialchars($p->kode_po) ?></li></ol></nav>
  <h4 class="fw-bold mb-1"><i class="ri-shopping-bag-3-line me-2 text-primary"></i><?= htmlspecialchars($p->kode_po) ?></h4>
  <small class="text-muted"><?= htmlspecialchars($p->supplier) ?> • <?= $p->tanggal ?> • Rp <?= number_format($p->total,0,',','.') ?></small>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <table class="table table-sm">
          <tr><th width="120">Kode PO</th><td><?= htmlspecialchars($p->kode_po) ?></td></tr>
          <tr><th>Supplier</th><td><?= htmlspecialchars($p->supplier) ?></td></tr>
          <tr><th>Tanggal</th><td><?= $p->tanggal ?></td></tr>
          <tr><th>Total</th><td>Rp <?= number_format($p->total,0,',','.') ?></td></tr>
          <tr><th>Status</th><td><span class="badge bg-primary"><?= htmlspecialchars($p->status) ?></span></td></tr>
          <tr><th>Keterangan</th><td><?= htmlspecialchars($p->keterangan) ?></td></tr>
        </table>
      </div>
      <div class="col-md-6">
        <div class="alert alert-info small">
          <strong>Contoh integrasi:</strong> Controller <code>Pembelian.php:1</code> extends <code>MY_Controller</code> → otomatis cek login, cek <code>status Show</code> & <code>ci_akses</code> jabatan <strong><?= htmlspecialchars($this->session->userdata('user')['jabatan_nama']) ?></strong>. Jika modul di-hide atau akses dicabut → 403.
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= base_url('pembelian/edit/'.$p->id) ?>" class="btn btn-primary btn-sm"><i class="ri-edit-line me-1"></i> Edit</a>
          <a href="<?= base_url('pembelian/pdf/'.$p->id) ?>" class="btn btn-danger btn-sm"><i class="ri-file-pdf-line me-1"></i> Export PDF</a>
          <a href="<?= base_url('pembelian/pdf_view/'.$p->id) ?>" target="_blank" class="btn btn-outline-danger btn-sm"><i class="ri-eye-line me-1"></i> View PDF</a>
          <a href="<?= base_url('pembelian') ?>" class="btn btn-light border btn-sm">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>
