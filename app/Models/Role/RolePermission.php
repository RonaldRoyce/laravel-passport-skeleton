<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
  	protected $table = 'role_permissions';
	protected $primaryKey = 'role_permission_id';
	protected $fillable = array('role_id', 'permission_id');
	public $timestamps = false;

	private $role_id = 0;
	private $permission_id = 0;
	private $role_permission_id = 0;

	public function getId()
	{
		return $this->role_permision_id;
	}

	public function setId($id)
	{
		$this->role_permission_id = $id;
	}

	public function getRoleId()
	{
		return $this->role_id;
	}

	public function setRoleid($id)
	{
		$this->role_id = $id;
	} 

	public function getPermissionId()
	{
		return $this->permission_id;
	}

	public function setPermissionId($id)
	{
		$this->permission_id = $id;
	}

    	public function permissions()
    	{
        	return $this->hasMany('App\Models\Role\Permission', 'permission_id', 'permission_id');
    	}

	public function role()
	{
		return $this->hasOne('App\Models\Role\Role', 'role_id', 'role_id');
	}
}
