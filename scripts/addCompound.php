<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

//get the data
$name = $_POST['Name'][0];
$desc = $_POST['Description'][0];
$molFile = $_POST['MolFile'][0];

//for one-to-one relationships get the first value and create a new compound
$data = array("CompoundID" => null, "Name" => $name, "Description" => $desc, "MolFile" => $molFile);
$compound = new Compound($data);
$compoundid = $compound->insert();

//having successfully inserted the compound we now need to make sure it's MolFile is referenceable
$oldPath = '../temp/' . $userid . '/' . str_replace('.', '_', $molFile) . '/' . $molFile;
$newPath = '../compounds/' . $compoundid . '/mol/';

if (!file_exists($newPath)) {
    mkdir($newPath, 0777, true);
}

rename($oldPath, $newPath . $molFile);

//and add a new compound-reference entry to the database too
$compoundReferenceData = array("CompoundReferenceID" => null, "CompoundID" => $compoundid, "ReferenceID" => $_POST['docid']);
$compoundReference = new CompoundReference($compoundReferenceData);
$compoundReferenceid = $compoundReference->insert();

//and now add the tags
$tags = explode(", ", $_POST['Tags'][0]);      
foreach($tags as $tag){
    //see if the tag already exists and get its id/insert it if not existing
    $tagEntry = Tag::getByKeyword($tag);
    if ($tagEntry){
        $tagid = $tagEntry->id;
    }else{
        $tagEntry = new Tag(array("TagID" => null, "Keyword" => $tag));
        $tagid = $tagEntry->insert();
    }
    
    //make connection between the compound and the tag
    $compoundTagData = array("CompoundReferenceID" => null, "CompoundID" => $compoundid, "TagID" => $tagid);
    $compoundTag = new CompoundTag($compoundTagData);
    $compoundTagid = $compoundTag->insert();
}


?>