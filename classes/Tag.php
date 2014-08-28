<?php

/**
 * Class to handle reactions
 */
class Tag
{

    //Properties    
    public $id = null;
    public $keyword = null;

    public function __construct($data = array())
    {
        if (isset($data['TagID']))
            $this->id = (int) $data['TagID'];
        if (isset($data['Keyword']))
            $this->keyword = $data['Keyword'];
    }

    public function storeFormValues($params)
    {
        //Store all the parameters
        $this->__construct($params);
    }

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM tag WHERE TagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Tag($row);
    }
    
    public static function getByKeyword($keyword)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM tag WHERE Keyword = :keyword";
        $st = $conn->prepare($sql);
        $st->bindValue(":keyword", $keyword, PDO::PARAM_STR);
        $result = $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row)
            return new Tag($row);
        else
            return false;
    }

    public static function getList($numRows = 1000000)
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM tag LIMIT :numRows";
        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $tag = new Tag($row);
            $list[] = $tag;
        }

        // Now get the total number of tags that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]) );
    }

    public function insert()
    {
        // Does the Tag object already have an ID?
        if (!is_null($this->id))
            trigger_error("Tag::insert(): Attempt to insert a Tag object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Insert the Compound
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "INSERT INTO tag (Keyword) VALUES ( :keyword )";
        $st = $conn->prepare($sql);
        $st->bindValue(":keyword", $this->keyword, PDO::PARAM_STR);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return $this->id;
    }
    
    public function update()
    {
        // Does the Tag object have an ID?
        if (is_null($this->id))
            trigger_error("Tag::update(): Attempt to update a Tag object that does not have its ID property set.", E_USER_ERROR);

        // Update the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "UPDATE tag SET Keyword=:keyword WHERE TagID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":keyword", $this->keyword, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    
    public function delete()
    {
        // Does the Tag object have an ID?
        if (is_null($this->id))
            trigger_error("Tag::delete(): Attempt to delete a Tag object that does not have its ID property set.", E_USER_ERROR);

        // Delete the Reaction
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $st = $conn->prepare("DELETE FROM tag WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}

?>
