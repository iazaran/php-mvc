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
        if (!is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT, true, 303);
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
        $secret = md5(uniqid(rand(), true));
        parse_str($_POST['formData'], $input);
        $input['secret'] = $secret;
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
        } elseif (Auth::existed($request->email)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'This Email registered before!';
        } elseif (csrf($request->token) && Auth::register($request)) {
            mailto($request->email, 'Welcome to PHPMVC! Your API secret key', '<p>Hi dear friend,</p><hr /><p>This is your API secret key to access authenticated API routes:</p><p><strong>' . $secret . '</strong></p><p>Please keep it in a safe place.</p><hr /><p>Good luck,</p><p><a href="http://localhost:8080" target="_blank" rel="noopener">PPMVC</a></p>');

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
        if (!is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT, true, 303);
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
        } elseif (csrf($request->token) && Auth::login($request)) {
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

        if (is_null(Middleware::init(__METHOD__))) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Authentication failed!';
        } elseif (Auth::logout()) {
            header('location: ' . URL_ROOT, true, 303);
            exit();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Logout Failed!';
        }

        echo json_encode($output);
    }
}
