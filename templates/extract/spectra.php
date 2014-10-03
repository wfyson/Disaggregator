<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">            
    <div id="extract-panel">      
    </div>

    <div id="extract-content">
        <?php include "content.php" ?>              
    </div>             
</div>

<!-- Sidebar-->
<!-- here the sidebar describes the spectra being generated -->
<div id='sidebar' class='extract col-md-3 col-md-offset-7'>        
</div>  

<script>
    window.onload = function(){        
                
        var spectraData = {};
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var spectraStages = [
            {name: "Type", type: "text", value: [""], multi: false, record: 0},
            {name: "Comment", type: "text", value: [""], multi: false, record: 0},
            {name: "JCAMPFile", type: "file", value: [""], multi: false, record: 0},
            {name: "Image", type: "image", value: [""], multi: false, record: 0}];   
        
        spectraData['type'] = "Spectra";
        spectraData['title'] = "Spectrum";
        spectraData['docid'] = <?php echo $_GET['docid'] ?>;
        spectraData['compoundid'] = <?php echo $_GET['compoundid'] ?>;
        spectraData['stages'] = spectraStages;
        
        var spectraBuilder = new Builder(spectraData, $('#extract-panel'), $('#sidebar'));              
        spectraBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){
           if($(this).is(":checked")){
               spectraBuilder.setChecked($(this).data("id"));
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
                spectraBuilder.setCell($(this).data("id"));
            }                        
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

