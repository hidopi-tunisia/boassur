<?php

namespace App\Providers;

use App\Services\SendinBlue\Client;
use Illuminate\Support\ServiceProvider;

class SendinBlueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client(
                key: config('services.sendinblue.api-key'),
            );
        });
    }

    public function boot()
    {
        //
    }
}
