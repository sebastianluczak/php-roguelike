<?php

namespace App\Model\AdventureLog;

// todo change to repository (it's not stupid idea after all)
use App\Service\LoggerService;

class AdventureLog implements AdventureLogInterface
{
    public const MAX_NUMBER_OF_MESSAGES = 12;

    /**
     * @var AdventureLogMessage[]
     */
    protected array $messages;

    protected LoggerService $loggerService;

    public function __construct(LoggerService $loggerService)
    {
        $this->loggerService = $loggerService;
        $this->messages = [];
    }

    public function getLines(): int
    {
        return count($this->messages);
    }

    /**
     * @return AdventureLogMessage[]
     */
    public function getNewMessages(): array
    {
        if (count($this->messages) >= self::MAX_NUMBER_OF_MESSAGES) {
            array_shift($this->messages);

            return $this->getNewMessages();
        }

        return array_reverse($this->messages);
    }

    public function addMessage(AdventureLogMessageInterface $message)
    {
        $this->messages[] = $message;
    }
}
