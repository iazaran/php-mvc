<?php

namespace App;

/**
 * Class CsvGenerator
 * @package App
 */
class CsvGenerator
{
    /**
     * Create a CSV file from a specific table on DB
     *
     * @param string $tableName
     * @return bool|void
     */
    public static function exportCSV(string $tableName)
    {
        // Validate table name to prevent SQL injection (only allow alphanumeric and underscores)
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName)) {
            return false;
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/storage/' . $tableName . '.csv';
        $file = fopen($path, 'w') or die('Unable to write into file!');

        $sql = "DESCRIBE $tableName";
        Database::query($sql);
        Database::execute();

        $header = Database::fetchColumn();

        fputcsv($file, $header);

        $sql = "SELECT * FROM $tableName";
        Database::query($sql);
        Database::execute();

        $rows = Database::fetchAll();
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }

        return true;
    }
}
