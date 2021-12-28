<?php

declare(strict_types=1);

namespace App\Model\Game;

use App\Model\Player\PlayerCoordinatesInterface;
use App\Model\Stats\StatsInterface;

/**
 * Stores state of Game.
 *
 * todo refactor to service
 * todo PoC
 */
class StateOfGameModel
{
    protected PlayerCoordinatesInterface $playerCoordinates;
    protected StatsInterface $playerStats;

    public function savePlayerCoordinates(PlayerCoordinatesInterface $coordinates)
    {
        $this->playerCoordinates = $coordinates;
    }

    public function savePlayerStates(StatsInterface $stats)
    {
        $this->playerStats = $stats;
    }

    public function getPlayerCoordinates(): PlayerCoordinatesInterface
    {
        return $this->playerCoordinates;
    }

    public function getPlayerStats(): StatsInterface
    {
        return $this->playerStats;
    }
}
