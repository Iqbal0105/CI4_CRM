<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('pos', ['namespace' => 'Modules\PenjualanPOS\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'PosController::index');
    $routes->post('checkout', 'PosController::checkout');
    $routes->get('struk/(:num)', 'PosController::struk/$1');
    $routes->get('struk-json/(:num)', 'PosController::strukJson/$1');
});

