<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Adldap\Adldap;
use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;
use App\Models\Ldap\Group;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $adldap = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $adldap)
    {
        $this->middleware('guest')->except('logout');

	$this->adldap = $adldap;
    }
/*
    public function login(Request $request)
    {
        if ($this->adldap->auth()->attempt($request->username, $request->password)) 
	{
       		$user = Auth::user();

		print_r($user);

		exit(1);
       
        	return redirect()->to('/')
            		->withMessage('Logged in!');
    	}

        return redirect()->to('login')
	        ->with('error', 'Invalid email/password entered');
   }
*/

 protected function saveGroups()
 {
	$groups = $this->adldap->search()->groups()->get();

	foreach ($groups as $group) 
	{
		$id = $group->getAttributes()['gidnumber'][0];
		$name = $group->getAttributes()['cn'][0];

		$g = Group::find($id);

		if (!$g)
		{
			Group::create(array('id' => $id, 'name' => $name));
		}
	}
 }

 protected function attemptLogin(Request $request) {
        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];
        $password = $credentials['password'];

        if(Adldap::auth()->attempt($username, $password, $bindAsUser = true)) {

	    $this->saveGroups();

            $user = \App\User::where($this->username(), $username) -> first();
            if (!$user) {
                // the user doesn't exist in the local database, so we have to create one

                $user = new \App\User();
                $user->username = $username;
                $user->password = '';

                // you can skip this if there are no extra attributes to read from the LDAP server
                // or you can move it below this if(!$user) block if you want to keep the user always
                // in sync with the LDAP server 
                $sync_attrs = $this->retrieveSyncAttributes($username);
                foreach ($sync_attrs as $field => $value) {
                    $user->$field = $value !== null ? $value : '';
                }
            }

            // by logging the user we create the session, so there is no need to login again (in the configured time).
            // pass false as second parameter if you want to force the session to expire when the user closes the browser.
            // have a look at the section 'session lifetime' in `config/session.php` for more options.
            $this->guard()->login($user, true);
            return true;
        }

        // the user doesn't exist in the LDAP server or the password is wrong
        // log error
        return false;
    }

    protected function retrieveSyncAttributes($username)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();
        if ( !$ldapuser ) {
            return false;
        }

        // if you want to see the list of available attributes in your specific LDAP server:
        // var_dump($ldapuser->attributes); exit;

        // needed if any attribute is not directly accessible via a method call.
        // attributes in \Adldap\Models\User are protected, so we will need
        // to retrieve them using reflection.
        $ldapuser_attrs = null;

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ( $local_attr == 'username' ) {
                continue;
            }

            $method = 'get' . $ldap_attr;
            if (method_exists($ldapuser, $method)) {
                $attrs[$local_attr] = $ldapuser->$method();
                continue;
            }

            if ($ldapuser_attrs === null) {
                $ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
            }

            if (!isset($ldapuser_attrs[$ldap_attr])) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            if (!is_array($ldapuser_attrs[$ldap_attr])) {
                $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
            }

            if (count($ldapuser_attrs[$ldap_attr]) == 0) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            // now it returns the first item, but it could return
            // a comma-separated string or any other thing that suits you better


            $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
            //$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);
        }

        return $attrs;
    }

    protected static function accessProtected ($obj, $prop) {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
   public function username()
   {
	return "username";
   }
}
