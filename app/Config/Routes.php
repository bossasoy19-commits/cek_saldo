<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('auth', function ($routes) {
    $controller = 'auth_saldo\auth';
    $routes->get('/', $controller . '::index');

    // Menerima parameter $form di URL, contoh: /login atau /login/welcome
    // $routes->get('(:segment)?', $controller . '::index/$1');
    $routes->get('registers', $controller . '::registers');
    $routes->get('guest', $controller . '::index/guest');
    $routes->get('setup', $controller . '::setup');
    $routes->get('login', $controller . '::login');
    $routes->get('logout', $controller . '::logout');
    $routes->post('save', $controller . '::save');
    $routes->post('save_first_time', $controller . '::save_first_time', ['filter' => 'auth']);
    $routes->post('proccess', $controller . '::proccess'); // /login/proccess
});


// $routes->get('/', 'Dashboard::index', ['filter' => 'auth']);   
$routes->get('/', 'home\home::index', ['filter' => 'auth']);
$routes->get('/dashboard', 'home\home::index', ['filter' => 'auth']);
$routes->get('logout', 'auth_saldo\Auth::logout');
$routes->get('login', 'auth_saldo\Auth::login');
$routes->get('setup', 'auth_saldo\Auth::setup');

$routes->get('adminLTE', 'adminLTE::index', ['filter' => 'auth']);
// $routes->get('adminLTE', 'adminLTE::index');

// ['filter' => 'auth']


$routes->group('home', ['filter' => 'auth'], function ($routes) {
    $controller = 'home\home';
    $routes->get('/', $controller . '::index');        // /login
});
$routes->group('transaction', ['filter' => 'auth'], function ($routes) {
    $controller = 'transaction\transaction';
    $routes->get('/', $controller . '::index');
    $routes->get('form/(:segment)', $controller . '::form/$1');
    $routes->get('history', $controller . '::history');
});
$routes->group('report', ['filter' => 'auth'], function ($routes) {
    $controller = 'report\report';
    $routes->get('/', $controller . '::index');
    $routes->get('pemasukan', $controller . '::pemasukan');
    $routes->get('arus_kas', $controller . '::arus_kas');
});

// $routes->group('user', ['filter' => 'auth'], function ($routes) {
//     $controller = 'sistem_utility\user';
//     rout($routes,  $controller);
//     $routes->get('profile_form', $controller . '::profile_form');
//     $routes->get('profile_form/(:segment)', $controller . '::profile_form/$1');
//     $routes->get('get_data_profile/(:segment)', $controller . '::get_data_profile/$1');
//     $routes->get('form_reset_password/(:segment)', $controller . '::form_reset_password/$1');
//     $routes->get('akses_cabang/(:segment)', $controller . '::akses_cabang/$1');
//     $routes->get('form_reset_password_profile', $controller . '::form_reset_password_profile');
//     $routes->post('save_profile', $controller . '::save_profile');
//     $routes->post('simpan_gambar/(:segment)', $controller . '::simpan_gambar/$1');
//     $routes->put('update_password/', $controller . '::update_password');
//     $routes->get('save_akses_cabang/', $controller . '::save_akses_cabang');
// });
// $routes->group('ugroup', ['filter' => 'auth'], function ($routes) {
//     $controller = 'sistem_utility\ugroup';
//     rout($routes,  $controller);
// });
// $routes->group('cabang', ['filter' => 'auth'], function ($routes) {
//     $controller = 'sistem_utility\cabang';
//     rout($routes,  $controller);
// });
