<?php

use WebSocket\Client;

require_once __DIR__ . "/vendor/autoload.php";

function notifyNewUser(string $message) {
    $client = new Client("ws://localhost:8080");
    $msg = json_encode(["type" => "new_user", "message" => $message]);
    $client->send($msg);
    $client->close();
}

notifyNewUser("oi mundo");