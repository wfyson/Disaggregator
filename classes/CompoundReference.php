<?php

/**
 * Class to handle CompoundReferences (used by database to link compounds to references)
 */
class CompoundReference {

    //Properties    
    public $id = null;
    public $compoundID = null;
    public $referenceID = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {
        if (isset($data['CompoundReferenceID']))
            $this->id = $data['CompoundReferenceID'];
        if (isset($data['CompoundID']))
            $this->compoundID = $data['CompoundID'];
        if (isset($data['ReferenceID']))
            $this->referenceID = $data['ReferenceID'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound_reference WHERE CompoundReferenceID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Reference($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM compound_reference LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $compoundReference = new CompoundReference($row);
            $list[] = $compoundReference;
        }

        // Now get the total number of articles that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the Reference object already have an ID?    
        if (!is_null($this->id))
            trigger_error("CompoundReference::insert(): Attempt to insert a CompoundReference object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Reference
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO compound_reference ( CompoundID, ReferenceID ) VALUES ( :compoundID,:referenceID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the Reference object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundReference::update(): Attempt to update a CompoundReference object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE compound_reference SET CompoundID=:compoundID ReferenceID=:referenceID WHERE CompoundReferenceID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the Reference object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundReference::delete(): Attempt to delete a CompoundReference object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reference
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM compound_reference WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
