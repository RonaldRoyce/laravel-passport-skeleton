<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Log;

class GroupApiController extends Controller
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
	// $group = $this->_adldap->make()->group();

	$d = date("Y-m-d H:i:s");

	$roleId = $request->all()['roleid'];
	$roleName = $request->all()['rolename'];

	$group = $this->_adldap->make()->group(['gidnumber' => $roleId, 'objectclass' => ['top', 'posixGroup']]);

	$dn = $group->getDnBuilder();

	$dn->addOu('groups');

	$dn->addCn($roleName);

	$group->setDn($dn);

	Log::debug("$d - GroupApiController going to create");

	try
	{
		$group->create();

		$group = null;

                Log::debug("$d - GroupApiController created successfully");

	        return array("success" => true, "message" => "");
	}
	catch(\Exception $ex)
	{
		Log::debug("$d - GroupApiController error creating: " . $ex->getMessage());
		return array("succces" => false, "message" => "Error saving: " . $ex->getMessage());
	}
    }
    public function delete(Request $request)
    {
	$roleName =  $request->all()['rolename'];

        $groups = $this->_adldap->search()->groups()->get();

        foreach ($groups as $group)
        {
		$attrs = $group->getAttributes();

		$groupName = $attrs['cn'][0];

		if ($groupName == $roleName)
		{
			$group->delete();
			return array("success" => true, "message" => "Group has been deleted");
		}
        }

	return array("success" => true, "message" => "");
    }
}

