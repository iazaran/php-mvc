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
const EMAIL_FROM = 'eazaran@gmail.com';
const EMAIL_CC = 'support@giliapps.com';
