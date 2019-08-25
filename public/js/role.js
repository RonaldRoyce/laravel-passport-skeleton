 $(document).ready(function() {
     var roleName = "";
     var roleId = "";
     
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
                roleId = $('#delete-role').data('id');
                roleName = $('#delete-role').data('name');

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

        $('.delete-role-btn').on('click', function() {
               globalRoleName = this.attributes['data-name'].value;
               globalRoleId = this.attributes['data-id'].value;
               
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
                                       		data: {roleid: globalRoleId, rolename: globalRoleName},
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


