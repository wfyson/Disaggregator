<?php

require("config.php");

/**
 * the OAuth server should have brought us to this page with a $_GET['code']
 */
if(isset($_GET['code'])) {
    // try to get an access token
    $code = $_GET['code'];
    $url = 'http://blog3-demo.mylabnotebook.ac.uk/oauth2/token';
    // this will be our POST data to send back to the OAuth server in exchange
	// for an access token
    $params = array(
        "code" => $code,
        "client_id" => '31d19a64971c25a5b2aa915dee4db887',
        "client_secret" => 'cc5e68f1f5af79e9a1f5ec632386d5e9',
        "redirect_uri" => 'http://disaggregator.asdf.ecs.soton.ac.uk/oauth2callback.php',
        "grant_type" => "authorization_code"
    );
 
    // build a new HTTP POST request
    $request = new HttpPost($url);
    $request->setPostData($params);
    $request->send();

	// decode the incoming string as JSON
    $responseObj = json_decode($request->getHttpResponse());

	// Tada: we have an access token!
    echo "OAuth2 server provided access token: " . $responseObj->access_token;
}















