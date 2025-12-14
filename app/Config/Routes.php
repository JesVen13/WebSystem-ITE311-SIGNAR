<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->get('MyLogin', 'Home::MyLogin');

// Public routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');
$routes->get('logout', 'Auth::logout');

// Admin routes (Protected by role:admin)
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('deleted-users', 'Admin::deletedUsers'); 
    $routes->get('restore/(:num)', 'Admin::restore/$1');
    $routes->get('permanent-delete/(:num)', 'Admin::permanentDelete/$1');
    $routes->get('purge-all', 'Admin::purgeAll');
    $routes->get('create', 'Admin::create');
    $routes->post('store', 'Admin::store');
    $routes->get('edit/(:num)', 'Admin::edit/$1');
    $routes->post('update/(:num)', 'Admin::update/$1');
    $routes->get('delete/(:num)', 'Admin::delete/$1');
    $routes->get('restrict/(:num)', 'Admin::restrict/$1'); 
});

// Teacher routes (Protected by role:teacher)
$routes->group('teacher', ['filter' => 'role:teacher'], function ($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses/create', 'Teacher::createCourse');
    $routes->post('courses', 'Teacher::storeCourse');
});

// Student routes (Protected by role:student)
$routes->group('student', ['filter' => 'role:student'], function ($routes) {
    $routes->get('dashboard', 'Student::dashboard');
    $routes->get('announcements', 'Announcement::index');
    $routes->post('enroll/(:num)', 'Student::enroll/$1');
});

// AJAX enrollment endpoint
$routes->post('course/enroll', 'Course::enroll');