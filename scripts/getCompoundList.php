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
$refCompounds = Compound::getByRef($docid);

//get user compounds
$userCompounds = Compound::getByUser($userid);
//and filter out ones already showing
$displayUserCompounds = array();
foreach($userCompounds[results] as $userCompound){
    if (!(in_array($userCompound, $refCompounds[results]))){
        
        $displayUserCompounds[] = $userCompound;
    }   
}

//others
$otherCompounds = Compound::getList();
//and filter out ones already showing
$displayOtherCompounds = array();
foreach($otherCompounds[results] as $otherCompound){
    if ((!(in_array($otherCompound, $refCompounds[results]))) && (!(in_array($otherCompound, $displayUserCompounds)))){
        $displayOtherCompounds[] = $otherCompound;
    }   
}

//reutrn the results
$json['refCompounds'] = $refCompounds[results];
$json['userCompounds'] = $displayUserCompounds;
$json['otherCompounds'] = $displayOtherCompounds;

echo json_encode($json);

?>