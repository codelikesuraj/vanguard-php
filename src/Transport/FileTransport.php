<?php

namespace Vanguard\Transport;

use Vanguard\Event;

class FileTransport
{
    public function __construct(private string $path = __DIR__ . '/../../logs/vanguard.log')
    {
    }

    public function send(Event $event): void
    {
        file_put_contents(
            filename: $this->path,
            data: json_encode($event->toArray(), JSON_PRETTY_PRINT) . PHP_EOL,
            flags: FILE_APPEND
        );
    }
}