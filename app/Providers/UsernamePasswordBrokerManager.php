<?php
namespace App\Providers;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use \Str;

class UsernamePasswordBrokerManager extends PasswordBrokerManager
{
    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];
 
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
 
        $connection = $config['connection'] ?? null;
 
        return new UsernameDatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire']
        );
    }
}
