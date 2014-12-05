<?php

/**
 * Class to handle contributors
 */
class Contributor
{
    //Properties    
    public $id = null;
    public $firstName = null;
    public $familyName = null;
    public $userID = null;
    public $orcid = null;    
    public $orcidCode = null;    
    public $orcidAccessToken = null;   
    

    public function __construct($data = array())
    {
        if (isset($data['ContributorID']))
            $this->id = (int) $data['ContributorID'];
        if (isset($data['FirstName']))
            $this->firstName = $data['FirstName'];
        if (isset($data['FamilyName']))
            $this->familyName = $data['FamilyName'];        
        if (isset($data['UserID']))
            $this->userID = $data['UserID'];
        if (isset($data['Orcid']))
            $this->orcid = $data['Orcid'];
        if (isset($data['OrcidCode']))
            $this->orcidCode = $data['OrcidCode'];
        if (isset($data['OrcidAccessToken']))
            $this->orcidAccessToken = $data['OrcidAccessToken'];
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {                
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM contributor WHERE ContributorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Contributor($row);
    }
    
    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM contributor LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $contributor = new Contributor($row);
            $list[] = $contributor;
        }

        // Now get the total number of contributors that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the Tag object already have an ID?
        if (!is_null($this->id))
            trigger_error("Contributor::insert(): Attempt to insert an Contributor object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Compound
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO contributor (FirstName, FamilyName, UserID, Orcid, OrcidCode, OrcidAccessToken) VALUES ( :firstname , :familyname , :userid , :orcid , :orcidcode , :orcidaccesstoken  )";
        $st = $conn->prepare($sql);
                
        $st->bindValue(":firstname", $this->firstName, PDO::PARAM_STR);
        $st->bindValue(":familyname", $this->familyName, PDO::PARAM_STR);        
        $st->bindValue(":userid", $this->userID, PDO::PARAM_INT);
        $st->bindValue(":orcid", $this->orcid, PDO::PARAM_STR);
        $st->bindValue(":orcidcode", $this->orcidCode, PDO::PARAM_STR);
        $st->bindValue(":orcidaccesstoken", $this->orcidAcessToken, PDO::PARAM_STR);                
        $st->execute();
        
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {
        // Does the Contributor object have an ID?
        if (is_null($this->id))
            trigger_error("Contributor::update(): Attempt to update a Contributor object that does not have its ID property set.", E_USER_ERROR);

        // Update the Contributor
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE contributor SET FirstName=:firstname, FamilyName=:familyname, UserID=:userID, Orcid=:orcid, OrcidCode=:orcidcode, OrcidAccessToken=:orcidaccesstoken WHERE ContributorID = :id";
        $st = $conn->prepare($sql);
        
        
        $st->bindValue(":firstname", $this->firstName, PDO::PARAM_STR);
        $st->bindValue(":familyname", $this->familyName, PDO::PARAM_STR);       
        $st->bindValue(":userID", $this->userID, PDO::PARAM_INT);
        $st->bindValue(":orcid", $this->orcid, PDO::PARAM_STR);
        $st->bindValue(":orcidcode", $this->orcidCode, PDO::PARAM_STR);
        $st->bindValue(":orcidaccesstoken", $this->orcidAccessToken, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Tag object have an ID?
        if (is_null($this->id))
            trigger_error("Contributor::delete(): Attempt to delete a Contributor object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM contributor WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function getName(){
        return $this->firstName . " " . $this->familyName;
    }
    
    public function getOrcidName(){
        return $this->familyName . ", " . $this->firstName;
    }
    
    public function getOrcidLink(){
        return "http://sandbox.orcid.org/" . $this->orcid;
    }
}

?>
