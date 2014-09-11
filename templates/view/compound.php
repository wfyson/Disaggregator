<?php include "templates/include/header.php" ?>

<?php $compound = Compound::getById($_GET['id']) ?>

<script type="text/javascript">

var Info = {
	width: 300,
	height: 300,
	debug: false,
	color: "0xFFFFFF",
	addSelectionOptions: false,
	use: "HTML5",   // JAVA HTML5 WEBGL are all options
	j2sPath: "js/libs/jsmol/j2s", // this needs to point to where the j2s directory is.
	jarPath: "js/libs/jsmol/java",// this needs to point to where the java directory is.
	jarFile: "JmolAppletSigned.jar",
	isSigned: true,
	src: "<?php echo $compound->getMolPath(); ?>",
	serverURL: "http://chemapps.stolaf.edu/jmol/jsmol/php/jsmol.php",	
	disableJ2SLoadMonitor: true,
        disableInitialConsole: true,
        allowJavaScript: true	
}

$(document).ready(function() {
  $("#appdiv").html(Jmol.getAppletHtml("jmolApplet0", Info))
})
</script>

<div id="compound-view" class="col-md-8">            
    <div id="compound-panel" class="view-title">      
        <h3>
            <?php echo $compound->name ?>
        </h3>
    </div>

    <div id="compound-content" class="view-content">
        <div class="main">        
            <div id="appdiv"></div>
        
            <div id="desc">
                <?php echo $compound->description ?>
            </div>
        </div>
        
        <div id="tags">
                <?php $keywords = $compound->getTags(); ChromePhp::log($keywords);              
                foreach ($keywords as $keyword){
                    ?>
                    <div class="keyword">
                        <?php echo $keyword ?></b>
                    </div>                    
                <?php } ?>
        </div>
    </div>
</div>

<!-- Sidebar-->
<!-- here the sidebar describes stuff related to the compound (doc, reactions, blah) -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>     
    
    <div class="sidebar-title">
        Related Reactions
    </div>
    <div class="sidebar-data">
   
    </div>
    
</div> 




<?php include "templates/include/footer.php" ?>

