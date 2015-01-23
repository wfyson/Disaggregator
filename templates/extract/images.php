<?php

$images = $results['images'];
if(count($images) == 0){
    ?><div class="no-results"><h4>No images found!</h4></div><?php
}else{
    foreach ($images as $image) {     
        ?>    
        <div class="view-image selector">            
            <img src="<?php echo $image ?>">
        </div>
        <?php
    }
    if($results['fails'] > 0)
    {
        ?>
        <div class="no-results">
            <h4>Unfortunately <?php echo $results['fails']; ?> images could not be read...</h4>
        </div>
        <?php
    }
}

?>



