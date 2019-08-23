 $(document).ready(function() {

	$('#addrole-btn').on('click', function() {
		var roleId = $('#roleid').val();
                var roleName = $('#rolename').val();

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


