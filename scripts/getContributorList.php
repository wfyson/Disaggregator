<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

$json = array();
$json['current'] = false;
if(isset($_GET['contributorid'])){
    $current = Contributor::getById($_GET['contributorid']);
    $json['current'] = $current->getName();
}

$contributors = Contributor::getList();

//remove user form list
$otherContributors = array();
foreach($contributors['results'] as $contributor)
{
    if ($contributor->userID != $userid)
    {    
        $otherContributors[] = $contributor;
    }
}

//return the results
$json['contributors'] = $otherContributors;
echo json_encode($json);

?>