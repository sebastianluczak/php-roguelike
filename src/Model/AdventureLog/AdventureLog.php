<?php

namespace App\Model\AdventureLog;

// todo change to repository (it's not stupid idea after all)
use App\Service\LoggerService;

class AdventureLog implements AdventureLogInterface
{
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
        $this->loggerService->log("Getting new messages. Messages count: " . count($this->messages));
        $messages = $this->messages;

        if (count($this->messages) > 8) {
            $this->loggerService->log("Message count over limit, shifting array");
            $this->loggerService->log("First element of the array is: ///->  " . $this->messages[0]->getMessage());
            $this->loggerService->log("Last element of the array is: ///->  " . end($this->messages)->getMessage());

            array_shift($this->messages);

            $this->loggerService->log("Array shifted. Current count of messages: " . count($this->messages));
            $this->loggerService->log("First element of the array is: ///->  " . $this->messages[0]->getMessage());
            $this->loggerService->log("Last element of the array is: ///->  " . end($this->messages)->getMessage());
        }

        return $messages;
    }

    public function addMessage(AdventureLogMessageInterface $message)
    {
        $this->messages[] = $message;
    }
}