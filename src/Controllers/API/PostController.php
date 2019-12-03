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
use Models\Post;

class PostController
{
    // READ all
    public function index()
    {
        $response = Post::index();

        if (count($response) > 0) {
            // Set response code
            http_response_code(200);

            // Return data in JSON format
            echo json_encode($response);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "No result!"]);
        }
    }

    // READ one
    public function show($slug)
    {
        $response = Post::show(htmlspecialchars(strip_tags($slug)));

        if (count($response) > 0) {
            // Set response code
            http_response_code(200);

            // Return data in JSON format
            echo json_encode($response);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "No result!"]);
        }
    }

    // STORE
    public function store()
    {
        Middleware::init(__METHOD__);

        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->title, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a title for the post!"]);
        }
        if (!validate($request->subtitle, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a subtitle for the post!"]);
        }
        if (!validate($request->body, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a body for the post!"]);
        }

        if (Post::store($request)) {
            feed();
            
            // Set response code
            http_response_code(201);

            // Return message in JSON format
            echo json_encode(["message" => "Data saved successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during saving data!"]);
        }
    }

    // UPDATE
    public function update()
    {
        Middleware::init(__METHOD__);

        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->title, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a title for the post!"]);
        }
        if (!validate($request->subtitle, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a subtitle for the post!"]);
        }
        if (!validate($request->body, 'required')) {
            // Set response code
            http_response_code(422);

            // Return message in JSON format
            echo json_encode(["message" => "Please enter a body for the post!"]);
        }

        if (Post::update($request)) {
            feed();

            // Set response code
            http_response_code(200);

            // Return message in JSON format
            echo json_encode(["message" => "Data updated successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during updating data!"]);
        }
    }

    // DELETE
    public function delete($slug)
    {
        Middleware::init(__METHOD__);

        if (Post::delete($slug)) {
            // Set response code
            http_response_code(200);

            // Return message in JSON format
            echo json_encode(["message" => "Data deleted successfully!"]);
        } else {
            // Set response code
            http_response_code(404);

            // Return error message in JSON format
            echo json_encode(["message" => "Failed during deleting data!"]);
        }
    }
}
