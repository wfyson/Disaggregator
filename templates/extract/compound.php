<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">    

    <div id="extract-panel">      
    </div>

    <div id="extract-content">
        <?php include "content.php" ?>              
    </div>
</div>

<!-- Sidebar-->
<!-- here the sidebar describes the compound being generated -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>    
    
</div>  

<script>
    window.onload = function(){
        var compoundBuilder = new CompoundBuilder($('#extract-panel'), $('#sidebar'));              
        compoundBuilder.showStage(0);
        
        //hook up all the checkboxes nad table cells to the compound builder here
        $('#extract-content .selector').change(function(){
           if($(this).is(":checked")){
               compoundBuilder.setChecked($(this).data("id"));
               console.log("checked");
           }else{
               console.log("unchecked");
           } 
        });
        
        $('#extract-content table td').click(function(){
            $this = $(this);
            if($this.hasClass("selected")) {
                $this.removeClass("selected");  
            }else{
                $this.addClass("selected");
                compoundBuilder.setValue($(this).data("id"));
            }                        
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

