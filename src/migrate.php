<?php

use App\Database;

/**
 * Initialize the migrations table if it doesn't exist.
 */
function initializeMigrationsTable(): void
{
    $query = "CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    Database::query($query);
    Database::execute();
}

/**
 * Applies a migration only if it has not been applied before.
 *
 * @param string   $migrationName
 * @param callable $migrationFunction
 */
function applyMigration(string $migrationName, callable $migrationFunction): void
{
    // Check if migration already exists.
    $checkQuery = "SELECT COUNT(*) as count FROM migrations WHERE migration = :migration";
    Database::query($checkQuery);
    Database::bind(':migration', $migrationName);
    $result = Database::fetch();
    
    if ((int)$result['count'] === 0) {
        // Run the migration.
        $migrationFunction();

        // Record this migration as executed.
        Database::query(
            "INSERT INTO migrations (migration, executed_at) VALUES (:migration, NOW())"
        );
        Database::bind(':migration', $migrationName);
        Database::execute();
    }
}
