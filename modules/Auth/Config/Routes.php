<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
$routes->group('', ['namespace' => 'Modules\Auth\Controllers'], static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');

    // Profile Management (Authenticated)
    $routes->group('profile', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/', 'ProfileController::index');
        $routes->post('update', 'ProfileController::updateProfile');
        $routes->post('change-password', 'ProfileController::changePassword');
        $routes->post('upload-avatar', 'ProfileController::uploadAvatar');
        $routes->post('delete-avatar', 'ProfileController::deleteAvatar');
    });
});

