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
            Database::query("SELECT * FROM users WHERE email = :email");
            Database::bind(':email', base64_decode($_COOKIE['loggedin']));

            return Database::fetch();
        }
        return null;
    }

    /**
     * Return selected user information
     *
     * @param $id
     * @return array
     */
    public static function info($id): array
    {
        Database::query("SELECT * FROM users WHERE id = :id");
        Database::bind(':id', $id);

        return Database::fetch();
    }
}