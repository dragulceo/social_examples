<?php
namespace Local;

class Config
{
    public static $FACEBOOK_APP_ID = '';
    public static $FACEBOOK_APP_SECRET = '';

    public static function getFacebookRedirectURL()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/facebook.php';
    }

    public static $LINKEDIN_APP_ID = '';
    public static $LINKEDIN_APP_SECRET = '';
    public static $LINKEDIN_SCOPE = 'r_fullprofile r_emailaddress';


    public static function getLinkedInRedirectURL()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/linkedin.php';
    }


    public static function getProfileSaveDir()
    {
        return __DIR__ . "/../profiles";
    }
    public static $TEAMVIEWER_APP_ID = '';
    public static $TEAMVIEWER_APP_SECRET = '';
    public static function getTeamViewerRedirectURL()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/teamviewer';
    }

    public static $PROFILE_FILANAME_PREFIX_FACEBOOK = 'fb';
    public static $PROFILE_FILENAME_PREFIX_LINKEDIN = 'ln';
    public static $PROFILE_FILENAME_PREFIX_TEAMVIEWER = 'tw';

}

