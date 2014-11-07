<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                    
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="complete-modal-title"><?php echo $_GET['authorise'] ?> authorisation</h4>
            </div>
            <div class="modal-body">
                <?php if ($_GET['success']){
                    ?> 
                <h2>Authorisation Approved</h2>
                <p>Thank you for authorising access to your <?php echo $_GET['authorise'] ?> profile!</p>
                <p>You are now free to publish your research outputs to this account.</p>
                <?php }else{
                    ?>
                <h2>Authorisation Denied</h2>
                <p>Unfortunately access to your <?php echo $_GET['authorise'] ?> profile was denied.</p>
                <p>Before the disaggregator can publish your research outcomes to this platform, authorisation must be given.</p>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>