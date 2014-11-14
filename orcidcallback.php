<?php

require("config.php");
session_start();

/*
 * get the user
 */
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";

$user = User::getById($userid);
$contributor = $user->getContributor();

if (isset($_GET['code'])){
    $contributor->orcidCode = $_GET['code'];
    $contributor->update();
    header('Location: ' . 'index.php?authorise=orcid&success=true');
    exit;
}else{
    header('Location: ' . 'index.php?authorise=orcid&success=false');
    exit;
}















