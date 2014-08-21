<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">    

    <div id="extract-panel">      
    </div>

    <div id="extract-content">
        <?php include "content.php" ?>              
    </div>
</div>

<!-- Sidebar-->
<!-- here the sidebar describes the reaction being generated -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>    
    
</div>  

<script>
    window.onload = function(){        
        
        var reactionData = {};
        var reactionStages = [
            {name: "Transformation", type: "text", value: ""},
            //{name: "Result", type: "compound", value: ""}, //this is going to require a load of work!!!
            {name: "Procedure", type: "text", value: ""}];  
        
        reactionData['type'] = "Reaction";
        reactionData['docid'] = <?php echo $_GET['docid'] ?>;
        reactionData['stages'] = reactionStages;
        
        var reactionBuilder = new Builder(reactionData, $('#extract-panel'), $('#sidebar'));              
        reactionBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the reaction builder here
        $('#extract-content .selector').change(function(){
           if($(this).is(":checked")){
               reactionBuilder.setChecked($(this).data("id"));
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
                reactionBuilder.setCell($(this).data("id"));
            }                        
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

