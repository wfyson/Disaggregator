<?php

/**
 * Class to handle authors
 */
class Author
{

    //Properties    
    public $id = null;
    public $name = null;
    public $userID = null;

    public function __construct($data = array())
    {
        if (isset($data['AuthorID']))
            $this->id = (int) $data['AuthorID'];
        if (isset($data['Name']))
            $this->name = $data['Name'];
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
        $sql = "SELECT * FROM author WHERE AuthorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Tag($row);
    }
    
    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM author LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $author = new Author($row);
            $list[] = $author;
        }

        // Now get the total number of authors that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the Tag object already have an ID?
        if (!is_null($this->id))
            trigger_error("Author::insert(): Attempt to insert an Author object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Compound
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO author (Name) VALUES ( :name, :userID )";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":userid", $this->userID, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {
        // Does the Author object have an ID?
        if (is_null($this->id))
            trigger_error("Author::update(): Attempt to update a Author object that does not have its ID property set.", E_USER_ERROR);

        // Update the Author
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE tag SET Name=:name, UserID=:userID WHERE AuthorID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":userID", $this->userID, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Tag object have an ID?
        if (is_null($this->id))
            trigger_error("Author::delete(): Attempt to delete a Author object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM author WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
