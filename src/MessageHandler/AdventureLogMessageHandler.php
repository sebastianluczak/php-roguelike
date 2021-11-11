<?php

namespace App\MessageHandler;

use App\Message\AddAdventureLogMessage;
use App\Model\AdventureLog\AdventureLogMessage;
use App\Service\AdventureLogService;
use App\Service\GameService;
use App\Service\LoggerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AdventureLogMessageHandler implements MessageHandlerInterface
{
    protected LoggerService $loggerService;
    protected AdventureLogService $adventureLogService;
    protected GameService $gameService;

    public function __construct(LoggerService $loggerService, AdventureLogService $adventureLogService, GameService $gameService)
    {
        $this->loggerService = $loggerService;
        $this->adventureLogService = $adventureLogService;
        $this->gameService = $gameService;
    }

    public function __invoke(AddAdventureLogMessage $message)
    {
        try {
            $gameStartTime = $this->gameService->getInternalClockService()->getGameStartTime();

            $this->adventureLogService->getAdventureLog()->addMessage(
                new AdventureLogMessage($message->getMessage(), $gameStartTime, $message->getMessageClass())
            );
        } catch (\Exception $e) {
            // do nothing
        }
    }
}