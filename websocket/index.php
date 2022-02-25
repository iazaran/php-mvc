#!/usr/bin/php

<?php

use App\WebSocket;

spl_autoload_register(function ($class_name) {
    require_once '/var/www/src/' . str_replace('\\', '/', $class_name) . '.php';
});
require_once '/var/www/env.php';

// Create new websocket with address and port
$webSocket = new WebSocket();

// Run socket server
$webSocket->run();
