<?php

namespace App\Mail;

use App\TokenUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeTheAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var \App\User $user
     */
    public $user;

    /**
     * @var \App\TokenUser $referenceUser
     */
    public $referenceUser;

    /**
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param \App\TokenUser $referenceUser
     */
    public function __construct(User $user, TokenUser $referenceUser)
    {
        $this->user = $user;
        $this->referenceUser = $referenceUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.registered');
    }
}
