<?php
$bcCurrent = trim(uri_string(), '/');
$bcSegments = $bcCurrent === '' ? [] : explode('/', $bcCurrent);

$bcNodes = [];
$bcByUrl = [];

$bcCollect = function ($items) use (&$bcCollect, &$bcNodes, &$bcByUrl) {
    foreach ((array) $items as $node) {
        if (!is_object($node) && !is_array($node)) continue;

        $arr = (array) $node;
        $id  = isset($arr['id']) ? (string) $arr['id'] : '';
        $url = isset($arr['url']) ? trim((string) $arr['url'], '/') : '';

        if ($id !== '') $bcNodes[$id] = $arr;
        if ($url !== '') $bcByUrl[$url] = $arr;
        if (!empty($arr['children'])) $bcCollect($arr['children']);
    }
};
$bcCollect(isset($menus) ? $menus : []);

$bcMatch = null;
$bcExtra = [];
for ($i = count($bcSegments); $i >= 1; $i--) {
    $candidate = implode('/', array_slice($bcSegments, 0, $i));
    if (isset($bcByUrl[$candidate])) {
        $bcMatch = $bcByUrl[$candidate];
        $bcExtra = array_slice($bcSegments, $i);
        break;
    }
}

$bcTrail = [];
if ($bcMatch) {
    $cur   = $bcMatch;
    $guard = 0;
    while ($cur && $guard++ < 20) {
        array_unshift($bcTrail, $cur);
        $pid = isset($cur['parent_id']) ? (string) $cur['parent_id'] : '0';
        if ($pid === '' || $pid === '0') break;
        $cur = isset($bcNodes[$pid]) ? $bcNodes[$pid] : null;
    }
}

if (!$bcTrail) {
    $fallback = isset($title) && $title !== ''
        ? $title
        : ucwords(str_replace(['-', '_'], ' ', isset($bcSegments[0]) ? $bcSegments[0] : 'Halaman'));
    $bcTrail[] = [
        'nama_modul' => $fallback,
        'url'        => $bcCurrent,
        'icon'       => 'ri-menu-line',
    ];
    $bcExtra = [];
}

$bcActionLabel = [
    'create' => 'Tambah', 'tambah' => 'Tambah', 'add' => 'Tambah',
    'edit'   => 'Edit',   'ubah'   => 'Ubah',
    'detail' => 'Detail', 'show'   => 'Detail', 'view' => 'Detail',
    'delete' => 'Hapus',  'hapus'  => 'Hapus',
];
foreach ($bcExtra as $seg) {
    if ($seg === '' || ctype_digit($seg)) continue;
    $label = isset($bcActionLabel[$seg])
        ? $bcActionLabel[$seg]
        : ucwords(str_replace(['-', '_'], ' ', $seg));
    $bcTrail[] = ['nama_modul' => $label, 'url' => '', 'icon' => 'ri-file-line'];
}

$bcLast = count($bcTrail) - 1;
?>
<style>
.breadcrumb-bar{
  display:flex; justify-content:flex-end; align-items:center;
  margin-top:16px; margin-bottom:20px;
}
.breadcrumb-bar .breadcrumb{
  background:#fff; padding:8px 14px; margin:0;
  border:1px solid #e9ecef; border-radius:8px;
  box-shadow:0 2px 8px rgba(42,49,66,.05);
  font-size:12.5px;
}
.breadcrumb-bar .breadcrumb-item{ display:inline-flex; align-items:center; gap:6px; }
.breadcrumb-bar .breadcrumb-item + .breadcrumb-item::before{
  content:'\203A'; color:#adb5bd; padding:0 8px; font-size:14px;
}
.breadcrumb-bar i{ color:#727cf5; font-size:14px; line-height:1; }
.breadcrumb-bar a{ color:#6c757d; text-decoration:none; }
.breadcrumb-bar a:hover{ color:#727cf5; }
.breadcrumb-bar .active{ color:#313a46; font-weight:700; }
@media (max-width: 575.98px){
  .breadcrumb-bar{ justify-content:flex-start; margin-top:12px; margin-bottom:14px; }
  .breadcrumb-bar .breadcrumb{ padding:7px 10px; font-size:11.5px; }
}
</style>

<nav aria-label="breadcrumb" class="breadcrumb-bar">
  <ol class="breadcrumb mb-0">
    <?php foreach ($bcTrail as $bcIndex => $bcItem): ?>
      <?php
      $bcIsLast = ($bcIndex === $bcLast);
      $bcName   = isset($bcItem['nama_modul']) ? $bcItem['nama_modul'] : '';
      $bcUrl    = isset($bcItem['url']) ? trim((string) $bcItem['url'], '/') : '';
      $bcIcon   = isset($bcItem['icon']) ? preg_replace('/[^a-z0-9\- ]/i', '', (string) $bcItem['icon']) : '';
      ?>
      <li class="breadcrumb-item<?= $bcIsLast ? ' active' : '' ?>"<?= $bcIsLast ? ' aria-current="page"' : '' ?>>
        <?php if ($bcIcon !== ''): ?><i class="<?= htmlspecialchars($bcIcon) ?>"></i><?php endif; ?>
        <?php if ($bcIsLast || $bcUrl === ''): ?>
          <span><?= htmlspecialchars($bcName) ?></span>
        <?php else: ?>
          <a href="<?= base_url($bcUrl) ?>"><?= htmlspecialchars($bcName) ?></a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
