<?php

namespace Models;

use App\Database;

class Auth
{
    /**
     * Register
     *
     * @param object $request
     * @return bool
     */
    public static function register($request)
    {
        Database::query("INSERT INTO users (
            `email`,
            `password`,
            `tagline`
        ) VALUES (:email, :password, :tagline)");
        Database::bind(':email', $request->email);
        Database::bind(':password', password_hash($request->password1, PASSWORD_DEFAULT));
        Database::bind(':tagline', $request->tagline);

        if (Database::execute() && setcookie('loggedin', base64_encode($request->email), time() + (86400 * 180))) return true;
        return false;
    }

    /**
     * Login
     *
     * @param object $request
     * @return bool
     */
    public static function login($request)
    {
        Database::query("SELECT * FROM users WHERE email = :email AND password = :password");
        Database::bind(':email', $request->email);
        Database::bind(':password', password_hash($request->password, PASSWORD_DEFAULT));

        if (Database::rowCount() === 1 && setcookie('loggedin', base64_encode($request->email), time() + (86400 * 180))) return true;
        return false;
    }

    /**
     * Logout
     *
     * @param object $request
     * @return bool
     */
    public static function logout()
    {
        if (setcookie('loggedin', '', time() - (86400 * 180))) {
            unset($_COOKIE['loggedin']);
            return true;
        }
        return false;
    }
}
