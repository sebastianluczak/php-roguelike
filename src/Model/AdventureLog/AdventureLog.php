<?php

namespace App\Model\AdventureLog;

class AdventureLog implements AdventureLogInterface
{
    public const MAX_NUMBER_OF_MESSAGES = 12;

    /**
     * @var AdventureLogMessageInterface[]
     */
    protected array $messages;

    public function __construct()
    {
        $this->messages = [];
    }

    public function getLines(): int
    {
        return count($this->messages);
    }

    /**
     * @return AdventureLogMessageInterface[]
     */
    public function getNewMessages(): array
    {
        if (count($this->messages) >= self::MAX_NUMBER_OF_MESSAGES) {
            array_shift($this->messages);

            return $this->getNewMessages();
        }

        return array_reverse($this->messages);
    }

    public function addMessage(AdventureLogMessageInterface $message): void
    {
        $this->messages[] = $message;
    }
}
