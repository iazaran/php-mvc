### Dockerized Pure PHP Composer based MVC Framework

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9b13bf034af64123821121d191acfaff)](https://app.codacy.com/manual/eazaran/php-mvc?utm_source=github.com&utm_medium=referral&utm_content=iazaran/php-mvc&utm_campaign=Badge_Grade_Dashboard)

> This project tries to cover some PHP features in a simple MVC structure with minimum installed composer packages. Then developers can use packages for specific requirements. Please add your ideas in Discussions, ask features or report bugs in issues.

#### Features:
**List of features related with structure**
- **public**
Contains the index.php file, to start application and configures auto-loading. Different server configurations added into this directory too. Finally, you can find the Sitemap generator that runs after creation or updating a post.
- **public/assets**
Assets can contain your media files like images, audios & videos.
- **public/css** & **public/js**
Contains the styles & scripts _(After changes on these files, you can use minifier script to update minified versions, just run `docker-compose exec php-mvc-app php minifier.php`)_
- **public/feed**
There is a RSS generator in here and runs after creation or updating a post.
- **grpc**
A simple router for distribute the requests to different services. It is not working yet and I created an issue for it ⚠ and if you have an idea or a solution, only by PHP for both server and client sides, please add your solution in there.
- **websocket**
A WebSocket sample. You can open /websocket route in 2 tabs and test the websocket connection.
- **src**
Contains migrations for a DB and routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware, Router & ...
- **src/Console**
Contains all scripts to run multiple times via Cron Jobs _(Scripts should be registered in /commands.php with custom timing, they will run by independent service in docker-compose)_
- **src/Controllers**
Controllers related with your routes separated for web and API. API folder includes both RESTful API and gRPC API. If you want use gRPC _(gRPC client & server are not completed, and I ignored them for now. So be careful about the bugs in gRPC ⚠ and if you have an idea or a solution, only by PHP, please make a new discussion/issue/PR)_, you can find .proto file in API folder. Updating it will need to generate PHP codes again by
```
docker-compose exec php-mvc-app protoc -I=src/Controllers/API \
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
- Install docker and docker-compose if needed
- Uncomment `// createTables();` in `src/routes`
- Run `docker-compose up --build -d`
- Open your browser and open web app in `localhost:8080` _(It will create tables related with migrations.php and then will comment `createTables();` automatically.)_
- You can run any command via Docker container like `docker exec -it php-mvc-app composer update`
- You can run `docker-compose down` to stop and remove containers
- Next time you can use `docker-compose up -d`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form, and `blog-create-submit` for submit button ID. All form's buttons need to have constant `form-button` class.

#### RESTful API sample

```php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_PORT => "8080",
    CURLOPT_URL => "http://localhost:8080/api/blog/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"category\": \"Laravel\",\n\t\"title\": \"Laravel 6.7.0 Released\",\n\t\"subtitle\": \"The Laravel team released a minor version v6.7.0 this week, with the latest features, changes, and fixes for 6.x\",\n\t\"body\": \"&lt;p style=\\\"box-sizing: inherit; border: 0px solid; margin: 0px 0px 1.875rem; color: rgb(82, 82, 82); font-family: %26quot;Source Sans Pro%26quot;, system-ui, BlinkMacSystemFont, -apple-system, %26quot;Segoe UI%26quot;, Roboto, Oxygen, Ubuntu, Cantarell, %26quot;Fira Sans%26quot;, %26quot;Droid Sans%26quot;, %26quot;Helvetica Neue%26quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\\\">The Laravel team released a minor version v6.7.0 this week, with the latest features, changes, and fixes for 6.x:&lt;/p&gt;\",\n\t\"position\": \"2\"\n}",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer qwaeszrdxtfcygvuhbijnokmpl0987654321",
        "Content-Type: application/javascript"
    ),
));

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
