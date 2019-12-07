<?php

use App\Database;

/**
 * Load a view file like Home/home and apssing data to it
 *
 * @param string $view
 * @param array $data
 * @return void
 */
function render(string $view, array $data = [])
{
    $file = APP_ROOT . '/src/Views/' . $view . '.php';

    if (is_readable($file)) require_once $file;
    else die('404 Page not found');
}

/**
 * Slugify string to make user friendly URL
 *
 * @param string $str
 * @param string $delimiter
 * @return string
 */
function slug($str, $delimiter = '-')
{
    $slug = strtolower(
        trim(
            preg_replace(
                '/[\s-]+/',
                $delimiter,
                preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    $delimiter,
                    preg_replace(
                        '/[&]/',
                        'and',
                        preg_replace(
                            '/[\']/',
                            '',
                            iconv('UTF-8', 'ASCII//TRANSLIT', $str)
                        )
                    )
                )
            ),
            $delimiter
        )
    );
    return $slug . '-' . date('d-m-Y');
}

/**
 * Check Cross-site request forgery token
 *
 * @return bool
 */
function csrf()
{
    if (isset($_POST['token'])) {
        if ($_SESSION['token'] === $_POST['token']) {
            if (time() <= $_SESSION['token-expire']) {
                return true;
            }
        }
    }
    return false;
}

// Thanks for great codes: https://gist.github.com/lindelius/4881d2b27fa04356b5736cad81b8c9de
/**
 * Dumps a given variable along with some additional data
 *
 * @param mixed $var
 * @param bool  $pretty
 */
function dd($var, $pretty = true)
{
    $backtrace = debug_backtrace();

    echo "<style>
            pre {
                background: dimgrey;
                border-left: 10px solid darkorange;
                color: whitesmoke;
                page-break-inside: avoid;
                font-family: monospace;
                font-size: 15px;
                line-height: 1.4;
                margin-bottom: 1.4em;
                max-width: 100%;
                overflow: auto;
                padding: 1em 1.4em;
                display: block;
                word-wrap: break-word;
            }
        </style>";
    echo "\n<pre>\n";
    if (isset($backtrace[0]['file'])) {
        echo "<i>" . $backtrace[0]['file'] . "</i>\n\n";
    }
    echo "<small>Type:</small> <strong>" . gettype($var) . "</strong>\n";
    echo "<small>Time: " . date('c') . "</small>\n";
    echo "--------------------------\n\n";
    ($pretty) ? print_r($var) : var_dump($var);
    echo "</pre>\n";
    die;
}

/**
 * Validation rules
 * More available at https://www.w3resource.com/php/form/php-form-validation.php
 *
 * @param string $type
 * @param mixed $value
 * @return bool
 */
function validate($value, $type)
{
    switch ($type) {
        case 'required':
            return !empty($value);
            break;
        case 'alphabets':
            preg_match('/^[a-zA-Z]*$/', $value, $matches);
            return !empty($value) && $matches[0];
            break;
        case 'numbers':
            preg_match('/^[0-9]*$/', $value, $matches);
            return !empty($value) && $matches[0];
            break;
        case 'email':
            preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $value, $matches);
            return !empty($value) && $matches[0];
            break;
        case 'date(m/d/y)':
            $array = explode("/", $value);
            return !empty($value) && checkdate($array[0], $array[1], $array[2]);
            break;
        case 'date(m-d-y)':
            $array = explode("-", $value);
            return !empty($value) && checkdate($array[0], $array[1], $array[2]);
            break;
        case 'date(d/m/y)':
            $array = explode("/", $value);
            return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            break;
        case 'date(d.m.y)':
            $array = explode(".", $value);
            return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            break;
        case 'date(d-m-y)':
            $array = explode("-", $value);
            return !empty($value) && checkdate($array[1], $array[0], $array[2]);
            break;
        case 'past':
            return !empty($value) && strtotime($value) < strtotime('now');
            break;
        case 'present':
            return !empty($value) && strtotime($value) === strtotime('now');
            break;
        case 'future':
            return !empty($value) && strtotime($value) > strtotime('now');
            break;
        default:
            return false;
            break;
    }
}

/**
 * Send HTML Email
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function mailto($to, $subject, $message)
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . EMAIL_FROM . "\r\n";
    $headers .= 'Cc: ' . EMAIL_CC . "\r\n";

    return mail($to, $subject, $message, $headers);
}

/**
 * Upload file
 *
 * @param $_FILES $file
 * @param string $name (related with the name property of input)
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
function upload($file, $name, $extensions, $size, $target, $compressRate = 100, $baseName = '', $newWidth = 0, $overlay = '', $overlayWidth = 0, $overlayHeight = 0)
{
    if (!isset($file[$name]["type"])) {
        return [false, 'File does not exist!'];
    }

    $temporary = explode('.', $file[$name]['name']);
    $fileExtension = end($temporary);
    if (!in_array($fileExtension, $extensions)) {
        return [false, 'Extension error!'];
    }

    if ($file[$name]['size'] > $size) {
        return [false, 'Size error!'];
    }

    if ($file[$name]['error'] > 0) {
        return [false, 'File error!'];
    }

    $sourcePath = $file[$name]['tmp_name'];

    if ($newWidth !== 0) {
        list($width, $height) = getimagesize($file[$name]['tmp_name']);
        $ratio = $height / $width;
        $newHeight = $newWidth * $ratio;

        $target1 = $target . $baseName . '-temp-' . date('d-m-Y') . $fileExtension;
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

        $target2 = $target1 . $baseName . '-' . date('d-m-Y') . $fileExtension;
        imagejpeg($newImage, $target2, 100);
        $overlayImage = imagecreatefrompng($overlay);
        imagecopyresampled($newImage, $overlayImage, 0, 0, 0, 0, $overlayWidth, $overlayHeight, $overlayWidth, $overlayHeight);
        imagejpeg($newImage, $target2, $compressRate);

        unlink($target1);
    } else {
        $target1 = $target . $baseName . '-' . date('d-m-Y') . $fileExtension;
        move_uploaded_file($sourcePath, $target1);
    }

    return [true, $target1];
}

/**
 * Convert HTML characters to characters entity reference
 *
 * @param string $str
 * @return string
 */
function rssXml($str)
{
    $str = str_replace('<', '&lt;', $str);
    $str = str_replace('>', '&gt;', $str);
    $str = str_replace('"', '&quot;', $str);
    $str = str_replace("'", '&#39;', $str);
    $str = str_replace("&", '&amp;', $str);
    return $str;
}

/**
 * Generate sitemap.xml & rss.xml via a script file
 *
 * @return void
 */
function feed()
{
    require_once dirname(__DIR__) . '/public/sitemap.php';
    require_once dirname(__DIR__) . '/public/feed/index.php';
}

/**
 * Return current user information
 *
 * @return array
 */
function currentUser()
{
    if (!isset($_COOKIE['loggedin'])) die('401 Unauthorized Error');

    Database::query("SELECT * FROM users WHERE email = :email");
    Database::bind(':email', base64_decode($_COOKIE['loggedin']));

    return Database::fetch();
}
