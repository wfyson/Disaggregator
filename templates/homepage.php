<?php include "templates/include/header.php" ?>

<!--logout button (should be in header perhaps...) -->
<form action="index.php?action=logout" method="post" style="width: 50%;">
    <input type="hidden" name="logout" value="true" />   
    <input class="btn btn-default" type="submit" name="logout" value="Logout" />
</form>


<div id="view" class="col-md-8">

    <!--Nav Tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#compounds" data-toggle="tab">Compounds</a></li>
        <li><a href="#reactions" data-toggle="tab">Reactions</a></li>
        <li><a href="#documents" data-toggle="tab">Documents</a></li>
    </ul>

    <!-- Tab Panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="compounds">
            <ul>            
                <?php
                foreach ($results['compounds'] as $compound) {
                    ?>
                    <li>
                        <div class="entry doc-entry">
                            <b><?php echo htmlspecialchars($compound->name) ?></b>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="tab-pane" id="reactions">
            <ul>            
                <?php
                foreach ($results['reactions'] as $reaction) {
                    ?>
                    <li>
                        <div class="entry doc-entry">
                            <b><?php echo htmlspecialchars($reaction->transformation) ?></b>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="tab-pane" id="documents">
            <ul>            
                <?php
                foreach ($results['references'] as $reference) {
                    ?>
                    <li>
                        <div class="entry doc-entry">
                            <b><?php echo htmlspecialchars($reference->refFile) ?></b>
                            <a class="btn btn-default" href="extract.php?docid=<?php echo $reference->id ?>">Extract</a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>



</div>

<!-- Sidebar -->
<div id='sidebar' class='col-md-3 col-md-offset-7'>

</div>  


<?php include "templates/include/footer.php" ?>
