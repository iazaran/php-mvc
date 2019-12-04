<?php

use App\Database;

/**
 * Create DB tables, indexes & relations
 *
 * @return void
 */
function createTables()
{
    /**
     * Tables' structure
     */
    $tablesStructures = [
        "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED NOT NULL,
            `email` TINYTEXT NOT NULL,
            `password` TINYTEXT NOT NULL,
            `tagline` TINYTEXT NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS `posts` (
            `id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `category` TINYTEXT NOT NULL,
            `title` TINYTEXT NOT NULL,
            `slug` TINYTEXT NOT NULL,
            `subtitle` TINYTEXT NOT NULL,
            `body` MEDIUMTEXT NOT NULL,
            `position` TINYINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    /**
     * Indexes
     */
    $tablesIndexes = [
        "ALTER TABLE `users` ADD PRIMARY KEY (`id`);",
        "ALTER TABLE `posts` ADD PRIMARY KEY (`id`);"
    ];

    /**
     * Auto increments
     */
    $tablesAutoIncrements = [
        "ALTER TABLE `users` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `posts` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;"
    ];

    /**
     * Foreign keys
     */
    $tablesForeignKeys = [
        "ALTER TABLE `posts` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);"
    ];

    foreach($tablesStructures as $tablesStructure) {
        Database::query($tablesStructure);
        Database::execute();
    }
    foreach($tablesIndexes as $tablesIndex) {
        Database::query($tablesIndex);
        Database::execute();
    }
    foreach($tablesAutoIncrements as $tablesAutoIncrement) {
        Database::query($tablesAutoIncrement);
        Database::execute();
    }
    foreach($tablesForeignKeys as $tablesForeignKey) {
        Database::query($tablesForeignKey);
        Database::execute();
    }

    /**
     * Prevent to create existed tables by commenting a command that call this function
     */
    $path_to_file = dirname(__DIR__) . '/src/routes.php';
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace("createTables();", "// createTables();", $file_contents);
    file_put_contents($path_to_file, $file_contents);
}
