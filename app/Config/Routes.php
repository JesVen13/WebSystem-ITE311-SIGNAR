<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Lab 3
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Lab 4 
$routes->get('/login', 'Auth::login');            
$routes->post('/login', 'Auth::attemptLogin');  

$routes->get('/register', 'Auth::register');     
$routes->post('/register', 'Auth::store');          

$routes->get('/dashboard', 'Auth::dashboard');       
$routes->get('/logout', 'Auth::logout');         
