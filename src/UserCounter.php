<?php

namespace Websocket\App;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class UserCounter implements MessageComponentInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->client->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->client->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach($this->client as $client) {
            $client->send($msg);
        }
    }

    public function sendMessage(string $message) {
        /** @var ConnectionInterface $client */
        foreach($this->client as $client) {
            $messageToClient = new Message("new_message", $message);
            $client->send($messageToClient->getEncondedMessage());
        }
    }
}