<div class="py-3">
  <h4 class="fw-bold mb-1">Kelola Akses per Jabatan</h4>
  <small class="text-muted">Checkbox modul yang bisa diakses oleh jabatan. Hanya modul <strong>status Show (1)</strong> yang tampil. Hide tidak bisa diakses.</small>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Pilih Jabatan</label>
        <select name="jabatan_id" class="form-select" onchange="this.form.submit()">
          <?php foreach($jabatan as $j): ?>
            <option value="<?= $j->id ?>" <?= $selected_jabatan==$j->id?'selected':'' ?>><?= htmlspecialchars($j->nama_jabatan) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <div class="alert alert-info small py-2 mb-0">Menampilkan akses untuk <strong>
          <?php foreach($jabatan as $j) if($j->id==$selected_jabatan) echo htmlspecialchars($j->nama_jabatan); ?>
        </strong> • Centang modul yang diizinkan.</div>
      </div>
    </form>
  </div>
</div>

<form method="post">
  <input type="hidden" name="jabatan_id" value="<?= $selected_jabatan ?>">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h6 class="mb-0 fw-bold"><i class="ri-shield-keyhole-line me-2"></i>Daftar Modul (checkbox)</h6>
      <div>
        <button type="button" class="btn btn-sm btn-light border" onclick="checkAll(true)">Check All</button>
        <button type="button" class="btn btn-sm btn-light border" onclick="checkAll(false)">Uncheck All</button>
        <button type="submit" class="btn btn-sm btn-primary"><i class="ri-save-line me-1"></i> Simpan Akses</button>
      </div>
    </div>
    <div class="card-body" style="max-height:600px; overflow-y:auto;">
      <?php
      function render_akses_tree($tree, $akses_ids, $depth=0) {
        foreach($tree as $n) {
          $checked = in_array($n->id, $akses_ids) ? 'checked' : '';
          $indent = $depth*24;
          echo '<div class="form-check py-2 border-bottom" style="margin-left:'.$indent.'px">';
          echo '<input class="form-check-input akses-check" type="checkbox" name="modul_ids[]" value="'.$n->id.'" id="m_'.$n->id.'" '.$checked.'>';
          echo '<label class="form-check-label d-flex align-items-center gap-2" for="m_'.$n->id.'">';
          echo '<i class="'.$n->icon.'"></i> <strong>'.htmlspecialchars($n->nama_modul).'</strong>';
          echo '<small class="text-muted">L'.$n->level.' • '.$n->url.'</small>';
          echo '<span class="badge bg-success-subtle text-success ms-1">show</span>';
          echo '</label>';
          echo '</div>';
          if (!empty($n->children)) render_akses_tree($n->children, $akses_ids, $depth+1);
        }
      }
      render_akses_tree($tree, $akses_ids);
      ?>
    </div>
    <div class="card-footer bg-white text-end">
      <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Simpan Akses untuk Jabatan Terpilih</button>
    </div>
  </div>
</form>

<script>
function checkAll(v){
  document.querySelectorAll('.akses-check').forEach(c=>c.checked=v);
}
</script>

<div class="alert alert-warning small mt-3">
  <strong>Catatan:</strong> Jika modul di-hide (status 0) di <a href="<?= base_url('modul') ?>">Kelola Modul</a>, otomatis tidak muncul di sini dan tidak bisa diakses meski dicentang.
</div>
