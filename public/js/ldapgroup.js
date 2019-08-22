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
                                       url: groupAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {roleid: roleId, rolename: roleName},
                                       success: function(data, textStatus, jqXHR)
                                       {
						window.location.href = '/admin/groups';
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

        $('#rename-group').on('click', function() {
                var roleId = $('#delete-group').data('id');
                var roleName = $('#delete-group').data('name');

                $.ajax({
                        url: tokenUrl,
                        type:"GET",
                        success: function(data, textStatus, jqXHR)
                        {
                               var token = data['access_token'];
                               $.ajax({
                                       url: groupAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {roleid: roleId, rolename: roleName},
                                       success: function(data, textStatus, jqXHR)
                                       {
                                                window.location.href = '/admin/groups';
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

        $('#delete-group').on('click', function() {
                var roleName = $('#delete-group').data('name');

		$('#role-name').html(roleName);
		
		$('#deleteGroupModal').modal('show');

		$('#execute-delete-group-btn').on('click', function() {
                	$.ajax({
                        	url: tokenUrl,
                        	type:"GET",
                        	success: function(data, textStatus, jqXHR)
                        	{
                        	       	var token = data['access_token'];
                               		$.ajax({
                                       		url: groupDeleteUrl,
                                       		type:"GET",
                                       		headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       		data: {rolename: roleName},
                                       		success: function(data, textStatus, jqXHR)
                                       		{		
                                                	window.location.href = '/admin/groups';
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


