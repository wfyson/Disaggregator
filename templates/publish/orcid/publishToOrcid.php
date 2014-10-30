<?php

    //publish an artefact using an accesstoken
    $url = ORCID_OAUTH_API . $user->orcid . '/orcid-works';
    
    $xmlPath = $artefact->getOrcidXml();
    
    // build a new HTTP POST request        
    $request = new HttpPost($url);
    $request->setCurlHeader('Content-Type: application/orcid+xml');
    $request->setCurlHeader("Authorization: Bearer " . $user->orcidAccessToken);
        
    $args['file'] = curl_file_create($xmlPath);   
    $request->setPostFile($args);


//$request->setPostField($xmlPath);
    $request->send();

    $responseObj = json_decode($request->getHttpResponse());
    
    ChromePhp::log("all done here!!!");
    ChromePhp::log($responseObj);
    













