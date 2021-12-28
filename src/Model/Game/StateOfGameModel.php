<?php
declare(strict_types=1);
namespace App\Model\Game;

use App\Model\Player\PlayerCoordinatesInterface;
use App\Model\Stats\StatsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Stores state of Game
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

    /**
     * @return PlayerCoordinatesInterface
     */
    public function getPlayerCoordinates(): PlayerCoordinatesInterface
    {
        return $this->playerCoordinates;
    }

    /**
     * @return StatsInterface
     */
    public function getPlayerStats(): StatsInterface
    {
        return $this->playerStats;
    }
}