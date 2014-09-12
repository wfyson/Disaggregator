<?php

/**
 * Class to handle reactions
 */
class Reaction
{

    //Properties    
    public $id = null;
    public $transformation = null;
    public $result = null;
    public $procedure = null;
    public $referenceID = null;

    public function __construct($data = array())
    {
        if (isset($data['ReactionID']))
            $this->id = (int) $data['ReactionID'];
        if (isset($data['Transformation']))
            $this->transformation = $data['Transformation'];
        if (isset($data['Result']))
            $this->result = (int) $data['Result'];
        if (isset($data['Procedure']))
            $this->procedure = $data['Procedure'];
        if (isset($data['ReferenceID']))
            $this->referenceID = $data['ReferenceID'];
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM reaction WHERE ReactionID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Reaction($row);
    }

    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM reaction LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $reaction = new Reaction($row);
            $list[] = $reaction;
        }

        // Now get the total number of reactions that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the Reaction object already have an ID?
        if (!is_null($this->id))
            trigger_error("Reaction::insert(): Attempt to insert a Reaction object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO reaction ( Transformation, Result, `Procedure`, ReferenceID ) VALUES ( :transformation, :result, :procedure, :referenceID )";
        $st = $conn->prepare($sql);
        
        $st->bindValue(":transformation", $this->transformation, PDO::PARAM_STR);
	$st->bindValue(":result", $this->result, PDO::PARAM_INT);
	$st->bindValue(":procedure", $this->procedure, PDO::PARAM_LOB);  
        $st->bindValue(":referenceID", $this->procedure, PDO::PARAM_INT);  
        $outcome = $st->execute();
        
        $this->id = $conn->lastInsertId();        
        $conn = null;
        return $this->id;                
    }
    
    public function update()
    {
        // Does the Reaction object have an ID?
        if (is_null($this->id))
            trigger_error("Reaction::update(): Attempt to update a Reaction object that does not have its ID property set.", E_USER_ERROR);

        // Update the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE reaction SET Transformation=:transformation , Result=:result , Procedure=:procedure , ReferenceID=:referenceID WHERE ReferenceID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":transformation", $this->transforamtion, PDO::PARAM_STR);
	$st->bindValue(":result", $this->result, PDO::PARAM_INT);
	$st->bindValue(":procedure", $this->procedure, PDO::PARAM_STR);
        $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);        
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Reaction object have an ID?
        if (is_null($this->id))
            trigger_error("Reaction::delete(): Attempt to delete a Reference object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM reaction WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

    public static function getByResult($compoundID)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM reaction WHERE result = :compoundID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $compoundID, PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $reaction = new Reaction($row);
            $list[] = $reaction;
        }        
        return $list;        
    }
    
    public function getTags()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT tag.Keyword FROM tag INNER JOIN reaction_tag ON tag.TagID = reaction_tag.TagID
                WHERE reaction_tag.ReactionID = :reactionID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":reactionID", $this->id, PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {         
            $list[] = $row[Keyword];
        }      
        return $list;
    }
    
    public function getReference()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM reference WHERE ReferenceID = :referenceID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":referenceID", $this->referenceID, PDO::PARAM_INT);
        $st->execute();        
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Reference($row);
    }
    
}

?>
