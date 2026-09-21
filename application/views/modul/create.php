<div class="py-3">
  <h4 class="fw-bold mb-1">Tambah Menu</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0"><li class="breadcrumb-item"><a href="<?= base_url('modul') ?>">Modul</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-warning small py-2">','</div>') ?>
    <form method="post" id="formCreate">
      <div class="row g-3">
        <?php $isPreset = isset($preset_parent) && $preset_parent != 0; ?>
        <?php
          // Hitung base URL konsisten dari breadcrumb: modul/menu/sub ...
          // Tampilkan di input URL sebagai value (bukan placeholder) biar user langsung lihat
          $parentBase = '';
          if($isPreset){
            $presetModul = $this->Modul_model->get_by_id($preset_parent);
            if($presetModul){
              if(!empty($presetModul->url)){
                $parentBase = trim($presetModul->url, '/');
              } else {
                $trail = $this->Modul_model->get_breadcrumb($preset_parent);
                $parts = [];
                foreach($trail as $t){
                  if(!empty($t->url)){
                    $parts[] = trim($t->url, '/');
                  } else {
                    $s = strtolower($t->nama_modul);
                    $s = preg_replace('/\s+/', '_', $s);
                    $s = preg_replace('/[^a-z0-9_]/', '', $s);
                    $s = trim($s, '_');
                    if($s !== '') $parts[] = $s;
                  }
                }
                // jika elemen terakhir sudah full path (mengandung /), pakai itu saja agar tidak dobel
                $last = end($parts);
                if($last !== false && strpos($last, '/') !== false){
                  $parentBase = $last;
                } else {
                  $parentBase = implode('/', array_filter($parts));
                }
              }
            }
          }
        ?>
        <?php if ($isPreset): ?>
          <div class="col-12">
            <div class="alert alert-primary py-2 small mb-0">
              <i class="ri-information-line me-1"></i> Menambah di dalam <strong><i class="<?= $presetModul->icon ?> me-1"></i><?= htmlspecialchars($presetModul->nama_modul) ?></strong> (Level <?= $presetModul->level ?> → akan jadi Level <?= $presetModul->level+1 ?>)
              <?php if(!empty($parentBase)): ?>
                <span class="ms-2">Path: <code><?= htmlspecialchars($parentBase) ?></code> → saran URL akan <code><?= htmlspecialchars($parentBase) ?>/&lt;nama_menu&gt;</code></span>
              <?php endif; ?>
            </div>
          </div>
          <input type="hidden" name="parent_id" value="<?= $preset_parent ?>">
          <div class="col-12">
            <label class="form-label small fw-semibold">Tipe Menu (untuk menu ini) <span class="text-danger">*</span></label>
            <div class="d-flex gap-3">
              <div class="form-check border rounded px-3 py-2 flex-grow-1" style="cursor:pointer;">
                <input class="form-check-input" type="radio" name="tipe_menu" id="tipeTunggal" value="tunggal" checked>
                <label class="form-check-label small w-100" for="tipeTunggal" style="cursor:pointer;">
                  <i class="ri-layout-line me-1 text-success"></i> <strong>Menu Tunggal</strong>
                  <small class="text-muted d-block" style="font-size:11px;">Tidak punya dropdown</small>
                </label>
              </div>
              <div class="form-check border rounded px-3 py-2 flex-grow-1" style="cursor:pointer;">
                <input class="form-check-input" type="radio" name="tipe_menu" id="tipeDropdown" value="dropdown">
                <label class="form-check-label small w-100" for="tipeDropdown" style="cursor:pointer;">
                  <i class="ri-list-radio me-1 text-primary"></i> <strong>Menu Dropdown</strong>
                  <small class="text-muted d-block" style="font-size:11px;">Punya dropdown</small>
                </label>
              </div>
            </div>
          </div>
        <?php else: ?>
          <!-- <div class="col-12">
            <div class="alert alert-success py-2 small mb-0">
              <i class="ri-apps-2-line me-1"></i> Menambah <strong>Modul</strong> Top Level (Level 1) — sudah pasti modul
            </div>
          </div> -->
          <input type="hidden" name="parent_id" value="0">
          <input type="hidden" name="tipe_menu" value="tunggal">
        <?php endif; ?>

        <div class="col-md-6">
          <label class="form-label small fw-semibold">Nama Menu</label>
          <input type="text" name="nama_modul" id="namaInput" class="form-control" value="<?= set_value('nama_modul') ?>" placeholder="Contoh: Laporan Penjualan" required>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Icon</label>
          <input type="text" name="icon" class="form-control" value="<?= set_value('icon','ri-apps-2-line') ?>" placeholder="ri-dashboard-line">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Urutan</label>
          <input type="number" name="urutan" class="form-control" value="<?= set_value('urutan',0) ?>">
        </div>

        <div class="col-md-6" id="urlWrapper">
          <label class="form-label small fw-semibold">URL <span id="urlRequired" class="text-danger">*</span></label>
          <input type="text" name="url" id="urlInput" class="form-control" value="<?= set_value('url') ?>" placeholder="contoh: pembelian/laporan">
          <small class="text-muted" style="font-size:11px;" id="urlHelp">Wajib untuk Menu Tunggal (leaf).</small>
          <small class="text-primary" style="font-size:11px; display:none;" id="urlSaran">Saran: <code id="urlSaranText"></code> — sudah terisi di input, tinggal edit jika perlu</small>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="1" selected>Show (1) - tampil</option>
            <option value="0">Hide (0) - sembunyi</option>
          </select>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= $isPreset ? base_url('modul/detail/'.$preset_parent) : base_url('modul') ?>" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const r1=document.getElementById('tipeTunggal'), r2=document.getElementById('tipeDropdown');
  const urlI=document.getElementById('urlInput'), req=document.getElementById('urlRequired'), help=document.getElementById('urlHelp');
  function tog(){ 
    const isTunggal = r1 ? r1.checked : true;
    if(isTunggal){ urlI.required=true; req.style.display=''; help.textContent='Wajib untuk Menu Tunggal (leaf).'; } 
    else { urlI.required=false; req.style.display='none'; help.textContent='Opsional untuk Menu Dropdown (wadah), kosongkan jika hanya induk.'; } 
  }
  if(r1) r1.addEventListener('change',tog); if(r2) r2.addEventListener('change',tog); tog();

  // === Konsisten URL = parentUrl + slug(nama_menu) ===
  // Contoh: parent Pembelian (url=pembelian) + nama=laporan => saran=pembelian/laporan
  // Sub: parent pembelian/laporan + nama=laporan_mingguan => pembelian/laporan/laporan_mingguan
  const namaI = document.getElementById('namaInput');
  const saranBox = document.getElementById('urlSaran');
  const saranText = document.getElementById('urlSaranText');
  const parentBase = <?= json_encode($parentBase ?? '') ?>;
  // alias untuk kompatibilitas
  const parentUrl = parentBase;
  function slugify(t){
    return t.toString().toLowerCase().trim()
      .replace(/\s+/g,'_')
      .replace(/[^a-z0-9_\/-]/g,'')
      .replace(/_+/g,'_')
      .replace(/^_+|_+$/g,'');
  }
  let lastAuto = '';
  let userEdited = false;
  function updateSaran(){
    const slug = slugify(namaI.value);
    const cleanParent = parentUrl ? parentUrl.replace(/\/$/,'') : '';
    let suggestion = '';
    if(!slug){
      // belum ketik nama: tampilkan prefix parent sebagai saran di INPUT (bukan placeholder)
      if(cleanParent){
        suggestion = cleanParent + '/';
      } else {
        saranBox.style.display='none';
        return;
      }
    } else {
      suggestion = cleanParent ? cleanParent + '/' + slug : slug;
    }
    saranText.textContent = suggestion;
    saranBox.style.display='';
    // auto-isi INPUT URL secara langsung (value, bukan placeholder)
    if(!userEdited || urlI.value === '' || urlI.value === lastAuto){
      urlI.value = suggestion;
      lastAuto = suggestion;
      if(urlI.value === suggestion) userEdited = false;
    } else {
      lastAuto = suggestion;
    }
  }
  // init: langsung isi input dengan prefix parent jika kosong
  if(parentUrl && urlI.value === '' && !namaI.value){
    const init = parentUrl.replace(/\/$/,'') + '/';
    urlI.value = init;
    lastAuto = init;
    saranText.textContent = init;
    saranBox.style.display='';
  }
  if(namaI){
    namaI.addEventListener('input', updateSaran);
    // trigger awal
    updateSaran();
  }
  if(urlI){
    urlI.addEventListener('input', function(){
      if(urlI.value !== lastAuto) userEdited = true;
      else userEdited = false;
    });
  }
});
</script>
