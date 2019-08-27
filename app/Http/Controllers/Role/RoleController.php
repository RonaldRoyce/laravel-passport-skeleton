<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ConfigHelper;
use App\Helpers\LdapHelper;
use App\Models\Role\Role;

class RoleController extends Controller
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

        if ($user->role == null)
        {
               return redirect('/notauthorized');
        }         

        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();

        if ($providerConfig->driver == "ldap")
        {
                LdapHelper::syncLDAPGroups();
        }

	$dbRoles = Role::all()->sortBy('name');

	foreach ($dbRoles as $role)
	{
		$group = (object)array("id" => $role->role_id, "name" => $role->name);

		$allGroups[] = $group;
	}

        return view('role.index', ["roles" => $allGroups]);
    }
}

