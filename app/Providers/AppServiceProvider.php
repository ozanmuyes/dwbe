<?php

namespace App\Providers;

use App\TranslatorMock;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // To enable mail support and do NOT want to use any translation;
        $this->app->singleton(Translator::class, function () {
            return new TranslatorMock;
        });

        //
    }
}
