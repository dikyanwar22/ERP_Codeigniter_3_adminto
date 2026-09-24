<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| JWT Config
*/
$config['jwt_key'] = 'adminto_jwt_secret_2026_32chars_secure!!'; // ganti di production
$config['jwt_expire'] = 3600 * 24; // 24 jam
$config['jwt_iss'] = 'adminto_api';
