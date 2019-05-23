<?php
/*
This Page For Make Switch Between ( create Text Post || Create Article/Link Post || create Image Post )
*/
// fetch the type of our post
$type = $_GET['type'];
// Switch condition
switch($type) {
    case 'text': // text post
        header('location: /gettextpost.php');
        die();
        break;
    case 'link': // article/link post
        die('link');
        break;
    case 'image': // image post
        die('image');
        break;
    default:
        die('INVALID USE OF HEADER INFORMATION');
        break;
}