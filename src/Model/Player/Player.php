<?php

namespace App\Model\Player;

use App\Model\Dialogue\DialogueInterface;
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
    protected bool $inDialogue = false;

    protected PlayerCoordinatesInterface $coordinates;
    protected PlayerHealthInterface $health;
    protected StatsInterface $stats;
    protected PlayerInventoryInterface $inventory;
    protected PlayerLevelInterface $level;
    protected ?DialogueInterface $currentDialogue;

    public function __construct(string $name, PlayerCoordinatesInterface $coordinates)
    {
        $this->name = $name;
        $this->health = new PlayerHealth();
        $this->level = new PlayerLevel();
        $this->coordinates = $coordinates;
        $this->stats = new Stats();
        $this->inventory = new PlayerInventory();
    }

    public function getName(): string
    {
        return $this->name;
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

    public function getCarryWeightLimit(): int
    {
        return (int) sqrt($this->getStats()->getStrength()) * 100 * $this->getMapLevel() + $this->getStats()->getStrength();
    }

    public function getInDialogue(): bool
    {
        return $this->inDialogue;
    }

    public function setInDialogue(bool $true): PlayerInterface
    {
        $this->inDialogue = $true;

        return $this;
    }

    public function setCurrentDialogue(?DialogueInterface $dialogue = null): void
    {
        $this->currentDialogue = $dialogue;
    }

    public function getCurrentDialogue(): ?DialogueInterface
    {
        return $this->currentDialogue;
    }

    public function isOverburdened(): bool
    {
        if ($this->getCarryWeightLimit() - $this->getInventory()->getItemsWeight() >= 0) {
            return false; // not overburdened
        }

        return true;
    }

    public function draw(): string
    {
        return '<color=bright-red>@</color>';
    }
}
