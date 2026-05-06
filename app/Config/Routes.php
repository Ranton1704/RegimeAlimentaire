<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/register-step1', 'Auth::registerStep1');
$routes->post('/register-step1', 'Auth::postStep1');

$routes->get('/register-step2', 'Auth::registerStep2');
$routes->post('/register-step2', 'Auth::postStep2');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');