<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

use App\Database;

$rssFeed = '<?xml version="1.0" encoding="utf-8"?>';
$rssFeed .= '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">';
$rssFeed .= '<channel>';
$rssFeed .= '<title>' . TITLE . '</title>';
$rssFeed .= '<link>' . URL_ROOT . '/blog/</link>';
$rssFeed .= '<language>en_US</language>';
$rssFeed .= '<generator>' . URL_ROOT . '</generator>';
$rssFeed .= '<description>' . SUBTITLE . '</description>';
$rssFeed .= '<copyright>Copyright © ' . date("Y") . ' ' . TITLE . '</copyright>';

Database::query("SELECT * FROM posts ORDER BY id DESC LIMIT :count");
Database::bind(':count', RSS_COUNTS);
$posts = Database::fetchAll();
foreach ($posts as $post) {
    $rssFeed .= '<item>';
    $rssFeed .= '<title>' . rssXml($post['title']) . '</title>';
    $rssFeed .= '<category>' . rssXml($post['category']) . '</category>';
    $rssFeed .= '<description>' . rssXml($post['subtitle']) . '</description>';
    $rssFeed .= '<link>' . URL_ROOT . '/blog/' . $post['slug'] . '</link>';
    $rssFeed .= '<pubDate>' . $post['updated_at'] . '</pubDate>';
    $rssFeed .= '<dc:creator>' . TITLE . '</dc:creator>';
    $rssFeed .= '</item>';
}

$rssFeed .= '</channel>';
$rssFeed .= '</rss>';

file_put_contents("feed/rss.xml", $rssFeed);
header("refresh:0;url=rss.xml");
