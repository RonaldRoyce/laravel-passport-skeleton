<!-- Add menu Modal -->

<div class="modal fade" id="addMenuModal" tabindex="-1" menu="dialog" aria-labelledby="addMenuLabel" aria-hidden="true">
  <div class="modal-dialog" menu="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMenuLabel">Add Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div>
                       <label for="menu-name" class="col-form-label">Text</label>
                         <input type="text" class="form-control-black" id="menu-item-text" name="menu-item-text" value="" required>
                    </div>
                    <div>
                       <label for="menu-name" class="col-form-label">Page Id</label>
                         <input type="text" class="form-control-black" id="page-id" name="page-id" value="" required>
                    </div>
                         <div>
                              <label for="div-anchor-name" class="col-form-label">Anchor Div Name</label>
                              <input type="text" class="form-control-black" id="div-anchor-name" name="div-anchor-name" value="" required>
                         </div>

                    <div>
                       <label for="menu-name" class="col-form-label">Image Class</label>
                         <input type="text" class="form-control-black" id="image-class" name="image-class" value="" required>
                    </div>
                     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="create-menu-btn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
