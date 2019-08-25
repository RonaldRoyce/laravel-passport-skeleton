<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;

use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;

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

     self::syncLDAPGroups();

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

    protected static function getAllLDAPGroups()
    {
        $groups = Adldap::search()->groups()->get();

        $allGroups = array();

        foreach ($groups as $group)
        {
                $attrs = $group->getAttributes();

                $group = (object)array("id" => $attrs["gidnumber"][0], "name" => $group->getCommonName());

                $allGroups[] = $group;
        }

	return $allGroups;
    }

    protected static function syncLDAPGroups()
    {
        $ldapRoles = self::getAllLDAPGroups();

        foreach ($ldapRoles as $ldapRole)
        {       
                $role = Role::where('role_id', '=', $ldapRole->id)->get();

		if (!$role || count($role) == 0)
                {
                        $newRole = new Role();

                        $newRole->role_id = $ldapRole->id;
                        $newRole->name = $ldapRole->name;

                        $newRole->save();
                }
        }
    }
}
