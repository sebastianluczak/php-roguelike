<?php

namespace App\Model\Loot;

use App\Model\Stats\StatsInterface;

class SkillBoost
{
    protected int $healthAmount;

    public function __construct(int $scale, StatsInterface $stats)
    {
        $this->healthAmount = random_int($scale, 10 * $scale + $stats->getLuck());
    }

    public function getHealthAmount(): int
    {
        return $this->healthAmount;
    }
}
