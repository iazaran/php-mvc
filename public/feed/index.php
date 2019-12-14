<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

use App\Database;

$rssfeed = '<?xml version="1.0" encoding="utf-8"?>';
$rssfeed .= '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>' . TITLE . '</title>';
$rssfeed .= '<link>' . URL_ROOT . '/blog/</link>';
$rssfeed .= '<language>en_US</language>';
$rssfeed .= '<generator>' . URL_ROOT . '</generator>';
$rssfeed .= '<description>' . SUBTITLE . '</description>';
$rssfeed .= '<copyright>Copyright © ' . date("Y") . ' ' . TITLE . '</copyright>';

Database::query("SELECT * FROM posts ORDER BY id DESC LIMIT :count");
Database::bind(':count', RSS_COUNTS);
$posts = Database::fetchAll();
foreach ($posts as $post) {
	$rssfeed .= '<item>';
	$rssfeed .= '<title>' . rssXml($post['title']) . '</title>';
	$rssfeed .= '<category>' . rssXml($post['category']) . '</category>';
	$rssfeed .= '<description>' . rssXml($post['subtitle']) . '</description>';
	$rssfeed .= '<link>' . URL_ROOT . '/blog/' . $post['slug'] . '</link>';
	$rssfeed .= '<pubDate>' . $post['updated_at'] . '</pubDate>';
	$rssfeed .= '<dc:creator>' . TITLE . '</dc:creator>';
	$rssfeed .= '</item>';
}

$rssfeed .= '</channel>';
$rssfeed .= '</rss>';

file_put_contents("feed/rss.xml", $rssfeed);
header("refresh:0;url=rss.xml");
