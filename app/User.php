<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at', 'updated_at'
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
	if ($this->role->name == "Super User")
	{
		return true;
	}

	foreach ($this->role->permissions as $rolePermission)
	{
		if ($rolePermission->permission->page_id == $pageId)
		{
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
	if (substr($user->password, 0, 1) == '{')
	{
		$hashMethod = substr($user->password, 1, stripos($user->password, '}'));
		$user->password = substr($user->password, stripos($user->password, '}') + 1);
	}
	$user->role_id = $u->getAttributes()['gidnumber'][0];	
	$user->name = $u->getAttributes()['givenname'][0] . " " . $u->getAttributes()['sn'][0];
	$user->email = (array_key_exists('mail', $u->getAttributes()) ? $u->getAttributes()['mail'][0] : '');

	return $user;
    }
}
