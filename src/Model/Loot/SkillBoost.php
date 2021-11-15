<?php

namespace App\Model\Loot;

use App\Model\Stats\StatsInterface;

class SkillBoost
{
    protected int $healthAmount;

    public function __construct(int $mapLevel, StatsInterface $stats)
    {
        $this->healthAmount = random_int(1 * $mapLevel, 10 * $mapLevel + $stats->getLuck());

    }

    public function getHealthAmount(): int
    {
        return $this->healthAmount;
    }
}