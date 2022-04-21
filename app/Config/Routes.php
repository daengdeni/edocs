<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->get('/register', 'UserController::registerUserView');
$routes->post('/register', 'UserController::storeUser');

$routes->get('/login', 'UserController::index');
$routes->post('/login', 'UserController::loginProces');
$routes->get('/logout', 'UserController::logout');

$routes->get('/my-doc', 'DokumenController::index');
$routes->get('/create-doc', 'DokumenController::createDocView');
$routes->post('/create-doc', 'DokumenController::storeDocView');

$routes->get('ajax-user', 'DokumenController::ajaxUser');
$routes->get('ajax-doc', 'DokumenController::ajaxDokumen');

$routes->get('/see/docs/(:segment)', 'DokumenController::detailDocs/$1');
$routes->post('/assign/docs/(:segment)', 'DokumenController::assignDoc/$1');
$routes->get('/destroy/docs/(:segment)', 'DokumenController::destroyDoc/$1');
$routes->get('/rollback/docs/(:segment)', 'DokumenController::rollbackDoc/$1');
$routes->get('/edit/docs/(:segment)', 'DokumenController::editDoc/$1');
$routes->post('/edit/docs/(:segment)', 'DokumenController::editDocProcess/$1');

$routes->get('my-notif', 'Home::viewNotif');
$routes->get('trash', 'Home::viewTrash');

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
