<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes for the Index controller
$routes->get('/', 'Index::index');

// Routes for the Users controller
$routes->get('users', 'Users::index');
$routes->get('users/add', 'Users::add');
$routes->post('users/insert', 'Users::insert');
$routes->get('users/view/(:num)', 'Users::view/$1');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');
$routes->get('users/verify/(:any)', 'Users::verify/$1');

// Routes for the Equipments controllers
$routes->get('equipments', 'Equipments::index');
$routes->get('equipments/add', 'Equipments::add');
$routes->post('equipments/insert', 'Equipments::insert');
$routes->get('equipments/view/(:any)', 'Equipments::view/$1');
$routes->get('equipments/equipment/(:any)', 'Equipments::view/$1');
$routes->get('equipments/edit/(:any)', 'Equipments::edit/$1');
$routes->post('equipments/update/(:any)', 'Equipments::update/$1');
$routes->get('equipments/delete/(:any)', 'Equipments::delete/$1');

// Routes for the Borrow controllers
$routes->get('borrow', 'Borrow::index');
$routes->get('borrow/form/(:segment)', 'Borrow::form/$1');
$routes->post('borrow/submit/(:any)', 'Borrow::submit');

// Authentication routes
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->match(['get', 'post'], 'signup', 'Auth::signup');
$routes->match(['get', 'post'], 'forgot-password', 'Auth::forgotPassword');
$routes->match(['get', 'post'], 'reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->get('logout', 'Auth::logout');

// Profile routes (protected)
$routes->get('profile', 'Profile::index', ['filter' => 'auth']);
$routes->post('profile/update', 'Profile::update', ['filter' => 'auth']);

// ITSO routes (protected) - 
$routes->group('itso', ['filter' => 'auth:itso'], function($routes) {
    $routes->get('dashboard', 'Itso\Dashboard::index');
});

// Associate routes (protected)
$routes->group('associate', ['filter' => 'auth:associate'], function($routes) {
    $routes->get('dashboard', 'Associate\Dashboard::index');
});

// Student routes (protected) -
$routes->group('student', ['filter' => 'auth:student'], function($routes) {
    $routes->get('dashboard', 'Student\Dashboard::index');
});
