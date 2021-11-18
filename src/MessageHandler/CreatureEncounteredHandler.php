<?php

namespace App\MessageHandler;

use App\Exception\GameOverException;
use App\Message\CreatureEncounteredMessage;
use App\Service\CreatureService;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatureEncounteredHandler implements MessageHandlerInterface
{
    protected CreatureService $creatureService;
    protected GameService $gameService;

    public function __construct(CreatureService $creatureService, GameService $gameService)
    {
        $this->creatureService = $creatureService;
        $this->gameService = $gameService;
    }

    public function __invoke(CreatureEncounteredMessage $message)
    {
        try {
            $this->creatureService->handleFight($message->getCreature(), $message->getPlayer());
        } catch (GameOverException $e) {
            $this->gameService->handleGameOver($e, $message->getPlayer());

            return;
        }
    }
}