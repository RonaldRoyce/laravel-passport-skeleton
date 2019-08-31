<?php
namespace App\Providers;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Carbon;

class UsernameDatabaseTokenRepository extends DatabaseTokenRepository
{
    public function exists(CanResetPassword $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'username',
            $user->username
        )->first();

        $rc = ($token == $record['token']);
        $tokenHasExpired = $this->tokenExpired($record['created_at']);
 
        return $record && !$tokenHasExpired && $rc;
    }

    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    protected function deleteExisting(CanResetPassword $user)
    {
        return $this->getTable()->where('username', $user->username)->delete();
    }
}
