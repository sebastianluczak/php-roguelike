<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\GameEffectMessage;
use App\Message\PrayAtTheAltarMessage;
use App\Model\RandomEvent\AltarPrayerGameEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PrayAtTheAltarHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(PrayAtTheAltarMessage $message): void
    {
        // TODO add multiple events based on luck of player
        // do this as a strategy, because this service is 100% DI ready
        $player = $message->getPlayer();
        // FIXME this is just an example how to fire a event in event loop ("tick's" in ClockService)
        $event = new AltarPrayerGameEvent($player);
        $this->messageBus->dispatch(new GameEffectMessage($event));
    }
}
