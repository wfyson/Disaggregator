<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

//get the data
$transformation = $_POST['Transformation'][0];
$procedure = $_POST['Procedure'][0];

//for one-to-one relationships get the first value and create a new reaction
$data = array("ReactionID" => null, "Transformation" => $transformation, "Procedure" => $procedure);
$reaction = new Reaction($data);
$reactionid = $reaction->insert();

//and the reaction tags
$tags = explode(", ", $_POST['Tags'][0]);      
foreach($tags as $tag){
    //see if the tag already exists and get its id/insert it if not existing
    $tagEntry = Tag::getByKeyword($tag);
    if ($tagEntry){
        $tagid = $tagEntry->id;
    }else{
        $tagEntry = new Tag(array("TagID" => null, "Keyword" => $tag));
        $tagid = $tagEntry->insert();
    }
    
    //make connection between the reaction and the tag
    $reactionTagData = array("ReactionTagID" => null, "ReactionID" => $reactionid, "TagID" => $tagid);
    $reactionTag = new ReactionTag($reactionTagData);
    $reactionTagid = $reactionTag->insert();
}


?>