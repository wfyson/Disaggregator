<!-- A modal for when a component has been added -->
<div class="modal fade" id="complete-modal" tabindex="-1" role="dialog" aria-labelledby="complete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title" id="complete-modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="left-panel">
                    <!-- return to homepage link -->
                    <h4>Go back...</h4>
                    <a class="btn btn-primary" href="index.php">Homepage</a>
                </div>
                <div class="right-panel">
                    <!-- or extract something else -->
                    <h4>Extract something else...</h4>
                    <div class="button-panel">
                        <a class="btn btn-success" href="extract.php?type=compound&docid=<?php echo $docid ?>"><span class="glyphicon glyphicon-plus"></span> New Compound</a>
                        <a class="btn btn-warning" href="extract.php?type=reaction&docid=<?php echo $docid ?>"><span class="glyphicon glyphicon-plus"></span> New Reaction</a>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

