<?php

require("config.php");

session_start();

//get content source
$docid = isset($_GET['docid']) ? $_GET['docid'] : "";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//TODO - check the the current use is allowed to read the document


//read the content source
//read the document and output to results array
$reference = Reference::getById($docid);
switch($reference->getFormat()){
    case "docx":        
        //can this whole process be changed to something more static??? (I think it probably can)        
        $reader = new WordReader($reference);
        $results = $reader->readWord();
    break;        
}

//get the artefact we are after
$type = isset($_GET['type']) ? $_GET['type'] : "";
switch($type){
    case "compound":
        $artefact = new Compound();
        $results['pageTitle'] = "Disaggregator - Extract Compound";
        
        //display via a template
        require( TEMPLATE_PATH . "/extract/compound.php" );
     break;    
}












