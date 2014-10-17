<?php

/**
 * Class to handle ReactionTag (used by database to link reactions to tags)
 */
class ReactionTag {

    //Properties    
    public $id = null;
    public $reactionID = null;
    public $tagID = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {        
        if (isset($data['ReactionTagID']))
            $this->id = $data['ReactionTagID'];
        if (isset($data['ReactionID']))
            $this->reactionID = $data['ReactionID'];
        if (isset($data['TagID']))
            $this->tagID = $data['TagID'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM reaction_tag WHERE ReactionTagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new ReactionTag($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM reaction_tag LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $reactionTag = new ReactionTag($row);
            $list[] = $reactionTag;
        }
       
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the ReactionTag object already have an ID?                            
        if (!is_null($this->id))
            trigger_error("ReactionTag::insert(): Attempt to insert a ReactionTag object that already has its ID property set (to $this->id).", E_USER_ERROR);                
        
        // Insert the Tag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO reaction_tag ( ReactionID, TagID ) VALUES ( :reactionID,:tagID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":reactionID", $this->reactionID, PDO::PARAM_INT);
        $st->bindValue(":tagID", $this->tagID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the ReactionTag object have an ID?
        if (is_null($this->id))
            trigger_error("ReactionTag::update(): Attempt to update a ReactionTag object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE reaction_tag SET ReactionID=:reactionID TagID=:tagID WHERE ReactionTagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":reactionID", $this->reactionID, PDO::PARAM_INT);
        $st->bindValue(":tagID", $this->tagID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the ReactionTag object have an ID?
        if (is_null($this->id))
            trigger_error("ReactionTag::delete(): Attempt to delete a ReactionTag object that does not have its ID property set.", E_USER_ERROR);

        // Delete the ReactionTag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM reaction_tag WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
