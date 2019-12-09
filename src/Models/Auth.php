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

        if (Database::execute() && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
        return false;
    }

    /**
     * Check for existed Email
     *
     * @param string $email
     * @return bool
     */
    public static function existed($email)
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $email);

        if (!is_null(Database::fetch()['id'])) return true;
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
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $request->email);

        if (password_verify($request->password, Database::fetch()['password']) && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
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
        if (setcookie('loggedin', '', time() - (86400 * COOKIE_DAYS))) {
            unset($_COOKIE['loggedin']);
            return true;
        }
        return false;
    }
}
