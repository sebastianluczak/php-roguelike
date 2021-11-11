<?php

namespace App\Message;

/**
 * Used at:
 * AdventureLogMessageHandler::__invoke()
 */
class AddAdventureLogMessage
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}