<?php

namespace App\Model\Player;

use App\Model\Stats\Stats;
use App\Model\Stats\StatsInterface;

class Player implements PlayerInterface
{
    protected string $name;
    protected int $damageScore;
    protected int $armorScore;
    protected int $health;
    protected int $gold;
    protected int $killCount;
    protected int $experience;
    protected PlayerCoordinatesInterface $coordinates;
    protected int $mapLevel = 1;
    protected StatsInterface $stats;

    public function __construct(string $name, PlayerCoordinatesInterface $coordinates, int $damageScore = 2, int $armorScore = 7, int $health = 100, int $gold = 0, int $experience = 0)
    {
        $this->name = $name;
        $this->damageScore = $damageScore;
        $this->armorScore = $armorScore;
        $this->health = $health;
        $this->gold = $gold;
        $this->killCount = 0;
        $this->experience = $experience;
        $this->coordinates = $coordinates;
        $this->stats = new Stats();
    }

    public function decreaseHealth(int $healthAmount)
    {
        $this->health = $this->health - $healthAmount;
    }

    public function getArmorScore(): int
    {
        if ($this->armorScore >= 80) {
            return 80;
        }

        return $this->armorScore;
    }

    public function setArmorScore(int $armorScore): PlayerInterface
    {
        $this->armorScore = $armorScore;

        return $this;
    }

    public function getDamageScore(): int
    {
        return $this->damageScore;
    }

    public function setDamageScore(int $damageScore): PlayerInterface
    {
        $this->damageScore = $damageScore;

        return $this;
    }

    public function increaseHealth(int $healthAmount)
    {
        $this->health = $this->health + $healthAmount;
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
        return $this->gold;
    }

    /**
     * @param int $gold
     * @return PlayerInterface
     */
    public function setGold(int $gold): PlayerInterface
    {
        $this->gold = $gold;

        return $this;
    }

    public function addGoldAmount(int $amount): PlayerInterface
    {
        $this->gold = $this->gold + $amount;

        return $this;
    }

    // todo add check for negative values
    public function decreaseGoldAmount(int $amount): PlayerInterface
    {
        $this->gold = $this->gold - $amount;

        return $this;
    }

    public function increaseDamage(int $amount): PlayerInterface
    {
        $this->damageScore = $this->damageScore + $amount;

        return $this;
    }

    public function increaseArmor(int $amount): PlayerInterface
    {
        $this->armorScore = $this->armorScore + $amount;

        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @return int
     */
    public function getKillCount(): int
    {
        return $this->killCount;
    }

    public function increaseKillCount(): int
    {
        $this->killCount = $this->killCount + 1;

        return $this->killCount;
    }

    public function getLevel(): int
    {
        return ceil((int)($this->experience / 100)) + 1;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function increaseExperience(int $amount)
    {
        $this->experience = $this->experience + $amount;
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

    public function draw(): string
    {
        return "<color=bright-red>@</color>";
    }
}