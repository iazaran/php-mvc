### Dockerized Pure PHP Composer based MVC Framework

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9b13bf034af64123821121d191acfaff)](https://app.codacy.com/manual/eazaran/php-mvc?utm_source=github.com&utm_medium=referral&utm_content=iazaran/php-mvc&utm_campaign=Badge_Grade_Dashboard)

> This project tries to cover some PHP features in a simple MVC structure with minimum installed composer packages. Then developers can use packages for specific requirements. Please add your ideas in Discussions, ask features or report bugs in issues.

🚧 WIP: gRPC server _(gRPC client not completed yet, and gRPC server is under working, so be careful about the bugs in gRPC)_

💡 TODO: WebSocket

#### Features:
**List of features related with structure**
- **public**
Contains the index.php file, to start application and configures auto-loading. Different server configurations added into this directory too. Finally, you can find the Sitemap generator that run after creation or updating a post.
- **public/assets**
Assets can contain your media files like images, audios & videos.
- **public/css** & **public/js**
Contains the styles & scripts _(After changes on these files, you can use minifier script to update minified versions, just run `docker-compose exec php-mvc-app php minifier.php`)_
- **public/feed**
There is a RSS generator in here and run after creation or updating a post.
- **src**
Contains migrations for a DB and routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware, Router & ...
- **src/Console**
Contains all scripts to run multiple times via Cron Jobs _(Scripts should be registered in /commands.php with custom timing, they will run by independent service in docker-compose)_
- **src/Controllers**
Controllers related with your routes separated for web and API. API folder includes both RESTful API and gRPC API. If you want use gRPC _(Under working now and server isn't ready to handle gRPC requests)_, you can find .proto file in API folder. Updating it will need to generate PHP codes again by
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
Slugify string to make user-friendly URL
- **Cache::checkCache(...)**, **Cache::cache(...)** & **Cache::clearCache(...)**
Check existed cache, cache data and clear cache, by Memcached
- **UserInfo::current()**
Return current user information
- **UserInfo::info(...)**
Return selected user information
- **Event::listen(...)** & **Event::trigger(...)**
Register an event listener and trigger it when needed

#### Run Web App:
- Install docker and docker-compose if needed
- Uncomment `// createTables();` in `src/routes`
- Run `docker-compose up --build -d`
- Open your browser and open web app in `localhost:8080` _(It will create tables related with migrations.php and then will comment `createTables();` automatically.)_
- You can run `docker-compose down` to stop and remove containers
- Next time you can use `docker-compose up -d`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form, and `blog-create-submit` for submit button ID. All form's buttons need to have constant `form-button` class.

#### RESTful API samples
Ready to use PostMan collection for RESTful API side: _(gRPC API side will be added later)_

[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/6224358/UV5agGTG)

------------
Let me know about collaborating:
[eazaran@gmail.com](mailto:eazaran@gmail.com "eazaran@gmail.com")
