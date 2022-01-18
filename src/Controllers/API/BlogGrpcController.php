<?php

namespace Controllers\API;

use App\Cache;
use App\Database;
use App\HandleForm;
use App\Helper;
use App\Middleware;
use App\XmlGenerator;
use Blog\BlogClient;
use Blog\BlogInterface;
use Blog\ListPostsResponse;
use Blog\PostStoreRequest;
use Blog\PostUpdateRequest;
use Blog\PostRequest;
use Blog\PostResponse;
use Blog\SuccessResponse;
use Grpc\ChannelCredentials;
use Google\Protobuf\GPBEmpty;
use Grpc\MethodDescriptor;
use Models\Blog;

/**
 * Class BlogController
 * @package Controllers\API
 */
class BlogGrpcController implements BlogInterface
{
    // TODO: Update authentication

    private BlogClient $client;

    /**
     * Constructor
     *
     * @param string $host
     */
    public function __construct(string $host = 'localhost:50051')
    {
        $this->client = new BlogClient($host, ['credentials' => ChannelCredentials::createInsecure()]);
    }

    /**
     * READ all
     *
     * @param GPBEmpty $gpbEmpty
     * @return ListPostsResponse
     */
    public function index(GPBEmpty $gpbEmpty): ListPostsResponse
    {
        // Checking cache
        if (!list($response, $status) = Cache::checkCache('api.index')) {
            $request = $this->client->Index($gpbEmpty);

            list($response, $status) = Cache::cache(
                'api.index',
                $request->wait()
            );
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * READ one
     *
     * @param PostRequest $postRequest
     * @return PostResponse
     */
    public function show(PostRequest $postRequest): PostResponse
    {
        // Checking cache
        if (!list($response, $status) = Cache::checkCache('api.show.' . $postRequest->getSlug())) {
            $request = $this->client->Show($postRequest);

            list($response, $status) = Cache::cache(
                'api.show.' . $postRequest->getSlug(),
                $request->wait()
            );
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * STORE
     *
     * @param PostStoreRequest $postStoreRequest
     * @return SuccessResponse
     */
    public function store(PostStoreRequest $postStoreRequest): SuccessResponse
    {
        $request = $this->client->Store($postStoreRequest);

        if (is_null(Middleware::init(__METHOD__))) {
            list($response, $status) = $request->wait()->setMessage('Authorization failed!');
            $this->checkStatus($status);
            return $response;
        }

        $output = HandleForm::validations([
            [$postStoreRequest->getTitle(), 'required', 'Please enter a title for the post!'],
            [$postStoreRequest->getSubtitle(), 'required', 'Please enter a subtitle for the post!'],
            [$postStoreRequest->getBody(), 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            list($response, $status) = $request->setMessage($output['status']);
        } elseif (Blog::store($postStoreRequest)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, Helper::slug($postStoreRequest->getTitle(), '-', false));
            }

            XmlGenerator::feed();
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            list($response, $status) = $request->wait()->setMessage('Data saved successfully!');
        } else {
            list($response, $status) = $request->wait()->setMessage('Failed during saving data!');
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * UPDATE
     *
     * @param PostUpdateRequest $postUpdateRequest
     * @return SuccessResponse
     */
    public function update(PostUpdateRequest $postUpdateRequest): SuccessResponse
    {
        $request = $this->client->Update($postUpdateRequest);

        if (is_null(Middleware::init(__METHOD__))) {
            list($response, $status) = $request->wait()->setMessage('Authorization failed!');
            $this->checkStatus($status);
            return $response;
        }

        $output = HandleForm::validations([
            [$postUpdateRequest->getTitle(), 'required', 'Please enter a title for the post!'],
            [$postUpdateRequest->getSubtitle(), 'required', 'Please enter a subtitle for the post!'],
            [$postUpdateRequest->getBody(), 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            list($response, $status) = $request->setMessage($output['status']);
        } elseif (Blog::update($postUpdateRequest)) {
            Database::query("SELECT * FROM posts WHERE id = :id");
            Database::bind(':id', $postUpdateRequest->getId());

            $currentPost = Database::fetch();

            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, substr($currentPost['slug'], 0, -11));
            }

            XmlGenerator::feed();
            Cache::clearCache('blog.show.' . $currentPost['slug']);
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            list($response, $status) = $request->wait()->setMessage('Data updated successfully!');
        } else {
            list($response, $status) = $request->wait()->setMessage('Failed during updating data!');
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * DELETE
     *
     * @param PostRequest $postRequest
     * @return SuccessResponse
     */
    public function delete(PostRequest $postRequest): SuccessResponse
    {
        $request = $this->client->Delete($postRequest);

        if (is_null(Middleware::init(__METHOD__))) {
            list($response, $status) = $request->wait()->setMessage('Authorization failed!');
            $this->checkStatus($status);
            return $response;
        }

        if (Blog::delete($postRequest->getSlug())) {
            Cache::clearCache('blog.show.' . $postRequest->getSlug());
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            list($response, $status) = $request->wait()->setMessage('Data deleted successfully!');
        } else {
            list($response, $status) = $request->wait()->setMessage('Failed during deleting data!');
        }

        $this->checkStatus($status);
        return $response;
    }

    // TODO: Complete this for others
    /**
     * Get the method descriptors of the service for server registration
     *
     * @return MethodDescriptor[]
     */
    public final function getMethodDescriptors(): array
    {
        return [
            '/blog.Blog/Index' => new MethodDescriptor(
                $this,
                'Index',
                \Google\Protobuf\GPBEmpty::class,
                MethodDescriptor::UNARY_CALL
            ),
        ];
    }

    /**
     * Check if status is OK
     *
     * @param mixed $status
     * @return void
     */
    private function checkStatus(mixed $status) {
        if ($status->code !== \Grpc\STATUS_OK) {
            echo "ERROR: " . $status->code . ", " . $status->details . PHP_EOL;
            exit(1);
        }
    }
}
