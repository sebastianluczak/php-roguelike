<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\GameEffectMessage;
use App\Service\InternalClockService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameEffectHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected InternalClockService $internalClockService;

    public function __construct(MessageBusInterface $messageBus, InternalClockService $internalClockService)
    {
        $this->messageBus = $messageBus;
        $this->internalClockService = $internalClockService;
    }

    public function __invoke(GameEffectMessage $message)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage($message->getEvent()->getDescription(), MessageClassEnum::LOOT()));
        $this->internalClockService->addGameEventToEventLoop($message->getEvent());
    }
}
