<?php

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

/**
 * @var $router Laravel\Lumen\Routing\Router
 */

$router->get('/', function () {
    // Send front-end (website) application
    return file_get_contents(base_path('public/index.html'));
});

// NOTE Change the path ('/panel') as desired, also set Vue.js Router's `basePath` accordingly
$router->get('/panel', function () {
    // Send front-end (control panel) application
    return file_get_contents(base_path('public/panel/index.html'));
});

$router->group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'negotiation'], function () use ($router) {
    $router->group(['prefix' => 'v1', 'namespace' => 'v1'], function () use ($router) {
        $router->post('token', 'TokenController@create');
        $router->put('token', 'TokenController@refresh');
        $router->delete('token', 'TokenController@delete');

        $router->get('users', 'UserController@index');
        $router->post('users', 'UserController@create');
        $router->get('users/{id}', 'UserController@view');
    });

    // NOTE Add other versions (e.g. 'v2', 'v3' or default; without prefix) here
});
