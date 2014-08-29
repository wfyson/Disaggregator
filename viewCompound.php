<?php

require("config.php");

session_start();

//get content source
$compoundid = isset($_GET['compoundid']) ? $_GET['id'] : "";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//TODO - check compound id is valid and display something appropriate














