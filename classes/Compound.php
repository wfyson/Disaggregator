<?php

/**
 * Class to handle reactions
 */
class Compound
{

    //Properties    
    public $id = null;
    public $name = null;
    public $description = null;
    public $molFile = null;
    public $referenceID = null;
    public $userID = null;
    
    public function __construct($data = array())
    {
        if (isset($data['CompoundID']))
            $this->id = (int) $data['CompoundID'];
        if (isset($data['Name']))
            $this->name = $data['Name'];
        if (isset($data['Description']))
            $this->description = $data['Description'];
        if (isset($data['MolFile']))
            $this->molFile = $data['MolFile'];
        if (isset($data['ReferenceID']))
            $this->referenceID = $data['ReferenceID']; 
        if (isset($data['UserID']))
            $this->userID = $data['UserID']; 
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound WHERE CompoundID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Compound($row);
    }

    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM compound LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $compound = new Compound($row);
            $list[] = $compound;
        }

        // Now get the total number of compounds that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the Compound object already have an ID?
        if (!is_null($this->id))
            trigger_error("Compound::insert(): Attempt to insert a Compound object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Compound
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO compound ( Name, Description, MolFile, ReferenceID, UserID ) VALUES ( :name , :description , :molfile, :referenceID, :userID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
	$st->bindValue(":description", $this->description, PDO::PARAM_STR);
	$st->bindValue(":molfile", $this->molFile, PDO::PARAM_STR);
        $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
        $st->bindValue(":userID", $this->userID, PDO::PARAM_INT);
        ChromePhp::log($st);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {
        // Does the Compound object have an ID?
        if (is_null($this->id))
            trigger_error("Compound::update(): Attempt to update a Compound object that does not have its ID property set.", E_USER_ERROR);

        // Update the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE compound SET Name=:name , Description=:description , MolFile=:molfile, ReferenceID=:referenceID, UserID=:userID WHERE CompoundID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
		$st->bindValue(":description", $this->description, PDO::PARAM_STR);
		$st->bindValue(":molfile", $this->molFile, PDO::PARAM_STR);
                $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
                $st->bindValue(":userID", $this->userID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Compound object have an ID?
        if (is_null($this->id))
            trigger_error("Compound::delete(): Attempt to delete a Compound object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM compound WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function getMolPath(){
        return "compounds/" . $this->id . '/mol/' . $this->molFile;
    }
    
    public static function getByRef($referenceID)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound WHERE ReferenceID = :referenceID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":referenceID", $referenceID, PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $compound = new Compound($row);
            $list[] = $compound;
        }        
        return ( array("results" => $list) );
    }
    
    public static function getByUser($userID)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound WHERE UserID = :userID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":userID", $userID, PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $compound = new Compound($row);
            $list[] = $compound;
        }        
        return ( array("results" => $list) );
    }
    
}
?>
