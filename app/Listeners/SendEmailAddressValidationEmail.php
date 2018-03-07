<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

// TODO Also in another (before) event OR in the record creation process create a (verification) token
class SendEmailAddressValidationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \App\Events\UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        \Illuminate\Support\Facades\Mail::to($event->user->email)
            ->queue(new \App\Mail\WelcomeTheUser($event->user));
    }
}
