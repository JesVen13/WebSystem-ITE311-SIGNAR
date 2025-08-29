<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');// normal home
$routes->get('about', 'Home::about'); //About
$routes->get('contact', 'Home::contact');//Contact
$routes->get('MyLogin', 'Home::MyLogin');// MyLogin page

