<?php

/*
 * Common elements that occur for reading any documents using the Office Open 
 * XML format are implemented here
 */

define("IMAGE_REL_TYPE", "http://schemas.openxmlformats.org/officeDocument/2006/relationships/image");

abstract class OpenXmlReader {

    protected $path;
    protected $zip;
    protected $imageLinks = array();

    //protected $docName;
    //protected $localPath;
    //protected $file;
    //protected $imagePath;

    public function __construct($reference) {
        $userid = $_SESSION['userid'];

        //set the readers reference to the file
        $this->path = './uploads/' . $userid . '/' . str_replace('.', '_', $reference->refFile) . '/' . $reference->refFile;
    }

    //read the image from the powerpoint and write it to the server and return the link
    public function readImage($entryName, $zipEntry) {
        $img = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
        if ($img !== null) {
            $imagePath = $this->path . basename($entryName);
            file_put_contents($imagePath, $img);
        }
        return $imagePath;
    }

}

class WordReader extends OpenXmlReader {

    private $fullText;
    
    public function readWord() {
        $this->zip = zip_open($this->path);
        $zipEntry = zip_read($this->zip);

        while ($zipEntry != false) {
            //read through all the files, call appropriate functions for each            
            $entryName = zip_entry_name($zipEntry);

            //for image files
            if (strpos($entryName, 'word/media/') !== FALSE) {
                $this->imageLinks[] = $this->readImage($entryName, $zipEntry);
            }
            
            //for image rels
            if (strpos($entryName, 'word/_rels/document.xml.rels') !== FALSE)
            {
                $this->rels = $this->readRels($zipEntry);
            }
              
            //for document content
            if (strpos($entryName, 'word/document.xml') !== FALSE)
            {
                $this->text = $this->readText($zipEntry);
            }
             
            $zipEntry = zip_read($this->zip);
        }

        $results = array();
        $results['images'] = $this->imageLinks;
        $results['text'] = $this->text;
        $results['rels'] = $this->rels;
        $results['fullText'] = $this->fullText;

        return $results;
    }
    

    /*
     * Create an associative array to link rel ids to images
     */    
    public function readRels($zipEntry)
    {        
        $relList = array();                       

        $rels = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
        $xml = simplexml_load_string($rels);
        
        for ($i = 0; $i < $xml->count(); $i++) {
            $record = $xml->Relationship{$i};
            $type = $record->attributes()->Type;
            $cmp = strcmp($type, constant("IMAGE_REL_TYPE"));
            if ($cmp == 0)
            {
                $id = $record->attributes()->Id;
                $target = $this->path . basename($record->attributes()->Target);                   
                $relList[(string)$id] = $target;           
            }
        }
        return $relList;        
    }

    /*
     * Read the text of a Word document creating a hierarchy of headings
     * Includes reading of images within the text (represented by their relIDs)
     * and captions.
     */
    public function readText($zipEntry) {
        $doc = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
        $xml = simplexml_load_string($doc);

        $this->id = 0;

        //create a root section
        $rootPara = new WordText($this->id, "Root");
        $this->id++;
        $root = new WordHeading($this->id, $rootPara, 0);
        $this->id++;

        $body = $xml->xpath('/w:document/w:body');

        $paras = $body[0]->xpath('w:p | w:tbl');

        $i = 0;
        $currentHeading = $root;
        while ($i < count($paras)) {
            $para = $paras[$i];

            if ($para[0]->getName() == "p") {
                $reading = $this->readPara($para, $currentHeading);
                if ($reading->getType() == "heading") {
                    $currentHeading = $reading->getParent();
                    $currentHeading->addPara($reading);
                } else {
                    $currentHeading->addPara($reading);
                }
            } else {
                if ($para[0]->getName() == "tbl") {
                    $reading = $this->readTable($para);
                    $currentHeading->addPara($reading);
                }
            }
            $i++;
        }
        return $root;
    }

    public function readPara($para, $parent) {
        //check if there is a picture
        $wordImage = $this->readParaImage($para);
        //check the style of the para 
        $style = $para[0]->xpath('w:pPr/w:pStyle');
        if ($style[0] != null) { // a style is present
            $val = $style[0]->xpath('@w:val');
            $styleVal = $val[0];

            //if the style is a heading            
            if (strpos($styleVal, 'Heading') === 0) {
                //determine heading level
                $headingLevel = intval(substr($styleVal, 7));

                //read the text of the para
                $text = $this->readParaText($para);

                //determine the new heading's parent
                if ($headingLevel > $parent->getLevel()) {
                    //a level deeper
                    $newParent = $parent;
                } else {
                    $difference = $parent->getLevel() - $headingLevel;
                    $i = 0;
                    while ($i < $difference) {
                        $newParent = $parent->getParent();
                        $parent = $newParent;
                        $i++;
                    }
                }

                //create a new heading
                $heading = new WordHeading($this->id, $text, $headingLevel, $newParent);
                return $heading;
            }

            //if the stye is a caption
            if (strpos($styleVal, "Caption") === 0) {
                //return a caption thing
                $text = $this->readParaText($para);

                if ($wordImage !== null) {
                    $this->id = $this->id - 1;
                    $wordCaption = new WordCaption($this->id, $text);
                    $wordImage->addCaption($wordCaption);
                    return $wordImage;
                } else {
                    $wordCaption = new WordCaption($this->id, $text);
                    return $wordCaption;
                }
            }

            //style present but we're not interested
            $text = $this->readParaText($para);
            return $text;
        }
        if ($wordImage !== null) {
            return $wordImage;
        } else {
            //else nothing interesting going on so just read text
            $text = $this->readParaText($para);
            return $text;
        }
    }

    public function readParaImage($para) {        
        $wordImage = null;
        $positioning = $para[0]->xpath('w:r/w:drawing/*');
        if ($positioning[0] != null) {
            $positioning[0]->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
            $graphicData = $positioning[0]->xpath('a:graphic/a:graphicData');
            $graphicData[0]->registerXPathNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
            $pic = $graphicData[0]->xpath('pic:pic');
            if ($pic[0] != null) {
                //get the rel id
                $relTag = $pic[0]->xpath('pic:blipFill/a:blip');
                $relID = $relTag[0]->xpath('@r:embed');
                $this->id++;
                $wordImage = new WordImage($this->id, (string) $relID[0]);
            }
        }
        return $wordImage;
    }

    public function readTable($table) {
        //create a table object
        $this->id++;
        $wordTable = new WordTable($this->id);

        //cycle through each table row
        $rows = $table[0]->xpath('w:tr');
        foreach ($rows as $row) {
            $this->id++;
            $wordRow = new WordRow($this->id);

            //get the cell for each row
            $cells = $row[0]->xpath('w:tc');
            foreach ($cells as $cell) {
                $this->id++;
                $wordCell = new WordCell($this->id);

                $paras = $cell[0]->xpath('w:p');
                foreach ($paras as $para) {
                    $wordImage = $this->readParaImage($para);
                    if ($wordImage !== null) {
                        //$this->id--;
                        $wordCell->addPara($wordImage);
                    } else {
                        $para = $this->readParaText($para);
                        //$this->id--;
                        $wordCell->addPara($para);
                    }
                }
                $wordRow->addCell($wordCell);
            }
            $wordTable->addRow($wordRow);
        }
        return $wordTable;
    }

    public function readParaText($para) {
        $text = '';
        $textTags = $para[0]->xpath('w:r/w:t');
        foreach ($textTags as $wt) {
            $text = $text . $wt[0];
        }
        $this->id++;
               
        $this->fullText = $this->fullText . " " . $text;
        
        $result = new WordText($this->id, $text);
        return $result;
    }

}

//describes the methods that a readable component of a word document must implement
interface WordReadable {

    public function getId();

    public function getType();

    public function getContent(); //return representation of the content
}

class WordText implements WordReadable {

    private $id;
    private $text;

    public function __construct($id, $text) {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getType() {
        return "text";
    }

    public function getContent() {
        return $this->text;
    }

}

class WordImage implements WordReadable {

    private $relID;
    private $id;
    private $caption = null;

    public function __construct($id, $relID) {
        $this->id = $id;
        $this->relID = $relID;
    }

    public function addCaption($caption) {
        $this->caption = $caption;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return "image";
    }

    public function getCaption() {
        return $this->caption;
    }

    public function getContent() {
        //return a link ideally...
        return $this->relID;
    }

}

class WordCaption implements WordReadable {

    private $text;
    private $id;

    public function __construct($id, $text) {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getType() {
        return "caption";
    }

    public function getContent() {
        return $this->text->getText();
    }

}

class WordHeading implements WordReadable {

    private $id;
    private $title;
    private $level;
    private $parent;
    private $paraArray = array();

    public function __construct($id, $title, $level, $parent = null) {
        $this->id = $id;
        $this->title = $title;
        $this->level = $level;
        $this->parent = $parent;
    }

    public function addPara($para) {
        $this->paraArray[] = $para;
    }

    public function getId() {
        return $this->id;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getParaArray() {
        return $this->paraArray;
    }

    public function getType() {
        return "heading";
    }

    public function getContent() {
        return $this->title->getText();
    }

}

class WordTable implements WordReadable {

    private $id;
    private $rows = array();

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return "table";
    }

    public function addRow($row) {
        $this->rows[] = $row;
    }

    public function getContent() {
        return $this->rows;
    }

}

class WordRow implements WordReadable {

    private $id;
    private $cells = array();

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return "row";
    }

    public function addCell($cell) {
        $this->cells[] = $cell;
    }

    public function getContent() {
        return $this->cells;
    }

}

class WordCell implements WordReadable {

    private $id;
    private $paras = array();

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return "cell";
    }

    public function addPara($text) {
        $this->paras[] = $text;
    }

    public function getContent() {
        return $this->paras;
    }

}
?>

