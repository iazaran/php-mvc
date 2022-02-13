<?php
// Thanks for great codes: https://github.com/bakins/nginx-php-grpc

/**
 * Required headers
 */
header("grpc-status: 0");
header("Content-Type: application/grpc+proto");

use Controllers\API\BlogGrpcController;

/**
 * Configure auto-loading
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

$body = file_get_contents('php://input');

/**
 * https://github.com/grpc/grpc/blob/master/doc/PROTOCOL-HTTP2.md
 * each message is preceded by a flag that denotes if the
 * message is compressed and the length of the message
 */
$array = unpack("Cflag/Nlength", $body);

/**
 * Message begins after the prefix, and should have
 * the length defined in the prefix
 */
$message = substr($body, 5, $array['length']);

// Call appropriate method
$blogGrpcController = new BlogGrpcController();

// TODO: Make correct request and return correct response based on request type
$request = new \Blog\PostRequest();
try {
    $request->mergeFromString($message);
} catch (Exception $e) {
    echo $e->getMessage();
}
$response = $blogGrpcController->show($request);

$out = $response->serializeToString();

// Add grpc-status as a trailer in the nginx configuration
echo pack('CN', 0, strlen($out));
echo $out;
