<?php

namespace Models;

use App\Database;
use App\Helper;

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

        $user = Database::fetch();
        if (
            !empty($user)
            && !empty($user['user_token'])
            && $request->user_token == $user['user_token']
        ) {
            Database::query("UPDATE users SET verified = :verified WHERE email = :email");
            Database::bind([
                ':verified' => 1,
                ':email' => $request->email,
            ]);

            if (Database::execute()) {
                Helper::setAuthCookie($request->email);
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

        $user = Database::fetch();
        if (!empty($user) && !empty($user['secret'])) return $user['secret'];
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

        $user = Database::fetch();
        if (!empty($user)) return true;
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

        $user = Database::fetch();
        if (
            !empty($user)
            && password_verify($request->password, $user['password'] ?? '')
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

        $user = Database::fetch();
        if (
            !empty($user)
            && $user['verified']
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
        Helper::setAuthCookie($email);
    }

    /**
     * Logout
     *
     * @return bool
     */
    public static function logout(): bool
    {
        if (setcookie('loggedin', '', [
            'expires' => time() - (86400 * COOKIE_DAYS),
            'path' => '/',
            'secure' => parse_url(URL_ROOT, PHP_URL_SCHEME) === 'https',
            'httponly' => true,
            'samesite' => 'Lax',
        ])) {
            unset($_COOKIE['loggedin']);
            return true;
        }
        return false;
    }
}
