@extends('layouts.app', ['pageSlug' => 'menu-index', 'titlePage' => __('Menus'), 'sidebarType' => 'admin'])

@section('content')

<div class="container">
     <input type="hidden" id="menu_id" value="{{$menu_id}}" />
     <input type="hidden" id="menu_item_id" value="{{$menuItem->menu_item_id}}" />
     <input type="hidden" id="menu_item_parent_id" value="{{$menuItem->menu_item_parent_id}}" />
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card main-card">
                <div class="card-header card-header-slim">
                    <div>
                         <div style="float: left;"><h3 class="slim">Edit Menu Item</h3></div>
                         
                         <div class="clear: both;"></div>
                    </div>
                </div>
               <div class="card-body view-table">
                    <div><h3 class="cookie-trail-text"><?php echo $menuTrailPath; ?></h3></div>                     
                    <div>
                           <label for="menu-id" class="col-form-label">Type</label>
                           <select class="form-control-black" id="menu-item-type" disabled>
                                <option value="G" <?php if ($menuItem->menu_item_type == "G") {
    echo "selected";
} ?> >Menu</option>
                                <option value="M" <?php if ($menuItem->menu_item_type == "M") {
    echo "selected";
} ?> >Menu Item</option>
                         </select>
                    </div>
                    <div>
                       <label for="menu-name" class="col-form-label">Text</label>
                         <input type="text" class="form-control-black" id="menu-item-text" name="menu-item-text" value="{{$menuItem->menu_item_text}}" required>
                    </div>
                    <div>
                       <label for="menu-name" class="col-form-label">Page Id</label>
                         <input type="text" class="form-control-black" id="page-id" name="page-id" value="{{$menuItem->page_id}}" required>
                    </div>
                    <div @if ($menuItem->menu_item_type == "M")style="display: block" @else style="display: none;" @endif >
                       <label for="menu-name" class="col-form-label">Anchor Url</label>
                         <input type="text" class="form-control-black" id="anchor-url" name="anchor-url" value="{{$menuItem->anchor_url}}" required>
                    </div>
                    
                         <div @if ($menuItem->menu_item_type == "G")style="display: block" @else style="display: none;" @endif >
                              <label for="div-anchor-name" class="col-form-label">Anchor Div Name</label>
                              <input type="text" class="form-control-black" id="div-anchor-name" name="div-anchor-name" value="{{$menuItem->div_anchor_name}}" required>
                         </div>

                    <div>
                       <label for="menu-name" class="col-form-label">Image Class</label>
                         <input type="text" class="form-control-black" id="image-class" name="image-class" value="{{$menuItem->image_class}}" required>
                    </div>
               </div>
            <div class="card-footer">
               <div class="slim" style="float: left;"><button id="cancel-save-btn" class="btn btn-danger">Cancel</button></div>
               <div class="slim" style="float: right;"><button id="save-menu-item-btn" class="btn btn-info">Save</button></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMenuItemModal" tabindex="-1" menu="dialog" aria-labelledby="addMenuItemLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuItemLabel">Add Menu Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          		<div>
                           <label for="menu-id" class="col-form-label">Menu Item Type</label>
                           <select class="form-control-black" id="menu-item-type">
                                <option value="G">Menu</option>
                                <option value="M">Menu Item</option>
                         </select>
          		</div>
          		<div>
            			<label for="menu-name" class="col-form-label">Menu Item Text</label>
				<input type="text" class="form-control-black" id="menu-item-text" name="menu-item-text" required>
          		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addmenu-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteRoleModal" tabindex="-1" menu="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content">
      <input type="hidden" id="delete-menuid" name="delete-menuid" />
      <div class="modal-header">
        <h2 class="modal-title" id="addRoleModalLabel">Delete User Role</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="menu-id" />
      <div class="modal-body">
                        <div>Are you sure you wish to delete the menu: <span id="menu-name" style="font-weight: bold;"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" id="execute-delete-menu-btn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('view-scripts')
<script style="text/javascript">
	var tokenUrl = "<?php echo env('APP_URL', 'http://localhost') . '/token' ?>";
	var menuAddUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/menu/add' ?>";
	var menuEditUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/menuitems' ?>";
     var menuDeleteUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/menu/delete' ?>";
     var menuItemSaveUrl = "<?php echo env('APP_URL', 'http://localhost') . '/api/menuitem/save' ?>";
     var previousMenuItemUrl = "<?php if ($previousMenuItem) {
    echo env('APP_URL', 'http://localhost') . '/menuitems?menu_id=' . $menu_id . "&menu_item_id=" .
                                   $previousMenuItem->menu_item_id;
} else {
    echo env('APP_URL', 'http://localhost') . '/menuitems?menu_id=' . $menu_id;
} ?>";
     var globalRoleId = "";
     var globalRoleName = "";
</script>

<script style="text/javascript" src="/js/menuitem.js"></script>

@endpush

