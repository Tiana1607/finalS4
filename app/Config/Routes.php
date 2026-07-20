<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index2');

$routes->group('admin', function ($routes) {
    $routes->get('login', 'Operateur\AuthController::login');
    $routes->post('login', 'Operateur\AuthController::attemptLogin');
    $routes->get('logout', 'Operateur\AuthController::logout');

    $routes->group('', ['filter' => 'adminAuth'], function ($routes) {
        $routes->get('dashboard', 'Operateur\DashboardController::index');

        $routes->get('prefixes', 'Operateur\PrefixeController::index');
        $routes->post('prefixes/ajouter', 'Operateur\PrefixeController::store');
        $routes->post('prefixes/supprimer/(:num)', 'Operateur\PrefixeController::delete/$1');
    });
});
