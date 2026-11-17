    </div>
    <footer class="footer d-none d-lg-flex align-items-center justify-content-center text-muted small">
        <span>Copyright © <?php echo date('Y'); ?> All Rights Reserved – PT Erp System</span>
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
            <i class="fa-solid fa-house"></i>
        </div>
    </a>
    <a href="<?= base_url('profile') ?>" class="bottom-item <?= $isProfile ? 'active' : '' ?>">
        <i class="ri-user-3-line"></i>
        <span>PROFILE</span>
    </a>
    <a href="<?= base_url('logout') ?>" class="bottom-item logout-btn">
        <i class="ri-logout-box-r-line"></i>
        <span>LOGOUT</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="<?= base_url('assets/js/main.js') ?>"></script>
<script>
// Konfirmasi logout dengan SweetAlert2
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.logout-btn');
    if (!btn) return;
    e.preventDefault();
    var href = btn.getAttribute('href');
    Swal.fire({
        title: 'Yakin ingin logout?',
        text: 'Anda akan keluar dari sistem.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fa5c7c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});
</script>
<script>
(function () {
    var LOGIN_URL = <?= json_encode(base_url('login'), JSON_UNESCAPED_SLASHES) ?>;
    var CHECK_URL = <?= json_encode(base_url('auth/check_session'), JSON_UNESCAPED_SLASHES) ?>;
    var CHECK_INTERVAL = 1500;
    var REDIRECT_DELAY = 1500;

    var checking = false;
    var expired = false;
    var lastCheck = 0;

    function goToLogin() {
        window.location.replace(LOGIN_URL);
    }

    function showExpired() {
        if (expired) return;
        expired = true;

        if (window.Swal) {
            Swal.fire({
                icon: 'warning',
                title: 'Sesi Login Berakhir',
                text: 'Anda akan diarahkan ke halaman login...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                timer: REDIRECT_DELAY,
                timerProgressBar: true
            }).then(goToLogin);
        }
        setTimeout(goToLogin, REDIRECT_DELAY + 300);
    }

    function checkSession(force) {
        if (checking || expired) return;

        var now = Date.now();
        if (!force && (now - lastCheck) < CHECK_INTERVAL) return;
        lastCheck = now;
        checking = true;

        fetch(CHECK_URL, {
            method: 'GET',
            cache: 'no-store',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (!data || data.logged_in !== true) showExpired();
        })
        .catch(function () {
        })
        .then(function () { checking = false; });
    }

    document.addEventListener('scroll', function () { checkSession(false); }, { passive: true, capture: true });
    document.addEventListener('touchmove', function () { checkSession(false); }, { passive: true, capture: true });
    document.addEventListener('touchstart', function () { checkSession(false); }, { passive: true, capture: true });

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) checkSession(true);
    });
    window.addEventListener('focus', function () { checkSession(true); });
    window.addEventListener('pageshow', function () { checkSession(true); });
})();
</script>
</body>
</html>
