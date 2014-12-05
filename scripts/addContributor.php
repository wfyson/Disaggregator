<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

//get the data
$firstname = $_POST['firstname'];
$familyname = $_POST['familyname'];
$orcid = $_POST['orcid'];

if ($orcid == "")
    $orcid = null;

$data = array("FirstName" => $firstname, "FamilyName" => $familyname, "Orcid" => $orcid);
$contributor = new Contributor($data);
$contributorid = $contributor->insert();

echo $contributorid;

?>