<?php

namespace App\Message;

use App\Enum\MessageClassEnum;

/**
 * Used at:
 * AdventureLogMessageHandler::__invoke().
 */
class AddAdventureLogMessage
{
    protected string $message;
    protected ?MessageClassEnum $messageClass;

    public function __construct(string $message, MessageClassEnum $messageClass = null)
    {
        $this->message = $message;
        $this->messageClass = $messageClass;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getMessageClass(): ?MessageClassEnum
    {
        return $this->messageClass;
    }
}
