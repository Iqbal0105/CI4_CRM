<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('pembelian', ['namespace' => 'Modules\Pembelian\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'PembelianController::index');
    $routes->get('export', 'PembelianController::export');
    $routes->get('create', 'PembelianController::create');
    $routes->post('store', 'PembelianController::store');
    $routes->get('detail/(:num)', 'PembelianController::detail/$1');
    $routes->get('pdf/(:num)', 'PembelianController::pdf/$1');
    $routes->get('penerimaan/(:num)', 'PembelianController::penerimaan/$1');
    $routes->post('process-penerimaan/(:num)', 'PembelianController::processPenerimaan/$1');
    $routes->get('cancel/(:num)', 'PembelianController::cancel/$1');
});

