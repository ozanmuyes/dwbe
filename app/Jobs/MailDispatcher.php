<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Facades\Mail;

class MailDispatcher extends Job
{
    public $mail;

    /**
     * MailDispatcher constructor.
     *
     * @param $mail
     */
    public function __construct(Mailable $mail)
    {
        $this->mail = $mail;
    }

    public function handle(Queue $queue)
    {
        Mail::setQueue($queue)->send($this->mail);
    }
}
