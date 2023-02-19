<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');
$routes->get('/', 'admin\Login::index');

$routes->get('admin', 'admin\Login::index');

$routes->group('admin', static function ($routes) {    
    $routes->get('login', 'admin\Login::index');
    $routes->get('login/(:any)', 'admin\Login::$1');
    $routes->post('login/(:any)', 'admin\Login::$1');
    
    $routes->get('dashboard', 'admin\Dashboard::index');
    $routes->get('dashboard/(:any)', 'admin\Dashboard::$1');
    $routes->post('dashboard/(:any)', 'admin\Dashboard::$1');
    
    $routes->get('myaccount', 'admin\MyAccount::index');
    $routes->get('myaccount/(:any)', 'admin\MyAccount::$1');
    $routes->post('myaccount/(:any)', 'admin\MyAccount::$1');
});

$routes->group('api', static function ($routes) {
    $routes->get('docs', 'api\Docs::index');

    $routes->get('shop/(:any)', 'api\Shop::$1');
    $routes->post('shop/(:any)', 'api\Shop::$1');
    
    $routes->get('kiosk/(:any)', 'api\Kiosk::$1');
    $routes->post('kiosk/(:any)', 'api\Kiosk::$1');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
