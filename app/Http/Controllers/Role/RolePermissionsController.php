<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;

use App\Models\Role\RolePermission;
use App\Http\Controllers\Controller;

class RolePermissionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
	$rolePermissions = RolePermission::all();

	$rolePermissionsList = array();

	$rolesDefined = array();

	foreach ($rolePermissions as $rolePermission)
	{
		$role = $rolePermission->role;
		$permissions = $rolePermission->permissions;

		$permissionNames = "";

		foreach ($permissions as $permission)
		{
			if ($permissionNames != "")
			{
				$permissionNames .= ", ";
			}

			$permissionNames .= $permission->name;
		}
			
		if (!array_key_exists($role->name, $rolesDefined))
		{
			$rolePermissionsList[] = array("role_id" => $role->role_id, "name" => $role->name, "permissions" => $permissionNames);
			$rolesDefined[$role->name] = count($rolePermissionsList) - 1;
		}
		else {
			$rolePermissionsList[$rolesDefined[$role->name]]["permissions"] .= ", " . $permissionNames;
		}
	}

        return view('rolepermission.index', ["rolepermissions" => (object)$rolePermissionsList]);
    }
}
