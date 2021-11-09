<?php

namespace App\MessageHandler;

use App\Message\AddAdventureLogMessage;
use App\Model\AdventureLog\AdventureLogMessage;
use App\Service\AdventureLogService;
use App\Service\LoggerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AdventureLogMessageHandler implements MessageHandlerInterface
{
    protected LoggerService $loggerService;
    protected AdventureLogService $adventureLogService;

    public function __construct(LoggerService $loggerService, AdventureLogService $adventureLogService)
    {
        $this->loggerService = $loggerService;
        $this->adventureLogService = $adventureLogService;
    }

    public function __invoke(AddAdventureLogMessage $message)
    {
        $this->adventureLogService->getAdventureLog()->addMessage(
            new AdventureLogMessage($message->getMessage())
        );
    }
}