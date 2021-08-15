<?php

namespace Controllers;

use App\Cache;
use App\Helper;
use Models\Blog;

/**
 * Class HomeController
 * @package Controllers
 */
class HomeController
{
    /**
     * Home page rendering to show recent posts
     *
     * @return void
     */
    public function index()
    {
        // Checking cache
        if ($posts = Cache::checkCache('index')) Cache::cache('index', Blog::index(10));

        Helper::render(
            'Home/home',
            [
                'page_title' => 'Home',
                'page_subtitle' => 'Basic PHP MVC',

                'posts' => $posts,
            ]
        );
    }
}
