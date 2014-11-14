<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

$json = array();
$json['current'] = false;
if(isset($_GET['contributorid'])){
    $current = Contributor::getById($_GET['contributorid']);
    $json['current'] = $current->name;
}

$contributors = Contributor::getList();

//reutrn the results
$json['contributors'] = $contributors;
echo json_encode($json);

?>