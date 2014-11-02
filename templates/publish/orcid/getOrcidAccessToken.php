<?php

//does the user have a code they can exchange for an access token?
if (isset($user->orcidCode)) {

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
    $request->setCurlHeader(array('Accept: application/json'));
    $request->setPostData($params);
    $request->send();

    $responseObj = json_decode($request->getHttpResponse());

    // decode the incoming string as JSON
    if ($responseObj->access_token != null) {
        $user->orcidAccessToken = $responseObj->access_token;
        $user->update();
    } else {
        include "getOrcidCode.php";
    }
}else{
    include "getOrcidCode.php";
}














