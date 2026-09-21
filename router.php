<?php
// Router untuk php -S agar URL tanpa index.php bisa jalan (http://127.0.0.1:8081/login)
// Usage: php -S 127.0.0.1:8081 router.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__ . $uri;
if ($uri !== '/' && is_file($path)) {
    // file statis (css, js, img) langsung serve
    return false;
}
// selain itu arahkan ke index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
include __DIR__ . '/index.php';
