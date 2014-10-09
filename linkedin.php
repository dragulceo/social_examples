<?php
session_start();
require 'vendor/autoload.php';

use Local\Config;
use Local\FileWriter;
use Local\LinkedIn;


$linkedIn = new LinkedIn();

$linkedIn->checkState();

$profileData = $linkedIn->fetch('GET', '/v1/people/~');
$matches = false;
if (preg_match('/\bid=([0-9]+)\b/', $profileData, $matches)) {
    $id = $matches[1];
    FileWriter::writeProfile($profileData, $id, Config::$PROFILE_FILENAME_PREFIX_LINKEDIN);
}
$isLoggedIn = true;
include("template.php");

