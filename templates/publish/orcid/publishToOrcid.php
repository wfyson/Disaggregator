<?php

    //publish an artefact using an accesstoken
    $url = ORCID_OAUTH_API . $user->orcid . '/orcid-works';
    
    $xmlString = $artefact->getOrcidXml();
    
    // build a new HTTP POST request      
    $contentHeader = "Content-Type: application/orcid+xml";
    $authorizationHeader = "Authorization: Bearer " . $user->orcidAccessToken;
    
    
    $request = new HttpPost($url);
    $request->setCurlHeader(array($contentHeader, $authorizationHeader));
        
    $request->setPostField($xmlString);

    $request->send();

    $responseObj = json_decode($request->getHttpResponse());
    













