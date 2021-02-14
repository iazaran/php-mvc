<?php

namespace Controllers;

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
        Helper::render(
            'Home/home',
            [
                'page_title' => 'Home',
                'page_subtitle' => 'Basic PHP MVC',

                'posts' => Blog::index(10)
            ]
        );
    }
}
