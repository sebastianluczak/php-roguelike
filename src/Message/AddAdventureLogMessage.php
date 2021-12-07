<?php

namespace App\Message;

use App\Enum\MessageClassEnum;

/**
 * Used at:
 * AdventureLogMessageHandler::__invoke()
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

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getMessageClass(): ?string
    {
        return $this->messageClass;
    }
}
