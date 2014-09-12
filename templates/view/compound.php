<?php include "templates/include/header.php" ?>

<?php $compound = Compound::getById($_GET['id']) ?>

<script type="text/javascript">

var Info = {
	width: 300,
	height: 300,
	debug: false,
	color: "0xeeeeee",
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
        
            <div class="view-details">
                <div class="metadata">
                    <div class="meta-title">
                        <b>Reference: </b>
                    </div>
                    <div class="meta-value">
                        <a href="<?php $reference = $compound->getReference(); echo $reference->getLink(); ?>"><?php echo $reference->refFile?></a>
                    </div>
                </div>
                <div class="view-desc">
                    <?php echo $compound->description ?>
                </div>
            </div>
        </div>
        
        <div id="tags">
                <?php $keywords = $compound->getTags();             
                foreach ($keywords as $keyword){
                    ?>
                    <div class="keyword">
                        <div class="label label-default"><?php echo $keyword ?></div>
                    </div>                    
                <?php } ?>
        </div>
    </div>
</div>

<!-- Sidebar-->
<!-- here the sidebar describes stuff related to the compound (doc, reactions, blah) -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>     
    
    <div class="sidebar-title">
        <h4>Related Reactions</h4>
    </div>
    <?php $reactions = Reaction::getByResult($compound->id);          
        foreach ($reactions as $reaction){
        ?>
        <div class="sidebar-data">
            <a href="view.php?type=reaction&id=<?php echo $reaction->id ?>"><?php echo $reaction->transformation ?></a>
        </div>                    
    <?php } ?>
</div> 




<?php include "templates/include/footer.php" ?>

