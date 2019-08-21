<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;

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
	return json_encode(array("user_id" => 35));
    }
}

