<?php

require("config.php");

session_start();

//get content source
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//get the artefact we are after
$type = isset($_GET['type']) ? $_GET['type'] : "";
switch($type){
    case "compound":
        $results['pageTitle'] = "Disaggregator - View Compound";
       
        //display via a template
        require( TEMPLATE_PATH . "/view/compound.php" );
        break;    
    case "reaction":
        $results['pageTitle'] = "Disaggregator - View Reaction";
        
        //display via a template
        require( TEMPLATE_PATH . "/view/reaction.php" );
        break;
    case "user":
        $results['pageTitle'] = "Disaggregator - View Profile";
        
        //display via a template
        require( TEMPLATE_PATH . "/view/user.php" );
     break;   
}

















