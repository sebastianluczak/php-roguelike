<?php

namespace App\Enum\Player\Health;

use MyCLabs\Enum\Enum;

/**
 * @method static DECREASE()
 * @method static INCREASE()
 */
class HealthActionEnum extends Enum
{
    private const DECREASE = 0;
    private const INCREASE = 1;
}