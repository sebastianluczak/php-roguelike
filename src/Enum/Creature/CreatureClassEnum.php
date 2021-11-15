<?php

namespace App\Enum\Creature;

use MyCLabs\Enum\Enum;

/**
 * @method static LEGENDARY()
 * @method static ELITE()
 * @method static NORMAL()
 */
class CreatureClassEnum extends Enum
{
    private const LEGENDARY = 150;
    private const ELITE = 120;
    private const NORMAL = 80;
}