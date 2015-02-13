<?php

    require("config.php");

    session_start();

    //get content source
    $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

    $user = User::getById($userid);
    $contributor = $user->getContributor();
    
    if($contributor->orcid){
        //we can import stuff based on this orcid
        $results['pageTitle'] = "Disaggregator - Import Documents";
        require( TEMPLATE_PATH . "/import/import.php" );
    }else{        
        //can't proceed without an orcid...
    }     
    
    
?>







