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
      <small class="text-muted">Level <?= $modul->level ?> • Parent ID <?= $modul->parent_id ?> • URL: <?= $modul->url ? '<code>'.htmlspecialchars($modul->url).'</code>' : '-' ?> • Status: <?= $modul->status? '<span class="badge bg-success">Show</span>' : '<span class="badge bg-secondary">Hide</span>' ?> • <span id="childCountBadge" class="badge bg-info-subtle text-info">loading...</span></small>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('modul') ?>" class="btn btn-light border btn-sm"><i class="ri-arrow-left-line me-1"></i> Kembali</a>
      <a href="<?= base_url('modul/create?parent_id='.$modul->id) ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Tambah di "<?= htmlspecialchars($modul->nama_modul) ?>"</a>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">Menu terkait: "<span class="text-primary"><?= htmlspecialchars($modul->nama_modul) ?></span>" <small class="text-muted" id="totalInfo"></small></h6>
    <span class="badge bg-light text-dark border"><?= $modul->level==1?'Menu (Level 2)':($modul->level==2?'Sub Menu (Level 3)':'Sub-Sub Menu (Level 4)') ?></span>
  </div>
  <div class="card-body">
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
            <th width="260">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div id="emptyState" class="text-center py-5 text-muted d-none">
      <i class="ri-inbox-line fs-1 d-block mb-2"></i>
      Belum ada menu di dalam "<?= htmlspecialchars($modul->nama_modul) ?>"<br>
      <a href="<?= base_url('modul/create?parent_id='.$modul->id) ?>" class="btn btn-sm btn-primary mt-2">Tambah Sekarang</a>
    </div>
  </div>
</div>

<div class="alert alert-warning small mt-3">
  <strong>Hide/Show:</strong> Data diambil via <code>GET <?= base_url('modul/json_detail/'.$modul->id) ?></code> dengan jQuery AJAX serverSide.
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
var DETAIL_PARENT_ID = <?= (int)$modul->id ?>;
var DETAIL_PARENT_LEVEL = <?= (int)$modul->level ?>;
var DETAIL_BASE = "<?= base_url('modul/detail/') ?>";
function escHtml(s){ if(s==null) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
$(function(){
  var table = $('#dtDetail').DataTable({
    processing: true,
    serverSide: true,
    deferRender: true,
    responsive: true,
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    ajax: {
      url: BASE_URL + 'modul/json_detail/' + DETAIL_PARENT_ID,
      type: 'GET',
      dataSrc: function(json){
        // update header badges
        $('#childCountBadge').text(json.recordsTotal + ' item');
        $('#totalInfo').text('('+json.recordsTotal+' item)');
        if(json.recordsTotal===0){
          $('#emptyState').removeClass('d-none');
        } else {
          $('#emptyState').addClass('d-none');
        }
        return json.data;
      },
      error: function(xhr){ console.error('JSON detail error', xhr.responseText); $('#childCountBadge').text('gagal load'); }
    },
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json', processing: '<div class="spinner-border spinner-border-sm text-primary"></div> Loading...' },
    order: [[6,'asc']],
    createdRow: function(row, data){ if(data.status==0){ $(row).addClass('table-light text-muted'); } },
    columns: [
      { data: 'id' },
      { data: 'nama_modul', render: function(d,t,row){
          var badge = row.child_count>0 ? ' <span class="badge bg-info-subtle text-info ms-1">'+row.child_count+' sub</span>' : '';
          return '<i class="'+escHtml(row.icon)+' me-1 text-primary"></i> '+escHtml(row.nama_modul)+badge;
        }
      },
      { data: 'icon', render: function(d,t,row){ return '<code>'+escHtml(row.icon)+'</code>'; } },
      { data: 'url', render: function(d,t,row){ return row.url ? '<code>'+escHtml(row.url)+'</code>' : '-'; } },
      { data: 'level', render: function(d,t,row){ return '<span class="badge bg-primary-subtle text-primary">L'+row.level+'</span>'; } },
      { data: 'tipe', render: function(d,t,row){
          return row.tipe==='dropdown' ? '<span class="badge bg-warning-subtle text-warning"><i class="ri-list-radio me-1"></i>Dropdown</span>' : '<span class="badge bg-success-subtle text-success"><i class="ri-layout-line me-1"></i>Tunggal</span>';
        }
      },
      { data: 'urutan' },
      { data: 'status', render: function(d,t,row){
          return row.status==1 ? '<span class="badge bg-success-subtle text-success">Show</span>' : '<span class="badge bg-secondary">Hide</span>';
        }
      },
      { data: null, orderable:false, searchable:false, render: function(d,t,row){
          var html='';
          if(row.tipe==='dropdown' && row.level < 4){
            var btnCls = row.child_count ? 'btn-info' : 'btn-outline-info';
            var cnt = row.child_count ? ' ('+row.child_count+')' : '';
            html+='<a href="'+BASE_URL+'modul/detail/'+row.id+'" class="btn btn-sm '+btnCls+' me-1"><i class="ri-eye-line me-1"></i>Detail'+cnt+'</a>';
          }
          html+='<a href="'+BASE_URL+'modul/edit/'+row.id+'" class="btn btn-sm btn-light border me-1"><i class="ri-edit-line"></i></a>';
          var toggleCls = row.status ? 'btn-warning' : 'btn-success';
          var toggleIcon = row.status ? 'ri-eye-off-line' : 'ri-eye-on-line';
          html+='<a href="'+BASE_URL+'modul/toggle/'+row.id+'?from=modul/detail/'+DETAIL_PARENT_ID+'" class="btn btn-sm '+toggleCls+' me-1"><i class="'+toggleIcon+'"></i></a>';
          html+='<a href="'+BASE_URL+'modul/delete/'+row.id+'" class="btn btn-sm btn-danger" onclick="return confirm(\'Hapus '+escHtml(row.nama_modul).replace(/\'/g,"\\'")+'?\')"><i class="ri-delete-bin-line"></i></a>';
          return html;
        }
      }
    ]
  });
});
</script>
