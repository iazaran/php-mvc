<?php

namespace Models;

use App\Database;

class Blog
{
    /**
     * READ all
     *
     * @param integer $count
     * @return array
     */
    public static function index($count = 0)
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
    public static function show($slug)
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
    public static function store($request)
    {
        $userInfo = currentUser();
        Database::query("INSERT INTO posts (
            `user_id`,
            `category`,
            `title`,
            `slug`,
            `subtitle`,
            `body`,
            `position`
        ) VALUES (:user_id, :category, :title, :slug, :subtitle, :body, :position)");
        Database::bind(':user_id', $userInfo['id']);
        Database::bind(':category', $request->category || DEFAULT_CATEGORY);
        Database::bind(':title', $request->title);
        Database::bind(':slug', slug($request->title));
        Database::bind(':subtitle', $request->subtitle);
        Database::bind(':body', $request->body);
        Database::bind(':position', $request->position || 3);

        if (Database::execute()) return true;
        return false;
    }

    /**
     * EDIT
     *
     * @param string $slug
     * @return array
     */
    public static function edit($slug)
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
    public static function update($request)
    {
        Database::query("UPDATE posts SET (
            category = :category,
            title = :title,
            subtitle = :subtitle,
            body = :body,
            position = :position
        ) WHERE id = :id");
        Database::bind(':category', $request->category || DEFAULT_CATEGORY);
        Database::bind(':title', $request->title);
        Database::bind(':subtitle', $request->subtitle);
        Database::bind(':body', $request->body);
        Database::bind(':position', $request->position || 3);
        Database::bind(':id', $request->id);

        if (Database::execute()) return true;
        return false;
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return bool
     */
    public static function delete($slug)
    {
        Database::query("DELETE FROM posts WHERE slug = :slug");
        Database::bind(':slug', $slug);

        if (Database::execute()) return true;
        return false;
    }
}
