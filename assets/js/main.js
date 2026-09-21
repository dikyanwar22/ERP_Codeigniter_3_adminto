// Adminto Top Menu - Main JS
document.addEventListener('DOMContentLoaded', function() {
    // Current date
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }

    // Horizontal scroll buttons
    const topnav = document.getElementById('topnav');
    const leftBtn = document.getElementById('scrollLeft');
    const rightBtn = document.getElementById('scrollRight');

    function updateScrollButtons() {
        if (!topnav || !leftBtn || !rightBtn) return;
        const canScrollLeft = topnav.scrollLeft > 5;
        const canScrollRight = topnav.scrollLeft + topnav.clientWidth < topnav.scrollWidth - 5;
        leftBtn.classList.toggle('d-none', !canScrollLeft);
        rightBtn.classList.toggle('d-none', !canScrollRight);
        // hide both if no overflow
        if (topnav.scrollWidth <= topnav.clientWidth) {
            leftBtn.classList.add('d-none');
            rightBtn.classList.add('d-none');
        }
    }

    if (topnav) {
        topnav.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        window.addEventListener('load', updateScrollButtons);
        // initial check after layout - cek berulang agar pasti terdeteksi overflow modul
        setTimeout(updateScrollButtons, 200);
        setTimeout(updateScrollButtons, 800);
        setTimeout(updateScrollButtons, 1500);
    }

    if (leftBtn) leftBtn.addEventListener('click', () => topnav.scrollBy({ left: -300, behavior: 'smooth' }));
    if (rightBtn) rightBtn.addEventListener('click', () => topnav.scrollBy({ left: 300, behavior: 'smooth' }));

    // Drag to scroll (mouse)
    let isDown = false, startX, scrollLeftPos;
    if (topnav) {
        topnav.addEventListener('mousedown', (e) => {
            isDown = true;
            topnav.classList.add('dragging');
            startX = e.pageX - topnav.offsetLeft;
            scrollLeftPos = topnav.scrollLeft;
        });
        topnav.addEventListener('mouseleave', () => { isDown = false; topnav.classList.remove('dragging'); });
        topnav.addEventListener('mouseup', () => { isDown = false; topnav.classList.remove('dragging'); });
        topnav.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - topnav.offsetLeft;
            const walk = (x - startX) * 1.2;
            topnav.scrollLeft = scrollLeftPos - walk;
        });
        // wheel horizontal
        topnav.addEventListener('wheel', (e) => {
            if (Math.abs(e.deltaX) < Math.abs(e.deltaY) && topnav.scrollWidth > topnav.clientWidth) {
                // convert vertical wheel to horizontal when shift not pressed
                if (e.deltaY !== 0) {
                    topnav.scrollLeft += e.deltaY;
                    e.preventDefault();
                }
            }
        }, { passive: false });
    }

    // Fix: menu terhalang scroll - toggle overflow visible saat dropdown dibuka
    const topHeader = document.querySelector('.top-header');
    const topnavWrapper = document.querySelector('.topnav-wrapper');
    document.querySelectorAll('.topnav-item.dropdown').forEach(dd => {
        dd.addEventListener('show.bs.dropdown', () => {
            if (topnav) topnav.classList.add('dropdown-open');
            if (topnavWrapper) topnavWrapper.classList.add('dropdown-open');
            if (topHeader) topHeader.classList.add('dropdown-open');
        });
        // auto flip if would overflow viewport
        dd.addEventListener('shown.bs.dropdown', () => {
            const menu = dd.querySelector('.dropdown-menu');
            if (!menu) return;
            // reset
            menu.style.left = '';
            menu.style.right = '';
            const rect = menu.getBoundingClientRect();
            if (rect.right > window.innerWidth - 8) {
                menu.style.left = 'auto';
                menu.style.right = '0';
                menu.style.transform = 'none';
            }
        });
        dd.addEventListener('hide.bs.dropdown', () => {
            // delay to allow bootstrap hide animation
            setTimeout(() => {
                const anyOpen = document.querySelector('.topnav-item.dropdown .dropdown-menu.show');
                if (!anyOpen) {
                    if (topnav) topnav.classList.remove('dropdown-open');
                    if (topnavWrapper) topnavWrapper.classList.remove('dropdown-open');
                    if (topHeader) topHeader.classList.remove('dropdown-open');
                }
            }, 10);
        });
    });
    // ===== Submenu (menu di dalam menu) - 2 & 3 level - FIX klik langsung hilang =====
    document.querySelectorAll('.dropdown-submenu > .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            const parent = this.parentElement;
            const isOpen = parent.classList.contains('show');
            // close siblings at same level
            const siblings = parent.parentElement.querySelectorAll(':scope > .dropdown-submenu.show');
            siblings.forEach(s => { if (s !== parent) s.classList.remove('show'); });
            // toggle current - pakai class show agar tetap terbuka setelah hover hilang
            if (isOpen) {
                parent.classList.remove('show');
                // also close children
                parent.querySelectorAll('.dropdown-submenu.show').forEach(c => c.classList.remove('show'));
            } else {
                parent.classList.add('show');
                // flip if would overflow viewport
                const submenu = parent.querySelector(':scope > .dropdown-menu');
                if (submenu) {
                    submenu.style.visibility = 'hidden';
                    submenu.style.display = 'block';
                    const rect = submenu.getBoundingClientRect();
                    submenu.style.visibility = '';
                    submenu.style.display = '';
                    if (rect.right > window.innerWidth - 10) {
                        parent.classList.add('flip-left');
                    } else {
                        parent.classList.remove('flip-left');
                    }
                }
            }
            // keep parent dropdown open (prevent Bootstrap auto-close)
            const topDropdown = parent.closest('.topnav-item.dropdown');
            if (topDropdown) {
                if (topnav) topnav.classList.add('dropdown-open');
                if (topnavWrapper) topnavWrapper.classList.add('dropdown-open');
                if (topHeader) topHeader.classList.add('dropdown-open');
            }
        });
    });
    // leaf item (menu tanpa submenu) -> tutup parent dropdown setelah diklik
    document.querySelectorAll('.topnav-item.dropdown .dropdown-menu .dropdown-item').forEach(leaf => {
        if (leaf.parentElement.classList.contains('dropdown-submenu')) return; // skip toggle items
        leaf.addEventListener('click', function(e) {
            // jangan stopPropagation agar Bootstrap bisa close? kita close manual karena auto-close=outside
            const topDropdown = this.closest('.topnav-item.dropdown');
            if (topDropdown) {
                const toggle = topDropdown.querySelector('[data-bs-toggle="dropdown"]');
                if (toggle) {
                    const inst = bootstrap.Dropdown.getInstance(toggle) || new bootstrap.Dropdown(toggle);
                    // delay sedikit agar navigasi terasa
                    setTimeout(() => inst.hide(), 100);
                }
            }
        });
    });
    // close all submenus when parent dropdown closes
    document.querySelectorAll('.topnav-item.dropdown').forEach(dd => {
        dd.addEventListener('hide.bs.dropdown', () => {
            dd.querySelectorAll('.dropdown-submenu.show').forEach(s => s.classList.remove('show'));
            dd.querySelectorAll('.dropdown-submenu.flip-left').forEach(s => s.classList.remove('flip-left'));
        });
    });
    // click outside closes submenus
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-submenu')) {
            // hanya tutup submenu, jangan tutup parent dropdown (biar parent tetap open sampai leaf diklik)
            const insideDropdown = e.target.closest('.topnav-item.dropdown');
            if (!insideDropdown) {
                document.querySelectorAll('.dropdown-submenu.show').forEach(s => s.classList.remove('show'));
            } else {
                // klik di dalam dropdown tapi bukan di submenu -> tutup sibling submenus
                // biarkan yang diklik tetap, jadi tidak tutup semua
            }
        }
        if (!e.target.closest('.topnav-item.dropdown')) {
            const anyOpen = document.querySelector('.topnav-item.dropdown .dropdown-menu.show');
            if (!anyOpen) {
                if (topnav) topnav.classList.remove('dropdown-open');
                if (topnavWrapper) topnavWrapper.classList.remove('dropdown-open');
                if (topHeader) topHeader.classList.remove('dropdown-open');
            }
            document.querySelectorAll('.dropdown-submenu.show').forEach(s => s.classList.remove('show'));
        }
    });

    // Bottom bar actions
    const bottomRefresh = document.getElementById('bottomRefresh');
    if (bottomRefresh) bottomRefresh.addEventListener('click', function(e) { e.preventDefault(); location.reload(); });

    // Mobile drawer
    const drawer = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('drawerOverlay');
    const toggle = document.getElementById('mobileMenuToggle');
    const close = document.getElementById('mobileDrawerClose');
    function openDrawer() { drawer.classList.add('show'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeDrawer() { drawer.classList.remove('show'); overlay.classList.remove('show'); document.body.style.overflow = ''; }
    if (toggle) toggle.addEventListener('click', openDrawer);
    if (close) close.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);

    // ApexCharts
    const revenueOptions = {
        series: [
            { name: 'Revenue', data: [31, 40, 28, 51, 42, 109, 100, 120, 80, 95, 110, 130] },
            { name: 'Orders', data: [11, 32, 45, 32, 34, 52, 41, 55, 42, 60, 75, 85] }
        ],
        chart: { height: 350, type: 'area', toolbar: { show: false }, fontFamily: 'Nunito, sans-serif' },
        colors: ['#727cf5', '#0acf97'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.05 } },
        xaxis: { categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] },
        yaxis: { labels: { formatter: val => '$' + val + 'k' } },
        tooltip: { y: { formatter: val => '$' + val + 'k' } },
        legend: { position: 'top', horizontalAlign: 'right' },
        grid: { borderColor: '#f1f3fa', strokeDashArray: 4 }
    };
    const revenueEl = document.querySelector("#revenueChart");
    if (revenueEl && typeof ApexCharts !== 'undefined') new ApexCharts(revenueEl, revenueOptions).render();

    const donutOptions = {
        series: [42, 28, 15, 15],
        chart: { type: 'donut', height: 280, fontFamily: 'Nunito, sans-serif' },
        labels: ['Electronics', 'Fashion', 'Groceries', 'Others'],
        colors: ['#727cf5', '#0acf97', '#fa5c7c', '#ffbc00'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%' } } },
        stroke: { width: 0 }
    };
    const donutEl = document.querySelector("#donutChart");
    if (donutEl && typeof ApexCharts !== 'undefined') new ApexCharts(donutEl, donutOptions).render();
});
