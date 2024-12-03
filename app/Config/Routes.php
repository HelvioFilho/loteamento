<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->match(['get', 'post'], '/login', 'Home::index');

$routes->get('/logout', 'Home::logout');
$routes->get('/createUserAdmin', 'Home::createUserAdmin');

$routes->get('/chacreamento/(:segment)', 'Chacreamento::chacreamento/$1');

$routes->group('', ['filter' => 'auth'], function (RouteCollection $routes) {
  $routes->get('/user', 'User::index');
  $routes->post('/createUser', 'User::createUser');
  $routes->post('/updateUser/(:num)', 'User::updateUser/$1');
  $routes->post('/updateUserName/(:num)', 'User::updateUserName/$1');
  $routes->post('/updateUserImage/(:num)', 'User::updateUserImage/$1');
  $routes->get('/updateUserImage/(:num)', 'User::updateUserImage/$1');
  $routes->get('/deleteUser/(:segment)', 'User::deleteUser/$1');
});

$routes->get('testCode', 'Home::generateCode');
$routes->get('/card/(:segment)', 'Home::card/$1');
