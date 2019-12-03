<?php

namespace Controllers;

use App\Middleware;
use Models\Auth;

class AuthController
{
    // Registeration from
    public function registerForm()
    {
        render(
            'Auth/register',
            [
                'page_title' => 'Register',
                'page_subtitle' => 'Register to send post in Blog'
            ]
        );
    }

    // Register
    public function register()
    {
        $request = json_decode(json_encode($_POST));

        $output = [];
        $output['status'] = 'OK';
        
        if (!validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a valid Email address!';
        }
        if (!validate($request->password1, 'required') && $request->password1 === $request->password2) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a password and repeat that in confirmation field!';
        }

        if (!csrf() || !Auth::register($request)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    // Login from
    public function loginForm()
    {
        render(
            'Auth/login',
            [
                'page_title' => 'Login',
                'page_subtitle' => 'Login to send post in Blog'
            ]
        );
    }

    // Login
    public function login()
    {
        $request = json_decode(json_encode($_POST));

        $output = [];
        $output['status'] = 'OK';

        if (!validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter a valid Email address!';
        }
        if (!validate($request->password, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please enter your password!';
        }

        if (!csrf() || !Auth::login($request)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please check your email & password again.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    // Logout
    public function logout()
    {
        $output = [];
        $output['status'] = 'OK';

        if (!Middleware::init(__METHOD__)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Authentication failed!';
        }

        if (!Auth::logout()) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Logout Failed!';
        }

        echo json_encode($output);
    }
}
