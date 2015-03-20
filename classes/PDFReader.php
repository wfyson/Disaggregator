<?php

class PDFReader {

    protected $path;
    protected $imageLinks = array();
    protected $fullText;

    public function __construct($reference) {
        $userid = $_SESSION['userid'];

        //set the readers reference to the file
        $this->path = './uploads/' . $userid . '/' . str_replace('.', '_', $reference->refFile) . '/' . $reference->refFile;
    }

    public function readPDF() {
        $id = 0; 
        $rootPara = new WordText($id, "Root");
        $root = new WordHeading($id++, $rootPara, 0);
                
        $parser = new Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($this->path);        
        
        $pages = $pdf->getPages();               
        $pageCount = 0;
        foreach($pages as $page){      
            $pageNo = $pageCount + 1;
            $headingText = new WordText($id++, "Page $pageNo");
            $pageHeader = new WordHeading($id++, $headingText, 2, $root);
            $root->addPara($pageHeader);
            
            //get the text from the page
            $text = $page->getText();    
            $paragraphs = explode("\n", $text);            
            foreach($paragraphs as $para)
            {
                $this->fullText = $this->fullText . " " . $para;
                $textContent = new WordText($id++, $para);    
                $root->addPara($textContent);
                $count++;
            }
            
            //increment the page count
            $pageCount++;
        }   
        
        //get the images for the pdf
        $objects = $pdf->getObjects();
        $imageCount = 0;
        $imagePaths = array();
        $noFails = 0;
        foreach ($objects as $object){                                  
            if ($object instanceof Smalot\PdfParser\XObject\Image){                                   
                //get the objects details
                $details = $object->getDetails();
                $filter = $details['Filter'];
        
                //get the image data and create an image object
                $data = $object->getContent();    
                
                $imagePath = $this->path . "_image$imageCount";
                if($this->writeImage($filter, $data, $imagePath)){
                    $imagePaths[] = $imagePath;
                }else{
                    $noFails++;
                }                
                //increment image counter for generation of new paths
                $imageCount++;
            }
        }

        $results = array();
        $results['text'] = $root;
        $results['images'] = $imagePaths;
        $results['fails'] = $noFails;
        $results['fullText'] = $this->fullText;
        
        return $results;
    }
    
    public function writeImage($filter, $data, $imagePath)
    {
        switch($filter){
            case "DCTDecode":   
                $image = imagecreatefromstring($data);
                imagepng($image, $imagePath);   
                return true;
            break;     
            case "FlateDecode": 
                /*$tempPath = $imagePath . '_temp.bin';
                $fp = fopen($tempPath, 'w');
                fwrite($fp, $data);                
                $f = @fopen($tempPath, "rb");
                echo filesize($tempPath) . "<br>";
                $c = fread($f, filesize($tempPath));           // now I know all about it 
                print_r($c);
                $u = @gzuncompress($c);                 // function, exactly fits for this /FlateDecode filter  
          
                $out = fopen($imagePath, "wb");     // ready to output anywhere
                fwrite($out, $u);*/
                return false;                
            break;    
        }
    }
}
?>

