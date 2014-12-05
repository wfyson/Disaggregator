<!-- A modal for adding a new contributor -->
<div class="modal fade" id="contributor-modal" tabindex="-1" role="dialog" aria-labelledby="contributor-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title" id="contributor-modal-title">New Contributor</h4>
            </div>
                <div class="modal-body">                  

                    <div id="contributor-error" class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>     
                        <span id="contributor-error-message"></span>
                    </div>

                    <div class='form-group'>
                        <label for="firstname">First Name*: </label>
                        <input id="firstname" class="form-control" type="text" name="firstname" />
                    </div>
                    
                    <div class='form-group'>
                        <label for="familyname">Family Name*: </label>
                        <input id="familyname" class="form-control" type="text" name="familyname" />
                    </div>
                    
                    <div class='orcid'>
                        <label for="orcid">Orcid: </label>
                        <input id="orcid" class="form-control" type="text" name="orcid" />
                    </div>
                </div>

                <div class="modal-footer">
                    <div class='form-buttons'>
                        <button id="new-contributor" class="btn btn-success">Save</button>
                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>                           
        </div>
    </div>
</div>

