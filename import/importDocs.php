<?php 

    session_start();
    include "../templates/include/header.php"; 
    require("../config.php");
    
    $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
    $user = User::getById($userid);
    $contributor = $user->getContributor();

    $type = $_POST['import'];
    switch($type){
        case "eprints":
            $source = $_POST['source'];
            $documents = EPrintsImporter::getDocuments($source, $contributor->orcid);
            break;                    
    };
    include 'listDocs.php';
    
?>


