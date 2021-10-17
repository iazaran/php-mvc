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
        $user_token = md5(uniqid(rand(), true));
        $request->user_token = $user_token;

        $output = HandleForm::validations([
            [$request->email, 'email', 'Please enter a valid Email address!'],
            [$request->password1, 'required', 'Please enter a password!'],
            [$request->tagline, 'required', 'Please enter a tagline to introduce yourself!'],
        ]);

        if ($output['status'] == 'OK') {
            if ($request->password1 !== $request->password2) {
                $output['status'] = 'ERROR';
                $output['message'] = 'Please repeat password in confirmation field!';
            } elseif (Auth::existed($request->email)) {
                $output['status'] = 'ERROR';
                $output['message'] = 'This Email registered before!';
            } elseif (Helper::csrf($request->token) && Auth::register($request)) {
                Helper::mailto($request->email, 'Welcome to PHPMVC! Email Verification', '<p>Hi dear friend,</p><hr /><p>Please click on this link to verify your email</p><hr /><p>Good luck,</p><p><a href="http://localhost:8080/verify?email=' . $request->email . '&user_token=' . $user_token . '" target="_blank" rel="noopener">Verify your email at PHPMVC</a></p>');

                setcookie('message', 'Verification has been sent to your email, please check your inbox.', time() + 60);
            } else {
                $output['status'] = 'ERROR';
                $output['message'] = 'There is an error! Please try again.';
            }
        }

        unset($_POST);
        echo json_encode($output);
    }

    /**
     * Email verification
     *
     * @return void
     */
    public function verify()
    {
        $request = json_decode(json_encode($_GET));

        if (Auth::verify($request) && $secret = Auth::getSecret($request)) {
            Helper::mailto($request->email, 'PHPMVC! Your API secret key', '<p>Hi dear friend,</p><hr /><p>This is your API secret key to access authenticated API routes:</p><p><strong>' . $secret . '</strong></p><p>Please keep it in a safe place.</p><hr /><p>Good luck,</p><p><a href="http://localhost:8080" target="_blank" rel="noopener">PHPMVC</a></p>');

            setcookie('message', 'Thanks for verification. Now you can publish a post!', time() + 60);

            header('location: ' . URL_ROOT, true, 303);
            exit();
        } else {
            Helper::render(
                'Auth/verify',
                [
                    'page_title' => 'Email Verification',
                    'page_subtitle' => 'Verification process failed! Please register again with a new email address.'
                ]
            );
        }
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
