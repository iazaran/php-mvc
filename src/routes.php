<?php

use App\Router;

/**
 * Web routes
 */
Router::get('/', 'HomeController@index');
Router::get('/blog', 'BlogController@index');
Router::get('/blog/(:any)', 'BlogController@show');
Router::get('/blog/create', 'BlogController@create');
Router::post('/blog/create', 'BlogController@store');
Router::get('/blog/update/(:any)', 'BlogController@edit');
Router::post('/blog/update', 'BlogController@update');
Router::delete('/blog/delete/(:any)', 'BlogController@delete');

Router::get('/register', 'AuthController@registerForm');
Router::post('/register', 'AuthController@register');
Router::get('/login', 'AuthController@loginForm');
Router::post('/login', 'AuthController@login');
Router::get('/logout', 'AuthController@logout');

/**
 * API routes
 */
Router::get('/api/blog', 'API\BlogController@index');
Router::get('/api/blog/(:any)', 'API\BlogController@show');
Router::post('/api/blog/create', 'API\BlogController@store');
Router::put('/api/blog/update', 'API\BlogController@update');
Router::delete('/api/blog/delete/(:any)', 'API\BlogController@delete');

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
