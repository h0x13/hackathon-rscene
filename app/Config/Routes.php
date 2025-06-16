<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('talents', 'TalentController::home');
$routes->get('talents/events', 'TalentController::events');
$routes->get('talents/profile', 'TalentController::profile');
$routes->get('talents/venue', 'TalentController::venues');
$routes->post('talents/saveEvent', 'TalentController::saveEvent');

