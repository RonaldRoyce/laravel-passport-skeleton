<?php
namespace App\Helpers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function singleMenuItemTemplate($menuItem, $currentPageId)
    {
        if (!Auth::user()->hasPermission($menuItem->page_id)) {
            return '';
        }
   
        $menuItemAnchorUrl = $menuItem->anchor_url;
        $menuItemImageClass = $menuItem->image_class;
        $menuItemText = $menuItem->menu_item_text;

        return sprintf(
            '
                                   <li %s>
                                        <a href="%s">
                                             <i class="%s" style="width: 30px; height: 30px;border-radius: 2.2857rem;"></i>
                                             <span class="nav-link-text" >%s</span>
                                        </a>
                                   </li>
                              ',
            (
                $menuItem->page_id == $currentPageId ? 'class="active"' : ''
            ),
            $menuItemAnchorUrl,
            $menuItemImageClass,
            $menuItemText
        );
    }

    public static function multipleMenuItemTemplate($menuItem, $currentPageId)
    {
        $divAnchorName = $menuItem->div_anchor_name;
        $topLevelImageClass = $menuItem->image_class;
        $topLevelMenuItemText = $menuItem->menu_item_text;

        if (!Auth::user()->hasPermission($menuItem->page_id)) {
            return '';
        }

        $html = sprintf(
            '
                         <li>
                              <a data-toggle="collapse" href="#%s">
                              <i class="%s"></i>
                              <span class="nav-link-text" >%s</span>
                              </a>
                              <div class="collapse" id="%s">
                                   <ul class="nav pl-4">
                         ',
            $divAnchorName,
            $topLevelImageClass,
            $topLevelMenuItemText,
            $divAnchorName
        );

        if ($menuItem->menu_item_type == "G") {
            $menuItem->load(['submenuItems' => function ($query) {
                $query->orderBy('level_order', 'asc');
            }]);

            foreach ($menuItem->submenuItems as $submenuItem) {
                if (!Auth::user()->hasPermission($submenuItem->page_id)) {
                    continue;
                }
           
                if ($submenuItem->menu_item_type == "G") {
                    $html .= self::multipleMenuItemTemplate($submenuItem, $currentPageId);
                } else {
                    $html .= self::singleMenuItemTemplate($submenuItem, $currentPageId);
                }
            }
        } else {
            $html .= self::singleMenuItemTemplate($menuItem, $currentPageId);
        }
        
        $html .= '
                              </ul>
                         </div>
                    </li>
        ';

        return $html;
    }


    public static function render($currentPageId, $menuItems = null)
    {
        if (!$menuItems) {
            $menuItems = MenuItem::whereNull('menu_item_parent_id')->orderBy('level_order')->get();
        }
         
        $html = '';

        foreach ($menuItems as $menuItem) {
            if ($menuItem->menu_item_type == "G") {
                $html .= self::multipleMenuItemTemplate($menuItem, $currentPageId);
            } else {
                $html .= self::singleMenuItemTemplate($menuItem, $currentPageId);
            }
        }

        return $html;
    }
}
    /*
    This class helps build men
    <li>
    <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
        <i class="fa fa-users"></i>
        <span class="nav-link-text" >{{ ('User Administration') }}</span>
    </a>

    <div class="collapse show" id="laravel-examples">
        <ul class="nav pl-4">
        @if ( Auth::user()->hasPermission('permissionmanagement-index'))
            <li @if ($pageSlug == 'permissions-index') class="active " @endif>
                <a href="{{ route('permissions')  }}">
                    <i class="fa fa-object-group"></i>
                    <span class="nav-link-text">{{ _('Permissions') }}</span>
                </a>
            </li>
        @endif
    @if ( Auth::user()->hasPermission('rolemanagement-index'))
            <li @if ($pageSlug == 'roles') class="active " @endif>
                <a href="{{ route('roles')  }}">
                    <i class="fa fa-object-group"></i>
                    <span class="nav-link-text">{{ _('Roles') }}</span>
                </a>
            </li>
    @endif
    @if ( Auth::user()->hasPermission('rolepermissionmanagement-index'))
            <li @if ($pageSlug == 'rolepermissions') class="active " @endif>
                <a href="{{ route('rolepermissions')  }}">
                    <i class="fa fa-object-group"></i>
                    <span class="nav-link-text">{{ _('Role Permissions') }}</span>
                </a>
            </li>
    @endif

    @if (Auth::user()->hasPermission('usermanagement-index'))
            <li @if ($pageSlug == 'users') class="active " @endif>
                <a href="{{ route('user.index')  }}">
                    <i class="tim-icons icon-bullet-list-67"></i>
                    <span class="nav-link-text">{{ _('User Management') }}</span>
                </a>
            </li>
    @endif
        </ul>
    </div>
 </li>
@endif
<li>
<a>
        <img src="/black/img/anime3.png" alt="Profile Photo" style="width: 30px; height: 30px;border-radius: 2.2857rem;">
        <span class="nav-link-text" >Single Item</span>
</a>
</li>
*/
