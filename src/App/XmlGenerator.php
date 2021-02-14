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
    public static function rss(string $str)
    {
        $str = str_replace('<', '&lt;', $str);
        $str = str_replace('>', '&gt;', $str);
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace("'", '&#39;', $str);
        $str = str_replace("&", '&amp;', $str);
        return $str;
    }

    /**
     * Generate sitemap.xml & rss.xml via a script file
     *
     * @return void
     */
    public static function feed()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/sitemap.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/feed/index.php';
    }
}