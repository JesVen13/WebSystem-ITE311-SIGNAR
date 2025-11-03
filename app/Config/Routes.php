<?php

use CodeIgniter\Router\RouteCollection;

// Public routes
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::store');
$routes->get('/logout', 'Auth::logout');

// Admin routes protected by the 'role' filter:
$routes->group('admin', ['filter' => 'role'], function($routes) {
    $routes->get('dashboard',   'Admin::dashboard');
    $routes->get('users',       'Admin::users'); 
    $routes->get('create',      'Admin::create');
    $routes->post('store',      'Admin::store');
    $routes->get('edit/(:num)', 'Admin::edit/$1');
    $routes->post('update/(:num)','Admin::update/$1');
    $routes->get('delete/(:num)','Admin::delete/$1');
});

// Teacher routes protected by role:teacher
$routes->group('teacher', ['filter' => 'role:teacher'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// Student routes protected by role:student
$routes->group('student', ['filter' => 'role:student'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});
