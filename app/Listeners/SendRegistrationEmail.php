<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegistrationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \App\Events\UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        // NOTE Access the user using $event->user
    }
}
