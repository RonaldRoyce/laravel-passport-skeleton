<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;

use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LdapHelper;
use App\Helpers\ConfigHelper;

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
	$user = Auth::user();

	if ($user->role == null)
	{
        	return redirect('/notauthorized');
     	}	         

        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

	if ($providerConfig->driver == "ldap")
        {
		LdapHelper::syncLDAPGroups();
	}

	$rolePermissionsList = array();

        $rolesDefined = Role::all();
 
        $rolesPermissionsList = array();
        $rolesDefine = array();

	foreach ($rolesDefined as $definedRole)
	{
		$roleId = $definedRole->role_id;
		$roleName = $definedRole->name;

		$rolePermissions = RolePermission::where('role_id', '=', $roleId)->get();

		if (count($rolePermissions) == 0)
		{
			$rolePermissionsList[] = array("role_id" => $roleId, "name" => $roleName, "permissions" => '');
			continue;
		}

		$permissionNames = "";

		foreach ($rolePermissions as $rolePermission)
		{
			$role = $rolePermission->role;
			$permission = $rolePermission->permission;

			if ($permissionNames != "")
			{
				$permissionNames .= ", ";
			}
		
			$permissionNames .= $permission->name;
		}
	
		$rolePermissionsList[] = array("role_id" => $roleId, "name" => $roleName, "permissions" => $permissionNames);
	}

        return view('rolepermission.index', ["rolepermissions" => (object)$rolePermissionsList]);
    }
}
