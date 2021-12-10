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
        if (is_string($generatedName = $rpgFaker->name)) {
            $this->player = new Player($generatedName, new PlayerCoordinates(0, 0));
        } else {
            throw new \LogicException('Initialization of Player failed due to bug in name generator.');
        }
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}
