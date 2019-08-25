<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Helpers\ConfigHelper;

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

     if ($user->role == null)
     {
          return redirect('/notauthorized');
     }         

     $group = $user->group;

        return view('dashboard', ["group" => $group]);
    }
}
