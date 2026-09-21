<div class="py-3">
  <h4 class="fw-bold mb-1">Edit Modul: <?= htmlspecialchars($modul->nama_modul) ?></h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('modul') ?>">Modul</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <?php $isDropdown = $has_children ? true : false; ?>
    <form method="post" id="formEdit">
      <div class="row g-3">
        <?php $isTop = ($modul->parent_id==0 && $modul->level==1); ?>
        <?php if($isTop): ?>
          <div class="col-12">
            <div class="alert alert-success py-2 small mb-0">
              <i class="ri-apps-2-line me-1"></i> Edit <strong>Modul</strong> Top Level (Level 1) — sudah pasti modul
            </div>
          </div>
          <input type="hidden" name="parent_id" value="0">
          <input type="hidden" name="tipe_menu" value="<?= $modul->tipe ?>">
        <?php else: ?>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Masuk ke dalam</label>
            <select name="parent_id" class="form-select" required>
              <option value="0" <?= $modul->parent_id==0?'selected':'' ?>>— Top Level (Menu Utama) —</option>
              <?php foreach($parents as $pid=>$label): ?>
                <?php if($pid==0) continue; ?>
                <option value="<?= $pid ?>" <?= $modul->parent_id==$pid?'selected':'' ?>><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
            <small class="text-muted" style="font-size:11px;">Pilih induk. Top = menu utama.</small>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Tipe Menu (untuk menu ini)</label>
            <div class="d-flex gap-3 mt-1">
              <div class="form-check border rounded px-3 py-2 flex-grow-1">
                <input class="form-check-input" type="radio" name="tipe_menu" id="tipeTunggalEdit" value="tunggal" <?= !$isDropdown ? 'checked' : '' ?>>
                <label class="form-check-label small w-100" for="tipeTunggalEdit" style="cursor:pointer;">
                  <i class="ri-layout-line me-1 text-success"></i> <strong>Menu Tunggal</strong>
                  <small class="d-block text-muted" style="font-size:11px;">Tidak punya dropdown</small>
                </label>
              </div>
              <div class="form-check border rounded px-3 py-2 flex-grow-1">
                <input class="form-check-input" type="radio" name="tipe_menu" id="tipeDropdownEdit" value="dropdown" <?= $isDropdown ? 'checked' : '' ?>>
                <label class="form-check-label small w-100" for="tipeDropdownEdit" style="cursor:pointer;">
                  <i class="ri-list-radio me-1 text-primary"></i> <strong>Menu Dropdown</strong>
                  <small class="d-block text-muted" style="font-size:11px;">Punya dropdown</small>
                </label>
              </div>
            </div>
            <?php if($isDropdown): ?><small class="text-warning" style="font-size:11px;"><i class="ri-information-line me-1"></i>Menu ini punya <?= $this->Modul_model->count_children($modul->id) ?> sub menu.</small><?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama Menu</label>
          <input type="text" name="nama_modul" class="form-control" value="<?= set_value('nama_modul',$modul->nama_modul) ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Icon</label>
          <input type="text" name="icon" class="form-control" value="<?= set_value('icon',$modul->icon) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Urutan</label>
          <input type="number" name="urutan" class="form-control" value="<?= set_value('urutan',$modul->urutan) ?>">
        </div>
        <div class="col-md-6" id="urlWrapperEdit">
          <label class="form-label small fw-semibold">URL <span id="urlReqEdit" class="text-danger">*</span></label>
          <input type="text" name="url" id="urlInputEdit" class="form-control" value="<?= set_value('url',$modul->url) ?>" placeholder="contoh: laporan/penjualan">
          <small class="text-muted" style="font-size:11px;" id="urlHelpEdit">Wajib untuk Tunggal.</small>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="1" <?= $modul->status==1?'selected':'' ?>>Show (1) - tampil</option>
            <option value="0" <?= $modul->status==0?'selected':'' ?>>Hide (0) - sembunyi</option>
          </select>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('modul') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const r1=document.getElementById('tipeTunggalEdit'), r2=document.getElementById('tipeDropdownEdit');
  const urlI=document.getElementById('urlInputEdit'), req=document.getElementById('urlReqEdit'), help=document.getElementById('urlHelpEdit');
  function tog(){ if(r1.checked){ urlI.required=true; req.style.display=''; help.textContent='Wajib untuk Menu Tunggal (leaf).'; } else { urlI.required=false; req.style.display='none'; help.textContent='Opsional untuk Dropdown (wadah), kosongkan jika hanya induk.'; } }
  r1.addEventListener('change',tog); r2.addEventListener('change',tog); tog();
});
</script>
