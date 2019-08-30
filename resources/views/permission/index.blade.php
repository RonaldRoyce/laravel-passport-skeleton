@extends('layouts.app', ['pageSlug' => 'permission-index', 'titlePage' => __('Permissions'), 'sidebarType' => 'admin'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card main-card">
                <div class="card-header card-header-slim">
			<div>
				<div style="float: left;"><h3 class="slim">Permissions</h3></div>
				<div class="slim" style="float: right;"><button class="btn btn-info" data-toggle="modal" data-target="#addPermissionModal">Add Permission</button></div>
				<div class="clear: both;"></div>
			</div>
		</div>
                <div class="card-body">
			<table class="table table-striped table-bordered view-table">
				<thead class="thead-light ldap-groups">
					<tr>
						<th scope="col" class="ldap-group-name">Name</th>
						<th scope="col" class="ldap-group-action">Action</th>
					</tr>
				</thead>
				<tbody class="view-table">
					@foreach ($permissions as $permission)
						<tr>
							<td class="ldap-group-name">{{$permission->name}}</td>
							<td class="action-btns ldap-group-action"><button type="button" class="btn btn-primary" id="rename-permission" data-name="{{$permission->name}}" data-id="{{$permission->id}}">Rename</button>&nbsp;<button type="button" class="btn btn-danger delete-permission-btn" data-name="{{$permission->name}}" data-id="{{$permission->id}}">Delete</button></td>
						</tr>
					@endforeach
				</tbody>
			</table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" permission="dialog" aria-labelledby="addPermissionLabel" aria-hidden="true">
  <div class="modal-dialog" permission="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPermissionModalLabel">Add Permission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          		<div class="row">
            			<label for="permission-name" class="col-form-label">Permission Name</label>
				     <input type="text" class="form-control" id="permissionname" name="permissionname" required>
          		</div>
          		<div class="row">
            			<label for="permission-name" class="col-form-label">Page Identifier</label>
				     <input type="text" class="form-control" id="permission-page-id" name="permission-page-id" required>
          		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addpermission-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deletePermissionModal" tabindex="-1" permission="dialog" aria-labelledby="addPermissionLabel" aria-hidden="true">
  <div class="modal-dialog" permission="document">
    <div class="modal-content">
      <input type="hidden" id="delete-permissionid" name="delete-permissionid" />
      <div class="modal-header">
        <h2 class="modal-title" id="addPermissionModalLabel">Delete User Permission</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="permission-id" />
      <div class="modal-body">
                        <div>Are you sure you wish to delete the permission: <span id="permission-name" style="font-weight: bold;"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" id="execute-delete-permission-btn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('view-scripts')
<script style="text/javascript">
	var tokenUrl = "<?php echo env('APP_URL', 'http://localhost') . '/token' ?>";
	var permissionAddUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/permission/add' ?>";
     var permissionDeleteUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/permission/delete' ?>";
     var globalPermissionId = "";
     var globalPermissionName = "";
</script>

<script style="text/javascript" src="/js/permission.js"></script>
@endpush

