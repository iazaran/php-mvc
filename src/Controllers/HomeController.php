<?php

namespace Controllers;

use App\Cache;
use App\Event;
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
        // Fire an event as a sample
        Event::listen('homeStarter', function($param) {
            // Log fired event by logger as a sample
            Helper::log('App started and ' . $param . ' event has been fired!');
        });
        Event::trigger('homeStarter', 'Home Starter');

        // Checking cache
        if (!$posts = Cache::checkCache('index')) $posts = Cache::cache('index', Blog::index(10));

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
