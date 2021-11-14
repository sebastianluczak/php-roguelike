<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureGetsKilledMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class LootPickupHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreatureGetsKilledMessage $message)
    {
        $player = $message->getPlayer();
        $creature = $message->getCreature();

        $this->messageBus->dispatch(new AddAdventureLogMessage("[DEBUG] Creature current scale " . $message->getCreature()->getScale(), MessageClassEnum::DEVELOPER()));
    }
}