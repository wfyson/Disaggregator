<?php

//does the user have a code they can exchange for an access token?
if (isset($contributor->orcidCode)) {
    
    $url = ORCID_OAUTH_TOKEN_URL;

    // this will be our POST data to send back to the OAuth server in exchange for an access token
    $params = array(
        "client_id" => ORCID_OAUTH_CLIENT_ID,
        "client_secret" => ORCID_OAUTH_CLIENT_SECRET,
        "grant_type" => "authorization_code",
        "code" => $contributor->orcidCode
    );

    // build a new HTTP POST request
    $request = new HttpPost($url);
    $request->setCurlHeader(array('Accept: application/json'));
    $request->setPostData($params);
    $request->send();

    $responseObj = json_decode($request->getHttpResponse());

    // decode the incoming string as JSON
    if ($responseObj->access_token != null) {
        $contributor->orcidAccessToken = $responseObj->access_token;
        $contributor->update();
        
        include "publishToOrcid.php";
    } else {
        include "getOrcidCode.php";
    }
}else{
    include "getOrcidCode.php";
}














