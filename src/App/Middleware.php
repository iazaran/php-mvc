<?php

namespace App;

/**
 * Class Middleware
 * @package App
 */
class Middleware
{
    /**
     * Define your methods' custom middlewares for the web routes
     *
     * @var array
     */
    private static array $WEBmiddlewares = [
        'BlogController@create' => 'WEBauthentication',
        'BlogController@store' => 'WEBauthentication',
        'BlogController@edit' => 'WEBauthentication',
        'BlogController@update' => 'WEBauthentication',
        'BlogController@delete' => 'WEBauthentication',

        'AuthController@logout' => 'WEBauthentication',
    ];

    /**
     * Define your methods' custom middlewares for the api routes
     *
     * @var array
     */
    private static array $APImiddlewares = [
        'BlogController@store' => 'APIauthentication',
        'BlogController@update' => 'APIauthentication',
        'BlogController@delete' => 'APIauthentication',

        'AuthController@logout' => 'APIauthentication',
    ];

    /**
     * Assign related middleware method to the each controller's method
     *
     * @param string $classMethod class method name $classMethod
     * @return mixed
     */
    public static function init(string $classMethod): mixed
    {
        $classMethod = str_replace('Controllers\\', '', $classMethod);
        $classMethod = str_replace('::', '@', $classMethod);
        if (str_contains($classMethod, 'API\\')) {
            $classMethod = str_replace('API\\', '', $classMethod);
            if (array_key_exists($classMethod, self::$APImiddlewares)) return self::{self::$APImiddlewares[$classMethod]}();
        } else {
            if (array_key_exists($classMethod, self::$WEBmiddlewares)) return self::{self::$WEBmiddlewares[$classMethod]}();
        }
        return null;
    }

    /**
     * Check logged in user
     *
     * @return mixed
     */
    private static function WEBauthentication(): mixed
    {
        if (isset($_COOKIE['loggedin'])) {
            $email = base64_decode($_COOKIE['loggedin']);

            Database::query("SELECT * FROM users WHERE email = :email");
            Database::bind(':email', $email);

            if (!is_null(Database::fetch()['id'])) return Database::fetch()['id'];
        }
        return null;
    }

    /**
     * Check access token from header
     * Client should use this `Bearer qwaeszrdxtfcygvuhbijnokmpl0987654321` for Authorization in header
     * That APP_SECRET can be set in ENV or generate after a login or ...
     *
     * @return mixed
     */
    private static function APIauthentication(): mixed
    {
        $header = self::getAuthorizationHeader();
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                Database::query("SELECT * FROM users WHERE secret = :secret");
                Database::bind(':secret', $matches[1]);

                if (!is_null(Database::fetch()['id'])) {
                    setcookie('loggedin', base64_encode(Database::fetch()['email']), time() + (86400 * COOKIE_DAYS));
                    return Database::fetch()['id'];
                }
            }
        }
        return null;
    }

    /**
     * Get access token from header
     *
     * @return string|null
     */
    private static function getAuthorizationHeader(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            /**
             * Nginx or fast CGI
             */
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            /**
             * @var array $requestHeaders
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
