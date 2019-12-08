### Pure PHP Composer based MVC Framework

*Now I'm coding on Views to implement some examples & tests and then will update this README :-)*
*I will be happy if you want to help me to improve and complete this project. Please let me know with Issues (Feature request) or send me your ID to add you in Collaborators. It's my pleasure.*

> This project tries to cover most of PHP features in a simple MVC structure without installing composer packages. Then developers can use packages for specific requirements.

#### Features:
**List of features related with structure**
- **public**
Contains the index.php file, to start application and configures autoloading. Different server configurations added into this directory too. Finally you can find the Sitemap generator that run after creation or updating a post.
- **public/assets**
Assets can contain your media files like images, audios & videos.
- **public/css** & **public/js**
Contains the styles & scripts (I will add some tool to minify them)
- **public/feed**
There is a RSS generator in here and run after creation or updating a post.
- **src**
Contains all functions that used in codes, migrations for DB and also routes.
- **src/App**
Contains all classes that used in codes like PDO, Middleware & Router.
- **src/Controllers**
Controllers related with your routes separated for web and app.
- **src/Models**
Models related with controllers' DB queries & requirements.
- **src/Views**
Simple PHP files to show data on Frontend with reusable include files.
- **/**
You can update env variables and also composer to add custome packages.

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
- **csrf()**
Check Cross-site request forgery token
- **slug(...)**
Slugify string to make user friendly URL
- **currentUser()**
Return current user information

#### Run Web App:
Use in command line: `php -S localhost:8080 -t public/`

#### Use Ajax to send forms' data:
Consider a route for your form like `/blog/create`; now use `blog-create` as an ID for form and `blog-create-submit` for it's button. Form's buttons need to have constant `form-button` class.

------------

Contact me: [eazaran@gmail.com](mailto:eazaran@gmail.com "eazaran@gmail.com")