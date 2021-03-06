<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

/** @var \Laravel\Lumen\Application $app */
$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Configure Application
|--------------------------------------------------------------------------
|
| Here we will configure the application with the files in the
| 'config' directory. Please note that this is modification
| to the standard bootstrapping process.
|
*/

$app->configure('database');
$app->configure('queue');
$app->configure('mail');
//

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    App\Http\Middleware\CheckForMaintenanceMode::class,
    //
]);

$app->routeMiddleware([
    'negotiation' => App\Http\Middleware\ContentNegotiation::class,
    'auth' => App\Http\Middleware\Authenticate::class,
    //
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);

$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(App\Providers\MailServiceProvider::class);
if ($app->environment() !== 'production') {
    app('config')->set('app.aliases', [
//        'Validator' => Illuminate\Support\Facades\Validator::class,
//        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Gate' => Illuminate\Support\Facades\Gate::class
    ]);

    $app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function (/** @noinspection PhpUnusedParameterInspection */ $router) {
    require __DIR__.'/../routes/web.php';
});

// NOTE This is SO important for us to be able to send queued emails
$app->make('queue');

return $app;
