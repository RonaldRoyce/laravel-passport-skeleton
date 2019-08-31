<?php
namespace App\Providers;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use App\Providers\UsernamePasswordBrokerManager;

class UsernamePasswordResetServiceProvider extends PasswordResetServiceProvider
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new UsernamePasswordBrokerManager($app);
        });
 
        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}
