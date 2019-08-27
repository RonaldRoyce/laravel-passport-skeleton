<?php
namespace App\Helpers;

use Adldap\Laravel\Facades\Adldap;
use App\Helpers\ConfigHelper;
use App\Models\Role\Role;

class LdapHelper {

    protected static function getAllLDAPGroups()
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver != "ldap")
        {
                return;
        }

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

    protected static function RoleExists($dbRole, $ldapRoles)
    {
        foreach ($ldapRoles as $ldapRole)
        {
                if ($ldapRole->id == $dbRole->role_id)
                {
                        return true;
                }
        }

        return false;
    }

    public static function syncLDAPGroups()
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver != "ldap")
        {
                return;
        }

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

        $dbRoles = Role::all();

        foreach ($dbRoles as $dbRole)
        {
                if (!self::RoleExists($dbRole, $ldapRoles))
                {
                        $dbRole->delete();
                }
        }

    }
}


