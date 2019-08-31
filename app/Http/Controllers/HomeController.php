<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Helpers\ConfigHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == null) {
            return redirect('/notauthorized');
        }

        if ($user->must_change_password) {
            //create a new token to be sent to the user.

            $user->token = str_random(60);

            DB::table('password_resets')->insert([
                    'username' => $user->username,
                    'token' => $user->token, //change 60 to any length you want
                    'created_at' => Carbon::now()
               ]);
                         
            $user->save();
               
            return redirect('/resetpassword');
        }

        $group = $user->group;

        return view('dashboard', ["group" => $group]);
    }
}
