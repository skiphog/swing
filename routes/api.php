<?php

use System\Routing\Router;

/**
 * @var Router $route
 */

// Login, registration, repass, logout
$route->group('/auth', function (Router $route) {
    $route->post('/login', 'Auth\LoginController@auth')->middleware('guest');
    $route->get('/quit', 'Auth\LoginController@quit')->middleware('auth');
});
