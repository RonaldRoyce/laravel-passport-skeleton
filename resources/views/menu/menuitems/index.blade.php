@extends('layouts.app', ['pageSlug' => 'menu-index', 'titlePage' => __('Menus'), 'sidebarType' => 'admin'])

@section('content')

<div class="container">
     <input type="hidden" id="menu_id" value="{{$menu_id}}" />
     <input type="hidden" id="menu_item_id" value="{{$menu_item_id}}" />
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-slim">
                    <div>
                         <div style="float: left;"><h3 class="slim">Menu Items</h3></div>
                         <div class="clear: both;"></div>
                    </div>
               </div>
                <div class="card-body card-body-full">
                    <div><h3  class="cookie-trail-text"><?php echo '<div style="float: left;">' . $menuTrailPath . '</div><div style="clear: both;"></div>'; ?></h3></div> 
                    <table style="width: 100%;height: calc(100% - 50px);" border="1">
                         <thead>
                              <tr>
                                   <th style="background-color: #d6c757;text-align: center;vertical-align: middle;margin: auto;padding: 10px;"><h4 style="padding: 0px;margin: 0px;">Menu Details</h4></th>
                                   <th style="background-color: #d6c757;text-align: center;vertical-align: middle;margin: auto;padding: 10px;"><h4 style="padding: 0px;margin: 0px;">Menu Items</h4></th>
                              </tr>
                         </thead>
                         <tbody>
                              <tr>
                                   <td style="vertical-align: top;padding: 5px;">
                                        <div>
                                                  <label for="menu-name" class="col-form-label">Text</label>
                                                  <input type="text" class="form-control-black" id="menu-item-text" name="menu-item-text" value="{{$menuItem->menu_item_text}}" required>
                                             </div>
                                             <div>
                                                  <label for="menu-name" class="col-form-label">Page Id</label>
                                                  <input type="text" class="form-control-black" id="page-id" name="page-id" value="{{$menuItem->page_id}}" required>
                                             </div>
                                             <div @if ($menuItem->menu_item_type == "M")style="display: block" @else style="display: none;" @endif >
                                                  <label for="menu-name" class="col-form-label">Anchor Url&nbsp;{{$menuItem->menu_item_type}}</label>
                                                  <input type="text" class="form-control-black" id="anchor-url" name="anchor-url" value="{{$menuItem->anchor_url}}" required>
                                             </div>
                                             
                                                  <div @if ($menuItem->menu_item_type == "G")style="display: block" @else style="display: none;" @endif >
                                                       <label for="div-anchor-name" class="col-form-label">Anchor Div Name</label>
                                                       <input type="text" class="form-control-black" id="div-anchor-name" name="div-anchor-name" value="{{$menuItem->div_anchor_name}}" required>
                                                  </div>
                                        
                                             <div style="margin-bottom: 10px;">
                                                  <label for="menu-name" class="col-form-label">Image Class</label>
                                                  <input type="text" class="form-control-black" id="image-class" name="image-class" value="{{$menuItem->image_class}}" required>
                                             </div>
                                        
                                        
                                             <div class="slim" style="width: 45px;margin:auto;margin-bottom: 10px;"><button id="save-menu-item-details-btn" class="btn btn-danger">Save</button></div>
                                             <div id="save-message-div" class="alert" role="alert" style="display: none;"></div>
                                        </div>
                                   </td>
                                   <td style="width: 820px;vertical-align: top;padding: 5px;height: 100%;">
                                        <table id="menu-items-table" class="table table-striped table-bordered">
                                             <thead class="thead-light ldap-groups">
                                                  <tr>
                                                       <th scope="col" class="menu-text">Text</th>
                                                       <th scope="col" class="menu-type">Type</th>
                                                       <th scope="col" class="menu-move">&nbsp;</th>
                                                       <th scope="col" class="menu-action">Action</th>
                                                  </tr>
                                             </thead>
                                             <tbody class="ldap-groups" style="max-height: calc(100vh - 258px);overflow-y: auto;">
                                                  <?php
                                             $itemNum = 0;
                                             ?>
                                                  @foreach ($menus as $menu)
                                                  <tr data-level-order="{{$menu->level_order}}">
                                        
                                                       <td class="menu-text"><a href="/menuitems?menu_id={{$menu_id}}&menu_item_id={{$menu->menu_item_id}}">{{$menu->menu_item_text}}</a></td>
                                                       <td class="menu-type">{{ $menu->menu_item_type == "G" ? "Menu" : "Menu Item" }}</td>
                                                       <td class="menu-move" style="text-align: center;">
                                                            @if($itemNum < count($menus) - 1) <a href="#" class="move-down-btn" data-level-order="{{$menu->level_order}}" data-name="{{$menu->menu_item_text}}" data-id="{{$menu->menu_item_id}}"><i class="fa fa-arrow-alt-circle-down down-arrow"></i></a>
                                                                 @endif
                                                                 @if($itemNum > 0)
                                                                 <a href="#" class="move-up-btn" data-level-order="{{$menu->level_order}}" data-name="{{$menu->menu_item_text}}" data-id="{{$menu->menu_item_id}}"><i class="fa fa-arrow-alt-circle-up up-arrow"></i></a>
                                                                 @endif
                                                       </td>
                                                       <td class="menu-action"><button type="button" class="btn btn-primary edit-menu-item-btn" data-name="{{$menu->menu_item_text}}" data-id="{{$menu->menu_item_id}}">Edit</button>&nbsp;<button type="button" class="btn btn-danger delete-menu-item-btn" data-name="{{$menu->menu_item_text}}" data-id="{{$menu->menu_item_id}}">Delete</button></td>
                                                  </tr>
                                                  <?php
                                             $itemNum++;
                                             ?>
                                                  @endforeach
                                             </tbody>
                                        </table>
                                   </td>
                              </tr>
                         </tbody>
                    </table>
                </div>
                <div class="card-footer">
                     <div class="menu-items-footer-div">
                         <div class="slim" style="text-align: center;margin: auto;float: left;"><button id="add-menu-btn" class="btn btn-primary" data-toggle="modal" data-target="#addMenuModal">Add Menu</button></div>
                         <div class="slim" style="text-align: center;margin: auto;float: left;"><button id-="add-menu-item-btn" class="btn btn-info" data-toggle="modal" data-target="#addMenuItemModal">Add Menu Item</button></div>
                         <div style="clear: both;"></div>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>

@include('partials.menu.menu')
@include('partials.menu.menuitem')



@endsection

@push('view-scripts')
<script style="text/javascript">
	var tokenUrl = "<?php echo env('APP_URL', 'http://localhost') . '/token' ?>";
	var menuAddUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/menu/add' ?>";
     var menuItemSaveDetailsUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/save' ?>";
     var menuItemAddUrl = "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/add' ?>";
	var menuEditUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/menuitems' ?>";
     var menuItemDeleteUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/delete' ?>";
     var menuItemMoveDownUrl = "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/movedown' ?>";
     var menuItemMoveUpUrl = "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/moveup' ?>";
     var globalMenuItemText = "";
     var globalMenuItemId = "";
</script>

<script style="text/javascript" src="/js/menuitem.js"></script>

@endpush

