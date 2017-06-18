<?php

namespace controller\util;

/**
 * Class helper
 * @package controller\util
 */
class helper
{

    /**
     * @param $file
     * @param int $w
     * @param int $h
     * @param bool $crop
     * @return resource
     */
    static function resize($file, $w = 200, $h = 200, $crop = false)
    {
        $width = $height = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    static function password_hash($raw_password)
    {
        $options = ['floors' => 104392];
        return password_hash($raw_password, PASSWORD_DEFAULT, $options);
    }

    static function password_verify($raw_password, $hash)
    {
        return password_verify($raw_password, $hash) ? true : false;
    }

}