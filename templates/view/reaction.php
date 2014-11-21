<?php include "templates/include/header.php" ?>

<?php $reaction = Reaction::getById($_GET['id']) ?>

<div id="reaction-view" class="col-md-10">            
    <div id="reaction-panel" class="view-title">      
        <h3>
            <?php echo $reaction->transformation; ?>
        </h3>
    </div>

    <div id="reaction-content" class="view-content">
        <div class="main">           
            <div class="view-details col-md-12">
                <div class="metadata">
                    <div class="meta-entry">
                        <h4>Procedure</h4>
                        <?php echo $reaction->procedure ?>
                    </div>
                    <div class="meta-entry">
                        <h4>Reference</h4>
                        <a href="<?php $reference = $reaction->getReference(); echo $reference->getLink(); ?>"><?php echo $reference->refFile?></a>
                    </div>   
                    <div class="meta-entry">
                        <h4>Contributors</h4>                        
                        <?php
                            $contributors = $reaction->getReactionContributors();
                            include "contributorTable.php"
                        ?>
                    </div>
                </div>                
            </div>
        </div>
        <div class="compound">
            <h3>Compound</h3>
            <?php $compound = Compound::getById($reaction->result); ?>
                <a href="view.php?type=compound&id=<?php echo $compound->id ?>"><?php echo $compound->name ?></a>
        </div>
    </div>
</div>

<?php include "templates/include/footer.php" ?>

