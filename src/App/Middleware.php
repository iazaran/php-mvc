<?php

namespace App;

class Middleware
{
    /**
     * Define your methods' custom middlewares for the web routes
     *
     * @var array
     */
    private static $WEBmiddlewares = [
        'PostController@create' => 'WEBauthentication',
        'PostController@store' => 'WEBauthentication',
        'PostController@edit' => 'WEBauthentication',
        'PostController@update' => 'WEBauthentication',
        'PostController@delete' => 'WEBauthentication',

        'AuthController@logout' => 'WEBauthentication',
    ];

    /**
     * Define your methods' custom middlewares for the api routes
     *
     * @var array
     */
    private static $APImiddlewares = [
        'PostController@store' => 'APIauthentication',
        'PostController@update' => 'APIauthentication',
        'PostController@delete' => 'APIauthentication',

        'AuthController@logout' => 'APIauthentication',
    ];

    /**
     * Assign related middleware method to the each controller's method
     *
     * @param string class method name $classMethod
     * @return void
     */
    public static function init($classMethod)
    {
        $classMethod = str_replace('Controllers\\', '', $classMethod);
        $classMethod = str_replace('@', '::', $classMethod);
        if (strpos($classMethod, 'API\\') !== false) {
            $classMethod = str_replace('API\\', '', $classMethod);
            if (array_key_exists($classMethod, self::$APImiddlewares)) self::{self::$APImiddlewares[$classMethod]}();
        } else {
            if (array_key_exists($classMethod, self::$WEBmiddlewares)) self::{self::$WEBmiddlewares[$classMethod]}();
        }
    }

    /**
     * Check loggedin user
     *
     * @return bool
     */
    private static function WEBauthentication()
    {
        if (isset($_COOKIE['loggedin'])) {
            $email = base64_decode($_COOKIE['loggedin']);

            Database::query("SELECT * FROM users WHERE email = :email");
            Database::bind(':email', $email);

            if (Database::rowCount() !== 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check access token from header
     *
     * @return void
     */
    private static function APIauthentication()
    {
        $header = self::getAuthorizationHeader();
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                if ($matches[1] !== APP_SECRET) {
                    http_response_code(403);
                    echo json_encode(["message" => "Authorization failed!"]);
                }
            } else {
                http_response_code(403);
                echo json_encode(["message" => "Authorization failed!"]);
            }
        } else {
            http_response_code(403);
            echo json_encode(["message" => "Authorization failed!"]);
        }
    }

    /**
     * Get access token from header
     *
     * @return string
     */
    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            /**
             * Nginx or fast CGI
             */
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            /**
             * @var array
             */
            $requestHeaders = apache_request_headers();

            /**
             * Server-side fix for bug in old Android versions
             * A nice side-effect of this fix means we don't
             * care about capitalization for Authorization
             */
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}
