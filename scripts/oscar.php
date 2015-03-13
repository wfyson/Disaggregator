<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];
$docid = $_GET['docid'];

$reference = Reference::getById($docid);

$json = array();
$path = "../" . $reference->getFulltext();

//need to escape the shell or some such thing I strongly suspect
exec('java -cp ../java:../java/* OscarFile "' . $path . '"', $output);

$output = array_count_values($output);

$json['output'] = $output;

echo json_encode($json);

?>