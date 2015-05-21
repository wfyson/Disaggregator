<?php

require("../config.php");

session_start();
$userid = $_SESSION['userid'];

//get the data
$transformation = $_POST['Transformation'][0];
$procedure = $_POST['Procedure'][0];
$result = $_POST['Result'][0];
$referenceID = $_POST['docid'];

$compoundid = json_decode($result)->id;

//for one-to-one relationships get the first value and create a new reaction
$data = array("ReactionID" => null, "Transformation" => $transformation, "Result" => $compoundid, "Procedure" => $procedure, "ReferenceID" => $referenceID, "UserID" => $userid);
$reaction = new Reaction($data);
$reactionid = $reaction->insert();

//add the contributors
if ($contributors != "") {
    $contributors = $_POST['Contributors'];
    foreach ($contributors as $contributor) {
        $contributorInfo = json_decode($contributor);
        $contributorEntry = new ReactionContributor(array("ReactionContributorID" => null,
            "ReactionID" => $reactionid,
            "ContributorID" => $contributorInfo->id,
            "Role" => $contributorInfo->role
        ));
        $contributorEntry->insert();
    }
}

//and the reaction tags
if ($tags != "") {
    $tags = explode(", ", $_POST['Tags'][0]);
    foreach ($tags as $tag) {

        //see if the tag already exists and get its id/insert it if not existing
        $tagEntry = Tag::getByKeyword($tag);
        if ($tagEntry) {
            $tagid = $tagEntry->id;
        } else {
            $tagEntry = new Tag(array("TagID" => null, "Keyword" => $tag));
            $tagid = $tagEntry->insert();
        }

        //make connection between the reaction and the tag
        $reactionTagData = array("ReactionTagID" => null, "ReactionID" => $reactionid, "TagID" => $tagid);
        $reactionTag = new ReactionTag($reactionTagData);
        $reactionTagid = $reactionTag->insert();
    }
}
?>
