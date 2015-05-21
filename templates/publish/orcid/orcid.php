<?php include "templates/include/header.php" ?>

<?php    

    if(isset($artefact))
    {
        $user = User::getById($userid); 
        $contributor = $user->getContributor();

        //does the user have an access token?
        if(isset($contributor->orcidAccessToken)){
            //publish the artefact
            include "publishToOrcid.php";         
        }else{
            include "getOrcidAccessToken.php";
        }
    }else{
        include "getOrcidCode.php";
    }
        
    
// HttpPost.class.php
class HttpPost {
          public $url;
          public $postString;
          public $httpResponse;

          public $ch;

          public function __construct($url) {
                $this->url = $url;
                $this->ch = curl_init( $this->url );
                curl_setopt( $this->ch, CURLOPT_FOLLOWLOCATION, false );                
                curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
          }

          public function __destruct() {
                    curl_close($this->ch);
          }    
          
          public function setCurlHeader($header){
              curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);              
          }
          
          public function setPostField( $field ){
                curl_setopt( $this->ch, CURLOPT_POST, true );
                curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $field );
          }
          
          public function setPostFile( $file ){
                curl_setopt($this->ch, CURLOPT_POST,1);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $file);
          }
          
          public function setPostData( $params ) {
                // http_build_query encodes URLs, which breaks POST data
                $this->postString = rawurldecode(http_build_query( $params ));
                curl_setopt( $this->ch, CURLOPT_POST, true );
                curl_setopt ( $this->ch, CURLOPT_POSTFIELDS, $this->postString );
          }

          public function send() {              
              
                $info = curl_getinfo($this->ch);                
                              
                $this->httpResponse = curl_exec( $this->ch );
                
                $result = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
          }

          public function getHttpResponse() {
                return $this->httpResponse;
          }
}

?>

<?php include "templates/include/footer.php" ?>

