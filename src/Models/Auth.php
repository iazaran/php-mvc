<?php

namespace Models;

use App\Database;

/**
 * Class Auth
 * @package Models
 */
class Auth
{
    /**
     * Register
     *
     * @param object $request
     * @return bool
     */
    public static function register(object $request): bool
    {
        Database::query("INSERT INTO users (
            `email`,
            `password`,
            `secret`,
            `tagline`
        ) VALUES (:email, :password, :secret, :tagline)");
        Database::bind([
            ':email' => $request->email,
            ':password' => password_hash($request->password1, PASSWORD_DEFAULT),
            ':secret' => $request->secret,
            ':tagline' => $request->tagline,
        ]);

        if (Database::execute() && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
        return false;
    }

    /**
     * Check for existed Email
     *
     * @param string $email
     * @return bool
     */
    public static function existed(string $email): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $email);

        if (!is_null(Database::fetch()) && !is_null(Database::fetch()['id'])) return true;
        return false;
    }

    /**
     * Login
     *
     * @param object $request
     * @return bool
     */
    public static function login(object $request): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $request->email);

        if (!is_null(Database::fetch()) && password_verify($request->password, Database::fetch()['password']) && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
        return false;
    }

    /**
     * Logout
     *
     * @return bool
     */
    public static function logout(): bool
    {
        if (setcookie('loggedin', '', time() - (86400 * COOKIE_DAYS))) {
            unset($_COOKIE['loggedin']);
            return true;
        }
        return false;
    }
}
