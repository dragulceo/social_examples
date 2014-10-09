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
    private static function getProfilePath($id, $prefix)
    {
        return Config::getProfileSaveDir() . '/' . $prefix . '_' . $id . '.json';
    }

    public static function writeProfile($data, $id, $prefix)
    {
        file_put_contents(self::getProfilePath($id, $prefix), $data);
    }
} 