<?php

/**
 * Class to handle ReactionContributors
 */
class ReactionContributor {

    //Properties    
    public $id = null;
    public $reactionID = null;
    public $contributorID = null;
    public $role = null;
    public $comment = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {
        if (isset($data['ReactionContributorID']))
            $this->id = $data['ReactionContributorID'];
        if (isset($data['ReactionID']))
            $this->reactionID = $data['ReactionID'];
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
        $sql = "SELECT * FROM reaction_contributor WHERE ReactionContributorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new ReactionContributor($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM reaction_contributor LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $reactionContributor = new ReactionContributor($row);
            $list[] = $reactionContributor;
        }
       
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the ReactionTag object already have an ID?    
        if (!is_null($this->id))
            trigger_error("ReactionContributor::insert(): Attempt to insert a ReactionContributor object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Tag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO reaction_contributor ( ReactionID, ContributorID, Role, Comment ) VALUES ( :reactionID, :contributorID, :role, :comment )";
        $st = $conn->prepare($sql);
        $st->bindValue(":reactionID", $this->reactionID, PDO::PARAM_INT);
        $st->bindValue(":contributorID", $this->contributorID, PDO::PARAM_INT);
        $st->bindValue(":role", $this->role, PDO::PARAM_STR);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the ReactionTag object have an ID?
        if (is_null($this->id))
            trigger_error("ReactionContributor::update(): Attempt to update a ReactionContributor object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE reaction_contributor SET ReactionID=:reactionID, ContributorID=:contributorID, Role=:role, Comment=:comment WHERE ReactionContributorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":reactionID", $this->reactionID, PDO::PARAM_INT);
        $st->bindValue(":contributorID", $this->contributorID, PDO::PARAM_INT);
        $st->bindValue(":role", $this->role, PDO::PARAM_STR);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the ReactionTag object have an ID?
        if (is_null($this->id))
            trigger_error("ReactionContributor::delete(): Attempt to delete ReactionContributor ReactionTag object that does not have its ID property set.", E_USER_ERROR);

        // Delete the ReactionTag
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM reaction_contributor WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function getContributor()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM contributor WHERE ContributorID = :contributorID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":contributorID", $this->contributorID, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Contributor($row);
    }
    
    public function getOrcidRole(){
        switch ($this->role){
            case "Doer":
                return "co-investigator";
            case "Conceiver":
                return "co-inventor";
            case "Supervisor":
                return "principal-investigator";            
        }
    }
}

?>
