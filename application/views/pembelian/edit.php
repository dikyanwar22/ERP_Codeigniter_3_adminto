<div class="py-3">
  <h4 class="fw-bold mb-1">Edit PO: <?= htmlspecialchars($p->kode_po) ?></h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('pembelian') ?>">Pembelian</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Kode PO *</label>
          <input type="text" name="kode_po" class="form-control" value="<?= set_value('kode_po',$p->kode_po) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Supplier *</label>
          <input type="text" name="supplier" class="form-control" value="<?= set_value('supplier',$p->supplier) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Tanggal *</label>
          <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal',$p->tanggal) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Total *</label>
          <input type="number" name="total" class="form-control" value="<?= set_value('total',$p->total) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status *</label>
          <select name="status" class="form-select" required>
            <option value="draft" <?= $p->status=='draft'?'selected':'' ?>>Draft</option>
            <option value="proses" <?= $p->status=='proses'?'selected':'' ?>>Proses</option>
            <option value="selesai" <?= $p->status=='selesai'?'selected':'' ?>>Selesai</option>
            <option value="batal" <?= $p->status=='batal'?'selected':'' ?>>Batal</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Keterangan</label>
          <input type="text" name="keterangan" class="form-control" value="<?= set_value('keterangan',$p->keterangan) ?>">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('pembelian') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>
