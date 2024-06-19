<?php

namespace Websocket\App;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Websocket\App\database\Connection;

class UserCounter implements MessageComponentInterface
{
    protected $client;
    private \PDO $connection;

    public function __construct()
    {
        $this->client = new \SplObjectStorage();
        $this->connection = Connection::getInstance();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryParams);
        $this->client->attach($conn, (int) $queryParams["userId"]);
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
        $decodedMessage = json_decode($msg);
        $decodedMessage->user_id = $this->client[$from];
        $to = $decodedMessage->to;

        $statement = $this->connection->prepare("INSERT INTO user_messages (from_user_id, to_user_id, message_content) VALUES (:from_id, :to_id, :message_content)");
        $statement->execute([
            "from_id" => $this->client[$from],
            "to_id" => $to,
            "message_content" => $decodedMessage->message
        ]);

        foreach($this->client as $client) {
            var_dump(json_encode($decodedMessage));
            if($this->client[$client] === $to || $this->client[$client] === $decodedMessage->user_id) {
                var_dump($this->client[$client]);
                $client->send(json_encode($decodedMessage));
            }
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