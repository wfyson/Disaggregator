<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">            
    <div id="extract-panel">      
    </div>
    <ul class="nav nav-tabs" id="view-tabs">
        <li class="active"><a id='content-tab' href="#content" data-toggle="tab">Document</a></li>
        <li><a href="#images" data-toggle="tab">Images</a></li>
    </ul>
    <div id="extract-content" class='tab-content'>
        <div class="tab-pane active" id="content">
            <?php include "content.php" ?>              
        </div>
        <div class="tab-pane" id="images" type="image">
            <?php include "images.php" ?>
        </div>
    </div>             
</div>
<!-- Sidebar-->
<div id="sidebar" class ="extract col-md-3 col-md-offset-7">
    <ul class="nav nav-tabs" id="sidebar-tabs">
        <li class="active"><a id='progress-tab' href="#progress" data-toggle="tab">Progress</a></li>
        <li><a href="#navigation" data-toggle="tab">Navigation</a></li>
    </ul>

    <div class='tab-content'>
        <div class="tab-pane active" id="progress"></div>
        <div class="tab-pane" id="navigation">
            <h3>Table of Contents</h3>
            <?php 
                foreach ($tocLinks as $link => $heading){
                    ?>
            <a href="#<?php echo $link; ?>" class="heading-<?php echo $heading["level"]; ?>"><?php echo $heading["text"]; ?></a> 
                <?php } ?>           
        </div>
    </div>  
</div>