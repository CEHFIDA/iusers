<?php

namespace Selfreliance\Iusers;

use Illuminate\Support\ServiceProvider;

class IusersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Selfreliance\Iusers\UsersController');
        $this->loadViewsFrom(__DIR__.'/views', 'iusers');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            __DIR__ . '/config/users.php' => config_path('users.php')
        ], 'config');        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
