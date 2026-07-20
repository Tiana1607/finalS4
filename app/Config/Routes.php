<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/admin', 'Home::index2');

$routes->group('admin', function ($routes) {
    $routes->get('login', 'Operateur\AuthController::login');
    $routes->post('login', 'Operateur\AuthController::attemptLogin');
    $routes->get('logout', 'Operateur\AuthController::logout');

    $routes->group('', ['filter' => 'adminAuth'], function ($routes) {
        $routes->get('dashboard', 'Operateur\DashboardController::index');

        $routes->get('prefixes', 'Operateur\PrefixeController::index');
        $routes->post('prefixes/ajouter', 'Operateur\PrefixeController::store');
        $routes->post('prefixes/supprimer/(:num)', 'Operateur\PrefixeController::delete/$1');

        $routes->get('frais', 'Operateur\FraisController::index');
        $routes->post('frais/ajouter', 'Operateur\FraisController::store');
        $routes->get('frais/modifier/(:num)', 'Operateur\FraisController::edit/$1');
        $routes->post('frais/modifier/(:num)', 'Operateur\FraisController::update/$1');
        $routes->post('frais/supprimer/(:num)', 'Operateur\FraisController::delete/$1');

        $routes->get('gains', 'Operateur\GainController::index');
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

// Client : Retrait
$routes->get('/client/retrait', 'Client\ClientsController::showRetraitForm', ['filter' => 'clientAuth']);
$routes->post('/client/retrait', 'Client\ClientsController::retrait', ['filter' => 'clientAuth']);

// Client : Transfert
$routes->get('/client/transfert', 'Client\ClientsController::showTransfertForm', ['filter' => 'clientAuth']);
$routes->post('/client/transfert', 'Client\ClientsController::transfert', ['filter' => 'clientAuth']);

// Client : Historique
$routes->get('/client/historique', 'Client\ClientsController::showHistorique', ['filter' => 'clientAuth']);
$routes->post('/client/historique', 'Client\ClientsController::historique', ['filter' => 'clientAuth']);
