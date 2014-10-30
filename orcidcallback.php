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
    header('Location: ' . 'disaggregator.asdf.ecs.soton.ac.uk?authorise=orcid&success=true');
}else{
    header('Location: ' . '?authorise=orcid&success=false');
}















