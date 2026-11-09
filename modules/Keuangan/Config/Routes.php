<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('keuangan', ['namespace' => 'Modules\Keuangan\Controllers', 'filter' => 'auth'], static function ($routes) {
    // Piutang & BKM
    $routes->get('piutang', 'KeuanganController::piutang');
    $routes->get('piutang/export', 'KeuanganController::exportPiutang');
    $routes->get('bkm', 'KeuanganController::bkm');
    $routes->get('bkm/export', 'KeuanganController::exportBkm');
    $routes->get('bkm-thermal-data/(:num)', 'KeuanganController::bkmThermalData/$1');
    $routes->get('bkm-print/(:num)', 'KeuanganController::bkmPrint/$1');
    $routes->get('bkm-pdf/(:num)', 'KeuanganController::bkmPdf/$1');
    $routes->post('bayar-piutang', 'KeuanganController::storeBayarPiutang');

    // Utang & BKK
    $routes->get('utang', 'KeuanganController::utang');
    $routes->get('utang/export', 'KeuanganController::exportUtang');
    $routes->get('bkk', 'KeuanganController::bkk');
    $routes->get('bkk/export', 'KeuanganController::exportBkk');
    $routes->get('bkk-thermal-data/(:num)', 'KeuanganController::bkkThermalData/$1');
    $routes->get('bkk-print/(:num)', 'KeuanganController::bkkPrint/$1');
    $routes->get('bkk-pdf/(:num)', 'KeuanganController::bkkPdf/$1');
    $routes->post('bayar-utang', 'KeuanganController::storeBayarUtang');

    // Buku Besar & Jurnal
    $routes->get('jurnal', 'KeuanganController::jurnal');
    $routes->get('jurnal/export', 'KeuanganController::exportJurnal');

    // Akun Kas & Bank
    $routes->get('kas-bank', 'KeuanganController::kasBank');
    $routes->post('kas-bank/store', 'KeuanganController::storeKasBank');
});

