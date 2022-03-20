<?php

/**
 * Random token to use as a CSRF with an expiration time
 */
session_start();
if (!isset($_SESSION['token']) || (isset($_SESSION['token-expire']) && time() > $_SESSION['token-expire'])) {
    $_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
    $_SESSION['token-expire'] = time() + 3600;
}

/**
 * Configure auto-loading
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Configure default timezone
 */
date_default_timezone_set(DEFAULT_TIMEZONE);

/**
 * Control error reporting via ENV vars
 */
ini_set('display_errors', DISPLAY_ERRORS);
error_reporting(ERROR_REPORTING);
