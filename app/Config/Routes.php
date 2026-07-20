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
        $routes->get('prefixes/ajouter', 'Operateur\PrefixeController::create');
        $routes->post('prefixes', 'Operateur\PrefixeController::store');
        $routes->post('prefixes/delete/(:num)', 'Operateur\PrefixeController::delete/$1');

        $routes->get('operateurs/ajouter', 'Operateur\PrefixeController::createOperateur');
        $routes->post('operateurs/ajouter', 'Operateur\PrefixeController::storeOperateur');

        $routes->get('frais', 'Operateur\FraisController::index');
        $routes->post('frais/ajouter', 'Operateur\FraisController::store');
        $routes->get('frais/modifier/(:num)', 'Operateur\FraisController::edit/$1');
        $routes->post('frais/modifier/(:num)', 'Operateur\FraisController::update/$1');
        $routes->post('frais/supprimer/(:num)', 'Operateur\FraisController::delete/$1');

        $routes->get('gains', 'Operateur\GainController::index');

        $routes->get('commissions', 'Operateur\CommissionController::index');
        $routes->post('commissions/ajouter', 'Operateur\CommissionController::store');
        $routes->post('commissions/modifier/(:num)', 'Operateur\CommissionController::update/$1');
        $routes->get('commissions/popup/(:num)', 'Operateur\CommissionController::popup/$1');

        $routes->get('montants', 'Operateur\MontantController::index');

        $routes->get('clients', 'Operateur\ClientController::index');
        $routes->get('clients/detail/(:num)', 'Operateur\ClientController::detail/$1');
    });
});

// Accueil -> page login client
$routes->get('/', 'Home::index');

// Client : Authentification
$routes->get('/client/login', 'Client\AuthController::showLoginForm');
$routes->post('/client/login', 'Client\AuthController::login');
$routes->get('/client/logout', 'Client\AuthController::logout');

// Client : Dashboard
$routes->get('/client/dashboard', 'Client\AuthController::dashboard', ['filter' => 'clientAuth']);

// Client : Dépôt
$routes->get('/client/depot', 'Client\DepotController::showForm', ['filter' => 'clientAuth']);
$routes->post('/client/depot', 'Client\DepotController::depot', ['filter' => 'clientAuth']);

// Client : Retrait
$routes->get('/client/retrait', 'Client\RetraitController::showForm', ['filter' => 'clientAuth']);
$routes->post('/client/retrait', 'Client\RetraitController::retrait', ['filter' => 'clientAuth']);

// Client : Transfert
$routes->get('/client/transfert', 'Client\TransfertController::showForm', ['filter' => 'clientAuth']);
$routes->post('/client/transfert', 'Client\TransfertController::transfert', ['filter' => 'clientAuth']);
$routes->get('/client/transfert/detecter-operateur', 'Client\TransfertController::detecterOperateur', ['filter' => 'clientAuth']);

// Client : Historique
$routes->get('/client/historique', 'Client\HistoriqueController::showHistorique', ['filter' => 'clientAuth']);
$routes->match(['get', 'post'], '/client/historique/filtrer', 'Client\HistoriqueController::historique', ['filter' => 'clientAuth']);
