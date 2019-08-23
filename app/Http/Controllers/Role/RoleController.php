<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;
use App\Http\Controllers\Controller;

use Adldap\AdldapInterface;

class RoleController extends Controller
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

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	$groups = Adldap::search()->groups()->get();

	$allGroups = array();

	foreach ($groups as $group)
	{
        	$attrs = $group->getAttributes();

		$group = (object)array("id" => $attrs["gidnumber"][0], "name" => $group->getCommonName());

		$allGroups[] = $group;
	}

	$names = array_column($allGroups, "name");
	$ids = array_column($allGroups, "id");

	array_multisort($names, SORT_ASC, $ids, SORT_ASC, $allGroups);

        return view('role.index', ["roles" => $allGroups]);
    }
}

