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
        
        var compoundData = {};
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var compoundStages = [
            {name: "Name", type: "text", value: [], multi: false, record: 0},
            {name: "Description", type: "text", value: [], multi: false, record: 0},
            {name: "MolFile", type: "file", value: [], multi: false, record: 0},
            {name: "Spectra", type: "image", value: [], multi: true, record: 0}];   
        
        compoundData['type'] = "Compound";
        compoundData['docid'] = <?php echo $_GET['docid'] ?>;
        compoundData['stages'] = compoundStages;
        
        var compoundBuilder = new Builder(compoundData, $('#extract-panel'), $('#sidebar'));              
        compoundBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
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
                compoundBuilder.setCell($(this).data("id"));
            }                        
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

