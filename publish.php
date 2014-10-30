<?php

require("config.php");

session_start();

//get content source
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//get the artefact we are after
$type = isset($_GET['type']) ? $_GET['type'] : "";

$artefact = Compound::getById($_GET['id']);

//get the platform we wish to publish to
$platform = isset($_GET['platform']) ? $_GET['platform'] : "";

switch($platform){
    case "orcid":
        $results['pageTitle'] = "Disaggregator - Publish to ORCID";
        
        //display via a template
        require( TEMPLATE_PATH . "/publish/orcid/orcid.php" );
        break;    
}













