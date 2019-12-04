<?php

namespace Controllers;

use App\Middleware;
use Models\Post;

class PostController
{
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        render(
            'Post/index',
            [
                'page_title' => 'Blog',
                'page_subtitle' => 'Basic PHP MVC | Blog',

                'posts' => Post::index()
            ]
        );
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show($slug)
    {
        $post = Post::show($slug);

        render(
            'Post/show',
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        render(
            'Post/create',
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));

        $output = [];
        $output['status'] = 'OK';

        if (!validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        }
        if (!validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        }
        if (!validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        }

        if (!csrf() || !Post::store($request)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        if ($output['status'] === 'OK') {
            unset($_POST);
            feed();
        }
        echo json_encode($output);
    }

    /**
     * EDIT
     *
     * @param string $slug
     * @return void
     */
    public function edit($slug)
    {
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $post = Post::show($slug);

        render(
            'Post/edit',
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));

        $output = [];
        $output['status'] = 'OK';

        if (!validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        }
        if (!validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        }
        if (!validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        }

        if (!csrf() || !Post::update($request)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        if ($output['status'] === 'OK') {
            unset($_POST);
            feed();
        }
        echo json_encode($output);
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return void
     */
    public function delete($slug)
    {
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        if (!csrf() || !Post::delete($slug)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        echo json_encode($output);
    }
}
