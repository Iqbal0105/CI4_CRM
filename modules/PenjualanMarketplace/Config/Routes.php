<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('marketplace', ['namespace' => 'Modules\PenjualanMarketplace\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'MarketplaceController::index');
    $routes->get('export', 'MarketplaceController::export');
    $routes->get('import', 'MarketplaceController::import');
    $routes->post('preview-import', 'MarketplaceController::previewImport');
    $routes->post('process-import', 'MarketplaceController::processImport');
    $routes->get('scan-resi', 'MarketplaceController::scanResi');
    $routes->get('import-resi', 'MarketplaceController::scanResi'); // Alias
    $routes->post('process-scan-resi', 'MarketplaceController::processScanResi');
    $routes->post('parse-resi-text', 'MarketplaceController::parseResiTextAjax');
    $routes->post('save-resi-order', 'MarketplaceController::saveResiOrder');
    $routes->post('save-batch-resi', 'MarketplaceController::saveBatchResi');
    $routes->get('detail/(:num)', 'MarketplaceController::detail/$1');
    $routes->get('edit/(:num)', 'MarketplaceController::edit/$1');
    $routes->post('update/(:num)', 'MarketplaceController::update/$1');
    $routes->get('sync-master', 'MarketplaceController::syncMaster');
    $routes->post('update-status/(:num)', 'MarketplaceController::updateStatus/$1');
    $routes->post('update-pelanggan/(:num)', 'MarketplaceController::updatePelanggan/$1');
    $routes->get('resi-thermal-data/(:num)', 'MarketplaceController::resiThermalData/$1');
    $routes->get('debug-ocr', 'MarketplaceController::debugOcr');
    $routes->match(['get', 'post'], 'delete/(:num)', 'MarketplaceController::delete/$1');
});

