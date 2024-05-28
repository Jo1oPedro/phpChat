<?php

namespace Websocket\App;

class Message
{
    public function __construct(
        private string $type,
        private string $content
    ) {}

    public function getType(): string {
        return $this->type;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getMessage(): array {
        return [
            "type" => $this->type,
            "message" => $this->content
        ];
    }

    public function getEncondedMessage(): string {
        return json_encode($this->getMessage());
    }
}