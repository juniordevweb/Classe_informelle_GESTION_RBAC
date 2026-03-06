<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//USER
$routes->get('users', 'C_UserController::index');
$routes->post('/users/save_user', 'C_UserController::save_user');
$routes->get('/users/block/(:num)', 'C_UserController::block/$1');
$routes->get('/users/delete/(:num)', 'C_UserController::delete/$1');
$routes->post('/users/update', 'C_UserController::update');


//PROFIL
$routes->get('profils', 'C_ProfilController::index');
$routes->get('profils/get/(:num)', 'C_ProfilController::getProfil/$1');
$routes->post('profils/update', 'C_ProfilController::update');
$routes->get('profils/delete/(:num)', 'C_ProfilController::delete/$1');
$routes->post('profils/delete_ajax/(:num)', 'C_ProfilController::delete_ajax/$1');
$routes->post('profils/save', 'C_ProfilController::save');

//LOGIN
$routes->get('login', 'C_AuthController::login');
$routes->post('login/process', 'C_AuthController::process');
$routes->get('logout', 'C_AuthController::logout');

//DASHBOARD
$routes->get('/dashboard', 'C_DashboardController::index');