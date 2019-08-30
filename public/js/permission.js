 $(document).ready(function() {
     var permissionName = "";
     var permissionId = "";
     
	$('#addpermission-btn').on('click', function() {
                var permissionName = $('#permissionname').val();
               var pageId = $('#permission-page-id').val();

                $.ajax({
                        url: tokenUrl,
                        type:"GET",
                        success: function(data, textStatus, jqXHR)
                        {
                 	       var token = data['access_token'];
                               $.ajax({
                                       url: permissionAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {permissionname: permissionName, "pageid": pageId},
                                       success: function(data, textStatus, jqXHR)
                                       {
                                            if (!data.success)
                                            {
                                                  alert(data.message);
                                                  return;
                                            }
                              
                                            window.location.href = '/admin/permissions';
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

        $('#rename-permission').on('click', function() {
                permissionId = $('#delete-permission').data('id');
                permissionName = $('#delete-permission').data('name');

                $.ajax({
                        url: tokenUrl,
                        type:"GET",
                        success: function(data, textStatus, jqXHR)
                        {
                               var token = data['access_token'];
                               $.ajax({
                                       url: permissionAddUrl,
                                       type:"GET",
                                       headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       data: {permissionid: permissionId, permissionname: permissionName},
                                       success: function(data, textStatus, jqXHR)
                                       {
                                                window.location.href = '/admin/permissions';
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

        $('.delete-permission-btn').on('click', function() {
               globalPermissionName = this.attributes['data-name'].value;
               globalPermissionId = this.attributes['data-id'].value;
               
		$('#permission-name').html(permissionName);
		
		$('#deletePermissionModal').modal('show');

		$('#execute-delete-permission-btn').on('click', function() {
                	$.ajax({
                        	url: tokenUrl,
                        	type:"GET",
                        	success: function(data, textStatus, jqXHR)
                        	{
                        	       	var token = data['access_token'];
                               		$.ajax({
                                       		url: permissionDeleteUrl,
                                       		type:"GET",
                                       		headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                                       		data: {permissionid: globalPermissionId, permissionname: globalPermissionName},
                                       		success: function(data, textStatus, jqXHR)
                                       		{		
                                                	window.location.href = '/admin/permissions';
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


