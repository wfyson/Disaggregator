<?php include "templates/include/header.php" ?>

<?php
    //check to see if a token exists for the user
    $user = User::getById($userid);
    
    if(isset($user->orcidCode)){
        //get an access token                
        $query_params = array(
            'client_id' => ORCID_OAUTH_CLIENT_ID,
            'client_secret' => ORCID_OAUTH_CLIENT_SECRET,
            'grant_type' => 'authorization_code',
            'code' => $user->orcidCode
        );
        
        // try to get an access token
        $url = ORCID_OAUTH_TOKEN_URL;
        
        // this will be our POST data to send back to the OAuth server in exchange for an access token
	$params = array(
            "client_id" => ORCID_OAUTH_CLIENT_ID,
            "client_secret" => ORCID_OAUTH_CLIENT_SECRET,
            "grant_type" => "authorization_code",
            "code" => $user->orcidCode
        );
 
        // build a new HTTP POST request
        $request = new HttpPost($url);
        $request->setPostData($params);
        $request->send();

        ChromePhp::log($params);
        
	// decode the incoming string as JSON
        if ($responseObj->error-desc != null){
            echo "Hello";
            
        }else{
            echo "Fail";
            include "registerOrcid.php";
        }
        
        $responseObj = json_decode($request->getHttpResponse());
        ChromePhp::log($responseObj);
        echo $responseObj;
        
    }else{
        
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
                   curl_setopt( $this->ch, CURLOPT_HEADER, 'Accept: application/json' );
                   curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
          }


          public function __destruct() {
                    curl_close($this->ch);
          }    
          public function setPostData( $params ) {
                   // http_build_query encodes URLs, which breaks POST data
                   $this->postString = rawurldecode(http_build_query( $params ));
                   curl_setopt( $this->ch, CURLOPT_POST, true );
                   curl_setopt ( $this->ch, CURLOPT_POSTFIELDS, $this->postString );
          }

          public function send() {
                   $this->httpResponse = curl_exec( $this->ch );
          }

          public function getHttpResponse() {
                    return $this->httpResponse;
          }
}

?>

<?php include "templates/include/footer.php" ?>

