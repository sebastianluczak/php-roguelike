<?php

namespace App\MessageHandler;

use App\Message\AdvanceDungeonProgressMessage;
use App\Service\MapService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AdvanceDungeonProgressHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected MapService $mapService;

    public function __construct(MessageBusInterface $messageBus, MapService $mapService)
    {
        $this->messageBus = $messageBus;
        $this->mapService = $mapService;
    }

    public function __invoke(AdvanceDungeonProgressMessage $message)
    {
        $this->mapService->increaseMapLevel();
        $this->mapService->createNewLevel();
        $message->getPlayer()->setMapLevel($this->mapService->getMapLevel());
    }
}
