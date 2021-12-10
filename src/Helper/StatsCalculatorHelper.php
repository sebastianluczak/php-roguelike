<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\Creature\CreatureClassEnum;
use App\Model\Stats\StatsInterface;

class StatsCalculatorHelper
{
    /**
     * @description calculates stat based on base value, used in AbstractCreature
     */
    public static function calculateStatWithBaseAndScale(int $baseStat, int $scale, CreatureClassEnum $creatureClass): int
    {
        return (int) (ceil($baseStat * ($creatureClass->getValue() / 100)) * sqrt($scale));
    }

    /**
     * @description Used in calculating create health during it's spawn (constructor of Creature itself)
     */
    public static function calculateCreatureHealthWithScale(int $scale, StatsInterface $stats, CreatureClassEnum $creatureClass): int
    {
        return (int) ceil($stats->getEndurance() * ceil($scale / 2) * ($creatureClass->getValue() / 100));
    }
}
