<?php
session_start();
require 'vendor/autoload.php';

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Local\Config;

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

    $isLoggedIn = true;
    $profileData = json_encode($data);
    include("template.php");

} else {
    $loginUrl = $helper->getLoginUrl();
    header("Location: ". $loginUrl);
}

