<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public Landing Page & Dashboard Portal Routes
$routes->get('/', '\App\Controllers\LandingController::index');
$routes->get('landing', '\App\Controllers\LandingController::index');
$routes->get('dashboard', '\Modules\Dashboard\Controllers\DashboardController::index', ['filter' => 'auth']);
$routes->get('invoice', '\Modules\PenjualanRegular\Controllers\PenjualanController::invoices', ['filter' => 'auth']);

// Notifications Center & Feed Routes
$routes->group('notifications', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', '\App\Controllers\NotificationController::index');
    $routes->get('feed', '\App\Controllers\NotificationController::getFeed');
    $routes->post('mark-read/(:num)', '\App\Controllers\NotificationController::markRead/$1');
    $routes->post('mark-all-read', '\App\Controllers\NotificationController::markAllRead');
    $routes->match(['get', 'post'], 'clear-all', '\App\Controllers\NotificationController::clearAll');
    $routes->match(['get', 'post'], 'delete/(:num)', '\App\Controllers\NotificationController::delete/$1');
});

// Auto-discover routes from modules
$moduleRouteFiles = glob(ROOTPATH . 'modules/*/Config/Routes.php');
if ($moduleRouteFiles) {
    foreach ($moduleRouteFiles as $routeFile) {
        require $routeFile;
    }
}

