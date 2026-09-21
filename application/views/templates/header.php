<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Adminto - Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<header class="top-header">
    <a href="<?= base_url('dashboard') ?>" class="logo-box">
        <i class="ri-dashboard-line"></i>
        <span>ADMINTO</span>
    </a>
    <button class="btn btn-dark d-lg-none ms-2" id="mobileMenuToggle">
        <i class="bi bi-list fs-4"></i>
    </button>
    <div class="topnav-wrapper flex-grow-1 mx-3">
        <button class="scroll-btn scroll-left d-none" id="scrollLeft" aria-label="Scroll left">
            <i class="bi bi-chevron-left"></i>
        </button>
        <nav class="topnav" id="topnav">
            <!-- diisi via jQuery AJAX dari /api/menu -->
        </nav>
        <button class="scroll-btn scroll-right" id="scrollRight" aria-label="Scroll right">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
    <div class="header-actions d-flex align-items-center gap-2">
        <div class="dropdown">
            <button class="btn btn-dark position-relative btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Pesan">
                <i class="ri-message-3-line"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size:9px;">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0" style="width:340px; margin-top:10px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top">
                    <h6 class="mb-0 fw-bold"><i class="ri-message-3-line me-2 text-primary"></i>Pesan</h6>
                    <span class="badge bg-success">3 baru</span>
                </div>
                <div class="list-group list-group-flush" style="max-height:320px; overflow-y:auto;">
                    <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <img src="https://i.pravatar.cc/100?img=15" class="rounded-circle flex-shrink-0" width="40" height="40" alt="">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 small fw-semibold">Budi Santoso</h6>
                                <small class="text-muted" style="font-size:11px;">2m lalu</small>
                            </div>
                            <p class="mb-0 small text-muted text-truncate">Apakah pesanan sudah dikirim?</p>
                        </div>
                    </a>
                </div>
                <div class="p-2 border-top text-center">
                    <a href="#" class="btn btn-sm btn-primary w-100">Lihat Semua Pesan</a>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-dark position-relative btn-sm me-1" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                <i class="ri-notification-3-line"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">4</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0" style="width:360px; margin-top:10px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top">
                    <h6 class="mb-0 fw-bold"><i class="ri-notification-3-line me-2 text-danger"></i>Notifikasi</h6>
                    <a href="#" class="small text-primary text-decoration-none">Tandai dibaca</a>
                </div>
                <div class="list-group list-group-flush" style="max-height:360px; overflow-y:auto;">
                    <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;"><i class="ri-shopping-cart-line"></i></div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 small fw-semibold">Pesanan baru masuk</h6>
                            <p class="mb-1 small text-muted">#ADM-0142 - $2,499</p>
                            <small class="text-muted" style="font-size:11px;">5 menit lalu</small>
                        </div>
                    </a>
                </div>
                <div class="p-2 border-top d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-light flex-grow-1">Pengaturan</a>
                    <a href="#" class="btn btn-sm btn-primary flex-grow-1">Lihat Semua</a>
                </div>
            </div>
        </div>
        <div class="dropdown ms-1">
            <a class="nav-link d-flex align-items-center gap-2 text-white" href="#" data-bs-toggle="dropdown">
                <img src="https://i.pravatar.cc/100?img=12" class="rounded-circle" width="32" height="32" alt="user">
                <span class="d-none d-md-inline small fw-semibold"><?= htmlspecialchars($user['nama']) ?></span>
                <small class="d-none d-md-inline text-white-50" style="font-size:11px;"><?= htmlspecialchars($user['jabatan_nama']) ?></small>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="ri-user-line me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="<?= base_url('akun') ?>"><i class="ri-team-line me-2"></i>Daftar Akun</a></li>
                <li><a class="dropdown-item" href="<?= base_url('modul') ?>"><i class="ri-apps-2-line me-2"></i>Kelola Modul</a></li>
                <li><a class="dropdown-item" href="<?= base_url('akses') ?>"><i class="ri-shield-keyhole-line me-2"></i>Akses</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="ri-logout-box-line me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Mobile Drawer - diisi via jQuery -->
<div class="mobile-drawer" id="mobileDrawer">
    <div class="drawer-header d-flex justify-content-between align-items-center p-3 border-bottom">
        <span class="fw-bold text-white"><i class="ri-dashboard-line me-2"></i>ADMINTO</span>
        <button class="btn btn-sm btn-outline-light" id="mobileDrawerClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="drawer-body p-2" id="drawerBody">
        <!-- diisi via jQuery -->
    </div>
</div>
<div class="drawer-overlay" id="drawerOverlay"></div>

<div class="main-content">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

<!-- jQuery render menu full via API -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
var BASE_URL = "<?= base_url() ?>";
var CURRENT_URL = "<?= uri_string() ?>";
if (!CURRENT_URL) CURRENT_URL = "dashboard";
function escHtmlMenu(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
// Render top menu via JSON agar tidak lelet (1x AJAX, bukan PHP loop per request)
$(function(){
  $.getJSON(BASE_URL + 'api/menu', function(menus){
    if(!menus || !menus.length){
      $('#topnav').html('<a href="'+BASE_URL+'dashboard" class="topnav-item active"><i class="ri-dashboard-line"></i><span>Dashboard</span></a>');
      return;
    }
    // cari best_match id (longest prefix url)
    var all = [];
    function collect(nodes){ nodes.forEach(function(n){ if(n.url) all.push(n); if(n.children) collect(n.children); }); }
    collect(menus);
    var best=null, bestLen=-1;
    all.forEach(function(n){
      if(CURRENT_URL===n.url || (CURRENT_URL+'/').indexOf(n.url+'/')===0){
        if(n.url.length>bestLen){ bestLen=n.url.length; best=n; }
      }
    });
    var bestId = best ? best.id : null;
    function isAncestor(node, tid){
      if(!node || !node.children) return false;
      for(var i=0;i<node.children.length;i++){
        var c=node.children[i];
        if(c.id==tid) return true;
        if(isAncestor(c, tid)) return true;
      }
      return false;
    }
    function isActive(node){
      if(bestId===null) return false;
      if(node.id==bestId) return true;
      return isAncestor(node, bestId);
    }
    function renderSub(children){
      var h='';
      children.forEach(function(c){
        var hasChild = c.children && c.children.length;
        var active = isActive(c) || c.id==bestId;
        if(hasChild){
          h+='<li class="dropdown-submenu"><a class="dropdown-item'+(active?' active':'')+'" href="#"><i class="'+escHtmlMenu(c.icon)+' me-2"></i> '+escHtmlMenu(c.nama_modul)+' <i class="ri-arrow-right-s-line ms-auto"></i></a><ul class="dropdown-menu">';
          h+=renderSub(c.children);
          h+='</ul></li>';
        } else {
          var url = c.url ? BASE_URL + c.url : '#';
          h+='<li><a class="dropdown-item'+(c.id==bestId?' active':'')+'" href="'+url+'"><i class="'+escHtmlMenu(c.icon)+' me-2"></i> '+escHtmlMenu(c.nama_modul)+'</a></li>';
        }
      });
      return h;
    }
    var topHtml='';
    menus.forEach(function(m){
      var hasChild = m.children && m.children.length;
      var activeModul = isActive(m);
      if(!hasChild){
        var url = m.url ? BASE_URL + m.url : '#';
        topHtml+='<a href="'+url+'" class="topnav-item '+(activeModul?'active':'')+'"><i class="'+escHtmlMenu(m.icon)+'"></i><span>'+escHtmlMenu(m.nama_modul)+'</span></a>';
      } else {
        topHtml+='<div class="topnav-item dropdown '+(activeModul?'active':'')+'"><a href="#" class="dropdown-toggle '+(activeModul?'active':'')+'" data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside" aria-expanded="false"><i class="'+escHtmlMenu(m.icon)+'"></i><span>'+escHtmlMenu(m.nama_modul)+'</span><i class="ri-arrow-down-s-line arrow"></i></a><ul class="dropdown-menu" data-bs-popper="static">'+renderSub(m.children)+'</ul></div>';
      }
    });
    $('#topnav').html(topHtml);
    // render drawer
    function renderDrawer(nodes, level){
      var h='';
      nodes.forEach(function(m){
        var hasChild = m.children && m.children.length;
        var active = isActive(m);
        if(hasChild){
          var id='m_'+m.id;
          h+='<div class="drawer-group"><a class="drawer-link '+(active?'active':'')+'" data-bs-toggle="collapse" href="#'+id+'"><i class="'+escHtmlMenu(m.icon)+' me-2"></i> '+escHtmlMenu(m.nama_modul)+' <i class="ri-arrow-down-s-line ms-auto"></i></a><div class="collapse '+(active?'show':'')+'" id="'+id+'">';
          h+=renderDrawer(m.children, level+1);
          h+='</div></div>';
        } else {
          var url = m.url ? BASE_URL + m.url : '#';
          var activeItem = (m.id==bestId);
          if(level==0) h+='<a href="'+url+'" class="drawer-link '+(activeItem?'active':'')+'"><i class="'+escHtmlMenu(m.icon)+' me-2"></i> '+escHtmlMenu(m.nama_modul)+'</a>';
          else h+='<a href="'+url+'" class="drawer-sublink '+(activeItem?'active':'')+'">'+escHtmlMenu(m.nama_modul)+'</a>';
        }
      });
      return h;
    }
    var drawerHtml = renderDrawer(menus,0);
    drawerHtml+='<hr class="border-secondary my-2">';
    var isProfile = CURRENT_URL.indexOf('profile')===0 || CURRENT_URL.indexOf('akun')===0;
    drawerHtml+='<a href="'+BASE_URL+'profile" class="drawer-link '+(isProfile?'active':'')+'"><i class="ri-user-line me-2"></i> Profile</a>';
    drawerHtml+='<a href="'+BASE_URL+'logout" class="drawer-link"><i class="ri-logout-box-line me-2"></i> Logout</a>';
    $('#drawerBody').html(drawerHtml);
    // --- re-bind logic untuk topnav yang baru di-render via AJAX ---
    (function(){
      var topnav = document.getElementById('topnav');
      var leftBtn = document.getElementById('scrollLeft');
      var rightBtn = document.getElementById('scrollRight');
      var headerActions = document.querySelector('.header-actions');
      function updScroll(){
        if(!topnav||!leftBtn||!rightBtn) return;
        var canL = topnav.scrollLeft > 5;
        var canR = topnav.scrollLeft + topnav.clientWidth < topnav.scrollWidth - 5;
        leftBtn.classList.toggle('d-none', !canL);
        rightBtn.classList.toggle('d-none', !canR);
        if(topnav.scrollWidth <= topnav.clientWidth){ leftBtn.classList.add('d-none'); rightBtn.classList.add('d-none'); }
      }
      function scrollToActive(){
        if(!topnav) return;
        var active = topnav.querySelector('.topnav-item.active, .dropdown-item.active');
        var target=null;
        if(active){
          if(active.classList.contains('dropdown-item')) target = active.closest('.topnav-item.dropdown');
          else target = active;
          if(target){ var left = target.offsetLeft - (topnav.clientWidth/2)+(target.clientWidth/2); topnav.scrollTo({left:left, behavior:'smooth'}); }
        }
      }
      if(topnav){
        topnav.addEventListener('scroll', updScroll);
        window.addEventListener('resize', updScroll);
        setTimeout(updScroll, 200); setTimeout(updScroll, 800); setTimeout(updScroll, 1500);
        setTimeout(scrollToActive, 400);
      }
      if(leftBtn && topnav) leftBtn.addEventListener('click', function(){ topnav.scrollBy({left:-300, behavior:'smooth'}); });
      if(rightBtn && topnav) rightBtn.addEventListener('click', function(){ topnav.scrollBy({left:300, behavior:'smooth'}); });
      // dropdown fixed positioning
      document.querySelectorAll('.topnav-item.dropdown').forEach(function(dd){
        dd.addEventListener('show.bs.dropdown', function(){
          var toggle = dd.querySelector('[data-bs-toggle="dropdown"]');
          var menu = dd.querySelector('.dropdown-menu');
          if(!toggle||!menu) return;
          menu.classList.add('fixed-dropdown');
          var rect = toggle.getBoundingClientRect();
          menu.style.setProperty('top', (rect.bottom+6)+'px','important');
          menu.style.setProperty('left', rect.left+'px','important');
          menu.style.setProperty('right','auto','important');
          menu.style.setProperty('transform','none','important');
          requestAnimationFrame(function(){
            var mRect = menu.getBoundingClientRect();
            var hRect = headerActions ? headerActions.getBoundingClientRect():null;
            var viewportRight = window.innerWidth-8;
            if((hRect && mRect.right > hRect.left-8) || mRect.right > viewportRight){
              var w=mRect.width; var newLeft=rect.right-w;
              if(newLeft<8) newLeft=8;
              if(hRect && newLeft+w > hRect.left-8) newLeft = hRect.left-w-12;
              menu.style.setProperty('left', newLeft+'px','important');
            }
          });
        });
        dd.addEventListener('hide.bs.dropdown', function(){
          var menu = dd.querySelector('.dropdown-menu');
          if(menu) setTimeout(function(){ menu.classList.remove('fixed-dropdown'); menu.style.removeProperty('top'); menu.style.removeProperty('left'); menu.style.removeProperty('right'); menu.style.removeProperty('transform'); },200);
          dd.querySelectorAll('.dropdown-submenu.show').forEach(function(s){ s.classList.remove('show'); });
          dd.querySelectorAll('.dropdown-submenu.flip-left').forEach(function(s){ s.classList.remove('flip-left'); });
        });
      });
      // submenu click toggle
      document.querySelectorAll('.dropdown-submenu > .dropdown-item').forEach(function(item){
        item.addEventListener('click', function(e){
          e.preventDefault(); e.stopPropagation();
          var parent=this.parentElement;
          var isOpen=parent.classList.contains('show');
          parent.parentElement.querySelectorAll(':scope > .dropdown-submenu.show').forEach(function(s){ if(s!==parent) s.classList.remove('show'); });
          if(isOpen){ parent.classList.remove('show'); parent.querySelectorAll('.dropdown-submenu.show').forEach(function(c){ c.classList.remove('show'); }); }
          else {
            parent.classList.add('show');
            var submenu=parent.querySelector(':scope > .dropdown-menu');
            if(submenu){
              submenu.style.visibility='hidden'; submenu.style.display='block';
              var rect=submenu.getBoundingClientRect();
              submenu.style.visibility=''; submenu.style.display='';
              if(rect.right > window.innerWidth-10) parent.classList.add('flip-left'); else parent.classList.remove('flip-left');
            }
          }
        });
      });
      // leaf close
      document.querySelectorAll('.topnav-item.dropdown .dropdown-menu .dropdown-item').forEach(function(leaf){
        if(leaf.parentElement.classList.contains('dropdown-submenu')) return;
        leaf.addEventListener('click', function(){
          var topDropdown=this.closest('.topnav-item.dropdown');
          if(topDropdown){
            var toggle=topDropdown.querySelector('[data-bs-toggle="dropdown"]');
            if(toggle){ var inst=bootstrap.Dropdown.getInstance(toggle)||new bootstrap.Dropdown(toggle); setTimeout(function(){ inst.hide(); },100); }
          }
        });
      });
    })();
    $(document).trigger('menu-loaded');
  }).fail(function(){ $('#topnav').html('<a href="'+BASE_URL+'dashboard" class="topnav-item"><i class="ri-dashboard-line"></i><span>Dashboard</span></a>'); });
});
</script>
