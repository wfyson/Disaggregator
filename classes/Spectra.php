<?php

/**
 * Class to handle spectra
 */
class Spectra
{

    //Properties    
    public $id = null;
    public $type = null;
    public $comment = null;
    public $jcampFile = null;
    public $image = null;
    public $compoundID = null;
    
    public function __construct($data = array())
    {                
        if (isset($data['SpectraID']))
            $this->id = (int) $data['SpectraID'];
        if (isset($data['Type']))
            $this->type = $data['Type'];
        if (isset($data['Comment']))
            $this->comment = $data['Comment'];
        if (isset($data['JCAMPFile']))
            $this->jcampFile = $data['JCAMPFile'];
        if (isset($data['Image']))
            $this->image = $data['Image']; 
        if (isset($data['CompoundID']))
            $this->compoundID = $data['CompoundID']; 
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM spectra WHERE SpectraID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Spectra($row);
    }

    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM spectra LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $spectra = new Spectra($row);
            $list[] = $spectra;
        }

        // Now get the total number of spectra that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {   
        // Does the Spectra object already have an ID?
        if (!is_null($this->id))
            trigger_error("Spectra::insert(): Attempt to insert a Spectrum object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Spectra
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO spectra ( Type, Comment, JCAMPFile, Image, CompoundID) VALUES ( :type , :comment , :jcampfile, :image, :compoundID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":type", $this->type, PDO::PARAM_STR);
	$st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
	$st->bindValue(":jcampfile", $this->jcampFile, PDO::PARAM_STR);
        $st->bindValue(":image", $this->image, PDO::PARAM_STR);
        $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {
        // Does the Spectra object have an ID?
        if (is_null($this->id))
            trigger_error("Spectra::update(): Attempt to update a Spectra object that does not have its ID property set.", E_USER_ERROR);

        // Update the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE spectra SET Type=:type , Comment=:comment , JCAMPFile=:jcampfile, Image=:image, CompoundID=:compoundID WHERE SpectraID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":type", $this->type, PDO::PARAM_STR);
		$st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
		$st->bindValue(":jcampfile", $this->jcampFile, PDO::PARAM_STR);
                $st->bindValue(":image", $this->image, PDO::PARAM_STR);
                $st->bindValue(":compoundID", $this->compoundID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Spectra object have an ID?
        if (is_null($this->id))
            trigger_error("Spectra::delete(): Attempt to delete a Spectra object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Spectra
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM spectra WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    
}
?>
