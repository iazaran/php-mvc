### Pure PHP Composer based MVC Framework

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9b13bf034af64123821121d191acfaff)](https://app.codacy.com/manual/eazaran/php-mvc?utm_source=github.com&utm_medium=referral&utm_content=iazaran/php-mvc&utm_campaign=Badge_Grade_Dashboard)

> This project tries to cover most of PHP features in a simple MVC structure with minimum installed composer packages. Then developers can use packages for specific requirements. Please add your ideas in Discussions or report bugs in issues.

🚧 WIP: Events | TODO: Task Scheduling

#### Features:
**List of features related with structure**
- **public**
Contains the index.php file, to start application and configures auto-loading. Different server configurations added into this directory too. Finally, you can find the Sitemap generator that run after creation or updating a post.
- **public/assets**
Assets can contain your media files like images, audios & videos.
- **public/css** & **public/js**
Contains the styles & scripts _(After changes on these files, you can use minifier script to update minified versions, just run `php minifier.php`)_
- **public/feed**
There is a RSS generator in here and run after creation or updating a post.
- **src**
Contains migrations for a DB and routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware, Router & ...
- **src/Controllers**
Controllers related with your routes separated for web and API.
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
- **Helper::mailto(...)**
Send HTML Email
- **HandleForm::validate(...)**
Write into and read from file
- **HandleFile::write(...)** & **HandleFile::read(...)**
Validation rules
- **Helper::dd(...)**
Dumps a given variable along with some additional data
- **Helper::log(...)**
Logging custom data into file
- **Helper::csrf(...)**
Check Cross-site request forgery token
- **Helper::slug(...)**
Slugify string to make user-friendly URL
- **Cache::checkCache(...)**, **Cache::cache(...)** & **Cache::clearCache(...)**
Check existed cache, cache data and clear cache, by Memcached (needs installed and enabled Memcached)
- **UserInfo::current()**
Return current user information
- **UserInfo::info(...)**
Return selected user information

#### Run Web App:
- Create a MySQL DB and change credentials in `env.php`. Update other values like MEMCACHED... and MAIL...
- Ensure about your PHP version >= 8.0 and then run `composer install`
- Uncomment `// createTables();` in `src/routes` _(It will create tables related with migrations.php and then will comment `createTables();` automatically.)_
- Use command line to serve it on localhost:8080: `php -S localhost:8080 -t public/`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form, and `blog-create-submit` for submit button ID. All form's buttons need to have constant `form-button` class.

#### API samples
Ready to use PostMan collection for API side:

[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/6224358/UV5agGTG)

------------
Check Discussions for last added features and future ones. Add yours too.
Let me know about collaborating:
[eazaran@gmail.com](mailto:eazaran@gmail.com "eazaran@gmail.com")
