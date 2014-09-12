<?php include "templates/include/header.php" ?>

<?php $reaction = Reaction::getById($_GET['id']) ?>

<div id="compound-view" class="col-md-8">            
    <div id="compound-panel" class="view-title">      
        <h3>
            <?php echo $reaction->transformation ?>
        </h3>
    </div>

    <div id="compound-content" class="view-content">
        <div class="main">        
            <div class="view-details">
                <div class="metadata">
                    <div class="meta-title">
                        <b>Reference: </b>
                    </div>
                    <div class="meta-value"> 
                        <a href="<?php $reference = $reaction->getReference(); echo $reference->getLink(); ?>"><?php echo $reference->refFile?></a>
                    </div>
                </div>
                <div class="metadata">
                    <div class="meta-title">
                        <b>Procedure: </b>
                    </div>
                    <div class="meta-value"> 
                        <?php echo $reaction->procedure ?>
                    </div>
                </div>
                <div class="view-desc">
                </div>
            </div>
        </div>
        
        <div id="tags">
                <?php $keywords = $reaction->getTags();             
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
        <h4>Resultant Compound</h4>
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

