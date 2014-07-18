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
                <?php
                foreach ($results['compounds'] as $compound) {
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
                                        color: "0xC0C0C0",
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
                                    }
                                })();
                            </script>

                            <script>

                                Jmol.getTMApplet("jmol", Info)

                            </script>

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

                            <!--thumbnail -->
                            <?php $format = $reference->getFormat(); ?>
                            <img src="img/<?php echo $format ?>_thumb.png"/>                    
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
</script>


<?php include "templates/include/footer.php" ?>
