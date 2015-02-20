<?php

    require("../config.php");

    //generate destination
    $path = '../uploads/' . $_POST['userid'] . '/' . str_replace('.', '_', $_POST['name']) . '/'; //. $_POST['name'];
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    
    //copy the file
    $dest = $path . $_POST['name'];    
    if(copy($_POST['url'], $dest)){
        //generate record
        $data = array("ReferenceID" => null, "RefFile" => $_POST['name'], "UploaderID" => $_POST['userid'], "Source" => $_POST['source']);
        $reference = new Reference($data);
        $reference->insert();        
        echo "success";
    }

?>

