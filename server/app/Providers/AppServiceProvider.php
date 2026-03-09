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
        // Bind Repositories to interfaces
        $this->app->bind(
            \App\Repositories\Auth\AuthRepositoryInterface::class,
            \App\Repositories\Auth\EloquentAuthRepository::class
        );

        $this->app->bind(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\EloquentUserRepository::class
        );

        // Bind Services to interfaces
        $this->app->bind(
            \App\Services\Auth\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
        );

        $this->app->bind(
            \App\Services\User\UserServiceInterface::class,
            \App\Services\User\UserService::class
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
