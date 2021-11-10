<?php

namespace App\MessageHandler;

use App\Message\GameOverMessage;
use App\Service\LeaderboardService;
use App\Service\PlayerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GameOverHandler implements MessageHandlerInterface
{
    protected PlayerService $playerService;
    protected LeaderboardService $leaderboardService;

    public function __construct(PlayerService $playerService, LeaderboardService $leaderboardService)
    {
        $this->playerService = $playerService;
        $this->leaderboardService = $leaderboardService;
    }

    public function __invoke(GameOverMessage $message)
    {
        $this->leaderboardService->addEntry($message->getPlayer());
    }
}