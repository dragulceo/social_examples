<?php
session_start();
require 'vendor/autoload.php';

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRequestException;
use Local\Config;
use Local\FileWriter;

FacebookSession::setDefaultApplication(Config::$FACEBOOK_APP_ID, Config::$FACEBOOK_APP_SECRET);

$helper = new FacebookRedirectLoginHelper(Config::getFacebookRedirectURL());
$session = false;
try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {
    echo "FacebookRequestException: " . $ex->getMessage();
} catch (\Exception $ex) {
    echo "Exception: " . $ex->getMessage();
}

$graphObject = false;
if ($session) {
    // Logged in
    $request = new FacebookRequest($session, 'GET', '/me');
    $response = $request->execute();
    $data = $response->getResponse();

    $profileData = json_encode($data);
    FileWriter::writeProfile($profileData, $data->id, Config::$PROFILE_FILANAME_PREFIX_FACEBOOK);
    $isLoggedIn = true;
    include("template.php");

} else {

    //Add 'user_about_me' permission key in the array below to get the full profile
    $loginUrl = $helper->getLoginUrl(array('email', 'public_profile'));
    header("Location: " . $loginUrl);
}

