$(document).ready(function () {
  var roleName = ''
  var roleId = ''

  $('#edit-menu-btn').on('click', function () {
    var menuId = $('#edit-menu-btn').data('id')

    window.location.href = menuEditUrl + '?menu_id=' + menuId

    return false
  })

  $('#add-menu-btn').on('click', function () {
    menuName = $('#menu-name-input').val()

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: menuAddUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: { name: menuName },
          success: function (response, textStatus, jqXHR) {
            if (response.success) {
              window.location.href =
                menuEditUrl + '?menu_id=' + response.data.id
            }
            return false
          },
          error: function (jqXHR, textStatus, errorThrown) {
            return false
          }
        })
      },
      error: function (jqXHR, textStatus, errorThrown) {
        return false
      }
    })

    return false
  })

  $('.edit-menu-btn').on('click', function () {
    window.location.href =
      menuEditUrl + '?menu_id=' + this.attributes['data-id'].value
  })

  $('.delete-role-btn').on('click', function () {
    globalRoleName = this.attributes['data-name'].value
    globalRoleId = this.attributes['data-id'].value

    $('#role-name').html(roleName)

    $('#deleteRoleModal').modal('show')

    $('#execute-delete-role-btn').on('click', function () {
      $.ajax({
        url: tokenUrl,
        type: 'GET',
        success: function (data, textStatus, jqXHR) {
          var token = data['access_token']
          $.ajax({
            url: roleDeleteUrl,
            type: 'GET',
            headers: {
              Authorization: 'Bearer ' + token,
              Accept: 'application/json',
              'Content-Type': 'application/json'
            },
            data: { roleid: globalRoleId, rolename: globalRoleName },
            success: function (data, textStatus, jqXHR) {
              window.location.href = '/admin/roles'
              return false
            },
            error: function (jqXHR, textStatus, errorThrown) {
              return false
            }
          })
        },
        error: function (jqXHR, textStatus, errorThrown) {
          return false
        }
      })
    })
  })
})
