<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Log;
use App\Models\Role\RolePermission;
use App\Models\Role\Permission;

class RoleApiController extends Controller
{
	protected $_adldap;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $adldap)
    {
	$this->_adldap = $adldap;

        $this->middleware('client_credentials');
    }

    public function create(Request $request)
    {
	// $group = $this->_adldap->make()->group();

	$d = date("Y-m-d H:i:s");

	$roleId = $request->all()['roleid'];
	$roleName = $request->all()['rolename'];

	$group = $this->_adldap->make()->group(['gidnumber' => $roleId, 'objectclass' => ['top', 'posixGroup']]);

	$dn = $group->getDnBuilder();

	$dn->addOu('groups');

	$dn->addCn($roleName);

	$group->setDn($dn);

	try
	{
		$group->create();

		$group = null;

	        return array("success" => true, "message" => "");
	}
	catch(\Exception $ex)
	{
		Log::debug("$d - GroupApiController error creating: " . $ex->getMessage());
		return array("succces" => false, "message" => "Error saving: " . $ex->getMessage());
	}
    }
    public function delete(Request $request)
    {
	$roleName =  $request->all()['rolename'];

        $groups = $this->_adldap->search()->groups()->get();

        foreach ($groups as $group)
        {
		$attrs = $group->getAttributes();

		$groupName = $attrs['cn'][0];

		if ($groupName == $roleName)
		{
			$group->delete();
			return array("success" => true, "message" => "Group has been deleted");
		}
        }

	return array("success" => true, "message" => "");
    }

    private function setPermissionGranted(&$permissions, $permission)
    {
	for($i = 0; $i < count($permissions); $i++)
	{
		if ($permissions[$i]["permission_id"] == $permission->permission_id)
		{
			$permissions[$i]["granted"] = true;
			return;
		}
	}
    }

    public function getRolePermissions(Request $request)
    {
	$roleId = $request->all()['roleid'];

        \Illuminate\Support\Facades\Log::debug("Calling RolePermission::where('role_id', $roleId)");

        $rolePermissions = RolePermission::where('role_id', "=", $roleId)->get();

        $rolePermissionsList = array();

        $rolesDefined = array();

	$data = null;

	$allPermissions = Permission::all()->sortBy('name');

	$outputPermissions = array();

        foreach ($allPermissions as $permission)
        {
                $outputPermissions[] = array("permission_id" => $permission->permission_id, "name" => $permission->name, "page_id" => $permission->page_id, "granted" => false);
        }

        foreach ($rolePermissions as $rolePermission)
        {
                $role = $rolePermission->role;
		$roleId = $role->role_id;
		$roleName = $role->name;
                $permissions = $rolePermission->permissions;

		if (!$data)
		{
			$data = array("role_id" => $roleId, "role_name" => $roleName);
		}
			
		foreach ($permissions as $permission)
		{
			$this->setPermissionGranted($outputPermissions, $permission);
		}
        }

	if ($data)
	{
		$data["permissions"] = $outputPermissions;
	}

	return $data;
    }

    public function addRolePermissions(Request $request)
    {
	$roleId = $request->all()['roleid'];

	$permissions = $request->all()['permissions'];

        DB::transaction(function() use ($question, $questionCategory) {
		RolePermission::where('role_id', '=', $roleId)->delete();

		foreach ($permissions as $permissionId)
		{
		        $rolePermissions = new RolePermission();

		        $rolePermissions->setRoleId($roleId);
			$rolePermissions->setPermissionId($permissionId);

		        $rolePermissions->save();
		}
        });

	return array("success" => true, "message" => "");
    }
}

