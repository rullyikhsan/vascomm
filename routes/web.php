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


function dashboard($router, $uri, $controller)
{
    $router->get($uri, $controller . '@index');
}
dashboard($router, '/api/dashboard', 'DashboardController');

function frontend($router, $uri, $controller)
{
    $router->get($uri, $controller . '@index');
}
frontend($router, '/frontend', 'FrontendController');

function products($router, $uri, $controller)
{
    $router->get($uri, $controller . '@index');
    $router->post($uri . '/view/datatables', $controller . '@dataTables');
    $router->post($uri, $controller . '@store');

    $router->get($uri . '/{id}', $controller . '@show');
    $router->put($uri . '/{id}', $controller . '@update');
    $router->patch($uri . '/{id}', $controller . '@update');

    $router->delete($uri . '/{id}', $controller . '@delete');
}
products($router, '/products', 'ProductController');

function users($router, $uri, $controller)
{
    $router->get($uri, $controller . '@index');
    $router->post($uri . '/view/datatables', $controller . '@dataTables');
    $router->post($uri, $controller . '@store');

    $router->get($uri . '/{id}', $controller . '@show');
    $router->put($uri . '/{id}', $controller . '@update');
    $router->patch($uri . '/{id}', $controller . '@update');

    $router->delete($uri . '/{id}', $controller . '@delete');
}
users($router, '/users', 'UserController');
