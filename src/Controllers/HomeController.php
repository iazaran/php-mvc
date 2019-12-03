<?php

namespace Controllers;

use Models\Post;

class HomeController
{
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
