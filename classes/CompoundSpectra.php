<?php

/**
 * Class to handle CompoundSpectra (used by database to link compounds to spectra)
 */
class CompoundSpectra {

    //Properties    
    public $id = null;
    public $compoundID = null;
    public $spectraID = null;

    /*
     * Standard database-esque stuff
     */
    
    public function __construct($data = array()) {
        if (isset($data['CompoundSpectraID']))
            $this->id = $data['CompoundSpectraID'];
        if (isset($data['CompoundID']))
            $this->compoundID = $data['CompoundID'];
        if (isset($data['Spectra']))
            $this->spectraID = $data['SpectraID'];
    }

    public function storeFormValues($params) {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound_spectra WHERE CompoundSpectraID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new CompoundSpectra($row);
    }

    public static function getList($numRows = 1000000) {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM compound_spectra LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $compoundSpectra = new CompoundSpectra($row);
            $list[] = $compoundSpectra;
        }

        // Now get the total number of articles that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert() {
        // Does the CompoundSpectra object already have an ID?    
        if (!is_null($this->id))
            trigger_error("CompoundSpectra::insert(): Attempt to insert a CompoundSpectra object that already has its ID property set (to $this->id).", E_USER_ERROR);
        
        // Insert the Spectra
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO compound_spectra ( CompoundID, SpectraID ) VALUES ( :compoundID,: spectraID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":spectraID", $this->spectraID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function update() {
        // Does the CompoundSpectra object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundSpectra::update(): Attempt to update a CompoundSpectra object that does not have its ID property set.", E_USER_ERROR);

        // Update the Article
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE compound_spectra SET CompoundID=:compoundID SpectraID=:spectraID WHERE CompoundSpectraID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":spectraID", $this->spectraID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public function delete() {
        // Does the CompoundSpectra object have an ID?
        if (is_null($this->id))
            trigger_error("CompoundSpectra::delete(): Attempt to delete a CompoundSpectra object that does not have its ID property set.", E_USER_ERROR);

        // Delete the CompoundSpectra
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM compound_spectra WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
