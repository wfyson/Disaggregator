<?php include "templates/include/header.php" ?>

<?php $compound = Compound::getById($_GET['id']) ?>

<div id="compound-view" class="col-md-8">            
    <div id="compound-panel" class="view-title">      
        <h3>
            <?php echo $compound->name ?>
        </h3>
    </div>

    <div id="compound-content" class="view-content">
        <script type="text/javascript">
            var molArray = new Array();
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
                    src: "<?php echo $compound->getMolPath(); ?>",
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
                molArray[<?php echo $compound->id ?>] = Info;
            })();
        </script>     
        <div id="compound-<?php echo $compound->id ?>-mol" class="compound-jmol"></div>
    </div>             
</div>

<!-- Sidebar-->
<!-- here the sidebar describes stuff related to the compound (doc, reactions, blah) -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>        
</div>  

<script>
    window.onload = function() {

    };
    
    $(document).ready(function(){              
        Jmol.setDocument(0);
        for (var id in molArray){
            console.log(id);
            Jmol.getTMApplet("mol" + id, molArray[id]);
            $('#compound-' + id + '-mol').html(Jmol.getAppletHtml(window["mol" + id]));
        }
    });
</script>

<?php include "templates/include/footer.php" ?>

