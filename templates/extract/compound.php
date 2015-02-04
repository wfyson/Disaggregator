<?php include "pageSetup.php" ?>

<script>
    window.onload = function(){        
                
        var compoundData = {};
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var compoundStages = [
            {name: "Name", type: "text", value: [""], multi: false, record: 0, optional: false},
            {name: "Description", type: "text", value: [""], multi: false, record: 0, optional: false},
            {name: "MolFile", type: "file", value: [""], multi: false, record: 0, optional: false},
            {name: "Contributors", type: "contributor", value: [""], multi: true, record: 0, optional: true},
            {name: "Tags", type: "tags", value: [""], multi: false, record: 0, optional: true}];   
        
        compoundData['type'] = "Compound";
        compoundData['title'] = "Compound";
        compoundData['docid'] = <?php echo $_GET['docid'] ?>;
        compoundData['stages'] = compoundStages;
        
        var builder = new Builder(compoundData, $('#extract-panel'), $('#progress'));              
        builder.showStage(0);
        
        <?php include "controls.php" ?>
    };
</script>

<?php include "templates/include/footer.php" ?>

