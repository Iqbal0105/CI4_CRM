<?php

declare(strict_types=1);

/**
 * @var CodeIgniter\Router\RouteCollection $routes
 */
// Webhook route (CSRF exempt)
$routes->post('webhook/fonnte', '\Modules\CRM\Controllers\CrmWebhookController::fonnte');

$routes->group('crm', ['namespace' => 'Modules\CRM\Controllers', 'filter' => 'auth'], static function ($routes) {
    $routes->get('', 'CrmController::index');
    $routes->get('export', 'CrmController::export');
    $routes->get('dashboard', 'CrmController::dashboard');
    $routes->get('broadcast', 'CrmController::broadcast');
    $routes->get('broadcast/filter', 'CrmController::filterBroadcast');
    $routes->post('broadcast/send', 'CrmController::sendBroadcast');
    $routes->post('broadcast-batch', 'CrmController::broadcastBatch');
    $routes->get('directory', 'CrmController::directory');
    $routes->get('customer_detail', 'CrmController::customerDetailView');
    $routes->get('transaction_detail_ajax', 'CrmController::transactionDetailAjax');
    $routes->get('directory/detail/(:num)', 'CrmController::customerDetail/$1');
    $routes->get('directory/export', 'CrmController::exportDirectory');
    $routes->get('kinerja', 'CrmController::kinerja');
    $routes->get('kinerja/export', 'CrmController::exportKinerja');
    $routes->get('kelompok', 'CrmController::kelompok');
    $routes->post('kelompok/store', 'CrmController::storeKelompok');
    $routes->match(['get', 'post'], 'kelompok/delete/(:num)', 'CrmController::deleteKelompok/$1');
    $routes->get('detail-modal/(:num)', 'CrmController::detailModal/$1');
    $routes->get('send-now/(:num)', 'CrmController::sendNow/$1');
    $routes->post('mark-sent/(:num)', 'CrmController::markSent/$1');
    $routes->get('chat', 'CrmController::chat');
    $routes->post('chat/store', 'CrmController::storeNote');
    $routes->post('chat/update-label/(:num)', 'CrmController::updateLabel/$1');
});

