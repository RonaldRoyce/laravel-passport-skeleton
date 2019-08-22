<?php

namespace App\Models\Ldap;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  	protected $table = 'groups';
	protected $primaryKey = 'group_id';
	protected $fillable = array('group_id', 'name');
	public $timestamps = false;

	private $group_id = 0;
	private $name = "";

	public function getId()
	{
		return $this->group_id;
	}

	public function setid($id)
	{
		$this->group_id = $id;
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
        	return $this->hasMany('App\User');
    	}
}
