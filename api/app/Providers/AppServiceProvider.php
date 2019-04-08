<?php

namespace App\Providers;

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
        //jwt
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
//        dingo/api
        $this->app->register(\Dingo\Api\Provider\LumenServiceProvider::class);

        if (env('APP_DEBUG')) {
            $this->app->register(\Barryvdh\Debugbar\LumenServiceProvider::class);
        }

    }
}
