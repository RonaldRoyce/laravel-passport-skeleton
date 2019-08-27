 $(document).ready(function() {
     function showPageError(msg)
     {
	$('#error-message-div').html(msg);
        $('#eror-message-div').show();

	setTimeout(function() {
		$('#error-message-div').hide();
	}, 5000);
     }

     $('.edit-rolepermission-btn').on('click', function() {
          var roleId = this.attributes['data-id'].value;

          $.ajax({
               url: tokenUrl,
               type:"GET",
               headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
               success: function(data, textStatus, jqXHR)
               {
                         var token = data['access_token'];                     
                         $.ajax({
                              url: rolePermissionsGetUrl,
                              type:"GET",
                              headers: {'Authorization': "Bearer " + token, "Accept" : "application/json", "Content-Type": "application/json" },
                              data: {roleid: roleId},
                              success: function(data, textStatus, jqXHR)
                              {
					if (!data.success)
					{
						showPageError(data.message);
						return false;
					}

                         		$('#add-role-permissions-id').val(data.data.role_id);
                         		$('#add-role-permissions-name').val(data.data.role_name);

                    			$('#permissions-table-body').children().remove();

                    			data.data.permissions.forEach(function(p) {
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
						if (!data.success)
						{
							showPageError(data.message);
							return;
						}

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
});


