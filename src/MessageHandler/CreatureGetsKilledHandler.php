<?php

namespace App\MessageHandler;

use App\Message\CreatureGetsKilledMessage;
use App\Service\LoggerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatureGetsKilledHandler implements MessageHandlerInterface
{
    protected LoggerService $loggerService;

    public function __construct(LoggerService $loggerService)
    {
        $this->loggerService = $loggerService;
    }

    public function __invoke(CreatureGetsKilledMessage $message)
    {
        $this->loggerService->logMessage($message);

        $player = $message->getPlayer();
        $creature = $message->getCreature();

        $gold = $creature->handleLoot();
        $player->addGoldAmount($gold->getAmount());
        $player->increaseExperience($creature->getExperience());
        $player->increaseKillCount();
    }
}