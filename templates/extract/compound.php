<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">    

    <div id="extract-panel">
        <h2 id="stage-name"></h2>        
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
        
        //show this be sent the places where it can display stuff?? quite possible
        //i.e. pass it the panel and the sidebar
        var compoundBuilder = new CompoundBuilder();              
        
        $('#stage-name').append("1) Compound Name");
    }


</script>

<?php include "templates/include/footer.php" ?>

?>  