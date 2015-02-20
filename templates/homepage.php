<?php include "templates/include/header.php" ?>

<?php if (isset($_GET['authorise'])){ 
    include "homepage/registerModal.php";
}
?>

<div id="home-view" class="col-md-8">

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
                <script type="text/javascript">
                    var molArray = new Array();
                </script>
                <?php
                if(count($results['compounds']) > 0){
                foreach ($results['compounds'] as $compound) {                    
                    $compoundID = $compound->id;
                    $molPath = $compound->getMolPath();
                    ?>
                    <li>
                        <div class="entry compound-entry row">

                            <script type="text/javascript">
                                var Info;                                                                                           
                                
                                (function() {
                                    Info = {
                                        width: 100,
                                        height: 100,
                                        debug: false,
                                        color: "#eee",
                                        addSelectionOptions: false,
                                        serverURL: "http://chemapps.stolaf.edu/jmol/jsmol/php/jsmol.php",
                                        use: "HTML5",
                                        readyFunction: null,
                                        src: "<?php echo $molPath; ?>",
                                        bondWidth: 4,
                                        zoomScaling: 1.5,
                                        pinchScaling: 2.0,
                                        mouseDragFactor: 0.5,
                                        touchDragFactor: 0.15,
                                        multipleBondSpacing: 4,
                                        spinRateX: 0.2,
                                        spinRateY: 0.5,
                                        spinFPS: 20,
                                        spin: true,
                                        debug: false
                                    };
                                    molArray[<?php echo $compoundID ?>] = Info;
                                })();
                            </script>

                            <div id="compound-<?php echo $compoundID ?>-mol" class="compound-jmol col-md-2"></div>
                            
                            <div class="compound-data col-md-7">
                                <!-- Other info about the compound -->
                                <b><?php echo htmlspecialchars($compound->name) ?></b>
                            </div>      
                            <!-- buttons -->
                            <div class="compound-buttons col-md-3">
                                <a class="btn btn-primary btn-xs" href="view.php?type=compound&id=<?php echo $compound->id ?>">View</a>     
                                <a class="btn btn-primary btn-xs" href="publish.php?type=Compound&id=<?php echo $compound->id ?>&platform=orcid">Publish</a>
                                <a class="btn btn-success btn-xs" href="extract.php?type=spectra&docid=<?php echo $compound->referenceID ?>&compoundid=<?php echo $compound->id ?>"><span class="glyphicon glyphicon-plus"></span> Add Spectrum</a> 
                            </div>
                        </div>
                    </li>
                <?php }
               } else {
                    ?>
                        <div class="no-results"><h4>No compounds available!</h4></div>
          <?php } ?>
            </ul>
        </div>
        <div class="tab-pane" id="reactions">
            <ul>            
                <?php
                if (count($results['reactions']) > 0) {
                    foreach ($results['reactions'] as $reaction) {
                        ?>
                        <li>
                            <div class="entry reaction-entry row">
                                <div class="reaction-data col-md-9">
                                    <span><b><?php echo htmlspecialchars($reaction->transformation) ?></b></span>
                                    <span><b>Result: <?php echo htmlspecialchars(Compound::getById($reaction->result)->name) ?></b></span>
                                </div>
                                <!-- buttons -->
                                <div class="reaction-buttons col-md-3">
                                    <a class="btn btn-primary btn-xs" href="view.php?type=reaction&id=<?php echo $reaction->id ?>">View</a>     
                                    <a class="btn btn-primary btn-xs" href="publish.php?type=Reaction&id=<?php echo $reaction->id ?>&platform=orcid">Publish</a>
                                </div>
                            </div>                        
                        </li>
                    <?php
                    }
                } else {
                    ?>
                        <div class="no-results"><h4>No reactions available!</h4></div>
          <?php } ?>
            </ul>
        </div>
        <div class="tab-pane" id="documents">
            <ul>         
                <li>
                    <div id='file-upload' class ="entry">
                        <a class='btn btn-primary' href='javascript:;'>                        
                            <input id='files' type="file" style='position:absolute;z-index:2;top:55px;left:25px;width:125px;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="doc_source" size="40">
                            <span class="glyphicon glyphicon-plus"></span> Upload Document
                        </a>
                        <a class='btn btn-primary' href='import/'>                                                    
                            <span class="glyphicon glyphicon-import"></span> Import
                        </a>
                    </div>
                    <div id="upload-progress" class="entry upload-entry">
                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        Uploading...
                        </div>
                    </div>
                </li>
                <?php
                if (count($results['references']) > 0) {
                    foreach ($results['references'] as $reference) {
                        ?>
                        <li>
                            <div class="entry doc-entry row" data-docid="<?php echo $reference->id ?>">                            
                                <div class="doc-info col-md-9">
                                    <!--thumbnail -->
                                    <?php $format = $reference->getFormat(); ?>
                                    <img src="img/<?php echo $format ?>_thumb.png"/>                                                                            
                                    <b><?php echo htmlspecialchars($reference->refFile) ?></b>
                                </div>                           
                                <!-- buttons -->
                                <div class="doc-buttons col-md-3">
                                    <a class="btn btn-success btn-sm" href="extract.php?type=compound&docid=<?php echo $reference->id ?>"><span class="glyphicon glyphicon-plus"></span> New Compound</a>
                                    <a class="btn btn-warning btn-sm" href="extract.php?type=reaction&docid=<?php echo $reference->id ?>"><span class="glyphicon glyphicon-plus"></span> New Reaction</a>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                } else {
                    ?>
                    <div class="no-results"><h4>No documents available!</h4></div>
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

        switch (hash) {
            case "documents":
                $('.nav-tabs a[href=#documents]').tab('show');
                break;
            case "reactions":
                $('.nav-tabs a[href=#reactions]').tab('show');
                break;
            case "compounds":
                $('.nav-tabs a[href=#compounds]').tab('show');
                break;
        }

        // Setup the file input listener
        var input = document.getElementById('files');
        input.addEventListener('change', function(evt){
            handleFileSelect(evt, function(){
                var baseUrl = url.split('#')[0];
                url = baseUrl + '#documents';                            
                document.location.href = url;
                location.reload();
            });
        }, false);
        
        <?php if(isset($_GET['authorise'])){
            ?>               
                $('#register-modal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
        <?php } ?>
        
    };
    
    $(document).ready(function(){              
        Jmol.setDocument(0);
        for (var id in molArray){
            Jmol.getTMApplet("mol" + id, molArray[id]);
            $('#compound-' + id + '-mol').html(Jmol.getAppletHtml(window["mol" + id]));
        }
        
        //set document on click functionality
        $('.doc-entry').click(function(){           
            //get everything interesting relating to this document
            //e.g it's reactions, compounds, whatever... presumably via a .json call of some sort...
        });
    });
</script>


<?php include "templates/include/footer.php" ?>
