<?php

/**
 * Define your constant variables
 */

// General
define('APP_ROOT', dirname(__FILE__));
const URL_ROOT = 'http://localhost:8080';
const COOKIE_DAYS = 180;
const CONTROLLER_FOLDER = 'Controllers\\';

// Debug
const DISPLAY_ERRORS = true;
const ERROR_REPORTING = E_ALL;

// Information
const TITLE = 'PHP MVC';
const SUBTITLE = 'A Pure PHP Composer based MVC Framework to Cover All Requirements!';
const THEME_COLOR = '#f0e6dc';
const MASK_COLOR = '#008044';

// Caching
const MEMCACHED_ENABLED = true;
const MEMCACHED_HOST = '127.0.0.1';
const MEMCACHED_PORT = 11211;
const CACHE_TIME_SEC = 86400;

// Blog
const DEFAULT_CATEGORY = 'General';
const RSS_COUNTS = 5;

// DB
const DB_TYPE = 'mysql';
const DB_HOST = 'localhost';
const DB_PORT = '3306';
const DB_USER = 'mvc_user';
const DB_PASS = 'mvc_Pass995!';
const DB_NAME = 'mvc_db';
// Keep this empty, if you don't use NoSQL DB like SQLite
const NO_SQL_ADDRESS = '';

// Email
const MAIL_MAILER = 'smtp';
const MAIL_HOST = 'smtp.mailtrap.io';
const MAIL_PORT = 2525;
const MAIL_USERNAME = '****e62857****';
const MAIL_PASSWORD = '****e46fed****';
const MAIL_ENCRYPTION = 'tls';
const MAIL_FROM = 'eazaran@gmail.com';
const MAIL_FROM_NAME = 'GiliApps';
const MAIL_CC = 'support@giliapps.com';
const MAIL_BCC = 'support@giliapps.com';
