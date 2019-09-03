<div class="modal fade" id="addMenuItemModal" tabindex="-1" menu="dialog" aria-labelledby="addMenuItemLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content" style="width: 800px;">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuItemLabel">Add Menu Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="save-message-div" class="alert" role="alert" style="display: none;"></div>
            <div>
                         <input type="text" class="form-control-black" id="create-menu-item-text" placeholder="Text" name="menu-item-text" value="" required>
                    </div>
                    <div>
                         <input type="text" class="form-control-black" id="create-page-id" name="create-page-id" placeholder="Page Id" value="" required>
                    </div>
                    <div  >
                         <input type="text" class="form-control-black" id="create-anchor-url" name="create-anchor-url" placeholder="Anchor Url" value="" required>
                    </div>
      
                    <div>
                         <input type="text" class="form-control-black" id="create-image-class" name="create-image-class" placeholder="Image class" value="" required>
                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="create-menu-item-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteMenuItemModal" tabindex="-1" menu="dialog" aria-labelledby="deleteMenuItemModalLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content">
      <input type="hidden" id="delete-menu-item-id" name="delete-menu-item-id" />
      <div class="modal-header">
        <h2 class="modal-title" id="deleteMenuItemModalLabel">Delete User Role</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="menu-id" />
 
      <div class="modal-body">
          <div id="delete-message-div" class="alert" role="alert" style="display: none;"></div>
          <div>Are you sure you wish to delete the menu: <span id="delete-menu-item-text" style="font-weight: bold;"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" id="execute-delete-menu-item-btn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>