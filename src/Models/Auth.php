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
            `user_token`,
            `tagline`
        ) VALUES (:email, :password, :secret, :user_token, :tagline)");
        Database::bind([
            ':email' => $request->email,
            ':password' => password_hash($request->password1, PASSWORD_DEFAULT),
            ':secret' => $request->secret,
            ':user_token' => $request->user_token,
            ':tagline' => $request->tagline,
        ]);

        if (Database::execute()) return true;
        return false;
    }

    /**
     * Email verification
     *
     * @param object $request
     * @return bool
     */
    public static function verify(object $request): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $request->email);

        if (
            !is_null(Database::fetch())
            && !is_null(Database::fetch()['user_token'])
            && $request->user_token == Database::fetch()['user_token']
        ) {
            Database::query("UPDATE users SET verified = :verified WHERE email = :email");
            Database::bind([
                ':verified' => 1,
                ':email' => $request->email,
            ]);

            if (Database::execute()) {
                setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS));
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve token
     *
     * @param object $request
     * @return mixed
     */
    public static function getSecret(object $request): mixed
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $request->email);

        if (!is_null(Database::fetch()) && !is_null(Database::fetch()['secret'])) return Database::fetch()['secret'];
        return false;
    }

    /**
     * Check for existed Email
     *
     * @param string $email
     * @return bool
     */
    public static function checkEmail(string $email): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $email);

        if (!is_null(Database::fetch())) return true;
        return false;
    }

    /**
     * Check password
     *
     * @param object $request
     * @return bool
     */
    public static function checkPassword(object $request): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $request->email);

        if (
            !is_null(Database::fetch())
            && password_verify($request->password, Database::fetch()['password'] ?? '')
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check verification
     *
     * @param string $email
     * @return bool
     */
    public static function checkVerification(string $email): bool
    {
        Database::query("SELECT * FROM users WHERE email = :email");
        Database::bind(':email', $email);

        if (
            !is_null(Database::fetch())
            && Database::fetch()['verified']
        ) {
            return true;
        }
        return false;
    }

    /**
     * Login
     *
     * @param string $email
     * @return void
     */
    public static function login(string $email): void
    {
        setcookie('loggedin', base64_encode($email), time() + (86400 * COOKIE_DAYS));
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
