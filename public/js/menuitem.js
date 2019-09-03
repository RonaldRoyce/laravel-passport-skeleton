$(document).ready(function () {
  var roleName = ''
  var roleId = ''

  $('.move-down-btn').on('click', function () {
    var menuId = this.attributes['data-id'].value
  })

  $('.move-up-btn').on('click', function () {
    var menuId = this.attributes['data-id'].value
  })

  $('.edit-menu-item-btn').on('click', function () {
    var menuId = $('#menu_id').val()
    var menuItemId = this.attributes['data-id'].value

    window.location.href =
      menuEditUrl + '?menu_id=' + menuId + '&menu_item_id=' + menuItemId
  })

  $('#cancel-save-btn').on('click', function () {
    window.location.href = previousMenuItemUrl
  })

  $('#save-menu-item-details-btn').on('click', function () {
    var menuId = $('#menu_id').val()
    var menuItemId = $('#menu_item_id').val()

    var menuItemText = $('#menu-item-text').val()
    var pageId = $('#page-id').val()
    var anchorUrl = $('#anchor-url').val()
    var divAnchorName = $('#div-anchor-name').val()
    var imageClass = $('#image-class').val()

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: menuItemSaveDetailsUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: {
            menuitemid: menuItemId,
            menuitemtext: menuItemText,
            pageid: pageId,
            anchorurl: anchorUrl,
            divanchorname: divAnchorName,
            imageclass: imageClass
          },
          success: function (data, textStatus, jqXHR) {
            if (data.success) {
              $('#save-message-div')
                .removeClass('alert-danger')
                .addClass('alert-info')
              $('#save-message-div').html('Menu item was saved successfully')
            } else {
              $('#save-message-div')
                .removeClass('alert-info')
                .addClass('alert-danger')
              $('#save-message-div').html(data.message)
            }

            $('#save-message-div').show()
            setTimeout(function () {
              $('#save-message-div').hide()
            }, 5000)
            return true
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

  $('#create-menu-item-btn').on('click', function () {
    var menuId = $('#menu_id').val()
    var menuItemId = $('#menu_item_id').val()
    var menuItemParentId = menuItemId == 0 ? null : menuItemId
    var menuItemText = $('#create-menu-item-text').val()
    var pageId = $('#create-page-id').val()
    var anchorUrl = $('#create-anchor-url').val()
    var divAnchorName = $('#create-div-anchor-name').val()
    var imageClass = $('#create-image-class').val()

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: menuItemAddUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: {
            menuid: menuId,
            menuitemparentid: menuItemId,
            menuitemtext: menuItemText,
            menuitemtype: 'M',
            pageid: pageId,
            anchorurl: anchorUrl,
            divanchorname: '',
            imageclass: imageClass
          },
          success: function (data, textStatus, jqXHR) {
            if (data.success) {
              if (menuItemParentId) {
                window.location.href =
                  '/menuitems?menu_id=' +
                  menuId +
                  '&menu_item_id=' +
                  menuItemParentId
              } else {
                window.location.href = '/menuitems?menu_id=' + menuId
              }
            } else {
              $('#save-message-div')
                .removeClass('alert-info')
                .addClass('alert-danger')
              $('#save-message-div').html(data.message)

              $('#save-message-div').show()
              setTimeout(function () {
                $('#save-message-div').hide()
              }, 5000)
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
  })

  $('#save-menu-item-btn').on('click', function () {
    var menuId = $('#menu_id').val()
    var menuItemId = $('#menu_item_id').val()
    var menuItemParentId = $('#menu_item_parent_id').val()
    var menuItemText = $('#menu-item-text').val()
    var pageId = $('#page-id').val()
    var anchorUrl = $('#anchor-url').val()
    var divAnchorName = $('#div-anchor-name').val()
    var imageClass = $('#image-class').val()

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: menuItemSaveUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: {
            menuitemid: menuItemId,
            menuitemtext: menuItemText,
            pageid: pageId,
            anchorurl: anchorUrl,
            divanchorname: divAnchorName,
            imageclass: imageClass
          },
          success: function (data, textStatus, jqXHR) {
            window.location.href =
              '/menuitems?menu_id=' +
              menuId +
              '&menu_item_id=' +
              menuItemParentId
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

  $('#create-menu-btn').on('click', function () {
    var menuId = $('#menu_id').val()
    var menuItemId = $('#menu_item_id').val()
    var menuItemText = $('#menu-item-text').val()
    var pageId = $('#page-id').val()
    var divAnchorName = $('#div-anchor-name').val()
    var imageClass = $('#image-class').val()

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: menuItemAddUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: {
            menuid: menuId,
            menuitemparentid: menuItemId == 0 ? null : menuItemId,
            menuitemtext: menuItemText,
            menuitemtype: 'G',
            pageid: pageId,
            anchorurl: '',
            divanchorname: divAnchorName,
            imageclass: imageClass
          },
          success: function (data, textStatus, jqXHR) {
            window.location.href =
              '/menuitems?menu_id=' +
              menuId +
              '&menu_item_id=' +
              menuItemParentId
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

  $('#rename-role').on('click', function () {
    roleId = $('#delete-role').data('id')
    roleName = $('#delete-role').data('name')

    $.ajax({
      url: tokenUrl,
      type: 'GET',
      success: function (data, textStatus, jqXHR) {
        var token = data['access_token']
        $.ajax({
          url: roleAddUrl,
          type: 'GET',
          headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          },
          data: { roleid: roleId, rolename: roleName },
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

    return false
  })

  $('.delete-menu-item-btn').on('click', function () {
    globalMenuItemText = this.attributes['data-name'].value
    globalMenuItemId = this.attributes['data-id'].value

    $('#delete-menu-item-id').html(globalMenuItemId)
    $('#delete-menu-item-text').html(globalMenuItemText)

    $('#deleteMenuItemModal').modal('show')

    $('#execute-delete-menu-item-btn').on('click', function () {
      $.ajax({
        url: tokenUrl,
        type: 'GET',
        success: function (data, textStatus, jqXHR) {
          var token = data['access_token']
          $.ajax({
            url: menuItemDeleteUrl,
            type: 'GET',
            headers: {
              Authorization: 'Bearer ' + token,
              Accept: 'application/json',
              'Content-Type': 'application/json'
            },
            data: { menuitemid: globalMenuItemId },
            success: function (data, textStatus, jqXHR) {
              if (data.success) {
              } else {
                $('#delete-message-div')
                  .removeClass('alert-info')
                  .addClass('alert-danger')
                $('#delete-message-div').html(data.message)

                $('#delete-message-div').show()
                setTimeout(function () {
                  $('#delete-message-div').hide()
                }, 5000)
              }
              if ($('#menu_item_id').val() != '0') {
                window.location.href =
                  '/menuitems?menu_id=' +
                  $('#menu_id').val() +
                  '&menu_item_id=' +
                  $('#menu_item_id').val()
              } else {
                window.location.href =
                  '/menuitems?menu_id=' + $('#menu_id').val()
              }
              // window.location.href = '/admin/roles'
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
