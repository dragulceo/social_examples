<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 08/10/14
 * Time: 03:09
 */

namespace Local;


class FileWriter
{
    public static $PROFILE_TYPE_FACEBOOK = 'fb';
    public static $PROFILE_TYPE_LINKEDIN = 'ln';

    private static function getProfilePath($id, $type) {
        return Config::getProfileSaveDir() . '/' . $type . '_' . $id . '.json';
    }

    public static function writeProfile($data, $id, $type) {
        file_put_contents(self::getProfilePath($id, $type), $data);
    }
} 