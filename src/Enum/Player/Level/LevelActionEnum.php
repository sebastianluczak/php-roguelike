<?php

namespace App\Enum\Player\Level;

use MyCLabs\Enum\Enum;

/**
 * @method static DECREASE()
 * @method static INCREASE()
 */
class LevelActionEnum extends Enum
{
    private const DECREASE = 0;
    private const INCREASE = 1;
}