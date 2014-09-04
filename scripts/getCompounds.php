<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];
$docid = $_GET['docid'];

$json = array();
$json['current'] = false;
if(isset($_GET['compoundid'])){
    $current = Compound::getById($_GET['compoundid']);
    $json['current'] = $current->name;
}

//ref compounds
$json['refCompounds'] = Compound::getByRef($docid);

echo json_encode($json);

?>