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
$routes->get('equipments/equipment_add', 'Equipments::add');
$routes->post('equipments/equipment_insert', 'Equipments::insert');
$routes->get('equipments/equipment/(:num)', 'Equipments::view/$1');
$routes->get('equipments/equipment_edit/(:num)', 'Equipments::edit/$1');
$routes->post('equipments/equipment_update/(:num)', 'Equipments::update/$1');
$routes->get('equipments/equipment_delete/(:num)', 'Equipments::delete/$1');

// Routes for the ____ controllers