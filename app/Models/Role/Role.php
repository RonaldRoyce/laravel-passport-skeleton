<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  	protected $table = 'roles';
	protected $primaryKey = 'role_id';
	protected $fillable = array('role_id', 'name');
	public $timestamps = false;

	private $role_id = 0;
	private $name = "";

	public function getId()
	{
		return $this->role_id;
	}

	public function setid($id)
	{
		$this->role_id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	} 

    	public function users()
    	{
        	return $this->hasMany('App\User', 'role_id', 'role_id');
    	}

	public function permissions()
	{
		return $this->hasMany('App\Models\Role\RolePermission', 'role_id', 'role_id');
	}
}
