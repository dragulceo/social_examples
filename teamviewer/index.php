<?php
session_start();
require '../vendor/autoload.php';

use Local\FileWriter;
use Local\TeamViewer;


$teamViewer = new TeamViewer();

$teamViewer->checkState();
$profileData = $teamViewer->fetch('GET', '/api/v1/account');
$data = json_decode($profileData);
if ($data->userid) {
    FileWriter::writeProfile($profileData, $data->userid, FileWriter::$PROFILE_TYPE_TEAMVIEWER);
}
$isLoggedIn = true;
include("../template.php");
