<?php

namespace App\Providers;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);
    }
}
