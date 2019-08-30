<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\Models\Role\Permission;

class PermissionHelper
{
    public static function setToDefault()
    {
        $permissionsData = require dirname(__FILE__) . "/../../config/permissions.conf.php";

        DB::table('permissions')->truncate();

        foreach ($permissionsData as $permission) {
            $newPermission = new Permission();

            $newPermission->permission_id = $permission["permission_id"];
            $newPermission->name = $permission["name"];
            $newPermission->page_id = $permission["page_id"];

            $newPermission->save();
        }
    }

    public static function initRolePermission($roleId)
    {
        DB::table('role_permissions')->truncate();

        $allPermissions = Permission::all();

        foreach ($allPermissions as $permission) {
            $rolePermission = new RolePermission();

            $rolePermission->role_id = $roleId;
            $rolePermission->permission_id = $permission->permission_id;

            $rolePermission->save();
        }
    }
}
