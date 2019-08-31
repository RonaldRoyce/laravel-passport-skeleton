<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Adldap\Adldap;
use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Adldap\Laravel\Facades\Adldap;
use App\Models\Role\Role;
use App\Helpers\ConfigHelper;

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

    protected function saveRoles()
    {
        $groups = $this->adldap->search()->groups()->get();

        foreach ($groups as $group) {
            $id = $group->getAttributes()['gidnumber'][0];
            $name = $group->getAttributes()['cn'][0];

            $g = Role::find($id);

            if (!$g) {
                Role::create(array('role_id' => $id, 'name' => $name));
            }
        }
    }

    protected function attemptLogin(Request $request)
    {
        $providerConfig = ConfigHelper::getAuthDriverProviderConfig();
 
        if ($providerConfig->driver == "ldap") {
            return $this->attemptLoginLDAP($request);
        }

        return $this->attemptLoginDatabase($request);
    }


    protected function attemptLoginLDAP(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];
        $password = $credentials['password'];

        if (Adldap::auth()->attempt($username, $password, $bindAsUser = true)) {
            $this->saveRoles();

            $user = \App\User::where($this->username(), $username) -> first();
            if (!$user) {
                // the user doesn't exist in the local database, so we have to create one

                $user = new \App\User();
                $user->username = $username;
                $user->password = Hash::make($password);
                $user->created_at = $user->updated_at = date("Y-m-d H:i:s");
                $user->token = str_random(60);
                $user->remember_token = str_random(60);

                // you can skip this if there are no extra attributes to read from the LDAP server
                // or you can move it below this if(!$user) block if you want to keep the user always
                // in sync with the LDAP server
                $sync_attrs = $this->retrieveSyncAttributes($username);
                foreach ($sync_attrs as $field => $value) {
                    $value = ($value !== null ? $value : '');
                    $user->$field = $value;
                }
            } else {
                //  Update

                $sync_attrs = $this->retrieveSyncAttributes($username);

                foreach ($sync_attrs as $field => $value) {
                    $value = ($value !== null ? $value : '');
                    $user->$field = $value;
                }

                $user->updated_at = $user->last_login_at = date("Y-m-d H:i:s");
                $user->token = str_random(60);

                $user->save();
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

    protected function attemptLoginDatabase(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];
        $password = $credentials['password'];
        
        if (\Auth::attempt([
               'username' => $username,
               'password' => $password])
           ) {
            return true;
        }
        return false;
    }

    protected function retrieveSyncAttributes($username)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();
        if (!$ldapuser) {
            return false;
        }

        $ldapAttributes = $ldapuser->getAttributes();

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ($local_attr == 'username') {
                continue;
            }

            $attrValue = "";

            foreach ($ldap_attr as $ldap_attribute) {
                if (array_key_exists($ldap_attribute, $ldapAttributes)) {
                    if ($attrValue != "") {
                        $attrValue .= " ";
                    }

                    $attrValue .= $ldapAttributes[$ldap_attribute][0];
                }
            }

            $attrs[$local_attr] = $attrValue;
        }

        return $attrs;
    }

    protected static function accessProtected($obj, $prop)
    {
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
