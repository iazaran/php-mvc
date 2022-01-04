<?php

namespace Controllers\API;

use App\Cache;
use Blog\BlogClient;
use Blog\BlogInterface;
use Blog\PostDataRequest;
use Blog\PostRequest;
use Grpc\ChannelCredentials;
use Google\Protobuf\GPBEmpty;

/**
 * Class BlogController
 * @package Controllers\API
 */
class BlogGrpcController implements BlogInterface
{
    private $client;

    /**
     * Constructor
     *
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->client = new BlogClient($host, ['credentials' => ChannelCredentials::createDefault()]);
    }

    /**
     * READ all
     */
    public function index(GPBEmpty $request)
    {
        // ...
    }

    /**
     * READ one
     *
     * @param PostRequest $postRequest
     * @return \Blog\PostResponse|void
     */
    public function show(PostRequest $postRequest)
    {
        // Checking cache
        if (!$response = Cache::checkCache('api.show.' . $postRequest->getSlug())) {
            $request = $this->client->Show($postRequest);

            $response = Cache::cache(
                'api.show.' . $postRequest->getSlug(),
                $request->wait()
            );
        }

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
     */
    public function store(PostDataRequest $request)
    {
        // ...
    }

    /**
     * UPDATE
     */
    public function update(PostDataRequest $request)
    {
        // ...
    }

    /**
     * DELETE
     */
    public function delete(PostRequest $request)
    {
        // ...
    }
}
