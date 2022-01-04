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

use App\Cache;
use App\Database;
use App\HandleForm;
use App\Helper;
use App\Middleware;
use App\XmlGenerator;
use Models\Blog;

/**
 * Class BlogController
 * @package Controllers\API
 */
class BlogController
{
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        // Checking cache
        if (!$response = Cache::checkCache('api.index')) $response = Cache::cache('api.index', Blog::index());

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No result!']);
        }
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show(string $slug)
    {
        // Checking cache
        if (!$response = Cache::checkCache('api.show.' . $slug)) $response = Cache::cache('api.show.' . $slug, Blog::show($slug));

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No result!']);
        }
    }

    /**
     * STORE
     *
     * @return void
     */
    public function store()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(['message' => 'Authorization failed!']);
            exit();
        }

        $request = json_decode(file_get_contents('php://input'));

        $output = HandleForm::validations([
            [$request->title, 'required', 'Please enter a title for the post!'],
            [$request->subtitle, 'required', 'Please enter a subtitle for the post!'],
            [$request->body, 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            http_response_code(422);
            echo json_encode($output);
        } elseif (Blog::store($request)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, Helper::slug($request->title, '-', false));
            }

            XmlGenerator::feed();
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            http_response_code(201);
            echo json_encode(['message' => 'Data saved successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during saving data!']);
        }
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(['message' => 'Authorization failed!']);
            exit();
        }

        $request = json_decode(file_get_contents('php://input'));

        $output = HandleForm::validations([
            [$request->title, 'required', 'Please enter a title for the post!'],
            [$request->subtitle, 'required', 'Please enter a subtitle for the post!'],
            [$request->body, 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            http_response_code(422);
            echo json_encode($output);
        } elseif (Blog::update($request)) {
            Database::query("SELECT * FROM posts WHERE id = :id");
            Database::bind(':id', $request->id);

            $currentPost = Database::fetch();

            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, substr($currentPost['slug'], 0, -11));
            }

            XmlGenerator::feed();
            Cache::clearCache('blog.show.' . $currentPost['slug']);
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            http_response_code(200);
            echo json_encode(['message' => 'Data updated successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during updating data!']);
        }
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return void
     */
    public function delete(string $slug)
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(['message' => 'Authorization failed!']);
            exit();
        }

        if (Blog::delete($slug)) {
            Cache::clearCache('blog.show.' . $slug);
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            http_response_code(200);
            echo json_encode(['message' => 'Data deleted successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during deleting data!']);
        }
    }
}
