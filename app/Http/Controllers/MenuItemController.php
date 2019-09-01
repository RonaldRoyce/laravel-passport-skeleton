<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MenuHelper;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $menuItems = MenuItem::where('menu_id', '=', $request->all()['menu_id'])->whereNull('menu_item_parent_id')->orderBy('level_order')->get();

        return view('menu.menuitems.index', array('menus' => $menuItems));
    }
}
