<?php include "templates/include/header.php" ?>


<div id="view" class="col-md-8">

    <!--Nav Tabs -->
    <ul class="nav nav-tabs" id="homepage-tabs">
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
                <li>
                    <div class ="entry">
                        <a class='btn btn-primary' href='javascript:;'>                        
                            <input id='files' type="file" style='position:absolute;z-index:2;top:55px;left:25px;width:125px;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40">
                            Upload Document...
                        </a>
                    </div>
                </li>
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

<script>
    
    window.onload = function() {  
        
        var url = window.location.href;
        var hash = url.split('#')[1];
        
        switch (hash){
            case "documents":
                $('.nav-tabs a[href=#documents]').tab('show') ;
            break;
            case "reactions":
                $('.nav-tabs a[href=#reactions]').tab('show') ;
            break;
            case "compounds":
                $('.nav-tabs a[href=#compounds]').tab('show') ;
            break;                       
        }
        
        
        // Setup the file input listener
        var input = document.getElementById('files');
        input.addEventListener('change', handleFileSelect, false); 
    };                       
</script>


<?php include "templates/include/footer.php" ?>
