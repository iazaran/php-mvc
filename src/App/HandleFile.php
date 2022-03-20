<?php

namespace App;

/**
 * Class HandleForm
 * @package App
 */
class HandleFile
{
    /**
     * Write into file
     *
     * mode can be:
     * r    Open a file for read only.
     * w    Open a file for write only.
     * a    Open a file for write only.
     * x    Creates a new file for write only.
     * r+    Open a file for read/write.
     * w+    Open a file for read/write.
     * a+    Open a file for read/write.
     * x+    Creates a new file for read/write.
     *
     * @param string $name
     * @param string $extension
     * @param string $mode
     * @param string $text
     * @return string
     */
    public static function write(string $name, string $extension, string $mode, string $text): string
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/storage/' . $name . '.' . $extension;
        $file = fopen($path, $mode) or die('Unable to write into file!');
        fwrite($file, $text);
        fclose($file);

        return $path;
    }

    /**
     * Read from file
     *
     * @param string $name
     * @param string $extension
     * @return string
     */
    public static function read(string $name, string $extension): string
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/storage/' . $name . '.' . $extension;
        $file = fopen($path, 'r') or die('Unable to read file!');
        $text = fgets($file);
        fclose($file);

        return $text;
    }
}
