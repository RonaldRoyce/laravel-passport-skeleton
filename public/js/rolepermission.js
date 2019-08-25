 $(document).ready(function() {
	$('#edit-rolepermission-btn').on('click', function() {
                $.ajax({
                        url: tokenUrl,
                        type:"GET",
			headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                        success: function(data, textStatus, jqXHR)
                        {
                               var token = data['access_token'];
				var roleId = $('#edit-rolepermission-btn').data('id');
                               $.ajax({
                                       url: rolePermissionsGetUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {roleid: roleId},
                                       success: function(data, textStatus, jqXHR)
                                       {
			                	$('#add-role-permissions-id').val(data.role_id);
                				$('#add-role-permissions-name').val(data.role_name);

						$('#permissions-table-body').children().remove();

						data.permissions.forEach(function(p) {
							var tr = document.createElement('tr');

							var td = document.createElement('td');

							td.className = "light-background";
							td.style.width = "20px";
							if (p.granted)
							{
								td.innerHTML = '<input type="checkbox" data-id="' + p.permission_id + '" checked />';
							}
							else
							{
								td.innerHTML = '<input type="checkbox" data-id="' + p.permission_id + '" />';
							}

							tr.appendChild(td);

							td = document.createElement('td');

							td.className = "light-background";
                                                        td.style.width = "400px";
							td.innerText = p.name;

							tr.appendChild(td);

							document.getElementById('permissions-table-body').appendChild(tr);
						});

                				$('#addRolePermissionsModal').modal('show') ;
                                                return false;
                                       },
                                       error: function (jqXHR, textStatus, errorThrown)
                                       {
                                                return false;
                                       }
                                });
                         },
                         error: function (jqXHR, textStatus, errorThrown)
                         {
                                 return false;
                         }
                });
	});

	$('#saverolepermission-btn').on('click', function() {
		var roleId = $('#add-role-permissions-id').val();
                var roleName = $('#add-role-permissions-name').val();
		var permissions = [];

		$('#permissions-table-body td input').each(function(e) {
			var o = $(this);

			if (o[0].checked)
			{
				var permission = o.data('id');

				permissions.push(permission);
			}
		});

                $.ajax({
                        url: tokenUrl,
                        type:"GET",
                        success: function(data, textStatus, jqXHR)
                        {
                 	       var token = data['access_token'];
				var postData = {roleid: roleId, permissions: permissions};

                               $.ajax({
                                       url: rolePermissionsAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: postData,
                                       success: function(data, textStatus, jqXHR)
                                       {
						window.location.href = '/admin/rolepermissions';
                                                return false;
                                       },
                                       error: function (jqXHR, textStatus, errorThrown)
                                       {
                                                return false;
                                       }
                                });
                         },
                         error: function (jqXHR, textStatus, errorThrown)
                         {
	                         return false;
                         }
                });

                return false;
    	});

        $('#rename-role').on('click', function() {
                var roleId = $('#delete-role').data('id');
                var roleName = $('#delete-role').data('name');

                $.ajax({
                        url: tokenUrl,
                        type:"GET",
                        success: function(data, textStatus, jqXHR)
                        {
                               var token = data['access_token'];
                               $.ajax({
                                       url: roleAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {roleid: roleId, rolename: roleName},
                                       success: function(data, textStatus, jqXHR)
                                       {
                                                window.location.href = '/admin/roles';
                                                return false;
                                       },
                                       error: function (jqXHR, textStatus, errorThrown)
                                       {
                                                return false;
                                       }
                                });
                         },
                         error: function (jqXHR, textStatus, errorThrown)
                         {
                                 return false;
                         }
                });

                return false;
        });

        $('#delete-role').on('click', function() {
                var roleName = $('#delete-role').data('name');

		$('#role-name').html(roleName);
		
		$('#deleteRoleModal').modal('show');

		$('#execute-delete-role-btn').on('click', function() {
                	$.ajax({
                        	url: tokenUrl,
                        	type:"GET",
                        	success: function(data, textStatus, jqXHR)
                        	{
                        	       	var token = data['access_token'];
                               		$.ajax({
                                       		url: roleDeleteUrl,
                                       		type:"GET",
                                       		headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       		data: {rolename: roleName},
                                       		success: function(data, textStatus, jqXHR)
                                       		{		
                                                	window.location.href = '/admin/roles';
                                                	return false;
                                       		},
                                       		error: function (jqXHR, textStatus, errorThrown)
                                       		{
                                                	return false;
                                       		}
                                	});
                         	},
                         	error: function (jqXHR, textStatus, errorThrown)
                         	{
                                	return false;
                         	}
                	});
        	});
	});

});


