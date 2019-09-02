<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('client_credentials');
    }

    public function createMenu(Request $request)
    {
        $menu = new Menu();

        $menu->name = $request->all()["name"];

        $menu->save();

        $id = $menu->menu_id;

        return array("success" => true, "message" => "", "data" => array("id" =>$id));
    }

    public function saveMenuItem(Request $request)
    {
        $queryString = $request->all();

        $menuItemId = $queryString['menuitemid'];
        $menuItemText = $queryString['menuitemtext'];
        $pageId = $queryString['pageid'];
        $anchorUrl = $queryString['anchorurl'];
        $divAnchorName = $queryString['divanchorname'];
        $imageClass = $queryString['imageclass'];

        $menuItem = MenuItem::where('menu_item_id', '=', $menuItemId)->get()->first();

        $menuItem->menu_item_text = $menuItemText;
        $menuItem->page_id = $pageId;
        $menuItem->anchor_url = $anchorUrl;
        $menuItem->div_anchor_name = $divAnchorName;
        $menuItem->image_class = $imageClass;

        $menuItem->save();

        return array("success" => true, "message" => "", "data" => null);
    }
}
