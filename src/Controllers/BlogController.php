<?php

namespace Controllers;

use App\Middleware;
use Models\Blog;

class BlogController
{
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        render(
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
    public function show($slug)
    {
        $post = Blog::show($slug);

        render(
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        render(
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        parse_str($_POST['formData'], $input);
        $request = json_decode(json_encode($input));

        $output = [];

        if (!validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        } elseif (!validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        } elseif (!validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        } elseif (csrf() && Blog::store($request)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            unset($_POST);
            feed();
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
    public function edit($slug)
    {
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $post = Blog::show($slug);

        render(
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
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        parse_str($_POST['formData'], $input);
        $request = json_decode(json_encode($input));

        $output = [];

        if (!validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a title for the post!';
        } elseif (!validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a subtitle for the post!';
        } elseif (!validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a body for the post!';
        } elseif (csrf() && Blog::update($request)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            unset($_POST);
            feed();
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
    public function delete($slug)
    {
        if (!Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $output = [];

        if (csrf() && Blog::delete($slug)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        echo json_encode($output);
    }
}
