<?php include "pageSetup.php" ?>

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
        
        <?php include "controls.php" ?>
    };
</script>

<?php include "templates/include/footer.php" ?>

