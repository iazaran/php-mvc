<?php

namespace App;

/**
 * Class UserInfo
 * @package App
 */
class UserInfo
{
    /**
     * Return current user information
     *
     * @return array|null
     */
    public static function current(): ?array
    {
        if (isset($_COOKIE['loggedin'])) {
            $email = Helper::authCookieEmail($_COOKIE['loggedin']);
            if ($email === null) {
                return null;
            }

            Database::query("SELECT * FROM users WHERE email = :email");
            Database::bind(':email', $email);

            $user = Database::fetch();
            return $user ?: null;
        }
        return null;
    }

    /**
     * Return selected user information
     *
     * @param $id
     * @return array
     */
    public static function info(int $id): array|false
    {
        Database::query("SELECT * FROM users WHERE id = :id");
        Database::bind(':id', $id);

        return Database::fetch();
    }
}
