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

function addPara($para) {
    if ($para->getText() != "") {
        echo '<div><p>' . $para->getText() . '</p></div>';
    }
}

function addHeading($heading, $tocLinks) {
    $htmlHeading = $heading->getLevel() + 1;
    $tocLinks[$heading->getId()] = $heading->getTitle()->getText();
    echo '<div><h' . $htmlHeading . ' id=' . $heading->getId() . '>' . $heading->getTitle()->getText() . '</h' . $htmlHeading . '></div>';
    return $tocLinks;
}

function addImage($image) {
    
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