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
    public $user;

    /**
     * @var string $verificationLink
     */
    public $verificationLink;

    /**
     * Create a new event instance.
     *
     * @param \App\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        // Verification link MUST point to the front-end application
        $this->verificationLink = url('/me/verify') . '?' . http_build_query(['token' => $user->verification_token]);
    }
}
