<?php

namespace Controllers;

use Models\Post;

class HomeController
{
    /**
     * Home page rendering to show recent posts
     *
     * @return void
     */
    public function index()
    {
        render(
            'Home/home',
            [
                'page_title' => 'Home',
                'page_subtitle' => 'Basic PHP MVC',

                'posts' => Post::index(10)
            ]
        );
    }
}
