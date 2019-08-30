@extends('layouts.app', ['pageSlug' => 'rolepermission-index', 'titlePage' => __('Role Permissions'), 'sidebarType' => 'admin'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-14">
            <div class="card main-card">
                <div class="card-header card-header-slim">
			<div>
				<div style="float: left;"><h3 class="slim">Role Permissions</h3></div>
				<div class="clear: both;"></div>
			</div>
		</div>
                <div class="card-body">
			<table class="table table-striped table-bordered view-table">
				<thead class="thead-light role-permission">
					<tr>
						<th scope="col" class="role-name">Role Name</th>
						<th scope="col" class="role-permissions">Permissions</th>
						<th scope="col" class="role-permission-action">Action</th>
					</tr>
				</thead>
				<tbody class="role-permission view-table">
					@foreach ($rolepermissions as $role)
						<tr>
							<td class="role-name">{{$role->name}}</td>
							<td class="role-permissions">
                                        @if (count($role->permissions) > 0)
                                        <table class="table table-bordered table-striped">
                                             <thead style="display: block;">
                                                  <tr>
                                                       <th class="permission-name">Name</th>
                                                       <th class="permission-page-id">Page Id</th>
                                                  </tr> 
                                             </thead>
                                             <tbody style="display: block;max-height: 200px;overflow-y: auto;">
                                             @foreach ($role->permissions as $permission)
                                                  <tr>
                                                       <td class="permission-name dark-background">{{$permission->name}}</td>
                                                       <td class="permission-page-id dark-background">{{$permission->page_id}}</td>
                                                  </tr>
                                             @endforeach
                                             </tbody>
                                        </table>
                                        @endif
                                   </td>
							<td class="action-btns role-permission-action">
                                        <button type="button" class="btn btn-primary edit-rolepermission-btn" data-name="{{$role->name}}" data-id="{{$role->role_id}}">Edit</button>
                                   </td>
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
<div class="modal fade" id="addRolePermissionsModal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
  <input type="hidden" id="add-role-permissions-id" name="add-role-permissions-id">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleModalLabel">Edit Role Permissions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          		<div>
            			<label for="role-name" class="col-form-label">Role Name</label>
				<input type="text" class="form-control" id="add-role-permissions-name" name="add-role-permissions-name" readonly>
          		</div>
			<div>
				<label for="permissions" class="col-form-label">Permissions</label>
				<div>
					<table class="table table-bordered-black">
						<thead style="display: block;">
							<th class="light-background">&nbsp;</th>
							<th class="light-background">Permission</th>
						</thead>
						<tbody id="permissions-table-body" style="display: block;max-height: 431px;overflow-y: auto;">
							<tr>
								<td class="light-background" style="width: 20px;"><input type="checkbox"></input></td>
								<td class="light-background" style="width: 400px;">Permission 1</td>
							</tr>
        	                	        	<tr>
                	                	        	<td class="light-background" style="width: 20px;"><input type="checkbox"></input></td>
                        	                		<td class="light-background" style="width: 400px;">Permission 2</td>
							</tr>
						</tbody>
	                                </table>
				</div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saverolepermission-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteRolePermissionsModal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="addRoleModalLabel">Delete User Role</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="role-id" />
      <div class="modal-body">
                        <div>Are you sure you wish to delete the role: <span id="role-name" style="font-weight: bold;"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" id="execute-delete-group-btn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('view-scripts')
<script style="text/javascript">
	var tokenUrl = "<?php echo env('APP_URL', 'http://localhost') . '/token' ?>";
	var rolePermissionsAddUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/rolepermission/add' ?>";
	var roleDeleteUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/roles/delete' ?>";
	var rolePermissionsGetUrl = "<?php echo env('APP_URL', 'http://localhost') . '/api/rolepermission/get' ?>";
</script>

<script style="text/javascript" src="/js/rolepermission.js"></script>
@endpush

