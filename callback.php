<?php

require 'init.php';

// Condition to Prevent CSRF attempt during authorisation
if ( $_GET['state'] != $_SESSION['linkedincsrf'] ) {
    die( 'INVALID REQUEST' );
}

// This Access Token will allow our app to execute requests on the behalf of the authorised user
$accessToken = $linkedin->getAccessToken( $_GET['code'] );
if ( !$accessToken ) {
    die( 'NO ACCESS TOKEN FOUND, LOGIN AGAIN' );
}

// Storing The Session For Our Token
$_SESSION['linkedInAccessToken'] = $accessToken;

header('location: /profile.php');
die();