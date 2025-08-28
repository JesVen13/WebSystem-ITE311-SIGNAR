<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');         // normal home
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->get('MyHome', 'Home::myhome');   // route for MyHome page

