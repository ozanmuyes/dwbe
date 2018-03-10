<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailAddressVerificationEmail implements ShouldQueue
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
            ->queue(new \App\Mail\WelcomeTheUser($event->user, $event->verificationLink));
    }
}
