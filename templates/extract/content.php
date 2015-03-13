<?php

$tocLinks = array();
$rels = $results['rels'];
$root = $results['text'];

$content = $root->getParaArray();

foreach ($content as $entry) {     
    switch ($entry->getType()) {
        case "text":            
            addPara($entry);
            break;
        case "heading":
            $tocLinks = addHeading($entry, $tocLinks);
            break;
        case "caption":
            addCaption($entry);
            break;
        case "image":            
            addImage($entry, $rels);
            break;
        case "table":
            addTable($entry, $rels);
            break;
    }
}

function addCheckBox($id){
    echo "<input class='selector' type='checkbox' data-id='" . $id . "'>";
}

function addPara($para, $table=false) {
    if (strlen(trim($para->getText())) != 0){
        echo '<div id="item-' . $para->getId() . '" class="para" type="para">';
        if (!$table)
            addCheckBox($para->getId());
        echo '<p class="value">' . $para->getText() . '</p></div>';
    }
}

function addHeading($heading, $tocLinks) {
    echo '<div id="item-' . $heading->getId() . '"class="heading" type="heading">';
    addCheckBox($heading->getId());
    $htmlHeading = $heading->getLevel() + 1;
    $tocLinks[$heading->getId()] = array("text" => $heading->getTitle()->getText(), "level" => $heading->getLevel());
    echo '<h' . $htmlHeading . ' id=' . $heading->getId() . ' class="header value">' . $heading->getTitle()->getText() . '</h' . $htmlHeading . '></div>';
    return $tocLinks;
}

function addImage($image, $rels, $table=false) {
    echo '<div id="item-' . $image->getId() . '"class="image" type="image">'; 
    
    if (!$table)
        addCheckBox($image->getId());
    
    $relId = $image->getContent();    
    $source = $rels[$relId];
    
    echo '<p><img src="' . $source . '"></p></div>';     
}

function addCaption($caption) {
   echo '<div id="item-' . $caption->getId() . '"class="caption" type="caption">'; 
   addCheckBox($caption->getId());
   echo '<p><b>' . $caption->getContent() . '</b></p></div>';     
}

function addTable($table, $rels) {
    echo '<div class="table">';
    echo "<table>";
    $rows = $table->getContent();
    foreach ($rows as $row) {
        echo '<tr>';
        $cells = $row->getContent();
        foreach ($cells as $cell) {
            echo '<td id="item-' . $cell->getId() . '" data-id="' . $cell->getId() . '">';
            $paras = $cell->getContent();
            foreach ($paras as $para) {
                if ($para->getType() == "text"){
                    addPara($para, true);
                }elseif ($para->getType() == "image"){
                    addImage($para, $rels, true); 
                }                             
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table></div>';
}

