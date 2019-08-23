<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  	protected $table = 'permissions';
	protected $primaryKey = 'permission_id';
	protected $fillable = array('permission_id', 'name', 'page_id');
	protected $hidden = array('granted');

	public $timestamps = false;

	private $permission_id = 0;
	private $name = "";
	private $page_id = "";
	private $granted = false;

	public function getId()
	{
		return $this->permission_id;
	}

	public function setid($id)
	{
		$this->permission_id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	} 

	public function getPageId()
	{
		return $this->page_id;
	}

	public function setPageId($pageId)
	{
		$this->page_id = $pageId;
	}

	public function getGranted()
	{
		return $this->granted;
	}

	public function setGranted()
	{
		$this->granted = true;
	}
}
