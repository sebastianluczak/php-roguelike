<?php

namespace App\Enum\Creature;

use MyCLabs\Enum\Enum;

/**
 * @method static BOSS()
 * @method static LEGENDARY()
 * @method static ELITE()
 * @method static NORMAL()
 */
class CreatureClassEnum extends Enum
{
    private const BOSS = 180;
    private const LEGENDARY = 140;
    private const ELITE = 120;
    private const NORMAL = 80;
}