<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

/**
 * Class Helper
 * @package App
 */
class Helper
{
    /**
     * Check Cross-site request forgery token
     *
     * @param string $token
     * @return bool
     */
    public static function csrf(string $token): bool
    {
        if ($_SESSION['token'] === $token) {
            if (time() <= $_SESSION['token-expire']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load a view file like Home/home and assign data to it
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public static function render(string $view, array $data = [])
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
     * @param bool $addDate
     * @return string
     */
    public static function slug(string $str, string $delimiter = '-', bool $addDate = true): string
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
        return $slug . ($addDate ? '-' . date('d-m-Y') : '');
    }

    // Thanks for great codes: https://gist.github.com/lindelius/4881d2b27fa04356b5736cad81b8c9de
    /**
     * Dumps a given variable along with some additional data
     *
     * @param mixed $var
     * @param bool $pretty
     */
    #[NoReturn] public static function dd(mixed $var, bool $pretty = true)
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
     * Send HTML Email
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public static function mailto(string $to, string $subject, string $message): bool
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ' . EMAIL_FROM . "\r\n";
        $headers .= 'Cc: ' . EMAIL_CC . "\r\n";

        return mail($to, $subject, $message, $headers);
    }
}
