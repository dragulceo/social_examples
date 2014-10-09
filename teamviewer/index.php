<?php
session_start();
require '../vendor/autoload.php';

use Local\Config;
use Local\FileWriter;
use Local\TeamViewer;


$teamViewer = new TeamViewer();

$teamViewer->checkState();
$profileData = $teamViewer->fetch('GET', '/api/v1/account');
$data = json_decode($profileData);
if ($data->userid) {
    FileWriter::writeProfile($profileData, $data->userid, Config::$PROFILE_FILENAME_PREFIX_TEAMVIEWER);
}
$isLoggedIn = true;
include("../template.php");
