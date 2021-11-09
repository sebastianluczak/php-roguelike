<?php

namespace App\Model\AdventureLog;

class AdventureLog implements AdventureLogInterface
{
    /**
     * @var AdventureLogMessage[]
     */
    protected array $messages;

    public function __construct()
    {
        $this->messages = [];
    }

    public function getNewLines(): int
    {
        return count($this->messages);
    }

    /**
     * @return AdventureLogMessage[]
     */
    public function getNewMessages(): array
    {
        $messages = $this->messages;
        $this->messages = [];

        return $messages;
    }

    public function addMessage(AdventureLogMessageInterface $message)
    {
        if (count($this->messages) > 3) {
            throw new \Exception("Ehmmk");
        }

        $this->messages[] = $message;
    }
}