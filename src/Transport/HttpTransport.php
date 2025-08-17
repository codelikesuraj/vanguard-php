<?php

namespace Vanguard\Transport;

use Vanguard\Event;

class HttpTransport
{
    public function __construct(private string $endpoint)
    {
    }

    public function send(Event $event): void
    {
        $ch = curl_init($this->endpoint);
        $payload = json_encode($event->toArray());

        curl_setopt_array($ch, [
            CURLOPT_HEADER         => ['Content-Type: application/json'],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            error_log("Vanguard: cURL error: " . curl_error($ch));
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status < 200 || $status >= 300) {
            error_log("Vanguard: Server responsded with status " . $status);
        }

        curl_close($ch);
    }
}