### Dockerized Pure PHP Composer based MVC Framework

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8d638fe590dd4e68b9ce4ac9a7517e3d)](https://app.codacy.com/gh/iazaran/php-mvc/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

> This project tries to cover some PHP features in a simple MVC structure with minimum installed composer packages. Then developers can use packages for specific requirements. Please add your ideas in Discussions, ask features or report bugs in issues.

#### Features:
**List of features related with structure**
- **public**
Contains the index.php file, to start application and configures auto-loading. Different server configurations added into this directory too. Finally, you can find the Sitemap generator that runs after creation or updating a post.
- **public/assets**
Assets can contain your media files like images, audios & videos.
- **public/css** & **public/js**
Contains the styles & scripts _(After changes on these files, you can use minifier script to update minified versions, just run `docker compose exec php-mvc-app php minifier.php`)_
- **public/feed**
There is a RSS generator in here and runs after creation or updating a post.
- **grpc**
A simple router for distribute the requests to different services. It is not working yet and I created an issue for it ⚠ and if you have an idea or a two-way solution only by PHP for both server and client sides, please add your solution in there. The Docker gRPC/protobuf build steps are commented out for now because they make image builds much slower while this feature is incomplete; uncomment the optional gRPC lines in `docker/Dockerfile` before working on it.
- **websocket**
A WebSocket sample. You can open /websocket route in 2 tabs and test the websocket connection.
- **src**
Contains database migrations and routes. Migrations are run from the console command instead of from web routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware, Router & ...
- **src/Console**
Contains CLI scripts and scheduled jobs. Use `src/Console/migrate.php` to create or update database tables. Recurring scripts should be registered in `/commands.php` with custom timing; they will run by an independent service in docker compose.
- **src/Controllers**
Controllers related with your routes separated for web and API. API folder includes both RESTful API and gRPC API. If you want use gRPC _(gRPC client & server are not completed, and I ignored them for now. So be careful about the bugs in gRPC ⚠ and if you have an idea or a two-way solution only by PHP, please make a new discussion/issue/PR)_, uncomment the optional gRPC/protobuf build steps in `docker/Dockerfile` first and require `grpc/grpc`, `google/protobuf`, and `ext-grpc` in `composer.json`. You can find .proto file in API folder. Updating it will need to generate PHP codes again by
```
docker compose exec php-mvc-app protoc -I=src/Controllers/API \
    src/Controllers/API/blog.proto \
    --php_out=src/Controllers/API/gRPC \
    --grpc_out=src/Controllers/API/gRPC \
    --plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin
```
- **src/Models**
Models related with controllers' DB queries & requirements.
- **src/Views**
Simple PHP files to show data on Frontend with reusable include files.
- **/**
You can update env variables and composer.json to add custom packages.

#### Useful Functions:
- **XmlGenerator::feed()**
Generate sitemap.xml & rss.xml via a script file
- **HandleForm::upload(...)**
Upload file, resize image & add watermark
- **HandleForm::validate(...)**
Validation rules
- **HandleFile::write(...)** & **HandleFile::read(...)**
Write into and read from file
- **Helper::mailto(...)**
Send HTML Email
- **Helper::dd(...)**
Dumps a given variable along with some additional data
- **Helper::log(...)**
Logging custom data into file
- **Helper::csrf(...)**
Check Cross-site request forgery token
- **Helper::slug(...)**
Slugging string to make user-friendly URL
- **Cache::checkCache(...)**, **Cache::cache(...)** & **Cache::clearCache(...)**
Check existing cache, cache data and clear cache, by Memcached
- **UserInfo::current()**
Return current user information
- **UserInfo::info(...)**
Return selected user information
- **Event::listen(...)** & **Event::trigger(...)**
Register an event listener and trigger it when needed
- **R::runScript(...)**
Run R script (if you have R installed)
- **HttpClient::cURL(...)**
Run cURL script to send a request
- **CsvGenerator::exportCSV(...)**
Export a specific table from DB to a CSV file

#### Run Web App:
- Install docker and docker compose if needed
- Run `docker compose up --build -d`
- Run migrations with `docker compose exec php-mvc-app php src/Console/migrate.php`
- Open the web app at `http://localhost:8080`
- You can run any command via Docker container like `docker exec -it php-mvc-app composer update`
- You can run `docker compose down` to stop and remove containers
- Next time you can use `docker compose up -d`

#### Run Migrations:
- Start Docker first with `docker compose up -d`
- Run `docker compose exec php-mvc-app php src/Console/migrate.php`
- Add new migrations in `src/migrations.php`
- Migrations are tracked in the `migrations` database table, so completed migrations will not run again

#### Configuration:
- For non-local deployments, change `AUTH_COOKIE_SECRET` in `env.php`
- gRPC/protobuf dependencies are optional and commented out in Docker because the gRPC implementation is incomplete

#### Local URLs:
- Web app: `http://localhost:8080`
- gRPC nginx server: `http://localhost:8585`
- WebSocket nginx server: `http://localhost:9090`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form, and `blog-create-submit` for submit button ID. All form's buttons need to have constant `form-button` class.

#### Use Xdebug:
Xdebug installed via Docker, so it is ready to use, just need to start debug in your IDE or start listening to the Xdebug.

#### RESTful API sample

```php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_PORT => "8080",
    CURLOPT_URL => "http://localhost:8080/api/blog/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"category\": \"Laravel\", \"title\": \"Laravel 6.7.0 Released\", \"subtitle\": \"The Laravel team released a minor version v6.7.0 this week\", \"body\": \"<p>The Laravel team released a minor version v6.7.0 this week, with the latest features, changes, and fixes for 6.x</p>\", \"position\": \"2\"}",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer qwaeszrdxtfcygvuhbijnokmpl0987654321",
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
```

------------
Let me know about collaborating:
[eazaran@gmail.com](mailto:eazaran@gmail.com "eazaran@gmail.com")
