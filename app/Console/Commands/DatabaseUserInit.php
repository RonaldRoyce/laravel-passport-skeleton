<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role\Permission;
use App\Models\Role\RolePermission;
use App\Models\Role\Role;
use App\User;
use App\Helpers\LdapHelper;
use App\Helpers\ConfigHelper;
use App\Helpers\ModelHelper;
use \Exception;

class DatabaseUserInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes database with default user, roles, permissions, and role permissions';
/*

    protected $permissionData = array(
                                (object)array("permission_id" => 1, "name" => "User Management", "page_id" => "usermanagement-index"),
                                (object)array("permission_id" => 2, "name" => "User Management - Add",  "page_id" => "usermanagement-add"),
                                (object)array("permission_id" => 3, "name" => "User Management - Edit",  "page_id" => "usermanagement-edit"),
                                (object)array("permission_id" => 4, "name" => "User Management - Delete",  "page_id" => "usermanagement-delete"),

                                (object)array("permission_id" => 5, "name" => "Role Management",  "page_id" => "rolemanagement-index"),
                                (object)array("permission_id" => 6, "name" => "Role Management - Add",  "page_id" => "rolemanagement-add"),
                                (object)array("permission_id" => 7, "name" => "Role Management - Edit",  "page_id" => "rolemanagement-edit"),
                                (object)array("permission_id" => 8, "name" => "Role Management - Delete",  "page_id" => "rolemanagement-delete"),

                                (object)array("permission_id" => 9, "name" => "Role Permission Management",  "page_id" => "rolepermissionmanagement-index"),
                                (object)array("permission_id" => 10, "name" => "Role Permission Management - Add",  "page_id" => "rolepermissionmanagement-add"),
                                (object)array("permission_id" => 11, "name" => "Role Permission Management - Edit",  "page_id" => "rolepermissionmanagement-edit"),
                                (object)array("permission_id" => 12, "name" => "Role Permission Management - Delete",  "page_id" => "rolepermissionmanagement-delete"),

                                (object)array("permission_id" => 13, "name" => "Permission Management",  "page_id" => "permissionmanagement-index"),
                                (object)array("permission_id" => 14, "name" => "Permission Management - Add",  "page_id" => "permissionmanagement-add"),
                                (object)array("permission_id" => 15, "name" => "Permission Management - Edit",  "page_id" => "permissionmanagement-edit"),
                                (object)array("permission_id" => 16, "name" => "Permission Management - Delete",  "page_id" => "permissionmanagement-delete"),
                                );
*/
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try
        {
		LdapHelper::createUser("Site", "Administrator", env('SITE_ADMIN_USERNAME', 'siteadmin'), env('SITE_ADMIN_DEFAULT_PASSWORD', 'secret'));

		return;

                DB::table('users')->truncate();
                DB::table('roles')->truncate();
                DB::table('permissions')->truncate();
                DB::table('role_permissions')->truncate();
        }
        catch(Exception $ex)
        {
                $this->error('Exception: ' . $ex->getMessage());
                return;
        }

	$siteAdminGroupName = env('LDAP_SITE_ADMIN_CN', 'Site Administrators');
	$ldapGroupsGroup = env('LDAP_GROUPS_CN', 'groups');

        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver == "ldap")
        {
		try
		{
			$ou = LdapHelper::get('ou', '=', $ldapGroupsGroup);
			if (count($ou) == 0)
			{	
				LdapHelper::createOu($ldapGroupsGroup);
			}

			$siteAdminLDAPGroup = LdapHelper::getLDAPGroup($siteAdminGroupName);
			$siteAdminGroupId = 0;

			if (!$siteAdminLDAPGroup)
			{
				$siteAdminLDAPGroup = LdapHelper::createGroup(0, $siteAdminGroupName);

				$siteAdminGroupId = $siteAdminLDAPGroup->getAttributes()['gidnumber'][0];

				$role = ModelHelper::saveRole($siteAdminGroupId, $siteAdminGroupName);
			}
			else
			{
				$siteAdminGroupId = $siteAdminLDAPGroup->getAttributes()['gidnumber'][0];

                                $role = ModelHelper::saveRole($siteAdminGroupId, $siteAdminGroupName);
			}


			

		}
		catch(Exception $ex)
		{
			$this->error('Exception: ' . $ex->getMessage());
			return;
		}
        }
	else
	{
		$role = ModelHelper::addRole(-1, $siteAdminGroupName);
	}


	try
	{
		$permissionsData = require dirname(__FILE__) . "/permissions.conf.php";

		foreach ($permissionData as $permission)
		{
		
			$permission = new Permission();

			$permission->permission_id = $permission->permission_id;
			$permission->name = $permission->name;
			$permission->page_id = $permission->page_id;
		}
	}
	catch(Exception $ex)
	{
		$this->error('Exception: ' . $ex->getMessage());
                return;
	}

    }
}
