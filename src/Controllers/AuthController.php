<?php

namespace Controllers;

use App\Middleware;
use Models\Auth;

class AuthController
{
    /**
     * Registeration from
     *
     * @return void
     */
    public function registerForm()
    {
        if (Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/', true, 303);
            exit();
        }
        
        render(
            'Auth/register',
            [
                'page_title' => 'Register',
                'page_subtitle' => 'Register to send post in Blog'
            ]
        );
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        parse_str($_POST['formData'], $input);
        $request = json_decode(json_encode($input));

        $output = [];
        
        if (!validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a valid Email address!';
        } elseif (!validate($request->password1, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a password!';
        } elseif ($request->password1 !== $request->password2) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please repeat password in confirmation field!';
        } elseif (!validate($request->tagline, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a tagline to introduce yourself!';
        } elseif (csrf() && Auth::register($request)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    /**
     * Login from
     *
     * @return void
     */
    public function loginForm()
    {
        if (Middleware::init(__METHOD__)) {
            header('location: ' . URL_ROOT . '/', true, 303);
            exit();
        }

        render(
            'Auth/login',
            [
                'page_title' => 'Login',
                'page_subtitle' => 'Login to send post in Blog'
            ]
        );
    }

    /**
     * Login
     *
     * @return void
     */
    public function login()
    {
        parse_str($_POST['formData'], $input);
        $request = json_decode(json_encode($input));

        $output = [];

        if (!validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a valid Email address!';
        } elseif (!validate($request->password, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter your password!';
        } elseif (csrf() && Auth::login($request)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $output = [];

        if (!Middleware::init(__METHOD__)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Authentication failed!';
        } elseif (Auth::logout()) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Logout Failed!';
        }

        echo json_encode($output);
    }
}
