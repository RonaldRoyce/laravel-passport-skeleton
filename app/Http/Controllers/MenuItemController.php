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
            $previousMenuItem = $menuItem->parentMenuItem();
            $menuItemType = $menuItem->menu_item_type;
            $menuItems = $menuItem->submenuItems;
            $menuItemId = $menuItem->menu_item_id;
            $menuTailPath = '<a href="/menus" class="cookie-trail-text">Menus</a> / <a href="/menuitems?menu_id=' . $menu->menu_id . '"  class="cookie-trail-text">' . $menu->name . '</a>' . ' / ' . $menuItem->getNavTrailPath();
        } else {
            $menuTailPath = '<a href="/menus"  class="cookie-trail-text">Menus</a> / ' . $menu->name;
            $menuItemType = "G";
            $menuItemId = 0;
            $menuItem = null;
            $menuItems = MenuItem::where('menu_id', '=', $menuId)->whereNull('menu_item_parent_id')->orderBy('level_order')->get();

            $previousMenuItem = null;
        }

        if ($menuItemType == "G") {
            return view('menu.menuitems.index', array('menu_id' => $menuId, 'menuName' => $menu->name, 'menuItem' => $menuItem, 'menu_item_id' => $menuItemId, 'menus' => $menuItems, 'menuTrailPath' => $menuTailPath, "previousMenuItem" =>$previousMenuItem));
        } else {
            return view('menu.menuitems.edit', array('menu_id' => $menuId, 'menuName' => $menu->name, 'menuItem' => $menuItem, 'menu_item_id' => $menuItemId, 'menus' => $menuItems, 'menuTrailPath' => $menuTailPath, "previousMenuItem" =>$previousMenuItem));
        }
    }
}
