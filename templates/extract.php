<?php include "templates/include/header.php" ?>

<div id="extract-view" class="col-md-8">    

    <div id="extract-panel">
        <button class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New Compound</button>
        <button class="btn btn-warning"><span class="glyphicon glyphicon-plus"></span> New Reaction</button>
    </div>

    <div id="extract-content">

        <?php
        $root = $results['text'];
        $content = $root->getParaArray();
        foreach ($content as $entry) {
            switch ($entry->getType()) {
                case "text":
                    addPara($entry);
                    break;
                case "heading":
                    addHeading($entry);
                    break;
                case "caption":
                    addCaption($entry);
                    break;
                case "image":
                    addImage($entry);
                    break;
                case "table":
                    addTable($entry);
                    break;
            }
        }
        ?>

        </ul>    
    </div>
</div>

<!-- Sidebar -->
<div id='sidebar' class='col-md-3 col-md-offset-7'>

</div>  

<?php include "templates/include/footer.php" ?>

<?php

function addPara($para) {
    if ($para->getText() != "") {
        echo '<div><p>' . $para->getText() . '</p></div>';
    }
}

function addHeading($heading) {
    $htmlHeading = $heading->getLevel() + 1;
    echo '<div><h' . $htmlHeading . '>' . $heading->getTitle()->getText() . '</h' . $htmlHeading . '></div>';
}

function addCaption($caption) {
    
}

function addTable($table) {


    $html = "<div><table>";

    $rows = $table->getContent();
    foreach ($rows as $row) {
        $html = $html . '<tr>';

        $cells = $row->getContent();

        foreach ($cells as $cell) {
            $html = $html . '<td>';

            $paras = $cell->getContent();
            foreach ($paras as $para) {
                $html = $html . '<p>' . $para->getText() . '</p>';
            }
            $html = $html . '</td>';
        }
        $html = $html . '</tr>';
    }
    $html = $html . '</table></div>';
    echo $html;
}
?>  