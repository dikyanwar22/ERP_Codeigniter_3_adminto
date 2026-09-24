<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['daftar-akun'] = 'akun/create';
$route['profile'] = 'profile/index';

// API - Menu & Dummy (kompatibel header lama)
$route['api/menu'] = 'api/menu/index';
$route['api/messages'] = 'notif/messages';
$route['api/notifications'] = 'notif/notifications';

// API JWT - Auth
$route['api/auth/login'] = 'api/auth/login';
$route['api/auth/me'] = 'api/auth/me';

// API JWT - Pembelian CRUD (tabel ci_pembelian) - controller di folder Api
$route['api/pembelian'] = 'api/pembelian/index';
$route['api/pembelian/(:num)'] = 'api/pembelian/index/$1';
$route['api/pembelian/show/(:num)'] = 'api/pembelian/show/$1';
$route['api/pembelian/store'] = 'api/pembelian/store';
$route['api/pembelian/update/(:num)'] = 'api/pembelian/update/$1';
$route['api/pembelian/delete/(:num)'] = 'api/pembelian/destroy/$1';
$route['api/pembelian/create'] = 'api/pembelian/store';

// Modul & Akses
$route['modul'] = 'modul/index';
$route['akses'] = 'akses/index';
