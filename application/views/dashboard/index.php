<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
  <div>
    <h4 class="mb-1 fw-bold">Welcome back, <?= htmlspecialchars($user['nama']) ?>! 👋</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Adminto</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
    <small class="text-muted"><?= htmlspecialchars($user['jabatan_nama']) ?> • <?= htmlspecialchars($user['email']) ?> • NIK: <?= htmlspecialchars($user['nik']) ?> • Status: <span class="badge bg-<?= $user['status']? 'success':'danger' ?>"><?= $user['status']? 'Aktif':'Nonaktif' ?></span></small>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= base_url('modul') ?>" class="btn btn-primary btn-sm"><i class="ri-apps-2-line me-1"></i> Kelola Modul</a>
    <button class="btn btn-light btn-sm border bg-white"><i class="bi bi-calendar3 me-1"></i> <span id="currentDate"></span></button>
  </div>
</div>

<div class="row g-3">
  <div class="col-xl-3 col-md-6">
    <div class="card widget-card border-0 shadow-sm"><div class="card-body">
      <div class="d-flex align-items-center">
        <div class="widget-icon bg-primary bg-opacity-10 text-primary rounded-3"><i class="ri-money-dollar-circle-line fs-3"></i></div>
        <div class="ms-3 flex-grow-1"><h5 class="text-muted fw-normal mt-0 mb-1 small">Total Revenue</h5><h3 class="mb-0">$58,947</h3></div>
        <div class="text-end"><span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up-short"></i> 12.5%</span></div>
      </div>
      <div class="progress mt-3" style="height:4px;"><div class="progress-bar bg-primary" style="width:75%"></div></div>
    </div></div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card widget-card border-0 shadow-sm"><div class="card-body">
      <div class="d-flex align-items-center">
        <div class="widget-icon bg-success bg-opacity-10 text-success rounded-3"><i class="ri-shopping-bag-line fs-3"></i></div>
        <div class="ms-3 flex-grow-1"><h5 class="text-muted fw-normal mt-0 mb-1 small">Total Orders</h5><h3 class="mb-0">1,845</h3></div>
        <div class="text-end"><span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-down-short"></i> 3.2%</span></div>
      </div>
      <div class="progress mt-3" style="height:4px;"><div class="progress-bar bg-success" style="width:60%"></div></div>
    </div></div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card widget-card border-0 shadow-sm"><div class="card-body">
      <div class="d-flex align-items-center">
        <div class="widget-icon bg-warning bg-opacity-10 text-warning rounded-3"><i class="ri-user-add-line fs-3"></i></div>
        <div class="ms-3 flex-grow-1"><h5 class="text-muted fw-normal mt-0 mb-1 small">Users Aktif</h5><h3 class="mb-0"><?= $this->db->where('status',1)->count_all_results('ci_users') ?></h3></div>
        <div class="text-end"><span class="badge bg-success-subtle text-success">aktif</span></div>
      </div>
      <div class="progress mt-3" style="height:4px;"><div class="progress-bar bg-warning" style="width:85%"></div></div>
    </div></div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card widget-card border-0 shadow-sm"><div class="card-body">
      <div class="d-flex align-items-center">
        <div class="widget-icon bg-info bg-opacity-10 text-info rounded-3"><i class="ri-apps-2-line fs-3"></i></div>
        <div class="ms-3 flex-grow-1"><h5 class="text-muted fw-normal mt-0 mb-1 small">Modul Show</h5><h3 class="mb-0"><?= $this->db->where('status',1)->count_all_results('ci_modul') ?></h3></div>
        <div class="text-end"><span class="badge bg-success-subtle text-success">show</span></div>
      </div>
      <div class="progress mt-3" style="height:4px;"><div class="progress-bar bg-info" style="width:70%"></div></div>
    </div></div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-xl-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Revenue Analytics</h5>
        <div class="d-flex gap-1">
          <button class="btn btn-sm btn-primary">Monthly</button>
          <button class="btn btn-sm btn-light">Weekly</button>
        </div>
      </div>
      <div class="card-body"><div id="revenueChart"></div></div>
    </div>
  </div>
  <div class="col-xl-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white border-bottom"><h5 class="card-title mb-0">Sales by Category</h5></div>
      <div class="card-body">
        <div id="donutChart"></div>
        <div class="row text-center mt-3 g-2">
          <div class="col-4"><h5 class="mb-1">42%</h5><p class="text-muted small mb-0">Electronics</p></div>
          <div class="col-4"><h5 class="mb-1">28%</h5><p class="text-muted small mb-0">Fashion</p></div>
          <div class="col-4"><h5 class="mb-1">30%</h5><p class="text-muted small mb-0">Others</p></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mt-3">
  <div class="card-header bg-white"><h5 class="mb-0">Info Sistem</h5></div>
  <div class="card-body small">
    <ul class="mb-0">
      <li>Login bisa pakai <strong>Email</strong> atau <strong>NIK</strong> (cek <code>ci_users</code>)</li>
      <li>Status 0 = tidak bisa login (contoh: nonaktif@adminto.com / 1234567890123459)</li>
      <li>Hanya modul <strong>status 1 (show)</strong> yang muncul di top menu dan bisa diakses (via <code>MY_Controller</code>)</li>
      <li>Akses per jabatan di <a href="<?= base_url('akses') ?>">Kelola Akses</a> (checkbox)</li>
      <li>Port DB: <code>127.0.0.1:3307</code> — CI 3.1.13 — PHP <?= PHP_VERSION ?></li>
    </ul>
  </div>
</div>
