<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('master', ['namespace' => 'Modules\Master\Controllers', 'filter' => 'auth'], static function ($routes) {
    // Produk
    $routes->get('produk', 'MasterProdukController::index');
    $routes->get('produk/export', 'MasterProdukController::export');
    $routes->get('produk/create', 'MasterProdukController::create');
    $routes->post('produk/store', 'MasterProdukController::store');
    $routes->get('produk/edit/(:num)', 'MasterProdukController::edit/$1');
    $routes->post('produk/update/(:num)', 'MasterProdukController::update/$1');
    $routes->match(['get', 'post'], 'produk/delete/(:num)', 'MasterProdukController::delete/$1');
    $routes->get('produk/search-ajax', 'MasterProdukController::searchAjax');

    // Pelanggan
    $routes->get('pelanggan', 'MasterPelangganController::index');
    $routes->get('pelanggan/export', 'MasterPelangganController::export');
    $routes->get('pelanggan/create', 'MasterPelangganController::create');
    $routes->post('pelanggan/store', 'MasterPelangganController::store');
    $routes->get('pelanggan/edit/(:num)', 'MasterPelangganController::edit/$1');
    $routes->post('pelanggan/update/(:num)', 'MasterPelangganController::update/$1');
    $routes->match(['get', 'post'], 'pelanggan/delete/(:num)', 'MasterPelangganController::delete/$1');

    // Supplier & Kurs
    $routes->get('supplier', 'MasterSupplierController::index');
    $routes->get('supplier/export', 'MasterSupplierController::export');
    $routes->post('supplier/store', 'MasterSupplierController::store');
    $routes->post('supplier/update/(:num)', 'MasterSupplierController::update/$1');
    $routes->match(['get', 'post'], 'supplier/delete/(:num)', 'MasterSupplierController::delete/$1');
    $routes->post('kurs/store', 'MasterSupplierController::storeKurs');
    $routes->post('kurs/sync-api', 'MasterSupplierController::syncKursApi');
    $routes->get('kurs/latest', 'MasterSupplierController::getLatestKursAjax');

    // Kategori & Satuan
    $routes->get('kategori', 'MasterKategoriController::index');
    $routes->get('kategori/export', 'MasterKategoriController::exportKategori');
    $routes->get('satuan', 'MasterKategoriController::satuan');
    $routes->get('satuan/export', 'MasterKategoriController::exportSatuan');
    $routes->post('kategori/store', 'MasterKategoriController::storeKategori');
    $routes->post('kategori/update/(:num)', 'MasterKategoriController::updateKategori/$1');
    $routes->match(['get', 'post'], 'kategori/delete/(:num)', 'MasterKategoriController::deleteKategori/$1');
    $routes->post('satuan/store', 'MasterKategoriController::storeSatuan');
    $routes->post('satuan/update/(:num)', 'MasterKategoriController::updateSatuan/$1');
    $routes->match(['get', 'post'], 'satuan/delete/(:num)', 'MasterKategoriController::deleteSatuan/$1');
});

