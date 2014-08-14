<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

$data = array("CompoundID" => null, "Name" => $_POST['Name'], "Description" => $_POST['Description'], "MolFile" => $_POST['MolFile']);
$compound = new Compound($data);
$compoundid = $compound->insert();


//having successfully inserted the compound we now need to make sure it's MolFile is referenceable
$oldPath = '../temp/' . $userid . '/' . str_replace('.', '_', $_POST['MolFile']) . '/' . $_POST['MolFile'];
$newPath = '../compounds/' . $compoundid . '/';

if (!file_exists($newPath)) {
    mkdir($newPath, 0777, true);
}

rename($oldPath, $newPath . $_POST['MolFile']);
//and add a new compound-reference entry to the database too
$compoundReferenceData = array("CompoundReferenceID" => null, "CompoundID" => $compoundid, "ReferenceID" => $_POST['docid']);
$compoundReference = new CompoundReference($compoundReferenceData);
$compoundReferenceid = $compoundReference->insert();

?>