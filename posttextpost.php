<?php

require 'init.php';

$profileid = $_POST['profileid']; // Profile id from gettextpost file
$content = $_POST['content']; // content from gettextpost file
$privacy = $_POST['privacy']; // privacy from gettextpost file

// object to insert Our Post Inside Linkedin Acount
$post = $linkedin->linkedInTextPost($_SESSION['linkedInAccessToken'], $profileid, $content, $privacy);
$post = json_decode($post);
// check post id to ensure that our post was created
if (isset($post)) {
    echo 'POSTED';
} else {
    echo 'FAILED';
} 
?>
<br>
<a href="/profile.php">Back To Profile</a>