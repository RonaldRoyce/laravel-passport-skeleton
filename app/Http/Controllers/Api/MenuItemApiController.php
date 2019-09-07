<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use \Exception;
use Illuminate\Support\Facades\DB;

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

    public function createMenuItem(Request $request)
    {
        $queryString = $request->all();

        $menuId = $queryString['menuid'];
        $menuItemParentId = $queryString['menuitemparentid'];

        if ($menuItemParentId == 0) {
            $menuItemParentId = null;
        }


        $menuItemText = $queryString['menuitemtext'];
        $menuItemType = $queryString['menuitemtype'];
        $pageId = $queryString['pageid'];
        $anchorUrl = $queryString['anchorurl'];
        $divAnchorName = $queryString['divanchorname'];
        $imageClass = $queryString['imageclass'];

        if ($menuItemParentId) {
            $menuItem = MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('menu_item_text', '=', $menuItemText)->get()->first();
          
            if ($menuItem) {
                return array("success" => false, "message" => "Menu already contains an item with text '" . $menuItemText . "'");
            }

            if ($menuItemType == "G") {
                $menuItem = MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('div_anchor_name', '=', $divAnchorName)->get()->first();
                if ($menuItem) {
                    return array("success" => false, "message" => "Menu already contains an item with div anchor name '" . $divAnchorName . "'");
                }
            } else {
                $menuItem = MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('anchor_url', '=', $anchorUrl)->get()->first();
                if ($menuItem) {
                    return array("success" => false, "message" => "Menu already contains an item with anchor url '" . $anchorUrl . "'");
                }
            }

            $menuItem = MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('page_id', '=', $pageId)->get()->first();
            if ($menuItem) {
                return array("success" => false, "message" => "Menu already contains an item with page id '" . $pageId . "'");
            }
        } else {
            $menuItem = MenuItem::whereNULL('menu_item_parent_id')->where('menu_item_text', '=', $menuItemText)->get()->first();
          
            if ($menuItem) {
                return array("success" => false, "message" => "Menu already contains an item with text '" . $menuItemText . "'");
            }

            if ($menuItemType == "G") {
                $menuItem = MenuItem::whereNULL('menu_item_parent_id')->where('div_anchor_name', '=', $divAnchorName)->get()->first();
                if ($menuItem) {
                    return array("success" => false, "message" => "Menu already contains an item with div anchor name '" . $divAnchorName . "'");
                }
            } else {
                $menuItem = MenuItem::whereNULL('menu_item_parent_id')->where('anchor_url', '=', $anchorUrl)->get()->first();
                if ($menuItem) {
                    return array("success" => false, "message" => "Menu already contains an item with anchor url '" . $anchorUrl . "'");
                }
            }

            $menuItem = MenuItem::whereNULL('menu_item_parent_id')->where('page_id', '=', $pageId)->get()->first();
            if ($menuItem) {
                return array("success" => false, "message" => "Menu already contains an item with page id '" . $pageId . "'");
            }
        }

        $menuItem = new MenuItem();

        $menuItem->menu_id = $menuId;
        $menuItem->menu_item_text = $menuItemText;
        $menuItem->menu_item_type = $menuItemType;
        $menuItem->menu_item_parent_id = $menuItemParentId;

        $menuItem->page_id = $pageId;
        if ($menuItem->menu_item_type == "G") {
            $menuItem->anchor_url = '';
            $menuItem->div_anchor_name = $divAnchorName;
        } else {
            $menuItem->anchor_url = $anchorUrl;
            $menuItem->div_anchor_name = '';
        }

        if (!$menuItemParentId) {
            //  Get next level order for top level menu

            $lastMenuItem = MenuItem::whereNULL('menu_item_parent_id')->orderBy('level_order', 'desc')->get()->first();
            if (!$lastMenuItem) {
                $menuLevelOrder = 1;
            } else {
                $menuLevelOrder = $lastMenuItem->level_order + 1;
            }
        } else {
            //  Get next level order for non top level menu

            // echo "Getting level order for menu parent id $menuItemParentId<br>";
          

            // Your Eloquent query
            
            $lastMenuItem = MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->orderBy('level_order', 'desc')->get()->first();

            if (!$lastMenuItem) {
                $menuLevelOrder = 1;
            } else {
                $menuLevelOrder = $lastMenuItem->level_order + 1;
            }
        }
        $menuItem->level_order = $menuLevelOrder;
        $menuItem->image_class = $imageClass;

        try {
            $menuItem->save();
        } catch (Exception $ex) {
            return array("success" => true, "message" => $ex->getMessage(), "data" => null);
        }

        return array("success" => true, "message" => "", "data" => array("menu_item_id" => $menuItem->menu_item_id));
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
        if ($menuItem->menu_item_type == "G") {
            $menuItem->anchor_url = '';
            $menuItem->div_anchor_name = $divAnchorName;
        } else {
            $menuItem->anchor_url = $anchorUrl;
            $menuItem->div_anchor_name = '';
        }

        $menuItem->image_class = $imageClass;

        $menuItem->save();

        return array("success" => true, "message" => "", "data" => null);
    }

    public function deleteMenuItem(Request $request)
    {
        $queryString = $request->all();
        

        $menuItemId = $queryString['menuitemid'];

        $menuItem = MenuItem::where('menu_item_id', '=', $menuItemId)->get()->first();

        if (!$menuItem) {
            return array("success" => true, "message" => "");
        }

 
        $menuItemLevelOrder = $menuItem->level_order;
        $menuItemParentId = $menuItem->menu_item_parent_id;
   
        try {
            DB::beginTransaction();
            MenuItem::where('menu_item_id', '=', $menuItemId)->delete();

            if ($menuItemParentId) {
                MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('level_order' > $menuItemLevelOrder)
               ->update(['level_order' => DB::raw("level_order - 1")]);
            } else {
                MenuItem::whereNULL('menu_item_parent_id')->where('level_order', '>', $menuItemLevelOrder)
               ->update(['level_order' => DB::raw("level_order - 1")]);
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            return array("success" => false, "message" => $ex->getMessage());
        }

        return array("success" => true, "message" => "");
    }


    public function moveDown(Request $request)
    {
        $queryString = $request->all();

        $menuItemId = $queryString['menuitemid'];

        $menuItem = MenuItem::where('menu_item_id', '=', $menuItemId)->get()->first();

        if (!$menuItem) {
            return array("success" => true, "message" => "");
        }
 
        $menuItemLevelOrder = $menuItem->level_order;
        $menuItemParentId = $menuItem->menu_item_parent_id;

        //  If this item is the last one, do nothing

        if ($menuItemParentId) {
            $lastLevelOrder =  MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->orderBy('level_order', 'desc')->get()->first()->level_order;
            if ($lastLevelOrder == $menuItemLevelOrder) {
                return array("success" => true, "message" => "");
            }
        } else {
            $lastLevelOrder =  MenuItem::whereNULL('menu_item_parent_id')->orderBy('level_order', 'desc')->get()->first();
            if ($lastLevelOrder == $menuItemLevelOrder) {
                return array("success" => true, "message" => "");
            }
        }

        //  This is not the last item

        try {
            DB::beginTransaction();

            //  Set the item to move's level order to zero to make room for updating other items in menu

            MenuItem::where('menu_item_id', '=', $menuItemId)
            ->update(['level_order' => 0]);

            //  Set the item who will now be the level order of the item

            if ($menuItemParentId) {
                MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('level_order', '=', $menuItemLevelOrder + 1)
               ->update(['level_order' => $menuItemLevelOrder]);
            } else {
                MenuItem::whereNULL('menu_item_parent_id')->where('level_order', '=', $menuItemLevelOrder + 1)
               ->update(['level_order' => $menuItemLevelOrder]);
            }
            MenuItem::where('menu_item_id', '=', $menuItemId)
            ->update(['level_order' => $menuItemLevelOrder + 1]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            return array("success" => false, "message" => $ex->getMessage());
        }

        return array("success" => true, "message" => "");
    }
    
    public function moveUp(Request $request)
    {
        $queryString = $request->all();

        $menuItemId = $queryString['menuitemid'];

        $menuItem = MenuItem::where('menu_item_id', '=', $menuItemId)->get()->first();

        if (!$menuItem) {
            return array("success" => true, "message" => "");
        }

        $menuItemLevelOrder = $menuItem->level_order;
        $menuItemParentId = $menuItem->menu_item_parent_id;

        //  If this item is the last one, do nothing

        if ($menuItemParentId) {
            $lastLevelOrder =  MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->orderBy('level_order', 'desc')->get()->first();
            if ($lastLevelOrder == $menuItemLevelOrder) {
                return array("success" => true, "message" => "");
            }
        } else {
            $lastLevelOrder =  MenuItem::whereNULL('menu_item_parent_id')->orderBy('level_order', 'desc')->get()->first();
            if ($lastLevelOrder == $menuItemLevelOrder) {
                return array("success" => true, "message" => "");
            }
        }

        //  This is not the last item

        try {
            DB::beginTransaction();

            //  Set the item to move's level order to zero to make room for updating other items in menu

            MenuItem::where('menu_item_id', '=', $menuItemId)
         ->update(['level_order' => 0]);

            //  Set the item who will now be the level order of the item

            if ($menuItemParentId) {
                MenuItem::where('menu_item_parent_id', '=', $menuItemParentId)->where('level_order', '=', $menuItemLevelOrder + 1)
            ->update(['level_order' => $menuItemLevelOrder]);
            } else {
                MenuItem::whereNULL('menu_item_parent_id')->where('level_order', '=', $menuItemLevelOrder + 1)
            ->update(['level_order' => $menuItemLevelOrder]);
            }
            MenuItem::where('menu_item_id', '=', $menuItemId)
         ->update(['level_order' => menuItemLevelOrder + 1]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            return array("success" => false, "message" => $ex->getMessage());
        }

        return array("success" => true, "message" => "");
    }
}