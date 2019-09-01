<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MenuHelper;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::whereNull('menu_item_parent_id')->orderBy('level_order')->get();

        return view('menu.index', array('menus' => $menuItems));
    }
}
