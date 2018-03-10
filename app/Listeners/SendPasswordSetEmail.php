<?php

namespace App\Listeners;

use App\Events\AdminCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordSetEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \App\Events\AdminCreated $event
     * @return void
     */
    public function handle(AdminCreated $event)
    {
        \Illuminate\Support\Facades\Mail::to($event->user->email)
            ->queue(new \App\Mail\WelcomeTheAdmin($event->user, $event->referenceUser, $event->passwordSetLink));
    }
}
