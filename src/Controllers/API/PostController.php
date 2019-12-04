<?php

namespace Controllers\API;

/**
 * Required headers
 */
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
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        $response = Post::index();

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No result!"]);
        }
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show($slug)
    {
        $response = Post::show(htmlspecialchars(strip_tags($slug)));

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No result!"]);
        }
    }

    /**
     * STORE
     *
     * @return void
     */
    public function store()
    {
        Middleware::init(__METHOD__);

        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->title, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a title for the post!"]);
        }
        if (!validate($request->subtitle, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a subtitle for the post!"]);
        }
        if (!validate($request->body, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a body for the post!"]);
        }

        if (Post::store($request)) {
            feed();
            
            http_response_code(201);
            echo json_encode(["message" => "Data saved successfully!"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Failed during saving data!"]);
        }
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
        Middleware::init(__METHOD__);

        $request = json_decode(file_get_contents('php://input'));

        if (!validate($request->title, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a title for the post!"]);
        }
        if (!validate($request->subtitle, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a subtitle for the post!"]);
        }
        if (!validate($request->body, 'required')) {
            http_response_code(422);
            echo json_encode(["message" => "Please enter a body for the post!"]);
        }

        if (Post::update($request)) {
            feed();

            http_response_code(200);
            echo json_encode(["message" => "Data updated successfully!"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Failed during updating data!"]);
        }
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return void
     */
    public function delete($slug)
    {
        Middleware::init(__METHOD__);

        if (Post::delete($slug)) {
            http_response_code(200);
            echo json_encode(["message" => "Data deleted successfully!"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Failed during deleting data!"]);
        }
    }
}
