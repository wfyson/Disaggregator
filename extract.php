<?php

require("config.php");

session_start();

//get content source
$docid = isset($_GET['docid']) ? $_GET['docid'] : "";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//TODO - check the the current user is allowed to read the document

//read the content source
//read the document and output to results array - this should all be done by the reference class no doubt!!!
$reference = Reference::getById($docid);
switch($reference->getFormat()){
    case "docx":        
        //can this whole process be changed to something more static??? (I think it probably can)        
        $reader = new WordReader($reference);
        $results = $reader->readWord();
    break;     
    case "pdf":        
        require ("libraries/pdfparser/vendor/autoload.php");        
        require ("libraries/base85.class.php");
        $reader = new PDFReader($reference);
        $results = $reader->readPDF();
        break;
}

//write the full text file if not already present
$fullTextPath = $reference->getFulltext();
error_log("the full text path is...........$fullTextPath");
if(!(file_exists($fullTextPath))){    
    file_put_contents($fullTextPath, $results['fullText']);
}

//get the artefact we are after
$type = isset($_GET['type']) ? $_GET['type'] : "";
switch($type){
    case "compound":
        $artefact = new Compound();
        $results['pageTitle'] = "Disaggregator - Extract Compound";
        
        //display via a template
        require( TEMPLATE_PATH . "/extract/compound.php" );
        break;    
    case "reaction":
        $artefact = new Reaction();
        $results['pageTitle'] = "Disaggregator - Extract Reaction";
        
        //display via a template
        require(
                TEMPLATE_PATH . "/extract/reaction.php" );
        break;  
    case "spectra":
        $artefact = new Spectra();
        $results['pageTitle'] = "Disaggregator - Extract Spectrum";
        
        //display via a template
        require( TEMPLATE_PATH . "/extract/spectra.php" );
     break; 
}












