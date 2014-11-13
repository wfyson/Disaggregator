<?php include "pageSetup.php" ?>
<?php include "completeModal.php" ?>

<script>
    window.onload = function(){        
                
        var compoundData = {};
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var compoundStages = [
            {name: "Name", type: "text", value: [""], multi: false, record: 0},
            {name: "Description", type: "text", value: [""], multi: false, record: 0},
            {name: "MolFile", type: "file", value: [""], multi: false, record: 0},
            {name: "Contributors", type: "contributor", value: [""], multi: true, record: 0},
            {name: "Tags", type: "tags", value: [""], multi: false, record: 0}];   
        
        compoundData['type'] = "Compound";
        compoundData['title'] = "Compound";
        compoundData['docid'] = <?php echo $_GET['docid'] ?>;
        compoundData['stages'] = compoundStages;
        
        var compoundBuilder = new Builder(compoundData, $('#extract-panel'), $('#progress'));              
        compoundBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){              
               compoundBuilder.setChecked($(this));
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

