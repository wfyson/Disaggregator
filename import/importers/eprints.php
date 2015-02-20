<?php

class EPrintsImporter
{       
    public static function getDocuments($source, $orcid)
    {
        $mimeTypes = array(
            "application/pdf" => "pdf",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "docx"
            );
        
        $documents = array();
        
        //construct url
        $url = "$source/cgi/exportview/orcid/$orcid/JSON/$orcid.js";
        $json = @file_get_contents($url);
        if ($json) {
            $json = json_decode($json);

            //loop through the eprints
            foreach ($json as $jsonEprint) {
                //loop through the eprint's documents
                $eprintDocuments = $jsonEprint->documents;
                foreach ($eprintDocuments as $document) {
                    //get document if its one the disaggregator can handle
                    if (in_array($document->mime_type, array_keys($mimeTypes))) {
                        $title = $jsonEprint->title . ": " . $document->main;
                        $eprintDocument = new ImportDocument($title, $mimeTypes[$document->mime_type], $source, $document->uri);
                        $documents[] = $eprintDocument;
                    }
                }
            }
            return $documents;
        }
    }    
}

class ImportDocument
{
    public $title;
    public $format;
    public $source;
    public $url;
    
    public function __construct($title, $format, $source, $url)
    {
        $this->title = $title;
        $this->format = $format;
        $this->source = $source;
        $this->url = $url;
    }
}
?>
