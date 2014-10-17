<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

//get the data
$type = $_POST['Type'][0];
$comment = $_POST['Comment'][0];
$jcampFile = $_POST['JCAMPFile'][0];
$image = $_POST['Image'][0];
$compoundID = $_POST['compoundid']; 

$imageFile = substr($image, (strrpos($image, '/') + 1));

//add the spectrum
$data = array("SpectraID" => null, "Type" => $type, "Comment" => $comment, "JCAMPFile" => $jcampFile, "Image" => $imageFile, "CompoundID" => $compoundID);
$spectra = new Spectra($data);
$spectraid = $spectra->insert();

//having successfully inserted the spectrum we now need to write the JCAMP file
$oldPath = '../temp/' . $userid . '/' . str_replace('.', '_', $jcampFile) . '/' . $jcampFile;
$newPath = '../spectra/' . $spectraid . '/jcamp/';

if (!file_exists($newPath)) {
    mkdir($newPath, 0777, true);
}

rename($oldPath, $newPath . $jcampFile);

//and the image file
$oldPath = '.' . $image;
$newPath = '../spectra/' . $spectraid . '/images/';

if (!file_exists($newPath)) {
    mkdir($newPath, 0777, true);
}

$result = copy($oldPath, $newPath . $imageFile);

?>