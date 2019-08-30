<?php
namespace App\Helpers;

use Adldap\Laravel\Facades\Adldap;
use App\Helpers\ConfigHelper;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\Models\Role\Permission;
use \Exception;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\ModelHelper;

class LdapHelper
{
    public static function get($dn, $operator, $value)
    {
        return Adldap::search()->where($dn, $operator, $value)->get();
    }

    public static function createOu($dn)
    {
        $ou = Adldap::make()->ou(["dn" => 'ou=' . $dn . ','. env('LDAP_BASE_DN') ]);

        $ou->save();

        return $ou;
    }

    public static function getLDAPGroup($groupName)
    {
        return Adldap::search()->groups()->where('cn', '=', $groupName)->get()->first();
    }

    public static function getAllLDAPGroups()
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver != "ldap") {
            return;
        }

        $groups = Adldap::search()->groups()->get();

        $allGroups = array();

        foreach ($groups as $group) {
            $attrs = $group->getAttributes();

            $group = (object)array("id" => $attrs["gidnumber"][0], "name" => $group->getCommonName());

            $allGroups[] = $group;
        }

        return $allGroups;
    }

    protected static function RoleExists($dbRole, $ldapRoles)
    {
        foreach ($ldapRoles as $ldapRole) {
            if ($ldapRole->id == $dbRole->role_id) {
                return true;
            }
        }

        return false;
    }

    public static function syncLDAPGroups()
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver != "ldap") {
            return;
        }

        $ldapRoles = self::getAllLDAPGroups();

        foreach ($ldapRoles as $ldapRole) {
            $role = Role::where('role_id', '=', $ldapRole->id)->get();

            if (!$role || count($role) == 0) {
                $newRole = new Role();

                $newRole->role_id = $ldapRole->id;
                $newRole->name = $ldapRole->name;

                $newRole->save();
            }
        }

        $dbRoles = Role::all();

        foreach ($dbRoles as $dbRole) {
            if (!self::RoleExists($dbRole, $ldapRoles)) {
                $dbRole->delete();
            }
        }
    }

    public static function createGroup($roleId, $roleName)
    {
        if ($roleId == 0) {
            $groups = self::getAllLDAPGroups();

            $maxId = 0;

            foreach ($groups as $group) {
                if ($group->id > $maxId) {
                    $maxId = $group->id;
                }
            }

            $roleId = $maxId + 1;
        }

        $group = Adldap::make()->group(['gidnumber' => $roleId, 'objectclass' => ['top', 'posixGroup']]);

        $dn = $group->getDnBuilder();

        $dn->addOu('groups');

        $dn->addCn($roleName);

        $group->setDn($dn);

        $group->save();
    
        return $group;
    }

    public static function createUser($firstName, $lastName, $username, $password)
    {
        $user = Adldap::make()->user();

        $user->setCommonName($username);
        $user->setDisplayName($firstName . " " . $lastName);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPassword($password);

        $dn = $user->getDnBuilder();

        $dn->addCn($user->getCommonName());
        $dn->addOu('Testusers');

        $baseDn = env(LDAP_BASE_DN);

        $parts = explode(",", $baseDn);

        foreach ($parts as $part) {
            $dn->addDc(str_replace("dc=", "", $part));
        }

        echo "DN: [" . $dn->get() . "]\n";

        $user->setDn($dn);
        $user->save();
    }

    public static function getUsers()
    {
        return self::get('objectclass', '=', 'posixAccount');
    }

    public static function syncLdapUsers()
    {
        DB::table('users')->truncate();

        $users = LdapHelper::getUsers();

        foreach ($users as $ldapUser) {
            $user = User::mapFromLdap($ldapUser);

            $user->save();
        }
    }

    public static function syncLdapSiteAdminRole($siteAdminGroupName, $ldapGroupsGroup)
    {
        $ou = self::get('ou', '=', $ldapGroupsGroup);
        if (count($ou) == 0) {
            self::createOu($ldapGroupsGroup);
        }

        $siteAdminLDAPGroup = self::getLDAPGroup($siteAdminGroupName);
        $siteAdminGroupId = 0;

        if (!$siteAdminLDAPGroup) {
            $siteAdminLDAPGroup = self::createGroup($siteAdminGroupId, $siteAdminGroupName);
            $siteAdminGroupId = $siteAdminLDAPGroup->getAttributes()['gidnumber'][0];
        } else {
            $siteAdminGroupId = $siteAdminLDAPGroup->getAttributes()['gidnumber'][0];
        }
 
        return ModelHelper::saveRole($siteAdminGroupId, $siteAdminGroupName);
    }
}
