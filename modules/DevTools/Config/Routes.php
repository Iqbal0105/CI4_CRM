<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('dev', ['namespace' => 'Modules\DevTools\Controllers', 'filter' => 'auth'], static function ($routes) {
    // 1. CRUD Builder
    $routes->get('crud', 'DevToolsController::crud');
    $routes->post('crud/store', 'DevToolsController::storeCrud');
    $routes->post('crud/update/(:num)', 'DevToolsController::updateCrud/$1');
    $routes->post('crud/delete/(:num)', 'DevToolsController::deleteCrud/$1');
    $routes->get('crud/export', 'DevToolsController::exportCrud');

    // 2. Menu Builder
    $routes->get('menu', 'DevToolsController::menu');
    $routes->post('menu/store', 'DevToolsController::storeMenuItem');
    $routes->post('menu/type/store', 'DevToolsController::storeMenuType');
    $routes->post('menu/type/delete', 'DevToolsController::deleteMenuType');

    // 3. Extensions
    $routes->get('extension', 'DevToolsController::extension');
    $routes->post('extension/store', 'DevToolsController::storeExtension');

    // 4. Builders & Docs
    $routes->get('api', 'DevToolsController::api');
    $routes->get('page', 'DevToolsController::page');
    $routes->get('form', 'DevToolsController::form');
    $routes->get('docs/web', 'DevToolsController::webDocs');
    $routes->get('docs/api', 'DevToolsController::apiDocs');
});
