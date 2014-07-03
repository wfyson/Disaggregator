<?php

include "debug/ChromePhp.php";



require("config.php");

session_start();
$docid = isset($_GET['docid']) ? $_GET['docid'] : "";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//check the current user is allowed to read the document


$results = array();
$results['pageTitle'] = "Disaggregator - Extract";


//read the document and output to results array




//display via a template
require( TEMPLATE_PATH . "/extract.php" );




