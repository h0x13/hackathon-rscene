<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('talents', 'TalentController::home');
$routes->get('talents/events', 'TalentController::events');

