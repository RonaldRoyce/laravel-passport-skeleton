<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolePermissionsController extends Controller
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

        $group = $user->group;

        return view('dashboard', ["group" => $group]);
    }
}
