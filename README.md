### Pure PHP Composer based MVC Framework

> This project tries to cover most of PHP features in a simple MVC structure without installing composer packages. Then developers can use packages for specific requirements.

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
Contains all functions that used in codes, migrations for a DB and routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware & Router.
- **src/Controllers**
Controllers related with your routes separated for web and app.
- **src/Models**
Models related with controllers' DB queries & requirements.
- **src/Views**
Simple PHP files to show data on Frontend with reusable include files.
- **/**
You can update env variables and composer.json to add custom packages.

#### Useful Functions:
- **feed()**
Generate sitemap.xml & rss.xml via a script file
- **upload(...)**
Upload file, resize image & add watermark
- **mailto(...)**
Send HTML Email
- **validate(...)**
Validation rules
- **dd(...)**
Dumps a given variable along with some additional data
- **csrf(...)**
Check Cross-site request forgery token
- **slug(...)**
Slugify string to make user-friendly URL
- **currentUser()**
Return current user information
- **userInfo(...)**
Return selected user information

#### Run Web App:
- Create a MySQL DB and change credentials in `env.php`
- Uncomment `// createTables();` in `src/routes` _(It will create tables related with migrations.php and then will comment `createTables();` automatically.)_
- Use command line to serve it on localhost:8080: `php -S localhost:8080 -t public/`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form, and `blog-create-submit` for submit button ID. All form's buttons need to have constant `form-button` class.

------------

Contact me: [eazaran@gmail.com](mailto:eazaran@gmail.com "eazaran@gmail.com")