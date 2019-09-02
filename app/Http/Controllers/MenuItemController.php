<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MenuHelper;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $queryString = $request->all();

        $menuId = $queryString['menu_id'];

        $menu = Menu::where('menu_id', '=', $menuId)->get()->first();

        $menuItemId =  (array_key_exists('menu_item_id', $queryString) ? $queryString['menu_item_id'] : '');

        if ($menuItemId != '') {
            $menuItem = MenuItem::where('menu_id', '=', $menuId)->where('menu_item_id', '=', $menuItemId)->get()->first();
            $menuItemType = $menuItem->menu_item_type;
            $menuItems = $menuItem->submenuItems;
            $menuTailPath = '<a href="/menus">Menus</a> / <a href="/menuitem/menu_id=' . $menu->menu_id . '">' . $menu->name . '</a>' . ' / ' . $menuItem->getNavTrailPath();
        } else {
            $menuTailPath = '<a href="/menus">Menus</a> / ' . $menu->name;
            $menuItemType = "G";
            $menuItems = MenuItem::where('menu_id', '=', $menuId)->whereNull('menu_item_parent_id')->get();
        }

        if ($menuItemType == "G") {
            return view('menu.menuitems.index', array('menu_id' => $menuId, 'menuName' => $menu->name, 'menus' => $menuItems, 'menuTrailPath' => $menuTailPath));
        } else {
            return view('menu.menuitems.edit', array('menu_id' => $menuId, 'menuName' => $menu->name, 'menuItem' => $menuItem, 'menus' => $menuItems, 'menuTrailPath' => $menuTailPath));
        }
    }
}
