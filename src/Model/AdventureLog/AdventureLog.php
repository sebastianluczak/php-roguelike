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
        if (count($this->messages) > 8) {
            array_shift($this->messages);

            // experimental todo check if works
            return $this->getNewMessages();
        }

        return array_reverse($this->messages);
    }

    public function addMessage(AdventureLogMessageInterface $message)
    {
        $this->messages[] = $message;

        $this->loggerService->log("[IMPORTANCE: ".$message->getImportance()."][NEW MESSAGE] -> " . $message->getMessage());
    }
}