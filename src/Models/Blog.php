<?php

namespace Models;

use App\Database;
use App\Helper;
use App\UserInfo;

/**
 * Class Blog
 * @package Models
 */
class Blog
{
    /**
     * READ all
     *
     * @param integer $count
     * @return array
     */
    public static function index(int $count = 0): array
    {
        if ($count === 0) {
            Database::query("SELECT * FROM posts ORDER BY id DESC");
        } else {
            Database::query("SELECT * FROM posts ORDER BY id DESC LIMIT :count");
            Database::bind(':count', $count);
        }

        return Database::fetchAll();
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return array
     */
    public static function show(string $slug): array
    {
        Database::query("SELECT * FROM posts WHERE slug = :slug");
        Database::bind(':slug', $slug);

        return Database::fetch();
    }

    /**
     * STORE
     *
     * @param object $request
     * @return bool
     */
    public static function store(object $request): bool
    {
        $userInfo = UserInfo::current();

        Database::query("INSERT INTO posts (
            `user_id`,
            `category`,
            `title`,
            `slug`,
            `subtitle`,
            `body`,
            `position`
        ) VALUES (:user_id, :category, :title, :slug, :subtitle, :body, :position)");
        Database::bind([
            ':user_id' => $userInfo['id'],
            ':category' => $request->category ?? DEFAULT_CATEGORY,
            ':title' => $request->title,
            ':slug' => Helper::slug($request->title),
            ':subtitle' => $request->subtitle,
            ':body' => $request->body,
            ':position' => $request->position ?? 3,
        ]);

        if (Database::execute()) return true;
        return false;
    }

    /**
     * EDIT
     *
     * @param string $slug
     * @return array
     */
    public static function edit(string $slug): array
    {
        Database::query("SELECT * FROM posts WHERE slug = :slug");
        Database::bind(':slug', $slug);

        return Database::fetch();
    }

    /**
     * UPDATE
     *
     * @param object $request
     * @return bool
     */
    public static function update(object $request): bool
    {
        Database::query("UPDATE posts SET
            category = :category,
            title = :title,
            subtitle = :subtitle,
            body = :body,
            position = :position
        WHERE id = :id");
        Database::bind([
            ':category' => $request->category ?? DEFAULT_CATEGORY,
            ':title' => $request->title,
            ':subtitle' => $request->subtitle,
            ':body' => $request->body,
            ':position' => $request->position ?? 3,
            ':id' => $request->id,
        ]);

        if (Database::execute()) return true;
        return false;
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return bool
     */
    public static function delete(string $slug): bool
    {
        Database::query("DELETE FROM posts WHERE slug = :slug");
        Database::bind(':slug', $slug);

        if (Database::execute()) return true;
        return false;
    }
}
