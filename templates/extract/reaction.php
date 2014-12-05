<?php include "pageSetup.php" ?>
<?php include "completeModal.php" ?>
<?php include "contributorModal.php" ?>

<script>
    window.onload = function(){        
                
        var reactionData = {};
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var reactionStages = [
            {name: "Transformation", type: "text", value: [""], multi: false, record: 0, optional: false},
            {name: "Procedure", type: "text", value: [""], multi: false, record: 0, optional: false},
            {name: "Result", type: "compound", value: [""], multi: false, record: 0, optional: false},
            {name: "Contributors", type: "contributor", value: [""], multi: true, record: 0, optional: true},
            {name: "Tags", type: "tags", value: [""], multi: false, record: 0, optional: true}];   
        
        reactionData['type'] = "Reaction";
        reactionData['title'] = "Reaction";
        reactionData['docid'] = <?php echo $_GET['docid'] ?>;
        reactionData['stages'] = reactionStages;
        
        var builder = new Builder(reactionData, $('#extract-panel'), $('#progress'));              
        builder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){              
            builder.setChecked($(this));
        });
        
         $('#extract-content table td .para').click(function(){            
            builder.setCell($(this));
        });
        
        $('#extract-content table td .image').click(function(){            
            builder.setCell($(this));
        });
        
        //contributor functionality
        $('#new-contributor').click(function(){                        
            newContributor(builder);
        }); 
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

