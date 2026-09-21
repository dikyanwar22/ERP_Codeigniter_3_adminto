    </div>
    <footer class="footer d-none d-lg-flex align-items-center justify-content-center text-muted small">
        <span>2026 © Adminto - CodeIgniter 3 • Port 3307 • Crafted with <i class="bi bi-heart-fill text-danger mx-1"></i> ERP System</span>
    </footer>
</div>

<?php
$cur = uri_string(); if(empty($cur)) $cur='dashboard';
$isHome = ($cur==='dashboard' || $cur==='' || strpos($cur,'dashboard')===0);
$isProfile = (strpos($cur,'profile')===0 || strpos($cur,'akun')===0);
?>
<!-- Mobile Bottom Bar -->
<nav class="mobile-bottom-bar d-lg-none" aria-label="Mobile bottom navigation">
    <a href="javascript:history.back()" class="bottom-item">
        <i class="ri-arrow-left-line"></i>
        <span>BACK</span>
    </a>
    <a href="#" class="bottom-item" onclick="location.reload();return false;">
        <i class="ri-refresh-line"></i>
        <span>REFRESH</span>
    </a>
    <a href="<?= base_url('dashboard') ?>" class="bottom-item fab-item <?= $isHome ? 'active' : '' ?>">
        <div class="fab-circle <?= $isHome ? 'active' : '' ?>">
            <i class="ri-home-5-fill"></i>
        </div>
        <span>HOME</span>
    </a>
    <a href="<?= base_url('profile') ?>" class="bottom-item <?= $isProfile ? 'active' : '' ?>">
        <i class="ri-user-3-line"></i>
        <span>PROFILE</span>
    </a>
    <a href="<?= base_url('logout') ?>" class="bottom-item">
        <i class="ri-logout-box-r-line"></i>
        <span>LOGOUT</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>
