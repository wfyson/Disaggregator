<?php

/**
 * Class to handle CompoundTag (used by database to link compounds to tags)
 */
class CompoundTag {

    //Properties    
    public $id = null;
    public $compoundID = null;
    public $tagID = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {
        if (isset($data['CompoundTagID']))
            $this->id = $data['CompoundTagID'];
        if (isset($data['CompoundID']))
            $this->compoundID = $data['CompoundID'];
        if (isset($data['TagID']))
            $this->tagID = $data['TagID'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound_tag WHERE CompoundTagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new CompoundTag($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM compound_tag LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $compoundTag = new CompoundTag($row);
            $list[] = $compoundTag;
        }
       
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the CompoundTag object already have an ID?    
        if (!is_null($this->id))
            trigger_error("CompoundTag::insert(): Attempt to insert a CompoundTag object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Tag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO compound_tag ( CompoundID, TagID ) VALUES ( :compoundID,:tagID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":tagID", $this->tagID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the CompoundTag object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundTag::update(): Attempt to update a CompoundTag object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE compound_tag SET CompoundID=:compoundID TagID=:tagID WHERE CompoundTagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":tagID", $this->tagID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the CompoundTag object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundTag::delete(): Attempt to delete a CompoundTag object that does not have its ID property set.", E_USER_ERROR);

        // Delete the CompoundTag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM compound_tag WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
