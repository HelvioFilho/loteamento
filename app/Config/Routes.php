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

  #payment list

  $routes->get('/lista-de-pagamentos', 'PaymentList::index');
  $routes->post('/payments/createList', 'PaymentList::createList');
  $routes->get('/lista-de-pagamentos/gerenciar/(:num)', 'PaymentList::manage/$1');
  $routes->post('/payments/savePayments', 'PaymentList::savePayments');
  $routes->post('/payments/editList', 'PaymentList::editList');
  $routes->get('/payments/delete/(:num)', 'PaymentList::deleteList/$1');
  $routes->get('/payments/exportExcel/(:num)', 'PaymentList::exportExcel/$1');

  # Dependents
  $routes->get('/dependentes', 'Dependents::index');
  $routes->get('/dependentes/gerenciar/(:segment)', 'Dependents::createPageDependent/$1');
  $routes->post('/createDependent/(:num)', 'Dependents::createDependent/$1');
  $routes->post('/updateDependent/(:num)', 'Dependents::updateDependent/$1');
  $routes->get('/deleteDependent/(:num)/(:num)', 'Dependents::deleteDependent/$1/$2');

  # Plots
  $routes->get('/lotes', 'Plots::index');
  $routes->get('/lotes/gerenciar/(:segment)', 'Plots::createPagePlot/$1');
  $routes->post('/createPlot/(:num)', 'Plots::createPlot/$1');
  $routes->post('/updatePlot/(:num)', 'Plots::updatePlot/$1');
  $routes->get('/deletePlot/(:num)/(:num)', 'Plots::deletePlot/$1/$2');
});

$routes->get('testCode', 'Home::generateCode');
$routes->get('/card/(:segment)', 'Home::card/$1');
