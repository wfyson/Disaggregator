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

//get related contributors
$user = User::getById($userid);
$relatedContributors = $user->getRelatedContributors();

//get all contributors
$otherContributors = Contributor::getList();
$displayOtherContributors = array();
//remove related contributors from this list
foreach($otherContributors[results] as $contributor){
    if ((!(in_array($contributor, $relatedContributors))) && ($contributor->userID != $userid)){        
        $displayOtherContributors[] = $contributor;
        continue;
    }
}

$json['relatedContributors'] = $relatedContributors;
$json['otherContributors'] = $displayOtherContributors;

//return the results
echo json_encode($json);

?>