<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Event
{
    use SerializesModels;

    /**
     * @var \App\User $user
     */
    protected $user;

    /**
     * Create a new event instance.
     *
     * @param \App\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        //
    }
}
