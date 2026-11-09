<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('rfm', ['namespace' => 'Modules\RFM\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'RfmController::index');
    $routes->get('dashboard', 'RfmController::index');
    $routes->get('customer', 'RfmController::customer');
    $routes->get('customer_detail', 'RfmController::customerDetail');
    $routes->get('customer/detail', 'RfmController::customerDetail');
    $routes->get('customer/export', 'RfmController::exportCustomer');
    $routes->get('tracking', 'RfmController::tracking');
    $routes->get('kohort', 'RfmController::kohort');
    $routes->get('tren-produk', 'RfmController::trenProduk');
    $routes->get('matriks', 'RfmController::matriks');
    $routes->post('strategy/save', 'RfmController::saveStrategy');
    $routes->post('import-excel', 'RfmController::importExcel');
});

