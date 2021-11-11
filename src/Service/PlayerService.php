<?php

namespace App\Service;

use App\Model\Player\Player;
use App\Model\Player\PlayerCoordinates;
use App\Model\Player\PlayerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PlayerService
{
    protected array $inventory;
    protected MessageBusInterface $messageBus;
    protected PlayerInterface $player;
    protected LoggerService $loggerService;

    public function __construct(MessageBusInterface $messageBus, LoggerService $loggerService)
    {
        $this->messageBus = $messageBus;
        $this->loggerService = $loggerService;
        $this->player = new Player('Adventurer', New PlayerCoordinates(0, 0));
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}