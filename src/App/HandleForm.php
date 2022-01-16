<?php

namespace App;

/**
 * Class HandleForm
 * @package App
 */
class HandleForm
{
    /**
     * Validates request data by an array that includes:
     * value, type of validation and custom message for error
     *
     * @param array $validates
     * @return array
     */
    public static function validations(array $validates): array
    {
        $output = [];
        $output['status'] = 'OK';
        $output['message'] = 'The process has been completed successfully!';

        $defaultMessages = [
            'required' => 'The field should not be empty!',
            'alphabet' => 'The field should be filled by alphabets!',
            'number' => 'The field should be filled by numbers!',
            'integer' => 'The field should be filled by integers!',
            'email' => 'The field should be filled by an email!',
            'ip' => 'The field should be filled by IP address!',
            'ipv6' => 'The field should be filled by IPv6 address!',
            'url' => 'The field should be filled by URL address!',
            'date(m/d/y)' => 'The field should be filled by date(m/d/y)!',
            'date(m-d-y)' => 'The field should be filled by date(m-d-y)!',
            'date(d/m/y)' => 'The field should be filled by date(d/m/y)!',
            'date(d.m.y)' => 'The field should be filled by date(d.m.y)!',
            'date(d-m-y)' => 'The field should be filled by date(m-d-y)!',
            'past' => 'The field should be filled by past date!',
            'present' => 'The field should be filled by present date!',
            'future' => 'The field should be filled by future date!',
        ];

        foreach ($validates as $validate) {
            if (!self::validate($validate[0], $validate[1])) {
                $output['status'] = 'ERROR';
                $output['message'] = $validate[2] ?? $defaultMessages[$validate[1]];

                return $output;
            }
        }

        return $output;
    }

    /**
     * Validation rules
     * More available at https://www.w3resource.com/php/form/php-form-validation.php
     *
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    private static function validate(mixed $value, string $type): bool
    {
        switch ($type) {
            case 'required':
                return !empty($value);
            case 'alphabet':
                preg_match('/^[a-zA-Z]*$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'number':
                preg_match('/^[0-9]*$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'integer':
                return !empty($value) && (filter_var($value, FILTER_VALIDATE_INT) === 0 || !filter_var($value, FILTER_VALIDATE_INT) === false);
            case 'email':
                preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $value, $matches);
                return !empty($value) && $matches[0];
            case 'ip':
                return !empty($value) && !filter_var($value, FILTER_VALIDATE_IP) === false;
            case 'ipv6':
                return !empty($value) && !filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false;
            case 'url':
                return !empty($value) && !filter_var($value, FILTER_VALIDATE_URL) === false;
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
     * @param array $file ($_FILES['name'])
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
        array $file,
        array $extensions,
        int $size,
        string $target,
        int $compressRate = 100,
        string $baseName = '',
        int $newWidth = 0,
        string $overlay = '',
        int $overlayWidth = 0,
        int $overlayHeight = 0
    ): array
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

        list($width, $height) = getimagesize($file['tmp_name']);
        if ($newWidth == 0) {
            $newWidth = $width;
            $newHeight = $height;
        } else {
            $ratio = $height / $width;
            $newHeight = $newWidth * $ratio;
        }

        $target1 = $target . $baseName . '-temp.' . $fileExtension;
        move_uploaded_file($sourcePath, $target1);

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($fileExtension == 'png') {
            $oldImage = imagecreatefrompng($target1);
        } elseif ($fileExtension == 'jpeg') {
            $oldImage = imagecreatefromjpeg($target1);
        } elseif ($fileExtension == 'gif') {
            $oldImage = imagecreatefromgif($target1);
        } elseif ($fileExtension == 'bmp') {
            $oldImage = imagecreatefrombmp($target1);
        } elseif ($fileExtension == 'tga') {
            $oldImage = imagecreatefromtga($target1);
        } elseif ($fileExtension == 'webp') {
            $oldImage = imagecreatefromwebp($target1);
        } else {
            $oldImage = imagecreatefromstring(file_get_contents($target1));
        }
        imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $target2 = $target . $baseName . '.' . $fileExtension;
        if (!empty($overlay)) {
            imagejpeg($newImage, $target2, 100);
            $overlayImage = imagecreatefrompng($overlay);
            imagecopyresampled($newImage, $overlayImage, 0, 0, 0, 0, $overlayWidth, $overlayHeight, $overlayWidth, $overlayHeight);
        }
        imagejpeg($newImage, $target2, $compressRate);

        unlink($target1);

        return [true, $target1];
    }
}
