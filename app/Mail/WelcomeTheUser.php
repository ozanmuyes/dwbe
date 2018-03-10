<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeTheUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var \App\User $user */
    public $user;

    /** @var string $verificationLink */
    public $verificationLink;

    /**
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param string $verificationLink
     */
    public function __construct(User $user, $verificationLink)
    {
        $this->user = $user;
        $this->verificationLink = $verificationLink;

        $this->subject('Welcome to DWBE');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.registered');
    }
}
