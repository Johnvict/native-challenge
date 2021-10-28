<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->get('/products', 'ProductController@getProducts');
    $router->post('/auth', 'UserController@login');

    $router->group(['prefix' => 'user', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'UserController@getUser');
        $router->get('/products', 'UserController@getUserProducts');
        $router->post('/products', 'UserController@createUserProduct');
        $router->delete('/products/{SKU}', 'UserController@deleteUserProduct');
    });
});
