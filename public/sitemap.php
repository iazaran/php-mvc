<?php

/**
 * This script can produce a sitemap.xml in public folder
 * This one call by XmlGenerator::feed() function after
 * each post creation or updating
 */

header("Content-Type: application/rss+xml; charset=UTF-8");

use App\Database;

$sitemap = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
$sitemap .= '
	<url>
		<loc>' . URL_ROOT . '</loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>';

/**
 * TODO: Change to your blog address
 */
$sitemap .= '
	<url>
		<loc>' . URL_ROOT . '/blog</loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>';

Database::query("SELECT * FROM posts ORDER BY id DESC LIMIT :count");
Database::bind(':count', RSS_COUNTS);
$posts = Database::fetchAll();
foreach ($posts as $post) {
    /**
     * TODO: Change to your blog address and also your posts' routes
     */
    $sitemap .= '
		<url>
			<loc>' . URL_ROOT . '/blog/' . $post['slug'] . '</loc>
			<lastmod>' . gmdate('Y-m-d\TH:i:s+00:00', strtotime($post['updated_at'])) . '</lastmod>
			<changefreq>daily</changefreq>
			<priority>0.8</priority>
		</url>';
}

$sitemap .= '</urlset>';
file_put_contents(dirname(__DIR__) . '/public/sitemap.xml', $sitemap);
