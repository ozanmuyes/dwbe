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

// NOTE These (front-end application) routes has been commented out in favor of OpenResty (NGINX).
//$router->get('/', function () {
//    // Send front-end (website) application
//    return file_get_contents(base_path('public/index.html'));
//});
//
//// NOTE Change the path ('/panel') as desired, also set Vue.js Router's `basePath` accordingly
//$router->get('/panel', function () {
//    // Send front-end (control panel) application
//    return file_get_contents(base_path('public/panel/index.html'));
//});

use App\Mail\UserRegistered;

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

// ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST -----
$router->get('/api/ctr', function() {
//    $ctr = Cache::get('ctr');

//    Redis::incr('laravel:ctr');
    $ctr = \Illuminate\Support\Facades\Redis::get('laravel:ctr');

    return "Result: '$ctr'";
});

$router->get('/api/pub', function() {
    \Illuminate\Support\Facades\Redis::publish('test', json_encode(['foo' => 'bar']));
});
// ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST ----- REDIS TEST -----

// ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST -----
$router->get('/api/job', function () {
    dispatch(new App\Jobs\ExampleJob);

    return 'immediately OK @ ' . date('Y-m-d H:m:s');
});
// ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST ----- QUEUE TEST -----

// ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST -----
$router->get('/api/mail', function () {
    /**
     * @var \App\User $user
     */
    $user = App\User::first();


//    /**
//     * @var \Illuminate\Mail\Mailable $mailable
//     */
//    $mailable = new UserRegistered($user);
//    $mailable = $mailable->onConnection('redis')
//        ->onQueue('default');
//
//    \Illuminate\Support\Facades\Mail::to($user->email)->send($mailable);


    \Illuminate\Support\Facades\Mail::to($user->email)
        ->queue(new UserRegistered($user));
});
// ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST ----- MAIL TEST -----
