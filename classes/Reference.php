<?php

/**
 * Class to handle references
 */
class Reference {

    //Properties    
    public $id = null;
    public $refFile = null;
    public $uploaderID = null;

    public function __construct($data = array()) {
        if (isset($data['ReferenceID']))
            $this->id = $data['ReferenceID'];
        if (isset($data['RefFile']))
            $this->refFile = $data['RefFile'];
        if (isset($data['UploaderID']))
            $this->uploaderID = $data['UploaderID'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM reference WHERE ReferenceID = :id";
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
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM reference LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $reference = new Reference($row);
            $list[] = $reference;
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
            trigger_error("Reference::insert(): Attempt to insert a Reference object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Reference
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO reference ( RefFile, UploaderID ) VALUES ( :refFile,:uploaderID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":refFile", $this->refFile, PDO::PARAM_STR);
        $st->bindValue(":uploaderID", $this->uploaderID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the Reference object have an ID?
        if (is_null($this->id))
            trigger_error("Reference::update(): Attempt to update a Reference object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE reference SET RefFile=:refFile UploaderID=:uploaderID WHERE ReferenceID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":refFile", $this->refFile, PDO::PARAM_STR);
        $st->bindValue(":uploaderID", $this->uploaderID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the Reference object have an ID?
        if (is_null($this->id))
            trigger_error("Reference::delete(): Attempt to delete a Reference object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reference
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM reference WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

}

?>
