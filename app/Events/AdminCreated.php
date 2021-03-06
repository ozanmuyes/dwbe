<?php

namespace App\Events;

use App\TokenUser;
use App\User;
use Illuminate\Queue\SerializesModels;

class AdminCreated extends Event
{
    use SerializesModels;

    /**
     * @var \App\User $user
     */
    public $user;

    /**
     * @var \App\TokenUser $referenceUser
     */
    public $referenceUser;

    /**
     * @var string $passwordSetLink
     */
    public $passwordSetLink;

    /**
     * Create a new event instance.
     *
     * @param \App\User $user
     * @param \App\TokenUser $referenceUser
     */
    public function __construct(User $user, TokenUser $referenceUser)
    {
        $this->user = $user;
        $this->referenceUser = $referenceUser;
        // Password set link MUST point to the front-end application
        $this->passwordSetLink = url('/me/password') . '?' . http_build_query(['token' => $user->password_set_token]);
    }
}
