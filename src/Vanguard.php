<?php

namespace Vanguard;

use Vanguard\Transport\FileTransport;
use Vanguard\Transport\HttpTransport;

class Vanguard
{
    private static FileTransport|HttpTransport $transport;

    public static function init(FileTransport|HttpTransport $transport): void
    {
        self::$transport = $transport;

        set_error_handler([self::class, 'errorHandler']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleException(\Throwable $th): void
    {
        self::capture($th);
    }

    public static function handleError($severity, $message, $file, $line): void
    {
        $e = new \ErrorException($message, 0, $severity, $file, $line);
        self::capture($e);
    }

    public static function capture(\Throwable $th, array $context = []): void
    {
        $event = new Event($th, $context);
        self::$transport->send($event);
    }
}