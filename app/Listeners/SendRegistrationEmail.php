<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\MailDispatcher;
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

//        Mail::to($event->user->email)
//            ->send(new \App\Mail\UserRegistered($event->user));
////            ->queue(new \App\Mail\UserRegistered($event->user));
        $mail = new \App\Mail\UserRegistered($event->user);
        dispatch(new MailDispatcher($mail));
    }
}
