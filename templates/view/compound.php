<?php include "templates/include/header.php" ?>

<?php $compound = Compound::getById($_GET['id']) ?>

<script type="text/javascript">

jmol_isReady = function(applet) {
	document.title = (applet._id + " - Jmol " + ___JmolVersion)
	Jmol._getElement(applet, "appletdiv").style.border="1px solid #e1e1e8"   
}

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
        allowJavaScript: true,
        readyFunction: jmol_isReady
}

$(document).ready(function() {
  $("#appdiv").html(Jmol.getAppletHtml("jmolApplet0", Info))
})
</script>

<div id="compound-view" class="col-md-10">            
    <div id="compound-panel" class="view-title">      
        <h3>
            <?php echo $compound->name ?>
        </h3>
    </div>

    <div id="compound-content" class="view-content">
        <div class="main">        
            <div id="appdiv" class="col-md-4"></div>        
            <div class="view-details col-md-8">
                <div class="metadata">
                    <div class="meta-entry">
                        <h4>Description</h4>
                        <?php echo $compound->description ?>
                    </div>
                    <div class="meta-entry">
                        <h4>Reference</h4>
                        <a href="<?php $reference = $compound->getReference(); echo $reference->getLink(); ?>"><?php echo $reference->refFile?></a>
                    </div>   
                    <div class="meta-entry">
                        <h4>Contributors</h4>                        
                        <?php
                            $contributors = $compound->getCompoundContributors();
                            include "contributorTable.php"
                        ?>
                    </div>
                </div>                
            </div>
        </div>
        <div class="spectra">
            <h3>Spectra</h3>
            <a class="add-spectrum btn btn-success btn-xs" href="extract.php?type=spectra&docid=<?php echo $compound->referenceID ?>&compoundid=<?php echo $compound->id ?>"><span class="glyphicon glyphicon-plus"></span> Add Spectrum</a> 
            <?php $spectra = $compound->getSpectra();
            foreach($spectra as $spectrum){
                ?>
            <div class="compound-spectrum">
                
            </div>
            <?php } ?>            
        </div>
        <div class="reactions">
            <h3>Reactions</h3>
            <?php $reactions = Reaction::getByResult($compound->id);  
            foreach($reactions as $reaction){
                ?>
            <div class="compound-reaction">
                <a href="view.php?type=reaction&id=<?php echo $reaction->id ?>"><?php echo $reaction->transformation ?></a>
            </div>
            <?php } ?> 
        </div>
        <!--
        <div id="tags">
                <?php $keywords = $compound->getTags();             
                foreach ($keywords as $keyword){
                    ?>
                    <div class="keyword">
                        <div class="label label-default"><?php echo $keyword ?></div>
                    </div>                    
                <?php } ?>
        </div>-->
    </div>
</div>

<?php include "templates/include/footer.php" ?>

