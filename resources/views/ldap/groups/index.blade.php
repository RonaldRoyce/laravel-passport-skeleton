@extends('layouts.app', ['pageSlug' => 'roleindex', 'titlePage' => __('User Roles'), 'sidebarType' => 'admin'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-slim">
			<div>
				<div style="float: left;"><h3 class="slim">User Roles</h3></div>
				<div class="slim" style="float: right;"><button class="btn btn-warning" data-toggle="modal" data-target="#addRoleModal">Add Role</button></div>
				<div class="clear: both;"></div>
			</div>
		</div>
                <div class="card-body">
			<table class="table table-striped table-bordered">
				<thead class="thead-light ldap-groups">
					<tr>
						<th scope="col" class="ldap-group-name">Name</th>
						<th scope="col" class="ldap-group-action">Action</th>
					</tr>
				</thead>
				<tbody class="ldap-groups">
					@foreach ($groups as $group)
						<tr>
							<td class="ldap-group-name">{{$group->name}}</td>
							<td class="action-btns ldap-group-action"><button type="button" class="btn btn-primary" id="rename-group" data-name="{{$group->name}}" data-id="{{$group->id}}">Rename</button>&nbsp;<button type="button" id="delete-group" class="btn btn-danger" data-name="{{$group->name}}" data-id="{{$group->id}}">Delete</button></td>
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
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleModalLabel">Add User Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          		<div>
            			<label for="role-id" class="col-form-label">Role Id</label>
            			<input type="number" class="form-control" style="width: 100px;" id="roleid" name="roleid" required>
          		</div>
          		<div>
            			<label for="role-name" class="col-form-label">Role Name</label>
				<input type="text" class="form-control" id="rolename" name="rolename" required>
          		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addrole-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteGroupModal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
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
	var groupAddUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/groups/add' ?>";
	var groupDeleteUrl =  "<?php echo env('APP_URL', 'http://localhost') . '/api/groups/delete' ?>";
</script>

<script style="text/javascript" src="/js/ldapgroup.js"></script>
@endpush

