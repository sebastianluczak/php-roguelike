<?php

namespace App\Model\Loot;

use App\Model\Stats\StatsInterface;

class SkillBoost
{
    protected int $armorAmount;
    protected int $damageAmount;
    protected int $healthAmount;
    protected int $experience;

    public function __construct(int $mapLevel, StatsInterface $stats)
    {
        $this->armorAmount = random_int(1, 2 * $mapLevel + $stats->getLuck());
        $this->damageAmount = random_int(1, 2 * $mapLevel + $stats->getLuck());
        $this->healthAmount = random_int(1 * $mapLevel, 10 * $mapLevel + $stats->getLuck());
        $this->experience = random_int(10 + $stats->getIntelligence(), 100 * sqrt($stats->getIntelligence()));
    }

    public function getArmorAmount(): int
    {
        return $this->armorAmount;
    }

    public function getDamageAmount(): int
    {
        return $this->damageAmount;
    }

    public function getHealthAmount(): int
    {
        return $this->healthAmount;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }
}