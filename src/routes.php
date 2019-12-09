<?php

use App\Router;

/**
 * Web routes
 */
Router::get('/', 'Controllers\HomeController@index');
Router::get('/blog', 'Controllers\BlogController@index');
Router::get('/blog/(:any)', 'Controllers\BlogController@show');
Router::get('/blog/create', 'Controllers\BlogController@create');
Router::post('/blog/create', 'Controllers\BlogController@store');
Router::get('/blog/update/(:any)', 'Controllers\BlogController@edit');
Router::put('/blog/update', 'Controllers\BlogController@update');
Router::delete('/blog/delete/(:any)', 'Controllers\BlogController@delete');

Router::get('/register', 'Controllers\AuthController@registerForm');
Router::post('/register', 'Controllers\AuthController@register');
Router::get('/login', 'Controllers\AuthController@loginForm');
Router::post('/login', 'Controllers\AuthController@login');
Router::get('/logout', 'Controllers\AuthController@logout');

/**
 * API routes
 */
Router::get('/api/blog', 'Controllers\API\BlogController@index');
Router::get('/api/blog/(:any)', 'Controllers\API\BlogController@show');
Router::post('/api/blog/create', 'Controllers\API\BlogController@store');
Router::put('/api/blog/update', 'Controllers\API\BlogController@update');
Router::delete('/api/blog/delete/(:any)', 'Controllers\API\BlogController@delete');

Router::post('/api/register', 'Controllers\API\AuthController@register');
Router::post('/api/login', 'Controllers\API\AuthController@login');
Router::get('/api/logout', 'Controllers\API\AuthController@logout');

/**
 * There is no route defined for a certain location
 */
Router::error(function () {
    die('404 Page not found');
});

/**
 * Uncomment this function to migrate tables
 * It will commented automatically again
 */
// createTables();

Router::dispatch();
