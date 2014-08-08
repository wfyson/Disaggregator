<?php

$tocLinks = array();
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
            addImage($entry);
            break;
        case "table":
            addTable($entry);
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

function addImage($image) {
    
}

function addCaption($caption) {
    
}

function addTable($table) {
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
                $html = $html . '<p class="value">' . $para->getText() . '</p>';
            }
            $html = $html . '</td>';
        }
        $html = $html . '</tr>';
    }
    $html = $html . '</table></div>';
    echo $html;
}

