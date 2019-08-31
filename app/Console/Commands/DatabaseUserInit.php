<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Role\Permission;
use App\Models\Role\RolePermission;
use App\Models\Role\Role;
use App\User;
use App\Helpers\LdapHelper;
use App\Helpers\ConfigHelper;
use App\Helpers\ModelHelper;
use App\Helpers\PermissionHelper;

use \Exception;

class DatabaseUserInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:authinit';

    protected $siteAdminGroupName;
    protected $ldapGroupsGroup;
    
    protected $siteAdminUsername;
    protected $siteAdminEmail;
    protected $siteAdminPassword;
    protected $siteAdminName;
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes database with default user, roles, permissions, and role permissions';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->siteAdminGroupName = env('SITE_ADMIN_ROLE', 'Site Administrators');
        $this->ldapGroupsGroup = env('LDAP_GROUPS_CN', 'groups');
        $this->siteAdminUsername = env('SITE_ADMIN_USERNAME', 'siteadmin');
        $this->siteAdminPassword = env('SITE_ADMIN_DEFAULT_PASSWORD', 'secret');
        $this->siteAdminEmail = env('SITE_ADMIN_EMAIL', 'siteadmin@example.com');
        $this->siteAdminName = env('SITE_ADMIN_NAME', 'Application Site Administrator');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        $this->info("Current driver is: " . $providerConfig->driver);

        try {
            PermissionHelper::setToDefault();

            $this->info('Permissions reset to default values');

            if ($providerConfig->driver == "ldap") {
                LdapHelper::syncLdapUsers();
                $this->info("LDAP Users synched");
                $role = LdapHelper::syncLdapSiteAdminRole($this->siteAdminGroupName, $this->ldapGroupsGroup);
                $this->info("LDAP Site Admin Role synched");
            } else {
                DB::table('roles')->truncate();

                $role = ModelHelper::saveRole(-1, $this->siteAdminGroupName);

                $this->info("Database Site Admin Role added");

                ModelHelper::createUser($this->siteAdminUsername, $this->siteAdminPassword, $this->siteAdminEmail, $this->siteAdminName, $role->role_id);

                $this->info('Database site admin role added');
            }
            
            // Initialize Role Permissions for site admin

            PermissionHelper::initRolePermission($role->role_id);

            $this->info('Role Permissions for site administrator added');
        } catch (Exception $ex) {
            $this->error('Exception: ' . $ex->getMessage());
            return;
        }
    }
}
