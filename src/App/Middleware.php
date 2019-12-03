<?php

namespace App;

class Middleware
{
    // Define your methods' custom middlewares
    private static $WEBmiddlewares = [
        // Web middlewares
        'PostController@create' => 'WEBauthentication',
        'PostController@store' => 'WEBauthentication',
        'PostController@edit' => 'WEBauthentication',
        'PostController@update' => 'WEBauthentication',
        'PostController@delete' => 'WEBauthentication',

        'AuthController@logout' => 'WEBauthentication',
    ];
    private static $APImiddlewares = [
        // API middlewares
        'PostController@store' => 'APIauthentication',
        'PostController@update' => 'APIauthentication',
        'PostController@delete' => 'APIauthentication',

        'AuthController@logout' => 'APIauthentication',
    ];

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

    // Check loggedin user
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
     * get access token from header
     */
    private static function APIauthentication()
    {
        $header = self::getAuthorizationHeader();
        // Get the access token from the header
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                if ($matches[1] !== 'okmIJNuhbYGVtfcRDXesz098&^%432') {
                    // Set response code
                    http_response_code(403);

                    // Return error message in JSON format
                    echo json_encode(["message" => "Authorization failed!"]);
                }
            } else {
                // Set response code
                http_response_code(403);

                // Return error message in JSON format
                echo json_encode(["message" => "Authorization failed!"]);
            }
        } else {
            // Set response code
            http_response_code(403);

            // Return error message in JSON format
            echo json_encode(["message" => "Authorization failed!"]);
        }
    }

    /** 
     * Get header Authorization
     */
    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}
