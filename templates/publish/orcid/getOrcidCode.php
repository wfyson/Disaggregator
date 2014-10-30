<?php
//useful oauth link: http://20missionglass.tumblr.com/post/60787835108/programming-an-oauth2-client-app-in-php

//define('OAUTH_TOKEN_URL', 'http://blog3-demo.mylabnotebook.ac.uk/oauth2/token');

$query_params = array(
            'client_id' => ORCID_OAUTH_CLIENT_ID,
            'scope' => '/orcid-works/create',
            'response_type' => 'code',
            'redirect_uri' => ORCID_OAUTH_REDIRECT_URI,
            );

$forward_url = ORCID_OAUTH_AUTHORIZATION_URL . '?' . http_build_query($query_params);

header('Location: ' . $forward_url);

/*
//useful oauth link: http://20missionglass.tumblr.com/post/60787835108/programming-an-oauth2-client-app-in-php
define('OAUTH_CLIENT_ID', '31d19a64971c25a5b2aa915dee4db887');
define('OAUTH_CLIENT_SECRET', 'cc5e68f1f5af79e9a1f5ec632386d5e9');
define('OAUTH_REDIRECT_URI', 'http://disaggregator.asdf.ecs.soton.ac.uk/oauth2callback.php');
define('OAUTH_AUTHORIZATION_URL', 'http://blog3-demo.mylabnotebook.ac.uk/oauth2/authorize');
define('OAUTH_TOKEN_URL', 'http://blog3-demo.mylabnotebook.ac.uk/oauth2/token');

$query_params = array(
           'response_type' => 'code',
           'client_id' => OAUTH_CLIENT_ID,
           'redirect_uri' => OAUTH_REDIRECT_URI
           );

$forward_url = OAUTH_AUTHORIZATION_URL . '?' . http_build_query($query_params);

header('Location: ' . $forward_url);
*/














