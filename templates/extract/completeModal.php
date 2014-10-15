<!-- A modal for when a component has been added -->
<div class="modal fade" id="complete-modal" tabindex="-1" role="dialog" aria-labelledby="complete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title" id="complete-modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="left-panel" id="complete-modal-options">
                    <!-- return to homepage link -->
                    <h4>Go back...</h4>
                    <a class="btn btn-primary" href="index.php">Homepage</a>
                                        
                    <?php if (isset($_GET['compoundid'])){ ?>
                            <h4>Add another spectrum...</h4>
                            <a class="btn btn-info" href="extract.php?type=spectra&docid=<?php echo $docid ?>&compoundid=<?php echo $_GET['compoundid']; ?>"><span class="glyphicon glyphicon-plus"></span> New Spectrum</a>
                        <?php } ?>
                </div>
                <div class="right-panel">
                    <!-- or extract something else -->
                    <h4>Extract something else...</h4>
                    <div class="button-panel" id="complete-modal-extract">
                        <a class="btn btn-success" href="extract.php?type=compound&docid=<?php echo $docid ?>"><span class="glyphicon glyphicon-plus"></span> New Compound</a>
                        <a class="btn btn-warning" href="extract.php?type=reaction&docid=<?php echo $docid ?>"><span class="glyphicon glyphicon-plus"></span> New Reaction</a>                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

