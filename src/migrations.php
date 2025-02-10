<?php

require_once __DIR__ . '/migrate.php';

use App\Database;

/**
 * Create DB tables, indexes & relations
 *
 * @return void
 */
function createTables(): void
{
    initializeMigrationsTable();

    applyMigration('create_users_table', function () {
        $query = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `email` TINYTEXT NOT NULL,
            `password` TINYTEXT NOT NULL,
            `secret` TINYTEXT NOT NULL,
            `user_token` TINYTEXT NOT NULL,
            `verified` TINYINT UNSIGNED NOT NULL DEFAULT 0,
            `tagline` TINYTEXT NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        Database::query($query);
        Database::execute();
    });

    applyMigration('create_posts_table', function () {
        $query = "CREATE TABLE IF NOT EXISTS `posts` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` INT UNSIGNED NOT NULL,
            `category` TINYTEXT NOT NULL,
            `title` TINYTEXT NOT NULL,
            `slug` TINYTEXT NOT NULL,
            `subtitle` TINYTEXT NOT NULL,
            `body` MEDIUMTEXT NOT NULL,
            `position` TINYINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        Database::query($query);
        Database::execute();

        // You might also include the foreign key creation here.
        $fkQuery = "ALTER TABLE `posts` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);";
        Database::query($fkQuery);
        Database::execute();
    });

    // Add your new migrations here

    /**
     * Prevent to create existed tables by commenting a command that call this function
     */
    $path_to_file = dirname(__DIR__) . '/src/routes.php';
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace("createTables();", "// createTables();", $file_contents);
    file_put_contents($path_to_file, $file_contents);
}
