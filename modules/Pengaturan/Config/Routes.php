<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('pengaturan', ['namespace' => 'Modules\Pengaturan\Controllers', 'filter' => 'auth'], static function ($routes) {
    // 1. Settings
    $routes->get('settings', 'PengaturanController::settings');
    $routes->post('settings', 'PengaturanController::updateSettings');

    // 2. User Management
    $routes->get('user', 'PengaturanController::user');
    $routes->post('user/store', 'PengaturanController::storeUser');
    $routes->post('user/update/(:num)', 'PengaturanController::updateUser/$1');
    $routes->match(['get', 'post'], 'user/delete/(:num)', 'PengaturanController::deleteUser/$1');
    $routes->post('user/toggle-status/(:num)', 'PengaturanController::toggleStatusUser/$1');
    $routes->get('user/export', 'PengaturanController::exportUser');
    $routes->get('user/export/(:segment)', 'PengaturanController::exportUser/$1');

    // 3. Groups / Roles Management
    $routes->get('groups', 'PengaturanController::groups');
    $routes->post('groups/store', 'PengaturanController::storeGroup');
    $routes->post('groups/update/(:num)', 'PengaturanController::updateGroup/$1');
    $routes->match(['get', 'post'], 'groups/delete/(:num)', 'PengaturanController::deleteGroup/$1');
    $routes->get('groups/export', 'PengaturanController::exportGroup');
    $routes->get('groups/export/(:segment)', 'PengaturanController::exportGroup/$1');

    // 4. Approval Calon Customer
    $routes->get('approval', 'PengaturanController::approval');
    $routes->post('approval/approve/(:num)', 'PengaturanController::approveCustomer/$1');
    $routes->post('approval/reject/(:num)', 'PengaturanController::rejectCustomer/$1');

    // 5. Access Control
    $routes->get('access', 'PengaturanController::access');
    $routes->post('access/save', 'PengaturanController::saveAccess');
    $routes->get('access/get-role-permissions/(:num)', 'PengaturanController::getRolePermissions/$1');

    // 6. Permission Management
    $routes->get('permission', 'PengaturanController::permission');
    $routes->post('permission/store', 'PengaturanController::storePermission');
    $routes->post('permission/update/(:num)', 'PengaturanController::updatePermission/$1');
    $routes->match(['get', 'post'], 'permission/delete/(:num)', 'PengaturanController::deletePermission/$1');
    $routes->get('permission/export', 'PengaturanController::exportPermission');
    $routes->get('permission/export/(:segment)', 'PengaturanController::exportPermission/$1');

    // 7. API Keys
    $routes->get('api-keys', 'PengaturanController::apiKeys');
});
