<?php

/**
 * @var Router $route
 */

use System\Routing\Router;

$route->get('/', 'IndexController@index');

//Search
$route->get('/findlist', 'FindController@index');

// Diary
$route->group('diaries', function (Router $r) {
    $r->get('/', 'DiaryController@index');
    $r->get('/{id:\d+}', 'DiaryController@show');

    $r->get('/create', 'User\DiaryController@create');
    $r->get('/{id:\d+}/edit', 'User\DiaryController@edit');
    $r->get('/user/{user_id:\d+}', 'DiaryController@user');
});


// Login, registration, repass, logout
$route->group('/auth', function (Router $route) {
    $route->get('/login', 'Auth\LoginController@login')->middleware('guest');
});
