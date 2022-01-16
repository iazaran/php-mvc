<?php

$server = new \Grpc\RpcServer([]);
$server->addHttp2Port('localhost:50051');
$server->handle(new \Controllers\API\BlogGrpcController());
$server->run();
