<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('penjualan', ['namespace' => 'Modules\PenjualanRegular\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'PenjualanController::index');
    $routes->get('export', 'PenjualanController::export');
    $routes->get('invoice', 'PenjualanController::invoices');
    $routes->get('invoice/export', 'PenjualanController::exportInvoices');
    $routes->get('create', 'PenjualanController::create');
    $routes->post('store', 'PenjualanController::store');
    $routes->get('detail/(:num)', 'PenjualanController::detail/$1');
    $routes->post('update-status/(:num)', 'PenjualanController::updateStatus/$1');
    $routes->get('invoice-pdf/(:num)', 'PenjualanController::invoicePdf/$1');
    $routes->get('surat-jalan-pdf/(:num)', 'PenjualanController::suratJalanPdf/$1');
    $routes->get('thermal-data/(:num)', 'PenjualanController::thermalData/$1');
});

