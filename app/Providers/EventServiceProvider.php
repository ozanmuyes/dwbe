<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\UserRegistered::class => [
            \App\Listeners\SendEmailAddressValidationEmail::class,
            // NOTE Add other listeners for this event here
        ],
        \App\Events\AdminCreated::class => [
            \App\Listeners\SendPasswordSetEmail::class,
            //
        ],
        // NOTE Add other events here
    ];
}
