<?php

namespace App;

/**
 * Class HandleForm
 * @package App
 */
class HandleForm
{
    /**
     * Validation rules
     * More available at https://www.w3resource.com/php/form/php-form-validation.php
     *
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    public static function validate($value, string $type)
    {
        switch ($type) {
            case 'required':
                return !empty($value);
            case 'alphabets':
                preg_match('/^[a-zA-Z]*$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'numbers':
                preg_match('/^[0-9]*$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'email':
                preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'date(m/d/y)':
                $array = explode("/", $value);
                return !empty($value) && checkdate($array[0], $array[1], $array[2]);
            case 'date(m-d-y)':
                $array = explode("-", $value);
                return !empty($value) && checkdate($array[0], $array[1], $array[2]);
            case 'date(d/m/y)':
                $array = explode("/", $value);
                return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            case 'date(d.m.y)':
                $array = explode(".", $value);
                return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            case 'date(d-m-y)':
                $array = explode("-", $value);
                return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            case 'past':
                return !empty($value) && strtotime($value) < strtotime('now');
            case 'present':
                return !empty($value) && strtotime($value) === strtotime('now');
            case 'future':
                return !empty($value) && strtotime($value) > strtotime('now');
            default:
                return false;
        }
    }

    /**
     * Upload file
     *
     * @param string $file ($_FILES['name'])
     * @param array $extensions (like ['jpeg', 'jpg','png'] or ['pdf', 'xml', 'csv'])
     * @param integer $size (size in byte)
     * @param string $target (new file address)
     * @param integer $compressRate (like 85)
     * @param string $baseName (like post slug)
     * @param integer $newWidth (new pixel size for width like 1600, height calculate proportionally)
     * @param string $overlay (overlay PNG image address)
     * @param integer $overlayWidth (overlay PNG image width)
     * @param integer $overlayHeight (overlay PNG image height)
     * @return array (2 elements: false and error message OR true and file address)
     */
    public static function upload(
        string $file,
        array $extensions,
        int $size,
        string $target,
        int $compressRate = 100,
        string $baseName = '',
        int $newWidth = 0,
        string $overlay = '',
        int $overlayWidth = 0,
        int $overlayHeight = 0
    )
    {
        if (!isset($file['type'])) {
            return [false, 'File does not exist!'];
        }

        $temporary = explode('.', $file['name']);
        $fileExtension = end($temporary);
        if (!in_array($fileExtension, $extensions)) {
            return [false, 'Extension error!'];
        }

        if ($file['size'] > $size) {
            return [false, 'Size error!'];
        }

        if ($file['error'] > 0) {
            return [false, 'File error!'];
        }

        $sourcePath = $file['tmp_name'];

        if ($newWidth !== 0) {
            list($width, $height) = getimagesize($file['tmp_name']);
            $ratio = $height / $width;
            $newHeight = $newWidth * $ratio;

            $target1 = $target . $baseName . '-temp.' . $fileExtension;
            move_uploaded_file($sourcePath, $target1);

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            if ($fileExtension == 'png') {
                $oldImage = imagecreatefrompng($target1);
            } elseif ($fileExtension == 'jpeg') {
                $oldImage = imagecreatefromjpeg($target1);
            } elseif ($fileExtension == 'gif') {
                $oldImage = imagecreatefromgif($target1);
            }
            imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            $target2 = $target1 . $baseName . '.' . $fileExtension;
            imagejpeg($newImage, $target2, 100);
            $overlayImage = imagecreatefrompng($overlay);
            imagecopyresampled($newImage, $overlayImage, 0, 0, 0, 0, $overlayWidth, $overlayHeight, $overlayWidth, $overlayHeight);
            imagejpeg($newImage, $target2, $compressRate);

            unlink($target1);
        } else {
            $target1 = $target . $baseName . '.jpg';
            move_uploaded_file($sourcePath, $target1);
        }

        return [true, $target1];
    }
}