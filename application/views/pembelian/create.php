<div class="py-3">
  <h4 class="fw-bold mb-1">Buat Purchase Order</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('pembelian') ?>">Pembelian</a></li><li class="breadcrumb-item active">Buat</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Kode PO *</label>
          <input type="text" name="kode_po" class="form-control" value="<?= set_value('kode_po',$kode) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Supplier *</label>
          <input type="text" name="supplier" class="form-control" value="<?= set_value('supplier') ?>" placeholder="PT Sumber Makmur" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Tanggal *</label>
          <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal',date('Y-m-d')) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Total *</label>
          <input type="number" name="total" class="form-control" value="<?= set_value('total') ?>" placeholder="12500000" required>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Status *</label>
          <select name="status" class="form-select" required>
            <option value="draft">Draft</option>
            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
            <option value="batal">Batal</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Keterangan</label>
          <input type="text" name="keterangan" class="form-control" value="<?= set_value('keterangan') ?>" placeholder="Opsional">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('pembelian') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>
