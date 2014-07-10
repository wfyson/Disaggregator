<?php

require("config.php");

session_start();
$docid = isset($_GET['docid']) ? $_GET['docid'] : "";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//TODO - check the current user is allowed to read the document


//read the document and output to results array
$reference = Reference::getById($docid);

switch($reference->getFormat()){
    case "docx":        
        //can this whole process be changed to something more static??? (I think it probably can)        
        $reader = new WordReader($reference);
        $results = $reader->readWord();
    break;        
}

$results['pageTitle'] = "Disaggregator - Extract";

//display via a template
require( TEMPLATE_PATH . "/extract.php" );




