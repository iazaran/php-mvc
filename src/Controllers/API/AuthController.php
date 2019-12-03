<?php

namespace Controllers\API;

// required headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: " . URL_ROOT);
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: false");
header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 3600");

use App\Middleware;
use Models\Auth;

class AuthController
{
    // Register
    public function register()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->email, 'email')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a valid Email address!"]);
        }
        if (!validate($request->password1, 'required') && $request->password1 === $request->password2) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a password and repeat that in confirmation field!"]);
        }
        
        if (Auth::register($request)) {
            // Set response code
            http_response_code(201);

            // Return message in JSON format
            echo json_encode(["message" => "Registered successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during registration!"]);
        }
    }

    // Login
    public function login()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->email, 'email')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a valid Email address!"]);
        }
        if (!validate($request->password, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter your password!"]);
        }

        if (Auth::login($request)) {
            // Set response code
            http_response_code(200);

            // Return message in JSON format
            echo json_encode(["message" => "Login successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during login!"]);
        }
    }

    // Logout
    public function logout()
    {
        Middleware::init(__METHOD__);
        
        if (Auth::logout()) {
            // Set response code
            http_response_code(200);

            // Return message in JSON format
            echo json_encode(["message" => "Logout successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during logout!"]);
        }
    }
}
