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

$router->get('/', function () use ($router) {
    // Send front-end application
    return file_get_contents(base_path('public/index.html'));
});

$router->group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'negotiation'], function () use ($router) {
    $router->group(['prefix' => 'v1', 'namespace' => 'v1'], function () use ($router) {
        $router->post('token', 'TokenController@create'); // login
        $router->delete('token', ['middleware' => 'auth', 'uses' => 'TokenController@delete']); // logout

        $router->group(['prefix' => 'users', 'middleware' => 'auth'], function () use ($router) {
            $router->get('/', 'UserController@index');
            //
        });
    });

    // NOTE Add other versions (e.g. 'v2', 'v3' or default; without prefix) here
});

$router->get('/authenticated', ['middleware' => 'auth', function () use ($router) {
    return response()->json([
        'auth' => Auth::user(),
        //
    ]);
}]);
