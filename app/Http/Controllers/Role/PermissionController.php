<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ConfigHelper;
use App\Helpers\LdapHelper;
use App\Models\Role\Permission;

class PermissionController extends Controller
{
    protected $_adldap;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == null) {
            return redirect('/notauthorized');
        }

        $dbPermissions = Permission::all()->sortBy('name');
        $allPermissions = array();

        foreach ($dbPermissions as $permission) {
            $dbPermission = (object)array("id" => $permission->permission_id, "name" => $permission->name);

            $allPermissions[] = $dbPermission;
        }

        return view('permission.index', ["permissions" => $allPermissions]);
    }
}
