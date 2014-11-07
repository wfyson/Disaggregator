<?php

require("config.php");
session_start();

/*
 * get the user
 */
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";

$user = User::getById($userid);

if (isset($_GET['code'])){
    $user->orcidCode = $_GET['code'];
    $user->update();
    header('Location: ' . 'index.php?authorise=orcid&success=true');
    exit;
}else{
    header('Location: ' . 'index.php?authorise=orcid&success=false');
    exit;
}















