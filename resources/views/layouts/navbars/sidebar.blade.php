<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal" style="text-align: center;">{{ _('Dashboard') }}</a>
        </div>
        <ul class="nav">
             <?php
             echo \App\Helpers\MenuHelper::render($pageSlug);
             ?>
             <?php
             if (env('APP_DEBUG')) {
                 $menuItem = new \App\Models\MenuItem();
                  
                 $menuItem->menu_item_parent_id = null;
                 $menuItem->menu_item_type = "M";
                 $menuItem->page_id = 'maintain-menus-index';
                 $menuItem->anchor_url = "/menus";
                 $menuItem->image_class = "fa fa-list-alt";
                 $menuItem->menu_item_text = "Menus";
                 $menuItem->div_anchor_name = "maintain-menus-index-div";

                 echo \App\Helpers\MenuHelper::singleMenuItemTemplate($menuItem, $pageSlug);
             }
             ?>
        </ul>
    </div>
</div>
