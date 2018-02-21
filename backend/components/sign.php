<?php
namespace backend\components;

class sign
{
    private static function _file($no)
    {
        return getcwd() . '/data/sign/' . $no . '.uri';
    }

    public static function save($no, $data)
    {
        utilities::createFolder('data/sign/');
        $file = self::_file($no);
        file_put_contents($file, $data);
        chmod($file, 0666);
    }

    public static function read($no)
    {
        $file = self::_file($no);
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            return null;
        }
    }
}
