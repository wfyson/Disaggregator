<?php

$query_params = array(
            'client_id' => ORCID_OAUTH_CLIENT_ID,
            'scope' => '/orcid-works/create',
            'response_type' => 'code',
            'redirect_uri' => ORCID_OAUTH_REDIRECT_URI
            );

$forward_url = ORCID_OAUTH_AUTHORIZATION_URL . '?' . http_build_query($query_params);

header('Location: ' . $forward_url);














