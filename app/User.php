<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable
{
    use Notifiable; // , CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'must_change_password', 'created_at', 'updated_at', 'last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token','created_at', 'updated_at'
    ];

    protected $attributes = [
     'name' => '',
     'email' => '',
     'password' => '',
     'role_id' => '0',
     'username' => '',
     'must_change_password' => '0',
     'created_at' => null,
     'updated_at' => null,
     'last_login_at' => null,
 ];
 
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
    ];

    public $timestamps = true;

    public function username()
    {
        return $this->username;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role\Role', 'role_id', 'role_id');
    }

    public function hasPermission($pageId)
    {
        if ($this->role->name == env('SITE_ADMIN_ROLE', 'Site Administrators')) {
            return true;
        }

        foreach ($this->role->permissions as $rolePermission) {
            if ($rolePermission->permission->page_id == $pageId) {
                return true;
            }
        }

        return false;
    }

    public static function mapFromLdap($u)
    {
        $user = new \App\User();

        $user->username = $u->getAttributes()['uid'][0];
        $user->password =  $u->getAttributes()['userpassword'][0];
        if (substr($user->password, 0, 1) == '{') {
            $hashMethod = substr($user->password, 1, stripos($user->password, '}'));
            $user->password = substr($user->password, stripos($user->password, '}') + 1);
        }
        $user->role_id = $u->getAttributes()['gidnumber'][0];
        $user->name = $u->getAttributes()['givenname'][0] . " " . $u->getAttributes()['sn'][0];
        $user->email = (array_key_exists('mail', $u->getAttributes()) ? $u->getAttributes()['mail'][0] : '');

        return $user;
    }
}
