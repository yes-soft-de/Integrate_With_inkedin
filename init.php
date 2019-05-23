<?php
// Start Session
session_start();
// Require autoload file
require 'vendor/autoload.php';
require 'LinkedIn.php';

use myPhp\LinkedIn;

$app_id = "application_id"; // fetch after create app
$app_secret = "application_secret"; // fetch after create app
$app_callback = "http://example.com/callback.php"; // callback function file
$app_scopes = "r_emailaddress r_liteprofile w_member_social"; // permissions : fetch after create app

$ssl = false; // ALWAYS TRUE FOR PRODUCTION USE

// Linkedin Object
$linkedin = new LinkedIn($app_id, $app_secret, $app_callback, $app_scopes, $ssl);

