<?php

require("../config.php");

/*
 * Merge the slices of a file together
 */

session_start();
$userid = $_SESSION['userid'];

$path = '../' . $_REQUEST['dir'] . '/' . $userid . '/' . str_replace('.', '_', $_REQUEST['name']) . '/'; // . str_replace('.', '_', $_REQUEST['name']) . '_';

if (!isset($_REQUEST['name']))
    throw new Exception('Name required');
if (!preg_match('/^[-a-z0-9_][-a-z0-9_.]*$/i', $_REQUEST['name']))
    throw new Exception('Name error');

if (!isset($_REQUEST['index']))
    throw new Exception('Index required');
if (!preg_match('/^[0-9]+$/', $_REQUEST['index']))
    throw new Exception('Index error');

$target = $path . $_REQUEST['name'];
$dst = fopen($target, 'wb');

for ($i = 0; $i < $_REQUEST['index']; $i++) {
    $slice = $target . '-' . $i;
    $src = fopen($slice, 'rb');
    stream_copy_to_stream($src, $dst);
    fclose($src);
    unlink($slice);
}

fclose($dst);

//if we are uploading a document then we can add this to the database
if ($_REQUEST['dir'] == "uploads"){
    $data = array("ReferenceID" => null, "RefFile" => $_REQUEST['name'], "UploaderID" => $userid, "Source" => "upload");
    $reference = new Reference($data);
    $reference->insert();        
}
?>