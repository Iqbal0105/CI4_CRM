<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('retur', ['namespace' => 'Modules\Retur\Controllers', 'filter' => 'auth'], static function ($routes) {
    // Retur Penjualan
    $routes->get('', 'ReturController::penjualan');
    $routes->get('penjualan', 'ReturController::penjualan');
    $routes->get('penjualan/create', 'ReturController::createPenjualan');
    $routes->post('penjualan/store', 'ReturController::storePenjualan');
    $routes->get('penjualan/order-items/(:num)', 'ReturController::orderItems/$1');
    $routes->get('penjualan/detail/(:num)', 'ReturController::detailPenjualan/$1');
    $routes->match(['get', 'post'], 'penjualan/delete/(:num)', 'ReturController::deletePenjualan/$1');
    $routes->get('penjualan/export/(:segment)', 'ReturController::exportPenjualan/$1');

    // Retur Pembelian
    $routes->get('pembelian', 'ReturController::pembelian');
    $routes->get('pembelian/create', 'ReturController::createPembelian');
    $routes->post('pembelian/store', 'ReturController::storePembelian');
    $routes->get('pembelian/po-items/(:num)', 'ReturController::poItems/$1');
    $routes->get('pembelian/detail/(:num)', 'ReturController::detailPembelian/$1');
    $routes->match(['get', 'post'], 'pembelian/delete/(:num)', 'ReturController::deletePembelian/$1');
    $routes->get('pembelian/export/(:segment)', 'ReturController::exportPembelian/$1');
});

