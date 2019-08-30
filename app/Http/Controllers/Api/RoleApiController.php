<?php

namespace App\Http\Controllers\Api;

use Adldap\AdldapInterface;
use App\Http\Controllers\Controller;
use App\Models\Role\Permission;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\LdapHelper;

class RoleApiController extends Controller
{
    protected $_adldap;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $adldap)
    {
        $this->_adldap = $adldap;

        $this->middleware('client_credentials');
    }

    public function create(Request $request)
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        try {
            if ($providerConfig->driver == "ldap") {
                $this->createLDAPGroup($request);
            } else {
                $this->createDatabaseGroup($request);
            }

            return array("success" => true, "message" => "");
        } catch (\Exception $ex) {
            return array("success" => false, "message" => "Error creating role: " . $ex->getMessage());
        }
    }

    protected function createDatabaseRole(Request $request)
    {
        $roleId = $request->all()['roleid'];
        $roleName = $request->all()['rolename'];

        $role = new Role();

        $role->role_id = $roleId;
        $role->name = $roleName;

        $role->save();
    }

    protected function createLDAPGroup(Request $request)
    {
        $roleId = $request->all()['roleid'];
        $roleName = $request->all()['rolename'];

        LdapHelper::createGroup($roleId, $roleName);
    }

    public function delete(Request $request)
    {
        $roleName = $request->all()['rolename'];
        $roleId = $request->all('roleid');

        $groups = $this->_adldap->search()->groups()->get();

        foreach ($groups as $group) {
            $attrs = $group->getAttributes();

            $groupName = $attrs['cn'][0];

            if ($groupName == $roleName) {
                $group->delete();
                    
                Role::where('role_id', '=', $roleId)->delete();
                    
                return array("success" => true, "message" => "Group has been deleted");
            }
        }

        return array("success" => true, "message" => "");
    }

    private function setPermissionGranted(&$permissions, $permission)
    {
        for ($i = 0; $i < count($permissions); $i++) {
            if ($permissions[$i]["permission_id"] == $permission->permission_id) {
                $permissions[$i]["granted"] = true;
            }
        }
    }

    public function getRolePermissions(Request $request)
    {
        try {
            $roleId = $request->all()['roleid'];

            $role = Role::where('role_id', '=', $roleId)->get()->first();

            $roleName = $role->name;

            $rolePermissions = RolePermission::where('role_id', "=", $roleId)->get();

            $rolePermissionsList = array();

            $rolesDefined = array();

            $data = null;

            $allPermissions = Permission::all()->sortBy('name');

            $outputPermissions = array();

            foreach ($allPermissions as $permission) {
                $outputPermissions[] = array("permission_id" => $permission->permission_id, "name" => $permission->name, "page_id" => $permission->page_id, "granted" => false);
            }

            foreach ($rolePermissions as $rolePermission) {
                $role = $rolePermission->role;
                $roleId = $role->role_id;
                $roleName = $role->name;
                $permission = $rolePermission->permission;

                if (!$data) {
                    $data = array("role_id" => $roleId, "role_name" => $roleName);
                }

                $this->setPermissionGranted($outputPermissions, $permission);
            }

            if (!$data) {
                return array("role_id" => $roleId, "role_name" => $roleName, "permissions" => $outputPermissions);
            }

            $data["permissions"] = $outputPermissions;

            return array("success" => true, "message" => "", "data" => $data);
        } catch (\Exception $ex) {
            return array("success" => false, "message" => "Error retrieving role permissions: " . $ex->getMessage(), "data" => null);
        }
    }


    public function addRolePermissions(Request $request)
    {
        try {
            $allParams = $request->all();

            if (!array_key_exists('roleid', $allParams)) {
                return array("success" => false, "message" => "Missing request parameter 'roleid'");
            }

            $roleId = $allParams['roleid'];

            $permissions = $request->all()['permissions'];

            DB::transaction(function () use ($roleId, $permissions) {
                RolePermission::where('role_id', '=', $roleId)->delete();

                foreach ($permissions as $permissionId) {
                    $rolePermissions = new RolePermission();

                    $rolePermissions->role_id = $roleId;
                    $rolePermissions->permission_id = $permissionId;

                    $rolePermissions->save();
                }
            });
        
            return array("success" => true, "message" => "");
        } catch (\Exception $ex) {
            return array("success" => false, "message" => "Error saving role permissions: " . $ex->getMessage());
        }
    }

    public function addPermission(Request $request)
    {
        try {
            $permissionName = $request->all()['permissionname'];
            $pageId = $request->all()['pageid'];

            $permission = Permission::where('name', '=', $permissionName)->get()->first();

            if ($permission) {
                return array("success" => false, "message" => "Permission with this name already exists");
            }

            $dbPermission = new Permission();

            $dbPermission->name = $permissionName;
            $dbPermission->page_id = $pageId;
            
            $dbPermission->save();
        
            return array("success" => true, "message" => "");
        } catch (\Exception $ex) {
            return array("success" => false, "message" => "Error saving permission: " . $ex->getMessage());
        }
    }
}
