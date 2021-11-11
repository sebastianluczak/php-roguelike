<?php

namespace App\MessageHandler;

use App\Exception\GameOverException;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureEncounteredMessage;
use App\Message\CreatureGetsKilledMessage;
use App\Model\Creature\CreatureInterface;
use App\Service\CreatureService;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class LootPickupHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(CreatureGetsKilledMessage $message)
    {
        $dmg = $message->getCreature()->getDamage();
        if ($this->gameService->isDevMode()) {

        }
    }
}