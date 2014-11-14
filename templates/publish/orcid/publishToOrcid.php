<?php

    //publish an artefact using an accesstoken

    $url = ORCID_OAUTH_API . $contributor->orcid . '/orcid-works';
    
    $xmlString = $artefact->getOrcidXml();
    
    // build a new HTTP POST request      
    $contentHeader = "Content-Type: application/orcid+xml";
    $authorizationHeader = "Authorization: Bearer " . $contributor->orcidAccessToken;
    
    
    $request = new HttpPost($url);
    $request->setCurlHeader(array($contentHeader, $authorizationHeader));
        
    $request->setPostField($xmlString);

    $request->send();

    $responseObj = json_decode($request->getHttpResponse());
    
    if($responseObj == null)
    {
        ?>
<h2><?php echo get_class($artefact); ?> Published</h2>

<p>The <?php echo $type . ', "' . $artefact->getTitle() . '"'; ?> has been successfully added to your ORCID profile.</p>

<a class='btn btn-primary' href='index.php'>Return</a>

  <?php  } ?>