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

    /** @var \App\User $user */
    public $user;

    /** @var \App\TokenUser $referenceUser */
    public $referenceUser;

    /** @var string $passwordSetLink */
    public $passwordSetLink;

    /**
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param \App\TokenUser $referenceUser
     * @param string $passwordSetLink
     */
    public function __construct(User $user, TokenUser $referenceUser, $passwordSetLink)
    {
        $this->user = $user;
        $this->referenceUser = $referenceUser;
        $this->passwordSetLink = $passwordSetLink;

        $this->subject('Welcome to DWBE');
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
