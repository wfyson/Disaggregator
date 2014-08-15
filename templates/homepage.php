<?php include "templates/include/header.php" ?>


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
                foreach ($results['compounds'] as $compound) {                    
                    $compoundID = $compound->id;
                    $molPath = $compound->getMolPath();
                    ?>
                    <li>
                        <div class="entry compound-entry">

                            <script type="text/javascript">
                                var Info;
                                ;                                                              
                                
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

                            <div id="compound-<?php echo $compoundID ?>-mol" class="compound-jmol"></div>
                            
                            <div class="compound-data">
                                <!-- Other info about the compound -->
                                <b><?php echo htmlspecialchars($compound->name) ?></b>
                            </div>                            
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
                    <div id='file-upload' class ="entry">
                        <a class='btn btn-primary' href='javascript:;'>                        
                            <input id='files' type="file" style='position:absolute;z-index:2;top:55px;left:25px;width:125px;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="doc_source" size="40">
                            <span class="glyphicon glyphicon-plus"></span> Upload Document...
                        </a>
                    </div>
                    <div id="upload-progress" class="entry upload-entry">
                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        Uploading...
                        </div>
                    </div>
                </li>
                <?php
                foreach ($results['references'] as $reference) {
                    ?>
                    <li>
                        <div class="entry doc-entry" data-docid="<?php echo $reference->id ?>">                            
                            <div class="doc-info">
                                <!--thumbnail -->
                                <?php $format = $reference->getFormat(); ?>
                                <img src="img/<?php echo $format ?>_thumb.png"/>                                                                            
                                <b><?php echo htmlspecialchars($reference->refFile) ?></b>
                            </div>                           
                            <!-- buttons -->
                            <div class="doc-buttons">
                                <a class="btn btn-success" href="extract.php?type=compound&docid=<?php echo $reference->id ?>"><span class="glyphicon glyphicon-plus"></span> New Compound</a>
                                <a class="btn btn-warning" href="extract.php?type=reaction&docid=<?php echo $reference->id ?>"><span class="glyphicon glyphicon-plus"></span> New Reaction</a>
                            </div>
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
        input.addEventListener('change', handleFileSelect, false);
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
