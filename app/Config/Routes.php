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
// Authentication Routes
$routes->get('/login', 'Auth::login');            
$routes->post('/login', 'Auth::attemptLogin');  
// Registration Routes
$routes->get('/register', 'Auth::register');     
$routes->post('/register', 'Auth::store');          
// Protected Routes
$routes->get('/dashboard', 'Auth::dashboard');       
$routes->get('/logout', 'Auth::logout');                        
// Exam Tasks - Announcements
$routes->get('/announcements', 'Announcement::index');
// Exam Tasks - Role Dashboards (protected by RoleAuth)
$routes->group('teacher', ['filter' => 'roleauth'], static function ($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});
$routes->group('admin', ['filter' => 'roleauth'], static function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
});
// Optional: protect student dashboard with same filter
$routes->group('student', ['filter' => 'roleauth'], static function ($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});
