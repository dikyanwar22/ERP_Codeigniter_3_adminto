<div class="d-flex justify-content-between align-items-center py-3">
  <div>
    <h4 class="fw-bold mb-1">Kelola Modul</h4>
    <small class="text-muted">Semua modul (level 1) via JSON + jQuery • ServerSide - tidak lelet</small>
  </div>
  <a href="<?= base_url('modul/create') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i> Tambah Modul</a>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="table-responsive">
      <table id="dtModul" class="table table-hover table-striped table-bordered w-100">
        <thead class="bg-light">
          <tr>
            <th width="50">ID</th>
            <th>Nama Modul</th>
            <th>Icon</th>
            <th>URL</th>
            <th>Tipe</th>
            <th>Urut</th>
            <th>Status</th>
            <th width="260">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-info small mt-3">
  <strong>Cara pakai:</strong> Klik <span class="badge bg-info">Detail</span> pada modul untuk melihat menu terkait. Data diambil via <code>GET <?= base_url('modul/json') ?></code> memakai jQuery AJAX (serverSide) - cepat walaupun ribuan modul.
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
function escHtml(s){ if(s==null) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
$(function(){
  var table = $('#dtModul').DataTable({
    processing: true,
    serverSide: true,
    deferRender: true,
    responsive: true,
    pageLength: 10,
    lengthMenu: [10,25,50,100],
    ajax: {
      url: BASE_URL + 'modul/json',
      type: 'GET',
      error: function(xhr){ console.error('JSON error', xhr.responseText); alert('Gagal load modul: '+ xhr.status); }
    },
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json', processing: '<div class="spinner-border spinner-border-sm text-primary"></div> Loading...' },
    order: [[5,'asc']],
    columns: [
      { data: 'id', width: '50px' },
      { data: 'nama_modul', render: function(d, t, row){
          var badge = row.child_count > 0 ? ' <span class="badge bg-info-subtle text-info ms-1">'+row.child_count+' menu</span>' : '';
          return '<i class="'+escHtml(row.icon)+' me-1 text-primary"></i> <strong>'+escHtml(row.nama_modul)+'</strong>'+badge;
        }
      },
      { data: 'icon', render: function(d, t, row){ return '<code>'+escHtml(row.icon)+'</code>'; } },
      { data: 'url', render: function(d, t, row){ return row.url ? '<code>'+escHtml(row.url)+'</code>' : '<span class="text-muted">-</span>'; } },
      { data: 'tipe', render: function(d, t, row){
          return row.tipe==='dropdown' ? '<span class="badge bg-warning-subtle text-warning">Dropdown</span>' : '<span class="badge bg-success-subtle text-success">Tunggal</span>';
        }
      },
      { data: 'urutan' },
      { data: 'status', render: function(d, t, row){
          return row.status==1 ? '<span class="badge bg-success-subtle text-success">Show (1)</span>' : '<span class="badge bg-secondary">Hide (0)</span>';
        }
      },
      { data: null, orderable: false, searchable: false, render: function(d, t, row){
          var html = '';
          if(row.tipe==='dropdown'){
            var btnCls = row.child_count ? 'btn-info' : 'btn-outline-info';
            var cnt = row.child_count ? ' ('+row.child_count+')' : '';
            html += '<a href="'+BASE_URL+'modul/detail/'+row.id+'" class="btn btn-sm '+btnCls+' me-1"><i class="ri-eye-line me-1"></i>Detail'+cnt+'</a>';
          }
          html += '<a href="'+BASE_URL+'modul/edit/'+row.id+'" class="btn btn-sm btn-light border me-1"><i class="ri-edit-line"></i></a>';
          var toggleCls = row.status ? 'btn-warning' : 'btn-success';
          var toggleIcon = row.status ? 'ri-eye-off-line' : 'ri-eye-on-line';
          html += '<a href="'+BASE_URL+'modul/toggle/'+row.id+'?from=modul" class="btn btn-sm '+toggleCls+' me-1" title="Toggle hide/show"><i class="'+toggleIcon+'"></i></a>';
          html += '<a href="'+BASE_URL+'modul/delete/'+row.id+'" class="btn btn-sm btn-danger" onclick="return confirm(\'Hapus modul '+escHtml(row.nama_modul).replace(/\'/g,"\\'")+'?\')"><i class="ri-delete-bin-line"></i></a>';
          return html;
        }
      }
    ]
  });
});
</script>
