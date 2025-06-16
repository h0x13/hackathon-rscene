<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'Home::index');
// Account Management Routes
$routes->get('login', 'AccountController::login');
$routes->post('login', 'AccountController::login');
$routes->get('logout', 'AccountController::logout');
$routes->get('register', 'AccountController::register');
$routes->post('register', 'AccountController::register');
$routes->get('verify-code', 'AccountController::verifyCode');
$routes->post('verify-code', 'AccountController::verifyCode');
$routes->get('forgot-password', 'AccountController::forgotPassword');
$routes->post('forgot-password', 'AccountController::forgotPassword');
$routes->get('verify-password-reset', 'AccountController::verifyPasswordReset');
$routes->post('verify-password-reset', 'AccountController::verifyPasswordReset');
$routes->get('change-password', 'AccountController::changePassword');
$routes->post('change-password', 'AccountController::changePassword');
$routes->get('profile', 'AccountController::updateProfile');
$routes->post('profile/update', 'AccountController::updateProfile');
// $routes->get('profile', 'AccountController::index');


$routes->get('talents', 'TalentController::home');
$routes->get('talents/events', 'TalentController::events');
// $routes->get('talents/profile', 'TalentController::profile');
$routes->get('talents/venue', 'TalentController::venues');
$routes->post('talents/saveEvent', 'TalentController::saveEvent');
$routes->get('talents/allEvents', 'TalentController::allEvents');
$routes->get('talents/talentsEvents', 'TalentController::talentsEvents');

