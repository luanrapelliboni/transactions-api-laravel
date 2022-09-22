<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\Interfaces\UserServiceInterface',
            'App\Services\UserService'
        );

        $this->app->bind(
            'App\Services\Interfaces\WalletServiceInterface',
            'App\Services\WalletService'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
