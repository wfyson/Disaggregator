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

function addPara($para) {
    if ($para->getText() != "") {
        echo '<div id="item-' . $para->getId() . '" class="para">';
        addCheckBox($para->getId());
        echo '<p class="value">' . $para->getText() . '</p></div>';
    }
}

function addHeading($heading, $tocLinks) {
    echo '<div id="item-' . $heading->getId() . '"class="heading">';
    addCheckBox($heading->getId());
    $htmlHeading = $heading->getLevel() + 1;
    $tocLinks[$heading->getId()] = $heading->getTitle()->getText();
    echo '<h' . $htmlHeading . ' id=' . $heading->getId() . ' class="header value">' . $heading->getTitle()->getText() . '</h' . $htmlHeading . '></div>';
    return $tocLinks;
}

function addImage($image, $rels) {
    echo '<div id="item-' . $image->getId() . '"class="image">'; 
    addCheckBox($image->getId());
    
    $relId = $image->getContent();    
    $source = $rels[$relId];
    
    echo '<p><img src="' . $source . '"></p></div>';     
}

function addCaption($caption) {
   echo '<div id="item-' . $caption->getId() . '"class="caption">'; 
   addCheckBox($caption->getId());
   echo '<p><b>' . $caption->getContent() . '</b></p></div>';     
}

function addTable($table, $rels) {
    echo '<div class="table">';
    $html = "<table>";
    $rows = $table->getContent();
    foreach ($rows as $row) {
        $html = $html . '<tr>';
        $cells = $row->getContent();
        foreach ($cells as $cell) {
            $html = $html . '<td id="item-' . $cell->getId() . '" data-id="' . $cell->getId() . '">';
            $paras = $cell->getContent();
            foreach ($paras as $para) {
                if ($para->getType() == "text"){
                    $html = $html . '<p class="value">' . $para->getText() . '</p>';
                }elseif ($para->getType() == "image"){
                    $relId = $para->getContent();    
                    $source = $rels[$relId];
                    $html = $html . '<img src="' . $source . '">'; 
                }                             
            }
            $html = $html . '</td>';
        }
        $html = $html . '</tr>';
    }
    $html = $html . '</table></div>';
    echo $html;
}

