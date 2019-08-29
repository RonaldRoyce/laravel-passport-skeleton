<?php

namespace App\Helpers;

use App\Models\Role\Permission;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;

class ModelHelper {

	public static function saveRole($roleId, $roleName)
	{
		if ($roleId == -1)
		{
			$role = Role::where('name', '=', $roleName)->get()->first();

			if (!$role)
			{
				$roleId = Role::max('role_id') + 1;

		                $role = new Role();

        		        $role->role_id = $roleId;
                		$role->name = $roleName;

		                $role->save();
			}

			return $role;
		}

		$role = Role::where('role_id', '=', $roleId)->get()->first();

		if (!$role)
                {
                        $role = new Role();

                        $role->role_id = $roleId;
                        $role->name = $roleName;

			$role->save();
                }
                else if ($role->name != $roleName)
                {
                        $role->name = $roleName;

                        $role->save();
		}

		return $role;
	}

}

