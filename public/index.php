<?php
/**
 * Random token to use as a CSRF with an expiration time
 */
session_start();
$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
$_SESSION['token-expire'] = time() + 3600;

/**
 * Configure autoloading
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Control errro reporting via ENV vars
 */
ini_set('display_errors', DISPLAY_ERRORS);
error_reporting(ERROR_REPORTING);
