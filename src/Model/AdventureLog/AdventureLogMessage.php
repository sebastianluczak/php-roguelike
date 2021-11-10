<?php

namespace App\Model\AdventureLog;

class AdventureLogMessage implements AdventureLogMessageInterface
{
    protected string $message;

    public function __construct(string $message)
    {
        $timestamp = new \DateTime();
        $this->message = "[" . $timestamp->format(DATE_W3C) . "] " . $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChars(): int
    {
        return strlen($this->message);
    }
}