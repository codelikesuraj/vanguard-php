<?php

namespace Vanguard;

class Event
{
    public array $context;
    public array $stacktrace;
    public int $line;
    public string $file;
    public string $fingerprint;
    public string $message;
    public string $type;
    public \DateTimeImmutable $timestamp;

    public function __construct(\Throwable $th, array $context = [])
    {
        $this->context = $context;
        $this->file = $th->getFile();
        $this->line = $th->getLine();
        $this->message = $th->getMessage();
        $this->stacktrace = $th->getTrace();
        $this->timestamp = new \DateTimeImmutable();
        $this->type = get_class($th);
        $this->fingerprint = hash(
            algo: 'sha256',
            data: $this->type . $this->message . $this->file . $this->line
        );
    }

    public function toArray(): array
    {
        return [
            'context'     => $this->context,
            'file'        => $this->file,
            'fingerprint' => $this->fingerprint,
            'line'        => $this->line,
            'message'     => $this->message,
            'stacktrace'  => $this->stacktrace,
            'timestamp'   => $this->timestamp->format(DATE_ATOM),
            'type'        => $this->type
        ];
    }
}