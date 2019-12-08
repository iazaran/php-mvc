<?php

namespace Controllers\API;

/**
 * Required headers
 */
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: ' . URL_ROOT);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 3600');

use App\Middleware;
use Models\Auth;

class AuthController
{
    /**
     * Register an user
     *
     * @return void
     */
    public function register()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->email, 'email')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a valid Email address!']);
        } elseif (!validate($request->password1, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a password!']);
        } elseif ($request->password1 !== $request->password2) {
            http_response_code(422);
            echo json_encode(['message' => 'Please repeat password in confirmation field!']);
        } elseif (!validate($request->tagline, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a tagline to introduce yourself!']);
        } elseif (Auth::register($request)) {
            http_response_code(201);
            echo json_encode(['message' => 'Registered successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during registration!']);
        }
    }

    /**
     * Login an user
     *
     * @return void
     */
    public function login()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->email, 'email')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a valid Email address!']);
        } elseif (!validate($request->password, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter your password!']);
        } elseif (Auth::login($request)) {
            http_response_code(200);
            echo json_encode(['message' => 'Login successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during login!']);
        }
    }

    /**
     * Logout an user
     *
     * @return void
     */
    public function logout()
    {
        Middleware::init(__METHOD__);
        
        if (Auth::logout()) {
            http_response_code(200);
            echo json_encode(['message' => 'Logout successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during logout!']);
        }
    }
}
