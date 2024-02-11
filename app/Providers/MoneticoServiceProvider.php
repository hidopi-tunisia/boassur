<?php

namespace App\Providers;

use App\Services\Monetico\Monetico;
use Illuminate\Support\ServiceProvider;

class MoneticoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Monetico::class, function ($app) {
            return new Monetico(
                eptCode: config('services.monetico.etp'),
                securityKey: config('services.monetico.key'),
                companyCode: config('services.monetico.company'),
            );
        });
    }

    public function boot()
    {
        //
    }
}
