<?php
session_start();
require 'vendor/autoload.php';

use Local\FileWriter;
use Local\LinkedIn;

// OAuth 2 Control Flow
if (isset($_GET['error'])) {
    // LinkedIn returned an error
    print $_GET['error'] . ': ' . $_GET['error_description'];
    exit;
} elseif (isset($_GET['code'])) {
    // User authorized your application
    if ($_SESSION['state'] == $_GET['state']) {
        // Get token so you can make API calls
        LinkedIn::getAccessToken();
    } else {
        // CSRF attack? Or did you mix up your states?
        exit;
    }
} else {
    if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
        // Token has expired, clear the state
        $_SESSION = array();
    }
    if (empty($_SESSION['access_token'])) {
        // Start authorization process
        LinkedIn::getAuthorizationCode();
    }
}

// Congratulations! You have a valid token. Now fetch your profile
$user = LinkedIn::fetch('GET', '/v1/people/~');

$matches = false;
if(preg_match('/\bid=([0-9]+)\b/', $user, $matches)) {
    $id = $matches[1];
    FileWriter::writeProfile($user, $id, FileWriter::$PROFILE_TYPE_LINKEDIN);
}
$isLoggedIn = true;
$profileData = $user;
include("template.php");

exit;

