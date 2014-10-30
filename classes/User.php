<?php

/**
 * Class to handle users
 */
class User
{

    //Properties    
    public $id = null;
    public $userName = null;
    public $userPasswordHash = null;
    public $userEmail = null;
    public $orcid = null;    
    public $orcidCode = null;    
    public $orcidAccessToken = null;    
    
    public function __construct($data = array())
    {                    
        if (isset($data['user_id']))
            $this->id = (int) $data['user_id'];
        if (isset($data['user_name']))
            $this->userName = $data['user_name'];
        if (isset($data['user_password_hash']))
            $this->userPasswordHash = $data['user_password_hash'];
        if (isset($data['user_email']))
            $this->userEmail = $data['user_email'];
        if (isset($data['orcid']))
            $this->orcid = $data['orcid']; 
        if (isset($data['orcid_code']))
            $this->orcidCode = $data['orcid_code']; 
        if (isset($data['orcid_access_token']))
            $this->orcidAccessToken = $data['orcid_access_token']; 
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new User($row);
    }

    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $user = new User($row);
            $list[] = $user;
        }

        // Now get the total number of users that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the User object already have an ID?
        if (!is_null($this->id))
            trigger_error("User::insert(): Attempt to insert a User object that already has its ID property set (to $this->id).", E_USER_ERROR);       
        // Insert the User
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO users ( user_name, user_password_hash, user_email, orcid, orcid_code, orcid_access_token ) VALUES ( :user_name , :user_password_hash , :user_email, :orcid, :orcid_code, :orcid_access_token)";
        $st = $conn->prepare($sql);
        $st->bindValue(":user_name", $this->userName, PDO::PARAM_STR);
	$st->bindValue(":user_password_hash", $this->userPasswordHash, PDO::PARAM_STR);
	$st->bindValue(":user_email", $this->userEmail, PDO::PARAM_STR);
        $st->bindValue(":orcid", $this->orcid, PDO::PARAM_STR); 
        $st->bindValue(":orcid_code", $this->orcidCode, PDO::PARAM_STR); 
        $st->bindValue(":orcid_access_token", $this->orcidAccessToken, PDO::PARAM_STR); 
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {                  
        // Does the User object have an ID?
        if (is_null($this->id))
            trigger_error("User::update(): Attempt to update a User object that does not have its ID property set.", E_USER_ERROR);
        
        // Update the User
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE users SET user_name=:user_name, user_password_hash=:user_password_hash, user_email=:user_email, orcid=:orcid, orcid_code=:orcid_code, orcid_access_token=:orcid_access_token WHERE user_id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":user_name", $this->userName, PDO::PARAM_STR);
        $st->bindValue(":user_password_hash", $this->userPasswordHash, PDO::PARAM_STR);
	$st->bindValue(":user_email", $this->userEmail, PDO::PARAM_STR);
        $st->bindValue(":orcid", $this->orcid, PDO::PARAM_STR);
        $st->bindValue(":orcid_code", $this->orcidCode, PDO::PARAM_STR);
        $st->bindValue(":orcid_access_token", $this->orcidAccessToken, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {               
        // Does the User object have an ID?
        if (is_null($this->id))
            trigger_error("User::delete(): Attempt to delete a User object that does not have its ID property set.", E_USER_ERROR);

        // Delete the User
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);        
        $st->execute();
        
        $conn = null;
    }    
}
?>
