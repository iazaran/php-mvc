<?php

namespace Controllers;

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
        Helper::render(
            'Blog/index',
            [
                'page_title' => 'Blog',
                'page_subtitle' => 'Basic PHP MVC | Blog',

                'posts' => Blog::index()
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
        $post = Blog::show($slug);

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

        $output = [];

        if (!HandleForm::validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        } elseif (!HandleForm::validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        } elseif (Helper::csrf($request->token) && Blog::store($request)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/',
                    85, Helper::slug($request->title, '-', false));
            }
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            unset($_POST);
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
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

        $output = [];

        if (!HandleForm::validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        } elseif (!HandleForm::validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        } elseif (Helper::csrf($request->token) && Blog::update($request)) {
            if (isset($_FILES['image']['type'])) {
                Database::query("SELECT * FROM posts WHERE id = :id");
                Database::bind(':id', $request->id);

                $currentPost = Database::fetch();
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85,
                    substr($currentPost['slug'], 0, -11));
            }
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
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
            $output['message'] = 'Process complete successfully!';
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        echo json_encode($output);
    }
}
