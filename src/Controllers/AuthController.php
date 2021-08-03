<?php

namespace Controllers;

use App\HandleForm;
use App\Helper;
use App\Middleware;
use Models\Auth;

/**
 * Class AuthController
 * @package Controllers
 */
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

        Helper::render(
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
        $request = json_decode(json_encode($_POST));
        $secret = md5(uniqid(rand(), true));
        $request->secret = $secret;

        $output = HandleForm::validations([
            [$request->email, 'email', 'Please enter a valid Email address!'],
            [$request->password1, 'required', 'Please enter a password!'],
            [$request->tagline, 'required', 'Please enter a tagline to introduce yourself!'],
        ]);

        if ($request->password1 !== $request->password2) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Please repeat password in confirmation field!';
        } elseif (Auth::existed($request->email)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'This Email registered before!';
        } elseif ($output['status'] == 'OK' && Helper::csrf($request->token) && Auth::register($request)) {
            Helper::mailto($request->email, 'Welcome to PHPMVC! Your API secret key', '<p>Hi dear friend,</p><hr /><p>This is your API secret key to access authenticated API routes:</p><p><strong>' . $secret . '</strong></p><p>Please keep it in a safe place.</p><hr /><p>Good luck,</p><p><a href="http://localhost:8080" target="_blank" rel="noopener">PPMVC</a></p>');
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

        Helper::render(
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
        $request = json_decode(json_encode($_POST));

        $output = HandleForm::validations([
            [$request->email, 'email', 'Please enter a valid Email address!'],
            [$request->password, 'required', 'Please enter a password!'],
        ]);

        if ($output['status'] == 'OK' && (!Helper::csrf($request->token) || !Auth::login($request))) {
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
