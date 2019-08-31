<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
         reset as protected traitreset;
         rules as protected traitrules;
         credentials as protected traitcredentials;
         resetPassword as protected traitresetpassword;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.passwords.reset', array('token' => Auth::user()->token));
    }

    public function reset(Request $request)
    {
        $rc = $this->traitreset($request);
        
        $user = Auth::user();

        $user->must_change_password = 0;

        $user->save();

        return $rc;
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'username',
            'password',
            'password_confirmation',
            'token'
        );
    }
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $token = Str::random(60);

        $user->setRememberToken($token);
        $user->token = $token;

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
}
