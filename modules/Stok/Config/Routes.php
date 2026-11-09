<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('stok', ['namespace' => 'Modules\Stok\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'StokController::index');
    $routes->get('export', 'StokController::export');
    $routes->get('kartu/(:num)', 'StokController::kartu/$1');
    $routes->get('opname', 'StokController::opname');
    $routes->post('opname/store', 'StokController::storeOpname');
    $routes->post('update-rak/(:num)', 'StokController::updateRak/$1');
});

