<div class="list-documents">
    <?php
    if (count($documents) > 0) {
        ?> 
        <p class="lead">
            Found <?php echo count($documents); ?> documents to import that match your ORCID at <?php echo $source; ?>   
        </p>
        <table class="table" style="width: 70%">
            <tbody>
                <?php
                foreach ($documents as $doc) {
                    ?>
                    <tr>
                        <td>
                            <img src="/img/<?php echo $doc->format; ?>_thumb.png"/>
                        </td>
                        <td>
                            <p class="lead">
                                <?php echo $doc->title; ?>
                            </p>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-import" data-name="<?php echo $doc->title; ?>" data-source="<?php echo $doc->source; ?>" data-url="<?php echo $doc->url; ?>">Import <span class="glyphicon glyphicon-import"></span><img class="loader" src="/img/loader.gif"/></button>                            
                        </td>
                    </tr>
                    <?php
                }
                ?> </tbody></table>
        <p class="lead">
            Go back to <a href="/import">Import Overview</a>
        </p> <?php
    } else {
        ?>
        <p class="lead">
            Unfortunately we were unable to find any documents at <?php echo $source; ?> that match your ORCID.          
        </p>
        <p class="lead">
            Go back to <a href="/import">Import Overview</a>
        </p>
        <?php
    }
    ?>    
</div>

<script type="text/javascript">

    $('.btn-import').click(function() {
        var $this = $(this);

        $.post("importFile.php", {
            name: $this.data('name'),
            url: $this.data('url'),
            source: $this.data('source'),
            userid: <?php echo $userid; ?>
        },
        function(data) {
            if (data)
            {
                $this.removeClass("btn-primary").addClass("btn-success disabled").empty();
                $this.append('Imported <span class="glyphicon glyphicon-ok"></span>');
            }
        });

        $this.children(".glyphicon-import").hide();
        $this.children(".loader").show();
    });

</script>

