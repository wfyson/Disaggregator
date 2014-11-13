<?php

/**
 * Class to handle CompoundContributors
 */
class CompoundContributor {

    //Properties    
    public $id = null;
    public $compoundID = null;
    public $contributorID = null;
    public $role = null;
    public $comment = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {
        if (isset($data['CompoundContributorID']))
            $this->id = $data['CompoundContributorID'];
        if (isset($data['CompoundID']))
            $this->compoundID = $data['CompoundID'];
        if (isset($data['ContributorID']))
            $this->contributorID = $data['ContributorID'];
        if (isset($data['Role']))
            $this->role = $data['Role'];
        if (isset($data['Comment']))
            $this->comment = $data['Comment'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound_contributor WHERE CompoundContributorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new CompoundContributor($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM compound_contributor LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $compoundContributor = new CompoundContributor($row);
            $list[] = $compoundContributor;
        }
       
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the CompoundTag object already have an ID?    
        if (!is_null($this->id))
            trigger_error("CompoundContributor::insert(): Attempt to insert a CompoundContributor object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Tag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO compound_contributor ( CompoundID, ContributorID, Role, Comment ) VALUES ( :compoundID, :contributorID, :role, :comment )";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":contributorID", $this->contributorID, PDO::PARAM_INT);
        $st->bindValue(":role", $this->role, PDO::PARAM_STR);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the CompoundTag object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundContributor::update(): Attempt to update a CompoundContributor object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE compound_contributor SET CompoundID=:compoundID, ContributorID=:contributorID, Role=:role, Comment=:comment WHERE CompoundContributorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":contributorID", $this->contributorID, PDO::PARAM_INT);
        $st->bindValue(":role", $this->role, PDO::PARAM_STR);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the CompoundTag object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundContributor::delete(): Attempt to delete CompoundContributor CompoundTag object that does not have its ID property set.", E_USER_ERROR);

        // Delete the CompoundTag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM compound_contributor WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
