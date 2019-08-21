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
				<thead class="thead-light">
					<tr>
						<th scope="col">Id</th>
						<th scope="col">Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($groups as $group)
						<tr>
							<td>{{$group->id}}</td>
							<td>{{$group->name}}</td>
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
      <form class="form" action="/api/groups/add" method="POST">
        @csrf

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
        <button id="addrole-btn" type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('js')
<script style="text/javascript">
$(function() {

    $('#addRoleModal').submit(function() {

                              $.ajax({
                                        url: '<?php echo env('APP_URL', 'http://localhost') . "/callback" ?>',
                                        type:"GET",
                                        success: function(data, textStatus, jqXHR)
                                        {
                                                var token = data['access_token'];
                                                $.ajax({
                                                        url: '<?php echo env('APP_URL', 'http://localhost') . "/api/groups/add" ?>',
                                                        type:"GET",
                                                        headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
							data: {roleid: 200, rolename: 'My Role 200'},
                                                        success: function(data, textStatus, jqXHR)
                                                        {
                                                                var a = 1;
								return true;
                                                        },
                                                        error: function (jqXHR, textStatus, errorThrown)
                                                        {
                                                                var b = 1;
								return false;
                                                        }
                                                });
                                        },
                                        error: function (jqXHR, textStatus, errorThrown)
                                        {
                                                var b = 1;
						return false;
                                        }
                                });

				return false;
    });
});
</script>
@endpush

