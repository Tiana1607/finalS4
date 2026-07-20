<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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
