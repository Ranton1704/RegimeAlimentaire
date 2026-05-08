<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');          
$routes->post('/', 'Auth::doLogin');      

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');

$routes->get('register-step1', 'Auth::registerStep1');
$routes->post('register-step1', 'Auth::postStep1');

$routes->get('register-step2', 'Auth::registerStep2');
$routes->post('register-step2', 'Auth::postStep2');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('objectifs', 'Dashboard::objectifs', ['filter' => 'auth']);
$routes->post('objectifs', 'Dashboard::saveObjectifs', ['filter' => 'auth']);

$routes->get('export/pdf', 'Export::pdf', ['filter' => 'auth']);

$routes->get('wallet', 'Wallet::index', ['filter' => 'auth']);
$routes->post('wallet/recharge', 'Wallet::recharge', ['filter' => 'auth']);
$routes->get('buy-gold', 'Wallet::buyGold', ['filter' => 'auth']);
$routes->get('regime/(:num)/buy', 'Wallet::buyRegime/$1', ['filter' => 'auth']);

$routes->get('logout', 'Auth::logout');

// Profil utilisateur
$routes->get('profil', 'Profil::index', ['filter' => 'auth']);
$routes->get('profil/edit', 'Profil::edit', ['filter' => 'auth']);
$routes->post('profil/update', 'Profil::update', ['filter' => 'auth']);

// Gestion (Back office accessible via rôle admin)
$routes->group('gestion', ['filter' => 'auth'], static function($routes) {
	$routes->get('regimes', 'Regimes::index');
	$routes->get('regimes/create', 'Regimes::create');
	$routes->post('regimes/store', 'Regimes::store');
	$routes->get('regimes/show/(:num)', 'Regimes::show/$1');
	$routes->get('regimes/edit/(:num)', 'Regimes::edit/$1');
	$routes->post('regimes/update/(:num)', 'Regimes::update/$1');
	$routes->get('regimes/delete/(:num)', 'Regimes::delete/$1');

	$routes->get('activites-sportives', 'ActivitesSportives::index');
	$routes->get('activites-sportives/create', 'ActivitesSportives::create');
	$routes->post('activites-sportives/store', 'ActivitesSportives::store');
	$routes->get('activites-sportives/show/(:num)', 'ActivitesSportives::show/$1');
	$routes->get('activites-sportives/edit/(:num)', 'ActivitesSportives::edit/$1');
	$routes->post('activites-sportives/update/(:num)', 'ActivitesSportives::update/$1');
	$routes->get('activites-sportives/delete/(:num)', 'ActivitesSportives::delete/$1');

	$routes->get('codes-wallet', 'CodesWallet::index');
	$routes->get('codes-wallet/create', 'CodesWallet::create');
	$routes->post('codes-wallet/store', 'CodesWallet::store');
	$routes->get('codes-wallet/edit/(:num)', 'CodesWallet::edit/$1');
	$routes->post('codes-wallet/update/(:num)', 'CodesWallet::update/$1');
	$routes->get('codes-wallet/delete/(:num)', 'CodesWallet::delete/$1');

	$routes->get('parametres', 'Parametres::index');
	$routes->get('parametres/create', 'Parametres::create');
	$routes->post('parametres/store', 'Parametres::store');
	$routes->get('parametres/edit/(:num)', 'Parametres::edit/$1');
	$routes->post('parametres/update/(:num)', 'Parametres::update/$1');
	$routes->get('parametres/delete/(:num)', 'Parametres::delete/$1');
});