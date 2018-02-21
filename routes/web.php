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

// TODO Move this to FrontController
$router->get('/', function () {
    // Send front-end application
    return file_get_contents(base_path('public/index.html'));
});

// TODO Specify middlewares within the controllers \
//      See https://lumen.laravel.com/docs/5.6/controllers#controller-middleware
$router->group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'negotiation'], function () use ($router) {
    $router->group(['prefix' => 'v1', 'namespace' => 'v1'], function () use ($router) {
        $router->post('token', 'TokenController@create');
        $router->delete('token', ['middleware' => 'auth', 'uses' => 'TokenController@delete']);

        $router->put('token', 'TokenController@refresh');

        $router->group(['prefix' => 'users', 'middleware' => 'auth'], function () use ($router) {
            $router->get('/', 'UserController@index');

            $router->get('{id}', 'UserController@view');
        });
        $router->post('users', 'UserController@create');
    });

    // NOTE Add other versions (e.g. 'v2', 'v3' or default; without prefix) here
});

$router->get('/authenticated', ['middleware' => 'auth', function () {
    /**
     * @var \App\TokenUser $user
     */
    $user = Auth::user();

    return response()->json([
        'data' => [
            'user' => ((array) $user)["\0*\0attributes"],
            //
        ],
    ]);
}]);
