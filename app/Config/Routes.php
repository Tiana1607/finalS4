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
        // routes protégées
    });
});

// Accueil -> page login client
$routes->get('/', 'Home::index');

// Client : Authentification
$routes->get('/client/login', 'Client\ClientsController::showLoginForm');
$routes->post('/client/login', 'Client\ClientsController::login');
$routes->get('/client/logout', 'Client\ClientsController::logout');

// Client : Pages protégées
$routes->get('/client/dashboard', 'Client\ClientsController::dashboard', ['filter' => 'clientAuth']);

// Client : Dépôt
$routes->get('/client/depot', 'Client\ClientsController::showDepotForm', ['filter' => 'clientAuth']);
$routes->post('/client/depot', 'Client\ClientsController::depot', ['filter' => 'clientAuth']);
