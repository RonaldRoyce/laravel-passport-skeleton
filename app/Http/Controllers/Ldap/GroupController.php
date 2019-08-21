<?php

namespace App\Http\Controllers\Ldap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;

class GroupController extends Controller
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
/*
	$group = $this->_adldap->make()->group([ 'gidnumber' => 504 ]);

$dn = $group->getDnBuilder();
$dn->addOu('groups');
$dn->addCn("Managers 2");

$group->setDn($dn);

print_r($group);

	$group->save();
*/
	$groups = Adldap::search()->groups()->get();

	$allGroups = array();

	foreach ($groups as $group)
	{
        	$attrs = $group->getAttributes();

		$group = (object)array("id" => $attrs["gidnumber"][0], "name" => $group->getCommonName());

		$allGroups[] = $group;
	}


        return view('ldap.groups.index', ["groups" => $allGroups]);
    }

/*
    public function create(Request $request)
    {
	throw new \Exception("Error");

	return json_encode(array("user_id" => 1));
    }
*/
    public function add(Request $request)
    {
	$response = array("success" => true, "message" => "");

	echo json_encode($response);

	exit(1);
    }
}

