<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\GameOverMessage;
use App\Model\AdventureLog\AdventureLogMessage;
use App\Service\LeaderboardService;
use App\Service\PlayerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameOverHandler implements MessageHandlerInterface
{
    protected PlayerService $playerService;
    protected LeaderboardService $leaderboardService;
    protected MessageBusInterface $messageBus;

    public function __construct(PlayerService $playerService, LeaderboardService $leaderboardService, MessageBusInterface $messageBus)
    {
        $this->playerService = $playerService;
        $this->leaderboardService = $leaderboardService;
        $this->messageBus = $messageBus;
    }

    public function __invoke(GameOverMessage $message)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage("GAME OVER", MessageClassEnum::IMPORTANT()));
        $this->messageBus->dispatch(new AddAdventureLogMessage($message->getReason(), MessageClassEnum::IMPORTANT()));

        $this->leaderboardService->addEntry($message->getPlayer());
    }
}