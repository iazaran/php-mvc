<?php

namespace App;

/**
 * Class XmlGenerator
 * @package App
 */
class XmlGenerator
{
    /**
     * Convert HTML characters to characters entity reference
     *
     * @param string $str
     * @return string
     */
    public static function rss(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Generate sitemap.xml & rss.xml via a script file
     *
     * @return void
     */
    public static function feed(): void
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/sitemap.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/feed/index.php';
    }
}
