<?php

/**
 * Class to handle compounds
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
    
    public function getTitle(){
        return $this->name;
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
    
    public function getTags()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT tag.Keyword FROM tag INNER JOIN compound_tag ON tag.TagID = compound_tag.TagID
                WHERE compound_tag.CompoundID = :compoundID";                
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->id, PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {         
            $list[] = $row[Keyword];
        }      
        return $list;
    }
    
    public function getCompoundContributors()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM compound_contributor WHERE CompoundID = :compoundID";        
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->id, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $contributor = new CompoundContributor($row);
            $list[] = $contributor;
        }

        $conn = null;
        return $list;
    }
    
    public function getSpectra()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $sql = "SELECT * FROM spectra WHERE CompoundID = :compoundID";        
        $st = $conn->prepare($sql);
        $st->bindValue(":compoundID", $this->id, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $spectrum = new Spectra($row);
            $list[] = $spectrum;
        }

        $conn = null;
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
    
    public function getUrl()
    {
        $url = "disaggregator.asdf.ecs.soton.ac.uk/view.php?type=compound&id=" . $this->id;
        return $url;
    }
    
    public function getOrcidXml()
    {
        $xml = new DOMDocument();
        
        $xml->appendChild($xmlOrcidMessage = $xml->createElement("orcid-message"));

        //orcid-message
        $xmlOrcidMessage->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $xmlOrcidMessage->setAttribute("xsi:schemaLocation", "http://www.orcid.org/ns/orcid https://raw.github.com/ORCID/ORCID-Source/master/orcid-model/src/main/resources/orcid-message-1.1.xsd");
        $xmlOrcidMessage->setAttribute("xmlns", "http://www.orcid.org/ns/orcid");
        
        //message version
        $xmlOrcidMessage->appendChild($xmlMessageVersion = $xml->createElement("message-version"));        
        $xmlMessageVersion->nodeValue = "1.1";
        
        //orcid-profile, orcid-activities, orcid-works
        $xmlOrcidMessage->appendChild($xmlOrcidProfile = $xml->createElement("orcid-profile"));
        $xmlOrcidProfile->appendChild($xmlOrcidActivities = $xml->createElement("orcid-activities"));
        $xmlOrcidActivities->appendChild($xmlOrcidWorks = $xml->createElement("orcid-works"));
        
        //orcid-work
        $xmlOrcidWork = $xml->createElement("orcid-work");    
        $xmlOrcidWork->setAttribute("visibility", "public");
        
        //title
        $xmlOrcidWork->appendChild($xmlWorkTitle = $xml->createElement("work-title"));
        $xmlWorkTitle->appendChild($xmlTitle = $xml->createElement("title"));
        $xmlTitle->nodeValue = $this->name;
        
        //description
        $xmlOrcidWork->appendChild($xmlDesc = $xml->createElement("short-description"));
        $xmlDesc->nodeValue = $this->description;
        
        //type
        $xmlOrcidWork->appendChild($xmlWorkType = $xml->createElement("work-type"));
        $xmlWorkType->nodeValue = "other";
        
        //publication date
        $xmlOrcidWork->appendChild($xmlPubDate = $xml->createElement("publication-date"));
        $xmlPubDate->appendChild($xmlYear = $xml->createElement("year"));
        $xmlYear->nodeValue = date("Y");
        $xmlPubDate->appendChild($xmlMonth = $xml->createElement("month"));
        $xmlMonth->nodeValue = date("m");  
        
        //url
        $xmlOrcidWork->appendChild($xmlUrl = $xml->createElement("url"));
        $xmlUrl->appendChild($xml->createTextNode($this->getUrl()));
        
        
        //contributors
        $xmlOrcidWork->appendChild($xmlWorkContributors = $xml->createElement("work-contributors"));
        
        $compoundContributors = $this->getCompoundContributors();
        foreach ($compoundContributors as $compoundContributor)
        {
            $contributor = $compoundContributor->getContributor();                                                
            $xmlWorkContributors->appendChild($xmlContributor = $xml->createElement("contributor"));    
            
            $xmlContributor->appendChild($xmlCreditName = $xml->createElement("credit-name"));
            $xmlCreditName->appendChild($xml->createTextNode($contributor->getOrcidName()));          
            
            if($contributor->orcid){
                $xmlContributor->appendChild($xmlContributorOrcid = $xml->createElement("contributor-orcid"));
                
                $xmlContributorOrcid->appendChild($xmlUri = $xml->createElement("uri"));
                $xmlUri->appendChild($xml->createTextNode("http://sandbox.orcid.org/" . $contributor->orcid));
                
                $xmlContributorOrcid->appendChild($xmlPath = $xml->createElement("path"));
                $xmlPath->appendChild($xml->createTextNode($contributor->orcid));
                
                $xmlContributorOrcid->appendChild($xmlHost = $xml->createElement("host"));
                $xmlHost->appendChild($xml->createTextNode("orcid.org"));
            }                        
            
            $xmlContributor->appendChild($xmlContributorAttributes = $xml->createElement("contributor-attributes"));
            $xmlContributorAttributes->appendChild($xmlContributorRole = $xml->createElement("contributor-role"));
            $xmlContributorRole->appendChild($xml->createTextNode($compoundContributor->getOrcidRole()));                        
            
            $xmlContributorAttributes->appendChild($xmlContributorSequence = $xml->createElement("contributor-sequence"));
            $xmlContributorSequence->appendChild($xml->createTextNode("additional"));                        
        }
        
        //language
        $xmlOrcidWork->appendChild($xmlLang = $xml->createElement('language-code'));
        $xmlLang->nodeValue = "en";
        
        $xmlOrcidWorks->appendChild($xmlOrcidWork);

        $resultString = $xml->saveXml();
                        
        return $resultString;
    }                            
}
?>
