<?php

namespace Controllers\API;

use App\Cache;
use App\Database;
use App\HandleForm;
use App\Helper;
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
use Models\Blog;

/**
 * Class BlogController
 * @package Controllers\API
 */
class BlogGrpcController implements BlogInterface
{
    // TODO: This is a temporary solution to get the blog posts. It is not working yet

    private BlogClient $client;

    /**
     * Constructor
     *
     * @param string $host
     */
    public function __construct(string $host = 'localhost:8585')
    {
        $this->client = new BlogClient($host, [
            'credentials' => ChannelCredentials::createInsecure(),
            'grpc.max_send_message_length' => 512 * 1024 * 1024,
            'grpc.max_receive_message_length' => 512 * 1024 * 1024,
        ]);
    }

    /**
     * READ all
     *
     * @param GPBEmpty $request
     * @return ListPostsResponse
     */
    public function index(GPBEmpty $request): ListPostsResponse
    {
        // Checking cache
        if (!list($response, $status) = Cache::checkCache('api.index')) {
            $client = $this->client->Index($request);

            list($response, $status) = Cache::cache(
                'api.index',
                $client->setPosts(Blog::index())
            );
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * READ one
     *
     * @param PostRequest $request
     * @return PostResponse
     */
    public function show(PostRequest $request): PostResponse
    {
        // Checking cache
        if (!list($response, $status) = Cache::checkCache('api.show.' . $request->getSlug())) {
            $client = $this->client->Show($request);
            $post = Blog::show($request->getSlug());
            $client->setId($post['id']);
            $client->setCategory($post['category']);
            $client->setTitle($post['title']);
            $client->setSlug($post['slug']);
            $client->setSubtitle($post['subtitle']);
            $client->setBody($post['body']);
            $client->setPosition($post['position']);
            $client->setCreatedAt($post['created_at']);
            $client->setUpdatedAt($post['updated_at']);

            list($response, $status) = Cache::cache(
                'api.show.' . $request->getSlug(),
                $client
            );
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * STORE
     *
     * @param PostStoreRequest $request
     * @return SuccessResponse
     */
    public function store(PostStoreRequest $request): SuccessResponse
    {
        $client = $this->client->Store($request);

        $output = HandleForm::validations([
            [$request->getTitle(), 'required', 'Please enter a title for the post!'],
            [$request->getSubtitle(), 'required', 'Please enter a subtitle for the post!'],
            [$request->getBody(), 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            list($response, $status) = $client->setMessage($output['status']);
        } elseif (Blog::store($request)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, Helper::slug($request->getTitle(), '-', false));
            }

            XmlGenerator::feed();
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            list($response, $status) = $client->setMessage('Data saved successfully!');
        } else {
            list($response, $status) = $client->setMessage('Failed during saving data!');
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * UPDATE
     *
     * @param PostUpdateRequest $request
     * @return SuccessResponse
     */
    public function update(PostUpdateRequest $request): SuccessResponse
    {
        $client = $this->client->Update($request);

        $output = HandleForm::validations([
            [$request->getTitle(), 'required', 'Please enter a title for the post!'],
            [$request->getSubtitle(), 'required', 'Please enter a subtitle for the post!'],
            [$request->getBody(), 'required', 'Please enter a body for the post!'],
        ]);

        if ($output['status'] != 'OK') {
            list($response, $status) = $client->setMessage($output['status']);
        } elseif (Blog::update($request)) {
            Database::query("SELECT * FROM posts WHERE id = :id");
            Database::bind(':id', $request->getId());

            $currentPost = Database::fetch();

            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, substr($currentPost['slug'], 0, -11));
            }

            XmlGenerator::feed();
            Cache::clearCache('blog.show.' . $currentPost['slug']);
            Cache::clearCache(['index', 'blog.index', 'api.index']);

            list($response, $status) = $client->setMessage('Data updated successfully!');
        } else {
            list($response, $status) = $client->setMessage('Failed during updating data!');
        }

        $this->checkStatus($status);
        return $response;
    }

    /**
     * DELETE
     *
     * @param PostRequest $request
     * @return SuccessResponse
     */
    public function delete(PostRequest $request): SuccessResponse
    {
        $client = $this->client->Delete($request);

        if (Blog::delete($request->getSlug())) {
            Cache::clearCache(['index', 'blog.index', 'api.index', 'blog.show.' . $request->getSlug()]);

            list($response, $status) = $client->setMessage('Data deleted successfully!');
        } else {
            list($response, $status) = $client->setMessage('Failed during deleting data!');
        }

        $this->checkStatus($status);
        return $response;
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
