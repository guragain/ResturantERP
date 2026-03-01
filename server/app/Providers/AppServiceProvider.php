<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Bind Repositories to interfaces
        $this->app->bind(
            \App\Contracts\Interfaces\IAuthRepo::class,
            \App\Contracts\Implementation\AuthRepo::class
        );


        //Bind Services to interfaces
        $this->app->bind(
            \App\Services\Interfaces\IAuthService::class,
            \App\Services\Implementation\AuthService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
