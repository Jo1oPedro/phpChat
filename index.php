<?php

require_once __DIR__ . "/vendor/autoload.php";

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Websocket\App\UserCounter;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new UserCounter()
        )
    ),
    8080
);

$server->run();