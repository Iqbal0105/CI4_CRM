<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('', ['namespace' => 'Modules\Dashboard\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
});

