<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Role\Permission;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\User;

class ModelHelper
{
    public static function saveRole($roleId, $roleName)
    {
        if ($roleId == -1) {
            $role = Role::where('name', '=', $roleName)->get()->first();

            if (!$role) {
                $roleId = Role::max('role_id');
                
                if (!$roleId) {
                    $roleId = 1;
                } else {
                    $roleId++;
                }
               
                $role = new Role();

                $role->role_id = $roleId;
                $role->name = $roleName;

                $role->save();

                $role->role_id = $roleId;
            }

            return $role;
        }

        $role = Role::where('role_id', '=', $roleId)->get()->first();

        if (!$role) {
            $role = new Role();

            $role->role_id = $roleId;
            $role->name = $roleName;

            $role->save();

            $role->role_id = $roleId;
        } elseif ($role->name != $roleName) {
            $role->name = $roleName;

            $role->save();

            $role->role_id = $roleId;
        }

        return $role;
    }

    public static function createUser($username, $password, $email, $name, $roleId)
    {
        $user = User::where('email', '=', $email)->delete();
        $user = User::where('username', '=', $username)->delete();

        $user = new User();

        $user->name = $name;
        $user->username = $username;
        $user->email = $email;
        $user->role_id = $roleId;
        $user->created_at = $user->updated_at = date('Y-m-d H:i:s');
        $user->password = Hash::make($password);

        $user->save();
    }
}
