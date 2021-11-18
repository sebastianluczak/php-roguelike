<?php

namespace App\Model\Player;

use App\Model\Player\Health\PlayerHealth;
use App\Model\Player\Health\PlayerHealthInterface;
use App\Model\Player\Inventory\PlayerInventory;
use App\Model\Player\Inventory\PlayerInventoryInterface;
use App\Model\Player\Level\PlayerLevel;
use App\Model\Player\Level\PlayerLevelInterface;
use App\Model\Stats\Stats;
use App\Model\Stats\StatsInterface;

class Player implements PlayerInterface
{
    protected string $name;
    protected int $killCount = 0;
    protected int $mapLevel = 1;

    protected PlayerCoordinatesInterface $coordinates;
    protected PlayerHealthInterface $health;
    protected StatsInterface $stats;
    protected PlayerInventoryInterface $inventory;
    protected PlayerLevelInterface $level;

    public function __construct(string $name, PlayerCoordinatesInterface $coordinates, int $health = 100)
    {
        $this->name = $name;
        $this->health = new PlayerHealth($health);
        $this->level = new PlayerLevel();
        $this->coordinates = $coordinates;
        $this->stats = new Stats();
        $this->inventory = new PlayerInventory($this->stats);
    }

    public function getPlayerName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getGold(): int
    {
        return $this->inventory->getGoldAmount();
    }

    public function getHealth(): PlayerHealthInterface
    {
        return $this->health;
    }

    public function getKillCount(): int
    {
        return $this->killCount;
    }

    public function increaseKillCount(): int
    {
        $this->killCount = $this->killCount + 1;

        return $this->killCount;
    }

    public function getLevel(): PlayerLevelInterface
    {
        return $this->level;
    }

    public function getCoordinates(): PlayerCoordinatesInterface
    {
        return $this->coordinates;
    }

    public function setCoordinates(PlayerCoordinatesInterface $coordinates): Player
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getMapLevel(): int
    {
        return $this->mapLevel;
    }

    public function setMapLevel(int $mapLevel): PlayerInterface
    {
        $this->mapLevel = $mapLevel;

        return $this;
    }

    public function getStats(): StatsInterface
    {
        return $this->stats;
    }

    public function getInventory(): PlayerInventoryInterface
    {
        return $this->inventory;
    }

    public function getInitiative(): float
    {
        return $this->getStats()->getPerception() * $this->getStats()->getPerception() - $this->getStats()->getPerception();
    }

    public function draw(): string
    {
        return "<color=bright-red>@</color>";
    }
}