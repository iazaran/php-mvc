<?php

use App\Router;

/**
 * Web routes
 */
Router::get('/', 'Controllers\HomeController@index');
Router::get('/posts', 'Controllers\PostController@index');
Router::get('/posts/(:any)', 'Controllers\PostController@show');
Router::get('/posts/create', 'Controllers\PostController@create');
Router::post('/posts/create', 'Controllers\PostController@store');
Router::get('/posts/update/(:any)', 'Controllers\PostController@edit');
Router::put('/posts/update', 'Controllers\PostController@update');
Router::delete('/posts/delete/(:any)', 'Controllers\PostController@delete');

Router::get('/register', 'Controllers\AuthController@registerForm');
Router::post('/register', 'Controllers\AuthController@register');
Router::get('/login', 'Controllers\AuthController@loginForm');
Router::post('/login', 'Controllers\AuthController@login');
Router::post('/logout', 'Controllers\AuthController@logout');

/**
 * API routes
 */
Router::get('/api/posts', 'Controllers\API\PostController@index');
Router::get('/api/posts/(:any)', 'Controllers\API\PostController@show');
Router::post('/api/posts/create', 'Controllers\API\PostController@store');
Router::put('/api/posts/update', 'Controllers\API\PostController@update');
Router::delete('/api/posts/delete/(:any)', 'Controllers\API\PostController@delete');

Router::post('/api/register', 'Controllers\API\AuthController@register');
Router::post('/api/login', 'Controllers\API\AuthController@login');
Router::post('/api/logout', 'Controllers\API\AuthController@logout');

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
