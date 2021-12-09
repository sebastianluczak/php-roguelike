<?php

namespace App\Message;

/**
 * Used at:
 * AdventureLogMessageHandler::__invoke().
 */
class AddAdventureLogMessage
{
    protected string $message;
    protected ?string $messageClass;

    public function __construct(string $message, string $messageClass = null)
    {
        $this->message = $message;
        $this->messageClass = $messageClass;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getMessageClass(): ?string
    {
        return $this->messageClass;
    }
}
