<?php

namespace Controllers;

use App\Cache;
use App\Database;
use App\HandleForm;
use App\Helper;
use App\Middleware;
use App\XmlGenerator;
use Models\Blog;

/**
 * Class BlogController
 * @package Controllers
 */
class BlogController
{
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        // Checking cache
        if (!$posts = Cache::checkCache('blog.index')) $posts = Cache::cache('blog.index', Blog::index());

        Helper::render(
            'Blog/index',
            [
                'page_title' => 'Blog',
                'page_subtitle' => 'Basic PHP MVC | Blog',

                'posts' => $posts
            ]
        );
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show(string $slug)
    {
        // Checking cache
        if (!$post = Cache::checkCache('blog.show.' . $slug)) $post = Cache::cache('blog.show.' . $slug, Blog::show($slug));

        Helper::render(
            'Blog/show',
            [
                'page_title' => $post['title'],
                'page_subtitle' => $post['subtitle'],

                'post' => $post
            ]
        );
    }

    /**
     * CREATE
     *
     * @return void
     */
    public function create()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        Helper::render(
            'Blog/create',
            [
                'page_title' => 'Create Post',
                'page_subtitle' => 'Create new post in Blog'
            ]
        );
    }

    /**
     * STORE
     *
     * @return void
     */
    public function store()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));

        $output = HandleForm::validations([
            [$request->title, 'required', 'Please enter a title for the post!'],
            [$request->subtitle, 'required', 'Please enter a subtitle for the post!'],
            [$request->body, 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] == 'OK') {
            if (Helper::csrf($request->token) && Blog::store($request)) {
                if (isset($_FILES['image']['type'])) {
                    HandleForm::upload($_FILES['image'], ['jpeg', 'jpg', 'png'], 5000000, '../public/assets/images/', 85, Helper::slug($request->title, '-', false));
                }

                unset($_POST);
                XmlGenerator::feed();
                Cache::clearCache(['index', 'blog.index', 'api.index']);
            } else {
                $output['status'] = 'ERROR';
                $output['message'] = 'There is an error! Please try again.';
            }
        }

        echo json_encode($output);
    }

    /**
     * EDIT
     *
     * @param string $slug
     * @return void
     */
    public function edit(string $slug)
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $post = Blog::show($slug);

        Helper::render(
            'Blog/edit',
            [
                'page_title' => 'Edit ' . $post['title'],
                'page_subtitle' => $post['subtitle'],

                'post' => $post
            ]
        );
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));

        $output = HandleForm::validations([
            [$request->title, 'required', 'Please enter a title for the post!'],
            [$request->subtitle, 'required', 'Please enter a subtitle for the post!'],
            [$request->body, 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] == 'OK') {
            if ($output['status'] == 'OK' && Helper::csrf($request->token) && Blog::update($request)) {
                Database::query("SELECT * FROM posts WHERE id = :id");
                Database::bind(':id', $request->id);

                $currentPost = Database::fetch();

                if (isset($_FILES['image']['type'])) {
                    HandleForm::upload($_FILES['image'], ['jpeg', 'jpg', 'png'], 5000000, '../public/assets/images/', 85, substr($currentPost['slug'], 0, -11));
                }

                unset($_POST);
                XmlGenerator::feed();
                Cache::clearCache('blog.show.' . $currentPost['slug']);
                Cache::clearCache(['index', 'blog.index', 'api.index']);
            } else {
                $output['status'] = 'ERROR';
                $output['message'] = 'There is an error! Please try again.';
            }
        }

        echo json_encode($output);
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return void
     */
    public function delete(string $slug)
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $output = [];

        if (Blog::delete($slug)) {
            $output['status'] = 'OK';
            $output['message'] = 'The process has been completed successfully!';

            XmlGenerator::feed();
            Cache::clearCache('blog.show.' . $slug);
            Cache::clearCache(['index', 'blog.index', 'api.index']);
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        echo json_encode($output);
    }
}
