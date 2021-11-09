<?php

namespace App\Model\AdventureLog;

class AdventureLogMessage implements AdventureLogMessageInterface
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
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