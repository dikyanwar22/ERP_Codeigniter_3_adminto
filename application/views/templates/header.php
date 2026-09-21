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
<?php
$current = uri_string();
if (empty($current)) $current = 'dashboard';

// kumpulkan semua node dengan url untuk cari best match (longest prefix)
$all_nodes = [];
function collect_nodes($nodes, &$list) {
    foreach ($nodes as $n) {
        if (!empty($n->url)) $list[] = $n;
        if (!empty($n->children)) collect_nodes($n->children, $list);
    }
}
collect_nodes($menus ?? [], $all_nodes);
$best_match = null; $best_len = -1;
foreach ($all_nodes as $n) {
    if ($current === $n->url || strpos($current.'/', $n->url.'/') === 0) {
        $len = strlen($n->url);
        if ($len > $best_len) { $best_len = $len; $best_match = $n; }
    }
}
$best_id = $best_match ? $best_match->id : null;

function is_ancestor_of($ancestor, $target_id) {
    if ($ancestor->id == $target_id) return true;
    if (empty($ancestor->children)) return false;
    foreach ($ancestor->children as $c) {
        if ($c->id == $target_id) return true;
        if (is_ancestor_of($c, $target_id)) return true;
    }
    return false;
}
function render_submenu($children, $best_id) {
    foreach ($children as $c) {
        $hasChild = !empty($c->children);
        $isActive = ($best_id !== null && $c->id == $best_id) || ($hasChild && $best_id !== null && is_ancestor_of($c, $best_id));
        // untuk parent dropdown yang punya anak aktif, highlight parent juga
        if ($hasChild) {
            $activeClass = $isActive ? ' active' : '';
            echo '<li class="dropdown-submenu">';
            echo '<a class="dropdown-item'.$activeClass.'" href="#">';
            echo '<i class="'.$c->icon.' me-2"></i> '.htmlspecialchars($c->nama_modul);
            echo '<i class="ri-arrow-right-s-line ms-auto"></i>';
            echo '</a>';
            echo '<ul class="dropdown-menu">';
            render_submenu($c->children, $best_id);
            echo '</ul>';
            echo '</li>';
        } else {
            $url = $c->url ? base_url($c->url) : '#';
            $activeClass = ($best_id !== null && $c->id == $best_id) ? ' active' : '';
            echo '<li><a class="dropdown-item'.$activeClass.'" href="'.$url.'"><i class="'.$c->icon.' me-2"></i> '.htmlspecialchars($c->nama_modul).'</a></li>';
        }
    }
}
?>
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
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $m): ?>
                    <?php $hasChild = !empty($m->children); ?>
                    <?php $isActiveModul = ($best_id !== null && is_ancestor_of($m, $best_id)); ?>
                    <?php if (!$hasChild): ?>
                        <a href="<?= $m->url ? base_url($m->url) : '#' ?>" class="topnav-item <?= $isActiveModul ? 'active' : '' ?>">
                            <i class="<?= $m->icon ?>"></i>
                            <span><?= htmlspecialchars($m->nama_modul) ?></span>
                        </a>
                    <?php else: ?>
                        <div class="topnav-item dropdown <?= $isActiveModul ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle <?= $isActiveModul ? 'active' : '' ?>" data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="<?= $m->icon ?>"></i>
                                <span><?= htmlspecialchars($m->nama_modul) ?></span>
                                <i class="ri-arrow-down-s-line arrow"></i>
                            </a>
                            <ul class="dropdown-menu" data-bs-popper="static">
                                <?php render_submenu($m->children, $best_id); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="<?= base_url('dashboard') ?>" class="topnav-item active"><i class="ri-dashboard-line"></i><span>Dashboard</span></a>
            <?php endif; ?>
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
                    <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <img src="https://i.pravatar.cc/100?img=33" class="rounded-circle flex-shrink-0" width="40" height="40" alt="">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 small fw-semibold">Putri Ayu</h6>
                                <small class="text-muted" style="font-size:11px;">15m lalu</small>
                            </div>
                            <p class="mb-0 small text-muted text-truncate">Minta katalog produk terbaru</p>
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

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer">
    <div class="drawer-header d-flex justify-content-between align-items-center p-3 border-bottom">
        <span class="fw-bold text-white"><i class="ri-dashboard-line me-2"></i>ADMINTO</span>
        <button class="btn btn-sm btn-outline-light" id="mobileDrawerClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="drawer-body p-2">
        <?php
        // untuk drawer, gunakan best_id juga agar konsisten
        function render_drawer2($menus, $best_id, $level=0) {
            foreach ($menus as $m) {
                $hasChild = !empty($m->children);
                $isActive = ($best_id !== null && is_ancestor_of($m, $best_id));
                if ($hasChild) {
                    $id = 'm_'.$m->id;
                    $activeClass = $isActive ? ' active' : '';
                    echo '<div class="drawer-group">';
                    echo '<a class="drawer-link'.$activeClass.'" data-bs-toggle="collapse" href="#'.$id.'"><i class="'.$m->icon.' me-2"></i> '.htmlspecialchars($m->nama_modul).' <i class="ri-arrow-down-s-line ms-auto"></i></a>';
                    echo '<div class="collapse'.($isActive?' show':'').'" id="'.$id.'">';
                    render_drawer2($m->children, $best_id, $level+1);
                    echo '</div></div>';
                } else {
                    $url = $m->url ? base_url($m->url) : '#';
                    $isActiveItem = ($best_id !== null && $m->id == $best_id);
                    $activeClass = $isActiveItem ? ' active' : '';
                    if ($level==0) echo '<a href="'.$url.'" class="drawer-link'.$activeClass.'"><i class="'.$m->icon.' me-2"></i> '.htmlspecialchars($m->nama_modul).'</a>';
                    else echo '<a href="'.$url.'" class="drawer-sublink'.$activeClass.'">'.htmlspecialchars($m->nama_modul).'</a>';
                }
            }
        }
        if (!empty($menus)) render_drawer2($menus, $best_id);
        ?>
        <hr class="border-secondary my-2">
        <?php $isProfileDrawer = (strpos($current,'profile')===0 || strpos($current,'akun')===0); ?>
        <a href="<?= base_url('profile') ?>" class="drawer-link <?= $isProfileDrawer ? 'active' : '' ?>"><i class="ri-user-line me-2"></i> Profile</a>
        <a href="<?= base_url('logout') ?>" class="drawer-link"><i class="ri-logout-box-line me-2"></i> Logout</a>
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
