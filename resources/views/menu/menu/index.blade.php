@extends('layouts.app', ['pageSlug' => 'menu-index', 'titlePage' => __('Menus'), 'sidebarType' => 'admin'])

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-slim">
			<div>
				<div style="float: left;"><h3 class="slim">Menus</h3></div>
				<div class="slim" style="float: right;"><button class="btn btn-info" data-toggle="modal" data-target="#addMenuModal">Add Menu</button></div>
				<div class="clear: both;"></div>
			</div>
		</div>
                <div class="card-body">
			<table class="table table-striped table-bordered">
				<thead class="thead-light ldap-groups">
					<tr>
						<th scope="col" class="menu-text">Name</th>
						<th scope="col" class="menu-action">Action</th>
					</tr>
				</thead>
				<tbody class="ldap-groups">
                         <?php
                         $itemNum = 0;
                         ?>
					@foreach ($menus as $menu)
						<tr>
							<td class="menu-text">{{$menu->name}}</td>
							<td class="menu-action"><button type="button" class="btn btn-primary edit-menu-btn" data-id="{{$menu->menu_id}}">Edit</button>&nbsp;<button type="button" class="btn btn-danger delete-menu-btn" data-name="{{$menu->name}}" data-id="{{$menu->menu_id}}">Delete</button></td>
                              </tr>
                              <?php
                                   $itemNum++;
                              ?>
					@endforeach
				</tbody>
			</table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" menu="dialog" aria-labelledby="addMenuLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuLabel">Add Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          		<div>
            			<label for="menu-name" class="col-form-label">Menu Name</label>
				<input type="text" class="form-control-black dark-background" id="menu-name-input" name="menu-name-input" required>
          		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="add-menu-btn" type="button" class="btn btn-primary">Add</button>
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
</script>

<script style="text/javascript" src="/js/menu.js"></script>

@endpush

