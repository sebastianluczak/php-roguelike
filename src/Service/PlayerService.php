<?php

namespace App\Service;

use App\Model\Player\Player;
use App\Model\Player\PlayerCoordinates;
use App\Model\Player\PlayerInterface;
use RPGFaker\RPGFaker;

class PlayerService
{
    protected PlayerInterface $player;
    protected LoggerService $loggerService;

    public function __construct()
    {
        $rpgFaker = new RPGFaker(['count' => 1, 'race' => 'human']);
        $this->player = new Player($rpgFaker->name, new PlayerCoordinates(0, 0));
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}
